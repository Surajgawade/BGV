<?php defined('BASEPATH') or exit('No direct script access allowed');

class Credit_report extends MY_Client_Cotroller
{
    public function __construct()
    {
        parent::__construct();

        if (!$this->is_client_logged_in()) {
            redirect('client/login');
            exit();
        }

        $this->perm_array = array('direct_access' => true);
        $this->load->model(array('Credit_report_model'));
    }

    public function add($candsid = false)
    {
        if ($this->input->is_ajax_request()) {

            $data['get_cands_details'] = $this->candidate_entity_pack_details($candsid);
            $data['states'] = $this->get_states();

            $data['mode_of_verification'] = $this->Credit_report_model->get_mode_of_verification(array('entity' => $data['get_cands_details']['entity_id'], 'package' => $data['get_cands_details']['package_id'], 'tbl_clients_id' => $data['get_cands_details']['clientid']));

            echo $this->load->view('client/credit_report_add', $data, true);
        } else {
            echo "<h3>Something went wrong, please try again</h3>";
        }
    }

    protected function credit_report_com_ref($insert_id)
    {

        $name = COMPONENT_REF_NO['CREDIT_REPORT'];

        $credit_report_number = $name . $insert_id;

        $field_array = array('credit_report_com_ref' => $credit_report_number);

        $update_auto_increament_id = $this->Credit_report_model->update_auto_increament_value($field_array, array('id' => $insert_id));

        return $credit_report_number;
    }

    public function save_credit_report()
    {
        if ($this->input->is_ajax_request()) {

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
                            'fill_by' => 1,
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
                        auto_update_tat_status($frm_details['candsid']);

                        auto_update_overall_status($frm_details['candsid']);

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

}
