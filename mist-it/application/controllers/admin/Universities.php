<?php defined('BASEPATH') or exit('No direct script access allowed');
class Universities extends MY_Controller
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
        $this->perm_array = array('page_id' => 10);

        $this->load->model(array('university_model'));
    }

    public function index()
    {
        $data['header_title'] = "Universities Lists";

        $this->load->view('admin/header', $data);

        $this->load->view('admin/university_list');

        $this->load->view('admin/footer');
    }

    public function fetch_univer_list()
    {
        if ($this->input->is_ajax_request()) {
            $results = $this->university_model->select(false, array('*'), array());

            foreach ($results as $key => $value) {

                $vendor_id = $this->university_model->select_table('vendors',array('vendor_name'), array('id'=> $value['vendor_id'])); 
                if(!empty($vendor_id))
                {
                    $vendor_name =  ucwords($vendor_id[0]['vendor_name']);
                }
                else
                {
                    $vendor_name =   "";
                }
                 if( $value['year_of_passing'] != 'null')
                {
                    $year_of_passing =   $value['year_of_passing'];
                }
                else
                {
                    $year_of_passing =   "";
                }
                 if($value['url_link'] != 'null')
                {
                    $url_link =  $value['url_link'];
                }
                else
                {
                    $url_link =   "";
                }
                $id = encrypt($value['id']);
                $status = ($value['status'] == 1) ? 'Active' : 'Inactive';
                $data_arry[] = array('universityname' => ucwords($value['universityname']),
                    'vendor_id' => $vendor_name,
                    'year_of_passing' => $year_of_passing,
                    'url_link' =>$url_link,
                    'created_on' => convert_db_to_display_date($value['created_on'], DB_DATE_FORMAT),
                    'edit' => ADMIN_SITE_URL . 'universities/view_details/' . $id,
                    'status' => $status,
                );
            }

            $json_array = array("draw" => $data_arry, 'status' => SUCCESS_CODE);

            echo_json($json_array);
        } else {
            permission_denied();
        }
    }

    public function view_details($university_id = '')
    {

        if (!empty($university_id) && decrypt($university_id)) {
            $univers_details = $this->university_model->select(true, array('*'), array('id' => decrypt($university_id)));

            if (!empty($univers_details)) {
                $data['univers_details'] = $univers_details;
                $this->load->model('education_model');
                $data['university_attachments'] = $this->education_model->select_file_universtity('university_master_image',array('id', 'file_name', 'status'), array('university_id' => $university_id, 'status' => 1));


                $data['vendor_list'] = $this->vendor_list_university('eduver');

                $this->load->view('admin/header', $data);

                $this->load->view('admin/university_edit');

                $this->load->view('admin/footer');
            } else {
                show_404();
            }
        } else {
            $this->index();
        }
    }
      

    public function vendor_list_university($component_name)
    {
        $lists = $this->university_model->select_vendor_list($component_name);
        return convert_to_single_dimension_array($lists, 'id', 'vendor_name');
    }  

    public function update_university()
    {
        if ($this->input->is_ajax_request()) {
            $json_array = array();

            $this->form_validation->set_rules('universityname', 'University Name', 'required');

            if ($this->form_validation->run() == false) {
                $json_array['status'] = false;

                $json_array['message'] = validation_errors('', '');
            } else {
                $frm_details = $this->input->post();

                $fields = array('vendor_id' => $frm_details['vendor_name'],
                    'vendor_id' => $frm_details['vendor_name'],
                    'year_of_passing' => $frm_details['yop'],
                    'url_link' => $frm_details['url_link'],
                    'universityname' => ucwords($frm_details['universityname']),
                    'universityname' => ucwords($frm_details['universityname']),
                    'modified_by' => $this->user_info['id'],
                    'modified_on' => date(DB_DATE_FORMAT),
                    'status' => $frm_details['status'],
                );

                $where = array('id' => $frm_details['id']);
             

                $result = $this->university_model->save($fields, $where);

    
                $error_msgs = $file_array = array();

                $file_upload_path = SITE_BASE_PATH . UNIVERSITY_PIC;

                if (!folder_exist($file_upload_path)) {
                    mkdir($file_upload_path, 0777);
                }

             
                if (!empty($_FILES['university_image']['name'][0])) {
                    $files_count = count($_FILES['university_image']['name']);

                    for ($i = 0; $i < $files_count; $i++) {
                        $file_name = $_FILES['university_image']['name'][$i];

                        $file_info = pathinfo($file_name);

                        $new_file_name = preg_replace('/[[:space:]]+/', '_', $file_info['filename']);

                        $new_file_name = $new_file_name . '_' . DATE(UPLOAD_FILE_DATE_FORMAT_FILE_NAME);

                        $new_file_name = str_replace('.', '_', $new_file_name);

                        $new_file_name = preg_replace('/[^A-Za-z0-9\-]/', '_', $new_file_name);

                        $file_extension = $file_info['extension'];

                        $new_file_name = $new_file_name . '.' . $file_extension;

                        $_FILES['attchment']['name'] = $new_file_name;

                        $_FILES['attchment']['tmp_name'] = $_FILES['university_image']['tmp_name'][$i];

                        $_FILES['attchment']['error'] = $_FILES['university_image']['error'][$i];

                        $_FILES['attchment']['size'] = $_FILES['university_image']['size'][$i];

                        $config['upload_path'] = $file_upload_path;

                        $config['file_name'] = $new_file_name;

                        $config['allowed_types'] = 'jpeg|jpg|png';

                        $config['file_ext_tolower'] = true;

                        $config['remove_spaces'] = true;

                        $config['max_size'] = BULK_UPLOAD_MAX_SIZE_MB * 1000;

                        $this->load->library('upload', $config);

                        $this->upload->initialize($config);

                        if ($this->upload->do_upload('attchment')) {
                            array_push($file_array, array(
                                'file_name' => $new_file_name,
                                'real_file_name' => $file_name,
                                'university_id' => $frm_details['id'],
                                'status' => 1)
                            );
                        } else {
                            array_push($error_msgs, $this->upload->display_errors('', ''));

                            $json_array['status'] = ERROR_CODE;

                            $json_array['e_message'] = implode('<br>', $error_msgs);

                        }
                    }
                    if (!empty($file_array)) {
                        $this->university_model->uploaded_files($file_array);
                    }
                }               
                if ($result) {

                    $json_array['message'] = 'Details Updated Successfully';

                    $json_array['redirect'] = ADMIN_SITE_URL . 'universities';

                    $json_array['status'] = SUCCESS_CODE;
                } else {
                    $json_array['message'] = 'Something went wrong, please try again';

                    $json_array['status'] = ERROR_CODE;
                }
            }

            echo_json($json_array);
        }
    }

    public function delete($university_id)
    {
        if ($this->input->is_ajax_request()) {
            $json_array['message'] = 'Something went wrong, please try again';

            $json_array['status'] = ERROR_CODE;
            $university_id = decrypt($university_id);

            if (!empty($university_id)) {
                $univers_details = $this->university_model->select(true, array('*'), array('id' => $university_id));

                if (!empty($univers_details)) {
                    $fields = array('modified_by' => $this->user_info['id'],
                        'modified_on' => date(DB_DATE_FORMAT),
                        'status' => STATUS_DEACTIVE,
                    );

                    if ($this->university_model->save($fields, array('id' => $university_id))) {
                        $json_array['message'] = 'Details Updated Successfully';

                        $json_array['redirect'] = ADMIN_SITE_URL . 'universities';

                        $json_array['status'] = SUCCESS_CODE;
                    } else {
                        $json_array['message'] = 'Something went wrong, please try again';

                        $json_array['status'] = ERROR_CODE;
                    }
                }
            }
            echo_json($json_array);
        }
    }

    public function add()
    {
        $data['header_title'] = "Universities Add";

        $this->load->view('admin/header', $data);

        $this->load->view('admin/university_add');

        $this->load->view('admin/footer');
    }

    public function add_university()
    {
        if ($this->input->is_ajax_request()) {
            $json_array = array();

            $this->form_validation->set_rules('universityname', 'University Name', 'required|is_unique[university_master.universityname]');
            if ($this->form_validation->run() == false) {
                $json_array['status'] = false;

                $json_array['message'] = validation_errors('', '');
            } else {
                $frm_details = $this->input->post();

                $fields = array('universityname' => strtolower($frm_details['universityname']),
                    'created_by' => $this->user_info['id'],
                    'created_on' => date(DB_DATE_FORMAT),
                    'status' => STATUS_ACTIVE,
                );
                if ($this->university_model->save($fields)) {
                    $json_array['message'] = 'University Added Successfully';

                    $json_array['redirect'] = ADMIN_SITE_URL . 'universities';

                    $json_array['status'] = SUCCESS_CODE;
                } else {
                    $json_array['message'] = 'Something went wrong, please try again';

                    $json_array['status'] = ERROR_CODE;
                }
            }

            echo_json($json_array);
        }
    }

    //fake university

    public function fake_university()
    {
        $data['header_title'] = "Fake University List";

        $this->load->view('admin/header', $data);

        $this->load->view('admin/fake_university_list');

        $this->load->view('admin/footer');
    }

    public function fetch_fake_univer_list()
    {
        if ($this->input->is_ajax_request()) {
            $results = $this->university_model->select_fake_university(false, array('*'), array());

            foreach ($results as $key => $value) {

                $id = encrypt($value['id']);
                $status = ($value['status'] == 1) ? 'Active' : 'Inactive';
                $data_arry[] = array('u_name' => ucwords($value['u_name']),
                    'state' => ucwords($value['u_state']),
                    'created_on' => convert_db_to_display_date($value['created_on'], DB_DATE_FORMAT),
                    'edit' => ADMIN_SITE_URL . 'fake_university/view_fake_university/' . $id,
                    'status' => $status,
                );
            }

            $json_array = array("draw" => $data_arry, 'status' => SUCCESS_CODE);
            echo_json($json_array);
        } else {
            permission_denied();
        }
    }

    public function add_fake_university()
    {
        $data['header_title'] = "Add Fake University";
        $data['states'] = $this->get_states();

        $this->load->view('admin/header', $data);

        $this->load->view('admin/fake_university_add');

        $this->load->view('admin/footer');
    }
    public function save_fake_university()
    {
        if ($this->input->is_ajax_request()) {
            $json_array = array();

            $this->form_validation->set_rules('u_name', 'University Name', 'required|is_unique[fake_university.u_name]');

            $this->form_validation->set_rules('u_address', 'University Address', 'required');

            $this->form_validation->set_rules('u_state', 'State', 'required');

            $this->form_validation->set_rules('u_url', 'URL', 'required');

            if ($this->form_validation->run() == false) {
                $this->form_validation->set_error_delimiters('', '');

                $json_array['status'] = false;

                $json_array['message'] = validation_errors('', '');
            } else {
                $frm_details = $this->input->post();

                $fields = array('u_name' => strtolower($frm_details['u_name']),
                    'u_address' => strtolower($frm_details['u_address']),
                    'u_state' => $frm_details['u_state'],
                    'u_url' => $frm_details['u_url'],
                    'created_by' => $this->user_info['id'],
                    'created_on' => date(DB_DATE_FORMAT),
                    'status' => STATUS_ACTIVE,
                );

                if ($this->university_model->insert_fake_university($fields)) {
                    $json_array['message'] = 'Record Inserted Successfully';

                    $json_array['redirect'] = ADMIN_SITE_URL . 'universities/fake_university';

                    $json_array['status'] = SUCCESS_CODE;
                } else {
                    $json_array['message'] = 'Something went wrong, please try again';

                    $json_array['status'] = ERROR_CODE;
                }
            }
            echo_json($json_array);
        }
    }

    public function view_fake_university($id = false)
    {
        $details = $this->university_model->select_fake_university(true, array('id', 'u_name', 'u_address', 'u_state', 'u_url'), array('id' => decrypt($id)));

        if (!empty($details)) {
            $data['header_title'] = "Edit Fake University";
            $data['states'] = $this->get_states();
            $data['univers_details'] = $details;

            $this->load->view('admin/header', $data);

            $this->load->view('admin/fake_university_edit');

            $this->load->view('admin/footer');
        } else {
            show_404();
        }
    }

    public function update_fake_university()
    {
        if ($this->input->is_ajax_request()) {
            $json_array = array();

            $this->form_validation->set_rules('update_id', 'ID', 'required');

            $this->form_validation->set_rules('u_address', 'University Address', 'required');

            $this->form_validation->set_rules('u_state', 'State', 'required');

            $this->form_validation->set_rules('u_url', 'URL', 'required');

            if ($this->form_validation->run() == false) {
                $this->form_validation->set_error_delimiters('', '');

                $json_array['status'] = false;

                $json_array['message'] = validation_errors('', '');
            } else {
                $frm_details = $this->input->post();

                $fields = array('u_address' => strtolower($frm_details['u_address']),
                    'u_state' => $frm_details['u_state'],
                    'u_url' => $frm_details['u_url'],
                    'modified_by' => $this->user_info['id'],
                    'modified_on' => date(DB_DATE_FORMAT),
                    'status' => STATUS_ACTIVE,
                );

                if ($this->university_model->insert_fake_university($fields, array('id' => $frm_details['update_id']))) {

                    $json_array['message'] = 'Record Updated Successfully';

                    $json_array['redirect'] = ADMIN_SITE_URL . 'universities/fake_university';

                    $json_array['status'] = SUCCESS_CODE;

                } else {
                    $json_array['message'] = 'Something went wrong, please try again';

                    $json_array['status'] = ERROR_CODE;
                }
            }
            echo_json($json_array);
        }
    }

    public function delete_fake_university($university_id)
    {
        if ($this->input->is_ajax_request()) {
            $json_array['message'] = 'Something went wrong, please try again';

            $json_array['status'] = ERROR_CODE;
            $university_id = decrypt($university_id);

            if (!empty($university_id)) {
                $univers_details = $this->university_model->select_fake_university(true, array('*'), array('id' => $university_id));

                if (!empty($univers_details)) {
                    $fields = array('modified_by' => $this->user_info['id'],
                        'modified_on' => date(DB_DATE_FORMAT),
                        'status' => STATUS_DEACTIVE,
                    );

                    if ($this->university_model->insert_fake_university($fields, array('id' => $university_id))) {
                        $json_array['message'] = 'Details Updated Successfully';

                        $json_array['redirect'] = ADMIN_SITE_URL . 'universities/fake_university';

                        $json_array['status'] = SUCCESS_CODE;
                    } else {
                        $json_array['message'] = 'Something went wrong, please try again';

                        $json_array['status'] = ERROR_CODE;
                    }
                }
            }
            echo_json($json_array);
        }
    }

    public function import_fake_university()
    {
        $data['header_title'] = "Import & Export Fake University";

        $this->load->view('admin/header', $data);

        $this->load->view('admin/fake_university_import');

        $this->load->view('admin/footer');
    }

    public function upload_fake_university()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {
            $file_upload_path = SITE_BASE_PATH . FAKE_UNIVERSITE;

            if (!folder_exist($file_upload_path)) {
                mkdir($file_upload_path, 0777);
            } else if (!is_writable($file_upload_path)) {
                array_push($error_msgs, 'Problem while uploading');
            }

            $file_name = str_replace(' ', '_', $_FILES['import_file']['name']);

            $new_file_name = time() . "_" . $file_name;

            $_FILES['import_file_up']['name'] = $new_file_name;

            $_FILES['import_file_up']['tmp_name'] = $_FILES['import_file']['tmp_name'];

            $_FILES['import_file_up']['error'] = $_FILES['import_file']['error'];

            $_FILES['import_file_up']['size'] = $_FILES['import_file']['size'];

            $config['upload_path'] = $file_upload_path;

            $config['file_ext_tolower'] = true;

            $config['max_size'] = BULK_UPLOAD_MAX_SIZE_MB * 2000;

            $config['allowed_types'] = 'xlsx';

            $config['remove_spaces'] = true;

            $config['overwrite'] = false;

            $config['file_name'] = $new_file_name;

            $this->load->library('upload', $config);

            $this->upload->initialize($config);

            if (!$this->upload->do_upload('import_file_up')) {
                $data = array('error' => $this->upload->display_errors());
            } else {
                $this->load->library('excel_reader', array('file_name' => $file_upload_path . '/' . $new_file_name));

                $excel_handler = $this->excel_reader->file_handler;

                $excel_data = $excel_handler->rows();

                if (!empty($excel_data)) {
                    unset($excel_data[0]);

                    $excel_data = array_map("unserialize", array_unique(array_map("serialize", $excel_data)));

                    foreach ($excel_data as $value) {
                        if (count($value) < 4) {
                            continue;
                        }

                        $record[] = array('u_name' => htmlentities(strtolower(trim($value[0]))),
                            'u_address' => htmlentities(strtolower(trim($value[1]))),
                            'u_state' => htmlentities(strtolower(trim($value[2]))),
                            'u_url' => htmlentities(trim($value[3])),
                            'created_by' => $this->user_info['id'],
                            'status' => STATUS_ACTIVE,
                            'created_on' => date(DB_DATE_FORMAT),
                        );
                    }

                    $this->university_model->fake_university_insert_batch($record);

                    $json_array['message'] = 'Records Uploaded Successfully';

                    $json_array['redirect'] = ADMIN_SITE_URL . "universities/fake_university";

                    $json_array['status'] = SUCCESS_CODE;
                } else {
                    $json_array['message'] = 'Sheet is empty, please insert rows';

                    $json_array['status'] = ERROR_CODE;
                }
            }
        } else {
            $json_array['message'] = 'Something went wrong, please try again';

            $json_array['status'] = ERROR_CODE;
        }

        $this->echo_json($json_array);
    }

    public function export_fake_reports()
    {
        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');
        $query = $this->db->query("SELECT id as S_No, u_name as University_Name,u_address as University_Address,u_state as State,u_url as Website_URL,created_on FROM fake_university where status = 1");
        $delimiter = ",";
        $newline = "\r\n";
        $data = $this->dbutil->csv_from_result($query, $delimiter, $newline);
        force_download('Fake-University.csv', $data);
    }
}
