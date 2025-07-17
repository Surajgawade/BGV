<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends MY_Vendor_Cotroller
{    
 
    function __construct()
    {
        parent::__construct();
        if(!$this->is_vendor_logged_in())
        {
            redirect('vendor/login');
            exit();
        }
        $this->perm_array = array('direct_access' => true);

        $this->title = array(0 => 'Select Title','mr' => 'Mr','mrs' => 'Mrs','miss' => 'Miss','ms' => 'Ms');
  
       $this->load->model(array('vendor/Vendor_users_model'));
    }

    public function index()
    {   

       
        $data['header_title'] = "Vendor User List";
   
      
          
        $this->load->view('vendor/header',$data);
        
        $this->load->view('vendor/vendor_user_list');

        $this->load->view('vendor/footer');
    }

     public function add()
    {


        $data['header_title'] = "User Add";

         $data['states'] = $this->get_states();

         $data['title'] = $this->title;

        $this->load->view('vendor/header',$data);



        $this->load->view('vendor/user_add');

        $this->load->view('vendor/footer');
    }

    public function check_username()
    {
        $result = $this->Vendor_users_model->select(TRUE,array('id'),array('user_name' => $this->input->post('user_name')));

        echo (!empty($result) ? 'false' : 'true');
    }

     public function fetch_user()
    {   

        if($this->input->is_ajax_request())
        {   

            
          $params = $add_candidates = $data_arry = $columns = array();
         
          $params = $_REQUEST;
      
          $where_arry = array();

          $results = $this->Vendor_users_model->get_vendor_user_details(array('vendor_login_id'=>$this->vendor_info['id']),$params, $columns);
        
          $totalRecords = count($this->Vendor_users_model->get_vendor_user_details_count(array('vendor_login_id'=>$this->vendor_info['id']),$params, $columns));
        
               $x = 0;
        
        foreach ($results as $value)
        {


         if($value['status'] == 0) {  $status = 'Deactivate'; }elseif ($value['status'] == 1) {
                       $status =  'Active';}elseif ($value['status'] == 2){ $status =  'Deleted'; }else{   };
         
            $data_arry[$x]['checkbox'] = "<input type='checkbox' id='select-checkbox' name='user_id' class='user_id' value='".$value['id']."'>";
            $data_arry[$x]['id'] = $x+1;
            $data_arry[$x]['first_name'] = $value['first_name'];
            $data_arry[$x]['last_name'] = $value['last_name'];
            $data_arry[$x]['encry_id'] =VENDOR_SITE_URL."users/view_details/".encrypt($value['id']);
            $data_arry[$x]['email_id'] = $value['email_id'];
            $data_arry[$x]['mobile_no'] = $value['mobile_no'];
            $data_arry[$x]['status'] = $status;
                   
                 
          $x++;
        } 

           $json_data = array(
            "draw"            => intval( $params['draw'] ),
            "recordsTotal"    => intval( $totalRecords ),
            "recordsFiltered" => intval($totalRecords),
            "data"            => $data_arry
            );

        echo_json($json_data);
            
        }
        else
        {
           permission_denied();
        }
    }


    public function save_users()
    {
        if($this->input->is_ajax_request())
        {
           $mobile_phone = $this->input->post('mobile_no');

            $this->form_validation->set_rules('email', 'Email', 'valid_email');

            $this->form_validation->set_rules('firstname', 'First Name', 'required|alpha_numeric_spaces');

            $this->form_validation->set_rules('firstname', 'Last Name', 'required|alpha_numeric_spaces');

            $this->form_validation->set_rules('password', 'Password', 'required|min_length[5]');

            $this->form_validation->set_rules('crmpassword', 'Comfirm Password', 'required|min_length[5]|matches[password]');

            $this->form_validation->set_rules('mobile_no', 'Mobile No', 'required|numeric|min_length[10]|max_length[10]|is_unique[vendor_executive_login.mobile_no]');
        
            $this->form_validation->set_rules('pincode', 'Pin Code', 'numeric|min_length[6]|max_length[6]');

            $this->form_validation->set_rules('city', 'City', 'alpha_numeric_spaces');

            $this->form_validation->set_message('min_length','Password content min 5 charecter long');

            $this->form_validation->set_message('matches','Comfirm password same as Password');

            $this->form_validation->set_message('alpha_number_dot','Sorry, only letters (a-z), numbers (0-9), and periods (.) are allowed.');

            $this->form_validation->set_message('is_unique',"$mobile_phone  number taken, please try another.");

        
        
            if ($this->form_validation->run() == FALSE)
            {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('','');
            }
            else
            {   
                $frm_details = $this->input->post();
               
                $enc_pass =  create_password($frm_details['crmpassword']);

                $field_array = array("vendor_login_id" => $this->vendor_info['id'],
                                    "email_id" => $frm_details["email"],
                                    "first_name" => $frm_details["firstname"],
                                    "last_name" => $frm_details["lastname"],
                                    "profile_pic" => 'default.png', 
                                    "password" => '',
                                    "mobile_no" => $frm_details["mobile_no"],
                                    "address" => $frm_details["address"],
                                    "city" => $frm_details["city"],
                                    "pincode" => $frm_details["pincode"],
                                    "state" => $frm_details["state"],
                                    'created_on'  => date(DB_DATE_FORMAT),
                                    'created_by'  => $this->vendor_info['id'],
                                    'status'  => 1
                                  
                                );
                
                $field_array = array_map('strtolower', $field_array);

                $field_array['password'] = $enc_pass;

                $file_upload_path = SITE_BASE_PATH.VENDOR_PROFILE_PIC_PATH;

                if(!folder_exist($file_upload_path))
                {
                    mkdir($file_upload_path,0777);
                }

                if(!empty($_FILES['user_image']['name']))
                {
                    $file_name = str_replace(' ', '_', $_FILES['user_image']['name']);

                    $new_file_name = time()."_".$file_name;

                    $_FILES['logo']['name'] = $new_file_name;

                    $_FILES['logo']['tmp_name'] = $_FILES['user_image']['tmp_name'];

                    $_FILES['logo']['error'] = $_FILES['user_image']['error'];

                    $_FILES['logo']['size'] = $_FILES['user_image']['size'];

                    $config['upload_path'] = $file_upload_path;

                    $config['file_ext_tolower'] = TRUE;

                    $config['max_size'] = BULK_UPLOAD_MAX_SIZE_MB*1000;

                    $config['allowed_types'] = 'jpeg|jpg|png';

                    $config['remove_spaces'] = TRUE;

                    $config['overwrite'] = FALSE;

                    $config['file_name'] = $new_file_name;

                    $this->load->library('upload',$config);

                    $this->upload->initialize($config);

                    if ($this->upload->do_upload('logo'))
                    {
                        $field_array['profile_pic'] = $new_file_name;

                        $uploaded_file_name  = $this->upload->data('file_name');

                        $config = Array();

                        $config['image_library'] = 'gd2';

                        $config['source_image'] = $file_upload_path.'/'.$uploaded_file_name;

                        $config['maintain_ratio'] = FALSE;

                        $config['width'] = 160;

                        $config['height'] = 180;
                    
                        $this->load->library('image_lib', $config);

                        $this->image_lib->resize();
                    }
                    else
                    {
                        $json_array['upload_error'] = $this->upload->display_errors('','');
                    }
                }
             
                
                if($this->Vendor_users_model->save($field_array))
                {
                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'User Created Successfully';

                    $json_array['redirect'] = VENDOR_SITE_URL.'users';
                }
                else
                {
                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Something went wrong,please try again';
                }
            }
           
            echo_json($json_array);
        }
        else
        {
            permission_denied();
        }
    }


    public function view_details($users_id = '')
    {

        $is_exits = $this->Vendor_users_model->select(TRUE,array('*'),array('vendor_executive_login.id'=>decrypt($users_id)));

        if($users_id && !empty($is_exits))
        {   
            $data['header_title'] = "User Edit";
            
            $data['states'] = $this->get_states();

            $data['user_details'] = $is_exits;

            $this->load->view('vendor/header',$data);

            $this->load->view('vendor/user_edit');

            $this->load->view('vendor/footer');
        }   
    }

    public function update_users()
    {

        if($this->input->is_ajax_request())
        {
            $this->form_validation->set_rules('hidden_uid', 'User ID', 'required');

           $this->form_validation->set_rules('email', 'Email', 'valid_email');

            $this->form_validation->set_rules('firstname', 'First Name', 'required|alpha_numeric_spaces');

            $this->form_validation->set_rules('firstname', 'Last Name', 'required|alpha_numeric_spaces');

            $this->form_validation->set_rules('password', 'Password', 'min_length[5]');

            $this->form_validation->set_rules('crmpassword', 'Comfirm Password', 'min_length[5]|matches[password]');

            $this->form_validation->set_rules('mobile_no', 'Mobile No', 'required|numeric|min_length[10]|max_length[10]');
        
            $this->form_validation->set_rules('pincode', 'Pin Code', 'numeric|min_length[6]|max_length[6]');

            $this->form_validation->set_rules('city', 'City', 'alpha_numeric_spaces');

            $this->form_validation->set_message('min_length','Password content min 5 charecter long');

            $this->form_validation->set_message('matches','Comfirm password same as Password');

            $this->form_validation->set_message('alpha_number_dot','Sorry, only letters (a-z), numbers (0-9), and periods (.) are allowed.');

            
         

            if ($this->form_validation->run() == FALSE)
            {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = validation_errors('','');
            }
            else
            {   
                $frm_details = $this->input->post();
            
                $field_array = array(
                                    "email_id" => $frm_details["email"],
                                    "password" => $frm_details["password"],
                                    "first_name" => $frm_details["firstname"],
                                    "last_name" => $frm_details["lastname"],
                                    "mobile_no" => $frm_details["mobile_no"],
                                    "address" => $frm_details["address"],
                                    "city" => $frm_details["city"],
                                    "state" => $frm_details["state"],
                                    "pincode" => $frm_details["pincode"],
                                    'modified_on'  => date(DB_DATE_FORMAT),
                                    'modified_by'  => $this->vendor_info['id']
                                
                                );
                
                $field_array = array_map('strtolower', $field_array);

                ($frm_details["password"] != "") ? $field_array['password'] = create_password($frm_details['crmpassword']) : '';

                $file_upload_path = SITE_BASE_PATH.VENDOR_PROFILE_PIC_PATH;
                if(!empty($_FILES['user_image']['name']))
                {
                    $file_name = str_replace(' ', '_', $_FILES['user_image']['name']);

                    $new_file_name = time()."_".$file_name;

                    $_FILES['logo']['name'] = $new_file_name;

                    $_FILES['logo']['tmp_name'] = $_FILES['user_image']['tmp_name'];

                    $_FILES['logo']['error'] = $_FILES['user_image']['error'];

                    $_FILES['logo']['size'] = $_FILES['user_image']['size'];

                    $config['upload_path'] = $file_upload_path;

                    $config['file_ext_tolower'] = TRUE;

                    $config['max_size'] = BULK_UPLOAD_MAX_SIZE_MB*1000;

                    $config['allowed_types'] = 'jpeg|jpg|png';

                    $config['remove_spaces'] = TRUE;

                    $config['overwrite'] = FALSE;

                    $config['file_name'] = $new_file_name;

                    $this->load->library('upload',$config);

                    $this->upload->initialize($config);

                    if ($this->upload->do_upload('logo'))
                    {
                        $field_array['profile_pic'] = $new_file_name;

                        $uploaded_file_name  = $this->upload->data('file_name');

                        $config = Array();

                        $config['image_library'] = 'gd2';

                        $config['source_image'] = $file_upload_path.'/'.$uploaded_file_name;

                        $config['maintain_ratio'] = FALSE;

                        $config['width'] = 160;

                        $config['height'] = 180;
                    
                        $this->load->library('image_lib', $config);

                        $this->image_lib->resize();
                    }
                    else
                    {
                        $json_array['upload_error'] = $this->upload->display_errors('','');
                    }
                }

                if($this->Vendor_users_model->save($field_array,array('id' => $frm_details['hidden_uid'])))
                {
                    $json_array['status'] = SUCCESS_CODE;

                    $json_array['message'] = 'Profile Updated Successfully';

                    $json_array['redirect'] = VENDOR_SITE_URL.'users';
                }
                else
                {
                    $json_array['status'] = ERROR_CODE;

                    $json_array['message'] = 'Something went wrong,please try again';
                }
            }
           
            echo_json($json_array);
        }
        else
        {
            permission_denied();
        }
   }

   /* public function status_count()
    {

        $json_array = array();
        
        $result = '';
        
        if($this->input->is_ajax_request())
        {
          $this->load->model('vendor/Vendor_users_model');
          
          foreach($this->vendor_nemus as $key => $value) {
         
          if($value['component_key'] == 'addrver') 
          {
          $result['address'] = $this->Vendor_users_model->status_count_address($this->vendor_info['vendors_id']);

          }
          if($value['component_key'] == 'empver') 
          {
          $result['employment'] = $this->Vendor_users_model->status_count_employment($this->vendor_info['vendors_id']);
          }
          if($value['component_key'] == 'eduver') 
          {
          $result['education'] = $this->Vendor_users_model->status_count_education($this->vendor_info['vendors_id']);

          }
          if($value['component_key'] == 'refver') 
          {
          $result['reference'] = $this->Vendor_users_model->status_count_reference($this->vendor_info['vendors_id']);
          }
          if($value['component_key'] == 'courtver') 
          {
          $result['court'] = $this->Vendor_users_model->status_count_court($this->vendor_info['vendors_id']);
          }
          if($value['component_key'] == 'globdbver') 
          {
          $result['global'] = $this->Vendor_users_model->status_count_global_database($this->vendor_info['vendors_id']);
          }
          if($value['component_key'] == 'crimver') 
          {
          $result['pcc'] = $this->Vendor_users_model->status_count_pcc($this->vendor_info['vendors_id']);
          }
          if($value['component_key'] == 'identity') 
          {
          $result['identity'] = $this->Vendor_users_model->status_count_identity($this->vendor_info['vendors_id']);
          }
          if($value['component_key'] == 'narcver') 
          {
          $result['drugs'] = $this->Vendor_users_model->status_count_drugs($this->vendor_info['vendors_id']);
          } 
          if($value['component_key'] == 'cbrver') 
          {
          $result['credit_report'] = $this->Vendor_users_model->status_count_credit_report($this->vendor_info['vendors_id']);
          }

        }
           // $result = array_merge($result_address,$result_drugs);    

          $json_array['status'] = SUCCESS_CODE;
                    
          $json_array['message'] = $result;
        }
        else
        {
            $json_array['status'] = ERROR_CODE;

            $json_array['message'] = 'Something went wrong';
        }
        echo_json($json_array);
    }*/

    public function status_count()
    {

        $json_array = array();
        
        $result = array();

        $params = $_REQUEST;
        
        if($this->input->is_ajax_request())
        {
          $this->load->model('vendor/Vendor_users_model');
          
          foreach($this->vendor_nemus as $key => $value) {
         
          if($value['component_key'] == 'addrver') 
          {
          $result['address'] = $this->Vendor_users_model->status_count_address($this->vendor_info['vendors_id'],$params);

          }
          if($value['component_key'] == 'empver') 
          {
          $result['employment'] = $this->Vendor_users_model->status_count_employment($this->vendor_info['vendors_id'],$params);
          }
          if($value['component_key'] == 'eduver') 
          {
          $result['education'] = $this->Vendor_users_model->status_count_education($this->vendor_info['vendors_id'],$params);

          }
          if($value['component_key'] == 'refver') 
          {
          $result['reference'] = $this->Vendor_users_model->status_count_reference($this->vendor_info['vendors_id'],$params);
          }
          if($value['component_key'] == 'courtver') 
          {
          $result['court'] = $this->Vendor_users_model->status_count_court($this->vendor_info['vendors_id'],$params);
          }
          if($value['component_key'] == 'globdbver') 
          {
          $result['global'] = $this->Vendor_users_model->status_count_global_database($this->vendor_info['vendors_id'],$params);
          }
          if($value['component_key'] == 'crimver') 
          {
          $result['pcc'] = $this->Vendor_users_model->status_count_pcc($this->vendor_info['vendors_id'],$params);
          }
          if($value['component_key'] == 'identity') 
          {
          $result['identity'] = $this->Vendor_users_model->status_count_identity($this->vendor_info['vendors_id'],$params);
          }
          if($value['component_key'] == 'narcver') 
          {
          $result['drugs'] = $this->Vendor_users_model->status_count_drugs($this->vendor_info['vendors_id'],$params);
          } 
          if($value['component_key'] == 'cbrver') 
          {
          $result['credit_report'] = $this->Vendor_users_model->status_count_credit_report($this->vendor_info['vendors_id'],$params);
          }

        }
           // $result = array_merge($result_address,$result_drugs);    

          $json_array['status'] = SUCCESS_CODE;
                    
          $json_array['message'] = $result;
        }
        else
        {
            $json_array['status'] = ERROR_CODE;

            $json_array['message'] = 'Something went wrong';
        }
        echo_json($json_array);
    }

    public function remove_uploaded_file($id)
    {
        $json_array = array();


        if($this->input->is_ajax_request())
        {
          
            $result = $this->common_model->save_update_vendor_files(array('status' => 2),array('id'=>$id)); 

            if($result)
            {
                $json_array['status'] = SUCCESS_CODE;

                $json_array['message'] = 'Attachment removed successfully, please refresh the page';
            }
            else
            {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = 'Something went worong, please try again';
            }
           
           
        }
        else
        {
            permission_denied();         
        }

       echo_json($json_array);      
    }

    public function undo_uploaded_file($id)
    {
        $json_array = array();


        if($this->input->is_ajax_request())
        {
          
            $result = $this->common_model->save_update_vendor_files(array('status' => 1),array('id'=>$id)); 

            if($result)
            {
                $json_array['status'] = SUCCESS_CODE;

                $json_array['message'] = 'Attachment removed successfully, please refresh the page';
            }
            else
            {
                $json_array['status'] = ERROR_CODE;

                $json_array['message'] = 'Something went worong, please try again';
            }
           
           
        }
        else
        {
            permission_denied();         
        }

       echo_json($json_array);      
    }

}

?>