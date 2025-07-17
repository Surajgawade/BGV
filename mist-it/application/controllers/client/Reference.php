<?php defined('BASEPATH') or exit('No direct script access allowed');
class Reference extends MY_Client_Cotroller
{

    public function __construct()
    {
        parent::__construct();

        if (!$this->is_client_logged_in()) {
            redirect('client/login');
            exit();
        }

        $this->perm_array = array('direct_access' => true);
        $this->load->model(array('reference_verificatiion_model'));
    }

    public function add($candsid = false)
    {
        if ($this->input->is_ajax_request()) {

            $data['get_cands_details'] = $this->candidate_entity_pack_details($candsid);
            $data['states'] = $this->get_states();

            $data['mode_of_verification'] = $this->reference_verificatiion_model->get_mode_of_verification(array('entity' => $data['get_cands_details']['entity_id'], 'package' => $data['get_cands_details']['package_id'], 'tbl_clients_id' => $data['get_cands_details']['clientid']));

            echo $this->load->view('client/reference_add', $data, true);
        } else {
            echo "<h3>Something went wrong, please try again</h3>";
        }
    }

    protected function reference_com_ref($insert_id)
    {

        $name = COMPONENT_REF_NO['REFERENCES'];

        $referencenumber = $name . $insert_id;

        $field_array = array('reference_com_ref' => $referencenumber);

        $update_auto_increament_id = $this->reference_verificatiion_model->update_auto_increament_value($field_array, array('id' => $insert_id));

        return $referencenumber;
    }

    public function save_reference()
    {
        if ($this->input->is_ajax_request()) {

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
                            'fill_by' => 1,
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

}
