<?php defined('BASEPATH') or exit('No direct script access allowed');

class Education extends MY_Client_Cotroller
{

    public function __construct()
    {
        parent::__construct();

        if (!$this->is_client_logged_in()) {
            redirect('client/login');
            exit();
        }

        $this->perm_array = array('direct_access' => true);
        $this->load->model(array('education_model'));
    }

    public function add($candsid = false)
    {
        if ($this->input->is_ajax_request()) {

            $data['get_cands_details'] = $this->candidate_entity_pack_details($candsid);
            $data['states'] = $this->get_states();

            $data['mode_of_verification'] = $this->education_model->get_mode_of_verification(array('entity' => $data['get_cands_details']['entity_id'], 'package' => $data['get_cands_details']['package_id'], 'tbl_clients_id' => $data['get_cands_details']['clientid']));

            $data['universityname'] = $this->university_list();

            $data['qualification_name'] = $this->qualification_list();

            echo $this->load->view('client/education_add', $data, true);
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

    protected function education_com_ref($insert_id)
    {

        $name = COMPONENT_REF_NO['EDUCATION'];

        $educationnumber = $name . $insert_id;

        $field_array = array('education_com_ref' => $educationnumber);

        $update_auto_increament_id = $this->education_model->update_auto_increament_value($field_array, array('id' => $insert_id));

        return $educationnumber;
    }

    public function save_education()
    {
        if ($this->input->is_ajax_request()) {

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
                            'fill_by' => 1,
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
                            $config_array['education_id'] =  $education_com_ref ;
                            $retunr_de = $this->file_upload_library->file_upload_multiple_education($config_array, true);
                            //$retunr_de = $this->file_upload_multiple($config_array);
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
                                auto_update_tat_status($frm_details['candsid']);

                                auto_update_overall_status($frm_details['candsid']);

                               

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

}
?>