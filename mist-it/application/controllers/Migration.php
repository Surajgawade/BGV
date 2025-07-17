<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration extends MY_Controller {
    
    function __construct(){

        parent::__construct();
        $this->server_2 = $this->load->database('server_2', TRUE);
    }
    public function pass() {
        $options = ['cost' => 12];
        $enc_pass =  password_hash('admin12', PASSWORD_BCRYPT, $options);
        echo $enc_pass;
    }

    public function client_migration() {

        $sql = "SELECT  * FROM `clients`";
        $query = $this->db->query($sql);
        $results = $query->result_array();
        
        foreach ($results as $key => $result) {

            // data entry 
            $client = [
                    'id'                => $result['id'],
                    'created_on'        => $result['created'],
                    'created_by'        => $result['created_by'],
                    'clientname'        => $result['clientname'],
                    'status'            => $result['status'],
                    'modified_on'       => $result['created'],
                    'modified_by'       => $result['created_by']    
    
            ];
            $this->server_2->insert('clients', $client);  
        }

    } 

    public function candidate_migration() {

        $sql = "SELECT  * FROM `cands` GROUP BY cands.id";
        $query = $this->db->query($sql);
        $results = $query->result_array();
        
        foreach ($results as $key => $result) {

            if($result['overallstatus'] == "Clear")
            {
               $overallstatus = 4;
            }
            elseif($result['overallstatus'] == "Discrepancy")
            {
               $overallstatus = 6;
            }
            elseif($result['overallstatus'] == "WIP")
            {
               $overallstatus = 1; 
            }
            elseif($result['overallstatus'] == "Stop/Check")
            {
                $overallstatus = 3; 
            }
            else
            {
                $overallstatus = 1;
            }

            

            // data entry 
            $cands = [
                    'id'                => $result['id'],
                    'clientid'          => $result['clientid'],
                    'entity'            => 1,
                    'package'           => 2,
                    'created_on'        => $result['created'],
                    'created_by'        => $result['created_by'],
                    'ClientRefNumber'   => $result['ClientRefNumber'],
                    'cmp_ref_no'        => $result['MISTRefNumber'],
                    'gender'            => $result['gender'],
                    'CandidateName'     => $result['CandidateName'],
                    'DateofBirth'       => $result['DateofBirth'],
                    'NameofCandidateFather' => $result['NameofCandidateFather'],
                    'MothersName'       => $result['MothersName'],
                    'CandidatesContactNumber' => $result['CandidatesContactNumber'],
                    'ContactNo1'        => $result['ContactNo1'],
                    'ContactNo2'        => $result['ContactNo2'],
                    'DateofJoining'     => $result['DateofJoining'],
                    'Location'          => $result['Location'],
                    'DesignationJoinedas' => $result['DesignationJoinedas'],
                    'Department'        => $result['Department'],
                    'EmployeeCode'      => $result['EmployeeCode'],
                    'PANNumber'         => $result['PANNumber'],
                    'AadharNumber'      => $result['AadharNumber'],
                    'PassportNumber'    => $result['PassportNumber'],
                    'BatchNumber'       => $result['BatchNumber'],
                    'overallstatus'     => $overallstatus,
                    'overallclosuredate'=> $result['overallclosuredate'],
                    'caserecddate'      => $result['caserecddate'],
                    'overall_reiniated_date' => $result['overall_reiniated_date'],
                    'remarks'           => $result['remarks'],
                    'prasent_address'   => $result['prasent_address'],
                    'cands_country'     => $result['cands_country'],
                    'cands_state'       => $result['cands_state'],
                    'cands_city'        => $result['cands_city'],
                    'cands_pincode'     => $result['cands_pincode'],
                    'build_date'        => $result['build_date'],
                    'region'            => $result['region'],
                    'branch_name'       => $result['work_experience'],
                    'status'            => 1,
                    'is_bulk_upload'    => 1,
                    'final_qc'          => "Final QC Approve"
                    
    
            ];
            $this->server_2->insert('candidates_info', $cands);
            $insert_id_cands = $this->server_2->insert_id();

                  $cands_info = [
                    'id'                => $result['id'],
                    'clientid'          => $result['clientid'],
                    'entity'            => 1,
                    'package'           => 2,
                    'candidates_info_id'=> $insert_id_cands,
                    'created_on'        => $result['created'],
                    'created_by'        => $result['created_by'],
                    'ClientRefNumber'   => $result['ClientRefNumber'],
                    'cmp_ref_no'        => $result['MISTRefNumber'],
                    'gender'            => $result['gender'],
                    'CandidateName'     => $result['CandidateName'],
                    'DateofBirth'       => $result['DateofBirth'],
                    'NameofCandidateFather' => $result['NameofCandidateFather'],
                    'MothersName'       => $result['MothersName'],
                    'CandidatesContactNumber' => $result['CandidatesContactNumber'],
                    'ContactNo1'        => $result['ContactNo1'],
                    'ContactNo2'        => $result['ContactNo2'],
                    'DateofJoining'     => $result['DateofJoining'],
                    'Location'          => $result['Location'],
                    'DesignationJoinedas' => $result['DesignationJoinedas'],
                    'Department'        => $result['Department'],
                    'EmployeeCode'      => $result['EmployeeCode'],
                    'PANNumber'         => $result['PANNumber'],
                    'AadharNumber'      => $result['AadharNumber'],
                    'PassportNumber'    => $result['PassportNumber'],
                    'BatchNumber'       => $result['BatchNumber'],
                    'overallstatus'     => $overallstatus,
                    'overallclosuredate'=> $result['overallclosuredate'],
                    'caserecddate'      => $result['caserecddate'],
                    'overall_reiniated_date' => $result['overall_reiniated_date'],
                    'remarks'           => $result['remarks'],
                    'prasent_address'   => $result['prasent_address'],
                    'cands_country'     => $result['cands_country'],
                    'cands_state'       => $result['cands_state'],
                    'cands_city'        => $result['cands_city'],
                    'cands_pincode'     => $result['cands_pincode'],
                    'build_date'        => $result['build_date'],
                    'region'            => $result['region'],
                    'branch_name'       => $result['work_experience'],
                    'status'            => 1,
                    'is_bulk_upload'    => 1,
                    'final_qc'          => "Final QC Approve"
                    
    
            ];
            $this->server_2->insert('candidates_info_logs', $cands_info);
        }

    } 
    public function address_migration() {

        $sql = "
            SELECT  
                candidates_info.ClientRefNumber, candidates_info.cmp_ref_no, candidates_info.caserecddate, candidates_info.clientid, candidates_info.id as candsid,
                
                status.id as verfstatus_id,status.filter_status,status.report_status,

                addrver.id as addrverid, addrver.created as addrver_created, addrver.address, addrver.has_assigned_on as has_assigned_on, addrver.city, addrver.state, addrver.pincode, 
                
                ev1.modeofverification, ev1.closuredate,  ev1.neighbour1, ev1.neighbour1details, ev1.neighbour2, ev1.neighbour2details, ev1.residentstatus as resident_status, ev1.landmark, ev1.verifiername as verified_by, ev1.addrproofcollected as addr_proof_collected, ev1.remark as add_result_remarks, ev1.stayfrom as stay_from, ev1.stayto as stay_to,ev1.verfstatus, ev1.id as add_result_id, ev1.created_on as add_result_created_on, ev1.insuffraiseddate, ev1.insuffcleardate, ev1.insuffremarks, ev1.insuff_raised_date_2, ev1.insuff_clear_date_2, ev1.insuff_remarks_2, 

                GROUP_CONCAT(addrverres_files.file_name SEPARATOR '||') as file_names, GROUP_CONCAT(addrverres_files.real_filename SEPARATOR '||') as real_filename

            FROM `candidates_info`
            INNER JOIN addrver ON addrver.candsid =  candidates_info.id
            LEFT JOIN addrverres as ev1 ON ev1.addrverid = addrver.id
            LEFT JOIN addrverres as ev2 ON (ev2.addrverid = addrver.id and ev1.id < ev2.id)
            LEFT JOIN addrverres_files ON ( addrverres_files.addrverres_id = ev1.id and addrverres_files.status = 1)
            LEFT JOIN status ON (status.action = ev1.verfstatus and components_id = 6)
            WHERE ev2.verfstatus is null
            GROUP BY addrver.id

        ";
        $query = $this->db->query($sql);
        $results = $query->result_array();
        
        foreach ($results as $key => $result) {

            // data entry 
            $addrver = [
                    'clientid'          => $result['clientid'],
                    'candsid'           => $result['candsid'],
                    'add_com_ref'       => $result['cmp_ref_no'].'-'.$result['addrverid'],
                    'stay_from'         => '',
                    'stay_to'           => '',
                    'address_type'      => '',
                    'address'           => $result['address'],
                    'city'              => $result['city'],
                    'pincode'           => $result['pincode'],
                    'state'             => strtolower($result['state']),
                    'mod_of_veri'       => '',
                    'created_on'        => $result['addrver_created'],
                    'created_by'        => 1,
                    'has_case_id'       => 1,
                    'vendor_id'         => 1,
                    'has_assigned_on'   => $result['has_assigned_on'],
                    'iniated_date'      => $result['caserecddate']
            ];
            $this->server_2->insert('addrver', $addrver);
            $insert_id_addrver = $this->server_2->insert_id();

            if($result['verfstatus'] == "Clear")
            {
                $activity_mode = "Personal Visit";
                $activity_status = "Verification Received";
                $activity_type = "Other";
            }
            elseif($result['verfstatus'] == "Insufficiency")
            {
                $activity_mode = "Insufficiency Raised";
                $activity_status = "Insufficiency Raised";
                $activity_type = "";   
            }
            elseif($result['verfstatus'] == "Stop/Check")
            {
                $activity_mode = "Stop Check";
                $activity_status = "Stop Check";
                $activity_type = "Stop Check";   
            }
            elseif($result['verfstatus'] == "NA")
            {
                $activity_mode = "NA";
                $activity_status = "NA";
                $activity_type = "NA";   
            }
            elseif($result['verfstatus'] == "WIP")
            {
                $activity_mode = "WIP";
                $activity_status = "WIP";
                $activity_type = "WIP";   
            }
            elseif($result['verfstatus'] == "Unable to Verify")
            {
                $activity_mode = "Personal Visit";
                $activity_status = "UTV";
                $activity_type = "UTV";   
            }
            else
            {
                $activity_mode = "";
                $activity_status = "";
                $activity_type = "";
            } 

            $status_verification = $this->status_change($result['verfstatus']); 
           
            if($result['verfstatus'] != "Insufficiency" && $result['verfstatus'] != "WIP")
            {
                //activity_log 
                $activity_log = [
                        'candsid'               => $result['candsid'],
                        'ClientRefNumber'       => $result['ClientRefNumber'],
                        'comp_table_id'         => $insert_id_addrver,
                        'activity_mode'         => $activity_mode,
                        'activity_status'       => $activity_status,
                        'activity_type'         => $activity_type,
                        'action'                => $result['verfstatus'],
                        'next_follow_up_date'   => '',
                        'remarks'               => $result['add_result_remarks'],
                        'component_type'        => 1,
                        'is_auto_filled'        => '',
                        'created_on'            => isset($result['addrver_created']) ? $result['addrver_created'] : date('Y-m-d H:i:s')
                ];
                $this->server_2->insert('activity_log', $activity_log);
                $insert_id_activity_log = $this->server_2->insert_id();
            }
            //activity_log 
            $address_activity_data = [
                    'candsid'               => $result['candsid'],
                    'ClientRefNumber'       => $result['ClientRefNumber'],
                    'comp_table_id'         => $insert_id_addrver,
                    'activity_mode'         => $activity_mode,
                    'activity_status'       => $activity_status,
                    'activity_type'         => $activity_type,
                    'action'                => $result['verfstatus'],
                    'next_follow_up_date'   => '',
                    'remarks'               => $result['add_result_remarks'],
                    'is_auto_filled'        => '',
                    'created_on'            => isset($result['addrver_created']) ? $result['addrver_created'] : date('Y-m-d H:i:s')
            ];
            $this->server_2->insert('address_activity_data', $address_activity_data);

            //addrverres 

            if($result['verfstatus'] != "Insufficiency" && $result['verfstatus'] != "WIP")
            {
            if(!empty($result['add_result_id']) && !empty($result['verfstatus'])) {
                $addrverres_result = [
                        'verfstatus'            => $status_verification['verfstatus'],
                        'var_filter_status'     => $status_verification['var_filter_status'],
                        'var_report_status'     => $status_verification['var_report_status'],
                        'closuredate'           => $result['closuredate'],
                        'clientid'              => $result['clientid'],
                        'candsid'               => $result['candsid'],
                        'addrverid'             => $insert_id_addrver,
                        'res_address_type'      => '',
                        'neighbour_1'           => $result['neighbour1'],
                        'neighbour_details_1'   => $result['neighbour1details'],
                        'neighbour_2'           => $result['neighbour2'],
                        'neighbour_details_2'   => $result['neighbour2details'],
                        'mode_of_verification'  => isset($result['modeofverification']) ? $result['modeofverification'] : 'personal visit',
                        'resident_status'       => $result['resident_status'],
                        'landmark'              => $result['landmark'],
                        'verified_by'           => $result['verified_by'],
                        'addr_proof_collected'  => $result['addr_proof_collected'],
                        'remarks'               => $result['add_result_remarks'],
                        'res_address'           => $result['address'],
                        'res_city'              => $result['city'],
                        'res_pincode'           => $result['pincode'],
                        'res_state'             => strtolower($result['state']),
                        'res_stay_from'         => $result['stay_from'],
                        'res_stay_to'           => $result['stay_to'],
                        'address_action'        => 'yes',
                        'city_action'           => 'yes',
                        'pincode_action'        => 'yes',
                        'state_action'          => 'yes',
                        'stay_from_action'      => 'no',
                        'stay_to_action'        => 'no',
                        'created_on'            => $result['add_result_created_on'],
                        'created_by'            => 1,
                        'activity_log_id'       => $insert_id_activity_log
                ];
                $this->server_2->insert('addrverres_result', $addrverres_result);
              }

            }
            // update status 
            $addrverres = [
                    'verfstatus'            => $status_verification['verfstatus'],
                    'var_filter_status'     => $status_verification['var_filter_status'],
                    'var_report_status'     => $status_verification['var_report_status'],
                    'closuredate'           => $result['closuredate'],
                    'clientid'              => $result['clientid'],
                    'candsid'               => $result['candsid'],
                    'res_address_type'      => '',
                    'neighbour_1'           => $result['neighbour1'],
                    'neighbour_details_1'   => $result['neighbour1details'],
                    'neighbour_2'           => $result['neighbour2'],
                    'neighbour_details_2'   => $result['neighbour2details'],
                    'mode_of_verification'  => isset($result['modeofverification']) ? $result['modeofverification'] : 'personal visit',
                    'resident_status'       => $result['resident_status'],
                    'landmark'              => $result['landmark'],
                    'verified_by'           => $result['verified_by'],
                    'addr_proof_collected'  => $result['addr_proof_collected'],
                    'remarks'               => $result['add_result_remarks'],
                    'res_address'           => $result['address'],
                    'res_city'              => $result['city'],
                    'res_pincode'           => $result['pincode'],
                    'res_state'             => strtolower($result['state']),
                    'res_stay_from'         => $result['stay_from'],
                    'res_stay_to'           => $result['stay_to'],
                    'address_action'        => 'yes',
                    'city_action'           => 'yes',
                    'pincode_action'        => 'yes',
                    'state_action'          => 'yes',
                    'stay_from_action'      => 'no',
                    'stay_to_action'        => 'no',
                    'created_on'            => $result['add_result_created_on'],
                    'created_by'            => 1,
                    'activity_log_id'       => $insert_id_activity_log
            ];
            $this->server_2->where(['addrverid' => $insert_id_addrver]);
            $this->server_2->update('addrverres', $addrverres);

            $addrver_files = [];
            if(!empty($result['file_names'])) {
                $file_names     = explode('||', $result['file_names']);
                $real_filename  = explode('||', $result['real_filename']);
                foreach ($file_names as $key => $file_name) {
                    $real_name = isset($real_filename[$key]) ? $real_filename[$key] : $file_name;
                    $addrver_files[] = [
                            'file_name'         => $file_name,
                            'real_filename'     => $real_name,
                            'addrver_id'        => $insert_id_addrver,
                            'status'            => 1,
                            'type'              => 1
                    ];

                }
                if(isset($addrver_files) && !empty($addrver_files)) {
                    $this->server_2->insert_batch('addrver_files', $addrver_files);
                }
            }

        }
            //$addrver_insuff = [];
            // if($result['insuffraiseddate'] && $result['insuffraiseddate'] != '' && !empty($result['insuffraiseddate'])) {

            //     $insuff_status = 1;
            //     if(!empty($result['insuffcleardate']))
            //         $insuff_status = 2;

            //     $insuff_status = '';
            //     $addrver_insuff[] = [
            //             'insuff_raised_date'        => $result['insuffraiseddate']
            //             'insff_reason'              =>
            //             'insuff_raise_remark'       =>
            //             'insuff_clear_date'         =>
            //             'insuff_cleared_timestamp'  =>
            //             'insuff_cleared_by'         =>
            //             'insuff_remarks'            =>
            //             'addrverid'                 =>
            //             'created_on'                =>
            //             'created_by'                =>
            //             'status'                    =>
            //     ];
            // }

            // if($result['insuff_raised_date_2'] && $result['insuff_raised_date_2'] != '' && !empty($result['insuff_raised_date_2'])) {
            //     $insuff_status = '';
            //     $addrver_insuff[] = [
            //             'insuff_raised_date'        =>
            //             'insff_reason'              =>
            //             'insuff_raise_remark'       =>
            //             'insuff_clear_date'         =>
            //             'insuff_cleared_timestamp'  =>
            //             'insuff_cleared_by'         =>
            //             'insuff_remarks'            =>
            //             'addrverid'                 =>
            //             'created_on'                =>
            //             'created_by'                =>
            //             'status'                    =>
            //     ];
            // }

    } 

    public function employment_migration(){
      
           $sql_empver = "
            SELECT  
                candidates_info.ClientRefNumber, candidates_info.cmp_ref_no, candidates_info.caserecddate, candidates_info.clientid, candidates_info.id as candsid,
                
                status.id as verfstatus_id,status.filter_status,status.report_status,

                empver.id as empverid, empver.created as empver_created,empver.empid,empver.empid, empver.nameofthecompany, empver.has_assigned_on as has_assigned_on,empver.locationaddr, empver.citylocality, empver.empfrom, empver.empto,empver.designation,empver.remuneration,empver.employercontactno,empver.reportingmanager,empver.reasonforleaving,empver.reiniated_date,empver.mail_sent_status,empver.mail_sent_addrs,empver.mail_sent_on,
                
                ev1.modeofverification, ev1.closuredate,  ev1.employed_from, ev1.employed_to, ev1.emp_designation,ev1.empid as res_empid, ev1.reportingmanager as res_reportingmanager, ev1.reasonforleaving as res_reasonforleaving,ev1.remuneration as res_remuneration, ev1.integrity_disciplinary_issue, ev1.remarks as add_result_remarks, ev1.exitformalities, ev1.eligforrehire,ev1.fmlyowned,ev1.verfname,ev1.verfdesgn,ev1.verifiers_role,ev1.verifiers_contact_no,ev1.verifiers_email_id, ev1.verfstatus,ev1.justdialwebcheck,ev1.mcaregn,ev1.domainname,ev1.domainpurch, ev1.id as add_result_id, ev1.created as add_result_created_on, ev1.insuffraiseddate, ev1.insuffcleardate, ev1.insuffremark, ev1.insuff_raised_date_2, ev1.insuff_clear_date_2, ev1.insuff_remarks_2,empver.supervisor_name, empver.supervisor_designation,empver.supervisor_contact_details,empver.supervisor_name_2,empver.supervisor_designation_2,empver.supervisor_contact_details_2,

                GROUP_CONCAT(empverres_files.file_name SEPARATOR '||') as file_names, GROUP_CONCAT(empverres_files.real_filename SEPARATOR '||') as real_filename

            FROM `candidates_info`
            INNER JOIN empver ON empver.candsid =  candidates_info.id
            LEFT JOIN empverres as ev1 ON ev1.empverid = empver.id
            LEFT JOIN empverres as ev2 ON (ev2.empverid = empver.id and ev1.id < ev2.id)
            LEFT JOIN empverres_files ON ( empverres_files.empver_id = ev1.id and empverres_files.status = 1)
            LEFT JOIN status ON (status.action = ev1.verfstatus and components_id = 6)
            WHERE ev2.verfstatus is null
            GROUP BY empver.id

        ";
        $query_empver = $this->db->query($sql_empver);
        $results_empver = $query_empver->result_array();
        
        foreach ($results_empver as $key => $result) {

            // data entry 
            $emprver = [
                    'clientid'          => $result['clientid'],
                    'candsid'           => $result['candsid'],
                    'emp_com_ref'       => $result['cmp_ref_no'].'-'.$result['empverid'],
                    'nameofthecompany'  => $result['nameofthecompany'],
                    'deputed_company'   =>  '',
                    'empid'             => $result['empid'],
                    'employment_type'   => '',
                    'locationaddr'      => $result['locationaddr'],
                    'citylocality'      => $result['citylocality'],
                    'pincode'           => '',
                    'state'             => '',
                    'mode_of_veri'       => '',
                    'created_on'        => $result['empver_created'],
                    'compant_contact'   => $result['employercontactno'],
                    'compant_contact_name' => '',
                    'compant_contact_email' => '',
                    'compant_contact_designation'  => '',
                    'empfrom'           => $result['empfrom'],
                    'empto'             => $result['empto'],
                    'designation'       => $result['designation'],
                    'remuneration'      => $result['remuneration'],
                    'iniated_date'      => $result['caserecddate'],
                    'r_manager_name'    => $result['reportingmanager'],
                    'r_manager_no'      => '',
                    'r_manager_designation' => '',
                    'reasonforleaving'  => $result['reasonforleaving'],
                    'r_manager_email'   => '',
                    'mail_sent_status'  => $result['mail_sent_status'],
                    'mail_sent_addrs'   => $result['mail_sent_addrs'],
                    'mail_sent_on'      => $result['mail_sent_on'],
                    'emp_re_open_date'  => $result['reiniated_date'],
                    'created_by'        => 1,
                    'has_case_id'       => 1,
                    'has_assigned_on'   => $result['has_assigned_on']
            ];
            $this->server_2->insert('empver', $emprver);
            $insert_id_empver = $this->server_2->insert_id();

             if($result['verfstatus'] == "Clear")
            {
                $activity_mode = "Personal Visit";
                $activity_status = "Verification Received";
                $activity_type = "Other";
            }
            elseif($result['verfstatus'] == "Insufficiency")
            {
                $activity_mode = "Insufficiency Raised";
                $activity_status = "Insufficiency Raised";
                $activity_type = "";   
            }
            elseif($result['verfstatus'] == "Stop/Check")
            {
                $activity_mode = "Stop Check";
                $activity_status = "Stop Check";
                $activity_type = "Stop Check";   
            }
            elseif($result['verfstatus'] == "NA")
            {
                $activity_mode = "NA";
                $activity_status = "NA";
                $activity_type = "NA";   
            }
            elseif($result['verfstatus'] == "WIP")
            {
                $activity_mode = "WIP";
                $activity_status = "WIP";
                $activity_type = "WIP";   
            }
            elseif($result['verfstatus'] == "Unable to Verify")
            {
                $activity_mode = "Personal Visit";
                $activity_status = "UTV";
                $activity_type = "UTV";   
            }
            elseif($result['verfstatus'] == "Working With the Same Organization")
            {
                $activity_mode = "Same Organization";
                $activity_status = "Same Organization";
                $activity_type = "Same Organization";   
            }
            else
            {
                $activity_mode = "";
                $activity_status = "";
                $activity_type = "";
            }  

             $status_verification = $this->status_change($result['verfstatus']);  
             
            if($result['verfstatus'] != "Insufficiency" && $result['verfstatus'] != "WIP")
            {
            //activity_log 
            $activity_log = [
                    'candsid'               => $result['candsid'],
                    'ClientRefNumber'       => $result['ClientRefNumber'],
                    'comp_table_id'         => $insert_id_empver,
                    'activity_mode'         => $activity_mode,
                    'activity_status'       => $activity_status,
                    'activity_type'         => $activity_type,
                    'action'                => $result['verfstatus'],
                    'next_follow_up_date'   => '',
                    'remarks'               => $result['add_result_remarks'],
                    'component_type'        => 2,
                    'is_auto_filled'        => '',
                    'created_on'            => isset($result['empver_created']) ? $result['empver_created'] : date('Y-m-d H:i:s')
            ];
            $this->server_2->insert('activity_log', $activity_log);
            $insert_id_activity_log = $this->server_2->insert_id();

            }

            //activity_log 
            $empver_activity_data = [
                    'candsid'               => $result['candsid'],
                    'ClientRefNumber'       => $result['ClientRefNumber'],
                    'comp_table_id'         => $insert_id_empver,
                    'activity_mode'         => $activity_mode,
                    'activity_status'       => $activity_status,
                    'activity_type'         => $activity_type,
                    'action'                => $result['verfstatus'],
                    'next_follow_up_date'   => '',
                    'remarks'               => $result['add_result_remarks'],
                    'is_auto_filled'        => '',
                    'created_on'            => isset($result['empver_created']) ? $result['empver_created'] : date('Y-m-d H:i:s')
            ];
            $this->server_2->insert('empver_activity_data', $empver_activity_data);
           
           
            if($result['supervisor_name'] && $result['supervisor_name'] != '' && !empty($result['supervisor_name'])) {
            
                $supervisor_details1 = [
                    'empver_id'                  => $insert_id_empver,
                    'supervisor_name'            => $result['supervisor_name'],
                    'supervisor_designation'     => $result['supervisor_designation'],
                    'supervisor_contact_details' => $result['supervisor_contact_details'],
                    'supervisor_email_id'        => '',
                    'created_on'                 =>  isset($result['empver_created']) ? $result['empver_created'] : date('Y-m-d H:i:s'),
                    'created_by'                 => 1,
                    'status'                     => 1
                        
                 ];

                $this->server_2->insert('empver_supervisor_details', $supervisor_details1);
            }

            if($result['supervisor_name_2'] && $result['supervisor_name_2'] != '' && !empty($result['supervisor_name_2'])) {
            
                $supervisor_details2 = [
                    'empver_id'                  => $insert_id_empver,
                    'supervisor_name'            => $result['supervisor_name_2'],
                    'supervisor_designation'     => $result['supervisor_designation_2'],
                    'supervisor_contact_details' => $result['supervisor_contact_details_2'],
                    'supervisor_email_id'        => '',
                    'created_on'                 =>  isset($result['empver_created']) ? $result['empver_created'] : date('Y-m-d H:i:s'),
                    'created_by'                 => 1,
                    'status'                     => 1
                        
                 ];

                $this->server_2->insert('empver_supervisor_details', $supervisor_details2);
            }

    
            //empverres 

        if($result['verfstatus'] != "Insufficiency" && $result['verfstatus'] != "WIP")
        {

            if(!empty($result['add_result_id']) && !empty($result['verfstatus'])) {
                $empverres_result = [
                        'verfstatus'            => $status_verification['verfstatus'],
                        'var_filter_status'     => $status_verification['var_filter_status'],
                        'var_report_status'     => $status_verification['var_report_status'],
                        'closuredate'           => $result['closuredate'],
                        'clientid'              => $result['clientid'],
                        'candsid'               => $result['candsid'],
                        'empverid'              => $insert_id_empver,
                        'res_nameofthecompany'  => $result['nameofthecompany'],
                        'res_deputed_company'   => '',
                        'res_employment_type'   => '',
                        'employed_from'         => $result['employed_from'],
                        'employed_to'           => $result['employed_to'],
                        'emp_designation'       => $result['emp_designation'],
                        'res_empid'             => $result['res_empid'],
                        'reportingmanager'      => $result['res_reportingmanager'],
                        'res_reasonforleaving'  => $result['res_reasonforleaving'],
                        'res_remuneration'      => $result['res_remuneration'],
                        'info_integrity_disciplinary_issue'  => $result['integrity_disciplinary_issue'],
                        'info_exitformalities'  => $result['exitformalities'],
                        'info_eligforrehire'    => $result['eligforrehire'],
                        'integrity_disciplinary_issue'  => '',
                        'exitformalities'       => '',
                        'eligforrehire'         => '',
                        'fmlyowned'             => $result['fmlyowned'],
                        'modeofverification'    => $result['modeofverification'],
                        'remarks'               => $result['add_result_remarks'],
                        'verifiers_role'        => $result['verifiers_role'],
                        'verfname'              => $result['verfname'],
                        'verfdesgn'             => $result['verfdesgn'],
                        'verifiers_contact_no'  => $result['verifiers_contact_no'],
                        'justdialwebcheck'      => $result['justdialwebcheck'],
                        'mcaregn'               => $result['mcaregn'],
                        'domainname'            => $result['domainname'],
                        'domainpurch'           => $result['domainpurch'],
                        'nameofthecompany_action' => 'yes',
                        'deputed_company_action'  => 'yes',
                        'employment_type_action'  => 'yes',
                        'employed_from_action'    => 'yes',
                        'employed_to_action'      => 'yes',
                        'emp_designation_action'  => 'yes',
                        'empid_action'            => 'yes',
                        'reportingmanager_action' => 'yes',
                        'reasonforleaving_action' => 'yes',
                        'remuneration_action'     => 'yes',
                        'created_on'            => $result['add_result_created_on'],
                        'created_by'            => 1,
                        'activity_log_id'       => $insert_id_activity_log
                ];

                $this->server_2->insert('empverres_logs', $empverres_result);
            }
        }

            
            // update status 
            $empverres = [
                        'verfstatus'            => $status_verification['verfstatus'],
                        'var_filter_status'     => $status_verification['var_filter_status'],
                        'var_report_status'     => $status_verification['var_report_status'],
                        'closuredate'           => $result['closuredate'],
                        'clientid'              => $result['clientid'],
                        'candsid'               => $result['candsid'],
                        'empverid'              => $insert_id_empver,
                        'res_nameofthecompany'  => $result['nameofthecompany'],
                        'res_deputed_company'   => '',
                        'res_employment_type'   => '',
                        'employed_from'         => $result['employed_from'],
                        'employed_to'           => $result['employed_to'],
                        'emp_designation'       => $result['emp_designation'],
                        'res_empid'             => $result['res_empid'],
                        'reportingmanager'      => $result['res_reportingmanager'],
                        'res_reasonforleaving'  => $result['res_reasonforleaving'],
                        'res_remuneration'      => $result['res_remuneration'],
                        'info_integrity_disciplinary_issue'  => $result['integrity_disciplinary_issue'],
                        'info_exitformalities'  => $result['exitformalities'],
                        'info_eligforrehire'    => $result['eligforrehire'],
                        'integrity_disciplinary_issue'  => '',
                        'exitformalities'       => '',
                        'eligforrehire'         => '',
                        'fmlyowned'             => $result['fmlyowned'],
                        'modeofverification'    => $result['modeofverification'],
                        'remarks'               => $result['add_result_remarks'],
                        'verifiers_role'        => $result['verifiers_role'],
                        'verfname'              => $result['verfname'],
                        'verfdesgn'             => $result['verfdesgn'],
                        'verifiers_contact_no'  => $result['verifiers_contact_no'],
                        'justdialwebcheck'      => $result['justdialwebcheck'],
                        'mcaregn'               => $result['mcaregn'],
                        'domainname'            => $result['domainname'],
                        'domainpurch'           => $result['domainpurch'],
                        'nameofthecompany_action' => 'yes',
                        'deputed_company_action'  => 'yes',
                        'employment_type_action'  => 'yes',
                        'employed_from_action'    => 'yes',
                        'employed_to_action'      => 'yes',
                        'emp_designation_action'  => 'yes',
                        'empid_action'            => 'yes',
                        'reportingmanager_action' => 'yes',
                        'reasonforleaving_action' => 'yes',
                        'remuneration_action'     => 'yes',
                        'created_on'            => $result['add_result_created_on'],
                        'created_by'            => 1,
                        'activity_log_id'       => $insert_id_activity_log
            ];
            $this->server_2->where(['empverid' => $insert_id_empver]);
            $this->server_2->update('empverres', $empverres);

            $empver_files = [];
            if(!empty($result['file_names'])) {
                $file_names     = explode('||', $result['file_names']);
                $real_filename  = explode('||', $result['real_filename']);
                foreach ($file_names as $key => $file_name) {
                    $real_name = isset($real_filename[$key]) ? $real_filename[$key] : $file_name;
                    $empver_files[] = [
                            'file_name'         => $file_name,
                            'real_filename'     => $real_name,
                            'empver_id'         => $insert_id_empver,
                            'status'            => 1,
                            'type'              => 1
                    ];

                }
                if(isset($empver_files) && !empty($empver_files)) {
                    $this->server_2->insert_batch('empverres_files', $empver_files);
                }
            }
        }

    }


    public function education_migration() {

         $sql_education = "
            SELECT  
                candidates_info.ClientRefNumber, candidates_info.cmp_ref_no, candidates_info.caserecddate, candidates_info.clientid, candidates_info.id as candsid,
                
                status.id as verfstatus_id,status.filter_status,status.report_status,

                eduver.id as eduverid, eduver.created as eduver_created,eduver.school,eduver.university,eduver.qualification,eduver.is_assigned_on as has_assigned_on,eduver.grade,eduver.major,eduver.coursestart,eduver.courseend,eduver.yearofpassing,eduver.rollno,eduver.enrollno,eduver.docsprovided,eduver.reiniated_date,
                
                ev1.modeofverf, ev1.ClosureDate,ev1.verfname, ev1.verfdesgn, ev1.verfcontacts,ev1.verfremarks as add_result_remarks,ev1.id as add_result_id, ev1.created as add_result_created_on,ev1.verfstatus, ev1.InsuffRaisedDate, ev1.InsuffClearDate, ev1.insuff_remarks, ev1.insuff_raised_date_2, ev1.insuff_clear_date_2, ev1.insuff_remarks_2, 

                GROUP_CONCAT(eduver_files.file_name SEPARATOR '||') as file_names, GROUP_CONCAT(eduver_files.real_filename SEPARATOR '||') as real_filename

            FROM `candidates_info`
            INNER JOIN eduver ON eduver.candsid =  candidates_info.id
            LEFT JOIN eduverres as ev1 ON ev1.eduverid = eduver.id
            LEFT JOIN eduverres as ev2 ON (ev2.eduverid = eduver.id and ev1.id < ev2.id)
            LEFT JOIN eduver_files ON ( eduver_files.eduver_id = ev1.id and eduver_files.status = 1)
            LEFT JOIN status ON (status.action = ev1.verfstatus and components_id = 6)
            WHERE ev2.verfstatus is null
            GROUP BY eduver.id

        ";
        $query_education = $this->db->query($sql_education);
        $results_education = $query_education->result_array();
        
        foreach ($results_education as $key => $result) {

            // data entry 
            $education = [
                    'clientid'          => $result['clientid'],
                    'candsid'           => $result['candsid'],
                    'education_com_ref' => $result['cmp_ref_no'].'-'.$result['eduverid'],
                    'school_college'    => $result['school'],
                    'university_board'  => $result['university'],
                    'grade_class_marks' => $result['grade'],
                    'qualification'     => $result['qualification'],
                    'major'             => $result['major'],
                    'course_start_date' => $result['coursestart'],
                    'course_end_date'   => $result['courseend'],
                    'month_of_passing'  => '',
                    'year_of_passing'   => $result['yearofpassing'],
                    'roll_no'           => $result['rollno'],
                    'enrollment_no'     => $result['enrollno'],
                    'PRN_no'            => '',
                    'documents_provided'=> $result['docsprovided'],
                    'genuineness'       => '',
                    'online_URL'        => '',
                    'iniated_date'      => $result['caserecddate'],
                    'city'              => '',
                    'state'             => '',
                    'mode_of_veri'      => '',
                    'edu_re_open_date'  => $result['reiniated_date'],
                    'created_by'        => 1,
                    'has_case_id'       => 1,
                    'vendor_id'         => 1,
                    'has_assigned_on'   => $result['has_assigned_on'],
                    'created_on'        => $result['eduver_created']
            ];
            $this->server_2->insert('education', $education);
            $insert_id_education = $this->server_2->insert_id();


            if($result['verfstatus'] == "Clear")
            {
                $activity_mode = "Personal Visit";
                $activity_status = "Verification Received";
                $activity_type = "Other";
            }
            elseif($result['verfstatus'] == "Insufficiency")
            {
                $activity_mode = "Insufficiency Raised";
                $activity_status = "Insufficiency Raised";
                $activity_type = "";   
            }
            elseif($result['verfstatus'] == "Stop/Check")
            {
                $activity_mode = "Stop Check";
                $activity_status = "Stop Check";
                $activity_type = "Stop Check";   
            }
            elseif($result['verfstatus'] == "NA")
            {
                $activity_mode = "NA";
                $activity_status = "NA";
                $activity_type = "NA";   
            }
            elseif($result['verfstatus'] == "WIP")
            {
                $activity_mode = "WIP";
                $activity_status = "WIP";
                $activity_type = "WIP";   
            }
            elseif($result['verfstatus'] == "Unable to Verify")
            {
                $activity_mode = "Personal Visit";
                $activity_status = "UTV";
                $activity_type = "UTV";   
            }
            elseif($result['verfstatus'] == "Working With the Same Organization")
            {
                $activity_mode = "Same Organization";
                $activity_status = "Same Organization";
                $activity_type = "Same Organization";   
            }
            elseif($result['verfstatus'] == "No Record Found")
            {
                $activity_mode = "Verbal/Written";
                $activity_status = "No Record Found";
                $activity_type = "Collage/University";   
            }
            else
            {
                $activity_mode = "";
                $activity_status = "";
                $activity_type = "";
            }  

            $status_verification = $this->status_change($result['verfstatus']);   

            if($result['verfstatus'] != "Insufficiency" && $result['verfstatus'] != "WIP")
            {
            //activity_log 
            $activity_log = [
                    'candsid'               => $result['candsid'],
                    'ClientRefNumber'       => $result['ClientRefNumber'],
                    'comp_table_id'         => $insert_id_education,
                    'activity_mode'         => $activity_mode,
                    'activity_status'       => $activity_status,
                    'activity_type'         => $activity_type,
                    'action'                => $result['verfstatus'],
                    'next_follow_up_date'   => '',
                    'remarks'               => $result['add_result_remarks'],
                    'component_type'        => 3,
                    'is_auto_filled'        => '',
                    'created_on'            => isset($result['eduver_created']) ? $result['eduver_created'] : date('Y-m-d H:i:s')
            ];
            $this->server_2->insert('activity_log', $activity_log);
            $insert_id_activity_log = $this->server_2->insert_id();

            }
            //activity_log 
            $education_activity_data = [
                    'candsid'               => $result['candsid'],
                    'ClientRefNumber'       => $result['ClientRefNumber'],
                    'comp_table_id'         => $insert_id_education,
                    'activity_mode'         => $activity_mode,
                    'activity_status'       => $activity_status,
                    'activity_type'         => $activity_type,
                    'action'                => $result['verfstatus'],
                    'next_follow_up_date'   => '',
                    'remarks'               => $result['add_result_remarks'],
                    'is_auto_filled'        => '',
                    'created_on'            => isset($result['eduver_created']) ? $result['eduver_created'] : date('Y-m-d H:i:s')
            ];
            $this->server_2->insert('education_activity_data', $education_activity_data);
    
            //education result

        if($result['verfstatus'] != "Insufficiency" && $result['verfstatus'] != "WIP")
        {
            if(!empty($result['add_result_id']) && !empty($result['verfstatus'])) {
                $education_ver_result = [
                        'verfstatus'            => $status_verification['verfstatus'],
                        'var_filter_status'     => $status_verification['var_filter_status'],
                       'var_report_status'     => $status_verification['var_report_status'],
                        'closuredate'           => $result['ClosureDate'],
                        'clientid'              => $result['clientid'],
                        'candsid'               => $result['candsid'],
                        'education_id'          => $insert_id_education,
                        'res_qualification'     => $result['qualification'],
                        'res_university_board'  => $result['university'],
                        'res_school_college'    => $result['school'],
                        'res_major'             => $result['major'],
                        'res_month_of_passing'  => '',
                        'res_year_of_passing'   => $result['yearofpassing'],
                        'res_grade_class_marks' => $result['grade'],
                        'res_course_start_date' => $result['coursestart'],
                        'res_course_end_date'   => $result['courseend'],
                        'res_roll_no'           => $result['rollno'],
                        'res_enrollment_no'     => $result['enrollno'],
                        'res_PRN_no'            => '',
                        'res_mode_of_verification'=>$result['modeofverf'],
                        'verified_by'            => $result['verfname'],
                        'verifier_designation'   => $result['verfdesgn'],
                        'verifier_contact_details'=> $result['verfcontacts'],
                        'res_genuineness'        => '',
                        'remarks'                => $result['add_result_remarks'],
                        'qualification_action'   => 'yes',
                        'school_college_action'  => 'yes',
                        'university_board_action'=> 'yes',
                        'major_action'           => 'yes',
                        'month_of_passing_action'=> 'yes',
                        'year_of_passing_action' => 'yes',
                        'grade_class_marks_action'=> 'yes',
                        'course_start_date_action'=> 'yes',
                        'course_end_date_action' => 'yes',
                        'roll_no_action'         => 'yes',
                        'enrollment_no_action'   => 'yes',
                        'prn_no_action'         => 'yes',
                        'created_on'            => $result['add_result_created_on'],
                        'created_by'            => 1,
                        'activity_log_id'       => $insert_id_activity_log
                ];

                $this->server_2->insert('education_ver_result', $education_ver_result);
            }

        }    
            // update status 
            $education_result = [
                        'verfstatus'            => $status_verification['verfstatus'],
                        'var_filter_status'     => $status_verification['var_filter_status'],
                        'var_report_status'     => $status_verification['var_report_status'],
                        'closuredate'           => $result['ClosureDate'],
                        'clientid'              => $result['clientid'],
                        'candsid'               => $result['candsid'],
                        'education_id'          => $insert_id_education,
                        'res_qualification'     => $result['qualification'],
                        'res_university_board'  => $result['university'],
                        'res_school_college'    => $result['school'],
                        'res_major'             => $result['major'],
                        'res_month_of_passing'  => '',
                        'res_year_of_passing'   => $result['yearofpassing'],
                        'res_grade_class_marks' => $result['grade'],
                        'res_course_start_date' => $result['coursestart'],
                        'res_course_end_date'   => $result['courseend'],
                        'res_roll_no'           => $result['rollno'],
                        'res_enrollment_no'     => $result['enrollno'],
                        'res_PRN_no'            => '',
                        'res_mode_of_verification'=>$result['modeofverf'],
                        'verified_by'            => $result['verfname'],
                        'verifier_designation'   => $result['verfdesgn'],
                        'verifier_contact_details'=> $result['verfcontacts'],
                        'res_genuineness'        => '',
                        'remarks'                => $result['add_result_remarks'],
                        'qualification_action'   => 'yes',
                        'school_college_action'  => 'yes',
                        'university_board_action'=> 'yes',
                        'major_action'           => 'yes',
                        'month_of_passing_action'=> 'yes',
                        'year_of_passing_action' => 'yes',
                        'grade_class_marks_action'=> 'yes',
                        'course_start_date_action'=> 'yes',
                        'course_end_date_action' => 'yes',
                        'roll_no_action'         => 'yes',
                        'enrollment_no_action'   => 'yes',
                        'prn_no_action'         => 'yes',
                        'created_on'            => $result['add_result_created_on'],
                        'created_by'            => 1,
                        'activity_log_id'       => $insert_id_activity_log
            ];
            $this->server_2->where(['education_id' => $insert_id_education]);
            $this->server_2->update('education_result', $education_result);

            $education_files = [];
            if(!empty($result['file_names'])) {
                $file_names     = explode('||', $result['file_names']);
                $real_filename  = explode('||', $result['real_filename']);
                foreach ($file_names as $key => $file_name) {
                    $real_name = isset($real_filename[$key]) ? $real_filename[$key] : $file_name;
                    $education_files[] = [
                            'file_name'         => $file_name,
                            'real_filename'     => $real_name,
                            'education_id'         => $insert_id_education,
                            'status'            => 1,
                            'type'              => 1
                    ];

                }
                if(isset($education_files) && !empty($education_files)) {
                    $this->server_2->insert_batch('education_files', $education_files);
                }
            }
        }

    } 


    public function reference_migration(){

        $sql_reference = "
            SELECT  
                candidates_info.ClientRefNumber, candidates_info.cmp_ref_no, candidates_info.caserecddate, candidates_info.clientid, candidates_info.id as candsid,
                
                status.id as verfstatus_id,status.filter_status,status.report_status,

                refver.id as referenceid, refver.created as reference_created,refver.referencename,refver.designation,refver.contact_number,refver.updated as has_assigned_on,refver.ref_emial_id,refver.reiniated_date,
                
                ev1.closuredate,ev1.additional_remarks as add_result_remarks,ev1.id as add_result_id, ev1.created as add_result_created_on,ev1.verfstatus, ev1.insuffraiseddate, ev1.insuffcleardate, ev1.insuffremark, 

                GROUP_CONCAT(refverres_files.file_name SEPARATOR '||') as file_names, GROUP_CONCAT(refverres_files.real_filename SEPARATOR '||') as real_filename

            FROM `candidates_info`
            INNER JOIN refver ON refver.candsid =  candidates_info.id
            LEFT JOIN refverres as ev1 ON ev1.refverid = refver.id
            LEFT JOIN refverres as ev2 ON (ev2.refverid = refver.id and ev1.id < ev2.id)
            LEFT JOIN refverres_files ON ( refverres_files.refverres_id = ev1.id and refverres_files.status = 1)
            LEFT JOIN status ON (status.action = ev1.verfstatus and components_id = 6)
            WHERE ev2.verfstatus is null
            GROUP BY refver.id

        ";
        $query_reference = $this->db->query($sql_reference);
        $results_reference = $query_reference->result_array();
        
        foreach ($results_reference as $key => $result) {

            // data entry 
            $reference = [
                    'clientid'          => $result['clientid'],
                    'candsid'           => $result['candsid'],
                    'reference_com_ref' => $result['cmp_ref_no'].'-'.$result['referenceid'],
                    'name_of_reference' => $result['referencename'],
                    'designation'       => $result['designation'],
                    'contact_no'        => $result['contact_number'],
                    'contact_no_first'  => '',
                    'contact_no_second' => '',
                    'email_id'          => $result['ref_emial_id'],
                    'mode_of_veri'      => '',
                    'iniated_date'      => $result['caserecddate'],
                    'reference_re_open_date'  => $result['reiniated_date'],
                    'created_by'        => 1,
                    'has_case_id'       => 1,
                    'has_assigned_on'   => $result['has_assigned_on']
            ];
            $this->server_2->insert('reference', $reference);
            $insert_id_reference = $this->server_2->insert_id();

            if($result['verfstatus'] == "Clear")
            {
                $activity_mode = "Personal Visit";
                $activity_status = "Verification Received";
                $activity_type = "Other";
            }
            elseif($result['verfstatus'] == "Insufficiency")
            {
                $activity_mode = "Insufficiency Raised";
                $activity_status = "Insufficiency Raised";
                $activity_type = "";   
            }
            elseif($result['verfstatus'] == "Stop/Check")
            {
                $activity_mode = "Stop Check";
                $activity_status = "Stop Check";
                $activity_type = "Stop Check";   
            }
            elseif($result['verfstatus'] == "NA")
            {
                $activity_mode = "NA";
                $activity_status = "NA";
                $activity_type = "NA";   
            }
            elseif($result['verfstatus'] == "WIP")
            {
                $activity_mode = "WIP";
                $activity_status = "WIP";
                $activity_type = "WIP";   
            }
            elseif($result['verfstatus'] == "Unable to Verify")
            {
                $activity_mode = "Personal Visit";
                $activity_status = "UTV";
                $activity_type = "UTV";   
            }
            else
            {
                $activity_mode = "";
                $activity_status = "";
                $activity_type = "";
            } 

            $status_verification = $this->status_change($result['verfstatus']);    

            if($result['verfstatus'] != "Insufficiency" && $result['verfstatus'] != "WIP")
            {

            //activity_log 
            $activity_log = [
                    'candsid'               => $result['candsid'],
                    'ClientRefNumber'       => $result['ClientRefNumber'],
                    'comp_table_id'         => $insert_id_reference,
                    'activity_mode'         => $activity_mode,
                    'activity_status'       => $activity_status,
                    'activity_type'         => $activity_type,
                    'action'                => $result['verfstatus'],
                    'next_follow_up_date'   => '',
                    'remarks'               => $result['add_result_remarks'],
                    'component_type'        => 4,
                    'is_auto_filled'        => '',
                    'created_on'            => isset($result['reference_created']) ? $result['reference_created'] : date('Y-m-d H:i:s')
            ];
            $this->server_2->insert('activity_log', $activity_log);
            $insert_id_activity_log = $this->server_2->insert_id();
            }
            //activity_log 
            $reference_activity_data = [
                    'candsid'               => $result['candsid'],
                    'ClientRefNumber'       => $result['ClientRefNumber'],
                    'comp_table_id'         => $insert_id_reference,
                    'activity_mode'         => $activity_mode,
                    'activity_status'       => $activity_status,
                    'activity_type'         => $activity_type,
                    'action'                => $result['verfstatus'],
                    'next_follow_up_date'   => '',
                    'remarks'               => $result['add_result_remarks'],
                    'is_auto_filled'        => '',
                    'created_on'            => isset($result['reference_created']) ? $result['reference_created'] : date('Y-m-d H:i:s')
            ];
            $this->server_2->insert('reference_activity_data', $reference_activity_data);
    
            //education result

        if($result['verfstatus'] != "Insufficiency" && $result['verfstatus'] != "WIP")
        {   
            if(!empty($result['add_result_id']) && !empty($result['verfstatus'])) {
                $reference_ver_result = [
                        'verfstatus'            => $status_verification['verfstatus'],
                        'var_filter_status'     => $status_verification['var_filter_status'],
                        'var_report_status'     => $status_verification['var_report_status'],
                        'closuredate'           => $result['closuredate'],
                        'clientid'              => $result['clientid'],
                        'candsid'               => $result['candsid'],
                        'reference_id'          => $insert_id_reference,
                        'remarks'                => $result['add_result_remarks'],
                        'created_on'            => $result['add_result_created_on'],
                        'created_by'            => 1,
                        'activity_log_id'       => $insert_id_activity_log
                ];

                $this->server_2->insert('reference_ver_result', $reference_ver_result);
            }
        }
            
            // update status 
           $reference_result = [
                        'verfstatus'            => $status_verification['verfstatus'],
                        'var_filter_status'     => $status_verification['var_filter_status'],
                        'var_report_status'     => $status_verification['var_report_status'],
                        'closuredate'           => $result['closuredate'],
                        'clientid'              => $result['clientid'],
                        'candsid'               => $result['candsid'],
                        'reference_id'          => $insert_id_reference,
                        'remarks'                => $result['add_result_remarks'],
                        'created_on'            => $result['add_result_created_on'],
                        'created_by'            => 1,
                        'activity_log_id'       => $insert_id_activity_log
                ];


            $this->server_2->where(['reference_id' => $insert_id_reference]);
            $this->server_2->update('reference_result', $reference_result);

            $reference_files = [];
            if(!empty($result['file_names'])) {
                $file_names     = explode('||', $result['file_names']);
                $real_filename  = explode('||', $result['real_filename']);
                foreach ($file_names as $key => $file_name) {
                    $real_name = isset($real_filename[$key]) ? $real_filename[$key] : $file_name;
                    $reference_files[] = [
                            'file_name'         => $file_name,
                            'real_filename'     => $real_name,
                            'reference_id'      => $insert_id_reference,
                            'status'            => 1,
                            'type'              => 1
                    ];

                }
                if(isset($reference_files) && !empty($reference_files)) {
                    $this->server_2->insert_batch('reference_files', $reference_files);
                }
            }
        } 
    }

    public function court_migration(){

          $sql_court = "
            SELECT  
                candidates_info.ClientRefNumber, candidates_info.cmp_ref_no, candidates_info.caserecddate, candidates_info.clientid, candidates_info.id as candsid,
                
                status.id as verfstatus_id,status.filter_status,status.report_status,

                courtver.id as courtverid, courtver.created as courtver_created,courtver.verification_address,courtver.reiniated_date,courtver.is_assigned_on as has_assigned_on,
                
                courtver.closuredate,courtver.remarks as add_result_remarks,courtver.id as add_result_id, courtver.created as add_result_created_on,courtver.verfstatus, courtver.insuffraiseddate, courtver.insuffcleardate, courtver.modeofverification,courtver.verifiername,

                GROUP_CONCAT(courtver_files.file_name SEPARATOR '||') as file_names, GROUP_CONCAT(courtver_files.real_filename SEPARATOR '||') as real_filename

            FROM `candidates_info`
            INNER JOIN courtver ON courtver.candsid =  candidates_info.id
            LEFT JOIN courtver_files ON ( courtver_files.courtver_id = courtver.id and courtver_files.status = 1)
            LEFT JOIN status ON (status.action = courtver.verfstatus and components_id = 6)
            GROUP BY courtver.id 

        ";

        $query_court = $this->db->query($sql_court);
        $results_court = $query_court->result_array();
      
        foreach ($results_court as $key => $result) {

            // data entry 
            $Court = [
                    'clientid'          => $result['clientid'],
                    'candsid'           => $result['candsid'],
                    'court_com_ref'     => $result['cmp_ref_no'].'-'.$result['courtverid'],
                    'iniated_date'      => $result['caserecddate'],
                    'address_type'      => '',
                    'street_address'    => $result['verification_address'],
                    'city'              => '',
                    'pincode'           => '',
                    'state'             => '',
                    'mode_of_veri'      => '',
                    'courtver_re_open_date' => $result['reiniated_date'],
                    'created_by'        => 1,
                    'has_case_id'       => 1,
                    'vendor_id'         => 1,
                    'has_assigned_on'   => $result['has_assigned_on']
            ];
            $this->server_2->insert('courtver', $Court);
            $insert_id_court = $this->server_2->insert_id();

            if($result['verfstatus'] == "Clear")
            {
                $activity_mode = "Personal Visit";
                $activity_status = "Verification Received";
                $activity_type = "Other";
            }
            elseif($result['verfstatus'] == "Insufficiency")
            {
                $activity_mode = "Insufficiency Raised";
                $activity_status = "Insufficiency Raised";
                $activity_type = "";   
            }
            elseif($result['verfstatus'] == "Stop/Check")
            {
                $activity_mode = "Stop Check";
                $activity_status = "Stop Check";
                $activity_type = "Stop Check";   
            }
            elseif($result['verfstatus'] == "NA")
            {
                $activity_mode = "NA";
                $activity_status = "NA";
                $activity_type = "NA";   
            }
            elseif($result['verfstatus'] == "WIP")
            {
                $activity_mode = "WIP";
                $activity_status = "WIP";
                $activity_type = "WIP";   
            }
            elseif($result['verfstatus'] == "Unable to Verify")
            {
                $activity_mode = "Personal Visit";
                $activity_status = "UTV";
                $activity_type = "UTV";   
            }
            else
            {
                $activity_mode = "";
                $activity_status = "";
                $activity_type = "";
            } 

            $status_verification = $this->status_change($result['verfstatus']);    

            if($result['verfstatus'] != "Insufficiency" && $result['verfstatus'] != "WIP")
            {
            //activity_log 
            $activity_log = [
                    'candsid'               => $result['candsid'],
                    'ClientRefNumber'       => $result['ClientRefNumber'],
                    'comp_table_id'         => $insert_id_court,
                    'activity_mode'         => $activity_mode,
                    'activity_status'       => $activity_status,
                    'activity_type'         => $activity_type,
                    'action'                => $result['verfstatus'],
                    'next_follow_up_date'   => '',
                    'remarks'               => $result['add_result_remarks'],
                    'component_type'        => 5,
                    'is_auto_filled'        => '',
                    'created_on'            => isset($result['courtver_created']) ? $result['courtver_created'] : date('Y-m-d H:i:s')
            ];
            $this->server_2->insert('activity_log', $activity_log);
            $insert_id_activity_log = $this->server_2->insert_id();
            
            }
            //activity_log 
            $court_activity_data = [
                    'candsid'               => $result['candsid'],
                    'ClientRefNumber'       => $result['ClientRefNumber'],
                    'comp_table_id'         => $insert_id_court,
                    'activity_mode'         => $activity_mode,
                    'activity_status'       => $activity_status,
                    'activity_type'         => $activity_type,
                    'action'                => $result['verfstatus'],
                    'next_follow_up_date'   => '',
                    'remarks'               => $result['add_result_remarks'],
                    'is_auto_filled'        => '',
                    'created_on'            => isset($result['courtver_created']) ? $result['courtver_created'] : date('Y-m-d H:i:s')
            ];
            $this->server_2->insert('court_activity_data', $court_activity_data);
    
        if($result['verfstatus'] != "Insufficiency" && $result['verfstatus'] != "WIP")
        {
            //Court result
            if(!empty($result['add_result_id']) && !empty($result['verfstatus'])) {
                $court_ver_result = [
                        'verfstatus'            => $status_verification['verfstatus'],
                        'var_filter_status'     => $status_verification['var_filter_status'],
                        'var_report_status'     => $status_verification['var_report_status'],
                        'closuredate'           => $result['closuredate'],
                        'clientid'              => $result['clientid'],
                        'candsid'               => $result['candsid'],
                        'courtver_id'           => $insert_id_court,
                        'mode_of_verification'  =>$result['modeofverification'],
                        'advocate_name'         => $result['verifiername'],
                        'remarks'               => $result['add_result_remarks'],
                        'created_on'            => $result['add_result_created_on'],
                        'created_by'            => 1,
                        'activity_log_id'       => $insert_id_activity_log
                ];

                $this->server_2->insert('courtver_ver_result', $court_ver_result);
            }
        }
            
            // update status 
            $court_result = [
                        'verfstatus'            => $status_verification['verfstatus'],
                        'var_filter_status'     => $status_verification['var_filter_status'],
                        'var_report_status'     => $status_verification['var_report_status'],
                        'closuredate'           => $result['closuredate'],
                        'clientid'              => $result['clientid'],
                        'candsid'               => $result['candsid'],
                        'courtver_id'           => $insert_id_court,
                        'mode_of_verification'  => $result['modeofverification'],
                        'advocate_name'         => $result['verifiername'],
                        'remarks'               => $result['add_result_remarks'],
                        'created_on'            => $result['add_result_created_on'],
                        'created_by'            => 1,
                        'activity_log_id'       => $insert_id_activity_log
                ];

            $this->server_2->where(['courtver_id' => $insert_id_court]);
            $this->server_2->update('courtver_result', $court_result);

            $court_files = [];
            if(!empty($result['file_names'])) {
                $file_names     = explode('||', $result['file_names']);
                $real_filename  = explode('||', $result['real_filename']);
                foreach ($file_names as $key => $file_name) {
                    $real_name = isset($real_filename[$key]) ? $real_filename[$key] : $file_name;
                    $court_files[] = [
                            'file_name'         => $file_name,
                            'real_filename'     => $real_name,
                            'courtver_id'       => $insert_id_court,
                            'status'            => 1,
                            'type'              => 1
                    ];

                }
                if(isset($court_files) && !empty($court_files)) {
                    $this->server_2->insert_batch('courtver_files', $court_files);
                }
            }
        }
    }


    public function credit_report_migration(){

        $sql_credit_report = "
            SELECT  
                candidates_info.ClientRefNumber, candidates_info.cmp_ref_no, candidates_info.caserecddate, candidates_info.clientid, candidates_info.id as candsid,
                
                status.id as verfstatus_id,status.filter_status,status.report_status,

                creditcheck.id as credit_id, creditcheck.created as credit_created,creditcheck.verification_address,creditcheck.reiniated_date,
                
                creditcheck.closuredate,creditcheck.remarks as add_result_remarks,creditcheck.id as add_result_id, creditcheck.created as add_result_created_on,creditcheck.verfstatus, creditcheck.insuffraiseddate, creditcheck.insuffcleardate, creditcheck.modeofverification,creditcheck.verifiername,

                GROUP_CONCAT(creditcheck_files.file_name SEPARATOR '||') as file_names, GROUP_CONCAT(creditcheck_files.real_filename SEPARATOR '||') as real_filename

            FROM `candidates_info`
            INNER JOIN creditcheck ON creditcheck.candsid =  candidates_info.id
            LEFT JOIN creditcheck_files ON ( creditcheck_files.courtver_id = creditcheck.id and creditcheck_files.status = 1)
            LEFT JOIN status ON (status.action = creditcheck.verfstatus and components_id = 6)
            GROUP BY creditcheck.id 

        ";
        $query_credit_report = $this->db->query($sql_credit_report);
        $results_credit_report = $query_credit_report->result_array();
      
        foreach ($results_credit_report as $key => $result) {

            // data entry 
            $credit_report = [
                    'clientid'          => $result['clientid'],
                    'candsid'           => $result['candsid'],
                    'credit_report_com_ref' => $result['cmp_ref_no'].'-'.$result['credit_id'],
                    'iniated_date'      => $result['caserecddate'],
                    'doc_submited'      => '',
                    'id_number'      => '',
                    'street_address'    => $result['verification_address'],
                    'city'              => '',
                    'pincode'           => '',
                    'state'             => '',
                    'mode_of_veri'      => '',
                    'credit_report_re_open_date' => $result['reiniated_date'],
                    'created_by'        => 1,
                    'vendor_id'         => 1,
                    'has_case_id'       => 1
            ];
            $this->server_2->insert('credit_report', $credit_report);
            $insert_id_credit = $this->server_2->insert_id();

              if($result['verfstatus'] == "Clear")
            {
                $activity_mode = "Personal Visit";
                $activity_status = "Verification Received";
                $activity_type = "Other";
            }
            elseif($result['verfstatus'] == "Insufficiency")
            {
                $activity_mode = "Insufficiency Raised";
                $activity_status = "Insufficiency Raised";
                $activity_type = "";   
            }
            elseif($result['verfstatus'] == "Stop/Check")
            {
                $activity_mode = "Stop Check";
                $activity_status = "Stop Check";
                $activity_type = "Stop Check";   
            }
            elseif($result['verfstatus'] == "NA")
            {
                $activity_mode = "NA";
                $activity_status = "NA";
                $activity_type = "NA";   
            }
            elseif($result['verfstatus'] == "WIP")
            {
                $activity_mode = "WIP";
                $activity_status = "WIP";
                $activity_type = "WIP";   
            }
            elseif($result['verfstatus'] == "Unable to Verify")
            {
                $activity_mode = "Personal Visit";
                $activity_status = "UTV";
                $activity_type = "UTV";   
            }
            else
            {
                $activity_mode = "";
                $activity_status = "";
                $activity_type = "";
            } 

             $status_verification = $this->status_change($result['verfstatus']);    

            if($result['verfstatus'] != "Insufficiency" && $result['verfstatus'] != "WIP")
            {

            //activity_log 
            $activity_log = [
                    'candsid'               => $result['candsid'],
                    'ClientRefNumber'       => $result['ClientRefNumber'],
                    'comp_table_id'         => $insert_id_credit,
                    'activity_mode'         => $activity_mode,
                    'activity_status'       => $activity_status,
                    'activity_type'         => $activity_type,
                    'action'                => $result['verfstatus'],
                    'next_follow_up_date'   => '',
                    'remarks'               => $result['add_result_remarks'],
                    'component_type'        => 10,
                    'is_auto_filled'        => '',
                    'created_on'            => isset($result['credit_created']) ? $result['credit_created'] : date('Y-m-d H:i:s')
            ];
            $this->server_2->insert('activity_log', $activity_log);
            $insert_id_activity_log = $this->server_2->insert_id();

            }

            //activity_log 
            $credit_activity_data = [
                    'candsid'               => $result['candsid'],
                    'ClientRefNumber'       => $result['ClientRefNumber'],
                    'comp_table_id'         => $insert_id_credit,
                    'activity_mode'         => $activity_mode,
                    'activity_status'       => $activity_status,
                    'activity_type'         => $activity_type,
                    'action'                => $result['verfstatus'],
                    'next_follow_up_date'   => '',
                    'remarks'               => $result['add_result_remarks'],
                    'is_auto_filled'        => '',
                    'created_on'            => isset($result['courtver_created']) ? $result['courtver_created'] : date('Y-m-d H:i:s')
            ];
            $this->server_2->insert('credit_report_activity_data', $credit_activity_data);
    
            //Court result
        if($result['verfstatus'] != "Insufficiency" && $result['verfstatus'] != "WIP")
        {    
            if(!empty($result['add_result_id']) && !empty($result['verfstatus'])) {
                $credit_report_ver_result = [
                        'verfstatus'            => $status_verification['verfstatus'],
                        'var_filter_status'     => $status_verification['var_filter_status'],
                        'var_report_status'     => $status_verification['var_report_status'],
                        'closuredate'           => $result['closuredate'],
                        'clientid'              => $result['clientid'],
                        'candsid'               => $result['candsid'],
                        'credit_report_id'      => $insert_id_credit,
                        'mode_of_verification'  =>$result['modeofverification'],
                        'remarks'               => $result['add_result_remarks'],
                        'created_on'            => $result['add_result_created_on'],
                        'created_by'            => 1,
                        'activity_log_id'       => $insert_id_activity_log
                ];

                $this->server_2->insert('credit_report_ver_result', $credit_report_ver_result);
            }
        }
            
            // update status 
            $credit_result = [
                        'verfstatus'            => $status_verification['verfstatus'],
                        'var_filter_status'     => $status_verification['var_filter_status'],
                        'var_report_status'     => $status_verification['var_report_status'],
                        'closuredate'           => $result['closuredate'],
                        'clientid'              => $result['clientid'],
                        'candsid'               => $result['candsid'],
                        'credit_report_id'      => $insert_id_credit,
                        'mode_of_verification'  => $result['modeofverification'],
                        'remarks'               => $result['add_result_remarks'],
                        'created_on'            => $result['add_result_created_on'],
                        'created_by'            => 1,
                        'activity_log_id'       => $insert_id_activity_log
                ];

            $this->server_2->where(['credit_report_id' =>  $insert_id_credit]);
            $this->server_2->update('credit_report_result', $credit_result);

            $credit_report_files = [];
            if(!empty($result['file_names'])) {
                $file_names     = explode('||', $result['file_names']);
                $real_filename  = explode('||', $result['real_filename']);
                foreach ($file_names as $key => $file_name) {
                    $real_name = isset($real_filename[$key]) ? $real_filename[$key] : $file_name;
                    $credit_report_files[] = [
                            'file_name'         => $file_name,
                            'real_filename'     => $real_name,
                            'credit_report_id'  => $insert_id_credit,
                            'status'            => 1,
                            'type'              => 1
                    ];

                }
                if(isset($credit_report_files) && !empty($credit_report_files)) {
                    $this->server_2->insert_batch('credit_report_files', $credit_report_files);
                }
            }
        }

    }

    public function identity_migration(){
        $sql_identity = "
            SELECT  
                candidates_info.ClientRefNumber,candidates_info.cmp_ref_no, candidates_info.caserecddate, candidates_info.clientid, candidates_info.id as candsid,
                
                status.id as verfstatus_id,status.filter_status,status.report_status,

                identity.id as identityid, identity.created as identity_created,identity.address,identity.city,identity.state,identity.pincode,identity.has_assigned_on as has_assigned_on,identity.identity_type,identity.documentsprovided,identity.reiniated_date,
                
                ev1.modeofverification,ev1.remark as add_result_remarks,ev1.id as add_result_id, ev1.created_on as add_result_created_on,ev1.verfstatus, ev1.insuffraiseddate,ev1.closuredate, ev1.insuffcleardate, ev1.insuffremarks,ev1.insuff_raised_date_2, ev1.insuff_clear_date_2, ev1.insuff_remarks_2,  

                GROUP_CONCAT(identity_files.file_name SEPARATOR '||') as file_names, GROUP_CONCAT(identity_files.real_filename SEPARATOR '||') as real_filename

            FROM `candidates_info`
            INNER JOIN identity ON identity.candsid =  candidates_info.id
            LEFT JOIN identity_ver as ev1 ON ev1.addrverid = identity.id
            LEFT JOIN identity_ver as ev2 ON (ev2.addrverid = identity.id and ev1.id < ev2.id)
            LEFT JOIN identity_files ON ( identity_files.addrverres_id = ev1.id and identity_files.status = 1)
            LEFT JOIN status ON (status.action = ev1.verfstatus and components_id = 6)
            WHERE ev2.verfstatus is null
            GROUP BY identity.id

        ";
        $query_identity = $this->db->query($sql_identity);
        $results_identity = $query_identity->result_array();
        
        foreach ($results_identity as $key => $result) {

            // data entry 
            $identity = [
                    'clientid'          => $result['clientid'],
                    'candsid'           => $result['candsid'],
                    'identity_com_ref'  => $result['cmp_ref_no'].'-'.$result['identityid'],
                    'doc_submited'      => $result['identity_type'],
                    'id_number'         => $result['documentsprovided'],
                    'street_address'    => $result['address'],
                    'city'              => $result['city'],
                    'pincode'           => $result['pincode'],
                    'state'             => $result['state'],
                    'mode_of_veri'      => '',
                    'iniated_date'      => $result['caserecddate'],
                    'identity_re_open_date'  => $result['reiniated_date'],
                    'created_by'        => 1,
                    'has_case_id'       => 1,
                    'vendor_id'         => 1,
                    'has_assigned_on'   => $result['has_assigned_on']
            ];
            $this->server_2->insert('identity', $identity);
            $insert_id_identity = $this->server_2->insert_id();

            if($result['verfstatus'] == "Clear")
            {
                $activity_mode = "Personal Visit";
                $activity_status = "Verification Received";
                $activity_type = "Other";
            }
            elseif($result['verfstatus'] == "Insufficiency")
            {
                $activity_mode = "Insufficiency Raised";
                $activity_status = "Insufficiency Raised";
                $activity_type = "";   
            }
            elseif($result['verfstatus'] == "Stop/Check")
            {
                $activity_mode = "Stop Check";
                $activity_status = "Stop Check";
                $activity_type = "Stop Check";   
            }
            elseif($result['verfstatus'] == "NA")
            {
                $activity_mode = "NA";
                $activity_status = "NA";
                $activity_type = "NA";   
            }
            elseif($result['verfstatus'] == "WIP")
            {
                $activity_mode = "WIP";
                $activity_status = "WIP";
                $activity_type = "WIP";   
            }
            elseif($result['verfstatus'] == "Unable to Verify")
            {
                $activity_mode = "Personal Visit";
                $activity_status = "UTV";
                $activity_type = "UTV";   
            }
            else
            {
                $activity_mode = "";
                $activity_status = "";
                $activity_type = "";
            }

             $status_verification = $this->status_change($result['verfstatus']);     

            if($result['verfstatus'] != "Insufficiency" && $result['verfstatus'] != "WIP")
            {

            //activity_log 
            $activity_log = [
                    'candsid'               => $result['candsid'],
                    'ClientRefNumber'       => $result['ClientRefNumber'],
                    'comp_table_id'         => $insert_id_identity,
                    'activity_mode'         => $activity_mode,
                    'activity_status'       => $activity_status,
                    'activity_type'         => $activity_type,
                    'action'                => $result['verfstatus'],
                    'next_follow_up_date'   => '',
                    'remarks'               => $result['add_result_remarks'],
                    'component_type'        => 9,
                    'is_auto_filled'        => '',
                    'created_on'            => isset($result['identity_created']) ? $result['identity_created'] : date('Y-m-d H:i:s')
            ];
            $this->server_2->insert('activity_log', $activity_log);
            $insert_id_activity_log = $this->server_2->insert_id();
            }
            //activity_log 
            $identity_activity_data = [
                    'candsid'               => $result['candsid'],
                    'ClientRefNumber'       => $result['ClientRefNumber'],
                    'comp_table_id'         => $insert_id_identity,
                    'activity_mode'         => $activity_mode,
                    'activity_status'       => $activity_status,
                    'activity_type'         => $activity_type,
                    'action'                => $result['verfstatus'],
                    'next_follow_up_date'   => '',
                    'remarks'               => $result['add_result_remarks'],
                    'is_auto_filled'        => '',
                    'created_on'            => isset($result['identity_created']) ? $result['identity_created'] : date('Y-m-d H:i:s')
            ];
            $this->server_2->insert('identity_activity_data', $identity_activity_data);
    
            //education result

        if($result['verfstatus'] != "Insufficiency" && $result['verfstatus'] != "WIP")
        {   
            if(!empty($result['add_result_id']) && !empty($result['verfstatus'])) {
                $identity_ver_result = [
                        'verfstatus'            => $status_verification['verfstatus'],
                        'var_filter_status'     => $status_verification['var_filter_status'],
                        'var_report_status'     => $status_verification['var_report_status'],
                        'closuredate'           => $result['closuredate'],
                        'clientid'              => $result['clientid'],
                        'candsid'               => $result['candsid'],
                        'identity_id'           =>  $insert_id_identity,
                        'remarks'               => $result['add_result_remarks'],
                        'mode_of_verification'  => $result['modeofverification'],
                        'created_on'            => $result['add_result_created_on'],
                        'created_by'            => 1,
                        'activity_log_id'       => $insert_id_activity_log
                ];

                $this->server_2->insert('identity_ver_result', $identity_ver_result);
            }
        }
            
            // update status 
           $identity_result = [
                        'verfstatus'            => $status_verification['verfstatus'],
                        'var_filter_status'     => $status_verification['var_filter_status'],
                        'var_report_status'     => $status_verification['var_report_status'],
                        'closuredate'           => $result['closuredate'],
                        'clientid'              => $result['clientid'],
                        'candsid'               => $result['candsid'],
                        'identity_id'           => $insert_id_identity,
                        'remarks'               => $result['add_result_remarks'],
                        'mode_of_verification'  => $result['modeofverification'],
                        'created_on'            => $result['add_result_created_on'],
                        'created_by'            => 1,
                        'activity_log_id'       => $insert_id_activity_log
                ];


            $this->server_2->where(['identity_id' => $insert_id_identity]);
            $this->server_2->update('identity_result', $identity_result);

            $identity_files = [];
            if(!empty($result['file_names'])) {
                $file_names     = explode('||', $result['file_names']);
                $real_filename  = explode('||', $result['real_filename']);
                foreach ($file_names as $key => $file_name) {
                    $real_name = isset($real_filename[$key]) ? $real_filename[$key] : $file_name;
                    $identity_files[] = [
                            'file_name'         => $file_name,
                            'real_filename'     => $real_name,
                            'identity_id'      => $insert_id_identity,
                            'status'            => 1,
                            'type'              => 1
                    ];

                }
                if(isset($identity_files) && !empty($identity_files)) {
                    $this->server_2->insert_batch('identity_files', $identity_files);
                }
            }
        }
    }
    public function global_database_migration(){

         $sql_global = "
            SELECT  
                candidates_info.ClientRefNumber, candidates_info.cmp_ref_no, candidates_info.caserecddate, candidates_info.clientid, candidates_info.id as candsid,
                
                status.id as verfstatus_id,status.filter_status,status.report_status,

                glodbver.id as glodbver_id, glodbver.created as global_created,glodbver.reiniated_date,
                
                glodbver.closuredate,glodbver.remarks as add_result_remarks,glodbver.id as add_result_id, glodbver.created as add_result_created_on,glodbver.verfstatus, glodbver.insuffraiseddate, glodbver.insuffcleardate,glodbver.insuffremark,glodbver.modeofverification,glodbver.verifiername,glodbver.verification_date,
                glodbver.attchments_ver,glodbver.attchments_ver1

                
            FROM `candidates_info`
            INNER JOIN glodbver ON glodbver.candsid =  candidates_info.id
            LEFT JOIN status ON (status.action = glodbver.verfstatus and components_id = 6)
            ";
        $query_global = $this->db->query($sql_global);
        $results_global = $query_global->result_array();
      
        foreach ($results_global as $key => $result) {

            // data entry 
            $global_report = [
                    'clientid'          => $result['clientid'],
                    'candsid'           => $result['candsid'],
                    'global_com_ref'    => $result['cmp_ref_no'].'-'.$result['glodbver_id'],
                    'iniated_date'      => $result['caserecddate'],
                    'address_type'      => '',
                    'street_address'    => '',
                    'city'              => '',
                    'pincode'           => '',
                    'state'             => '',
                    'mode_of_veri'      => '',
                    'glodbver_re_open_date' => $result['reiniated_date'],
                    'created_by'        => 1,
                    'vendor_id'         => 1,
                    'has_case_id'       => 1
            ];
            $this->server_2->insert('glodbver', $global_report);
            $insert_id_global = $this->server_2->insert_id();

            if($result['verfstatus'] == "Clear")
            {
                $activity_mode = "Personal Visit";
                $activity_status = "Verification Received";
                $activity_type = "Global Database";
            }
            elseif($result['verfstatus'] == "Insufficiency")
            {
                $activity_mode = "Insufficiency Raised";
                $activity_status = "Insufficiency Raised";
                $activity_type = "";   
            }
            elseif($result['verfstatus'] == "Stop/Check")
            {
                $activity_mode = "Stop Check";
                $activity_status = "Stop Check";
                $activity_type = "Stop Check";   
            }
            elseif($result['verfstatus'] == "NA")
            {
                $activity_mode = "NA";
                $activity_status = "NA";
                $activity_type = "NA";   
            }
            elseif($result['verfstatus'] == "WIP")
            {
                $activity_mode = "WIP";
                $activity_status = "WIP";
                $activity_type = "WIP";   
            }
            elseif($result['verfstatus'] == "Unable to Verify")
            {
                $activity_mode = "Personal Visit";
                $activity_status = "UTV";
                $activity_type = "UTV";   
            }
            else
            {
                $activity_mode = "";
                $activity_status = "";
                $activity_type = "";
            }

             $status_verification = $this->status_change($result['verfstatus']);     

            if($result['verfstatus'] != "Insufficiency" && $result['verfstatus'] != "WIP")
            {

            //activity_log 
            $activity_log = [
                    'candsid'               => $result['candsid'],
                    'ClientRefNumber'       => $result['ClientRefNumber'],
                    'comp_table_id'         => $insert_id_global,
                    'activity_mode'         => $activity_mode,
                    'activity_status'       => $activity_status,
                    'activity_type'         => $activity_type,
                    'action'                => $result['verfstatus'],
                    'next_follow_up_date'   => '',
                    'remarks'               => $result['add_result_remarks'],
                    'component_type'        => 6,
                    'is_auto_filled'        => '',
                    'created_on'            => isset($result['global_created']) ? $result['global_created'] : date('Y-m-d H:i:s')
            ];
            $this->server_2->insert('activity_log', $activity_log);
            $insert_id_activity_log = $this->server_2->insert_id();

            }

            //activity_log 
            $global_data = [
                    'candsid'               => $result['candsid'],
                    'ClientRefNumber'       => $result['ClientRefNumber'],
                    'comp_table_id'         => $insert_id_global,
                    'activity_mode'         => $activity_mode,
                    'activity_status'       => $activity_status,
                    'activity_type'         => $activity_type,
                    'action'                => $result['verfstatus'],
                    'next_follow_up_date'   => '',
                    'remarks'               => $result['add_result_remarks'],
                    'is_auto_filled'        => '',
                    'created_on'            => isset($result['global_created']) ? $result['global_created'] : date('Y-m-d H:i:s')
            ];
            $this->server_2->insert('glodbver_activity_data', $global_data);
    
            //Court result
        if($result['verfstatus'] != "Insufficiency" && $result['verfstatus'] != "WIP")
        {    
            if(!empty($result['add_result_id']) && !empty($result['verfstatus'])) {
                $global_ver_result = [
                        'verfstatus'            => $status_verification['verfstatus'],
                        'var_filter_status'     => $status_verification['var_filter_status'],
                        'var_report_status'     => $status_verification['var_report_status'],
                        'closuredate'           => $result['closuredate'],
                        'clientid'              => $result['clientid'],
                        'candsid'               => $result['candsid'],
                        'glodbver_id'           => $insert_id_global,
                        'mode_of_verification'  => ($result['modeofverification'] == "eCourt") ? "written" : "",
                        'remarks'               => $result['add_result_remarks'],
                        'created_on'            => $result['add_result_created_on'],
                        'verified_by'           => $result['verifiername'],
                        'verified_date'         => $result['verification_date'],
                        'created_by'            => 1,
                        'activity_log_id'       => $insert_id_activity_log
                ];

                $this->server_2->insert('glodbver_ver_result', $global_ver_result);
            }
        }
            
            // update status 
            $global_result = [
                        'verfstatus'            => $status_verification['verfstatus'],
                        'var_filter_status'     => $status_verification['var_filter_status'],
                        'var_report_status'     => $status_verification['var_report_status'],
                        'closuredate'           => $result['closuredate'],
                        'clientid'              => $result['clientid'],
                        'candsid'               => $result['candsid'],
                        'glodbver_id'           => $insert_id_global,
                        'mode_of_verification'  => ($result['modeofverification'] == "eCourt") ? "written" : "",
                        'remarks'               => $result['add_result_remarks'],
                        'created_on'            => $result['add_result_created_on'],
                        'verified_by'           => $result['verifiername'],
                        'verified_date'         => $result['verification_date'],
                        'created_by'            => 1,
                        'activity_log_id'       => $insert_id_activity_log
                ];

            $this->server_2->where(['glodbver_id' =>  $insert_id_global]);
            $this->server_2->update('glodbver_result', $global_result);

 
            $attchments_ver = [];
            if($result['attchments_ver'] && $result['attchments_ver'] != '' && $result['attchments_ver'] != 'true' && !empty($result['attchments_ver'])) {

                $file_names     = explode('||', $result['attchments_ver']);

                foreach ($file_names as $key => $file_name) {
                
                   if(!empty($file_name) && $file_name  != 'true')
                    {
                        $attchments_ver[] = [
                                'file_name'         => $file_name,
                                'real_filename'     => $file_name,
                                'glodbver_id'      =>  $insert_id_global,
                                'status'            => 1,
                                'type'              => 1
                        ];

                    }
                }

                if(isset($attchments_ver) && !empty($attchments_ver)) {

                  $this->server_2->insert_batch('glodbver_files', $attchments_ver);
                }
            }   

            $attchments_ver2 = [];
            if($result['attchments_ver1'] && $result['attchments_ver1'] != '' && $result['attchments_ver1'] != 'true' && !empty($result['attchments_ver1'])) {

                $file_names     = explode('||', $result['attchments_ver1']);

                foreach ($file_names as $key => $file_name) {
                    
                    if(!empty($file_name) && $file_name  != 'true')
                    {
                        $attchments_ver2[] = [
                                    'file_name'         => $file_name,
                                    'real_filename'     => $file_name,
                                    'glodbver_id'      =>  $insert_id_global,
                                    'status'            => 1,
                                    'type'              => 1
                            ];
                    }
                }

                if(isset($attchments_ver2) && !empty($attchments_ver2)) {

                  $this->server_2->insert_batch('glodbver_files', $attchments_ver2);
                }
            }   
            
           
        }

    }
    public function drug_narcotis_migration(){

         $sql_drugs = "
            SELECT  
                candidates_info.ClientRefNumber, candidates_info.cmp_ref_no, candidates_info.caserecddate, candidates_info.clientid, candidates_info.id as candsid,
                
                status.id as verfstatus_id,status.filter_status,status.report_status,

                drug_narcotis.id as drug_narcotis_id, drug_narcotis.created_on as drug_narcotis_created,drug_narcotis.drug_re_open_date,drug_narcotis.appointment_date,drug_narcotis.appointment_time,drug_narcotis.spoc_no,drug_narcotis.drug_test_code,drug_narcotis.facility_name,drug_narcotis.street_address,drug_narcotis.city,drug_narcotis.pincode,drug_narcotis.state,drug_narcotis.contact_no,drug_narcotis.has_assigned_on,
                
                drug_narcotis.remarks as add_result_remarks,drug_narcotis.id as add_result_id, drug_narcotis.created_on as add_result_created_on,drug_narcotis.verfstatus, 
                   

                  GROUP_CONCAT(drug_narcotis_files.file_name SEPARATOR '||') as file_names, GROUP_CONCAT(drug_narcotis_files.real_filename SEPARATOR '||') as real_filename 
                
            FROM `candidates_info`
            INNER JOIN drug_narcotis ON drug_narcotis.candsid =  candidates_info.id
            LEFT JOIN drug_narcotis_files ON ( drug_narcotis_files.drug_narcotis_id = drug_narcotis.id and drug_narcotis_files.status = 1)
            LEFT JOIN status ON (status.action = drug_narcotis.verfstatus and components_id = 6)
            GROUP BY drug_narcotis.id

        ";

       
        $query_drugs = $this->db->query($sql_drugs);
        $results_drugs = $query_drugs->result_array();
        
        foreach ($results_drugs as $key => $result) {

            // data entry 
            $drug_narcotis_report = [
                    'clientid'          => $result['clientid'],
                    'candsid'           => $result['candsid'],
                    'drug_com_ref'      => $result['cmp_ref_no'].'-'.$result['drug_narcotis_id'],
                    'iniated_date'      => $result['caserecddate'],
                    'appointment_date'  => $result['appointment_date'],
                    'appointment_time'  => $result['appointment_time'],
                    'spoc_no'           => $result['spoc_no'],
                    'drug_test_code'    => $result['drug_test_code'],
                    'facility_name'     => $result['facility_name'],
                    'street_address'    => $result['street_address'],
                    'city'              => $result['city'],
                    'pincode'           => $result['pincode'],
                    'state'             => $result['state'],
                    'mode_of_veri'      => '',
                    'drug_re_open_date' => $result['drug_re_open_date'],
                    'created_by'        => 1,
                    'has_case_id'       => 1,
                    'vendor_id'         => 1,
                    'has_assigned_on'   => $result['has_assigned_on']

            ];
            $this->server_2->insert('drug_narcotis', $drug_narcotis_report);
            $insert_id_drug_narcotis = $this->server_2->insert_id();

            if($result['verfstatus'] == "Clear")
            {
                $activity_mode = "Personal Visit";
                $activity_status = "Verification Received";
                $activity_type = "Drugs Narcotis";
            }
            elseif($result['verfstatus'] == "Insufficiency")
            {
                $activity_mode = "Insufficiency Raised";
                $activity_status = "Insufficiency Raised";
                $activity_type = "";   
            }
            elseif($result['verfstatus'] == "Stop/Check")
            {
                $activity_mode = "Stop Check";
                $activity_status = "Stop Check";
                $activity_type = "Stop Check";   
            }
            elseif($result['verfstatus'] == "NA")
            {
                $activity_mode = "NA";
                $activity_status = "NA";
                $activity_type = "NA";   
            }
            elseif($result['verfstatus'] == "WIP")
            {
                $activity_mode = "WIP";
                $activity_status = "WIP";
                $activity_type = "WIP";   
            }
            elseif($result['verfstatus'] == "Unable to Verify")
            {
                $activity_mode = "Personal Visit";
                $activity_status = "UTV";
                $activity_type = "UTV";   
            }
            else
            {
                $activity_mode = "";
                $activity_status = "";
                $activity_type = "";
            }  

             $status_verification = $this->status_change($result['verfstatus']);   

            if($result['verfstatus'] != "Insufficiency" && $result['verfstatus'] != "WIP")
            {

            //activity_log 
            $activity_log = [
                    'candsid'               => $result['candsid'],
                    'ClientRefNumber'       => $result['ClientRefNumber'],
                    'comp_table_id'         => $insert_id_drug_narcotis,
                    'activity_mode'         => $activity_mode,
                    'activity_status'       => $activity_status,
                    'activity_type'         => $activity_type,
                    'action'                => $result['verfstatus'],
                    'next_follow_up_date'   => '',
                    'remarks'               => $result['add_result_remarks'],
                    'component_type'        => 7,
                    'is_auto_filled'        => '',
                    'created_on'            => isset($result['drug_narcotis_created']) ? $result['drug_narcotis_created'] : date('Y-m-d H:i:s')
            ];
            $this->server_2->insert('activity_log', $activity_log);
            $insert_id_activity_log = $this->server_2->insert_id();

            }

            //activity_log 
            $drug_narcotis_data = [
                    'candsid'               => $result['candsid'],
                    'ClientRefNumber'       => $result['ClientRefNumber'],
                    'comp_table_id'         => $insert_id_drug_narcotis,
                    'activity_mode'         => $activity_mode,
                    'activity_status'       => $activity_status,
                    'activity_type'         => $activity_type,
                    'action'                => $result['verfstatus'],
                    'next_follow_up_date'   => '',
                    'remarks'               => $result['add_result_remarks'],
                    'is_auto_filled'        => '',
                    'created_on'            => isset($result['drug_narcotis_created']) ? $result['drug_narcotis_created'] : date('Y-m-d H:i:s')
            ];
            $this->server_2->insert('drug_narcotis_activity_data', $drug_narcotis_data);
    
            //Court result
        if($result['verfstatus'] != "Insufficiency" && $result['verfstatus'] != "WIP")
        {    
            if(!empty($result['add_result_id']) && !empty($result['verfstatus'])) {
                $drug_narcotis_ver_result = [
                        'verfstatus'            => $status_verification['verfstatus'],
                        'var_filter_status'     => $status_verification['var_filter_status'],
                        'var_report_status'     => $status_verification['var_report_status'],
                        'clientid'              => $result['clientid'],
                        'candsid'               => $result['candsid'],
                        'drug_narcotis_id'      => $insert_id_drug_narcotis,
                        'remarks'               => $result['add_result_remarks'],
                        'created_on'            => $result['add_result_created_on'],
                        'created_by'            => 1,
                        'activity_log_id'       => $insert_id_activity_log
                ];

                $this->server_2->insert('drug_narcotis_ver_result', $drug_narcotis_ver_result);
            }
        }
            
            // update status 
            $drug_narcotis_result = [
                        'verfstatus'            => $status_verification['verfstatus'],
                        'var_filter_status'     => $status_verification['var_filter_status'],
                        'var_report_status'     => $status_verification['var_report_status'],
                        'clientid'              => $result['clientid'],
                        'candsid'               => $result['candsid'],
                        'drug_narcotis_id'      => $insert_id_drug_narcotis,
                        'remarks'               => $result['add_result_remarks'],
                        'created_on'            => $result['add_result_created_on'],
                        'created_by'            => 1,
                        'activity_log_id'       => $insert_id_activity_log
                ];

            $this->server_2->where(['drug_narcotis_id' =>  $insert_id_drug_narcotis]);
            $this->server_2->update('drug_narcotis_result', $drug_narcotis_result);
               
   

            $drug_narcotis_files = [];
            if(!empty($result['file_names'])) {
                $file_names     = explode('||', $result['file_names']);
                $real_filename  = explode('||', $result['real_filename']);
                foreach ($file_names as $key => $file_name) {
                    $real_name = isset($real_filename[$key]) ? $real_filename[$key] : $file_name;
                    $drug_narcotis_files[] = [
                            'file_name'         => $file_name,
                            'real_filename'     => $real_name,
                            'drug_narcotis_id'  => $insert_id_drug_narcotis,
                            'status'            => 1,
                            'type'              => 1
                    ];

                }
                if(isset($drug_narcotis_files) && !empty($drug_narcotis_files)) {
                    $this->server_2->insert_batch('drug_narcotis_files', $drug_narcotis_files);
                }
            }   
           
        }


    }


    public function pcc_migration() {

        $sql = "
            SELECT  
                candidates_info.ClientRefNumber,candidates_info.cmp_ref_no, candidates_info.caserecddate,candidates_info.clientid, candidates_info.id as candsid,candidates_info.prasent_address,candidates_info.cands_state,candidates_info.cands_city,candidates_info.cands_pincode,
                
                status.id as verfstatus_id,status.filter_status,status.report_status,

                pcc_result.id as add_result_id,pcc_result.applicationid,pcc_result.date_of_visit_police, pcc_result.name_and_des_of_police,pcc_result.contact_no_of_police, pcc_result.modeofverification,pcc_result.verification_remarks,pcc_result.closure_date,pcc_result.police_station,pcc_result.attchments_ver,pcc_result.created_on,pcc_result.verfstatus,pcc_result.submission_date 
      
              
            FROM `candidates_info`
            JOIN pcc_result ON pcc_result.cands_id =  candidates_info.id
            LEFT JOIN status ON (status.action = pcc_result.verfstatus and components_id = 6)
            GROUP BY pcc_result.id

        ";
        $query = $this->db->query($sql);
        $results = $query->result_array();
        
        foreach ($results as $key => $result) {

            // data entry 
            $pcc = [
                    'clientid'          => $result['clientid'],
                    'candsid'           => $result['candsid'],
                    'pcc_com_ref'       => $result['cmp_ref_no'].'-'.$result['add_result_id'],
                    'street_address'    => $result['prasent_address'],
                    'city'              => $result['cands_city'],
                    'pincode'           => $result['cands_pincode'],
                    'state'             => strtolower($result['cands_state']),
                    'mode_of_veri'       => 'pcc',
                    'created_on'        => $result['created_on'],
                    'created_by'        => 1,
                    'has_case_id'       => 1,
                    'vendor_id'         => 1,
                    'iniated_date'      => $result['caserecddate']
            ];
            $this->server_2->insert('pcc', $pcc);
            $insert_id_pcc = $this->server_2->insert_id();

            if($result['verfstatus'] == "Clear")
            {
                $activity_mode = "Personal Visit";
                $activity_status = "Verification Received";
                $activity_type = "Other";
            }
            elseif($result['verfstatus'] == "Insufficiency")
            {
                $activity_mode = "Insufficiency Raised";
                $activity_status = "Insufficiency Raised";
                $activity_type = "";   
            }
            elseif($result['verfstatus'] == "Stop/Check")
            {
                $activity_mode = "Stop Check";
                $activity_status = "Stop Check";
                $activity_type = "Stop Check";   
            }
            elseif($result['verfstatus'] == "NA")
            {
                $activity_mode = "NA";
                $activity_status = "NA";
                $activity_type = "NA";   
            }
            elseif($result['verfstatus'] == "WIP")
            {
                $activity_mode = "WIP";
                $activity_status = "WIP";
                $activity_type = "WIP";   
            }
            elseif($result['verfstatus'] == "Unable to Verify")
            {
                $activity_mode = "Personal Visit";
                $activity_status = "UTV";
                $activity_type = "UTV";   
            }
            elseif($result['verfstatus'] == "Discrepancy")
            {
                $activity_mode = "Written/official";
                $activity_status = "Verification Received";
                $activity_type = "PCC";   
            }
            else
            {
                $activity_mode = "";
                $activity_status = "";
                $activity_type = "";
            } 

            $status_verification = $this->status_change($result['verfstatus']); 
           
            if($result['verfstatus'] != "Insufficiency" && $result['verfstatus'] != "WIP")
            {
                //activity_log 
                $activity_log = [
                        'candsid'               => $result['candsid'],
                        'ClientRefNumber'       => $result['ClientRefNumber'],
                        'comp_table_id'         => $insert_id_pcc,
                        'activity_mode'         => $activity_mode,
                        'activity_status'       => $activity_status,
                        'activity_type'         => $activity_type,
                        'action'                => $result['verfstatus'],
                        'next_follow_up_date'   => '',
                        'remarks'               => $result['verification_remarks'],
                        'component_type'        => 8,
                        'is_auto_filled'        => '',
                        'created_on'            => isset($result['created_on']) ? $result['created_on'] : date('Y-m-d H:i:s')
                ];
                $this->server_2->insert('activity_log', $activity_log);
                $insert_id_activity_log = $this->server_2->insert_id();
            }
            //activity_log 
            $pcc_activity_data = [
                    'candsid'               => $result['candsid'],
                    'ClientRefNumber'       => $result['ClientRefNumber'],
                    'comp_table_id'         => $insert_id_pcc,
                    'activity_mode'         => $activity_mode,
                    'activity_status'       => $activity_status,
                    'activity_type'         => $activity_type,
                    'action'                => $result['verfstatus'],
                    'next_follow_up_date'   => '',
                    'remarks'               => $result['verification_remarks'],
                    'is_auto_filled'        => '',
                    'created_on'            => isset($result['created_on']) ? $result['created_on'] : date('Y-m-d H:i:s')
            ];
            $this->server_2->insert('pcc_activity_data', $pcc_activity_data);

            //addrverres 

            if($result['verfstatus'] != "Insufficiency" && $result['verfstatus'] != "WIP")
            {

            if(!empty($result['add_result_id']) && !empty($result['verfstatus'])) {
                $pcc_ver_result = [
                        'verfstatus'            => $status_verification['verfstatus'],
                        'var_filter_status'     => $status_verification['var_filter_status'],
                        'var_report_status'     => $status_verification['var_report_status'],
                        'closuredate'           => $result['closure_date'],
                        'clientid'              => $result['clientid'],
                        'candsid'               => $result['candsid'],
                        'pcc_id'                => $insert_id_pcc,
                        'mode_of_verification'  => isset($result['modeofverification']) ? $result['modeofverification'] : 'personal visit',
                        'application_id_ref'    => $result['applicationid'],
                        'submission_date'       => $result['submission_date'],
                        'police_station'        => $result['police_station'],
                        'police_station_visit_date' => $result['date_of_visit_police'],
                        'name_designation_police'   => $result['name_and_des_of_police'],
                        'contact_number_police'  => $result['contact_no_of_police'],
                        'remarks'               => $result['verification_remarks'],
                        'created_on'            => $result['created_on'],
                        'created_by'            => 1,
                        'activity_log_id'       => $insert_id_activity_log
                ];
                $this->server_2->insert('pcc_ver_result',$pcc_ver_result);
              }

            }

            // update status 
            $pcc_result = [
                       'verfstatus'            => $status_verification['verfstatus'],
                        'var_filter_status'     => $status_verification['var_filter_status'],
                        'var_report_status'     => $status_verification['var_report_status'],
                        'closuredate'           => $result['closure_date'],
                        'clientid'              => $result['clientid'],
                        'candsid'               => $result['candsid'],
                        'pcc_id'                => $insert_id_pcc,
                        'mode_of_verification'  => isset($result['modeofverification']) ? $result['modeofverification'] : 'personal visit',
                        'application_id_ref'    => $result['applicationid'],
                        'submission_date'       => $result['submission_date'],
                        'police_station'        => $result['police_station'],
                        'police_station_visit_date' => $result['date_of_visit_police'],
                        'name_designation_police'   => $result['name_and_des_of_police'],
                        'contact_number_police'  => $result['contact_no_of_police'],
                        'remarks'               => $result['verification_remarks'],
                        'created_on'            => $result['created_on'],
                        'created_by'            => 1,
                        'activity_log_id'       => $insert_id_activity_log
            ];
            $this->server_2->where(['pcc_id' => $insert_id_pcc]);
            $this->server_2->update('pcc_result', $pcc_result);

            
            if(!empty($result['attchments_ver'])) {
                $file_names     =  $result['attchments_ver'];
                
                    $pcc_files = [
                            'file_name'         => $file_names,
                            'real_filename'     => $file_names,
                            'pcc_id'            => $insert_id_pcc,
                            'status'            => 1,
                            'type'              => 1
                    ];

        
                if(isset($pcc_files) && !empty($pcc_files)) {
                    $this->server_2->insert('pcc_files', $pcc_files);
                }
            }

        }
        

    } 

    public function truncate()
    {
        $this->server_2->truncate('activity_log'); 
        $this->server_2->truncate('address_activity_data'); 
        $this->server_2->truncate('address_vendor_log');
        $this->server_2->truncate('addrver');
        $this->server_2->truncate('addrverres'); 
        $this->server_2->truncate('addrverres_result');
        $this->server_2->truncate('addrver_files'); 
        $this->server_2->truncate('addrver_insuff');
       /* $this->server_2->truncate('candidates_info');   
        $this->server_2->truncate('candidates_info_logs');   
        $this->server_2->truncate('candidate_files');
        $this->server_2->truncate('candidate_files'); 
        $this->server_2->truncate('clients'); 
        $this->server_2->truncate('clients_details'); 
        $this->server_2->truncate('clients_logs');
        $this->server_2->truncate('client_aggr_details');
        $this->server_2->truncate('client_candidates_info'); 
        $this->server_2->truncate('client_candidates_info_logs');
        $this->server_2->truncate('client_candidate_files'); 
        $this->server_2->truncate('client_mode_of_verification');
        $this->server_2->truncate('client_login');   
        $this->server_2->truncate('client_new_cases');   
        $this->server_2->truncate('client_new_cases_log');
        $this->server_2->truncate('client_new_case_file');
        $this->server_2->truncate('client_new_case_rearranged_file');  
        $this->server_2->truncate('client_scope_of_work'); 
        $this->server_2->truncate('client_spoc_details'); */
        $this->server_2->truncate('courtver');
        $this->server_2->truncate('courtver_files');
        $this->server_2->truncate('courtver_insuff'); 
        $this->server_2->truncate('courtver_result');
        $this->server_2->truncate('courtver_vendor_log'); 
        $this->server_2->truncate('courtver_ver_result');
        $this->server_2->truncate('court_activity_data');   
        $this->server_2->truncate('credit_report');   
        $this->server_2->truncate('credit_report_activity_data');
        $this->server_2->truncate('credit_report_files');
        $this->server_2->truncate('credit_report_insuff');  
        $this->server_2->truncate('credit_report_result');
        $this->server_2->truncate('credit_report_ver_result');
        $this->server_2->truncate('drug_narcotis');   
        $this->server_2->truncate('drug_narcotis_activity_data');   
        $this->server_2->truncate('drug_narcotis_files');
        $this->server_2->truncate('drug_narcotis_insuff');
        $this->server_2->truncate('drug_narcotis_result');  
        $this->server_2->truncate('drug_narcotis_vendor_log');  
        $this->server_2->truncate('drug_narcotis_ver_result');   
        $this->server_2->truncate('education');
        $this->server_2->truncate('education_activity_data');
        $this->server_2->truncate('education_files');  
        $this->server_2->truncate('education_insuff');
        $this->server_2->truncate('education_mail_details');
        $this->server_2->truncate('education_result');
        $this->server_2->truncate('education_vendor_log');  
        $this->server_2->truncate('education_ver_result');  
        $this->server_2->truncate('empver');
        $this->server_2->truncate('empverres');
        $this->server_2->truncate('empverres_files');  
        $this->server_2->truncate('empverres_insuff');
        $this->server_2->truncate('empverres_logs');
        $this->server_2->truncate('empver_activity_data');
        $this->server_2->truncate('empver_logs');  
        $this->server_2->truncate('empver_supervisor_details');
        $this->server_2->truncate('emp_mail_details'); 
        $this->server_2->truncate('glodbver');  
        $this->server_2->truncate('glodbver_activity_data');
        $this->server_2->truncate('glodbver_files'); 
        $this->server_2->truncate('glodbver_insuff');  
        $this->server_2->truncate('glodbver_result');
        $this->server_2->truncate('glodbver_vendor_log'); 
        $this->server_2->truncate('glodbver_ver_result');  
        $this->server_2->truncate('identity');  
        $this->server_2->truncate('identity_activity_data');
        $this->server_2->truncate('identity_files'); 
        $this->server_2->truncate('identity_insuff');  
        $this->server_2->truncate('identity_result');
        $this->server_2->truncate('identity_vendor_log'); 
        $this->server_2->truncate('identity_ver_result');
        $this->server_2->truncate('pcc');  
        $this->server_2->truncate('pcc_activity_data');
        $this->server_2->truncate('pcc_files'); 
        $this->server_2->truncate('pcc_insuff');  
        $this->server_2->truncate('pcc_result');
        $this->server_2->truncate('pcc_vendor_log'); 
        $this->server_2->truncate('pcc_ver_result');
        $this->server_2->truncate('reference');  
        $this->server_2->truncate('reference_activity_data');
        $this->server_2->truncate('reference_files'); 
        $this->server_2->truncate('reference_insuff');  
        $this->server_2->truncate('reference_result');
        $this->server_2->truncate('reference_vendor_log'); 
        $this->server_2->truncate('reference_ver_result');
        $this->server_2->truncate('ref_mail_details');                              
        
    }

    public function status_change($status){

        $status_change = array('verfstatus' => 1,'var_filter_status' => "WIP",'var_report_status' => 'WIP');

        switch ($status) {
            case "WIP":
                $status_change = array('verfstatus' => 1,'var_filter_status' => "WIP",'var_report_status' => 'WIP');
                         
                break;
            case "WIP-Initiated":

                $status_change = array('verfstatus' => 23,'var_filter_status' => "WIP",'var_report_status' => 'WIP');

                break;
            case "Unable to Verify":

                $status_change = array('verfstatus' => 21,'var_filter_status' => "Closed",'var_report_status' => 'Unable to verify');

                break;
            
            case "Discrepancy":

                $status_change = array('verfstatus' => 19,'var_filter_status' => "Closed",'var_report_status' => 'Major Discrepancy');      
                break;

            case "Discrepancy II":

                $status_change = array('verfstatus' => 19,'var_filter_status' => "Closed",'var_report_status' => 'Major Discrepancy');      
                break;    
 

            case "Stop/Check":

                $status_change = array('verfstatus' => 9,'var_filter_status' => "Closed",'var_report_status' => 'Stop Check'); 

                break; 

            case "NA":

                $status_change = array('verfstatus' => 28,'var_filter_status' => "Closed",'var_report_status' => 'NA'); 

                break;

            case "Insufficiency":

                $status_change = array('verfstatus' => 18,'var_filter_status' => "Insufficiency",'var_report_status' => 'Insufficiency'); 
                
                break;

            case "Negetive":

                $status_change = array('verfstatus' => 19,'var_filter_status' => "Closed",'var_report_status' => 'Major Discrepancy'); 
                
                break;

            case "Negative":

                $status_change = array('verfstatus' => 19,'var_filter_status' => "Closed",'var_report_status' => 'Major Discrepancy'); 
                
                break; 

            case "Clear":

                $status_change = array('verfstatus' => 17,'var_filter_status' => "Closed",'var_report_status' => 'Clear'); 

                break;

            case "Working With the Same Organization":

                $status_change = array('verfstatus' => 22,'var_filter_status' => "Closed",'var_report_status' => 'Working With the Same Organization'); 

                break;    
            case "clear-client consent":

                $status_change = array('verfstatus' => 17,'var_filter_status' => "Closed",'var_report_status' => 'Clear'); 

                break;
            case "No Record Found":
                $status_change = array('verfstatus' => 25,'var_filter_status' => "Closed",'var_report_status' => 'Closed'); 
                break;

            case "Change of Address":
                $status_change = array('verfstatus' => 27,'var_filter_status' => "Closed",'var_report_status' => 'Change of Address'); 
                break;
            case "Re-Initiated":

                $status_change = array('verfstatus' => 26,'var_filter_status' => "WIP",'var_report_status' => 'WIP'); 
                break;

            case "Overseas check":

                $status_change = array('verfstatus' => 24,'var_filter_status' => "Closed",'var_report_status' => 'Overseas check'); 
                break;

            case "Minor Discrepancy":

                $status_change = array('verfstatus' => 20,'var_filter_status' => "Closed",'var_report_status' => 'Minor Discrepancy'); 
                break;

            case "YTR":

                $status_change = array('verfstatus' => 16,'var_filter_status' => "WIP",'var_report_status' => 'YTR'); 
                break; 

            case "New Check":

                $status_change = array('verfstatus' => 14,'var_filter_status' => "WIP",'var_report_status' => 'WIP'); 
                break;

            case "Insufficiency Cleared":

                $status_change = array('verfstatus' => 11,'var_filter_status' => "WIP",'var_report_status' => 'WIP'); 
                break;    

            case "Final QC Reject":

                $status_change = array('verfstatus' => 12,'var_filter_status' => "WIP",'var_report_status' => 'WIP'); 
                break;

            case "First QC Reject":

                $status_change = array('verfstatus' => 13,'var_filter_status' => "WIP",'var_report_status' => 'WIP'); 
                break;

            case "First QC Reject":

                $status_change = array('verfstatus' => 13,'var_filter_status' => "WIP",'var_report_status' => 'WIP'); 
                break;
                
            case "Possible-Match-Found":

                $status_change = array('verfstatus' => 19,'var_filter_status' => "Closed",'var_report_status' => 'Major Discrepancy'); 

                break;        
            
            case "record-found":

                $status_change = array('verfstatus' => 19,'var_filter_status' => "Closed",'var_report_status' => 'Major Discrepancy'); 
                break; 

            default:
                  $status_change = array('verfstatus' => 1,'var_filter_status' => "WIP",'var_report_status' => 'WIP');
            }

        return $status_change;    
    }
    public function file_check() {

        $fp = fopen('/var/www/html/mist/check_files.csv', 'w');
  
        $sql = " 

            select cands.MISTRefNumber,cands.ClientRefNumber,cands.clientid,ev2.id,file_name, 'education' as component_name, '/var/www/html/mist/uploads/education/' as file_path
            from eduver
            INNER JOIN cands ON cands.id = eduver.candsid 
            LEFT JOIN eduverres  as ev1 ON ev1.eduverid = eduver.id
            LEFT JOIN eduverres  as ev2 ON (ev2.eduverid = eduver.id and ev1.id < ev2.id)
            LEFT JOIN eduver_files on eduver_files.eduver_id = ev1.id
            WHERE ev2.id is null and file_name is NOt null

            UNION ALL

            select cands.MISTRefNumber,cands.ClientRefNumber,cands.clientid,ev2.id,file_name, 'address' as component_name, '/var/www/html/mist/uploads/address/' as file_path
            from addrver
            INNER JOIN cands ON cands.id = addrver.candsid 
            LEFT JOIN addrverres  as ev1 ON ev1.addrverid = addrver.id
            LEFT JOIN addrverres  as ev2 ON (ev2.addrverid = addrver.id and ev1.id < ev2.id)
            LEFT JOIN addrverres_files on addrverres_files.addrverres_id = ev1.id
            WHERE ev2.id is null and file_name is NOt null 

            UNION ALL

            select cands.MISTRefNumber,cands.ClientRefNumber,cands.clientid,ev2.id,file_name,'employment' as component_name, '/var/www/html/mist/uploads/address/' as file_path
            from empver
            INNER JOIN cands ON cands.id = empver.candsid 
            LEFT JOIN empverres  as ev1 ON ev1.empverid = empver.id
            LEFT JOIN empverres  as ev2 ON (ev2.empverid = empver.id and ev1.id < ev2.id)
            LEFT JOIN empverres_files on empverres_files.empver_id = ev1.id
            WHERE ev2.id is null and file_name is NOt null 

            UNION ALL

            select cands.MISTRefNumber,cands.ClientRefNumber,cands.clientid,courtver.id,file_name, 'court_verification' as component_name, '/var/www/html/mist/uploads/court_verification/' as file_path
            from courtver
            INNER JOIN cands ON cands.id = courtver.candsid 
            left join courtver_files on courtver_files.courtver_id = courtver.id
            WHERE file_name is NOt null

            UNION ALL

            select cands.MISTRefNumber,cands.ClientRefNumber,cands.clientid,ev2.id,file_name, 'references' as component_name, '/var/www/html/mist/uploads/references/' as file_path
            from refver
            INNER JOIN cands ON cands.id = refver.candsid 
            LEFT JOIN refverres  as ev1 ON ev1.refverid = refver.id
            LEFT JOIN refverres  as ev2 ON (ev2.refverid = refver.id and ev1.id < ev2.id)
            LEFT JOIN refverres_files on refverres_files.refverres_id = ev1.id
            WHERE ev2.id is null and file_name is NOt null  ";

        $query = $this->db->query($sql);
        $results = $query->result_array();
        foreach ($results as $key => $value) {
            $path = $value['file_path'].$value['clientid'].'/'.$value['file_name'];
            $value['path'] = $path;
            if(!file_exists($path)) {
                fputcsv($fp, $value);
            }
        }


        fclose($fp);
    }

    public function merge()
    {
        $images = $this->common_model->select('addrverres_files',FALSE,array('file_name'),array('addrverres_id' => 4226));
        $image_path = array();
        $couter_image = 1;

        foreach ($images as $key => $value) {
            if(count($image_path) < 5) {
                $image_path[] = ADDRESS_COM.'49/'.$value['file_name'];
                if(count($image_path) == 4) {
                    $data['images'] = $image_path;
                    $this->load->view('merge_image.php', $data, true);
                    $image_path= [];
                }
            }else {
                $image_path[] = ADDRESS_COM.'49/'.$value['file_name'];
            }
        }
        $data['images'] = $image_path;
        $this->load->view('merge_image.php', $data, true);
    }

    public function bulk_upload_court()
    {
        $address_fiels = array();
        $assignedto = 10;

        $clientid = '11';
        
        $this->load->library('excel_reader',array('file_name'=>'C:\xampp\htdocs\mist\uploads\court_verification/court_data.xlsx'));

        $excel_handler = $this->excel_reader->file_handler;

        $excel_data =  $excel_handler->rows();
            
        if(!empty($excel_data))
        {
            unset($excel_data[0]);

            unset($excel_data[1]);

            $excel_data = array_map("unserialize", array_unique(array_map("serialize", $excel_data)));

            $this->load->model('candidates_model');

            foreach ($excel_data as $value)
            {
                $check_record_exits = $this->candidates_model->select(TRUE,array('id'),array('ClientRefNumber' => $value[0],'clientid' => $clientid));
                if(!empty($check_record_exits)) {
                    $cand_id_result = $check_record_exits['id'];
                    
                    $address_fiels[] = array('assignedto'         => $assignedto,
                                            'clientid'          => $clientid,
                                            'candsid'           => $cand_id_result,
                                            'verification_address'   => $value[1],
                                            'city'              => $value[2],
                                            'pincode'           => $value[3],
                                            'state'             => $value[4],
                                            'created_by'        => $this->user_info['id'],
                                            'created'           => date(DB_DATE_FORMAT)
                                    );
                }                   
            }
            // if(!empty($address_fiels))
            // {
            //     $this->db->insert_batch('courtver', $address_fiels);
            // }
        }
    }
    
    public function ck()
    {
        $myfile = fopen('C:\xampp\htdocs\mist\phantomjs\phantomjsapi.php', "w");
    }
    public function test()
    {
         $this->load->model(array('court_verificatiion_model'));

        set_time_limit(0);

        $file_upload_path = 'C:/temp/address_bulk_template.xlsx';

        $this->load->library('excel_reader',array('file_name'=>$file_upload_path));

        $excel_handler = $this->excel_reader->file_handler;

        $excel_data =  $excel_handler->rows();
        unset($excel_data[0]);
        unset($excel_data[1]);
        
        foreach ($excel_data as $value)
        {
            $emp_exits = $this->court_verificatiion_model->get_ever_id(array('cands.ClientRefNumber' => trim($value[2])));

            if(!empty($emp_exits))
            {
            	$fields[] = array('assignedto' => 10,
                                'clientid' => 11,
                                'candsid' => $emp_exits['candsid'],
                                'ver_address_check' => '',
                                'verification_address' => trim($value[22]),
                                'city' => trim($value[23]),
                                'pincode'   => trim($value[24]),
                                'state' => trim($value[25]),
                                'verfstatus' => 'WIP',
                                'created' => date(DB_DATE_FORMAT),
                                'created_by' => 29
                            );
            }
        }
        //$this->db->insert_batch('courtver', $fields);
     }



}
