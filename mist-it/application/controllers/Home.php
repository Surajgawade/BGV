<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends MY_Controller
{
    public function index()
    {
        if (!$this->is_admin_logged_in()) {
            redirect('admin/login');
        }else {
            redirect('admin');
        }
    }

    public function capture($cap_id) {
        if($cap_id != "") {

            //$cap_id = decrypt($cap_id);
            $this->load->model('addressver_model');
            
            $details = $this->addressver_model->get_report_details(array('addrver.id' =>$cap_id,'address_details.status' =>1));

            if(!empty($details))
            {   
                $details = $details[0];
               
                $data['header_title'] = $details['CandidateName'].' Profile';
                $data['details'] = $details;

               $this->load->view('capture',$data);
            }
        }
    }

    public function merge_image($cap_id) {

        if($cap_id != "") {

            //$cap_id = decrypt($cap_id);
            $this->load->model('addressver_model');
            $details = $this->addressver_model->get_report_details(array('addrver.id' =>$cap_id,'address_details.status' =>1));
            
            if(!empty($details))
            {   
                $details = $details[0];
               
                $data['header_title'] = $details['CandidateName'].' Profile';
                $data['details'] = $details;

                $this->load->view('merge_image',$data);
            }
        }
    }


}