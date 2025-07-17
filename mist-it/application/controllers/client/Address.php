<?php defined('BASEPATH') or exit('No direct script access allowed');

class Address extends MY_Client_Cotroller
{

    public function __construct()
    {
        parent::__construct();

        if (!$this->is_client_logged_in()) {
            redirect('client/login');
            exit();
        }

        $this->perm_array = array('direct_access' => true);
        $this->load->model(array('addressver_model'));
    }

    public function add($candsid = false)
    {

        if ($this->input->is_ajax_request()) {

            $data['get_cands_details'] = $this->candidate_entity_pack_details($candsid);
            $data['states'] = $this->get_states();

            $data['mode_of_verification'] = $this->addressver_model->get_mode_of_verification(array('entity' => $data['get_cands_details']['entity_id'], 'package' => $data['get_cands_details']['package_id'], 'tbl_clients_id' => $data['get_cands_details']['clientid']));

            $this->load->model('clients_model');

            $data['client_components'] = $this->clients_model->get_entitypackages(array('entity' => $data['get_cands_details']['entity_id'], 'tbl_clients_id' => $data['get_cands_details']['clientid'], 'package' => $data['get_cands_details']['package_id']))[0];

            echo $this->load->view('client/address_add', $data, true);
        } else {
            echo "<h3>Something went wrong, please try again</h3>";
        }
    }

    protected function add_com_ref($insert_id)
    {

        $name = COMPONENT_REF_NO['ADDRESS'];

        $addressnumber = $name . $insert_id;

        $field_array = array('add_com_ref' => $addressnumber);

        $update_auto_increament_id = $this->addressver_model->update_auto_increament_value($field_array, array('id' => $insert_id));

        return $addressnumber;
    }

    public function save_address()
    {
        if ($this->input->is_ajax_request()) {

            $frm_details = $this->input->post();

                $this->form_validation->set_rules('clientid', 'Client', 'required');

                $this->form_validation->set_rules('candsid', 'Candidate', 'required');

                $this->form_validation->set_rules('address', 'Address', 'required');

                $this->form_validation->set_rules('city', 'City', 'required');

                $this->form_validation->set_rules('pincode', 'PIN code', 'required');
             

                $this->form_validation->set_rules('state', 'State', 'required');


            if ($this->form_validation->run() == false ) {

                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('', '');
            } else {

                $address_id = $this->addressver_model->check_address_exists_in_candidate(array('address'  => $frm_details['address'],'candsid' => $frm_details['candsid']));

                if(empty($address_id))
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
                            'fill_by' => 1,
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
                            auto_update_tat_status($frm_details['candsid']);

                            auto_update_overall_status($frm_details['candsid']);

                            $mode_of_verification = $this->addressver_model->get_mode_of_verification(array('tbl_clients_id' => $frm_details['clientid'],'entity' =>$frm_details['entity_id'],'package' =>$frm_details['package_id'])); 

                            if(!empty($mode_of_verification))
                            {
                               $mode_of_verification_value = json_decode($mode_of_verification[0]['mode_of_verification']);
                            }


                            if(isset($mode_of_verification_value->addrver) && ($mode_of_verification_value->addrver  == "digital"))
                            { 
                                $update = $this->addressver_model->upload_vendor_assign('addrver', array('vendor_digital_id' => 25, 'vendor_digital_list_mode' => 'digital', 'vendor_digital_assgined_on' => date(DB_DATE_FORMAT)), array('id' => $result, 'vendor_id =' => 0));

                                $update1 = $this->addressver_model->update_status('addrverres', array('verfstatus' => 1), array('addrverid' => $result));

                                $this->cli_address_invite_mail($result);

                                if($update && $update1)
                                {
                                
                                    $vendor_name =  $this->addressver_model->select_address_dt("vendors",array("vendor_name"), array("id" => 25));
                        
                                    $result_address_activity_data = $this->addressver_model->initiated_date_addrver_activity_data(array('created_on' =>date(DB_DATE_FORMAT),'created_by' => 1, 'comp_table_id' => $result, 'activity_type' => "Mist it", 'activity_status' => "Assign", 'remarks' => 'client has assigned the case to '.$vendor_name[0]['vendor_name']));
                                }
                            } 


                            $json_array['status'] = SUCCESS_CODE;

                            $json_array['message'] = 'Address Record Successfully Inserted';

                            $json_array['redirect'] = ADMIN_SITE_URL . 'address';

                           
                        } else {
                            $json_array['status'] = ERROR_CODE;

                            $json_array['message'] = 'Something went wrong,please try again';
                        }

                        
                    } else {
                        $json_array['status'] = ERROR_CODE;

                        $json_array['message'] = 'Something went wrong,please try again';
                    }
                }
                else {
                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Address Already exits '.$frm_details['address'];
                }       
            }
            echo_json($json_array);
        }
    }

    protected function cli_address_invite_mail($result)
    {
        $this->load->library('email');
        $this->email->address_invite_mail_sms($result);
    }

}
?>
