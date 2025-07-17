
<?php defined('BASEPATH') or exit('No direct script access allowed');
class Candidate_pending extends MY_Candidate_Cotroller
{

    public function __construct()
    {
        parent::__construct();
        $this->perm_array = array('direct_access' => true);
        $this->load->model(array('candidate_info/candidates_model_pending'));
    }
    public function index()
    {
        $this->load->model('clients_model');

        $data['header_title'] = "Candidates List";

        $data['states'] = $this->get_states();

        $data['company_list'] = $this->get_company_list();
        $data['university_name'] = $this->get_university_list();
        $data['qualification_name'] = $this->get_qualification_list();

        $data['candidate_details'] = $this->candidates_model_pending->select(true, array(), array('id' => $this->candidate_info['id']));

        $data['client_components'] = $this->clients_model->get_entitypackages(array('entity' => $this->candidate_info['entity'], 'tbl_clients_id' => $this->candidate_info['client_id'], 'package' => $this->candidate_info['package']))[0];

        $this->load->view('candidate_info/header', $data);

        $this->load->view('candidate_info/candidate_detail_edit');

        $this->load->view('candidate_info/footer');

    }

}