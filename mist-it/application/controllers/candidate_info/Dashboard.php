
<?php defined('BASEPATH') or exit('No direct script access allowed');
class Dashboard extends MY_Candidate_Cotroller
{

    public function __construct()
    {
        parent::__construct();

        if (!$this->is_candidate_logged_in()) {
            redirect('candidate_info/login');
            exit();
        }

        $this->perm_array = array('direct_access' => true);

    }
    public function index()
    {

        $data['header_title'] = "Dashbord";

        $this->load->view('candidate_info/header', $data);

        $this->load->view('candidate_info/dashboard');

        $this->load->view('candidate_info/footer');

    }

    public function candidate_submit_component()
    {

        $json_array = array();

        if ($this->input->is_ajax_request()) {

            $this->load->model('candidate_info/Candidate_login_model');

            $frm_details = $this->input->post();

            $client_id = $frm_details['clientid'];

            $client_email_id = $this->Candidate_login_model->select_client_email_id(array('client_id' => $client_id));

            $client_manager = $this->Candidate_login_model->select_client_manager_details($client_id);

            $user_profile_info = $this->Candidate_login_model->select_user_info($client_manager[0]['clientmgr']);

            $email_tmpl_data['to_emails'] = $client_email_id[0]['email_id'];

            $email_tmpl_data['cc_emails'] = $user_profile_info[0]['email'];

            $email_tmpl_data['component_name'] = $frm_details['component_name'];

            if ($email_tmpl_data['component_name'] == "Address") {
                $email_tmpl_data['component_details'] = array("Stay From" => $frm_details['stay_from'], "Stay To" => $frm_details['stay_to'], "Address Type" => $frm_details['address_type'], "Street Address" => $frm_details['address'], "City" => $frm_details['city'], "Pincode" => $frm_details['pincode'], "State" => $frm_details['state']);
            }
            if ($email_tmpl_data['component_name'] == "Employment") {
                if (strpos($frm_details['empfrom'], "-") == 4 || strpos($frm_details['empto'], "-") == 2) {

                    $employed_from = date("d-m-Y", strtotime($frm_details['empfrom']));
                } else {
                    $employed_from = $frm_details['empfrom'];
                }

                if (strpos($frm_details['empto'], "-") == 4 || strpos($frm_details['empto'], "-") == 2) {

                    $employed_to = date("d-m-Y", strtotime($frm_details['empto']));
                } else {
                    $employed_to = $frm_details['empto'];
                }

                $email_tmpl_data['component_details'] = array("Company Name" => $frm_details['nameofthecompany'], "Deputed Company" => $frm_details['deputed_company'], "Previous Employee Code" => $frm_details['empid'], "Employment Type" => $frm_details['employment_type'], "Employment From" => $employed_from, "Employment To" => $employed_to, "Designation" => $frm_details['designation'], "Remuneration" => $frm_details['remuneration'], "Reason for Leaving" => $frm_details['reasonforleaving'], "Company Contact Name" => $frm_details['compant_contact_name'], "Company Contact Designation" => $frm_details['compant_contact_designation'], "Company Contact Email ID" => $frm_details['compant_contact_email'], "Company Contact No" => $frm_details['compant_contact'], "Street Address" => $frm_details['locationaddr'], "City" => $frm_details['citylocality'], "State" => $frm_details['state'], "Pincode" => $frm_details['pincode'], "Reporting Manager Name" => $frm_details['r_manager_name'], "Reporting Manager No." => $frm_details['r_manager_no'], "Reporting Manager Designation" => $frm_details['r_manager_designation'], "Reporting Manager Email Id" => $frm_details['r_manager_email']);
            }
            if ($email_tmpl_data['component_name'] == "Education") {

                $this->load->model('Education_model');
                $university_name = $this->Education_model->select_university(array('id' => $frm_details['university_board']));
                $qualification_name = $this->Education_model->select_qualification(array('id' => $frm_details['qualification']));
                $email_tmpl_data['component_details'] = array("School Collage" => $frm_details['school_college'], "University Name" => $university_name[0]['universityname'], "Grade Class Mark" => $frm_details['grade_class_marks'], "Qualification" => $qualification_name[0]['qualification'], "Major" => $frm_details['major'], "Course Start Date" => $frm_details['course_start_date'], "Course End Date" => $frm_details['course_end_date'], "Course End Date" => $frm_details['course_end_date'], "Month of Passing" => $frm_details['month_of_passing'], "Year of Passing" => $frm_details['year_of_passing'], "Roll No" => $frm_details['roll_no'], "Enrollment No" => $frm_details['enrollment_no'], "PRN No" => $frm_details['PRN_no'], "Document Provided" => implode(',', $frm_details['documents_provided']), "City" => $frm_details['city'], "State" => $frm_details['state']);

            }

            if ($email_tmpl_data['component_name'] == "Reference") {
                $email_tmpl_data['component_details'] = array("Name of Reference" => $frm_details['name_of_reference'], "Designation" => $frm_details['designation'], "Contact No" => $frm_details['contact_no'], "Email ID" => $frm_details['email_id']);

            }
            if ($email_tmpl_data['component_name'] == "Court") {
                $email_tmpl_data['component_details'] = array("Address Type" => $frm_details['address_type'], "Street Address" => $frm_details['street_address'], "City" => $frm_details['city'], "State" => $frm_details['state'], "Pincode" => $frm_details['pincode']);

            }
            if ($email_tmpl_data['component_name'] == "Global Database") {
                $email_tmpl_data['component_details'] = array("Address Type" => $frm_details['address_type'], "Street Address" => $frm_details['street_address'], "City" => $frm_details['city'], "State" => $frm_details['state'], "Pincode" => $frm_details['pincode']);

            }
            if ($email_tmpl_data['component_name'] == "PCC") {
                $email_tmpl_data['component_details'] = array("Address Type" => $frm_details['address_type'], "Street Address" => $frm_details['street_address'], "City" => $frm_details['city'], "State" => $frm_details['state'], "Pincode" => $frm_details['pincode'], "Reference" => $frm_details['references'], "Reference No." => $frm_details['references_no']);

            }
            if ($email_tmpl_data['component_name'] == "Identity") {
                $email_tmpl_data['component_details'] = array("Document Submitted" => $frm_details['doc_submited'], "Id Number" => $frm_details['id_number']);

            }
            if ($email_tmpl_data['component_name'] == "Credit Report") {
                $email_tmpl_data['component_details'] = array("Document Submitted" => $frm_details['doc_submited'], "Id Number" => $frm_details['id_number'], "Street Address" => $frm_details['street_address'], "City" => $frm_details['city'], "State" => $frm_details['state'], "Pincode" => $frm_details['pincode']);

            }

            $email_tmpl_data['comp_ref_no'] = $frm_details['comp_ref_no'];

            $email_tmpl_data['subject'] = $frm_details['CandidateName'] . " - Details Updated";

            $this->load->library('email');

            $result = $this->email->candidate_submit_comp($email_tmpl_data);

            if ($result) {

                $json_array['message'] = 'Email Send Successfully';

                $json_array['status'] = SUCCESS_CODE;

            } else {
                $json_array['message'] = 'E-Mail Not Send Successfully';

                $json_array['status'] = ERROR_CODE;
            }

        } else {
            $json_array['message'] = 'Something went wrong, please try again';

            $json_array['status'] = ERROR_CODE;
        }

        echo_json($json_array);
    }

}