<?php defined('BASEPATH') or exit('No direct script access allowed');

class Cli_request_only extends CI_Controller
{

    public function __construct()
    {
        ini_set('memory_limit', '640M');

        parent::__construct();
    }

    public function daysBetween($dt1, $dt2)
    {
        return date_diff(date_create($dt2), date_create($dt1))->format('%a');
    }

    public function vendor_cases_tat()
    {
        $vendor_tat_sql = $this->db->query("select * from view_vendor_master_log where status = 1");

        $vendor_tat = $vendor_tat_sql->result_array();

        $update_array = array();
        foreach ($vendor_tat as $key => $value) {
            $vendor_tat_day = json_decode($value['vendors_components_tat'], true);
            $days = $this->daysBetween($value['initaion_date'], date(DATE_ONLY));
            $update_array[] = array('id' => $value['id'], 'tat_status' => $days, 'vendor_tat_days' => $vendor_tat_day[$value['component']]);
        }

        if (!empty($update_array)) {
            $this->db->update_batch('vendor_master_log', $update_array, 'id');
        }
    }

    private function get_holidays_list($srart_date, $end_date)
    {
        $this->CI = &get_instance();

        $this->CI->db->select('holiday_date')->from('holiday_dates');

        $this->CI->db->where('holiday_date >=', $srart_date);

        $this->CI->db->where('holiday_date <=', $end_date);

        $result = $this->CI->db->get()->result_array();

        $return_date = '';

        if (!empty($result) && count($result) > 0) {
            $days = count($result);
            $return_date = getWorkingDays($end_date, array(), $days);
        } else {
            $return_date = $end_date;
        }
        return $return_date;
    }

    protected function case_tat_status($date)
    {
        $today_date = new DateTime(date(DATE_ONLY));

        $datetime2 = new DateTime($date);

        $difference = $today_date->diff($datetime2);
        $difference = $difference->d;

        if ($date < date(DATE_ONLY)) {
            $tat_stauts = 'Out TAT';
        } else if ($difference == 0) {
            $tat_stauts = 'TD TAT';
        } else if (3 >= $difference) {
            $tat_stauts = 'AP TAT';
        } else if ($difference >= 4) {
            $tat_stauts = 'IN TAT';
        }

        return $tat_stauts;
    }

    public function tat_calculation()
    {
        $group_id = $this->db->query("select group_concat(id) as wip_filter from status where filter_status = 'WIP'");

        $res_filter = $group_id->result_array()[0];

        //Employment
        $query = $this->db->query("SELECT empver.id, iniated_date,clients_details.tat_empver,(SELECT sum(hold_days) FROM empverres_insuff empinsf WHERE empinsf.empverres_id = empver.id) AS totat_days
            FROM empver
            INNER JOIN empverres ON ( empverres.empverid = empver.id and empverres.verfstatus in (" . $res_filter['wip_filter'] . ") )
            INNER JOIN candidates_info ON candidates_info.id = empver.candsid
            INNER JOIN clients_details ON (clients_details.tbl_clients_id =  candidates_info.clientid and clients_details.entity = candidates_info.entity and clients_details.package = candidates_info.package) ");
        $result_array = $query->result_array();

        $updateArray = array();

        foreach ($result_array as $key => $value) {

            $iniation_date = ($value['tat_empver'] + $value['totat_days']) - 1;

            $new_date = getWorkingDays($value['iniated_date'], array(), $iniation_date);

            if ($new_date) {
                $final_date = $this->get_holidays_list($value['iniated_date'], $new_date);
                $tat_status = $this->case_tat_status($final_date);
                $updateArray[] = array('due_date' => $final_date, 'tat_status' => $tat_status, 'id' => $value['id']);

            }
        }

        if (!empty($updateArray)) {
            $this->db->update_batch('empver', $updateArray, 'id');
        }

        //Address
        $query = $this->db->query("SELECT addrver.id, iniated_date,clients_details.tat_addrver,(SELECT sum(hold_days) FROM addrver_insuff empinsf WHERE empinsf.addrverid = addrver.id) AS totat_days
            FROM addrver
            INNER JOIN addrverres ON ( addrverres.addrverid = addrver.id and addrverres.verfstatus in (" . $res_filter['wip_filter'] . ") )
            INNER JOIN candidates_info ON candidates_info.id = addrver.candsid
            INNER JOIN clients_details ON (clients_details.tbl_clients_id =  candidates_info.clientid and clients_details.entity = candidates_info.entity and clients_details.package = candidates_info.package)");

        $result_array = $query->result_array();

        $updateArray = array();
        foreach ($result_array as $key => $value) {

            $iniation_date = $value['tat_addrver'] - 1;

            $new_date = getWorkingDays($value['iniated_date'], array(), $iniation_date);

            if ($new_date) {
                $final_date = $this->get_holidays_list($value['iniated_date'], $new_date);
                $tat_status = $this->case_tat_status($final_date);
                $updateArray[] = array('due_date' => $final_date, 'tat_status' => $tat_status, 'id' => $value['id']);

            }
        }

        if (!empty($updateArray)) {
            $this->db->update_batch('addrver', $updateArray, 'id');
        }

        // Education
        $query = $this->db->query("SELECT education.id, iniated_date,clients_details.tat_eduver,(SELECT sum(hold_days) FROM education_insuff empinsf WHERE empinsf.education_id = education.id) AS totat_days
            FROM education
            INNER JOIN education_result ON ( education_result.education_id = education.id and education_result.verfstatus in (" . $res_filter['wip_filter'] . ") )
            INNER JOIN candidates_info ON candidates_info.id = education.candsid
            INNER JOIN clients_details ON (clients_details.tbl_clients_id =  candidates_info.clientid and clients_details.entity = candidates_info.entity and clients_details.package = candidates_info.package)");

        $result_array = $query->result_array();

        $updateArray = array();
        foreach ($result_array as $key => $value) {

            $iniation_date = $value['tat_eduver'] - 1;

            $new_date = getWorkingDays($value['iniated_date'], array(), $iniation_date);

            if ($new_date) {
                $final_date = $this->get_holidays_list($value['iniated_date'], $new_date);
                $tat_status = $this->case_tat_status($final_date);
                $updateArray[] = array('due_date' => $final_date, 'tat_status' => $tat_status, 'id' => $value['id']);

            }
        }

        if (!empty($updateArray)) {
            $this->db->update_batch('education', $updateArray, 'id');
        }

        //Reference
        $query = $this->db->query("SELECT reference.id, iniated_date,clients_details.tat_refver,(SELECT sum(hold_days) FROM reference_insuff empinsf WHERE empinsf.reference_id = reference.id) AS totat_days
            FROM reference
            INNER JOIN reference_result ON ( reference_result.reference_id = reference.id and reference_result.verfstatus in (" . $res_filter['wip_filter'] . ") )
            INNER JOIN candidates_info ON candidates_info.id = reference.candsid
            INNER JOIN clients_details ON (clients_details.tbl_clients_id =  candidates_info.clientid and clients_details.entity = candidates_info.entity and clients_details.package = candidates_info.package)");

        $result_array = $query->result_array();

        $updateArray = array();
        foreach ($result_array as $key => $value) {

            $iniation_date = $value['tat_refver'] - 1;

            $new_date = getWorkingDays($value['iniated_date'], array(), $iniation_date);

            if ($new_date) {
                $final_date = $this->get_holidays_list($value['iniated_date'], $new_date);
                $tat_status = $this->case_tat_status($final_date);
                $updateArray[] = array('due_date' => $final_date, 'tat_status' => $tat_status, 'id' => $value['id']);

            }
        }

        if (!empty($updateArray)) {
            $this->db->update_batch('reference', $updateArray, 'id');
        }

        //Court
        $query = $this->db->query("SELECT courtver.id, iniated_date,clients_details.tat_courtver,(SELECT sum(hold_days) FROM courtver_insuff empinsf WHERE empinsf.courtver_id = courtver.id) AS totat_days
            FROM courtver
            INNER JOIN courtver_result ON ( courtver_result.courtver_id = courtver.id and courtver_result.verfstatus in (" . $res_filter['wip_filter'] . ") )
            INNER JOIN candidates_info ON candidates_info.id = courtver.candsid
            INNER JOIN clients_details ON (clients_details.tbl_clients_id =  candidates_info.clientid and clients_details.entity = candidates_info.entity and clients_details.package = candidates_info.package)");

        $result_array = $query->result_array();

        $updateArray = array();
        foreach ($result_array as $key => $value) {

            $iniation_date = $value['tat_courtver'] - 1;

            $new_date = getWorkingDays($value['iniated_date'], array(), $iniation_date);

            if ($new_date) {
                $final_date = $this->get_holidays_list($value['iniated_date'], $new_date);
                $tat_status = $this->case_tat_status($final_date);
                $updateArray[] = array('due_date' => $final_date, 'tat_status' => $tat_status, 'id' => $value['id']);

            }
        }

        if (!empty($updateArray)) {
            $this->db->update_batch('courtver', $updateArray, 'id');
        }
        // GLobal DB

        $query = $this->db->query("SELECT glodbver.id, iniated_date,clients_details.tat_globdbver,(SELECT sum(hold_days) FROM glodbver_insuff empinsf WHERE empinsf.glodbver_id = glodbver.id) AS totat_days
            FROM glodbver
            INNER JOIN glodbver_result ON ( glodbver_result.glodbver_id = glodbver.id and glodbver_result.verfstatus in (" . $res_filter['wip_filter'] . ") )
            INNER JOIN candidates_info ON candidates_info.id = glodbver.candsid
            INNER JOIN clients_details ON (clients_details.tbl_clients_id =  candidates_info.clientid and clients_details.entity = candidates_info.entity and clients_details.package = candidates_info.package)");

        $result_array = $query->result_array();

        $updateArray = array();
        foreach ($result_array as $key => $value) {

            $iniation_date = $value['tat_globdbver'] - 1;

            $new_date = getWorkingDays($value['iniated_date'], array(), $iniation_date);

            if ($new_date) {
                $final_date = $this->get_holidays_list($value['iniated_date'], $new_date);
                $tat_status = $this->case_tat_status($final_date);
                $updateArray[] = array('due_date' => $final_date, 'tat_status' => $tat_status, 'id' => $value['id']);

            }
        }

        if (!empty($updateArray)) {
            $this->db->update_batch('glodbver', $updateArray, 'id');
        }

        //Drug Verificatin
        $query = $this->db->query("SELECT drug_narcotis.id, iniated_date,clients_details.tat_narcver,(SELECT sum(hold_days) FROM drug_narcotis_insuff empinsf WHERE empinsf.drug_narcotis_id = drug_narcotis.id) AS totat_days
            FROM drug_narcotis
            INNER JOIN drug_narcotis_result ON ( drug_narcotis_result.drug_narcotis_id = drug_narcotis.id and drug_narcotis_result.verfstatus in (" . $res_filter['wip_filter'] . ") )
            INNER JOIN candidates_info ON candidates_info.id = drug_narcotis.candsid
            INNER JOIN clients_details ON (clients_details.tbl_clients_id =  candidates_info.clientid and clients_details.entity = candidates_info.entity and clients_details.package = candidates_info.package)");

        $result_array = $query->result_array();

        $updateArray = array();
        foreach ($result_array as $key => $value) {

            $iniation_date = $value['tat_narcver'] - 1;

            $new_date = getWorkingDays($value['iniated_date'], array(), $iniation_date);

            if ($new_date) {
                $final_date = $this->get_holidays_list($value['iniated_date'], $new_date);
                $tat_status = $this->case_tat_status($final_date);
                $updateArray[] = array('due_date' => $final_date, 'tat_status' => $tat_status, 'id' => $value['id']);

            }
        }

        if (!empty($updateArray)) {
            $this->db->update_batch('drug_narcotis', $updateArray, 'id');
        }
        // PCC
        $query = $this->db->query("SELECT pcc.id, iniated_date,clients_details.tat_crimver,(SELECT sum(hold_days) FROM pcc_insuff empinsf WHERE empinsf.pcc_id = pcc.id) AS totat_days
            FROM pcc
            INNER JOIN pcc_result ON ( pcc_result.pcc_id = pcc.id and pcc_result.verfstatus in (" . $res_filter['wip_filter'] . ") )
            INNER JOIN candidates_info ON candidates_info.id = pcc.candsid
            INNER JOIN clients_details ON (clients_details.tbl_clients_id =  candidates_info.clientid and clients_details.entity = candidates_info.entity and clients_details.package = candidates_info.package)");

        $result_array = $query->result_array();

        $updateArray = array();
        foreach ($result_array as $key => $value) {

            $iniation_date = $value['tat_crimver'] - 1;

            $new_date = getWorkingDays($value['iniated_date'], array(), $iniation_date);

            if ($new_date) {
                $final_date = $this->get_holidays_list($value['iniated_date'], $new_date);
                $tat_status = $this->case_tat_status($final_date);
                $updateArray[] = array('due_date' => $final_date, 'tat_status' => $tat_status, 'id' => $value['id']);

            }
        }

        if (!empty($updateArray)) {
            $this->db->update_batch('pcc', $updateArray, 'id');
        }

        // Identity
        $query = $this->db->query("SELECT identity.id, iniated_date,clients_details.tat_identity,(SELECT sum(hold_days) FROM identity_insuff empinsf WHERE empinsf.identity_id = identity.id) AS totat_days
            FROM identity
            INNER JOIN identity_result ON ( identity_result.identity_id = identity.id and identity_result.verfstatus in (" . $res_filter['wip_filter'] . ") )
            INNER JOIN candidates_info ON candidates_info.id = identity.candsid
            INNER JOIN clients_details ON (clients_details.tbl_clients_id =  candidates_info.clientid and clients_details.entity = candidates_info.entity and clients_details.package = candidates_info.package)");

        $result_array = $query->result_array();

        $updateArray = array();
        foreach ($result_array as $key => $value) {

            $iniation_date = $value['tat_identity'] - 1;

            $new_date = getWorkingDays($value['iniated_date'], array(), $iniation_date);

            if ($new_date) {
                $final_date = $this->get_holidays_list($value['iniated_date'], $new_date);
                $tat_status = $this->case_tat_status($final_date);
                $updateArray[] = array('due_date' => $final_date, 'tat_status' => $tat_status, 'id' => $value['id']);

            }
        }

        if (!empty($updateArray)) {
            $this->db->update_batch('identity', $updateArray, 'id');
        }
    }

    public function scheduler_task()
    {
        $this->load->model('report_generated_user');

        $this->report_generated_user->select_schedule_task_days(false, array(), array('status' => STATUS_ACTIVE, 'activity_days'));
    }

    public function overall_status_update($cands_id)
    {
        if (!$cands_id) {
            return true;
        }

        $where_array = array('candidates_info.id' => $cands_id);

        $component_status = array();

        $this->load->model('candidates_model');

        $candidate_details = $this->candidates_model->select(true, array('*'), $where_array);

        $client_components = $this->candidates_model->get_entitypackages(array('tbl_clients_id' => $candidate_details['clientid'], 'entity' => $candidate_details['entity'], 'package' => $candidate_details['package']));

        if (isset($client_components[0]['component_id'])) {
            $component_check = explode(',', $client_components[0]['component_id']);
        } else {
            $component_check = array();
        }

        if (in_array('addrver', $component_check)) {

            $result = $this->candidates_model->get_addres_ver_status($where_array);
            if (!empty($result)) {
                foreach ($result as $key => $value) {
                    if ($value['verfstatus'] == 'NA') {
                        continue;
                    }

                    $statuss = ($value['verfstatus'] != "") ? $value['verfstatus'] : 'WIP';
                    $component_status[] = array($statuss, $value['closuredate']);
                }
            }
        }

        if (in_array('empver', $component_check));
        {
            $result = $this->candidates_model->get_employment_ver_status($where_array);

            if (!empty($result)) {
                foreach ($result as $key => $value) {
                    if ($value['verfstatus'] == 'NA') {
                        continue;
                    }
                    $statuss = ($value['verfstatus'] != "") ? $value['verfstatus'] : 'WIP';
                    $component_status[] = array($statuss, $value['closuredate']);
                    //$component_status[] = ($value['verfstatus'] != "") ? $value['verfstatus'] : 'WIP';
                }
            }
        }

        if (in_array('eduver', $component_check)) {
            $result = $this->candidates_model->get_education_ver_status($where_array);

            if (!empty($result)) {
                foreach ($result as $key => $value) {
                    if ($value['verfstatus'] == 'NA') {
                        continue;
                    }
                    $statuss = ($value['verfstatus'] != "") ? $value['verfstatus'] : 'WIP';
                    $component_status[] = array($statuss, $value['closuredate']);
                    //$component_status[] = ($value['verfstatus'] != "") ? $value['verfstatus'] : 'WIP';
                }
            }
        }

        if (in_array('refver', $component_check)) {
            $result = $this->candidates_model->get_refver_ver_status($where_array);
            if (!empty($result)) {
                foreach ($result as $key => $value) {
                    if ($value['verfstatus'] == 'NA') {
                        continue;
                    }
                    $statuss = ($value['verfstatus'] != "") ? $value['verfstatus'] : 'WIP';
                    $component_status[] = array($statuss, $value['closuredate']);
                    //$component_status[] = ($value['verfstatus'] != "") ? $value['verfstatus'] : 'WIP';
                }
            }
        }

        if (in_array('courtver', $component_check));
        {
            $result = $this->candidates_model->get_court_ver_status($where_array);
            if (!empty($result)) {
                foreach ($result as $key => $value) {
                    if ($value['verfstatus'] == 'NA') {
                        continue;
                    }
                    $statuss = ($value['verfstatus'] != "") ? $value['verfstatus'] : 'WIP';
                    $component_status[] = array($statuss, $value['closuredate']);
                    //$component_status[] = ($value['verfstatus'] != "") ? $value['verfstatus'] : 'WIP';
                }
            }
        }

        if (in_array('globdbver', $component_check)) {
            $result = $this->candidates_model->get_globdbver_ver_status($where_array);
            if (!empty($result)) {
                foreach ($result as $key => $value) {
                    if ($value['verfstatus'] == 'NA') {
                        continue;
                    }
                    $statuss = ($value['verfstatus'] != "") ? $value['verfstatus'] : 'WIP';
                    $component_status[] = array($statuss, $value['closuredate']);
                    //$component_status[] = ($value['verfstatus'] != "") ? $value['verfstatus'] : 'WIP';
                }
            }
        }

        if (in_array('crimver', $component_check)) {
            $result = $this->candidates_model->get_pcc_ver_status($where_array);
            if (!empty($result)) {
                foreach ($result as $key => $value) {
                    if ($value['verfstatus'] == 'NA') {
                        continue;
                    }
                    $statuss = ($value['verfstatus'] != "") ? $value['verfstatus'] : 'WIP';
                    $component_status[] = array($statuss, $value['closuredate']);
                    //$component_status[] = ($value['verfstatus'] != "") ? $value['verfstatus'] : 'WIP';
                }
            }
        }

        if (in_array('identity', $component_check)) {
            $result = $this->candidates_model->get_identity_ver_status($where_array);
            if (!empty($result)) {
                foreach ($result as $key => $value) {
                    if ($value['verfstatus'] == 'NA') {
                        continue;
                    }
                    $statuss = ($value['verfstatus'] != "") ? $value['verfstatus'] : 'WIP';
                    $component_status[] = array($statuss, $value['closuredate']);
                    //$component_status[] = ($value['verfstatus'] != "") ? $value['verfstatus'] : 'WIP';
                }
            }
        }

        if (in_array('cbrver', $component_check)) {
            $result = $this->candidates_model->get_credit_reports_ver_status($where_array);
            if (!empty($result)) {
                foreach ($result as $key => $value) {
                    if ($value['verfstatus'] == 'NA') {
                        continue;
                    }
                    $statuss = ($value['verfstatus'] != "") ? $value['verfstatus'] : 'WIP';
                    $component_status[] = array($statuss, $value['closuredate']);
                    //$component_status[] = ($value['verfstatus'] != "") ? $value['verfstatus'] : 'WIP';
                }
            }
        }

        if (in_array('narcver', $component_check)) {
            $result = $this->candidates_model->get_narcver_ver_status($where_array);
            if (!empty($result)) {
                foreach ($result as $key => $value) {
                    if ($value['verfstatus'] == 'NA') {
                        continue;
                    }
                    $statuss = ($value['verfstatus'] != "") ? $value['verfstatus'] : 'WIP';
                    $component_status[] = array($statuss, $value['closuredate']);
                    //$component_status[] = ($value['verfstatus'] != "") ? $value['verfstatus'] : 'WIP';
                }
            }
        }

        if (in_array('social_media', $component_check)) {
            $result = $this->candidates_model->get_social_media_ver_status($where_array);
            if (!empty($result)) {
                foreach ($result as $key => $value) {
                    if ($value['verfstatus'] == 'NA') {
                        continue;
                    }
                    $statuss = ($value['verfstatus'] != "") ? $value['verfstatus'] : 'WIP';
                    $component_status[] = array($statuss, $value['closuredate']);
                    //$component_status[] = ($value['verfstatus'] != "") ? $value['verfstatus'] : 'WIP';
                }
            }
        }

//print_r($component_status);
        //$component_status = array_unique($component_status);

        $component_status = $this->array_flatten($component_status);

        $dates = $component_status['dates'];
        $component_status = $component_status['status'];
        if(!empty($dates))
        {
          $max_date = max($dates);
        }
        else
        {
          $max_date = NULL;  
        }

        // $check_status = array('Stop/Check','Insufficiency','Insufficiency II','Discrepancy','Discrepancy II','WIP','Clear','Work With the Same Organization');

        $check_status = array('Major Discrepancy', 'Minor Discrepancy', 'Unable to verify', 'Stop Check', 'Clear');

        if (!in_array("WIP", $component_status) && !in_array("Insufficiency", $component_status) && !in_array("Insufficiency Cleared", $component_status) && !in_array("Final QC Reject", $component_status) && !in_array("First QC Reject", $component_status) && !in_array("New Check", $component_status) && !in_array("YTR", $component_status) && !in_array("Follow Up", $component_status)) {

            foreach ($check_status as $key => $value) {

                //  $update_array = array('overallstatus' => $value,'updated' => date(DB_DATE_FORMAT));

                if (in_array($value, $component_status)) {

                    if ($value == "Major Discrepancy") {
                        $actual_status = '6';
                    } elseif ($value == "Minor Discrepancy") {
                        $actual_status = '7';
                    } elseif ($value == "Unable to verify") {
                        $actual_status = '8';
                    } elseif ($value == "Stop Check") {
                        $actual_status = '3';
                    } elseif ($value == "Clear") {
                        $actual_status = '4';
                    }
//print_r( $component_status);

                    if ($value != 'Insufficiency' && $value != 'WIP') {
                        $update_array = array('overallstatus' => $actual_status, 'modified_on' => date(DB_DATE_FORMAT));
                        $update_array['overallclosuredate'] = $max_date;
                       
                        if ($client_components[0]['final_qc'] == 1)
                        {   
                            $update_array['final_qc_send_mail'] = "";
                            $update_array['final_qc'] = "final qc pending";
                            $update_array['final_qc_arriving_timestamp'] = date(DB_DATE_FORMAT);
                        }
                    }

                    // $statuss = $this->changestatus($value);

                    //  $update_array['overallstatus'] = $statuss;

                    //show($update_array);
                    $rdesult = $this->candidates_model->save($update_array, array('id' => $cands_id));
                    break;
                }
            }
        }

        if (in_array("Insufficiency", $component_status)) {
            $update_array = array('overallstatus' => "5", 'modified_on' => date(DB_DATE_FORMAT));
            $update_array['overallclosuredate'] = "";
            $update_array['final_qc'] = "";
            $update_array['final_qc_send_mail'] = "";
            $update_array['final_qc_arriving_timestamp'] = "";
            $rdesult = $this->candidates_model->save($update_array, array('id' => $cands_id));
        }

        if ((!in_array("Insufficiency", $component_status)) && (in_array("WIP", $component_status) || in_array("Insufficiency Cleared", $component_status) || in_array("Final QC Reject", $component_status) || in_array("First QC Reject", $component_status) || in_array("New Check", $component_status) || in_array("YTR", $component_status) || in_array("Follow Up", $component_status) || in_array("Re-Initiated", $component_status))) {
            $update_array = array('overallstatus' => "1", 'modified_on' => date(DB_DATE_FORMAT));
            $update_array['overallclosuredate'] = "";
            $update_array['final_qc'] = "";
            $update_array['final_qc_send_mail'] = "";
            $update_array['final_qc_arriving_timestamp'] = "";
            $rdesult = $this->candidates_model->save($update_array, array('id' => $cands_id));
            //break;
        }

        exit();
    }

    public function array_flatten($array)
    {

        if (!is_array($array)) {
            return false;
        }

        $result = $dates = array();

        foreach ($array as $key => $value) {

            $result[] = $value[0];
            if ($value[1] != '') {
                $dates[] = $value[1];
            }

        }
        $new_arry = array('status' => $result, 'dates' => $dates);
        return $new_arry;
    }

    public function update_tat_status_candidate($cands_id)
    {

        if (!$cands_id) {
            return true;
        }

        $where_array = array('candidates_info.id' => $cands_id, 'status' => 1, 'overallstatus' => 1);

        $component_max_value = array();

        $this->load->model('candidates_model');

        $candidate_detail = $this->candidates_model->select(true, array('*'), $where_array);

        $client_components = $this->candidates_model->get_entitypackages(array('tbl_clients_id' => $candidate_detail['clientid'], 'entity' => $candidate_detail['entity'], 'package' => $candidate_detail['package']));

        if (isset($client_components[0]['component_id'])) {
            $component_check = explode(',', $client_components[0]['component_id']);
        } else {
            $component_check = array();
        }

        if (in_array('addrver', $component_check)) {
            $result = $this->candidates_model->get_addres_due_tat_status(array('addrver.candsid' => $candidate_detail['id'], 'ev1.var_filter_status' => 'WIP'));

            if (!empty($result)) {

                foreach ($result as $key => $value) {
                    $component_max_value[] = array($value['due_date']);
                }
            }
        }

        if (in_array('empver', $component_check));
        {
            $result = $this->candidates_model->get_employment_due_tat_status(array('empver.candsid' => $candidate_detail['id'], 'ev1.var_filter_status' => 'WIP'));

            if (!empty($result)) {
                foreach ($result as $key => $value) {
                    $component_max_value[] = array($value['due_date']);
                }
            }
        }

        if (in_array('eduver', $component_check));
        {
            $result = $this->candidates_model->get_education_due_tat_status(array('education.candsid' => $candidate_detail['id'], 'ev1.var_filter_status' => 'WIP'));

            if (!empty($result)) {
                foreach ($result as $key => $value) {
                    $component_max_value[] = array($value['due_date']);
                }
            }
        }

        if (in_array('refver', $component_check));
        {
            $result = $this->candidates_model->get_reference_due_tat_status(array('reference.candsid' => $candidate_detail['id'], 'ev1.var_filter_status' => 'WIP'));

            if (!empty($result)) {
                foreach ($result as $key => $value) {
                    $component_max_value[] = array($value['due_date']);
                }
            }
        }

        if (in_array('courtver', $component_check));
        {
            $result = $this->candidates_model->get_court_due_tat_status(array('courtver.candsid' => $candidate_detail['id'], 'ev1.var_filter_status' => 'WIP'));

            if (!empty($result)) {
                foreach ($result as $key => $value) {
                    $component_max_value[] = array($value['due_date']);
                }
            }
        }

        if (in_array('globdbver', $component_check));
        {
            $result = $this->candidates_model->get_global_due_tat_status(array('glodbver.candsid' => $candidate_detail['id'], 'ev1.var_filter_status' => 'WIP'));

            if (!empty($result)) {
                foreach ($result as $key => $value) {
                    $component_max_value[] = array($value['due_date']);
                }
            }
        }

        if (in_array('crimver', $component_check));
        {
            $result = $this->candidates_model->get_pcc_due_tat_status(array('pcc.candsid' => $candidate_detail['id'], 'ev1.var_filter_status' => 'WIP'));

            if (!empty($result)) {
                foreach ($result as $key => $value) {
                    $component_max_value[] = array($value['due_date']);
                }
            }
        }

        if (in_array('identity', $component_check));
        {
            $result = $this->candidates_model->get_identity_due_tat_status(array('identity.candsid' => $candidate_detail['id'], 'ev1.var_filter_status' => 'WIP'));

            if (!empty($result)) {
                foreach ($result as $key => $value) {
                    $component_max_value[] = array($value['due_date']);
                }
            }
        }

        if (in_array('cbrver', $component_check));
        {
            $result = $this->candidates_model->get_credit_report_due_tat_status(array('credit_report.candsid' => $candidate_detail['id'], 'ev1.var_filter_status' => 'WIP'));

            if (!empty($result)) {
                foreach ($result as $key => $value) {
                    $component_max_value[] = array($value['due_date']);
                }
            }
        }

        if (in_array('narcver', $component_check));
        {
            $result = $this->candidates_model->get_drugs_due_tat_status(array('drug_narcotis.candsid' => $candidate_detail['id'], 'ev1.var_filter_status' => 'WIP'));

            if (!empty($result)) {
                foreach ($result as $key => $value) {
                    $component_max_value[] = array($value['due_date']);
                }
            }
        }

        if (in_array('social_media', $component_check));
        {
            $result = $this->candidates_model->get_social_media_due_tat_status(array('social_media.candsid' => $candidate_detail['id'], 'ev1.var_filter_status' => 'WIP'));

            if (!empty($result)) {
                foreach ($result as $key => $value) {
                    $component_max_value[] = array($value['due_date']);
                }
            }
        }

        $component_dates = $this->array_flatten_tat_status($component_max_value);

        $dates = $component_dates['dates'];

        $max_date = max($dates);

        $get_holiday1 = $this->get_holiday();

        $get_holiday = array_map('current', $get_holiday1);

        $today = date("Y-m-d");

        $x = 1;
        $counter_1 = 0;
        while ($x <= 10) {

            $yesterday = date('Y-m-d', strtotime('-' . $x . ' day', strtotime($max_date)));

            $today_date1 = strtotime($yesterday);

            $day_name1 = date("D", $today_date1);

            if ($day_name1 == 'Sat' || $day_name1 == 'Sun' || in_array($yesterday, $get_holiday) == true) {

                $counter_1 += 1;
            } else {
                break;
            }

            $x++;
        }

        $yesterday_main = $yesterday;

        $y = 1;
        $counter_2 = 0;
        while ($y <= 10) {

            $yesterday_ago = date('Y-m-d', strtotime('-' . $y . ' day', strtotime($yesterday_main)));

            $today_date2 = strtotime($yesterday_ago);

            $day_name2 = date("D", $today_date2);

            if ($day_name2 == 'Sat' || $day_name2 == 'Sun' || in_array($yesterday_ago, $get_holiday) == true) {

                $counter_2 += 1;
            } else {
                break;
            }
            $y++;
        }

        $yesterday_ago_main = $yesterday_ago;

        $date_array = array($yesterday_main, $yesterday_ago_main);

        $date_array = array($yesterday_main, $yesterday_ago_main);

        if (!empty($max_date)) {

            if ($max_date < $today) {
                $new_tat = "OUT TAT";
            } elseif ($max_date == $today) {
                $new_tat = "TDY TAT";
            } elseif (in_array(date("Y-m-d"), $date_array) == true) {
                $new_tat = "AP TAT";
            } else {
                $new_tat = "IN TAT";
            }

            $result_due_date = $this->candidates_model->update_candidate_due_date(array('due_date_candidate' => $max_date, 'tat_status_candidate' => $new_tat), array('id' => $candidate_detail['id']));

        }
    }

    public function array_flatten_tat_status($array)
    {

        if (!is_array($array)) {
            return false;
        }

        $result = array();

        foreach ($array as $key => $value) {

            $result[] = $value[0];

        }
        $new_arry = array('dates' => $result);
        return $new_arry;
    }

    public function get_holiday()
    {
        $this->CI = &get_instance();

        $query = $this->CI->db->query("SELECT holiday_date  FROM holiday_dates WHERE status = 1");

        record_db_error($this->CI->db->last_query());

        $result = $query->result_array();

        if (!empty($result)) {
            return $result;
        }
        return false;

    }

    public function auto_all_component_closed_qc_status($cands_id)
    {

        $this->load->model('candidates_model');

        $this->load->model('Final_qc_model');

        $where_array = array('candidates_info.id' => $cands_id, 'candidates_info.status' => 1, 'clients_details.final_qc' => 1);

        $candidate_detail = $this->candidates_model->select_closed_qc_status($where_array);

        if (isset($candidate_detail[0]['component_id'])) {

            $component_check = explode(',', $candidate_detail[0]['component_id']);
        } else {
            $component_check = array();
        }

        if (isset($candidate_detail[0]['first_qc_component_name'])) {

            $component_first_check = explode(',', $candidate_detail[0]['first_qc_component_name']);
        } else {
            $component_first_check = array();
        }

        if (in_array('addrver', $component_check)) {

            $result = $this->Final_qc_model->get_address_closed_qc_result($cands_id);

            if (!empty($result)) {
                foreach ($result as $key => $value) {

                    $statuss = $value['var_filter_status'] == "Closed" || $value['var_filter_status'] == "closed" || $value['var_filter_status'] == "NA" ? "" : 'Address';
                    $component_status[] = $statuss;
                }
            }
        }

        if (in_array('addrver', $component_first_check)) {

            $result = $this->Final_qc_model->get_address_closed_qc_result($cands_id);

            if (!empty($result)) {
                foreach ($result as $key => $value) {

                    $statuss = $value['first_qc_approve'] == "First QC Approve" ? "" : 'Address';
                    $component_first_qc_status[] = $statuss;
                }
            }
        }

        if (in_array('empver', $component_check)) {

            $result = $this->Final_qc_model->get_employment_closed_qc_result($cands_id);

            if (!empty($result)) {
                foreach ($result as $key => $value) {
                    $statuss = $value['var_filter_status'] == "Closed" || $value['var_filter_status'] == "closed" || $value['var_filter_status'] == "NA" ? "" : 'Employment';
                    $component_status[] = $statuss;
                }
            }
        }

        if (in_array('empver', $component_first_check)) {

            $result = $this->Final_qc_model->get_employment_closed_qc_result($cands_id);

            if (!empty($result)) {
                foreach ($result as $key => $value) {

                    $statuss = $value['first_qc_approve'] == "First QC Approve" ? "" : 'Employment';
                    $component_first_qc_status[] = $statuss;
                }
            }
        }

        if (in_array('eduver', $component_check)) {

            $result = $this->Final_qc_model->get_education_closed_qc_result($cands_id);

            if (!empty($result)) {
                foreach ($result as $key => $value) {
                    $statuss = $value['var_filter_status'] == "Closed" || $value['var_filter_status'] == "closed" || $value['var_filter_status'] == "NA" ? "" : 'Education';
                    $component_status[] = $statuss;
                }
            }
        }

        if (in_array('eduver', $component_first_check)) {

            $result = $this->Final_qc_model->get_education_closed_qc_result($cands_id);

            if (!empty($result)) {
                foreach ($result as $key => $value) {

                    $statuss = $value['first_qc_approve'] == "First QC Approve" ? "" : 'Education';
                    $component_first_qc_status[] = $statuss;
                }
            }
        }

        if (in_array('refver', $component_check)) {

            $result = $this->Final_qc_model->get_reference_closed_qc_result($cands_id);

            if (!empty($result)) {
                foreach ($result as $key => $value) {
                    $statuss = $value['var_filter_status'] == "Closed" || $value['var_filter_status'] == "closed" || $value['var_filter_status'] == "NA" ? "" : 'Reference';
                    $component_status[] = $statuss;
                }
            }
        }

        if (in_array('refver', $component_first_check)) {

            $result = $this->Final_qc_model->get_reference_closed_qc_result($cands_id);

            if (!empty($result)) {
                foreach ($result as $key => $value) {

                    $statuss = $value['first_qc_approve'] == "First QC Approve" ? "" : 'Reference';
                    $component_first_qc_status[] = $statuss;
                }
            }
        }

        if (in_array('courtver', $component_check)) {

            $result = $this->Final_qc_model->get_court_closed_qc_result($cands_id);

            if (!empty($result)) {
                foreach ($result as $key => $value) {
                    $statuss = $value['var_filter_status'] == "Closed" || $value['var_filter_status'] == "closed" || $value['var_filter_status'] == "NA" ? "" : 'Court';
                    $component_status[] = $statuss;
                }
            }
        }

        if (in_array('courtver', $component_first_check)) {

            $result = $this->Final_qc_model->get_court_closed_qc_result($cands_id);

            if (!empty($result)) {
                foreach ($result as $key => $value) {

                    $statuss = $value['first_qc_approve'] == "First QC Approve" ? "" : 'Court';
                    $component_first_qc_status[] = $statuss;
                }
            }
        }

        if (in_array('globdbver', $component_check)) {

            $result = $this->Final_qc_model->get_global_closed_qc_result($cands_id);

            if (!empty($result)) {
                foreach ($result as $key => $value) {
                    $statuss = $value['var_filter_status'] == "Closed" || $value['var_filter_status'] == "closed" || $value['var_filter_status'] == "NA" ? "" : 'Global';
                    $component_status[] = $statuss;
                }
            }
        }

        if (in_array('globdbver', $component_first_check)) {

            $result = $this->Final_qc_model->get_global_closed_qc_result($cands_id);

            if (!empty($result)) {
                foreach ($result as $key => $value) {

                    $statuss = $value['first_qc_approve'] == "First QC Approve" ? "" : 'Global';
                    $component_first_qc_status[] = $statuss;
                }
            }
        }

        if (in_array('crimver', $component_check)) {

            $result = $this->Final_qc_model->get_pcc_closed_qc_result($cands_id);

            if (!empty($result)) {
                foreach ($result as $key => $value) {
                    $statuss = $value['var_filter_status'] == "Closed" || $value['var_filter_status'] == "closed" || $value['var_filter_status'] == "NA" ? "" : 'PCC';
                    $component_status[] = $statuss;
                }
            }
        }

        if (in_array('crimver', $component_first_check)) {

            $result = $this->Final_qc_model->get_pcc_closed_qc_result($cands_id);

            if (!empty($result)) {
                foreach ($result as $key => $value) {

                    $statuss = $value['first_qc_approve'] == "First QC Approve" ? "" : 'PCC';
                    $component_first_qc_status[] = $statuss;
                }
            }
        }

        if (in_array('identity', $component_check)) {

            $result = $this->Final_qc_model->get_identity_closed_qc_result($cands_id);

            if (!empty($result)) {
                foreach ($result as $key => $value) {
                    $statuss = $value['var_filter_status'] == "Closed" || $value['var_filter_status'] == "closed" || $value['var_filter_status'] == "NA" ? "" : 'Identity';
                    $component_status[] = $statuss;
                }
            }
        }

        if (in_array('identity', $component_first_check)) {

            $result = $this->Final_qc_model->get_identity_closed_qc_result($cands_id);

            if (!empty($result)) {
                foreach ($result as $key => $value) {

                    $statuss = $value['first_qc_approve'] == "First QC Approve" ? "" : 'Identity';
                    $component_first_qc_status[] = $statuss;
                }
            }
        }

        if (in_array('cbrver', $component_check)) {

            $result = $this->Final_qc_model->get_credit_report_closed_qc_result($cands_id);

            if (!empty($result)) {
                foreach ($result as $key => $value) {
                    $statuss = $value['var_filter_status'] == "Closed" || $value['var_filter_status'] == "closed" || $value['var_filter_status'] == "NA" ? "" : 'Credit Report';
                    $component_status[] = $statuss;
                }
            }
        }

        if (in_array('cbrver', $component_first_check)) {

            $result = $this->Final_qc_model->get_credit_report_closed_qc_result($cands_id);

            if (!empty($result)) {
                foreach ($result as $key => $value) {

                    $statuss = $value['first_qc_approve'] == "First QC Approve" ? "" : 'Credit Report';
                    $component_first_qc_status[] = $statuss;
                }
            }
        }

        if (in_array('narcver', $component_check)) {

            $result = $this->Final_qc_model->get_drugs_closed_qc_result($cands_id);

            if (!empty($result)) {
                foreach ($result as $key => $value) {
                    $statuss = $value['var_filter_status'] == "Closed" || $value['var_filter_status'] == "closed" || $value['var_filter_status'] == "NA" ? "" : 'Drugs';
                    $component_status[] = $statuss;
                }
            }
        }

        if (in_array('narcver', $component_first_check)) {

            $result = $this->Final_qc_model->get_drugs_closed_qc_result($cands_id);

            if (!empty($result)) {
                foreach ($result as $key => $value) {

                    $statuss = $value['first_qc_approve'] == "First QC Approve" ? "" : 'Drugs';
                    $component_first_qc_status[] = $statuss;
                }
            }
        }

        if (isset($component_status)) {
            if (count(array_filter($component_status)) == 0) {
                $component_status1 = "1";
            } else {
                $component_status1 = "2";
            }
        } else {
            $component_status1 = "1";
        }

        if (isset($component_first_qc_status)) {
            if (count(array_filter($component_first_qc_status)) == 0) {
                $component_first_qc_status1 = "1";
            } else {
                $component_first_qc_status1 = "2";
            }
        } else {
            $component_first_qc_status1 = "1";
        }

        /*if($component_status1 == 1 && $candidate_detail[0]['first_qc'] == 2)
        {
        $result_final_qc = $this->candidates_model->save(array("final_qc" => "final qc pending","final_qc_arriving_timestamp" => date(DB_DATE_FORMAT)),array("candidates_info.id" => $cands_id));

        }
        if($component_first_qc_status1 == 1 && $candidate_detail[0]['first_qc'] == 1)
        {

        $result_final_qc = $this->candidates_model->save(array("final_qc" => "final qc pending","final_qc_arriving_timestamp" => date(DB_DATE_FORMAT)),array("candidates_info.id" => $cands_id));
        }*/

        if ($component_status1 == 1 && $component_first_qc_status1 == 1 && $candidate_detail[0]['final_qc'] == 1) {
            $result_final_qc = $this->candidates_model->save(array("final_qc" => "final qc pending", "final_qc_arriving_timestamp" => date(DB_DATE_FORMAT)), array("candidates_info.id" => $cands_id));

        }

    }

    public function auto_update_client_candidates_status($cands_id)
    {

        if (!$cands_id) {
            return true;
        }

        $where_array = array('client_candidates_info.cands_info_id' => $cands_id, 'status' => 1);


        $this->load->model('client/candidates_model');

        $candidate_detail = $this->candidates_model->select(true, array('*'), $where_array);

        $check_status = 0; 

        if(!empty($candidate_detail)){


            if ($candidate_detail['address_component_check'] == 1) {
                 $check_status = 1; 
            }
            if ($candidate_detail['employment_component_check'] == 1) {
                 $check_status = 1; 
            }
            if ($candidate_detail['education_component_check'] == 1) {
                 $check_status = 1; 
            }
            if ($candidate_detail['reference_component_check'] == 1) {
                 $check_status = 1; 
            }
            if ($candidate_detail['global_component_check'] == 1) {
                 $check_status = 1; 
            }
            if ($candidate_detail['identity_component_check'] == 1) {
                 $check_status = 1; 
            }
            if ($candidate_detail['credit_report_component_check'] == 1) {
                 $check_status = 1; 
            }
            if ($candidate_detail['drugs_component_check'] == 1) {
                 $check_status = 1; 
            }
            if ($candidate_detail['pcc_component_check'] == 1) {
                 $check_status = 1; 
            }

            if($check_status == 1)
            {
                $result_status_update = $this->candidates_model->save(array('overallstatus' => 5), array('cands_info_id' => $cands_id));
                $result_main_table_status_update = $this->candidates_model->save_actual_table(array('overallstatus' => 5), array('id' => $cands_id));
            }else{

                $result_status_update = $this->candidates_model->save(array('overallstatus' => 1), array('cands_info_id' => $cands_id));
                $result_main_table_status_update = $this->candidates_model->save_actual_table(array('overallstatus' => 1), array('id' => $cands_id));   
            }
        }
    }

    public function generate_export_activity_data($parameters_array = null, $activity_from, $activity_to)
    {

        $this->load->model('report_generated_user');

        $where_arry = array('users_activitity_data.created_on >' => convert_display_to_db_date($activity_from) . ' 00:00:00', 'users_activitity_data.created_on <' => convert_display_to_db_date($activity_to) . ' 23:59:59');

        $file_upload_path = SITE_BASE_PATH . UPLOAD_FOLDER . 'bulk_reports';

        if (!folder_exist($file_upload_path)) {
            mkdir($file_upload_path, 0777);
        }
        if (!folder_exist($file_upload_path)) {
            mkdir($file_upload_path, 0777);
        } else if (!is_writable($file_upload_path)) {
            mkdir($file_upload_path, 0777);
        }

        $all_records = $this->report_generated_user->get_activity_records($where_arry);

        require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
        // Create new Spreadsheet object
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        // Set document properties
        $spreadsheet->getProperties()->setCreator(CRMNAME)
            ->setLastModifiedBy(CRMNAME)
            ->setTitle(CRMNAME)
            ->setSubject('Activity Records')
            ->setDescription('Activity Records');
        // add style to the header
        $styleArray = array(
            'font' => array(
                'bold' => true,
            ),
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
                'top' => array(
                    'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ),
            ),
            'fill' => array(
                'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                'rotation' => 90,
                'startcolor' => array(
                    'argb' => 'FFA0A0A0',
                ),
                'endcolor' => array(
                    'argb' => 'FFFFFFFF',
                ),
            ),
        );
        $spreadsheet->getActiveSheet()->getStyle('A1:O1')->applyFromArray($styleArray);
        // auto fit column to content
        foreach (range('A', 'O') as $columnID) {
            $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                ->setWidth(20);
        }

        // set the names of header cells
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue("A1", 'Components')
            ->setCellValue("B1", 'Ref No.')
            ->setCellValue("C1", 'Candidate Name')
            ->setCellValue("D1", 'Created On')
            ->setCellValue("E1", 'Created By')
            ->setCellValue("F1", 'Activity Type')
            ->setCellValue("G1", 'Action')
            ->setCellValue("H1", 'Current Status')
            ->setCellValue("I1", 'Client Name')
            ->setCellValue("J1", 'Entity')
            ->setCellValue("K1", 'Package')
            ->setCellValue("L1", 'Received Date')
            ->setCellValue("M1", 'Due date')
            ->setCellValue("N1", 'TAT Status')
            ->setCellValue("O1", 'Assigned To');
        // Add some data
        $x = 2;

        foreach ($all_records as $all_record) {

            $spreadsheet->setActiveSheetIndex(0);
            $spreadsheet->getActiveSheet()->setCellValue("A$x", $all_record['component']);
            $spreadsheet->getActiveSheet()->setCellValue("B$x", $all_record['ref_no']);
            $spreadsheet->getActiveSheet()->setCellValue("C$x", $all_record['candidate_name']);
            $spreadsheet->getActiveSheet()->setCellValue("D$x", convert_db_to_display_date($all_record['created_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12));
            $spreadsheet->getActiveSheet()->setCellValue("E$x", $all_record['username']);
            $spreadsheet->getActiveSheet()->setCellValue("F$x", $all_record['activity_type']);
            $spreadsheet->getActiveSheet()->setCellValue("G$x", $all_record['action']);

            if ($all_record['component'] == "Candidate") {
                $candidate_records = $this->report_generated_user->get_candidate_records(array('candidates_info.status' => STATUS_ACTIVE, 'candidates_info.cmp_ref_no' => $all_record['ref_no']));
                foreach ($candidate_records as $candidate_record) {

                    $spreadsheet->getActiveSheet()->setCellValue("H$x", $candidate_record['status_value']);
                    $spreadsheet->getActiveSheet()->setCellValue("I$x", $candidate_record['clientname']);
                    $spreadsheet->getActiveSheet()->setCellValue("J$x", $candidate_record['entity_name']);
                    $spreadsheet->getActiveSheet()->setCellValue("K$x", $candidate_record['package_name']);
                    $spreadsheet->getActiveSheet()->setCellValue("L$x", convert_db_to_display_date($candidate_record['caserecddate']));
                    $spreadsheet->getActiveSheet()->setCellValue("M$x", convert_db_to_display_date($candidate_record['due_date_candidate']));
                    $spreadsheet->getActiveSheet()->setCellValue("N$x", $candidate_record['tat_status_candidate']);
                    $spreadsheet->getActiveSheet()->setCellValue("O$x", "-");

                }

            }
            if ($all_record['component'] == "Address") {
                $address_records = $this->report_generated_user->get_address_records(array('addrver.add_com_ref' => $all_record['ref_no']));

                foreach ($address_records as $address_record) {

                    if (strtoupper($all_record['ref_no']) == strtoupper($address_record['add_com_ref'])) {
                        $spreadsheet->getActiveSheet()->setCellValue("H$x", $address_record['status_value']);
                        $spreadsheet->getActiveSheet()->setCellValue("I$x", $address_record['clientname']);
                        $spreadsheet->getActiveSheet()->setCellValue("J$x", $address_record['entity_name']);
                        $spreadsheet->getActiveSheet()->setCellValue("K$x", $address_record['package_name']);
                        $spreadsheet->getActiveSheet()->setCellValue("L$x", convert_db_to_display_date($address_record['iniated_date']));
                        $spreadsheet->getActiveSheet()->setCellValue("M$x", convert_db_to_display_date($address_record['due_date']));
                        $spreadsheet->getActiveSheet()->setCellValue("N$x", $address_record['tat_status']);
                        $spreadsheet->getActiveSheet()->setCellValue("O$x", $address_record['executive_name']);

                    }
                }

            }
            if ($all_record['component'] == "Employment") {
                $employment_records = $this->report_generated_user->get_employment_records(array('empver.emp_com_ref' => $all_record['ref_no']));

                foreach ($employment_records as $employment_record) {
                    $spreadsheet->getActiveSheet()->setCellValue("H$x", $employment_record['status_value']);
                    $spreadsheet->getActiveSheet()->setCellValue("I$x", $employment_record['clientname']);
                    $spreadsheet->getActiveSheet()->setCellValue("J$x", $employment_record['entity_name']);
                    $spreadsheet->getActiveSheet()->setCellValue("K$x", $employment_record['package_name']);
                    $spreadsheet->getActiveSheet()->setCellValue("L$x", convert_db_to_display_date($employment_record['iniated_date']));
                    $spreadsheet->getActiveSheet()->setCellValue("M$x", convert_db_to_display_date($employment_record['due_date']));
                    $spreadsheet->getActiveSheet()->setCellValue("N$x", $employment_record['tat_status']);
                    $spreadsheet->getActiveSheet()->setCellValue("O$x", $employment_record['executive_name']);

                }

            }
            if ($all_record['component'] == "Education") {
                $education_records = $this->report_generated_user->get_education_records(array('education.education_com_ref' => $all_record['ref_no']));

                foreach ($education_records as $education_record) {

                    $spreadsheet->getActiveSheet()->setCellValue("H$x", $education_record['status_value']);
                    $spreadsheet->getActiveSheet()->setCellValue("I$x", $education_record['clientname']);
                    $spreadsheet->getActiveSheet()->setCellValue("J$x", $education_record['entity_name']);
                    $spreadsheet->getActiveSheet()->setCellValue("K$x", $education_record['package_name']);
                    $spreadsheet->getActiveSheet()->setCellValue("L$x", convert_db_to_display_date($education_record['iniated_date']));
                    $spreadsheet->getActiveSheet()->setCellValue("M$x", convert_db_to_display_date($education_record['due_date']));
                    $spreadsheet->getActiveSheet()->setCellValue("N$x", $education_record['tat_status']);
                    $spreadsheet->getActiveSheet()->setCellValue("O$x", $education_record['executive_name']);

                }

            }
            if ($all_record['component'] == "Reference") {
                $reference_records = $this->report_generated_user->get_reference_records(array('reference.reference_com_ref' => $all_record['ref_no']));

                foreach ($reference_records as $reference_record) {
                    $spreadsheet->getActiveSheet()->setCellValue("H$x", $reference_record['status_value']);
                    $spreadsheet->getActiveSheet()->setCellValue("I$x", $reference_record['clientname']);
                    $spreadsheet->getActiveSheet()->setCellValue("J$x", $reference_record['entity_name']);
                    $spreadsheet->getActiveSheet()->setCellValue("K$x", $reference_record['package_name']);
                    $spreadsheet->getActiveSheet()->setCellValue("L$x", convert_db_to_display_date($reference_record['iniated_date']));
                    $spreadsheet->getActiveSheet()->setCellValue("M$x", convert_db_to_display_date($reference_record['due_date']));
                    $spreadsheet->getActiveSheet()->setCellValue("N$x", $reference_record['tat_status']);
                    $spreadsheet->getActiveSheet()->setCellValue("O$x", $reference_record['executive_name']);
                }

            }
            if ($all_record['component'] == "Court Verification") {
                $court_records = $this->report_generated_user->get_court_records(array('courtver.court_com_ref' => $all_record['ref_no']));

                foreach ($court_records as $court_record) {

                    $spreadsheet->getActiveSheet()->setCellValue("H$x", $court_record['status_value']);
                    $spreadsheet->getActiveSheet()->setCellValue("I$x", $court_record['clientname']);
                    $spreadsheet->getActiveSheet()->setCellValue("J$x", $court_record['entity_name']);
                    $spreadsheet->getActiveSheet()->setCellValue("K$x", $court_record['package_name']);
                    $spreadsheet->getActiveSheet()->setCellValue("L$x", convert_db_to_display_date($court_record['iniated_date']));
                    $spreadsheet->getActiveSheet()->setCellValue("M$x", convert_db_to_display_date($court_record['due_date']));
                    $spreadsheet->getActiveSheet()->setCellValue("N$x", $court_record['tat_status']);
                    $spreadsheet->getActiveSheet()->setCellValue("O$x", $court_record['executive_name']);
                }

            }
            if ($all_record['component'] == "Global Database") {
                $global_records = $this->report_generated_user->get_global_database_records(array('glodbver.global_com_ref' => $all_record['ref_no']));

                foreach ($global_records as $global_record) {
                    $spreadsheet->getActiveSheet()->setCellValue("H$x", $global_record['status_value']);
                    $spreadsheet->getActiveSheet()->setCellValue("I$x", $global_record['clientname']);
                    $spreadsheet->getActiveSheet()->setCellValue("J$x", $global_record['entity_name']);
                    $spreadsheet->getActiveSheet()->setCellValue("K$x", $global_record['package_name']);
                    $spreadsheet->getActiveSheet()->setCellValue("L$x", convert_db_to_display_date($global_record['iniated_date']));
                    $spreadsheet->getActiveSheet()->setCellValue("M$x", convert_db_to_display_date($global_record['due_date']));
                    $spreadsheet->getActiveSheet()->setCellValue("N$x", $global_record['tat_status']);
                    $spreadsheet->getActiveSheet()->setCellValue("O$x", $global_record['executive_name']);

                }
            }
            if ($all_record['component'] == "PCC") {
                $pcc_records = $this->report_generated_user->get_pcc_records(array('pcc.pcc_com_ref' => $all_record['ref_no']));

                foreach ($pcc_records as $pcc_record) {
                    $spreadsheet->getActiveSheet()->setCellValue("H$x", $pcc_record['status_value']);
                    $spreadsheet->getActiveSheet()->setCellValue("I$x", $pcc_record['clientname']);
                    $spreadsheet->getActiveSheet()->setCellValue("J$x", $pcc_record['entity_name']);
                    $spreadsheet->getActiveSheet()->setCellValue("K$x", $pcc_record['package_name']);
                    $spreadsheet->getActiveSheet()->setCellValue("L$x", convert_db_to_display_date($pcc_record['iniated_date']));
                    $spreadsheet->getActiveSheet()->setCellValue("M$x", convert_db_to_display_date($pcc_record['due_date']));
                    $spreadsheet->getActiveSheet()->setCellValue("N$x", $pcc_record['tat_status']);
                    $spreadsheet->getActiveSheet()->setCellValue("O$x", $pcc_record['executive_name']);

                }

            }
            if ($all_record['component'] == "Identity") {
                $identity_records = $this->report_generated_user->get_identity_records(array('identity.identity_com_ref' => $all_record['ref_no']));

                foreach ($identity_records as $identity_record) {

                    $spreadsheet->getActiveSheet()->setCellValue("H$x", $identity_record['status_value']);
                    $spreadsheet->getActiveSheet()->setCellValue("I$x", $identity_record['clientname']);
                    $spreadsheet->getActiveSheet()->setCellValue("J$x", $identity_record['entity_name']);
                    $spreadsheet->getActiveSheet()->setCellValue("K$x", $identity_record['package_name']);
                    $spreadsheet->getActiveSheet()->setCellValue("L$x", convert_db_to_display_date($identity_record['iniated_date']));
                    $spreadsheet->getActiveSheet()->setCellValue("M$x", convert_db_to_display_date($identity_record['due_date']));
                    $spreadsheet->getActiveSheet()->setCellValue("N$x", $identity_record['tat_status']);
                    $spreadsheet->getActiveSheet()->setCellValue("O$x", $identity_record['executive_name']);
                }

            }
            if ($all_record['component'] == "Credit Report") {
                $credit_report_records = $this->report_generated_user->get_credit_report_records(array('credit_report.credit_report_com_ref' => $all_record['ref_no']));

                foreach ($credit_report_records as $credit_report_record) {

                    $spreadsheet->getActiveSheet()->setCellValue("H$x", $credit_report_record['status_value']);
                    $spreadsheet->getActiveSheet()->setCellValue("I$x", $credit_report_record['clientname']);
                    $spreadsheet->getActiveSheet()->setCellValue("J$x", $credit_report_record['entity_name']);
                    $spreadsheet->getActiveSheet()->setCellValue("K$x", $credit_report_record['package_name']);
                    $spreadsheet->getActiveSheet()->setCellValue("L$x", convert_db_to_display_date($credit_report_record['iniated_date']));
                    $spreadsheet->getActiveSheet()->setCellValue("M$x", convert_db_to_display_date($credit_report_record['due_date']));
                    $spreadsheet->getActiveSheet()->setCellValue("N$x", $credit_report_record['tat_status']);
                    $spreadsheet->getActiveSheet()->setCellValue("O$x", $credit_report_record['executive_name']);
                }

            }
            if ($all_record['component'] == "Drugs Verification") {
                $drugs_records = $this->report_generated_user->get_drugs_records(array('drug_narcotis.drug_com_ref' => $all_record['ref_no']));

                foreach ($drugs_records as $drugs_record) {

                    $spreadsheet->getActiveSheet()->setCellValue("H$x", $drugs_record['status_value']);
                    $spreadsheet->getActiveSheet()->setCellValue("I$x", $drugs_record['clientname']);
                    $spreadsheet->getActiveSheet()->setCellValue("J$x", $drugs_record['entity_name']);
                    $spreadsheet->getActiveSheet()->setCellValue("K$x", $drugs_record['package_name']);
                    $spreadsheet->getActiveSheet()->setCellValue("L$x", convert_db_to_display_date($drugs_record['iniated_date']));
                    $spreadsheet->getActiveSheet()->setCellValue("M$x", convert_db_to_display_date($drugs_record['due_date']));
                    $spreadsheet->getActiveSheet()->setCellValue("N$x", $drugs_record['tat_status']);
                    $spreadsheet->getActiveSheet()->setCellValue("O$x", $drugs_record['executive_name']);
                }
            }

            $x++;
        }
        // Rename worksheet
        $spreadsheet->getActiveSheet()->setTitle('Activity Record');

        $spreadsheet->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=Activity Records.xlsx");
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Excel2007');

        define('UPLOAD_FILE_DATE_FORMAT2', "d-m-Y_H-i-s");

        $file_name = "Candidates_Records_" . DATE(UPLOAD_FILE_DATE_FORMAT2) . ".xls";

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Excel2007');
        ob_start();
        $writer->save($file_upload_path . "/" . $file_name);
        ob_end_clean();

        $this->update_request_data(array('file_name' => $file_name, 'folder_generated_status' => 1, 'folder_name' => $file_upload_path), array('id' => $parameters_array));
        $date_range = $activity_from . ' to ' . $activity_to;

        $this->update_request_schedular_list(array('report_id' => $parameters_array, 'date_range' => $date_range, 'file_name' => $file_name, 'run_status' => 1, 'last_run_on' => date(DB_DATE_FORMAT)), array('id' => '1'));

    }

    public function generate_export_wip_insuff_data($parameters_array = null)
    {
        ini_set('memory_limit', '-1');
        $this->load->model('report_generated_user');

        $file_upload_path = SITE_BASE_PATH . UPLOAD_FOLDER . 'bulk_reports';

        if (!folder_exist($file_upload_path)) {
            mkdir($file_upload_path, 0777);
        }
        if (!folder_exist($file_upload_path)) {
            mkdir($file_upload_path, 0777);
        } else if (!is_writable($file_upload_path)) {
            mkdir($file_upload_path, 0777);
        }

        $all_records = $this->report_generated_user->select_overall_wip_insuff_cases();

        require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
        // Create new Spreadsheet object
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        // Set document properties
        $spreadsheet->getProperties()->setCreator(CRMNAME)
            ->setLastModifiedBy(CRMNAME)
            ->setTitle(CRMNAME)
            ->setSubject('Activity Records')
            ->setDescription('Activity Records');
        // add style to the header
        $styleArray = array(
            'font' => array(
                'bold' => true,
            ),
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
                'top' => array(
                    'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ),
            ),
            'fill' => array(
                'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                'rotation' => 90,
                'startcolor' => array(
                    'argb' => 'FFA0A0A0',
                ),
                'endcolor' => array(
                    'argb' => 'FFFFFFFF',
                ),
            ),
        );
        $spreadsheet->getActiveSheet()->getStyle('A1:N1')->applyFromArray($styleArray);
        // auto fit column to content
        foreach (range('A', 'N') as $columnID) {
            $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                ->setWidth(20);
        }

        // set the names of header cells
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue("A1", 'Components')
            ->setCellValue("B1", 'Ref No.')
            ->setCellValue("C1", 'Client Ref No.')
            ->setCellValue("D1", 'Candidate Name')
            ->setCellValue("E1", 'Current Status')
            ->setCellValue("F1", 'Client Name')
            ->setCellValue("G1", 'Entity')
            ->setCellValue("H1", 'Package')
            ->setCellValue("I1", 'Comp Init date')
            ->setCellValue("J1", 'Due date')
            ->setCellValue("K1", 'TAT Status')
            ->setCellValue("L1", 'Assigned To')
            ->setCellValue("M1", REFNO)
            ->setCellValue("N1", 'Details');
        // Add some data
        $x = 2;

        foreach ($all_records as $all_record) {

            $spreadsheet->setActiveSheetIndex(0);
            $spreadsheet->getActiveSheet()->setCellValue("A$x", $all_record['component_name']);
            $spreadsheet->getActiveSheet()->setCellValue("B$x", $all_record['component_id']);
            $spreadsheet->getActiveSheet()->setCellValue("C$x", ucwords($all_record['ClientRefNumber']));
            $spreadsheet->getActiveSheet()->setCellValue("D$x", ucwords($all_record['CandidateName']));
            $spreadsheet->getActiveSheet()->setCellValue("E$x", $all_record['status_value']);
            $spreadsheet->getActiveSheet()->setCellValue("F$x", $all_record['clientname']);
            $spreadsheet->getActiveSheet()->setCellValue("G$x", $all_record['entity_name']);
            $spreadsheet->getActiveSheet()->setCellValue("H$x", $all_record['package_name']);

            $spreadsheet->getActiveSheet()->setCellValue("I$x", convert_db_to_display_date($all_record['iniated_date']));
            $spreadsheet->getActiveSheet()->setCellValue("J$x", convert_db_to_display_date($all_record['due_date']));
            $spreadsheet->getActiveSheet()->setCellValue("K$x", strtoupper($all_record['tat_status']));
            $spreadsheet->getActiveSheet()->setCellValue("L$x", $all_record['executive_name']);
            $spreadsheet->getActiveSheet()->setCellValue("M$x", $all_record['cmp_ref_no']);
            $spreadsheet->getActiveSheet()->setCellValue("N$x", $all_record['Details']);

            $x++;
        }
        // Rename worksheet
        $spreadsheet->getActiveSheet()->setTitle('WIP Insuff Record');

        $spreadsheet->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=WIP Insuff Records.xlsx");
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Excel2007');

        $file_name = "WIP_Insuff_Records_" . DATE(UPLOAD_FILE_DATE_FORMAT) . ".xls";

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Excel2007');
        ob_start();
        $writer->save($file_upload_path . "/" . $file_name);
        ob_end_clean();

        $this->update_request_data(array('file_name' => $file_name, 'folder_generated_status' => 1, 'folder_name' => $file_upload_path), array('id' => $parameters_array));

        $this->update_request_schedular_list(array('report_id' => $parameters_array, 'file_name' => $file_name, 'run_status' => 1, 'last_run_on' => date(DB_DATE_FORMAT)), array('id' => '5'));

    }

    public function generate_excel_axis_report($parameters_array = null)
    {

        $this->load->model('report_generated_user');

        $frm_details['activity_from_axis'] = '01-01-2009';
        $frm_details['activity_to_axis'] = date("d-m-Y");

        $file_upload_path = SITE_BASE_PATH . UPLOAD_FOLDER . 'bulk_reports';

        if (!folder_exist($file_upload_path)) {
            mkdir($file_upload_path, 0777);
        }
        if (!folder_exist($file_upload_path)) {
            mkdir($file_upload_path, 0777);
        } else if (!is_writable($file_upload_path)) {
            mkdir($file_upload_path, 0777);
        }

        $where_arry = array('candidates_info.caserecddate >' => convert_display_to_db_date($frm_details['activity_from_axis']), 'candidates_info.caserecddate <=' => convert_display_to_db_date($frm_details['activity_to_axis']));

        $all_records = $this->report_generated_user->get_candidate_record_for_axis($where_arry);

        require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
        // Create new Spreadsheet object
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        // Set document properties
        $spreadsheet->getProperties()->setCreator(CRMNAME)
            ->setLastModifiedBy(CRMNAME)
            ->setTitle(CRMNAME)
            ->setSubject('Candidates records')
            ->setDescription('candidates records with their status');
        // add style to the header
        $styleArray = array(
            'font' => array(
                'bold' => true,
            ),
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
                'top' => array(
                    'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ),
            ),
            'fill' => array(
                'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                'rotation' => 90,
                'startcolor' => array(
                    'argb' => 'FFA0A0A0',
                ),
                'endcolor' => array(
                    'argb' => 'FFFFFFFF',
                ),
            ),
        );
        $spreadsheet->getActiveSheet()->getStyle('A1:AC1')->applyFromArray($styleArray);
        // auto fit column to content
        foreach (range('A', 'AC') as $columnID) {
            $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                ->setWidth(20);
        }

        // set the names of header cells
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue("A1", 'Agency Name')
            ->setCellValue("B1", 'Handover Date')
            ->setCellValue("C1", 'Employee Code')
            ->setCellValue("D1", 'Employee Name')
            ->setCellValue("E1", 'Address Status')
            ->setCellValue("F1", 'Remark (including Insufficiency Details / Discrepancy Details)')
            ->setCellValue("G1", 'Date of insufficiency / Major Discrepancies raised by Vendor')
            ->setCellValue("H1", 'Insufficiency 2')
            ->setCellValue("I1", 'Date of Insufficeincy 2')
            ->setCellValue("J1", 'Date of Address check close')
            ->setCellValue("K1", 'Employment Status')
            ->setCellValue("L1", 'Closure Remark')
            ->setCellValue("M1", 'Insufficiency Remark 1')
            ->setCellValue("N1", 'Insuff Raised Date 1')
            ->setCellValue("O1", 'Insufficiency Remark 2')
            ->setCellValue("P1", 'Insuff Raised Date 2')
            ->setCellValue("Q1", 'Discrepancy Remark 1(if any)')
            ->setCellValue("R1", 'Discrepancy Remark 2(if any)')
            ->setCellValue("S1", 'Date of Employment check close')
            ->setCellValue("T1", 'Verifiers Email ID')
            ->setCellValue("U1", 'Address')
            ->setCellValue("V1", 'Name of Company')
            ->setCellValue("W1", 'Experience')
            ->setCellValue("X1", 'Add Clear Date')
            ->setCellValue("Y1", 'Emp Clear Date')
            ->setCellValue("Z1", 'Branch')
            ->setCellValue("AA1", 'Department')
            ->setCellValue("AB1", 'Add Insuff Raised Date')
            ->setCellValue("AC1", 'Emp Insuff Raised Date');
        // Add some data
        $x = 2;

        foreach ($all_records as $all_record) {

            $components = explode(',', $all_record['component_id']);

            $rename_status = array('New Check' => 'WIP', 'Clear' => 'Positive', 'Insufficiency Cleared' => 'WIP', 'First QC Reject' => 'WIP', 'Follow Up' => 'WIP', 'Final QC Reject' => 'WIP', 'Major Discrepancy' => 'Discrepancy');

            if (in_array('addrver', $components)) {
                $result1 = $this->report_generated_user->get_addres_ver_status(array('candidates_info.id' => $all_record['id']));

                if (!empty($result1)) {

                    $result = $this->report_generated_user->get_address_insuff_details(array('addrver.id' => $result1[0]['id']));

                    $result_discrepancy = $this->report_generated_user->get_address_discrepancy_details(array('addrver.id' => $result1[0]['id']));

                    if (array_key_exists($result1[0]['status_value'], $rename_status)) {
                        $all_record['addrver'] = $rename_status[$result1[0]['status_value']];
                    } else {
                        $all_record['addrver'] = ($result1[0]['status_value'] != "") ? $result1[0]['status_value'] : 'WIP';
                    }

                    if ($result1[0]['closuredate'] === null) {
                        $all_record['addrver_closure_date'] = "WIP";
                    } else if ($result1[0]['closuredate'] != "") {
                        $all_record['addrver_closure_date'] = date('d-M-Y', strtotime($result1[0]['closuredate']));
                        $all_record['addrver_closure_date'] = ($all_record['addrver_closure_date']) != '01-Jan-1970' ? $all_record['addrver_closure_date'] : 'NA';

                    } else {
                        $all_record['addrver_closure_date'] = "NA";
                    }

                    $all_record['address'] = $result1[0]['address'] ? $result1[0]['address'] : 'NA';

                    $all_record['addrver_insuff_remarks_1'] = ($result[0]['insuff_raise_remark'] != "") ? $result[0]['insuff_raise_remark'] : 'NA';

                    $all_record['addrver_insuff_raised_date_1'] = (date('d-M-Y', strtotime($result[0]['insuff_raised_date'])) != '01-Jan-1970') ? date('d-M-Y', strtotime($result[0]['insuff_raised_date'])) : 'NA';

                    $addrver_insuff_raised_date_array = array_slice($result, 1);

                    if (!empty($addrver_insuff_raised_date_array)) {
                        foreach ($addrver_insuff_raised_date_array as $addrver_insuff_raised_date) {
                            $addrver_insuff_raised_2[] = (date('d-M-Y', strtotime($addrver_insuff_raised_date['insuff_raised_date'])) != '01-Jan-1970') ? date('d-M-Y', strtotime($addrver_insuff_raised_date['insuff_raised_date'])) : 'NA';
                        }

                        if ($all_record['addrver'] == "Insufficiency") {
                            $all_record['addrver'] = "Insufficiency II";
                        } else {
                            $all_record['addrver'] = $all_record['addrver'];
                        }

                        $all_record['addrver_insuff_remarks_2'] = (end($result)['insuff_raise_remark'] != "") ? end($result)['insuff_raise_remark'] : 'NA';
                    } else {
                        $addrver_insuff_raised_2[] = "NA";
                        $all_record['addrver_insuff_remarks_2'] = "NA";
                    }

                    $all_record['addrver_insuff_raised_date_2'] = implode(" & ", $addrver_insuff_raised_2);
                    unset($addrver_insuff_raised_2);

                    foreach ($result as $results) {
                        $addrver_insuffraiseddate_2[] = (date('d-M-Y', strtotime($results['insuff_raised_date'])) != '01-Jan-1970') ? date('d-M-Y', strtotime($results['insuff_raised_date'])) : 'NA';
                    }

                    $all_record['addrver_insuffraiseddate_2'] = implode(" & ", $addrver_insuffraiseddate_2);
                    unset($addrver_insuffraiseddate_2);

                    foreach ($result as $results) {
                        $addrver_insuffcleardate_2[] = (date('d-M-Y', strtotime($results['insuff_clear_date'])) != '01-Jan-1970') ? date('d-M-Y', strtotime($results['insuff_clear_date'])) : 'NA';
                    }

                    $all_record['addrver_insuffcleardate_2'] = implode(" & ", $addrver_insuffcleardate_2);
                    unset($addrver_insuffcleardate_2);

                    $addrver_discrepancy_array = array_slice($result_discrepancy, 1);

                    if (!empty($addrver_discrepancy_array)) {

                        if ($all_record['addrver'] == "Discrepancy") {
                            $all_record['addrver'] = "Discrepancy II";
                        } else {
                            $all_record['addrver'] = $all_record['addrver'];
                        }
                    }

                } else {
                    $all_record['addrver'] = "NA";
                    $all_record['addrver_insuff_remarks_1'] = "NA";
                    $all_record['addrver_insuff_raised_date_1'] = "NA";
                    $all_record['addrver_insuff_remarks_2'] = "NA";
                    $all_record['addrver_insuff_raised_date_2'] = "NA";
                    $all_record['addrver_closure_date'] = "NA";
                    $all_record['addrver_insuffcleardate_2'] = "NA";
                    $all_record['addrver_insuffraiseddate_2'] = "NA";
                }
            } else {
                $all_record['addrver'] = "NA";
                $all_record['addrver_insuff_remarks_1'] = "NA";
                $all_record['addrver_insuff_raised_date_1'] = "NA";
                $all_record['addrver_insuff_remarks_2'] = "NA";
                $all_record['addrver_insuff_raised_date_2'] = "NA";
                $all_record['addrver_closure_date'] = "NA";
                $all_record['addrver_insuffcleardate_2'] = "NA";
                $all_record['addrver_insuffraiseddate_2'] = "NA";
            }

            if (in_array('empver', $components)) {

                $result1 = $this->report_generated_user->get_empver_ver_status(array('candidates_info.id' => $all_record['id']));

                if (!empty($result1)) {

                    $result = $this->report_generated_user->get_employment_insuff_details(array('empver.id' => $result1[0]['id']));

                    $result_discrepancy = $this->report_generated_user->get_employment_discrepancy_details(array('empver.id' => $result1[0]['id']));

                    if (array_key_exists($result1[0]['status_value'], $rename_status)) {
                        $all_record['empver'] = $rename_status[$result1[0]['status_value']];
                    } else {
                        $all_record['empver'] = ($result1[0]['status_value'] != "") ? $result1[0]['status_value'] : 'WIP';

                    }

                    if ($result1[0]['closuredate'] === null) {
                        $all_record['empver_closure_date'] = "WIP";
                    } else if ($result1[0]['closuredate'] != "") {
                        $all_record['empver_closure_date'] = date('d-M-Y', strtotime($result1[0]['closuredate']));

                        $all_record['empver_closure_date'] = ($all_record['empver_closure_date']) != '01-Jan-1970' ? $all_record['empver_closure_date'] : 'NA';
                    } else {
                        $all_record['empver_closure_date'] = "NA";
                    }

                    $all_record['coname'] = $result1[0]['coname'] ? $result1[0]['coname'] : 'NA';

                    $all_record['empres_remarks_'] = $result1[0]['remarks'] ? $result1[0]['remarks'] : 'NA';

                    $all_record['empver_insuff_remarks_1'] = ($result[0]['insuff_raise_remark'] != "") ? $result[0]['insuff_raise_remark'] : 'NA';

                    $all_record['empver_insuff_raised_date_1'] = (date('d-M-Y', strtotime($result[0]['insuff_raised_date'])) != '01-Jan-1970') ? date('d-M-Y', strtotime($result[0]['insuff_raised_date'])) : 'NA';

                    $all_record['verifiers_email_id'] = ($result1[0]['verifiers_email_id'] != "") ? $result1[0]['verifiers_email_id'] : 'NA';

                    $empver_insuff_raised_date_array = array_slice($result, 1);

                    if (!empty($empver_insuff_raised_date_array)) {
                        foreach ($empver_insuff_raised_date_array as $empver_insuff_raised_date) {
                            $empver_insuff_raised_2[] = (date('d-M-Y', strtotime($empver_insuff_raised_date['insuff_raised_date'])) != '01-Jan-1970') ? date('d-M-Y', strtotime($empver_insuff_raised_date['insuff_raised_date'])) : 'NA';
                        }

                        if ($all_record['empver'] == "Insufficiency") {
                            $all_record['empver'] = "Insufficiency II";
                        } else {
                            $all_record['empver'] = $all_record['empver'];
                        }

                        $all_record['empver_insuff_remarks_2'] = (end($result)['insuff_raise_remark'] != "") ? end($result)['insuff_raise_remark'] : 'NA';

                    } else {
                        $empver_insuff_raised_2[] = "NA";

                        $all_record['empver_insuff_remarks_2'] = 'NA';

                    }

                    $all_record['empver_insuff_raised_date_2'] = implode(" & ", $empver_insuff_raised_2);
                    unset($empver_insuff_raised_2);

                    foreach ($result as $results) {
                        $empver_insuffraiseddate_2[] = (date('d-M-Y', strtotime($results['insuff_raised_date'])) != '01-Jan-1970') ? date('d-M-Y', strtotime($results['insuff_raised_date'])) : 'NA';
                    }

                    $all_record['empver_insuffraiseddate_2'] = implode(" & ", $empver_insuffraiseddate_2);
                    unset($empver_insuffraiseddate_2);

                    foreach ($result as $results) {
                        $empver_insuffcleardate_2[] = (date('d-M-Y', strtotime($results['insuff_clear_date'])) != '01-Jan-1970') ? date('d-M-Y', strtotime($results['insuff_clear_date'])) : 'NA';
                    }

                    $all_record['empver_insuffcleardate_2'] = implode(" & ", $empver_insuffcleardate_2);
                    unset($empver_insuffcleardate_2);

                    $all_record['verifiers_email_id'] = ($result1[0]['verifiers_email_id'] != "") ? $result1[0]['verifiers_email_id'] : 'NA';

                    if (!empty($result_discrepancy)) {

                        $all_record['empver_discrepancy_remark_1'] = ($result_discrepancy[0]['remarks'] != "") ? $result_discrepancy[0]['remarks'] : "NA";
                    } else {
                        $all_record['empver_discrepancy_remark_1'] = "NA";

                    }

                    $empver_discrepancy_array = array_slice($result_discrepancy, 1);

                    if (!empty($empver_discrepancy_array)) {
                        $all_record['empver_discrepancy_remark_2'] = (end($result_discrepancy)['remarks'] != "") ? end($result_discrepancy)['remarks'] : 'NA';

                        if ($all_record['empver'] == "Discrepancy") {
                            $all_record['empver'] = "Discrepancy II";
                        } else {
                            $all_record['empver'] = $all_record['empver'];
                        }
                    } else {
                        $all_record['empver_discrepancy_remark_2'] = 'NA';
                    }
                } else {
                    $all_record['empver'] = "NA";
                    $all_record['empres_remarks_'] = "NA";
                    $all_record['empver_insuff_remarks_1'] = "NA";
                    $all_record['empver_insuff_raised_date_1'] = "NA";
                    $all_record['empver_insuff_remarks_2'] = "NA";
                    $all_record['empver_insuff_raised_date_2'] = "NA";
                    $all_record['empver_discrepancy_remark_1'] = "NA";
                    $all_record['empver_discrepancy_remark_2'] = "NA";
                    $all_record['empver_closure_date'] = "NA";
                    $all_record['verifiers_email_id'] = "NA";
                    // $all_record['addrver_insuffcleardate_2'] = "NA";
                    $all_record['empver_insuffcleardate_2'] = "NA";
                    $all_record['empver_insuffraiseddate_2'] = "NA";
                    $all_record['coname'] = "NA";

                }

            } else {
                $all_record['empver'] = "NA";
                $all_record['empres_remarks_'] = "NA";
                $all_record['empver_insuff_remarks_1'] = "NA";
                $all_record['empver_insuff_raised_date_1'] = "NA";
                $all_record['empver_insuff_remarks_2'] = "NA";
                $all_record['empver_insuff_raised_date_2'] = "NA";
                $all_record['empver_discrepancy_remark_1'] = "NA";
                $all_record['empver_discrepancy_remark_2'] = "NA";
                $all_record['empver_closure_date'] = "NA";
                $all_record['verifiers_email_id'] = "NA";
                //  $all_record['addrver_insuffcleardate_2'] = "NA";
                $all_record['empver_insuffcleardate_2'] = "NA";
                $all_record['empver_insuffraiseddate_2'] = "NA";
                $all_record['coname'] = "NA";
            }

            $clientName = $all_record['clientname'];

            $caserecddate = (date('d-M-Y', strtotime($all_record['caserecddate'])) != '01-Jan-1970') ? date('d-M-Y', strtotime($all_record['caserecddate'])) : 'NA';

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A$x", CRMNAME)
                ->setCellValue("B$x", $caserecddate)
                ->setCellValue("C$x", $all_record['ClientRefNumber'])
                ->setCellValue("D$x", $all_record['CandidateName'])
                ->setCellValue("E$x", $all_record['addrver'])
                ->setCellValue("F$x", $all_record['addrver_insuff_remarks_1'])
                ->setCellValue("G$x", $all_record['addrver_insuff_raised_date_1'])
                ->setCellValue("H$x", $all_record['addrver_insuff_remarks_2'])
                ->setCellValue("I$x", $all_record['addrver_insuff_raised_date_2'])
                ->setCellValue("J$x", $all_record['addrver_closure_date'])
                ->setCellValue("K$x", $all_record['empver'])
                ->setCellValue("L$x", $all_record['empres_remarks_'])
                ->setCellValue("M$x", $all_record['empver_insuff_remarks_1'])
                ->setCellValue("N$x", $all_record['empver_insuff_raised_date_1'])
                ->setCellValue("O$x", $all_record['empver_insuff_remarks_2'])
                ->setCellValue("P$x", $all_record['empver_insuff_raised_date_2'])
                ->setCellValue("Q$x", $all_record['empver_discrepancy_remark_1'])
                ->setCellValue("R$x", $all_record['empver_discrepancy_remark_2'])
                ->setCellValue("S$x", $all_record['empver_closure_date'])
                ->setCellValue("T$x", $all_record['verifiers_email_id'])
                ->setCellValue("U$x", $all_record['address'])
                ->setCellValue("V$x", $all_record['coname'])
                ->setCellValue("W$x", $all_record['branch_name'])
                ->setCellValue("X$x", $all_record['addrver_insuffcleardate_2'])
                ->setCellValue("Y$x", $all_record['empver_insuffcleardate_2'])
                ->setCellValue("Z$x", $all_record['Location'])
                ->setCellValue("AA$x", $all_record['Department'])
                ->setCellValue("AB$x", $all_record['addrver_insuffraiseddate_2'])
                ->setCellValue("AC$x", $all_record['empver_insuffraiseddate_2']);

            $x++;
        }
        // Rename worksheet
        $spreadsheet->getActiveSheet()->setTitle('Candidate Records');

        $spreadsheet->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=Candidates Records of .xlsx");
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Excel2007');

        $file_name = "Candidates_Records_Axis_Securities_" . DATE(UPLOAD_FILE_DATE_FORMAT) . ".xls";

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Excel2007');
        ob_start();
        $writer->save($file_upload_path . "/" . $file_name);
        ob_end_clean();

        $this->update_request_data(array('file_name' => $file_name, 'folder_generated_status' => 1, 'folder_name' => $file_upload_path), array('id' => $parameters_array));
        $date_range = $frm_details['activity_from_axis'] . ' to ' . $frm_details['activity_to_axis'];

        $this->update_request_schedular_list(array('report_id' => $parameters_array, 'date_range' => $date_range, 'file_name' => $file_name, 'run_status' => 1, 'last_run_on' => date(DB_DATE_FORMAT)), array('id' => '2'));

    }

    public function generate_excel_axis_report_new($parameters_array = null)
    {
        ini_set('memory_limit', '-1');

        $this->load->model('report_generated_user');

        $frm_details['activity_from_axis'] = '01-01-2009';
        $frm_details['activity_to_axis'] = date("d-m-Y");

        $file_upload_path = SITE_BASE_PATH . UPLOAD_FOLDER . 'bulk_reports';

        if (!folder_exist($file_upload_path)) {
            mkdir($file_upload_path, 0777);
        }
        if (!folder_exist($file_upload_path)) {
            mkdir($file_upload_path, 0777);
        } else if (!is_writable($file_upload_path)) {
            mkdir($file_upload_path, 0777);
        }

        $where_arry = array('candidates_info.caserecddate >' => convert_display_to_db_date($frm_details['activity_from_axis']), 'candidates_info.caserecddate <=' => convert_display_to_db_date($frm_details['activity_to_axis']));

        $all_records = $this->report_generated_user->get_candidate_record_for_axis_new($where_arry);

        require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
        // Create new Spreadsheet object
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        // Set document properties
        $spreadsheet->getProperties()->setCreator(CRMNAME)
            ->setLastModifiedBy(CRMNAME)
            ->setTitle(CRMNAME)
            ->setSubject('Candidates records')
            ->setDescription('candidates records with their status');
        // add style to the header
        $styleArray = array(
            'font' => array(
                'bold' => true,
            ),
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
                'top' => array(
                    'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ),
            ),
            'fill' => array(
                'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                'rotation' => 90,
                'startcolor' => array(
                    'argb' => 'FFA0A0A0',
                ),
                'endcolor' => array(
                    'argb' => 'FFFFFFFF',
                ),
            ),
        );
        $spreadsheet->getActiveSheet()->getStyle('A1:AF1')->applyFromArray($styleArray);
        // auto fit column to content
        foreach (range('A', 'AF') as $columnID) {
            $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                ->setWidth(20);
        }

        // set the names of header cells
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue("A1", 'Employee Code')
            ->setCellValue("B1", 'Associate Name')
            ->setCellValue("C1", 'Address Status')
            ->setCellValue("D1", 'Present Address')
            ->setCellValue("E1", 'City')
            ->setCellValue("F1", 'State')
            ->setCellValue("G1", 'Pincode')
            ->setCellValue("H1", 'Employment Status')
            ->setCellValue("I1", 'Experience status')
            ->setCellValue("J1", 'Last Company /Employer Name')
            ->setCellValue("K1", 'Company Location')
            ->setCellValue("L1", 'Company Address')
            ->setCellValue("M1", 'Designation (At the time of leaving)')
            ->setCellValue("N1", 'Total Experience')
            ->setCellValue("O1", 'Period From')
            ->setCellValue("P1", 'Period To')
            ->setCellValue("Q1", 'Last Drawn CTC')
            ->setCellValue("R1", 'Reference Name')
            ->setCellValue("S1", 'Reference Phone Number')
            ->setCellValue("T1", 'Reason for Separation')
            ->setCellValue("U1", 'Employment Type')
            ->setCellValue("V1", 'Previous Employee ID')
            ->setCellValue("W1", 'Experience')
            ->setCellValue("X1", 'Reporting Manager Name')
            ->setCellValue("Y1", 'Reporting Manager Designation')
            ->setCellValue("Z1", 'Reporting Manager Contact Number')
            ->setCellValue("AA1", 'Reporting Manager Mail ID')
            ->setCellValue("AB1", 'HR Name')
            ->setCellValue("AC1", 'HR Designation')
            ->setCellValue("AD1", 'HR Mail ID')
            ->setCellValue("AE1", 'HR Contact No')
            ->setCellValue("AF1", 'Remark');

        // Add some data
        $x = 2;

        foreach ($all_records as $all_record) {

            $components = explode(',', $all_record['component_id']);

            $rename_status = array('New Check' => 'WIP', 'Clear' => 'Positive', 'Insufficiency Cleared' => 'WIP', 'First QC Reject' => 'WIP', 'Follow Up' => 'WIP', 'Final QC Reject' => 'WIP', 'Major Discrepancy' => 'Discrepancy');

            if (in_array('addrver', $components)) {
                $result1 = $this->report_generated_user->get_addres_ver_status_new(array('candidates_info.id' => $all_record['id']));

                if (!empty($result1)) {

                    if (array_key_exists($result1[0]['status_value'], $rename_status)) {
                        $all_record['addrver'] = $rename_status[$result1[0]['status_value']];
                    } else {
                        $all_record['addrver'] = ($result1[0]['status_value'] != "") ? $result1[0]['status_value'] : 'WIP';
                    }

                    $all_record['address'] = $result1[0]['address'] ? $result1[0]['address'] : 'NA';

                    $all_record['city'] = $result1[0]['city'] ? $result1[0]['city'] : 'NA';

                    $all_record['state'] = $result1[0]['state'] ? $result1[0]['state'] : 'NA';

                    $all_record['pincode'] = $result1[0]['pincode'] ? $result1[0]['pincode'] : 'NA';

                } else {
                    $all_record['addrver'] = "NA";
                    $all_record['address'] = "NA";
                    $all_record['city'] = "NA";
                    $all_record['state'] = "NA";
                    $all_record['pincode'] = "NA";

                }
            } else {
                $all_record['addrver'] = "NA";
                $all_record['address'] = "NA";
                $all_record['city'] = "NA";
                $all_record['state'] = "NA";
                $all_record['pincode'] = "NA";
            }

            if (in_array('empver', $components)) {

                $result1 = $this->report_generated_user->get_empver_ver_status_new(array('candidates_info.id' => $all_record['id']));

                if (!empty($result1)) {

                    if (array_key_exists($result1[0]['status_value'], $rename_status)) {
                        $all_record['empver'] = $rename_status[$result1[0]['status_value']];
                    } else {
                        $all_record['empver'] = ($result1[0]['status_value'] != "") ? $result1[0]['status_value'] : 'WIP';

                    }

                    $all_record['coname'] = $result1[0]['coname'] ? $result1[0]['coname'] : 'NA';

                    $all_record['remarks'] = $result1[0]['remarks'] ? $result1[0]['remarks'] : 'NA';

                    $all_record['company_address'] = $result1[0]['company_address'] ? $result1[0]['company_address'] : 'NA';

                    $all_record['company_city'] = $result1[0]['company_city'] ? $result1[0]['company_city'] : 'NA';

                    $all_record['designation'] = $result1[0]['designation'] ? $result1[0]['designation'] : 'NA';

                    $all_record['year_of_experience'] = $result1[0]['year_of_experience'] ? $result1[0]['year_of_experience'] : 'NA';

                    $all_record['empfrom'] = $result1[0]['empfrom'] ? $result1[0]['empfrom'] : 'NA';

                    $all_record['empto'] = $result1[0]['empto'] ? $result1[0]['empto'] : 'NA';

                    $all_record['remuneration'] = $result1[0]['remuneration'] ? $result1[0]['remuneration'] : 'NA';

                    $all_record['employment_reference_name'] = $result1[0]['employment_reference_name'] ? $result1[0]['employment_reference_name'] : 'NA';

                    $all_record['employment_reference_no'] = $result1[0]['employment_reference_no'] ? $result1[0]['employment_reference_no'] : 'NA';

                    $all_record['reasonforleaving'] = $result1[0]['reasonforleaving'] ? $result1[0]['reasonforleaving'] : 'NA';

                    $all_record['employment_type'] = $result1[0]['employment_type'] ? $result1[0]['employment_type'] : 'NA';

                    $all_record['empid'] = $result1[0]['empid'] ? $result1[0]['empid'] : 'NA';

                    $all_record['verfname'] = $result1[0]['verfname'] ? $result1[0]['verfname'] : 'NA';

                    $all_record['verfdesgn'] = $result1[0]['verfdesgn'] ? $result1[0]['verfdesgn'] : 'NA';

                    $all_record['verifiers_contact_no'] = $result1[0]['verifiers_contact_no'] ? $result1[0]['verifiers_contact_no'] : 'NA';

                    $all_record['verifiers_email_id'] = ($result1[0]['verifiers_email_id'] != "") ? $result1[0]['verifiers_email_id'] : 'NA';

                    $all_record['r_manager_name'] = $result1[0]['r_manager_name'] ? $result1[0]['r_manager_name'] : 'NA';

                    $all_record['r_manager_no'] = $result1[0]['r_manager_no'] ? $result1[0]['r_manager_no'] : 'NA';

                    $all_record['r_manager_designation'] = $result1[0]['r_manager_designation'] ? $result1[0]['r_manager_designation'] : 'NA';

                    $all_record['r_manager_email'] = $result1[0]['r_manager_email'] ? $result1[0]['r_manager_email'] : 'NA';

                } else {
                    $all_record['empver'] = "NA";
                    $all_record['coname'] = "NA";
                    $all_record['company_address'] = "NA";
                    $all_record['company_city'] = "NA";
                    $all_record['remarks'] = "NA";
                    $all_record['designation'] = "NA";
                    $all_record['year_of_experience'] = "NA";
                    $all_record['empfrom'] = "NA";
                    $all_record['empto'] = "NA";
                    $all_record['remuneration'] = "NA";
                    $all_record['employment_reference_name'] = "NA";
                    $all_record['employment_reference_no'] = "NA";
                    $all_record['reasonforleaving'] = "NA";
                    $all_record['employment_type'] = "NA";
                    $all_record['employment_reference_no'] = "NA";
                    $all_record['reasonforleaving'] = "NA";
                    $all_record['employment_type'] = "NA";
                    $all_record['empid'] = "NA";
                    $all_record['verfname'] = "NA";
                    $all_record['verfdesgn'] = "NA";
                    $all_record['verifiers_contact_no'] = "NA";
                    $all_record['verifiers_email_id'] = "NA";
                    $all_record['r_manager_name'] = "NA";
                    $all_record['r_manager_no'] = "NA";
                    $all_record['r_manager_designation'] = "NA";
                    $all_record['r_manager_email'] = "NA";

                }

            } else {
                $all_record['empver'] = "NA";
                $all_record['coname'] = "NA";
                $all_record['company_address'] = "NA";
                $all_record['company_city'] = "NA";
                $all_record['remarks'] = "NA";
                $all_record['designation'] = "NA";
                $all_record['year_of_experience'] = "NA";
                $all_record['empfrom'] = "NA";
                $all_record['empto'] = "NA";
                $all_record['remuneration'] = "NA";
                $all_record['employment_reference_name'] = "NA";
                $all_record['employment_reference_no'] = "NA";
                $all_record['reasonforleaving'] = "NA";
                $all_record['employment_type'] = "NA";
                $all_record['employment_reference_no'] = "NA";
                $all_record['reasonforleaving'] = "NA";
                $all_record['employment_type'] = "NA";
                $all_record['empid'] = "NA";
                $all_record['verfname'] = "NA";
                $all_record['verfdesgn'] = "NA";
                $all_record['verifiers_contact_no'] = "NA";
                $all_record['verifiers_email_id'] = "NA";
                $all_record['r_manager_name'] = "NA";
                $all_record['r_manager_no'] = "NA";
                $all_record['r_manager_designation'] = "NA";
                $all_record['r_manager_email'] = "NA";
            }

            $all_record['ClientRefNumber'] = $all_record['ClientRefNumber'] ? $all_record['ClientRefNumber'] : 'NA';

            $all_record['CandidateName'] = $all_record['CandidateName'] ? $all_record['CandidateName'] : 'NA';

            $all_record['branch_name'] = $all_record['branch_name'] ? $all_record['branch_name'] : 'NA';

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A$x", $all_record['ClientRefNumber'])
                ->setCellValue("B$x", $all_record['CandidateName'])
                ->setCellValue("C$x", $all_record['addrver'])
                ->setCellValue("D$x", $all_record['address'])
                ->setCellValue("E$x", $all_record['city'])
                ->setCellValue("F$x", $all_record['state'])
                ->setCellValue("G$x", $all_record['pincode'])
                ->setCellValue("H$x", $all_record['empver'])
                ->setCellValue("I$x", $all_record['branch_name'])
                ->setCellValue("J$x", $all_record['coname'])
                ->setCellValue("K$x", $all_record['company_city'])
                ->setCellValue("L$x", $all_record['company_address'])
                ->setCellValue("M$x", $all_record['designation'])
                ->setCellValue("N$x", $all_record['year_of_experience'])
                ->setCellValue("O$x", $all_record['empfrom'])
                ->setCellValue("P$x", $all_record['empto'])
                ->setCellValue("Q$x", $all_record['remuneration'])
                ->setCellValue("R$x", $all_record['employment_reference_name'])
                ->setCellValue("S$x", $all_record['employment_reference_no'])
                ->setCellValue("T$x", $all_record['reasonforleaving'])
                ->setCellValue("U$x", $all_record['employment_type'])
                ->setCellValue("V$x", $all_record['empid'])
                ->setCellValue("W$x", $all_record['year_of_experience'])
                ->setCellValue("X$x", $all_record['r_manager_name'])
                ->setCellValue("Y$x", $all_record['r_manager_designation'])
                ->setCellValue("Z$x", $all_record['r_manager_no'])
                ->setCellValue("AA$x", $all_record['r_manager_email'])
                ->setCellValue("AB$x", $all_record['verfname'])
                ->setCellValue("AC$x", $all_record['verfdesgn'])
                ->setCellValue("AD$x", $all_record['verifiers_email_id'])
                ->setCellValue("AE$x", $all_record['verifiers_contact_no'])
                ->setCellValue("AF$x", $all_record['remarks']);

            $x++;
        }
        // Rename worksheet
        $spreadsheet->getActiveSheet()->setTitle('Candidate Records');

        $spreadsheet->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=Candidates Records of .xlsx");
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Excel2007');

        $file_name = "Candidates_Records_Axis_Securities_NEW" . DATE(UPLOAD_FILE_DATE_FORMAT) . ".xls";

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Excel2007');
        ob_start();
        $writer->save($file_upload_path . "/" . $file_name);
        ob_end_clean();

        $this->update_request_data(array('file_name' => $file_name, 'folder_generated_status' => 1, 'folder_name' => $file_upload_path), array('id' => $parameters_array));
        $date_range = $frm_details['activity_from_axis'] . ' to ' . $frm_details['activity_to_axis'];

        $this->update_request_schedular_list(array('report_id' => $parameters_array, 'date_range' => $date_range, 'file_name' => $file_name, 'run_status' => 1, 'last_run_on' => date(DB_DATE_FORMAT)), array('id' => '7'));

    }

    public function generate_excel_axis_ikya_report_new($parameters_array = null)
    {
        ini_set('memory_limit', '-1');

        $this->load->model('report_generated_user');

        $frm_details['activity_from_axis'] = '01-01-2009';
        $frm_details['activity_to_axis'] = date("d-m-Y");

        $file_upload_path = SITE_BASE_PATH . UPLOAD_FOLDER . 'bulk_reports';

        if (!folder_exist($file_upload_path)) {
            mkdir($file_upload_path, 0777);
        }
        if (!folder_exist($file_upload_path)) {
            mkdir($file_upload_path, 0777);
        } else if (!is_writable($file_upload_path)) {
            mkdir($file_upload_path, 0777);
        }

        $where_arry = array('candidates_info.caserecddate >' => convert_display_to_db_date($frm_details['activity_from_axis']), 'candidates_info.caserecddate <=' => convert_display_to_db_date($frm_details['activity_to_axis']));

        $all_records = $this->report_generated_user->get_candidate_record_for_axis_ikya_new($where_arry);

        require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
        // Create new Spreadsheet object
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        // Set document properties
        $spreadsheet->getProperties()->setCreator(CRMNAME)
            ->setLastModifiedBy(CRMNAME)
            ->setTitle(CRMNAME)
            ->setSubject('Candidates records')
            ->setDescription('candidates records with their status');
        // add style to the header
        $styleArray = array(
            'font' => array(
                'bold' => true,
            ),
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
                'top' => array(
                    'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ),
            ),
            'fill' => array(
                'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                'rotation' => 90,
                'startcolor' => array(
                    'argb' => 'FFA0A0A0',
                ),
                'endcolor' => array(
                    'argb' => 'FFFFFFFF',
                ),
            ),
        );
        $spreadsheet->getActiveSheet()->getStyle('A1:AF1')->applyFromArray($styleArray);
        // auto fit column to content
        foreach (range('A', 'AF') as $columnID) {
            $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                ->setWidth(20);
        }

        // set the names of header cells
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue("A1", 'Employee Code')
            ->setCellValue("B1", 'Associate Name')
            ->setCellValue("C1", 'Address Status')
            ->setCellValue("D1", 'Present Address')
            ->setCellValue("E1", 'City')
            ->setCellValue("F1", 'State')
            ->setCellValue("G1", 'Pincode')
            ->setCellValue("H1", 'Employment Status')
            ->setCellValue("I1", 'Experience status')
            ->setCellValue("J1", 'Last Company /Employer Name')
            ->setCellValue("K1", 'Company Location')
            ->setCellValue("L1", 'Company Address')
            ->setCellValue("M1", 'Designation (At the time of leaving)')
            ->setCellValue("N1", 'Total Experience')
            ->setCellValue("O1", 'Period From')
            ->setCellValue("P1", 'Period To')
            ->setCellValue("Q1", 'Last Drawn CTC')
            ->setCellValue("R1", 'Reference Name')
            ->setCellValue("S1", 'Reference Phone Number')
            ->setCellValue("T1", 'Reason for Separation')
            ->setCellValue("U1", 'Employment Type')
            ->setCellValue("V1", 'Previous Employee ID')
            ->setCellValue("W1", 'Experience')
            ->setCellValue("X1", 'Reporting Manager Name')
            ->setCellValue("Y1", 'Reporting Manager Designation')
            ->setCellValue("Z1", 'Reporting Manager Contact Number')
            ->setCellValue("AA1", 'Reporting Manager Mail ID')
            ->setCellValue("AB1", 'HR Name')
            ->setCellValue("AC1", 'HR Designation')
            ->setCellValue("AD1", 'HR Mail ID')
            ->setCellValue("AE1", 'HR Contact No')
            ->setCellValue("AF1", 'Remark');

        // Add some data
        $x = 2;

        foreach ($all_records as $all_record) {

            $components = explode(',', $all_record['component_id']);

            $rename_status = array('New Check' => 'WIP', 'Clear' => 'Positive', 'Insufficiency Cleared' => 'WIP', 'First QC Reject' => 'WIP', 'Follow Up' => 'WIP', 'Final QC Reject' => 'WIP', 'Major Discrepancy' => 'Discrepancy');

            if (in_array('addrver', $components)) {
                $result1 = $this->report_generated_user->get_addres_ver_status_new(array('candidates_info.id' => $all_record['id']));

                if (!empty($result1)) {

                    if (array_key_exists($result1[0]['status_value'], $rename_status)) {
                        $all_record['addrver'] = $rename_status[$result1[0]['status_value']];
                    } else {
                        $all_record['addrver'] = ($result1[0]['status_value'] != "") ? $result1[0]['status_value'] : 'WIP';
                    }

                    $all_record['address'] = $result1[0]['address'] ? $result1[0]['address'] : 'NA';

                    $all_record['city'] = $result1[0]['city'] ? $result1[0]['city'] : 'NA';

                    $all_record['state'] = $result1[0]['state'] ? $result1[0]['state'] : 'NA';

                    $all_record['pincode'] = $result1[0]['pincode'] ? $result1[0]['pincode'] : 'NA';

                } else {
                    $all_record['addrver'] = "NA";
                    $all_record['address'] = "NA";
                    $all_record['city'] = "NA";
                    $all_record['state'] = "NA";
                    $all_record['pincode'] = "NA";

                }
            } else {
                $all_record['addrver'] = "NA";
                $all_record['address'] = "NA";
                $all_record['city'] = "NA";
                $all_record['state'] = "NA";
                $all_record['pincode'] = "NA";
            }

            if (in_array('empver', $components)) {

                $result1 = $this->report_generated_user->get_empver_ver_status_new(array('candidates_info.id' => $all_record['id']));

                if (!empty($result1)) {

                    if (array_key_exists($result1[0]['status_value'], $rename_status)) {
                        $all_record['empver'] = $rename_status[$result1[0]['status_value']];
                    } else {
                        $all_record['empver'] = ($result1[0]['status_value'] != "") ? $result1[0]['status_value'] : 'WIP';

                    }

                    $all_record['coname'] = $result1[0]['coname'] ? $result1[0]['coname'] : 'NA';

                    $all_record['remarks'] = $result1[0]['remarks'] ? $result1[0]['remarks'] : 'NA';

                    $all_record['company_address'] = $result1[0]['company_address'] ? $result1[0]['company_address'] : 'NA';

                    $all_record['company_city'] = $result1[0]['company_city'] ? $result1[0]['company_city'] : 'NA';

                    $all_record['designation'] = $result1[0]['designation'] ? $result1[0]['designation'] : 'NA';

                    $all_record['year_of_experience'] = $result1[0]['year_of_experience'] ? $result1[0]['year_of_experience'] : 'NA';

                    $all_record['empfrom'] = $result1[0]['empfrom'] ? $result1[0]['empfrom'] : 'NA';

                    $all_record['empto'] = $result1[0]['empto'] ? $result1[0]['empto'] : 'NA';

                    $all_record['remuneration'] = $result1[0]['remuneration'] ? $result1[0]['remuneration'] : 'NA';

                    $all_record['employment_reference_name'] = $result1[0]['employment_reference_name'] ? $result1[0]['employment_reference_name'] : 'NA';

                    $all_record['employment_reference_no'] = $result1[0]['employment_reference_no'] ? $result1[0]['employment_reference_no'] : 'NA';

                    $all_record['reasonforleaving'] = $result1[0]['reasonforleaving'] ? $result1[0]['reasonforleaving'] : 'NA';

                    $all_record['employment_type'] = $result1[0]['employment_type'] ? $result1[0]['employment_type'] : 'NA';

                    $all_record['empid'] = $result1[0]['empid'] ? $result1[0]['empid'] : 'NA';

                    $all_record['verfname'] = $result1[0]['verfname'] ? $result1[0]['verfname'] : 'NA';

                    $all_record['verfdesgn'] = $result1[0]['verfdesgn'] ? $result1[0]['verfdesgn'] : 'NA';

                    $all_record['verifiers_contact_no'] = $result1[0]['verifiers_contact_no'] ? $result1[0]['verifiers_contact_no'] : 'NA';

                    $all_record['verifiers_email_id'] = ($result1[0]['verifiers_email_id'] != "") ? $result1[0]['verifiers_email_id'] : 'NA';

                    $all_record['r_manager_name'] = $result1[0]['r_manager_name'] ? $result1[0]['r_manager_name'] : 'NA';

                    $all_record['r_manager_no'] = $result1[0]['r_manager_no'] ? $result1[0]['r_manager_no'] : 'NA';

                    $all_record['r_manager_designation'] = $result1[0]['r_manager_designation'] ? $result1[0]['r_manager_designation'] : 'NA';

                    $all_record['r_manager_email'] = $result1[0]['r_manager_email'] ? $result1[0]['r_manager_email'] : 'NA';

                } else {
                    $all_record['empver'] = "NA";
                    $all_record['coname'] = "NA";
                    $all_record['company_address'] = "NA";
                    $all_record['company_city'] = "NA";
                    $all_record['remarks'] = "NA";
                    $all_record['designation'] = "NA";
                    $all_record['year_of_experience'] = "NA";
                    $all_record['empfrom'] = "NA";
                    $all_record['empto'] = "NA";
                    $all_record['remuneration'] = "NA";
                    $all_record['employment_reference_name'] = "NA";
                    $all_record['employment_reference_no'] = "NA";
                    $all_record['reasonforleaving'] = "NA";
                    $all_record['employment_type'] = "NA";
                    $all_record['employment_reference_no'] = "NA";
                    $all_record['reasonforleaving'] = "NA";
                    $all_record['employment_type'] = "NA";
                    $all_record['empid'] = "NA";
                    $all_record['verfname'] = "NA";
                    $all_record['verfdesgn'] = "NA";
                    $all_record['verifiers_contact_no'] = "NA";
                    $all_record['verifiers_email_id'] = "NA";
                    $all_record['r_manager_name'] = "NA";
                    $all_record['r_manager_no'] = "NA";
                    $all_record['r_manager_designation'] = "NA";
                    $all_record['r_manager_email'] = "NA";

                }

            } else {
                $all_record['empver'] = "NA";
                $all_record['coname'] = "NA";
                $all_record['company_address'] = "NA";
                $all_record['company_city'] = "NA";
                $all_record['remarks'] = "NA";
                $all_record['designation'] = "NA";
                $all_record['year_of_experience'] = "NA";
                $all_record['empfrom'] = "NA";
                $all_record['empto'] = "NA";
                $all_record['remuneration'] = "NA";
                $all_record['employment_reference_name'] = "NA";
                $all_record['employment_reference_no'] = "NA";
                $all_record['reasonforleaving'] = "NA";
                $all_record['employment_type'] = "NA";
                $all_record['employment_reference_no'] = "NA";
                $all_record['reasonforleaving'] = "NA";
                $all_record['employment_type'] = "NA";
                $all_record['empid'] = "NA";
                $all_record['verfname'] = "NA";
                $all_record['verfdesgn'] = "NA";
                $all_record['verifiers_contact_no'] = "NA";
                $all_record['verifiers_email_id'] = "NA";
                $all_record['r_manager_name'] = "NA";
                $all_record['r_manager_no'] = "NA";
                $all_record['r_manager_designation'] = "NA";
                $all_record['r_manager_email'] = "NA";
            }

            $all_record['ClientRefNumber'] = $all_record['ClientRefNumber'] ? $all_record['ClientRefNumber'] : 'NA';

            $all_record['CandidateName'] = $all_record['CandidateName'] ? $all_record['CandidateName'] : 'NA';

            $all_record['branch_name'] = $all_record['branch_name'] ? $all_record['branch_name'] : 'NA';

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A$x", $all_record['ClientRefNumber'])
                ->setCellValue("B$x", $all_record['CandidateName'])
                ->setCellValue("C$x", $all_record['addrver'])
                ->setCellValue("D$x", $all_record['address'])
                ->setCellValue("E$x", $all_record['city'])
                ->setCellValue("F$x", $all_record['state'])
                ->setCellValue("G$x", $all_record['pincode'])
                ->setCellValue("H$x", $all_record['empver'])
                ->setCellValue("I$x", $all_record['branch_name'])
                ->setCellValue("J$x", $all_record['coname'])
                ->setCellValue("K$x", $all_record['company_city'])
                ->setCellValue("L$x", $all_record['company_address'])
                ->setCellValue("M$x", $all_record['designation'])
                ->setCellValue("N$x", $all_record['year_of_experience'])
                ->setCellValue("O$x", $all_record['empfrom'])
                ->setCellValue("P$x", $all_record['empto'])
                ->setCellValue("Q$x", $all_record['remuneration'])
                ->setCellValue("R$x", $all_record['employment_reference_name'])
                ->setCellValue("S$x", $all_record['employment_reference_no'])
                ->setCellValue("T$x", $all_record['reasonforleaving'])
                ->setCellValue("U$x", $all_record['employment_type'])
                ->setCellValue("V$x", $all_record['empid'])
                ->setCellValue("W$x", $all_record['year_of_experience'])
                ->setCellValue("X$x", $all_record['r_manager_name'])
                ->setCellValue("Y$x", $all_record['r_manager_designation'])
                ->setCellValue("Z$x", $all_record['r_manager_no'])
                ->setCellValue("AA$x", $all_record['r_manager_email'])
                ->setCellValue("AB$x", $all_record['verfname'])
                ->setCellValue("AC$x", $all_record['verfdesgn'])
                ->setCellValue("AD$x", $all_record['verifiers_email_id'])
                ->setCellValue("AE$x", $all_record['verifiers_contact_no'])
                ->setCellValue("AF$x", $all_record['remarks']);

            $x++;
        }
        // Rename worksheet
        $spreadsheet->getActiveSheet()->setTitle('Candidate Records');

        $spreadsheet->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=Candidates Records of .xlsx");
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Excel2007');

        $file_name = "Candidates_Records_Axis_Securities_Ikya_NEW" . DATE(UPLOAD_FILE_DATE_FORMAT) . ".xls";

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Excel2007');
        ob_start();
        $writer->save($file_upload_path . "/" . $file_name);
        ob_end_clean();

        $this->update_request_data(array('file_name' => $file_name, 'folder_generated_status' => 1, 'folder_name' => $file_upload_path), array('id' => $parameters_array));
        $date_range = $frm_details['activity_from_axis'] . ' to ' . $frm_details['activity_to_axis'];

        $this->update_request_schedular_list(array('report_id' => $parameters_array, 'date_range' => $date_range, 'file_name' => $file_name, 'run_status' => 1, 'last_run_on' => date(DB_DATE_FORMAT)), array('id' => '8'));

    }

    public function generate_excel_axis_ikya_report($parameters_array = null)
    {
        ini_set('memory_limit', '-1');

        $this->load->model('report_generated_user');

        $frm_details['activity_from_axis'] = '01-01-2009';
        $frm_details['activity_to_axis'] = date("d-m-Y");

        $file_upload_path = SITE_BASE_PATH . UPLOAD_FOLDER . 'bulk_reports';

        if (!folder_exist($file_upload_path)) {
            mkdir($file_upload_path, 0777);
        }
        if (!folder_exist($file_upload_path)) {
            mkdir($file_upload_path, 0777);
        } else if (!is_writable($file_upload_path)) {
            mkdir($file_upload_path, 0777);
        }

        $where_arry = array('candidates_info.caserecddate >' => convert_display_to_db_date($frm_details['activity_from_axis']), 'candidates_info.caserecddate <=' => convert_display_to_db_date($frm_details['activity_to_axis']));

        $all_records = $this->report_generated_user->get_candidate_record_for_axis_ikya($where_arry);

        require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
        // Create new Spreadsheet object
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        // Set document properties
        $spreadsheet->getProperties()->setCreator(CRMNAME)
            ->setLastModifiedBy(CRMNAME)
            ->setTitle(CRMNAME)
            ->setSubject('Candidates records')
            ->setDescription('candidates records with their status');
        // add style to the header
        $styleArray = array(
            'font' => array(
                'bold' => true,
            ),
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
                'top' => array(
                    'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ),
            ),
            'fill' => array(
                'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                'rotation' => 90,
                'startcolor' => array(
                    'argb' => 'FFA0A0A0',
                ),
                'endcolor' => array(
                    'argb' => 'FFFFFFFF',
                ),
            ),
        );
        $spreadsheet->getActiveSheet()->getStyle('A1:AI1')->applyFromArray($styleArray);
        // auto fit column to content
        foreach (range('A', 'AI') as $columnID) {
            $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                ->setWidth(20);
        }

        // set the names of header cells
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue("A1", 'Agency Name')
            ->setCellValue("B1", 'Handover Date')
            ->setCellValue("C1", 'Employee Code')
            ->setCellValue("D1", 'Employee Name')
            ->setCellValue("E1", 'Address Status')
            ->setCellValue("F1", 'Remark (including Insufficiency Details / Discrepancy Details)')
            ->setCellValue("G1", 'Date of insufficiency / Major Discrepancies raised by Vendor')
            ->setCellValue("H1", 'Insufficiency 2')
            ->setCellValue("I1", 'Date of Insufficeincy 2')
            ->setCellValue("J1", 'Date of Address check close')
            ->setCellValue("K1", 'Employment Status')
            ->setCellValue("L1", 'Closure Remark')
            ->setCellValue("M1", 'Insufficiency Remark 1')
            ->setCellValue("N1", 'Insuff Raised Date 1')
            ->setCellValue("O1", 'Insufficiency Remark 2')
            ->setCellValue("P1", 'Insuff Raised Date 2')
            ->setCellValue("Q1", 'Discrepancy Remark 1(if any)')
            ->setCellValue("R1", 'Discrepancy Remark 2(if any)')
            ->setCellValue("S1", 'Date of Employment check close')
            ->setCellValue("T1", 'Verifiers Email ID')
            ->setCellValue("U1", 'Address')
            ->setCellValue("V1", 'Name of Company')
            ->setCellValue("W1", 'Experience')
            ->setCellValue("X1", 'Add Clear Date')
            ->setCellValue("Y1", 'Emp Clear Date')
            ->setCellValue("Z1", 'Branch')
            ->setCellValue("AA1", 'Department')
            ->setCellValue("AB1", 'Add Insuff Raised Date')
            ->setCellValue("AC1", 'Emp Insuff Raised Date')
            ->setCellValue("AD1", 'Emp From')
            ->setCellValue("AE1", 'Emp To')
            ->setCellValue("AF1", 'Joining Date')
            ->setCellValue("AG1", 'Designation')
            ->setCellValue("AH1", 'Verifiers Name')
            ->setCellValue("AI1", 'Previous Employee Code');

        // Add some data
        $x = 2;

        foreach ($all_records as $all_record) {

            $components = explode(',', $all_record['component_id']);

            $rename_status = array('New Check' => 'WIP', 'Clear' => 'Positive', 'Insufficiency Cleared' => 'WIP', 'First QC Reject' => 'WIP', 'Follow Up' => 'WIP', 'Final QC Reject' => 'WIP', 'Major Discrepancy' => 'Discrepancy');

            if (in_array('addrver', $components)) {
                $result1 = $this->report_generated_user->get_addres_ver_status(array('candidates_info.id' => $all_record['id']));

                if (!empty($result1)) {

                    $result = $this->report_generated_user->get_address_insuff_details(array('addrver.id' => $result1[0]['id']));

                    $result_discrepancy = $this->report_generated_user->get_address_discrepancy_details(array('addrver.id' => $result1[0]['id']));

                    if (array_key_exists($result1[0]['status_value'], $rename_status)) {
                        $all_record['addrver'] = $rename_status[$result1[0]['status_value']];
                    } else {
                        $all_record['addrver'] = ($result1[0]['status_value'] != "") ? $result1[0]['status_value'] : 'WIP';
                    }

                    if ($result1[0]['closuredate'] === null) {
                        $all_record['addrver_closure_date'] = "WIP";
                    } else if ($result1[0]['closuredate'] != "") {
                        $all_record['addrver_closure_date'] = date('d-M-Y', strtotime($result1[0]['closuredate']));

                        $all_record['addrver_closure_date'] = ($all_record['addrver_closure_date']) != '01-Jan-1970' ? $all_record['addrver_closure_date'] : 'NA';
                    } else {
                        $all_record['addrver_closure_date'] = "NA";
                    }

                    $all_record['address'] = $result1[0]['address'] ? $result1[0]['address'] : 'NA';

                    $all_record['addrver_insuff_remarks_1'] = ($result[0]['insuff_raise_remark'] != "") ? $result[0]['insuff_raise_remark'] : 'NA';

                    $all_record['addrver_insuff_raised_date_1'] = (date('d-M-Y', strtotime($result[0]['insuff_raised_date'])) != '01-Jan-1970') ? date('d-M-Y', strtotime($result[0]['insuff_raised_date'])) : 'NA';

                    $addrver_insuff_raised_date_array = array_slice($result, 1);

                    if (!empty($addrver_insuff_raised_date_array)) {
                        foreach ($addrver_insuff_raised_date_array as $addrver_insuff_raised_date) {
                            $addrver_insuff_raised_2[] = (date('d-M-Y', strtotime($addrver_insuff_raised_date['insuff_raised_date'])) != '01-Jan-1970') ? date('d-M-Y', strtotime($addrver_insuff_raised_date['insuff_raised_date'])) : 'NA';
                        }

                        if ($all_record['addrver'] == "Insufficiency") {
                            $all_record['addrver'] = "Insufficiency II";
                        } else {
                            $all_record['addrver'] = $all_record['addrver'];
                        }

                        $all_record['addrver_insuff_remarks_2'] = (end($result)['insuff_raise_remark'] != "") ? end($result)['insuff_raise_remark'] : 'NA';
                    } else {
                        $addrver_insuff_raised_2[] = "NA";
                        $all_record['addrver_insuff_remarks_2'] = "NA";
                    }

                    $all_record['addrver_insuff_raised_date_2'] = implode(" & ", $addrver_insuff_raised_2);
                    unset($addrver_insuff_raised_2);

                    foreach ($result as $results) {
                        $addrver_insuffraiseddate_2[] = (date('d-M-Y', strtotime($results['insuff_raised_date'])) != '01-Jan-1970') ? date('d-M-Y', strtotime($results['insuff_raised_date'])) : 'NA';
                    }

                    $all_record['addrver_insuffraiseddate_2'] = implode(" & ", $addrver_insuffraiseddate_2);
                    unset($addrver_insuffraiseddate_2);

                    foreach ($result as $results) {
                        $addrver_insuffcleardate_2[] = (date('d-M-Y', strtotime($results['insuff_clear_date'])) != '01-Jan-1970') ? date('d-M-Y', strtotime($results['insuff_clear_date'])) : 'NA';
                    }

                    $all_record['addrver_insuffcleardate_2'] = implode(" & ", $addrver_insuffcleardate_2);
                    unset($addrver_insuffcleardate_2);

                    $addrver_discrepancy_array = array_slice($result_discrepancy, 1);

                    if (!empty($addrver_discrepancy_array)) {

                        if ($all_record['addrver'] == "Discrepancy") {
                            $all_record['addrver'] = "Discrepancy II";
                        } else {
                            $all_record['addrver'] = $all_record['addrver'];
                        }
                    }

                } else {
                    $all_record['addrver'] = "NA";
                    $all_record['addrver_insuff_remarks_1'] = "NA";
                    $all_record['addrver_insuff_raised_date_1'] = "NA";
                    $all_record['addrver_insuff_remarks_2'] = "NA";
                    $all_record['addrver_insuff_raised_date_2'] = "NA";
                    $all_record['addrver_closure_date'] = "NA";
                    $all_record['addrver_insuffcleardate_2'] = "NA";
                    $all_record['addrver_insuffraiseddate_2'] = "NA";
                }
            } else {
                $all_record['addrver'] = "NA";
                $all_record['addrver_insuff_remarks_1'] = "NA";
                $all_record['addrver_insuff_raised_date_1'] = "NA";
                $all_record['addrver_insuff_remarks_2'] = "NA";
                $all_record['addrver_insuff_raised_date_2'] = "NA";
                $all_record['addrver_closure_date'] = "NA";
                $all_record['addrver_insuffcleardate_2'] = "NA";
                $all_record['addrver_insuffraiseddate_2'] = "NA";
            }

            if (in_array('empver', $components)) {

                $result1 = $this->report_generated_user->get_empver_ver_status(array('candidates_info.id' => $all_record['id']));

                if (!empty($result1)) {

                    $result = $this->report_generated_user->get_employment_insuff_details(array('empver.id' => $result1[0]['id']));

                    $result_discrepancy = $this->report_generated_user->get_employment_discrepancy_details(array('empver.id' => $result1[0]['id']));

                    if (array_key_exists($result1[0]['status_value'], $rename_status)) {
                        $all_record['empver'] = $rename_status[$result1[0]['status_value']];
                    } else {
                        $all_record['empver'] = ($result1[0]['status_value'] != "") ? $result1[0]['status_value'] : 'WIP';

                    }

                    if ($result1[0]['closuredate'] === null) {
                        $all_record['empver_closure_date'] = "WIP";
                    } else if ($result1[0]['closuredate'] != "") {
                        $all_record['empver_closure_date'] = date('d-M-Y', strtotime($result1[0]['closuredate']));

                        $all_record['empver_closure_date'] = ($all_record['empver_closure_date']) != '01-Jan-1970' ? $all_record['empver_closure_date'] : 'NA';
                    } else {
                        $all_record['empver_closure_date'] = "NA";
                    }

                    if (strpos($result1[0]['empfrom'], "-") == 4 || strpos($result1[0]['empfrom'], "-") == 2) {

                        $employed_from = date("d-m-Y", strtotime($result1[0]['empfrom']));
                    } else {
                        $employed_from = $result1[0]['empfrom'];
                    }

                    if (strpos($result1[0]['empto'], "-") == 4 || strpos($result1[0]['empto'], "-") == 2) {

                        $employed_to = date("d-m-Y", strtotime($result1[0]['empto']));
                    } else {
                        $employed_to = $result1[0]['empto'];
                    }

                    $all_record['coname'] = $result1[0]['coname'] ? $result1[0]['coname'] : 'NA';
                    $all_record['empfrom'] = ($employed_from != "") ? $employed_from : 'NA';
                    $all_record['empto'] = ($employed_to != "") ? $employed_to : 'NA';

                    $all_record['empres_remarks_'] = $result1[0]['remarks'] ? $result1[0]['remarks'] : 'NA';

                    $all_record['empver_insuff_remarks_1'] = ($result[0]['insuff_raise_remark'] != "") ? $result[0]['insuff_raise_remark'] : 'NA';

                    $all_record['empver_insuff_raised_date_1'] = (date('d-M-Y', strtotime($result[0]['insuff_raised_date'])) != '01-Jan-1970') ? date('d-M-Y', strtotime($result[0]['insuff_raised_date'])) : 'NA';

                    $all_record['verifiers_email_id'] = ($result1[0]['verifiers_email_id'] != "") ? $result1[0]['verifiers_email_id'] : 'NA';

                    $empver_insuff_raised_date_array = array_slice($result, 1);

                    if (!empty($empver_insuff_raised_date_array)) {
                        foreach ($empver_insuff_raised_date_array as $empver_insuff_raised_date) {
                            $empver_insuff_raised_2[] = (date('d-M-Y', strtotime($empver_insuff_raised_date['insuff_raised_date'])) != '01-Jan-1970') ? date('d-M-Y', strtotime($empver_insuff_raised_date['insuff_raised_date'])) : 'NA';
                        }

                        if ($all_record['empver'] == "Insufficiency") {
                            $all_record['empver'] = "Insufficiency II";
                        } else {
                            $all_record['empver'] = $all_record['empver'];
                        }

                        $all_record['empver_insuff_remarks_2'] = (end($result)['insuff_raise_remark'] != "") ? end($result)['insuff_raise_remark'] : 'NA';

                    } else {
                        $empver_insuff_raised_2[] = "NA";

                        $all_record['empver_insuff_remarks_2'] = 'NA';

                    }

                    $all_record['empver_insuff_raised_date_2'] = implode(" & ", $empver_insuff_raised_2);
                    unset($empver_insuff_raised_2);

                    foreach ($result as $results) {
                        $empver_insuffraiseddate_2[] = (date('d-M-Y', strtotime($results['insuff_raised_date'])) != '01-Jan-1970') ? date('d-M-Y', strtotime($results['insuff_raised_date'])) : 'NA';
                    }

                    $all_record['empver_insuffraiseddate_2'] = implode(" & ", $empver_insuffraiseddate_2);
                    unset($empver_insuffraiseddate_2);

                    foreach ($result as $results) {
                        $empver_insuffcleardate_2[] = (date('d-M-Y', strtotime($results['insuff_clear_date'])) != '01-Jan-1970') ? date('d-M-Y', strtotime($results['insuff_clear_date'])) : 'NA';
                    }

                    $all_record['empver_insuffcleardate_2'] = implode(" & ", $empver_insuffcleardate_2);
                    unset($empver_insuffcleardate_2);

                    $all_record['verifiers_email_id'] = ($result1[0]['verifiers_email_id'] != "") ? $result1[0]['verifiers_email_id'] : 'NA';

                    $all_record['designation'] = ($result1[0]['designation'] != "") ? $result1[0]['designation'] : 'NA';
                    $all_record['verfname'] = ($result1[0]['verfname'] != "") ? $result1[0]['verfname'] : 'NA';
                    $all_record['empid'] = ($result1[0]['empid'] != "") ? $result1[0]['empid'] : 'NA';

                    if (!empty($result_discrepancy)) {

                        $all_record['empver_discrepancy_remark_1'] = ($result_discrepancy[0]['remarks'] != "") ? $result_discrepancy[0]['remarks'] : "NA";
                    } else {
                        $all_record['empver_discrepancy_remark_1'] = "NA";

                    }

                    $empver_discrepancy_array = array_slice($result_discrepancy, 1);

                    if (!empty($empver_discrepancy_array)) {
                        $all_record['empver_discrepancy_remark_2'] = (end($result_discrepancy)['remarks'] != "") ? end($result_discrepancy)['remarks'] : 'NA';

                        if ($all_record['empver'] == "Discrepancy") {
                            $all_record['empver'] = "Discrepancy II";
                        } else {
                            $all_record['empver'] = $all_record['empver'];
                        }
                    } else {
                        $all_record['empver_discrepancy_remark_2'] = 'NA';
                    }
                } else {
                    $all_record['empver'] = "NA";
                    $all_record['empres_remarks_'] = "NA";
                    $all_record['empver_insuff_remarks_1'] = "NA";
                    $all_record['empver_insuff_raised_date_1'] = "NA";
                    $all_record['empver_insuff_remarks_2'] = "NA";
                    $all_record['empver_insuff_raised_date_2'] = "NA";
                    $all_record['empver_discrepancy_remark_1'] = "NA";
                    $all_record['empver_discrepancy_remark_2'] = "NA";
                    $all_record['empver_closure_date'] = "NA";
                    $all_record['verifiers_email_id'] = "NA";
                    //  $all_record['addrver_insuffcleardate_2'] = "NA";
                    $all_record['empver_insuffcleardate_2'] = "NA";
                    $all_record['empver_insuffraiseddate_2'] = "NA";
                    $all_record['coname'] = "NA";
                    $all_record['empfrom'] = "NA";
                    $all_record['empto'] = "NA";
                    $all_record['designation'] = "NA";
                    $all_record['verfname'] = "NA";
                    $all_record['empid'] = "NA";
                }

            } else {
                $all_record['empver'] = "NA";
                $all_record['empres_remarks_'] = "NA";
                $all_record['empver_insuff_remarks_1'] = "NA";
                $all_record['empver_insuff_raised_date_1'] = "NA";
                $all_record['empver_insuff_remarks_2'] = "NA";
                $all_record['empver_insuff_raised_date_2'] = "NA";
                $all_record['empver_discrepancy_remark_1'] = "NA";
                $all_record['empver_discrepancy_remark_2'] = "NA";
                $all_record['empver_closure_date'] = "NA";
                $all_record['verifiers_email_id'] = "NA";
                // $all_record['addrver_insuffcleardate_2'] = "NA";
                $all_record['empver_insuffcleardate_2'] = "NA";
                $all_record['empver_insuffraiseddate_2'] = "NA";
                $all_record['coname'] = "NA";
                $all_record['empfrom'] = "NA";
                $all_record['empto'] = "NA";
                $all_record['designation'] = "NA";
                $all_record['verfname'] = "NA";
                $all_record['empid'] = "NA";
            }

            $clientName = $all_record['clientname'];

            $caserecddate = (date('d-M-Y', strtotime($all_record['caserecddate'])) != '01-Jan-1970') ? date('d-M-Y', strtotime($all_record['caserecddate'])) : 'NA';

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A$x", CRMNAME)
                ->setCellValue("B$x", $caserecddate)
                ->setCellValue("C$x", $all_record['ClientRefNumber'])
                ->setCellValue("D$x", $all_record['CandidateName'])
                ->setCellValue("E$x", $all_record['addrver'])
                ->setCellValue("F$x", $all_record['addrver_insuff_remarks_1'])
                ->setCellValue("G$x", $all_record['addrver_insuff_raised_date_1'])
                ->setCellValue("H$x", $all_record['addrver_insuff_remarks_2'])
                ->setCellValue("I$x", $all_record['addrver_insuff_raised_date_2'])
                ->setCellValue("J$x", $all_record['addrver_closure_date'])
                ->setCellValue("K$x", $all_record['empver'])
                ->setCellValue("L$x", $all_record['empres_remarks_'])
                ->setCellValue("M$x", $all_record['empver_insuff_remarks_1'])
                ->setCellValue("N$x", $all_record['empver_insuff_raised_date_1'])
                ->setCellValue("O$x", $all_record['empver_insuff_remarks_2'])
                ->setCellValue("P$x", $all_record['empver_insuff_raised_date_2'])
                ->setCellValue("Q$x", $all_record['empver_discrepancy_remark_1'])
                ->setCellValue("R$x", $all_record['empver_discrepancy_remark_2'])
                ->setCellValue("S$x", $all_record['empver_closure_date'])
                ->setCellValue("T$x", $all_record['verifiers_email_id'])
                ->setCellValue("U$x", $all_record['address'])
                ->setCellValue("V$x", $all_record['coname'])
                ->setCellValue("W$x", $all_record['branch_name'])
                ->setCellValue("X$x", $all_record['addrver_insuffcleardate_2'])
                ->setCellValue("Y$x", $all_record['empver_insuffcleardate_2'])
                ->setCellValue("Z$x", $all_record['Location'])
                ->setCellValue("AA$x", $all_record['Department'])
                ->setCellValue("AB$x", $all_record['addrver_insuffraiseddate_2'])
                ->setCellValue("AC$x", $all_record['empver_insuffraiseddate_2'])
                ->setCellValue("AD$x", $all_record['empfrom'])
                ->setCellValue("AE$x", $all_record['empto'])
                ->setCellValue("AF$x", convert_db_to_display_date($all_record['DateofJoining']))
                ->setCellValue("AG$x", $all_record['designation'])
                ->setCellValue("AH$x", $all_record['verfname'])
                ->setCellValue("AI$x", $all_record['empid']);
            $x++;
        }
        // Rename worksheet
        $spreadsheet->getActiveSheet()->setTitle('Candidate Records');

        $spreadsheet->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=Candidates Records of .xlsx");
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Excel2007');

        $file_name = "Candidates_Records_Axis_Securities_Ikya_" . DATE(UPLOAD_FILE_DATE_FORMAT) . ".xls";

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Excel2007');
        ob_start();
        $writer->save($file_upload_path . "/" . $file_name);
        ob_end_clean();

        $this->update_request_data(array('file_name' => $file_name, 'folder_generated_status' => 1, 'folder_name' => $file_upload_path), array('id' => $parameters_array));
        $date_range = $frm_details['activity_from_axis'] . ' to ' . $frm_details['activity_to_axis'];

        $this->update_request_schedular_list(array('report_id' => $parameters_array, 'date_range' => $date_range, 'file_name' => $file_name, 'run_status' => 1, 'last_run_on' => date(DB_DATE_FORMAT)), array('id' => '3'));

    }

    public function generate_excel_axis_teamlease_report($parameters_array = null)
    {
        $this->load->model('report_generated_user');

        $frm_details['activity_from_axis'] = '01-01-2009';
        $frm_details['activity_to_axis'] = date("d-m-Y");

        $file_upload_path = SITE_BASE_PATH . UPLOAD_FOLDER . 'bulk_reports';

        if (!folder_exist($file_upload_path)) {
            mkdir($file_upload_path, 0777);
        }
        if (!folder_exist($file_upload_path)) {
            mkdir($file_upload_path, 0777);
        } else if (!is_writable($file_upload_path)) {
            mkdir($file_upload_path, 0777);
        }

        $where_arry = array('candidates_info.caserecddate >' => convert_display_to_db_date($frm_details['activity_from_axis']), 'candidates_info.caserecddate <=' => convert_display_to_db_date($frm_details['activity_to_axis']));

        $all_records = $this->report_generated_user->get_candidate_record_for_axis_teamlease($where_arry);

        require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
        // Create new Spreadsheet object
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        // Set document properties
        $spreadsheet->getProperties()->setCreator(CRMNAME)
            ->setLastModifiedBy(CRMNAME)
            ->setTitle(CRMNAME)
            ->setSubject('Candidates records')
            ->setDescription('candidates records with their status');
        // add style to the header
        $styleArray = array(
            'font' => array(
                'bold' => true,
            ),
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
                'top' => array(
                    'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ),
            ),
            'fill' => array(
                'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                'rotation' => 90,
                'startcolor' => array(
                    'argb' => 'FFA0A0A0',
                ),
                'endcolor' => array(
                    'argb' => 'FFFFFFFF',
                ),
            ),
        );
        $spreadsheet->getActiveSheet()->getStyle('A1:AG1')->applyFromArray($styleArray);
        // auto fit column to content
        foreach (range('A', 'AG') as $columnID) {
            $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                ->setWidth(20);
        }

        // set the names of header cells
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue("A1", 'Agency Name')
            ->setCellValue("B1", 'Handover Date')
            ->setCellValue("C1", 'Employee Code')
            ->setCellValue("D1", 'Employee Name')
            ->setCellValue("E1", 'Address Status')
            ->setCellValue("F1", 'Remark (including Insufficiency Details / Discrepancy Details)')
            ->setCellValue("G1", 'Date of insufficiency / Major Discrepancies raised by Vendor')
            ->setCellValue("H1", 'Insufficiency 2')
            ->setCellValue("I1", 'Date of Insufficeincy 2')
            ->setCellValue("J1", 'Date of Address check close')
            ->setCellValue("K1", 'Employment Status')
            ->setCellValue("L1", 'Closure Remark')
            ->setCellValue("M1", 'Insufficiency Remark 1')
            ->setCellValue("N1", 'Insuff Raised Date 1')
            ->setCellValue("O1", 'Insufficiency Remark 2')
            ->setCellValue("P1", 'Insuff Raised Date 2')
            ->setCellValue("Q1", 'Discrepancy Remark 1(if any)')
            ->setCellValue("R1", 'Discrepancy Remark 2(if any)')
            ->setCellValue("S1", 'Date of Employment check close')
            ->setCellValue("T1", 'Verifiers Email ID')
            ->setCellValue("U1", 'Address')
            ->setCellValue("V1", 'Name of Company')
            ->setCellValue("W1", 'Experience')
            ->setCellValue("X1", 'Add Clear Date')
            ->setCellValue("Y1", 'Emp Clear Date')
            ->setCellValue("Z1", 'Branch')
            ->setCellValue("AA1", 'Department')
            ->setCellValue("AB1", 'Add Insuff Raised Date')
            ->setCellValue("AC1", 'Emp Insuff Raised Date')
            ->setCellValue("AD1", 'Joining Date')
            ->setCellValue("AE1", 'Designation')
            ->setCellValue("AF1", 'Verifiers Name')
            ->setCellValue("AG1", 'Previous Employee Code');
        // Add some data
        $x = 2;

        foreach ($all_records as $all_record) {

            $components = explode(',', $all_record['component_id']);

            $rename_status = array('New Check' => 'WIP', 'Clear' => 'Positive', 'Insufficiency Cleared' => 'WIP', 'First QC Reject' => 'WIP', 'Follow Up' => 'WIP', 'Final QC Reject' => 'WIP', 'Major Discrepancy' => 'Discrepancy');

            if (in_array('addrver', $components)) {
                $result1 = $this->report_generated_user->get_addres_ver_status(array('candidates_info.id' => $all_record['id']));

                if (!empty($result1)) {

                    $result = $this->report_generated_user->get_address_insuff_details(array('addrver.id' => $result1[0]['id']));

                    $result_discrepancy = $this->report_generated_user->get_address_discrepancy_details(array('addrver.id' => $result1[0]['id']));

                    if (array_key_exists($result1[0]['status_value'], $rename_status)) {
                        $all_record['addrver'] = $rename_status[$result1[0]['status_value']];
                    } else {
                        $all_record['addrver'] = ($result1[0]['status_value'] != "") ? $result1[0]['status_value'] : 'WIP';
                    }

                    if ($result1[0]['closuredate'] === null) {
                        $all_record['addrver_closure_date'] = "WIP";
                    } else if ($result1[0]['closuredate'] != "") {
                        $all_record['addrver_closure_date'] = date('d-M-Y', strtotime($result1[0]['closuredate']));
                        $all_record['addrver_closure_date'] = ($all_record['addrver_closure_date']) != '01-Jan-1970' ? $all_record['addrver_closure_date'] : 'NA';
                    } else {
                        $all_record['addrver_closure_date'] = "NA";
                    }

                    $all_record['address'] = $result1[0]['address'] ? $result1[0]['address'] : 'NA';

                    $all_record['addrver_insuff_remarks_1'] = ($result[0]['insuff_raise_remark'] != "") ? $result[0]['insuff_raise_remark'] : 'NA';

                    $all_record['addrver_insuff_raised_date_1'] = (date('d-M-Y', strtotime($result[0]['insuff_raised_date'])) != '01-Jan-1970') ? date('d-M-Y', strtotime($result[0]['insuff_raised_date'])) : 'NA';

                    $addrver_insuff_raised_date_array = array_slice($result, 1);

                    if (!empty($addrver_insuff_raised_date_array)) {
                        foreach ($addrver_insuff_raised_date_array as $addrver_insuff_raised_date) {
                            $addrver_insuff_raised_2[] = (date('d-M-Y', strtotime($addrver_insuff_raised_date['insuff_raised_date'])) != '01-Jan-1970') ? date('d-M-Y', strtotime($addrver_insuff_raised_date['insuff_raised_date'])) : 'NA';
                        }

                        if ($all_record['addrver'] == "Insufficiency") {
                            $all_record['addrver'] = "Insufficiency II";
                        } else {
                            $all_record['addrver'] = $all_record['addrver'];
                        }

                        $all_record['addrver_insuff_remarks_2'] = (end($result)['insuff_raise_remark'] != "") ? end($result)['insuff_raise_remark'] : 'NA';
                    } else {
                        $addrver_insuff_raised_2[] = "NA";
                        $all_record['addrver_insuff_remarks_2'] = "NA";
                    }

                    $all_record['addrver_insuff_raised_date_2'] = implode(" & ", $addrver_insuff_raised_2);
                    unset($addrver_insuff_raised_2);

                    foreach ($result as $results) {
                        $addrver_insuffraiseddate_2[] = (date('d-M-Y', strtotime($results['insuff_raised_date'])) != '01-Jan-1970') ? date('d-M-Y', strtotime($results['insuff_raised_date'])) : 'NA';
                    }

                    $all_record['addrver_insuffraiseddate_2'] = implode(" & ", $addrver_insuffraiseddate_2);
                    unset($addrver_insuffraiseddate_2);

                    foreach ($result as $results) {
                        $addrver_insuffcleardate_2[] = (date('d-M-Y', strtotime($results['insuff_clear_date'])) != '01-Jan-1970') ? date('d-M-Y', strtotime($results['insuff_clear_date'])) : 'NA';
                    }

                    $all_record['addrver_insuffcleardate_2'] = implode(" & ", $addrver_insuffcleardate_2);
                    unset($addrver_insuffcleardate_2);

                    $addrver_discrepancy_array = array_slice($result_discrepancy, 1);

                    if (!empty($addrver_discrepancy_array)) {

                        if ($all_record['addrver'] == "Discrepancy") {
                            $all_record['addrver'] = "Discrepancy II";
                        } else {
                            $all_record['addrver'] = $all_record['addrver'];
                        }
                    }

                } else {
                    $all_record['addrver'] = "NA";
                    $all_record['addrver_insuff_remarks_1'] = "NA";
                    $all_record['addrver_insuff_raised_date_1'] = "NA";
                    $all_record['addrver_insuff_remarks_2'] = "NA";
                    $all_record['addrver_insuff_raised_date_2'] = "NA";
                    $all_record['addrver_closure_date'] = "NA";
                    $all_record['addrver_insuffcleardate_2'] = "NA";
                    $all_record['addrver_insuffraiseddate_2'] = "NA";
                }
            } else {
                $all_record['addrver'] = "NA";
                $all_record['addrver_insuff_remarks_1'] = "NA";
                $all_record['addrver_insuff_raised_date_1'] = "NA";
                $all_record['addrver_insuff_remarks_2'] = "NA";
                $all_record['addrver_insuff_raised_date_2'] = "NA";
                $all_record['addrver_closure_date'] = "NA";
                $all_record['addrver_insuffcleardate_2'] = "NA";
                $all_record['addrver_insuffraiseddate_2'] = "NA";
            }

            if (in_array('empver', $components)) {

                $result1 = $this->report_generated_user->get_empver_ver_status(array('candidates_info.id' => $all_record['id']));

                if (!empty($result1)) {

                    $result = $this->report_generated_user->get_employment_insuff_details(array('empver.id' => $result1[0]['id']));

                    $result_discrepancy = $this->report_generated_user->get_employment_discrepancy_details(array('empver.id' => $result1[0]['id']));

                    if (array_key_exists($result1[0]['status_value'], $rename_status)) {
                        $all_record['empver'] = $rename_status[$result1[0]['status_value']];
                    } else {
                        $all_record['empver'] = ($result1[0]['status_value'] != "") ? $result1[0]['status_value'] : 'WIP';

                    }

                    if ($result1[0]['closuredate'] === null) {
                        $all_record['empver_closure_date'] = "WIP";
                    } else if ($result1[0]['closuredate'] != "") {
                        $all_record['empver_closure_date'] = date('d-M-Y', strtotime($result1[0]['closuredate']));

                        $all_record['empver_closure_date'] = ($all_record['empver_closure_date']) != '01-Jan-1970' ? $all_record['empver_closure_date'] : 'NA';
                    } else {
                        $all_record['empver_closure_date'] = "NA";
                    }

                    $all_record['coname'] = $result1[0]['coname'] ? $result1[0]['coname'] : 'NA';

                    $all_record['empres_remarks_'] = $result1[0]['remarks'] ? $result1[0]['remarks'] : 'NA';

                    $all_record['empver_insuff_remarks_1'] = ($result[0]['insuff_raise_remark'] != "") ? $result[0]['insuff_raise_remark'] : 'NA';

                    $all_record['empver_insuff_raised_date_1'] = (date('d-M-Y', strtotime($result[0]['insuff_raised_date'])) != '01-Jan-1970') ? date('d-M-Y', strtotime($result[0]['insuff_raised_date'])) : 'NA';

                    $all_record['verifiers_email_id'] = ($result1[0]['verifiers_email_id'] != "") ? $result1[0]['verifiers_email_id'] : 'NA';
                    $all_record['designation'] = ($result1[0]['designation'] != "") ? $result1[0]['designation'] : 'NA';
                    $all_record['verfname'] = ($result1[0]['verfname'] != "") ? $result1[0]['verfname'] : 'NA';
                    $all_record['empid'] = ($result1[0]['empid'] != "") ? $result1[0]['empid'] : 'NA';

                    $empver_insuff_raised_date_array = array_slice($result, 1);

                    if (!empty($empver_insuff_raised_date_array)) {
                        foreach ($empver_insuff_raised_date_array as $empver_insuff_raised_date) {
                            $empver_insuff_raised_2[] = (date('d-M-Y', strtotime($empver_insuff_raised_date['insuff_raised_date'])) != '01-Jan-1970') ? date('d-M-Y', strtotime($empver_insuff_raised_date['insuff_raised_date'])) : 'NA';
                        }

                        if ($all_record['empver'] == "Insufficiency") {
                            $all_record['empver'] = "Insufficiency II";
                        } else {
                            $all_record['empver'] = $all_record['empver'];
                        }

                        $all_record['empver_insuff_remarks_2'] = (end($result)['insuff_raise_remark'] != "") ? end($result)['insuff_raise_remark'] : 'NA';

                    } else {
                        $empver_insuff_raised_2[] = "NA";

                        $all_record['empver_insuff_remarks_2'] = 'NA';

                    }

                    $all_record['empver_insuff_raised_date_2'] = implode(" & ", $empver_insuff_raised_2);
                    unset($empver_insuff_raised_2);

                    foreach ($result as $results) {
                        $empver_insuffraiseddate_2[] = (date('d-M-Y', strtotime($results['insuff_raised_date'])) != '01-Jan-1970') ? date('d-M-Y', strtotime($results['insuff_raised_date'])) : 'NA';
                    }

                    $all_record['empver_insuffraiseddate_2'] = implode(" & ", $empver_insuffraiseddate_2);
                    unset($empver_insuffraiseddate_2);

                    foreach ($result as $results) {
                        $empver_insuffcleardate_2[] = (date('d-M-Y', strtotime($results['insuff_clear_date'])) != '01-Jan-1970') ? date('d-M-Y', strtotime($results['insuff_clear_date'])) : 'NA';
                    }

                    $all_record['empver_insuffcleardate_2'] = implode(" & ", $empver_insuffcleardate_2);
                    unset($empver_insuffcleardate_2);

                    $all_record['verifiers_email_id'] = ($result1[0]['verifiers_email_id'] != "") ? $result1[0]['verifiers_email_id'] : 'NA';

                    if (!empty($result_discrepancy)) {

                        $all_record['empver_discrepancy_remark_1'] = ($result_discrepancy[0]['remarks'] != "") ? $result_discrepancy[0]['remarks'] : "NA";
                    } else {
                        $all_record['empver_discrepancy_remark_1'] = "NA";

                    }

                    $empver_discrepancy_array = array_slice($result_discrepancy, 1);

                    if (!empty($empver_discrepancy_array)) {
                        $all_record['empver_discrepancy_remark_2'] = (end($result_discrepancy)['remarks'] != "") ? end($result_discrepancy)['remarks'] : 'NA';

                        if ($all_record['empver'] == "Discrepancy") {
                            $all_record['empver'] = "Discrepancy II";
                        } else {
                            $all_record['empver'] = $all_record['empver'];
                        }
                    } else {
                        $all_record['empver_discrepancy_remark_2'] = 'NA';
                    }
                } else {
                    $all_record['empver'] = "NA";
                    $all_record['empres_remarks_'] = "NA";
                    $all_record['empver_insuff_remarks_1'] = "NA";
                    $all_record['empver_insuff_raised_date_1'] = "NA";
                    $all_record['empver_insuff_remarks_2'] = "NA";
                    $all_record['empver_insuff_raised_date_2'] = "NA";
                    $all_record['empver_discrepancy_remark_1'] = "NA";
                    $all_record['empver_discrepancy_remark_2'] = "NA";
                    $all_record['empver_closure_date'] = "NA";
                    $all_record['verifiers_email_id'] = "NA";
                    //$all_record['addrver_insuffcleardate_2'] = "NA";
                    $all_record['empver_insuffcleardate_2'] = "NA";
                    $all_record['empver_insuffraiseddate_2'] = "NA";
                    $all_record['coname'] = "NA";
                    $all_record['designation'] = "NA";
                    $all_record['verfname'] = "NA";
                    $all_record['empid'] = "NA";
                }

            } else {
                $all_record['empver'] = "NA";
                $all_record['empres_remarks_'] = "NA";
                $all_record['empver_insuff_remarks_1'] = "NA";
                $all_record['empver_insuff_raised_date_1'] = "NA";
                $all_record['empver_insuff_remarks_2'] = "NA";
                $all_record['empver_insuff_raised_date_2'] = "NA";
                $all_record['empver_discrepancy_remark_1'] = "NA";
                $all_record['empver_discrepancy_remark_2'] = "NA";
                $all_record['empver_closure_date'] = "NA";
                $all_record['verifiers_email_id'] = "NA";
                //  $all_record['addrver_insuffcleardate_2'] = "NA";
                $all_record['empver_insuffcleardate_2'] = "NA";
                $all_record['coname'] = "NA";
                $all_record['empver_insuffraiseddate_2'] = "NA";
                $all_record['designation'] = "NA";
                $all_record['verfname'] = "NA";
                $all_record['empid'] = "NA";
            }

            $clientName = $all_record['clientname'];

            $caserecddate = (date('d-M-Y', strtotime($all_record['caserecddate'])) != '01-Jan-1970') ? date('d-M-Y', strtotime($all_record['caserecddate'])) : 'NA';

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A$x", CRMNAME)
                ->setCellValue("B$x", $caserecddate)
                ->setCellValue("C$x", $all_record['ClientRefNumber'])
                ->setCellValue("D$x", $all_record['CandidateName'])
                ->setCellValue("E$x", $all_record['addrver'])
                ->setCellValue("F$x", $all_record['addrver_insuff_remarks_1'])
                ->setCellValue("G$x", $all_record['addrver_insuff_raised_date_1'])
                ->setCellValue("H$x", $all_record['addrver_insuff_remarks_2'])
                ->setCellValue("I$x", $all_record['addrver_insuff_raised_date_2'])
                ->setCellValue("J$x", $all_record['addrver_closure_date'])
                ->setCellValue("K$x", $all_record['empver'])
                ->setCellValue("L$x", $all_record['empres_remarks_'])
                ->setCellValue("M$x", $all_record['empver_insuff_remarks_1'])
                ->setCellValue("N$x", $all_record['empver_insuff_raised_date_1'])
                ->setCellValue("O$x", $all_record['empver_insuff_remarks_2'])
                ->setCellValue("P$x", $all_record['empver_insuff_raised_date_2'])
                ->setCellValue("Q$x", $all_record['empver_discrepancy_remark_1'])
                ->setCellValue("R$x", $all_record['empver_discrepancy_remark_2'])
                ->setCellValue("S$x", $all_record['empver_closure_date'])
                ->setCellValue("T$x", $all_record['verifiers_email_id'])
                ->setCellValue("U$x", $all_record['address'])
                ->setCellValue("V$x", $all_record['coname'])
                ->setCellValue("W$x", $all_record['branch_name'])
                ->setCellValue("X$x", $all_record['addrver_insuffcleardate_2'])
                ->setCellValue("Y$x", $all_record['empver_insuffcleardate_2'])
                ->setCellValue("Z$x", $all_record['Location'])
                ->setCellValue("AA$x", $all_record['Department'])
                ->setCellValue("AB$x", $all_record['addrver_insuffraiseddate_2'])
                ->setCellValue("AC$x", $all_record['empver_insuffraiseddate_2'])
                ->setCellValue("AD$x", convert_db_to_display_date($all_record['DateofJoining']))
                ->setCellValue("AE$x", $all_record['designation'])
                ->setCellValue("AF$x", $all_record['verfname'])
                ->setCellValue("AG$x", $all_record['empid']);

            $x++;
        }
        // Rename worksheet
        $spreadsheet->getActiveSheet()->setTitle('Candidate Records');

        $spreadsheet->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=Candidates Records of .xlsx");
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Excel2007');

        $file_name = "Candidates_Records_Axis_Securities_Teamlease_" . DATE(UPLOAD_FILE_DATE_FORMAT) . ".xls";

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Excel2007');
        ob_start();
        $writer->save($file_upload_path . "/" . $file_name);
        ob_end_clean();

        $this->update_request_data(array('file_name' => $file_name, 'folder_generated_status' => 1, 'folder_name' => $file_upload_path), array('id' => $parameters_array));
        $date_range = $frm_details['activity_from_axis'] . ' to ' . $frm_details['activity_to_axis'];

        $this->update_request_schedular_list(array('report_id' => $parameters_array, 'date_range' => $date_range, 'file_name' => $file_name, 'run_status' => 1, 'last_run_on' => date(DB_DATE_FORMAT)), array('id' => '4'));

    }

    public function export_to_excel($parameters_array = null, $client_id, $entity_id, $package_id, $client_name, $fil_by_status, $fil_by_sub_status)
    {

        ini_set('memory_limit', '-1');

        $this->load->model('report_generated_user');

        $file_upload_path = SITE_BASE_PATH . UPLOAD_FOLDER . 'bulk_reports';

        if (!folder_exist($file_upload_path)) {
            mkdir($file_upload_path, 0777);
        }
        if (!folder_exist($file_upload_path)) {
            mkdir($file_upload_path, 0777);
        } else if (!is_writable($file_upload_path)) {
            mkdir($file_upload_path, 0777);
        }

        $all_records = $this->get_all_client_data_for_export($client_id, $entity_id, $package_id, $fil_by_status, $fil_by_sub_status);

        require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
        // Create new Spreadsheet object
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        // Set document properties
        $spreadsheet->getProperties()->setCreator(CRMNAME)
            ->setLastModifiedBy(CRMNAME)
            ->setTitle(CRMNAME)
            ->setSubject('Candidates records')
            ->setDescription('candidates records with their status');

        // add style to the header
        $styleArray = array(
            'font' => array(
                'bold' => true,
            ),
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
                'top' => array(
                    'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ),
            ),
            'fill' => array(
                'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                'rotation' => 90,
                'startcolor' => array(
                    'argb' => 'FFA0A0A0',
                ),
                'endcolor' => array(
                    'argb' => 'FFFFFFFF',
                ),
            ),
        );
        $spreadsheet->getActiveSheet()->getStyle('A1:CV1')->applyFromArray($styleArray);
        // auto fit column to content
        foreach (range('A', 'CV') as $columnID) {
            $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                ->setWidth(20);
        }
        // set the names of header cells

        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue("A1", 'Client Ref No.')
            ->setCellValue("B1", REFNO)
            ->setCellValue("C1", 'Client Name')
            ->setCellValue("D1", 'Entity')
            ->setCellValue("E1", 'Package')
            ->setCellValue("F1", 'Case Initiated')
            ->setCellValue("G1", 'Candidate Name')
            ->setCellValue("H1", 'Overall Status')
            ->setCellValue("I1", 'Overall Closure Date')

            ->setCellValue("J1", 'Address Status 1')
            ->setCellValue("K1", 'Address')

            ->setCellValue("L1", 'Address Status 2')
            ->setCellValue("M1", 'Address')

            ->setCellValue("N1", 'Address Status 3')
            ->setCellValue("O1", 'Address')

            ->setCellValue("P1", 'Address Status 4')
            ->setCellValue("Q1", 'Address')

            ->setCellValue("R1", 'Address Status 5')
            ->setCellValue("S1", 'Address')

            ->setCellValue("T1", 'Employment Status 1')
            ->setCellValue("U1", 'Employer Company Name')

            ->setCellValue("V1", 'Employment Status 2')
            ->setCellValue("W1", 'Employer Company Name')

            ->setCellValue("X1", 'Employment Status 3')
            ->setCellValue("Y1", 'Employer Company Name')

            ->setCellValue("Z1", 'Employment Status 4')
            ->setCellValue("AA1", 'Employer Company Name')

            ->setCellValue("AB1", 'Employment Status 5')
            ->setCellValue("AC1", 'Employer Company Name')

            ->setCellValue("AD1", 'Education Status 1')
            ->setCellValue("AE1", 'Education University')

            ->setCellValue("AF1", 'Education Status 2')
            ->setCellValue("AG1", 'Education University')

            ->setCellValue("AH1", 'Education Status 3')
            ->setCellValue("AI1", 'Education University')

            ->setCellValue("AJ1", 'Education Status 4')
            ->setCellValue("AK1", 'Education University')

            ->setCellValue("AL1", 'Education Status 5')
            ->setCellValue("AM1", 'Education University')

            ->setCellValue("AN1", 'Reference Status 1')
            ->setCellValue("AO1", 'Reference Name')

            ->setCellValue("AP1", 'Reference Status 2')
            ->setCellValue("AQ1", 'Reference Name')

            ->setCellValue("AR1", 'Reference Status 3')
            ->setCellValue("AS1", 'Reference Name')

            ->setCellValue("AT1", 'Reference Status 4')
            ->setCellValue("AU1", 'Reference Name')

            ->setCellValue("AV1", 'Reference Status 5')
            ->setCellValue("AW1", 'Reference Name')

            ->setCellValue("AX1", 'Court Status 1')
            ->setCellValue("AY1", 'Court Address')

            ->setCellValue("AZ1", 'Court Status 2')
            ->setCellValue("BA1", 'Court Address')

            ->setCellValue("BB1", 'Court Status 3')
            ->setCellValue("BC1", 'Court Address')

            ->setCellValue("BD1", 'Court Status 4')
            ->setCellValue("BE1", 'Court Address')

            ->setCellValue("BF1", 'Court Status 5')
            ->setCellValue("BG1", 'Court Address')

            ->setCellValue("BH1", 'Global Status 1')
            ->setCellValue("BI1", 'Global Address')

            ->setCellValue("BJ1", 'Global Status 2')
            ->setCellValue("BK1", 'Global Address')

            ->setCellValue("BL1", 'Global Status 3')
            ->setCellValue("BM1", 'Global Address')

            ->setCellValue("BN1", 'Global Status 4')
            ->setCellValue("BO1", 'Global Address')

            ->setCellValue("BP1", 'Global Status 5')
            ->setCellValue("BQ1", 'Global Address')

            ->setCellValue("BR1", 'PCC Status 1')
            ->setCellValue("BS1", 'PCC Address')

            ->setCellValue("BT1", 'PCC Status 2')
            ->setCellValue("BU1", 'PCC Address')

            ->setCellValue("BV1", 'PCC Status 3')
            ->setCellValue("BW1", 'PCC Address')

            ->setCellValue("BX1", 'PCC Status 4')
            ->setCellValue("BY1", 'PCC Address')

            ->setCellValue("BZ1", 'PCC Status 5')
            ->setCellValue("CA1", 'PCC Address')

            ->setCellValue("CB1", 'Identity Status 1')
            ->setCellValue("CC1", 'Identity Document')

            ->setCellValue("CD1", 'Identity Status 2')
            ->setCellValue("CE1", 'Identity Document')

            ->setCellValue("CF1", 'Identity Status 3')
            ->setCellValue("CG1", 'Identity Document')

            ->setCellValue("CH1", 'Identity Status 4')
            ->setCellValue("CI1", 'Identity Document')

            ->setCellValue("CJ1", 'Identity Status 5')
            ->setCellValue("CK1", 'Identity Document')

            ->setCellValue("CL1", 'Credit Report Status 1')
            ->setCellValue("CM1", 'Credit Report Details')

            ->setCellValue("CN1", 'Credit Report Status 2')
            ->setCellValue("CO1", 'Credit Report Details')

            ->setCellValue("CP1", 'Credit Report Status 3')
            ->setCellValue("CQ1", 'Credit Report Details')

            ->setCellValue("CR1", 'Credit Report Status 4')
            ->setCellValue("CS1", 'Credit Report Details')

            ->setCellValue("CT1", 'Credit Report Status 5')
            ->setCellValue("CU1", 'Credit Report Details')

            ->setCellValue("CV1", 'Insuff Details');
        // Add some data
        $x = 2;
        foreach ($all_records as $all_record) {
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A$x", $all_record['ClientRefNumber'])
                ->setCellValue("B$x", $all_record['cmp_ref_no'])
                ->setCellValue("C$x", $all_record['clientname'])
                ->setCellValue("D$x", $all_record['entity_name'])
                ->setCellValue("E$x", $all_record['package_name'])
                ->setCellValue("F$x", $all_record['caserecddate'])
                ->setCellValue("G$x", $all_record['CandidateName'])
                ->setCellValue("H$x", $all_record['overallstatus'])
                ->setCellValue("I$x", $all_record['overallclosuredate'])
                ->setCellValue("J$x", $all_record['addrver0'])
                ->setCellValue("K$x", $all_record['addrver_address0'])
                ->setCellValue("L$x", $all_record['addrver1'])
                ->setCellValue("M$x", $all_record['addrver_address1'])
                ->setCellValue("N$x", $all_record['addrver2'])
                ->setCellValue("O$x", $all_record['addrver_address2'])
                ->setCellValue("P$x", $all_record['addrver3'])
                ->setCellValue("Q$x", $all_record['addrver_address3'])
                ->setCellValue("R$x", $all_record['addrver4'])
                ->setCellValue("S$x", $all_record['addrver_address4'])
                ->setCellValue("T$x", $all_record['empver0'])
                ->setCellValue("U$x", $all_record['empver_cpm0'])
                ->setCellValue("V$x", $all_record['empver1'])
                ->setCellValue("W$x", $all_record['empver_cpm1'])
                ->setCellValue("X$x", $all_record['empver2'])
                ->setCellValue("Y$x", $all_record['empver_cpm2'])
                ->setCellValue("Z$x", $all_record['empver3'])
                ->setCellValue("AA$x", $all_record['empver_cpm3'])
                ->setCellValue("AB$x", $all_record['empver4'])
                ->setCellValue("AC$x", $all_record['empver_cpm4'])
                ->setCellValue("AD$x", $all_record['eduver0'])
                ->setCellValue("AE$x", $all_record['eduver_univer0'])
                ->setCellValue("AF$x", $all_record['eduver1'])
                ->setCellValue("AG$x", $all_record['eduver_univer1'])
                ->setCellValue("AH$x", $all_record['eduver2'])
                ->setCellValue("AI$x", $all_record['eduver_univer2'])
                ->setCellValue("AJ$x", $all_record['eduver3'])
                ->setCellValue("AK$x", $all_record['eduver_univer3'])
                ->setCellValue("AL$x", $all_record['eduver4'])
                ->setCellValue("AM$x", $all_record['eduver_univer4'])
                ->setCellValue("AN$x", $all_record['refver0'])
                ->setCellValue("AO$x", $all_record['refvername0'])
                ->setCellValue("AP$x", $all_record['refver1'])
                ->setCellValue("AQ$x", $all_record['refvername1'])
                ->setCellValue("AR$x", $all_record['refver2'])
                ->setCellValue("AS$x", $all_record['refvername2'])
                ->setCellValue("AT$x", $all_record['refver3'])
                ->setCellValue("AU$x", $all_record['refvername3'])
                ->setCellValue("AV$x", $all_record['refver4'])
                ->setCellValue("AW$x", $all_record['refvername4'])
                ->setCellValue("AX$x", $all_record['courtver0'])
                ->setCellValue("AY$x", $all_record['courtver_address0'])
                ->setCellValue("AZ$x", $all_record['courtver1'])
                ->setCellValue("BA$x", $all_record['courtver_address1'])
                ->setCellValue("BB$x", $all_record['courtver2'])
                ->setCellValue("BC$x", $all_record['courtver_address2'])
                ->setCellValue("BD$x", $all_record['courtver3'])
                ->setCellValue("BE$x", $all_record['courtver_address3'])
                ->setCellValue("BF$x", $all_record['courtver4'])
                ->setCellValue("BG$x", $all_record['courtver_address4'])
                ->setCellValue("BH$x", $all_record['glodbver0'])
                ->setCellValue("BI$x", $all_record['glodbver_address0'])
                ->setCellValue("BJ$x", $all_record['glodbver1'])
                ->setCellValue("BK$x", $all_record['glodbver_address1'])
                ->setCellValue("BL$x", $all_record['glodbver2'])
                ->setCellValue("BM$x", $all_record['glodbver_address2'])
                ->setCellValue("BN$x", $all_record['glodbver3'])
                ->setCellValue("BO$x", $all_record['glodbver_address3'])
                ->setCellValue("BP$x", $all_record['glodbver4'])
                ->setCellValue("BQ$x", $all_record['glodbver_address4'])
                ->setCellValue("BR$x", $all_record['crimver0'])
                ->setCellValue("BS$x", $all_record['crimver_address0'])
                ->setCellValue("BT$x", $all_record['crimver1'])
                ->setCellValue("BU$x", $all_record['crimver_address1'])
                ->setCellValue("BV$x", $all_record['crimver2'])
                ->setCellValue("BW$x", $all_record['crimver_address2'])
                ->setCellValue("BX$x", $all_record['crimver3'])
                ->setCellValue("BY$x", $all_record['crimver_address3'])
                ->setCellValue("BZ$x", $all_record['crimver4'])
                ->setCellValue("CA$x", $all_record['crimver_address4'])
                ->setCellValue("CB$x", $all_record['identity0'])
                ->setCellValue("CC$x", $all_record['identity_doc0'])
                ->setCellValue("CD$x", $all_record['identity1'])
                ->setCellValue("CE$x", $all_record['identity_doc1'])
                ->setCellValue("CF$x", $all_record['identity2'])
                ->setCellValue("CG$x", $all_record['identity_doc2'])
                ->setCellValue("CH$x", $all_record['identity3'])
                ->setCellValue("CI$x", $all_record['identity_doc3'])
                ->setCellValue("CJ$x", $all_record['identity4'])
                ->setCellValue("CK$x", $all_record['identity_doc4'])
                ->setCellValue("CL$x", $all_record['cbrver0'])
                ->setCellValue("CM$x", $all_record['cbrver_cibil0'])
                ->setCellValue("CN$x", $all_record['cbrver1'])
                ->setCellValue("CO$x", $all_record['cbrver_cibil1'])
                ->setCellValue("CP$x", $all_record['cbrver2'])
                ->setCellValue("CQ$x", $all_record['cbrver_cibil2'])
                ->setCellValue("CR$x", $all_record['cbrver3'])
                ->setCellValue("CS$x", $all_record['cbrver_cibil3'])
                ->setCellValue("CT$x", $all_record['cbrver4'])
                ->setCellValue("CU$x", $all_record['cbrver_cibil4'])
                ->setCellValue("CV$x", $all_record['Details']);

            $x++;
        }
        // Rename worksheet
        $spreadsheet->getActiveSheet()->setTitle('Candidate Records');

        $spreadsheet->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=Candidates Records of .xlsx");
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Excel2007');

        $file_name = "Candidates_Records_of_" . $client_name . "_" . DATE(UPLOAD_FILE_DATE_FORMAT) . ".xls";

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Excel2007');
        ob_start();
        $writer->save($file_upload_path . "/" . $file_name);
        ob_end_clean();

        $this->update_request_data(array('file_name' => $file_name, 'folder_generated_status' => 1, 'folder_name' => $file_upload_path), array('id' => $parameters_array));

        $this->update_request_schedular_list(array('report_id' => $parameters_array, 'file_name' => $file_name, 'run_status' => 1, 'last_run_on' => date(DB_DATE_FORMAT)), array('id' => '6'));

    }

    public function export_to_excel_prepost($parameters_array = null, $client_id, $entity_id, $package_id, $client_name)
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $this->load->model('report_generated_user');

        $file_upload_path = SITE_BASE_PATH . UPLOAD_FOLDER . 'bulk_reports';

        if (!folder_exist($file_upload_path)) {
            mkdir($file_upload_path, 0777);
        }
        if (!folder_exist($file_upload_path)) {
            mkdir($file_upload_path, 0777);
        } else if (!is_writable($file_upload_path)) {
            mkdir($file_upload_path, 0777);
        }

        
        $all_records = $this->get_all_client_data_for_export_prepost($client_id, $entity_id, $package_id);
     
      
        require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
        // Create new Spreadsheet object
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        // Set document properties
        $spreadsheet->getProperties()->setCreator(CRMNAME)
            ->setLastModifiedBy(CRMNAME)
            ->setTitle(CRMNAME)
            ->setSubject('Candidates records')
            ->setDescription('candidates records with their status');

        // add style to the header
        $styleArray = array(
            'font' => array(
                'bold' => true,
            ),
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
                'top' => array(
                    'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ),
            ),
            'fill' => array(
                'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                'rotation' => 90,
                'startcolor' => array(
                    'argb' => 'FFA0A0A0',
                ),
                'endcolor' => array(
                    'argb' => 'FFFFFFFF',
                ),
            ),
        );

          $styleborder = array(
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ),
                'borders' => array(
                    'allborders' => array(
                        'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ),
                ),
            );


            $spreadsheet->createSheet();
            $work_sheet_count=1;
            $work_sheet = 0;

            while($work_sheet<=$work_sheet_count)
            {

                if($work_sheet==0)
                {
                    if($client_id == 3 || $client_id == 4 || $client_id == 5)
                    {
                        $spreadsheet->getActiveSheet()->getStyle('A1:T1')->applyFromArray($styleArray);
                        $spreadsheet->getActiveSheet()->getStyle("A1:T1")->applyFromArray($styleborder);

                        // auto fit column to content
                        foreach(range('A','T') as $columnID) {
                            $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setWidth(20);
                        }
                        // set the names of header cells

                        $spreadsheet->setActiveSheetIndex($work_sheet)

                        ->setCellValue("A1",'Client Ref No.')
                        ->setCellValue("B1",'CRM Ref No.')
                        ->setCellValue("C1",'Client Name')
                        ->setCellValue("D1",'Entity')
                        ->setCellValue("E1",'Spoc/Pack')
                        ->setCellValue("F1",'Case Initiated')
                        ->setCellValue("G1",'Latest Initiation Date')
                        ->setCellValue("H1",'Candidate Name')
                        ->setCellValue("I1",'Overall Status')
                        ->setCellValue("J1",'Overall Closure Date')
                        ->setCellValue("K1",'Identity 1')
                        ->setCellValue("L1",'Identity 2')
                        ->setCellValue("M1",'Identity 3')
                        ->setCellValue("N1",'Court 1')
                        ->setCellValue("O1",'Court 2')
                        ->setCellValue("P1",'Court 3')
                        ->setCellValue("Q1",'Discrepancy Details')
                        ->setCellValue("R1",'Insuff Details')
                        ->setCellValue("S1",'Insuff Raised Date')
                        ->setCellValue("T1",'Insuff Clear Date');
 
                        // Add some data
                        $x = 2;
                        foreach ($all_records as $all_record) {


                        $spreadsheet->getActiveSheet()->getStyle("A$x:T$x")->applyFromArray($styleborder); 
                        $spreadsheet->setActiveSheetIndex($work_sheet);
                            
                        if(($all_record['identity0'] != "NA" && $all_record['identity0'] != "N/A") || ($all_record['identity1'] != "NA" && $all_record['identity1'] != "N/A") || ($all_record['identity2'] != "NA" && $all_record['identity2'] != "N/A") || ($all_record['courtver0'] != "NA" && $all_record['courtver0'] != "N/A") || ($all_record['courtver1'] != "NA" && $all_record['courtver1'] != "N/A") || ($all_record['courtver2'] != "NA" && $all_record['courtver2'] != "N/A"))
                        {

                         $this->cellColorStatus("I$x",$all_record['final_status_pre'],$spreadsheet);
                         $this->cellColorStatus("K$x",$all_record['identity0'],$spreadsheet);
                         $this->cellColorStatus("L$x",$all_record['identity1'],$spreadsheet);
                         $this->cellColorStatus("M$x",$all_record['identity2'],$spreadsheet);
                         $this->cellColorStatus("N$x",$all_record['courtver0'],$spreadsheet);
                         $this->cellColorStatus("O$x",$all_record['courtver1'],$spreadsheet);
                         $this->cellColorStatus("P$x",$all_record['courtver2'],$spreadsheet);

                            $spreadsheet->getActiveSheet()->setCellValue("A$x",$all_record['ClientRefNumber']);
                            $spreadsheet->getActiveSheet()->setCellValue("B$x",$all_record['cmp_ref_no']);
                            $spreadsheet->getActiveSheet()->setCellValue("C$x",$all_record['clientname']);
                            $spreadsheet->getActiveSheet()->setCellValue("D$x",$all_record['entity_name']); 
                            $spreadsheet->getActiveSheet()->setCellValue("E$x",$all_record['package_name']); 
                            $spreadsheet->getActiveSheet()->setCellValue("F$x",$all_record['case_initiation_pre_min']); 
                            $spreadsheet->getActiveSheet()->setCellValue("G$x",$all_record['case_initiation_pre_max']);
                            $spreadsheet->getActiveSheet()->setCellValue("H$x",$all_record['CandidateName']);
                            $spreadsheet->getActiveSheet()->setCellValue("I$x", $this->rename_status_value($all_record['final_status_pre']));
                            $spreadsheet->getActiveSheet()->setCellValue("J$x",$all_record['overallclosuredate_pre']);
                            $spreadsheet->getActiveSheet()->setCellValue("K$x",$this->rename_status_value($all_record['identity0']));
                            $spreadsheet->getActiveSheet()->setCellValue("L$x",$this->rename_status_value($all_record['identity1']));
                            $spreadsheet->getActiveSheet()->setCellValue("M$x",$this->rename_status_value($all_record['identity2']));
                            $spreadsheet->getActiveSheet()->setCellValue("N$x",$this->rename_status_value($all_record['courtver0']));
                            $spreadsheet->getActiveSheet()->setCellValue("O$x",$this->rename_status_value($all_record['courtver1'])); 
                            $spreadsheet->getActiveSheet()->setCellValue("P$x",$this->rename_status_value($all_record['courtver2']));
                            $spreadsheet->getActiveSheet()->setCellValue("Q$x",$all_record['DiscrepancyDetails_pre']);
                            $spreadsheet->getActiveSheet()->setCellValue("R$x",$all_record['Details_pre']);
                            $spreadsheet->getActiveSheet()->setCellValue("S$x",$all_record['insuff_raise_date_pre']);
                            $spreadsheet->getActiveSheet()->setCellValue("T$x",$all_record['insuff_clear_date_pre']);
                         
                            
                            $x++;
                            }
  
                        }

                        $sheetData1 = $spreadsheet->getActiveSheet()->toArray(null,true,true,true); 

                        $sheetData2  =  count($sheetData1); 

                        $sheetData_pre   =   $sheetData2 - 1; 

                        $spreadsheet->getActiveSheet()->setTitle('Pre -'.$sheetData_pre);
 
                        $spreadsheet->setActiveSheetIndex($work_sheet);
                    }
                    if($client_id == 33)
                    {
                        $spreadsheet->getActiveSheet()->getStyle('A1:T1')->applyFromArray($styleArray);
                        $spreadsheet->getActiveSheet()->getStyle("A1:T1")->applyFromArray($styleborder);


                        // auto fit column to content
                        foreach(range('A','T') as $columnID) {
                            $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setWidth(20);
                        }
                        // set the names of header cells

                        $spreadsheet->setActiveSheetIndex($work_sheet)

                        ->setCellValue("A1",'Client Ref No.')
                        ->setCellValue("B1",'CRM Ref No.')
                        ->setCellValue("C1",'Client Name')
                        ->setCellValue("D1",'Entity')
                        ->setCellValue("E1",'Spoc/Pack')
                        ->setCellValue("F1",'Case Initiated')
                        ->setCellValue("G1",'Latest Initiation Date')
                        ->setCellValue("H1",'Candidate Name')
                        ->setCellValue("I1",'Overall Status')
                        ->setCellValue("J1",'Overall Closure Date')
                        ->setCellValue("K1",'Address 1')
                        ->setCellValue("L1",'Address 2')
                        ->setCellValue("M1",'Address 3')
                        ->setCellValue("N1",'Court 1')
                        ->setCellValue("O1",'Court 2')
                        ->setCellValue("P1",'Court 3')
                        ->setCellValue("Q1",'Discrepancy Details')
                        ->setCellValue("R1",'Insuff Details')
                        ->setCellValue("S1",'Insuff Raised Date')
                        ->setCellValue("T1",'Insuff Clear Date');
 
                        // Add some data
                        $x = 2;
                        foreach ($all_records as $all_record) {

                        $spreadsheet->getActiveSheet()->getStyle("A$x:T$x")->applyFromArray($styleborder);    

                        $spreadsheet->setActiveSheetIndex($work_sheet);

                        if(($all_record['addrver0'] != "NA" && $all_record['addrver0'] != "N/A") || 
                            ($all_record['addrver1'] != "NA" && $all_record['addrver1'] != "N/A") ||
                            ($all_record['addrver2'] != "NA" && $all_record['addrver2'] != "N/A") ||
                            ($all_record['courtver0'] != "NA" && $all_record['courtver0'] != "N/A") || ($all_record['courtver1'] != "NA" && $all_record['courtver1'] != "N/A") || ($all_record['courtver2'] != "NA" && $all_record['courtver2'] != "N/A"))
                        {

                         $this->cellColorStatus("I$x",$all_record['final_status_pre'],$spreadsheet);
                         $this->cellColorStatus("K$x",$all_record['addrver0'],$spreadsheet);
                         $this->cellColorStatus("L$x",$all_record['addrver1'],$spreadsheet);
                         $this->cellColorStatus("M$x",$all_record['addrver2'],$spreadsheet);
                         $this->cellColorStatus("N$x",$all_record['courtver0'],$spreadsheet);
                         $this->cellColorStatus("O$x",$all_record['courtver1'],$spreadsheet);
                         $this->cellColorStatus("P$x",$all_record['courtver2'],$spreadsheet);
                            
                            $spreadsheet->getActiveSheet()->setCellValue("A$x",$all_record['ClientRefNumber']);
                            $spreadsheet->getActiveSheet()->setCellValue("B$x",$all_record['cmp_ref_no']);
                            $spreadsheet->getActiveSheet()->setCellValue("C$x",$all_record['clientname']);
                            $spreadsheet->getActiveSheet()->setCellValue("D$x",$all_record['entity_name']); 
                            $spreadsheet->getActiveSheet()->setCellValue("E$x",$all_record['package_name']); 
                            $spreadsheet->getActiveSheet()->setCellValue("F$x",$all_record['case_initiation_pre_min']); 
                            $spreadsheet->getActiveSheet()->setCellValue("G$x",$all_record['case_initiation_pre_max']);
                            $spreadsheet->getActiveSheet()->setCellValue("H$x",$all_record['CandidateName']);
                            $spreadsheet->getActiveSheet()->setCellValue("I$x",$this->rename_status_value($all_record['final_status_pre']));
                            $spreadsheet->getActiveSheet()->setCellValue("J$x",$all_record['overallclosuredate_pre']);
                            $spreadsheet->getActiveSheet()->setCellValue("K$x",$this->rename_status_value($all_record['addrver0']));
                            $spreadsheet->getActiveSheet()->setCellValue("L$x",$this->rename_status_value($all_record['addrver1']));
                            $spreadsheet->getActiveSheet()->setCellValue("M$x",$this->rename_status_value($all_record['addrver2']));
                            $spreadsheet->getActiveSheet()->setCellValue("N$x",$this->rename_status_value($all_record['courtver0']));
                            $spreadsheet->getActiveSheet()->setCellValue("O$x",$this->rename_status_value($all_record['courtver1'])); 
                            $spreadsheet->getActiveSheet()->setCellValue("P$x",$this->rename_status_value($all_record['courtver2']));
                            $spreadsheet->getActiveSheet()->setCellValue("Q$x",$all_record['DiscrepancyDetails_pre']);
                            $spreadsheet->getActiveSheet()->setCellValue("R$x",$all_record['Details_pre']);
                            $spreadsheet->getActiveSheet()->setCellValue("S$x",$all_record['insuff_raise_date_pre']);
                            $spreadsheet->getActiveSheet()->setCellValue("T$x",$all_record['insuff_clear_date_pre']);
                            

                            $x++;
                            }
  
                        }

                        $sheetData1 = $spreadsheet->getActiveSheet()->toArray(null,true,true,true); 

                        $sheetData2  =  count($sheetData1); 

                        $sheetData_pre   =   $sheetData2 - 1; 

                        $spreadsheet->getActiveSheet()->setTitle('Pre -'.$sheetData_pre);
 
                        $spreadsheet->setActiveSheetIndex($work_sheet);
                    }
                }
                if($work_sheet==1)
                {  
                  
                    if($client_id == 3 || $client_id == 4 || $client_id == 5)
                    {  

                    $styleArray = array(
                        'font' => array(
                            'bold' => true,
                        ),
                        'alignment' => array(
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ),
                        'borders' => array(
                            'top' => array(
                                'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            ),
                        ),
                        'fill' => array(
                            'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                            'rotation' => 90,
                            'startcolor' => array(
                                'argb' => 'FFA0A0A0',
                            ),
                            'endcolor' => array(
                                'argb' => 'FFFFFFFF',
                            ),
                        ),
                    );

                      $styleborder = array(
                            'alignment' => array(
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ),
                            'borders' => array(
                                'allborders' => array(
                                    'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                ),
                            ), 
                        );


                        $spreadsheet->getActiveSheet()->getStyle("V1:Z1")->applyFromArray($styleArray);
                        $spreadsheet->getActiveSheet()->getStyle("V1:Z1")->applyFromArray($styleborder);


                        // auto fit column to content
                        foreach(range('A','W') as $columnID) {
                            $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setWidth(20);
                        }
                        // set the names of header cells

                        $spreadsheet->setActiveSheetIndex($work_sheet)

                            ->setCellValue("A1",'Client Ref No.')
                            ->setCellValue("B1",'CRM Ref No.')
                            ->setCellValue("C1",'Client Name')
                            ->setCellValue("D1",'Entity')
                            ->setCellValue("E1",'Spoc/Pack')
                            ->setCellValue("F1",'Case Initiated')
                            ->setCellValue("G1",'Latest Initiation Date')
                            ->setCellValue("H1",'Candidate Name')
                            ->setCellValue("I1",'Overall Status')
                            ->setCellValue("J1",'Overall Closure Date')
                            ->setCellValue("K1",'Address 1')
                            ->setCellValue("L1",'Address 2')
                            ->setCellValue("M1",'Address 3')
                            ->setCellValue("N1",'Employment 1')
                            ->setCellValue("O1",'Employment 2')
                            ->setCellValue("P1",'Employment 3')
                            ->setCellValue("Q1",'Discrepancy Details')
                            ->setCellValue("R1",'Insuff Details')
                            ->setCellValue("S1",'Insuff Raised Date')
                            ->setCellValue("T1",'Insuff Clear Date')
                            ->setCellValue("U1",'Company Name 1')
                            ->setCellValue("V1",'Company Name 2')
                            ->setCellValue("W1",'Company Name 3');
     
                        // Add some data
                        $x = 2;
                        foreach ($all_records as $all_record) {

                            $spreadsheet->getActiveSheet()->getStyle("A$x:W$x")->applyFromArray($styleborder);    


                            $spreadsheet->setActiveSheetIndex($work_sheet);

                        if(($all_record['addrver0'] != "NA" &&  $all_record['addrver0'] != "N/A") || 
                            ($all_record['addrver1'] != "NA" && $all_record['addrver1'] != "N/A") || 
                            ($all_record['addrver2'] != "NA" && $all_record['addrver2'] != "N/A") || 
                            ($all_record['empver0'] != "NA" && $all_record['empver0'] != "N/A") ||
                            ($all_record['empver1'] != "NA" && $all_record['empver1'] != "N/A") || 
                            ($all_record['empver2'] != "NA" && $all_record['empver2'] != "N/A"))
                        {

                         $this->cellColorStatus("I$x",$all_record['final_status_post'],$spreadsheet);
                         $this->cellColorStatus("K$x",$all_record['addrver0'],$spreadsheet);
                         $this->cellColorStatus("L$x",$all_record['addrver1'],$spreadsheet);
                         $this->cellColorStatus("M$x",$all_record['addrver2'],$spreadsheet);
                         $this->cellColorStatus("N$x",$all_record['empver0'],$spreadsheet);
                         $this->cellColorStatus("O$x",$all_record['empver1'],$spreadsheet);
                         $this->cellColorStatus("P$x",$all_record['empver2'],$spreadsheet);
                            
                            $spreadsheet->getActiveSheet()->setCellValue("A$x",$all_record['ClientRefNumber']);
                            $spreadsheet->getActiveSheet()->setCellValue("B$x",$all_record['cmp_ref_no']);
                            $spreadsheet->getActiveSheet()->setCellValue("C$x",$all_record['clientname']);
                            $spreadsheet->getActiveSheet()->setCellValue("D$x",$all_record['entity_name']); 
                            $spreadsheet->getActiveSheet()->setCellValue("E$x",$all_record['package_name']); 
                            $spreadsheet->getActiveSheet()->setCellValue("F$x",$all_record['case_initiation_post_min']); 
                            $spreadsheet->getActiveSheet()->setCellValue("G$x",$all_record['case_initiation_post_max']);
                            $spreadsheet->getActiveSheet()->setCellValue("H$x",$all_record['CandidateName']);
                            $spreadsheet->getActiveSheet()->setCellValue("I$x",$this->rename_status_value($all_record['final_status_post']));
                            $spreadsheet->getActiveSheet()->setCellValue("J$x",$all_record['overallclosuredate_post']);
                            $spreadsheet->getActiveSheet()->setCellValue("K$x",$this->rename_status_value($all_record['addrver0']));
                            $spreadsheet->getActiveSheet()->setCellValue("L$x",$this->rename_status_value($all_record['addrver1']));
                            $spreadsheet->getActiveSheet()->setCellValue("M$x",$this->rename_status_value($all_record['addrver2']));
                            $spreadsheet->getActiveSheet()->setCellValue("N$x",$this->rename_status_value($all_record['empver0']));
                            $spreadsheet->getActiveSheet()->setCellValue("O$x",$this->rename_status_value($all_record['empver1'])); 
                            $spreadsheet->getActiveSheet()->setCellValue("P$x",$this->rename_status_value($all_record['empver2']));
                            $spreadsheet->getActiveSheet()->setCellValue("Q$x",$all_record['DiscrepancyDetails_post']);
                            $spreadsheet->getActiveSheet()->setCellValue("R$x",$all_record['Details_post']);
                            $spreadsheet->getActiveSheet()->setCellValue("S$x",$all_record['insuff_raise_date_post']);
                            $spreadsheet->getActiveSheet()->setCellValue("T$x",$all_record['insuff_clear_date_post']);
                            $spreadsheet->getActiveSheet()->setCellValue("U$x",$all_record['coname0']);
                            $spreadsheet->getActiveSheet()->setCellValue("V$x",$all_record['coname1']);
                            $spreadsheet->getActiveSheet()->setCellValue("W$x",$all_record['coname2']);
                            
                            $x++;

                            }
  
                        }

                        $sheetData1 = $spreadsheet->getActiveSheet()->toArray(null,true,true,true); 

                        $sheetData2  =  count($sheetData1); 

                        $sheetData_post   =   $sheetData2 - 1; 

                        $spreadsheet->getActiveSheet()->setTitle('Post -'.$sheetData_post);
 
                        $spreadsheet->setActiveSheetIndex($work_sheet);
                    }

                    if($client_id == 33)
                    {  
                        $styleArray = array(
                            'font' => array(
                                'bold' => true,
                            ),
                            'alignment' => array(
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ),
                            'borders' => array(
                                'top' => array(
                                    'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                ),
                            ),
                            'fill' => array(
                                'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                                'rotation' => 90,
                                'startcolor' => array(
                                    'argb' => 'FFA0A0A0',
                                ),
                                'endcolor' => array(
                                    'argb' => 'FFFFFFFF',
                                ),
                            ),
                        );

                      $styleborder = array(
                            'alignment' => array(
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ),
                            'borders' => array(
                                'allborders' => array(
                                    'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                ),
                            ),
                        );
  

                        $spreadsheet->getActiveSheet()->getStyle("V1:Z1")->applyFromArray($styleArray);
                        $spreadsheet->getActiveSheet()->getStyle("V1:Z1")->applyFromArray($styleborder);


                        // auto fit column to content
                        foreach(range('A','T') as $columnID) {
                            $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setWidth(20);
                        }
                        // set the names of header cells

                        $spreadsheet->setActiveSheetIndex($work_sheet)

                            ->setCellValue("A1",'Client Ref No.')
                            ->setCellValue("B1",'CRM Ref No.')
                            ->setCellValue("C1",'Client Name')
                            ->setCellValue("D1",'Entity')
                            ->setCellValue("E1",'Spoc/Pack')
                            ->setCellValue("F1",'Case Initiated')
                            ->setCellValue("G1",'Latest Initiation Date')
                            ->setCellValue("H1",'Candidate Name')
                            ->setCellValue("I1",'Overall Status')
                            ->setCellValue("J1",'Overall Closure Date')
                            ->setCellValue("K1",'Employment 1')
                            ->setCellValue("L1",'Employment 2')
                            ->setCellValue("M1",'Employment 3')
                            ->setCellValue("N1",'Discrepancy Details')
                            ->setCellValue("O1",'Insuff Details')
                            ->setCellValue("P1",'Insuff Raised Date')
                            ->setCellValue("Q1",'Insuff Clear Date')
                            ->setCellValue("R1",'Company Name 1')
                            ->setCellValue("S1",'Company Name 2')
                            ->setCellValue("T1",'Company Name 3');
     
                        // Add some data
                        $x = 2;
                        foreach ($all_records as $all_record) {

                            $spreadsheet->getActiveSheet()->getStyle("A$x:T$x")->applyFromArray($styleborder);    


                            $spreadsheet->setActiveSheetIndex($work_sheet);

                        if(($all_record['empver0'] != "NA" &&  $all_record['empver0'] != "N/A") ||
                            ($all_record['empver1'] != "NA" && $all_record['empver1'] != "N/A") || 
                            ($all_record['empver2'] != "NA" && $all_record['empver2'] != "N/A"))
                        {

                         $this->cellColorStatus("I$x",$all_record['final_status_post'],$spreadsheet);
                         $this->cellColorStatus("K$x",$all_record['empver0'],$spreadsheet);
                         $this->cellColorStatus("L$x",$all_record['empver1'],$spreadsheet);
                         $this->cellColorStatus("M$x",$all_record['empver2'],$spreadsheet);
                            
                           
                            $spreadsheet->getActiveSheet()->setCellValue("A$x",$all_record['ClientRefNumber']);
                            $spreadsheet->getActiveSheet()->setCellValue("B$x",$all_record['cmp_ref_no']);
                            $spreadsheet->getActiveSheet()->setCellValue("C$x",$all_record['clientname']);
                            $spreadsheet->getActiveSheet()->setCellValue("D$x",$all_record['entity_name']); 
                            $spreadsheet->getActiveSheet()->setCellValue("E$x",$all_record['package_name']); 
                            $spreadsheet->getActiveSheet()->setCellValue("F$x",$all_record['case_initiation_post_min']); 
                            $spreadsheet->getActiveSheet()->setCellValue("G$x",$all_record['case_initiation_post_max']);
                            $spreadsheet->getActiveSheet()->setCellValue("H$x",$all_record['CandidateName']);
                            $spreadsheet->getActiveSheet()->setCellValue("I$x",$this->rename_status_value($all_record['final_status_post']));
                            $spreadsheet->getActiveSheet()->setCellValue("J$x",$all_record['overallclosuredate_post']);
                            $spreadsheet->getActiveSheet()->setCellValue("K$x",$this->rename_status_value($all_record['empver0']));
                            $spreadsheet->getActiveSheet()->setCellValue("L$x",$this->rename_status_value($all_record['empver1']));
                            $spreadsheet->getActiveSheet()->setCellValue("M$x",$this->rename_status_value($all_record['empver2']));
                            $spreadsheet->getActiveSheet()->setCellValue("N$x",$all_record['DiscrepancyDetails_post']);
                            $spreadsheet->getActiveSheet()->setCellValue("O$x",$all_record['Details_post']);
                            $spreadsheet->getActiveSheet()->setCellValue("P$x",$all_record['insuff_raise_date_post']);
                            $spreadsheet->getActiveSheet()->setCellValue("Q$x",$all_record['insuff_clear_date_post']);
                            $spreadsheet->getActiveSheet()->setCellValue("R$x",$all_record['coname0']);
                            $spreadsheet->getActiveSheet()->setCellValue("S$x",$all_record['coname1']);
                            $spreadsheet->getActiveSheet()->setCellValue("T$x",$all_record['coname2']);
                           
                           

                            $x++;
                            }
  
                        }

                        $sheetData1 = $spreadsheet->getActiveSheet()->toArray(null,true,true,true); 

                        $sheetData2  =  count($sheetData1); 

                        $sheetData_post   =   $sheetData2 - 1; 

                        $spreadsheet->getActiveSheet()->setTitle('Post -'.$sheetData_post);
 
                        $spreadsheet->setActiveSheetIndex($work_sheet);
                    }
                }

                $work_sheet++;
            }
        // Rename worksheet
        //$spreadsheet->getActiveSheet()->setTitle('Candidate Records');

      //  $spreadsheet->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=Candidates Records of .xlsx");
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Excel2007');

        $file_name = "Candidates_Records_of_" . $client_name . "_" . DATE(UPLOAD_FILE_DATE_FORMAT) . ".xls";

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Excel2007');
        ob_start();
        $writer->save($file_upload_path . "/" . $file_name);
        ob_end_clean();

        $this->update_request_data(array('file_name' => $file_name, 'folder_generated_status' => 1, 'folder_name' => $file_upload_path), array('id' => $parameters_array));

        $this->update_request_schedular_list(array('report_id' => $parameters_array, 'file_name' => $file_name, 'run_status' => 1, 'last_run_on' => date(DB_DATE_FORMAT)), array('id' => '1'));

    }

    public function get_all_client_data_for_export($clientid, $entity_id, $package_id, $fil_by_status, $fil_by_sub_status)
    {

        $this->load->model('candidates_model');

        $data_arry = array();

        $cands_results = $this->candidates_model->get_all_cand_with_search($clientid, $entity_id, $package_id, $fil_by_status, $fil_by_sub_status);

        $x = 0;
        foreach ($cands_results as $key => $cands_result) {

            $data_arry[$x]['id'] = $x + 1;
            $data_arry[$x]['candsid'] = $cands_result['id'];
            $data_arry[$x]['CandidateName'] = ucwords(strtolower($cands_result['CandidateName']));
            $data_arry[$x]['ClientRefNumber'] = $cands_result['ClientRefNumber'];
            $data_arry[$x]['cmp_ref_no'] = $cands_result['cmp_ref_no'];
            $data_arry[$x]['overallstatus'] = $cands_result['status_value'];
            $data_arry[$x]['entity_name'] = $cands_result['entity_name'];
            $data_arry[$x]['package_name'] = $cands_result['package_name'];
            $data_arry[$x]['overallstatus'] = $cands_result['status_value'];
            $data_arry[$x]['clientname'] = ucwords(strtolower($cands_result['clientname']));
            $data_arry[$x]['caserecddate'] = convert_db_to_display_date($cands_result['caserecddate']);
            $data_arry[$x]['overallclosuredate'] = ($cands_result['overallclosuredate'] != "") ? convert_db_to_display_date($cands_result['overallclosuredate']) : 'NA';

            for ($i = 0; $i < 5; $i++) {

                $data_arry[$x]["addrver$i"] = 'NA';
                $data_arry[$x]["addrver_address$i"] = 'NA';
                $data_arry[$x]["empver$i"] = 'NA';
                $data_arry[$x]["empver_cpm$i"] = 'NA';
                $data_arry[$x]["eduver$i"] = 'NA';
                $data_arry[$x]["eduver_univer$i"] = 'NA';
                $data_arry[$x]["refver$i"] = 'NA';
                $data_arry[$x]["refvername$i"] = 'NA';
                $data_arry[$x]["courtver$i"] = 'NA';
                $data_arry[$x]["courtver_address$i"] = 'NA';
                $data_arry[$x]["crimver$i"] = 'NA';
                $data_arry[$x]["crimver_address$i"] = 'NA';
                $data_arry[$x]["glodbver$i"] = 'NA';
                $data_arry[$x]["glodbver_address$i"] = 'NA';
                $data_arry[$x]["identity$i"] = 'NA';
                $data_arry[$x]["identity_doc$i"] = 'NA';
                $data_arry[$x]["cbrver$i"] = 'NA';
                $data_arry[$x]["cbrver_cibil$i"] = 'NA';

            }

            $component_id = explode(",", $cands_result['component_id']);

            $rename_status = array('NA' => 'N/A');

            $insufficiency_details = array();

            if (in_array('addrver', $component_id)) {
                $result = $this->candidates_model->get_addres_ver_status_for_export(array('addrver.candsid' => $cands_result['id']));

                if (!empty($result)) {
                    $counter = 1;
                    foreach ($result as $key => $value) {
                        if ($counter > 5) {
                            continue;
                        }

                        if (array_key_exists($value['verfstatus'], $rename_status)) {
                            $data_arry[$x]["addrver$key"] = $rename_status[$value['verfstatus']];
                        } else {
                            $data_arry[$x]["addrver$key"] = ($value['verfstatus'] != "") ? $value['verfstatus'] : 'WIP';
                        }

                        if (($value['verfstatus'] == "Insufficiency") || ($value['verfstatus'] == "insufficiency")) {

                            array_push($insufficiency_details, "Add " . $counter . " || " . $value['address'] . ',' . $value['city'] . ',' . $value['pincode'] . ',' . $value['state'] . " || " . $value['insuff_raise_remark']);

                        }

                        $data_arry[$x]["addrver_address$key"] = ($value['address'] != "") ? $value['address'] : 'NA';
                        $counter++;
                    }
                }
            }

            if (in_array('eduver', $component_id)) {
                $result = $this->candidates_model->get_education_ver_status_for_export(array('education.candsid' => $cands_result['id']));
                if (!empty($result)) {

                    $counter = 1;
                    foreach ($result as $key => $value) {
                        if ($counter > 5) {
                            continue;
                        }

                        if (array_key_exists($value['verfstatus'], $rename_status)) {
                            $data_arry[$x]["eduver$key"] = $rename_status[$value['verfstatus']];
                        } else {
                            $data_arry[$x]["eduver$key"] = ($value['verfstatus'] != "") ? $value['verfstatus'] : 'WIP';
                        }

                        if (($value['verfstatus'] == "Insufficiency") || ($value['verfstatus'] == "insufficiency")) {
                            array_push($insufficiency_details, "Edu " . $counter . " || " . $value['universityname'] . ',' . $value['qualification_name'] . " || " . $value['insuff_raise_remark']);
                        }

                        $data_arry[$x]["eduver_univer$key"] = ($value['universityname'] != "") ? $value['universityname'] : 'NA';
                        $counter++;
                    }
                }
            }

            if (in_array('empver', $component_id)) {
                $result = $this->candidates_model->get_employment_ver_status_for_export(array('empver.candsid' => $cands_result['id']));
                if (!empty($result)) {
                    $counter = 1;
                    foreach ($result as $key => $value) {
                        if ($counter > 5) {
                            continue;
                        }

                        if (array_key_exists($value['verfstatus'], $rename_status)) {
                            $data_arry[$x]["empver$key"] = $rename_status[$value['verfstatus']];
                        } else {
                            $data_arry[$x]["empver$key"] = ($value['verfstatus'] != "") ? $value['verfstatus'] : 'WIP';
                        }

                        if (($value['verfstatus'] == "Insufficiency") || ($value['verfstatus'] == "insufficiency")) {

                            array_push($insufficiency_details, "Emp " . $counter . " || " . $value['coname'] . " || " . $value['insuff_raise_remark']);
                        }

                        $data_arry[$x]["empver_cpm$key"] = ($value['coname'] != "") ? $value['coname'] : 'NA';

                        $counter++;
                    }
                }
            }

            if (in_array('refver', $component_id)) {
                $result = $this->candidates_model->get_refver_ver_status_for_export(array('reference.candsid' => $cands_result['id']));
                if (!empty($result)) {
                    $counter = 1;
                    foreach ($result as $key => $value) {
                        if ($counter > 5) {
                            continue;
                        }

                        if (array_key_exists($value['verfstatus'], $rename_status)) {
                            $data_arry[$x]["refver$key"] = $rename_status[$value['verfstatus']];
                        } else {
                            $data_arry[$x]["refver$key"] = ($value['verfstatus'] != "") ? $value['verfstatus'] : 'WIP';
                        }

                        if (($value['verfstatus'] == "Insufficiency") || ($value['verfstatus'] == "insufficiency")) {

                            array_push($insufficiency_details, "Ref " . $counter . " || " . $value['name_of_reference'] . " || " . $value['insuff_raise_remark']);
                        }

                        $data_arry[$x]["refvername$key"] = ($value['name_of_reference'] != "") ? $value['name_of_reference'] : 'NA';
                        $counter++;
                    }
                }
            }

            if (in_array('courtver', $component_id)) {
                $result = $this->candidates_model->get_court_ver_status_for_export(array('courtver.candsid' => $cands_result['id']));
                if (!empty($result)) {
                    $counter = 1;
                    foreach ($result as $key => $value) {
                        if ($counter > 5) {
                            continue;
                        }

                        if (array_key_exists($value['verfstatus'], $rename_status)) {
                            $data_arry[$x]["courtver$key"] = $rename_status[$value['verfstatus']];
                        } else {
                            $data_arry[$x]["courtver$key"] = ($value['verfstatus'] != "") ? $value['verfstatus'] : 'WIP';
                        }

                        if (($value['verfstatus'] == "Insufficiency") || ($value['verfstatus'] == "insufficiency")) {

                            array_push($insufficiency_details, "Court " . $counter . " || " . $value['street_address'] . ',' . $value['city'] . ',' . $value['pincode'] . ',' . $value['state'] . " || " . $value['insuff_raise_remark']);
                        }

                        $data_arry[$x]["courtver_address$key"] = ($value['street_address'] != "") ? $value['street_address'] : 'NA';
                        $counter++;
                    }
                }
            }
            if (in_array('crimver', $component_id)) {
                $result = $this->candidates_model->get_pcc_ver_status_for_export(array('pcc.candsid' => $cands_result['id']));
                if (!empty($result)) {

                    $counter = 1;
                    foreach ($result as $key => $value) {
                        if ($counter > 5) {
                            continue;
                        }

                        if (array_key_exists($value['verfstatus'], $rename_status)) {
                            $data_arry[$x]["crimver$key"] = $rename_status[$value['verfstatus']];
                        } else {
                            $data_arry[$x]["crimver$key"] = ($value['verfstatus'] != "") ? $value['verfstatus'] : 'WIP';
                        }

                        if (($value['verfstatus'] == "Insufficiency") || ($value['verfstatus'] == "insufficiency")) {

                            array_push($insufficiency_details, "PCC " . $counter . " || " . $value['street_address'] . ',' . $value['city'] . ',' . $value['pincode'] . ',' . $value['state'] . " || " . $value['insuff_raise_remark']);
                        }

                        $data_arry[$x]["crimver_address$key"] = ($value['street_address'] != "") ? $value['street_address'] : 'NA';
                        $counter++;
                    }
                }
            }

            if (in_array('globdbver', $component_id)) {
                $result = $this->candidates_model->get_globdbver_ver_status_for_export(array('glodbver.candsid' => $cands_result['id']));
                if (!empty($result)) {

                    $counter = 1;
                    foreach ($result as $key => $value) {
                        if ($counter > 5) {
                            continue;
                        }

                        if (array_key_exists($value['verfstatus'], $rename_status)) {
                            $data_arry[$x]["glodbver$key"] = $rename_status[$value['verfstatus']];
                        } else {
                            $data_arry[$x]["glodbver$key"] = ($value['verfstatus'] != "") ? $value['verfstatus'] : 'WIP';
                        }

                        if (($value['verfstatus'] == "Insufficiency") || ($value['verfstatus'] == "insufficiency")) {

                            array_push($insufficiency_details, "Global Database " . $counter . " || " . $value['street_address'] . ',' . $value['city'] . ',' . $value['pincode'] . ',' . $value['state'] . " || " . $value['insuff_raise_remark']);
                        }

                        $data_arry[$x]["glodbver_address$key"] = ($value['street_address'] != "") ? $value['street_address'] : 'NA';
                        $counter++;
                    }
                }
            }
            if (in_array('identity', $component_id)) {
                $result = $this->candidates_model->get_identity_ver_status_for_export(array('identity.candsid' => $cands_result['id']));

                if (!empty($result)) {

                    $counter = 1;
                    foreach ($result as $key => $value) {
                        if ($counter > 5) {
                            continue;
                        }

                        if (array_key_exists($value['verfstatus'], $rename_status)) {
                            $data_arry[$x]["identity$key"] = $rename_status[$value['verfstatus']];
                        } else {
                            $data_arry[$x]["identity$key"] = ($value['verfstatus'] != "") ? $value['verfstatus'] : 'WIP';
                        }

                        if (($value['verfstatus'] == "Insufficiency") || ($value['verfstatus'] == "insufficiency")) {

                            array_push($insufficiency_details, "Identity " . $counter . " || " . $value['doc_submited'] . " || " . $value['insuff_raise_remark']);
                        }

                        $data_arry[$x]["identity_doc$key"] = ($value['doc_submited'] != "") ? $value['doc_submited'] : 'NA';

                        $counter++;
                    }
                }
            }

            if (in_array('cbrver', $component_id)) {
                $result = $this->candidates_model->get_credit_report_ver_status_for_export(array('credit_report.candsid' => $cands_result['id']));

                if (!empty($result)) {

                    $counter = 1;
                    foreach ($result as $key => $value) {

                        if ($counter > 5) {

                            continue;
                        }

                        if (array_key_exists($value['verfstatus'], $rename_status)) {
                            $data_arry[$x]["cbrver$key"] = $rename_status[$value['verfstatus']];
                        } else {
                            $data_arry[$x]["cbrver$key"] = ($value['verfstatus'] != "") ? $value['verfstatus'] : 'WIP';
                        }

                        if (($value['verfstatus'] == "Insufficiency") || ($value['verfstatus'] == "insufficiency")) {

                            array_push($insufficiency_details, "Credit Report " . $counter . " || " . " Cibil " . " || " . $value['insuff_raise_remark']);
                        }

                        $data_arry[$x]["cbrver_cibil$key"] = 'Cibil';

                        $counter++;
                    }

                }
            }

            $insufficiencys_details = array();
            foreach ($insufficiency_details as $key => $value) {
                $insufficiencys_details[] = $value . ",";
            }

            $data_arry[$x]['Details'] = implode('', $insufficiencys_details);
            unset($insufficiencys_details);

            $x++;
        }

        return $data_arry;
    }

    public function get_all_client_data_for_export_prepost($clientid, $entity_id, $package_id)
    {

        $data_arry = array();

        $this->load->model('candidates_model');

        $cands_results = $this->candidates_model->get_all_cand_with_search_prepost($clientid, $entity_id, $package_id);

  
        $x = 0;
        foreach ($cands_results as $key => $cands_result) {

            $data_arry[$x]['id'] = $x + 1;
            $data_arry[$x]['candsid'] = $cands_result['id'];
            $data_arry[$x]['CandidateName'] = ucwords(strtolower($cands_result['CandidateName']));
            $data_arry[$x]['ClientRefNumber'] = $cands_result['ClientRefNumber'];
            $data_arry[$x]['cmp_ref_no'] = $cands_result['cmp_ref_no'];
            $data_arry[$x]['overallstatus'] = $cands_result['status_value'];
            $data_arry[$x]['entity_name'] = $cands_result['entity_name'];
            $data_arry[$x]['package_name'] = $cands_result['package_name'];
            $data_arry[$x]['clientname'] = ucwords(strtolower($cands_result['clientname']));
            $data_arry[$x]['caserecddate'] = convert_db_to_display_date($cands_result['caserecddate']);
            $data_arry[$x]['overallclosuredate'] = ($cands_result['overallclosuredate'] != "") ? convert_db_to_display_date($cands_result['overallclosuredate']) : 'NA';

            if($cands_result['clientid'] == 3 || $cands_result['clientid'] == 4 || $cands_result['clientid'] == 5){

                for ($i = 0; $i < 3; $i++) {

                    $data_arry[$x]["addrver$i"] = 'NA';
                    $data_arry[$x]["empver$i"] = 'NA';
                    $data_arry[$x]["courtver$i"] = 'NA';
                    $data_arry[$x]["identity$i"] = 'NA';
                    $data_arry[$x]["coname$i"] = 'NA';
                }


                $rename_status = array('NA' => 'N/A');

                
                $case_initiation_pre = array();
                  
                $case_initiation_post = array();  

                $overall_status_pre = array();

                $overall_closure_date_pre = array();

                $discrepancy_details_pre = array();

                $insufficiency_details_pre = array();

                $discrepancy_details_post = array();

                $insufficiency_details_post = array();

                $insuff_raise_pre = array();

                $insuff_clear_pre = array();

                $insuff_raise_post = array();

                $insuff_clear_post = array();

                $component_status_pre = array();

                $component_status_post = array();

              
                    $result = $this->candidates_model->get_addres_ver_status_for_export(array('addrver.candsid' => $cands_result['id']));
                    
                    $result_insuff = $this->candidates_model->get_address_insuff_details(array('addrver.candsid' => $cands_result['id']));

                    if (!empty($result)) {
                        $counter = 1;
                        foreach ($result as $key => $value) {
                            if ($counter > 3) {
                                continue;
                            }

                            if (array_key_exists($value['report_status'], $rename_status)) {
                                $data_arry[$x]["addrver$key"] = $rename_status[$value['report_status']];
                            } else {
                                $data_arry[$x]["addrver$key"] = ($value['report_status'] != "") ? $value['report_status'] : 'WIP';
                            }

                            if (($value['verfstatus'] == "Major Discrepancy") || ($value['verfstatus'] == "major discrepancy")) {

                                array_push($discrepancy_details_post, "Add " . $counter . " - " . $value['address'] . ',' . $value['city'] . ',' . $value['pincode'] . ',' . $value['state'] . " - " . $value['remarks']);

                            }

                            if (($value['verfstatus'] == "Insufficiency") || ($value['verfstatus'] == "insufficiency")) {

                                array_push($insufficiency_details_post, "Add " . $counter . " - " . $value['address'] . ',' . $value['city'] . ',' . $value['pincode'] . ',' . $value['state'] . " - " . $value['insuff_raise_remark']);

                            }

                            $statuss = ($value['verfstatus'] != "") ? $value['verfstatus'] : 'WIP';
                            $component_status_post[] = array($statuss, $value['closuredate']);

                            $counter++;
                        }
                        if(!empty($result_insuff))
                        { 
                            foreach ($result_insuff as  $value_insuff) {
                                if(!empty($value_insuff['insuff_raised_date']))
                                {
                                array_push($insuff_raise_post, convert_db_to_display_date($value_insuff['insuff_raised_date']));
                                }
                                if(!empty($value_insuff['insuff_clear_date']))
                                {
                                array_push($insuff_clear_post, convert_db_to_display_date($value_insuff['insuff_clear_date']));
                                }
                            }
                        }

                        foreach ($result as $key => $value_initiation) {

                            array_push($case_initiation_post, $value_initiation['iniated_date']); 
                        }

                    }
                
                
                
                    $result = $this->candidates_model->get_employment_ver_status_for_export(array('empver.candsid' => $cands_result['id']));

                    $result_insuff = $this->candidates_model->get_employment_insuff_details(array('empver.candsid' => $cands_result['id']));

                    if (!empty($result)) {
                        $counter = 1;
                        foreach ($result as $key => $value) {
                            if ($counter > 3) {
                                continue;
                            }

                            if (array_key_exists($value['report_status'], $rename_status)) {
                                $data_arry[$x]["empver$key"] = $rename_status[$value['report_status']];
                            } else {
                                $data_arry[$x]["empver$key"] = ($value['report_status'] != "") ? $value['report_status'] : 'WIP';
                            }

                            if (($value['verfstatus'] == "Major Discrepancy") || ($value['verfstatus'] == "major discrepancy")) {

                                array_push($discrepancy_details_post, "Emp " . $counter . " - " . $value['coname'] . " - " . $value['remarks']);

                            }

                            if (($value['verfstatus'] == "Insufficiency") || ($value['verfstatus'] == "insufficiency")) {

                                array_push($insufficiency_details_post, "Emp " . $counter . " - " . $value['coname'] . " - " . $value['insuff_raise_remark']);
                            }

                            $data_arry[$x]["coname$key"] = $value['coname']; 

                            $statuss = ($value['verfstatus'] != "") ? $value['verfstatus'] : 'WIP';
                            $component_status_post[] = array($statuss, $value['closuredate']);

                            $counter++;
                        }
                        if(!empty($result_insuff))
                        { 
                            foreach ($result_insuff as  $value_insuff) {
                                if(!empty($value_insuff['insuff_raised_date']))
                                {
                                array_push($insuff_raise_post, convert_db_to_display_date($value_insuff['insuff_raised_date']));
                                }
                                if(!empty($value_insuff['insuff_clear_date']))
                                {
                                array_push($insuff_clear_post, convert_db_to_display_date($value_insuff['insuff_clear_date']));
                                }
                            }
                        }

                        foreach ($result as $key => $value_initiation) {

                            array_push($case_initiation_post, $value_initiation['iniated_date']); 
                        }
                    }
                
     
              
              
                    $result = $this->candidates_model->get_court_ver_status_for_export(array('courtver.candsid' => $cands_result['id']));

                    $result_insuff = $this->candidates_model->get_court_insuff_details(array('courtver.candsid' => $cands_result['id']));

                    if (!empty($result)) {
                        $counter = 1;
                        foreach ($result as $key => $value) {
                            if ($counter > 3) {
                                continue;
                            }

                            if (array_key_exists($value['report_status'], $rename_status)) {
                                $data_arry[$x]["courtver$key"] = $rename_status[$value['report_status']];
                            } else {
                                $data_arry[$x]["courtver$key"] = ($value['report_status'] != "") ? $value['report_status'] : 'WIP';
                            }

                            if (($value['verfstatus'] == "Major Discrepancy") || ($value['verfstatus'] == "major discrepancy")) {

                                array_push($discrepancy_details_pre, "Court " . $counter . " - " . $value['street_address'] . ',' . $value['city'] . ',' . $value['pincode'] . ',' . $value['state'] . " - " . $value['remarks']);
                            }

                            if (($value['verfstatus'] == "Insufficiency") || ($value['verfstatus'] == "insufficiency")) {

                                array_push($insufficiency_details_pre, "Court " . $counter . " - " . $value['street_address'] . ',' . $value['city'] . ',' . $value['pincode'] . ',' . $value['state'] . " - " . $value['insuff_raise_remark']);
                            }

                            $statuss = ($value['verfstatus'] != "") ? $value['verfstatus'] : 'WIP';
                            $component_status_pre[] = array($statuss, $value['closuredate']);

                            $counter++;
                        }
                        if(!empty($result_insuff))
                        { 
                            foreach ($result_insuff as  $value_insuff) {
                                if(!empty($value_insuff['insuff_raised_date']))
                                {
                                array_push($insuff_raise_pre, convert_db_to_display_date($value_insuff['insuff_raised_date']));
                                }
                                if(!empty($value_insuff['insuff_clear_date']))
                                {
                                array_push($insuff_clear_pre, convert_db_to_display_date($value_insuff['insuff_clear_date']));
                                }
                            }
                        }

                        foreach ($result as $key => $value_initiation) {

                            array_push($case_initiation_pre, $value_initiation['iniated_date']); 
                        }
                    }
                
               
               
                    $result = $this->candidates_model->get_identity_ver_status_for_export(array('identity.candsid' => $cands_result['id']));

                    $result_insuff = $this->candidates_model->get_identity_insuff_details(array('identity.candsid' => $cands_result['id']));
                    

                    if (!empty($result)) {

                        $counter = 1;
                        foreach ($result as $key => $value) {
                            if ($counter > 3) {
                                continue;
                            }

                            if (array_key_exists($value['report_status'], $rename_status)) {
                                $data_arry[$x]["identity$key"] = $rename_status[$value['report_status']];
                            } else {
                                $data_arry[$x]["identity$key"] = ($value['report_status'] != "") ? $value['report_status'] : 'WIP';
                            }

                            if (($value['verfstatus'] == "Major Discrepancy") || ($value['verfstatus'] == "major discrepancy")) {

                                array_push($discrepancy_details_pre, "Identity " . $counter . " - " . $value['doc_submited'] . " - " . $value['remarks']);
                            }

                            if (($value['verfstatus'] == "Insufficiency") || ($value['verfstatus'] == "insufficiency")) {

                                array_push($insufficiency_details_pre, "Identity " . $counter . " - " . $value['doc_submited'] . " - " . $value['insuff_raise_remark']);
                            }
                           
                            $statuss = ($value['verfstatus'] != "") ? $value['verfstatus'] : 'WIP';
                            $component_status_pre[] = array($statuss, $value['closuredate']);
                           
                            $counter++;
                        }
                        if(!empty($result_insuff))
                        { 
                            foreach ($result_insuff as  $value_insuff) {
                                if(!empty($value_insuff['insuff_raised_date']))
                                {
                                array_push($insuff_raise_pre, convert_db_to_display_date($value_insuff['insuff_raised_date']));
                                }
                                if(!empty($value_insuff['insuff_clear_date']))
                                {
                                array_push($insuff_clear_pre, convert_db_to_display_date($value_insuff['insuff_clear_date']));
                                }
                            }
                        }

                        foreach ($result as $key => $value_initiation) {

                            array_push($case_initiation_pre, $value_initiation['iniated_date']); 
                        }
                    }
                

                if(!empty($case_initiation_pre))
                {    
                  $case_initiation_pre_max_value = max($case_initiation_pre); 
                }
                else
                {
                  $case_initiation_pre_max_value = '';  
                }

                $data_arry[$x]['case_initiation_pre_max'] = $case_initiation_pre_max_value;

                if(!empty($case_initiation_pre))
                {    
                  $case_initiation_pre_min_value = min($case_initiation_pre); 
                }
                else
                {
                  $case_initiation_pre_min_value = '';  
                }

                $data_arry[$x]['case_initiation_pre_min'] = $case_initiation_pre_min_value;


                if(!empty($case_initiation_post))
                {    
                  $case_initiation_post_max_value = max($case_initiation_post); 
                }
                else
                {
                  $case_initiation_post_max_value = '';  
                }

                $data_arry[$x]['case_initiation_post_max'] = $case_initiation_post_max_value;

                if(!empty($case_initiation_post))
                {    
                  $case_initiation_post_min_value = min($case_initiation_post); 
                }
                else
                {
                  $case_initiation_post_min_value = '';  
                }

                $data_arry[$x]['case_initiation_post_min'] = $case_initiation_post_min_value;
               
                
                $insufficiencys_details_pre = array();
                foreach ($insufficiency_details_pre as $key => $value) {
                    $insufficiencys_details_pre[] = $value . " || ";
                }

                $discrepancys_details_pre = array();
                foreach ($discrepancy_details_pre as $key => $value) {
                    $discrepancys_details_pre[] = $value . " || ";
                }

                $insufficiencys_details_post = array();
                foreach ($insufficiency_details_post as $key => $value) {
                    $insufficiencys_details_post[] = $value . " || ";
                }

                $discrepancys_details_post = array();
                foreach ($discrepancy_details_post as $key => $value) {
                    $discrepancys_details_post[] = $value . " || ";
                }

                asort($insuff_raise_pre);
              
                $insuff_raise_date_pre = '';
                foreach ($insuff_raise_pre as $key => $value) {
                    $insuff_raise_date_pre = $value;
                    break;
                }
                
                arsort($insuff_clear_pre);

                $insuff_clear_date_pre = '';
                foreach ($insuff_clear_pre as $key => $value) {
                    $insuff_clear_date_pre = $value;
                    break;
                }

                asort($insuff_raise_post);
              
                $insuff_raise_date_post = '';
                foreach ($insuff_raise_post as $key => $value) {
                    $insuff_raise_date_post = $value;
                    break;
                }
                
                arsort($insuff_clear_post);

            

                $insuff_clear_date_post = '';
                foreach ($insuff_clear_post as $key => $value) {
                    $insuff_clear_date_post = $value;
                    break;
                }

                

                $data_arry[$x]['Details_pre'] = implode('', $insufficiencys_details_pre);
                unset($insufficiencys_details_pre);

                $data_arry[$x]['DiscrepancyDetails_pre'] = implode('', $discrepancys_details_pre);
                unset($discrepancys_details_pre);

                $data_arry[$x]['Details_post'] = implode('', $insufficiencys_details_post);
                unset($insufficiencys_details_post);

                $data_arry[$x]['DiscrepancyDetails_post'] = implode('', $discrepancys_details_post);
                unset($discrepancys_details_post);



                $data_arry[$x]['insuff_raise_date_pre'] = $insuff_raise_date_pre;
               // unset($insuff_raise_date_pre);


                $data_arry[$x]['insuff_clear_date_pre'] = $insuff_clear_date_pre;
              //  unset($insuff_clear_date_pre);

                $data_arry[$x]['insuff_raise_date_post'] = $insuff_raise_date_post;
              //  unset($insuff_raise_date_post);


                $data_arry[$x]['insuff_clear_date_post'] = $insuff_clear_date_post;
              //  unset($insuff_clear_date_post);
          

                $component_status_pre = $this->array_flatten($component_status_pre);
   
                $dates_pre = $component_status_pre['dates'];
                $component_status_pre = $component_status_pre['status'];

                if(!empty($dates_pre))
                {
                  $max_date_pre = max($dates_pre);
                }
                else
                {
                  $max_date_pre = NULL;  
                }
                  
                $component_status_post = $this->array_flatten($component_status_post);

                $dates_post = $component_status_post['dates'];
                $component_status_post = $component_status_post['status'];
                if(!empty($dates_post))
                {
                  $max_date_post = max($dates_post);
                }
                else
                {
                  $max_date_post = NULL;  
                }

                $check_status = array('Major Discrepancy', 'Minor Discrepancy', 'Unable to verify', 'Stop Check', 'Clear');

                if (!in_array("WIP", $component_status_pre) && !in_array("Insufficiency", $component_status_pre) && !in_array("Insufficiency Cleared", $component_status_pre) && !in_array("Final QC Reject", $component_status_pre) && !in_array("First QC Reject", $component_status_pre) && !in_array("New Check", $component_status_pre) && !in_array("YTR", $component_status_pre) && !in_array("Follow Up", $component_status_pre)) {

                    foreach ($check_status as $key => $value) {

                        if (in_array($value, $component_status_pre)) {
              
                            if ($value == "Major Discrepancy") {
                                $actual_status_pre = 'Major Discrepancy';
                                $data_arry[$x]['final_status_pre'] =  $cands_result['status_value'];
                                $data_arry[$x]['overallclosuredate_pre'] = $max_date_pre;
                                break;
                            } elseif ($value == "Minor Discrepancy") {
                                $actual_status_pre = 'Minor Discrepancy';
                                $data_arry[$x]['final_status_pre'] =  $actual_status_pre;
                                $data_arry[$x]['overallclosuredate_pre'] = $max_date_pre;
                                break;
                            } elseif ($value == "Unable to verify") {
                                $actual_status_pre = 'Unable to verify';
                                $data_arry[$x]['final_status_pre'] =  $actual_status_pre;
                                $data_arry[$x]['overallclosuredate_pre'] = $max_date_pre;
                                break;
                            } elseif ($value == "Stop Check") {
                                $actual_status_pre = 'Stop Check';
                                $data_arry[$x]['final_status_pre'] =  $actual_status_pre;
                                $data_arry[$x]['overallclosuredate_pre'] = $max_date_pre;
                                break;
                            } elseif ($value == "Clear") {
                                $actual_status_pre = 'Clear';
                                $data_arry[$x]['final_status_pre'] =  $actual_status_pre;
                                $data_arry[$x]['overallclosuredate_pre'] = $max_date_pre;
                                break;
                            }
                            else
                            {
                                $actual_status_pre = 'NA';
                                $data_arry[$x]['final_status_pre'] =  $actual_status_pre;
                                $data_arry[$x]['overallclosuredate_pre'] = $max_date_pre;
                                break; 
                            }
  
                        }
                    }
                }
              
                if (in_array("Insufficiency", $component_status_pre)) {
                    $data_arry[$x]['final_status_pre'] =  "Insufficiency";
                 
                    $data_arry[$x]['overallclosuredate_pre'] = ""; 
                }
                if (in_array("WIP", $component_status_pre) || in_array("Insufficiency Cleared", $component_status_pre) || in_array("Final QC Reject", $component_status_pre) ||in_array("First QC Reject", $component_status_pre) || in_array("New Check", $component_status_pre) || in_array("YTR", $component_status_pre) || in_array("Follow Up", $component_status_pre)) {
                    $data_arry[$x]['final_status_pre'] =  "WIP";
                 
                    $data_arry[$x]['overallclosuredate_pre'] = ""; 
                }
        
                if (($cands_result['status_value'] == "NA") || ($cands_result['status_value'] == "Stop Check") || ($cands_result['status_value'] == "Clear") || ($cands_result['status_value'] == "Major Discrepancy") || ($cands_result['status_value'] == "Minor Discrepancy") || ($cands_result['status_value'] == "Unable to verify")) {
                
                  
                   
                    $data_arry[$x]['final_status_post'] =  $cands_result['status_value'];
                 
                    $data_arry[$x]['overallclosuredate_post'] = $max_date_post;
                       
                       
                    
                }

                if ($cands_result['status_value'] == "Insufficiency") {
                    $data_arry[$x]['final_status_post'] =  "Insufficiency";
                 
                    $data_arry[$x]['overallclosuredate_post'] = ""; 
                }
                if ($cands_result['status_value'] == "WIP") {
                    $data_arry[$x]['final_status_post'] =  "WIP";
                 
                    $data_arry[$x]['overallclosuredate_post'] = ""; 
                }
       
                $x++;
            }
            
            if($cands_result['clientid'] == 33){

                for ($i = 0; $i < 3; $i++) {

                    $data_arry[$x]["addrver$i"] = 'NA';
                    $data_arry[$x]["empver$i"] = 'NA';
                    $data_arry[$x]["courtver$i"] = 'NA';
                    $data_arry[$x]["coname$i"] = 'NA';
                   
                }


                $rename_status = array('NA' => 'N/A');

                
                $case_initiation_pre = array();
                  
                $case_initiation_post = array();  

                $overall_status_pre = array();

                $overall_closure_date_pre = array();

                $discrepancy_details_pre = array();

                $insufficiency_details_pre = array();

                $discrepancy_details_post = array();

                $insufficiency_details_post = array();

                $insuff_raise_pre = array();

                $insuff_clear_pre = array();

                $insuff_raise_post = array();

                $insuff_clear_post = array();

                $component_status_pre = array();

                $component_status_post = array();

                
                    $result = $this->candidates_model->get_addres_ver_status_for_export(array('addrver.candsid' => $cands_result['id']));
                    
                    $result_insuff = $this->candidates_model->get_address_insuff_details(array('addrver.candsid' => $cands_result['id']));

                    if (!empty($result)) {
                        $counter = 1;
                        foreach ($result as $key => $value) {
                            if ($counter > 3) {
                                continue;
                            }

                            if (array_key_exists($value['report_status'], $rename_status)) {
                                $data_arry[$x]["addrver$key"] = $rename_status[$value['report_status']];
                            } else {
                                $data_arry[$x]["addrver$key"] = ($value['report_status'] != "") ? $value['report_status'] : 'WIP';
                            }

                            if (($value['verfstatus'] == "Major Discrepancy") || ($value['verfstatus'] == "major discrepancy")) {

                                array_push($discrepancy_details_pre, "Add " . $counter . " - " . $value['address'] . ',' . $value['city'] . ',' . $value['pincode'] . ',' . $value['state'] . " - " . $value['remarks']);

                            }

                            if (($value['verfstatus'] == "Insufficiency") || ($value['verfstatus'] == "insufficiency")) {

                                array_push($insufficiency_details_pre, "Add " . $counter . " - " . $value['address'] . ',' . $value['city'] . ',' . $value['pincode'] . ',' . $value['state'] . " - " . $value['insuff_raise_remark']);

                            }

                            $statuss = ($value['verfstatus'] != "") ? $value['verfstatus'] : 'WIP';
                            $component_status_pre[] = array($statuss, $value['closuredate']);

                            $counter++;
                        }
                        if(!empty($result_insuff))
                        { 
                            foreach ($result_insuff as  $value_insuff) {
                                if(!empty($value_insuff['insuff_raised_date']))
                                {
                                array_push($insuff_raise_pre, convert_db_to_display_date($value_insuff['insuff_raised_date']));
                                }
                                if(!empty($value_insuff['insuff_clear_date']))
                                {
                                array_push($insuff_clear_pre, convert_db_to_display_date($value_insuff['insuff_clear_date']));
                                }
                            }
                        }

                        foreach ($result as $key => $value_initiation) {

                            array_push($case_initiation_pre, $value_initiation['iniated_date']); 
                        }

                    }
             
                    $result = $this->candidates_model->get_employment_ver_status_for_export(array('empver.candsid' => $cands_result['id']));

                    $result_insuff = $this->candidates_model->get_employment_insuff_details(array('empver.candsid' => $cands_result['id']));

                    if (!empty($result)) {
                        $counter = 1;
                        foreach ($result as $key => $value) {
                            if ($counter > 3) {
                                continue;
                            }

                            if (array_key_exists($value['report_status'], $rename_status)) {
                                $data_arry[$x]["empver$key"] = $rename_status[$value['report_status']];
                            } else {
                                $data_arry[$x]["empver$key"] = ($value['report_status'] != "") ? $value['report_status'] : 'WIP';
                            }

                            if (($value['verfstatus'] == "Major Discrepancy") || ($value['verfstatus'] == "major discrepancy")) {

                                array_push($discrepancy_details_post, "Emp " . $counter . " - " . $value['coname'] . " - " . $value['remarks']);

                            }

                            if (($value['verfstatus'] == "Insufficiency") || ($value['verfstatus'] == "insufficiency")) {

                                array_push($insufficiency_details_post, "Emp " . $counter . " - " . $value['coname'] . " - " . $value['insuff_raise_remark']);
                            }

                            $data_arry[$x]["coname$key"] = $value['coname']; 


                            $statuss = ($value['verfstatus'] != "") ? $value['verfstatus'] : 'WIP';
                            $component_status_post[] = array($statuss, $value['closuredate']);

                            $counter++;
                        }
                        if(!empty($result_insuff))
                        { 
                            foreach ($result_insuff as  $value_insuff) {
                                if(!empty($value_insuff['insuff_raised_date']))
                                {
                                array_push($insuff_raise_post, convert_db_to_display_date($value_insuff['insuff_raised_date']));
                                }
                                if(!empty($value_insuff['insuff_clear_date']))
                                {
                                array_push($insuff_clear_post, convert_db_to_display_date($value_insuff['insuff_clear_date']));
                                }
                            }
                        }

                        foreach ($result as $key => $value_initiation) {

                            array_push($case_initiation_post, $value_initiation['iniated_date']); 
                        }
                    }
              
                    $result = $this->candidates_model->get_court_ver_status_for_export(array('courtver.candsid' => $cands_result['id']));

                    $result_insuff = $this->candidates_model->get_court_insuff_details(array('courtver.candsid' => $cands_result['id']));

                    if (!empty($result)) {
                        $counter = 1;
                        foreach ($result as $key => $value) {
                            if ($counter > 3) {
                                continue;
                            }

                            if (array_key_exists($value['report_status'], $rename_status)) {
                                $data_arry[$x]["courtver$key"] = $rename_status[$value['report_status']];
                            } else {
                                $data_arry[$x]["courtver$key"] = ($value['report_status'] != "") ? $value['report_status'] : 'WIP';
                            }

                            if (($value['verfstatus'] == "Major Discrepancy") || ($value['verfstatus'] == "major discrepancy")) {

                                array_push($discrepancy_details_pre, "Court " . $counter . " - " . $value['street_address'] . ',' . $value['city'] . ',' . $value['pincode'] . ',' . $value['state'] . " - " . $value['remarks']);
                            }

                            if (($value['verfstatus'] == "Insufficiency") || ($value['verfstatus'] == "insufficiency")) {

                                array_push($insufficiency_details_pre, "Court " . $counter . " - " . $value['street_address'] . ',' . $value['city'] . ',' . $value['pincode'] . ',' . $value['state'] . " - " . $value['insuff_raise_remark']);
                            }

                            $statuss = ($value['verfstatus'] != "") ? $value['verfstatus'] : 'WIP';
                            $component_status_pre[] = array($statuss, $value['closuredate']);

                            $counter++;
                        }
                        if(!empty($result_insuff))
                        { 
                            foreach ($result_insuff as  $value_insuff) {
                                if(!empty($value_insuff['insuff_raised_date']))
                                {
                                array_push($insuff_raise_pre, convert_db_to_display_date($value_insuff['insuff_raised_date']));
                                }
                                if(!empty($value_insuff['insuff_clear_date']))
                                {
                                array_push($insuff_clear_pre, convert_db_to_display_date($value_insuff['insuff_clear_date']));
                                }
                            }
                        }

                        foreach ($result as $key => $value_initiation) {

                            array_push($case_initiation_pre, $value_initiation['iniated_date']); 
                        }
                    }
                
               
               
                if(!empty($case_initiation_pre))
                {    
                  $case_initiation_pre_max_value = max($case_initiation_pre); 
                }
                else
                {
                  $case_initiation_pre_max_value = '';  
                }

                $data_arry[$x]['case_initiation_pre_max'] = $case_initiation_pre_max_value;

                if(!empty($case_initiation_pre))
                {    
                  $case_initiation_pre_min_value = min($case_initiation_pre); 
                }
                else
                {
                  $case_initiation_pre_min_value = '';  
                }

                $data_arry[$x]['case_initiation_pre_min'] = $case_initiation_pre_min_value;


                if(!empty($case_initiation_post))
                {    
                  $case_initiation_post_max_value = max($case_initiation_post); 
                }
                else
                {
                  $case_initiation_post_max_value = '';  
                }

                $data_arry[$x]['case_initiation_post_max'] = $case_initiation_post_max_value;

                if(!empty($case_initiation_post))
                {    
                  $case_initiation_post_min_value = min($case_initiation_post); 
                }
                else
                {
                  $case_initiation_post_min_value = '';  
                }

                $data_arry[$x]['case_initiation_post_min'] = $case_initiation_post_min_value;
               
                
                $insufficiencys_details_pre = array();
                foreach ($insufficiency_details_pre as $key => $value) {
                    $insufficiencys_details_pre[] = $value . " || ";
                }

                $discrepancys_details_pre = array();
                foreach ($discrepancy_details_pre as $key => $value) {
                    $discrepancys_details_pre[] = $value . " || ";
                }

                $insufficiencys_details_post = array();
                foreach ($insufficiency_details_post as $key => $value) {
                    $insufficiencys_details_post[] = $value . " || ";
                }

                $discrepancys_details_post = array();
                foreach ($discrepancy_details_post as $key => $value) {
                    $discrepancys_details_post[] = $value . " || ";
                }

                asort($insuff_raise_pre);
              
                $insuff_raise_date_pre = '';
                foreach ($insuff_raise_pre as $key => $value) {
                    $insuff_raise_date_pre = $value;
                    break;
                }
                
                arsort($insuff_clear_pre);

                $insuff_clear_date_pre = '';
                foreach ($insuff_clear_pre as $key => $value) {
                    $insuff_clear_date_pre = $value;
                    break;
                }

                asort($insuff_raise_post);
              
                $insuff_raise_date_post = '';
                foreach ($insuff_raise_post as $key => $value) {
                    $insuff_raise_date_post = $value;
                    break;
                }
                
                arsort($insuff_clear_post);

                $insuff_clear_date_post = '';
                foreach ($insuff_clear_post as $key => $value) {
                    $insuff_clear_date_post = $value;
                    break;
                }
          

                $data_arry[$x]['Details_pre'] = implode('', $insufficiencys_details_pre);
                unset($insufficiencys_details_pre);

                $data_arry[$x]['DiscrepancyDetails_pre'] = implode('', $discrepancys_details_pre);
                unset($discrepancys_details_pre);

                $data_arry[$x]['Details_post'] = implode('', $insufficiencys_details_post);
                unset($insufficiencys_details_post);

                $data_arry[$x]['DiscrepancyDetails_post'] = implode('', $discrepancys_details_post);
                unset($discrepancys_details_post);



                $data_arry[$x]['insuff_raise_date_pre'] = $insuff_raise_date_pre;
               // unset($insuff_raise_date_pre);


                $data_arry[$x]['insuff_clear_date_pre'] = $insuff_clear_date_pre;
                //unset($insuff_clear_date_pre);

                $data_arry[$x]['insuff_raise_date_post'] = $insuff_raise_date_post;
               // unset($insuff_raise_date_post);


                $data_arry[$x]['insuff_clear_date_post'] = $insuff_clear_date_post;
               // unset($insuff_clear_date_post);


                $component_status_pre = $this->array_flatten($component_status_pre);

                $dates_pre = $component_status_pre['dates'];
                $component_status_pre = $component_status_pre['status'];
                if(!empty($dates_pre))
                {
                  $max_date_pre = max($dates_pre);
                }
                else
                {
                  $max_date_pre = NULL;  
                }
                  
                $component_status_post = $this->array_flatten($component_status_post);

                $dates_post = $component_status_post['dates'];
                $component_status_post = $component_status_post['status'];
                if(!empty($dates_post))
                {
                  $max_date_post = max($dates_post);
                }
                else
                {
                  $max_date_post = NULL;  
                }

                $check_status = array('Major Discrepancy', 'Minor Discrepancy', 'Unable to verify', 'Stop Check', 'Clear');

                if (!in_array("WIP", $component_status_pre) && !in_array("Insufficiency", $component_status_pre) && !in_array("Insufficiency Cleared", $component_status_pre) && !in_array("Final QC Reject", $component_status_pre) && !in_array("First QC Reject", $component_status_pre) && !in_array("New Check", $component_status_pre) && !in_array("YTR", $component_status_pre) && !in_array("Follow Up", $component_status_pre)) {

                    foreach ($check_status as $key => $value) {
            
                        if (in_array($value, $component_status_pre)) {
              
                            if ($value == "Major Discrepancy") {
                                $actual_status_pre = 'Major Discrepancy';
                                $data_arry[$x]['final_status_pre'] =  $cands_result['status_value'];
                                $data_arry[$x]['overallclosuredate_pre'] = $max_date_pre;
                                break;
                            } elseif ($value == "Minor Discrepancy") {
                                $actual_status_pre = 'Minor Discrepancy';
                                $data_arry[$x]['final_status_pre'] =  $actual_status_pre;
                                $data_arry[$x]['overallclosuredate_pre'] = $max_date_pre;
                                break;
                            } elseif ($value == "Unable to verify") {
                                $actual_status_pre = 'Unable to verify';
                                $data_arry[$x]['final_status_pre'] =  $actual_status_pre;
                                $data_arry[$x]['overallclosuredate_pre'] = $max_date_pre;
                                break;
                            } elseif ($value == "Stop Check") {
                                $actual_status_pre = 'Stop Check';
                                $data_arry[$x]['final_status_pre'] =  $actual_status_pre;
                                $data_arry[$x]['overallclosuredate_pre'] = $max_date_pre;
                                break;
                            } elseif ($value == "Clear") {
                                $actual_status_pre = 'Clear';
                                $data_arry[$x]['final_status_pre'] =  $actual_status_pre;
                                $data_arry[$x]['overallclosuredate_pre'] = $max_date_pre;
                                break;
                            }
                            else
                            {
                                $actual_status_pre = 'NA';
                                $data_arry[$x]['final_status_pre'] =  $actual_status_pre;
                                $data_arry[$x]['overallclosuredate_pre'] = $max_date_pre;
                                break; 
                            }
  
                        }
                    }

                }

                if (in_array("Insufficiency", $component_status_pre)) {
                    $data_arry[$x]['final_status_pre'] =  "Insufficiency";
                 
                    $data_arry[$x]['overallclosuredate_pre'] = ""; 
                }
                if (in_array("WIP", $component_status_pre) || in_array("Insufficiency Cleared", $component_status_pre) || in_array("Final QC Reject", $component_status_pre) ||in_array("First QC Reject", $component_status_pre) || in_array("New Check", $component_status_pre) || in_array("YTR", $component_status_pre) || in_array("Follow Up", $component_status_pre)) {
                    $data_arry[$x]['final_status_pre'] =  "WIP";
                 
                    $data_arry[$x]['overallclosuredate_pre'] = ""; 
                }
        

                    if (($cands_result['status_value'] == "NA") || ($cands_result['status_value'] == "Stop Check") || ($cands_result['status_value'] == "Clear") || ($cands_result['status_value'] == "Major Discrepancy") || ($cands_result['status_value'] == "Minor Discrepancy") || ($cands_result['status_value'] == "Unable to verify")) {
                
                  
                   
                    $data_arry[$x]['final_status_post'] =  $cands_result['status_value'];
                 
                    $data_arry[$x]['overallclosuredate_post'] = $max_date_post;
                       
                       
                    
                }

                if ($cands_result['status_value'] == "Insufficiency") {
                    $data_arry[$x]['final_status_post'] =  "Insufficiency";
                 
                    $data_arry[$x]['overallclosuredate_post'] = ""; 
                }
                if ($cands_result['status_value'] == "WIP") {
                    $data_arry[$x]['final_status_post'] =  "WIP";
                 
                    $data_arry[$x]['overallclosuredate_post'] = ""; 
                }

                $x++;
            }   
        }
  
        return $data_arry;
    }

    protected  function rename_status_value($value ='')
    {
        if($value == "Clear")
        {
            $status_value = "Green";
        }
        else
        {
            $status_value = $value;   
        }

        return $status_value;
    }
    
    protected function cellColorStatus($cells,$status_value,$spreadsheet)
    {
       
        if($status_value == "Clear")
        {
           

            $styleArrayClear = array(
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ),
                
                'fill' => array(
                    'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'rotation' => 90,
                    'color' => array(
                        'argb' => '008000',
                    ),
                   
                ),
            );
     

             $spreadsheet->getActiveSheet()->getStyle($cells)->applyFromArray($styleArrayClear);
          
        }
        if($status_value == "Insufficiency")
        {

            $styleArrayInsuff = array(
                'font' => array(
                    'bold' => true,
                    'color' =>  array(
                        'argb' => 'FF0000',
                    ),
                ),
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ),
                
            );
     
                 
          $spreadsheet->getActiveSheet()->getStyle($cells)->applyFromArray($styleArrayInsuff);
        }

        if($status_value == "Major Discrepancy")
        {

            $styleArrayMajor = array(
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ),

                'fill' => array(
                    'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'rotation' => 90,
                    'color' => array(
                        'argb' => 'FF0000',
                    ),
                   
                ),
                
            );
     
                 
          $spreadsheet->getActiveSheet()->getStyle($cells)->applyFromArray($styleArrayMajor);
        }

        if($status_value == "Stop Check" || $status_value == "NA" || $status_value == "N/A")
        {

            $styleArrayMajor = array(
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ),

                'fill' => array(
                    'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'rotation' => 90,
                    'color' => array(
                        'argb' => 'D3D3D3',
                    ),
                   
                ),
                
            );
          
          $spreadsheet->getActiveSheet()->getStyle($cells)->applyFromArray($styleArrayMajor);
        }

        if($status_value == "Unable to verify")
        {

            $styleArrayMajor = array(
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ),

                'fill' => array(
                    'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'rotation' => 90,
                    'color' => array(
                        'argb' => 'FFFF00',
                    ),
                   
                ),
                
            );
          
          $spreadsheet->getActiveSheet()->getStyle($cells)->applyFromArray($styleArrayMajor);
        }

        if($status_value == "Minor Discrepancy")
        {

            $styleArrayMajor = array(
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ),

                'fill' => array(
                    'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'rotation' => 90,
                    'color' => array(
                        'argb' => 'FFA500',
                    ),
                   
                ),
                
            );
          
          $spreadsheet->getActiveSheet()->getStyle($cells)->applyFromArray($styleArrayMajor);
        }
    }
   
    public function update_request_data($details, $where)
    {
        $this->load->model('report_generated_user');
        $this->report_generated_user->report_requested_save($details, $where);
    }
    public function update_request_schedular_list($details, $where)
    {
        $this->load->model('report_generated_user');
        $this->report_generated_user->report_requested_schedular($details, $where);
    }

    public function final_qc_send_report_mail($candidate_id,$case_status,$user_id)
    {
         $candidate_id = explode(',', $candidate_id);
             
            $this->load->library('email');

            $this->load->model('candidates_model');

            log_message('error', 'Candidate Send mail Final Approve');
            try {    

                foreach ($candidate_id as $key => $value) {
                   
                    $details = $this->candidates_model->select_candidate(array('candidates_info.id' => $value));

                    if($case_status  == '1')
                    {
                     
                        $create_file =  $this->report_generate($value,'final_report');
                       
                        $clients_details = $this->candidates_model->get_entitypackages(array('tbl_clients_id'=>$details[0]['clientid'],'entity'=>$details[0]['entity'],'package'=>$details[0]['package']));
                      

                        $component_id = explode(",", $clients_details[0]['component_id']);
                        
                        $data_arry = array();
                        if (in_array('addrver', $component_id)) {
                          
                            $result = $this->candidates_model->get_addres_ver_status_for_export(array('addrver.candsid' => $details[0]['id']));
                         

                            if (!empty($result)) {
                                $counter = 1;
                                foreach ($result as $key => $value) {  

                                    $data_arry["address"][] = $value['report_status'];
                                    $counter++;
                                }
                                   
                            }
                        }


                        if (in_array('eduver', $component_id)) {
                            $result = $this->candidates_model->get_education_ver_status_for_export(array('education.candsid' =>  $details[0]['id']));

                            if (!empty($result)) {

                                $counter = 1;
                                foreach ($result as $key => $value) {
                                
                                    $data_arry["education"][] = $value['report_status'];         
                           
                                    $counter++;
                                }
                              
                            }
                        }

                        if (in_array('empver', $component_id)) {
                            $result = $this->candidates_model->get_employment_ver_status_for_export(array('empver.candsid' => $details[0]['id']));

                          
                            if (!empty($result)) {
                                $counter = 1;
                                foreach ($result as $key => $value) {
                                    
                                   $data_arry["employment"][] = $value['report_status'];  
                                   
                                    $counter++;
                                }

                            }
                        }
             
                        if (in_array('refver', $component_id)) {
                            $result = $this->candidates_model->get_refver_ver_status_for_export(array('reference.candsid' => $details[0]['id']));

                            if (!empty($result)) {
                                $counter = 1;
                                foreach ($result as $key => $value) {
                                    
                                    $data_arry["reference"][] = $value['report_status']; 
                                    
                                    $counter++;
                                }
                              
                            }
                        }

                        if (in_array('courtver', $component_id)) {
                            $result = $this->candidates_model->get_court_ver_status_for_export(array('courtver.candsid' => $details[0]['id']));

                            if (!empty($result)) {
                                $counter = 1;
                                foreach ($result as $key => $value) {

                                    $data_arry["court"][] = $value['report_status']; 

                                    $counter++;
                                }
                               
                            }
                        }

                        if (in_array('crimver', $component_id)) {
                            $result = $this->candidates_model->get_pcc_ver_status_for_export(array('pcc.candsid' => $details[0]['id']));

                            
                            if (!empty($result)) {

                                $counter = 1;
                                foreach ($result as $key => $value) {
                                    
                                    $data_arry["pcc"][] = $value['report_status']; 
                                
                                    $counter++;
                                }
                                
                            }
                        }

                        if (in_array('globdbver', $component_id)) {
                            $result = $this->candidates_model->get_globdbver_ver_status_for_export(array('glodbver.candsid' => $details[0]['id']));
                            
                            if (!empty($result)) {

                                $counter = 1;
                                foreach ($result as $key => $value) {

                                    $data_arry["global"][] = $value['report_status'];

                                    $counter++;
                                }
                               
                            }
                        }

                        if (in_array('identity', $component_id)) {
                            $result = $this->candidates_model->get_identity_ver_status_for_export(array('identity.candsid' => $details[0]['id']));

                            if (!empty($result)) {

                                $counter = 1;
                                foreach ($result as $key => $value) {

                                    $data_arry["identity"][] = $value['report_status'];
                                   
                                    $counter++;
                                }
                               
                            }
                        }

                        if (in_array('cbrver', $component_id)) {
                            $result = $this->candidates_model->get_credit_report_ver_status_for_export(array('credit_report.candsid' => $details[0]['id']));


                            if (!empty($result)) {

                                $counter = 1;
                                foreach ($result as $key => $value) {

                                    $data_arry["credit_report"][] = $value['report_status'];
                                    $counter++;
                                }

                            }
                        }

                       
                        $this->load->model('employment_model');

                        $reportingmanager_user = $this->employment_model->get_reporting_manager_final_id($user_id);

                        $email_tmpl_data['user_profile_info'] = $reportingmanager_user[0];

                        $client_details_id = $this->candidates_model->select_client_details(array('tbl_clients_id'=>$details[0]['clientid'],'entity'=>$details[0]['entity'],'package'=>$details[0]['package']));
          
                        $client_actual_id = array();
                        foreach ($client_details_id as $key => $value) {
                          $client_actual_id[] = $value['id'];  
                        }
                        
                        $spoc_email_id = $this->candidates_model->select_spoc_mail_id($client_actual_id);
                       
                        $spoc_email = array();
                        $spoc_cc = array();
                        foreach ($spoc_email_id as $key => $value) {
                          $spoc_email[] = $value['spoc_email'];  
                          $spoc_cc[] = $value['spoc_manager_email'];  
                        }   

                        $spoc_email = array_unique($spoc_email);
                        $spoc_cc = array_unique($spoc_cc);

                        $client_details_id = $this->candidates_model->get_client_manager_id(array('id'=>$details[0]['clientid']));

                        $client_details_email_id = $this->candidates_model->get_reporting_manager_email_id($client_details_id[0]['clientmgr']);
                  
                        $email_tmpl_data['to_emails'] = implode(',',$spoc_email);
                                
                        $carc_mail =  implode(',',$spoc_cc).','.REPORTEMAIL.','.$client_details_email_id[0]['email'];
                                
                        $carc_mails = explode(',', $carc_mail); 
                          
                        $carboncopy_mails = array_unique($carc_mails);

                        $carboncopy_mail = implode(',',$carboncopy_mails);

                        $email_tmpl_data['cc_emails'] = $carboncopy_mail;


                        if($details[0]['clientid'] == '3' || $details[0]['clientid'] == '4' || $details[0]['clientid'] == '5')
                        {

                            if(empty($details[0]['final_qc_send_mail_timestamp']))
                            {
                                if(isset($data_arry['address']))
                                {
                                    $email_tmpl_data['subject'] = 'Report of '.ucwords($details[0]['CandidateName']) . ' | Post BGV Report'; 
                                }
                                else
                                {
                                    $email_tmpl_data['subject'] = 'Report of '.ucwords($details[0]['CandidateName']) . ' | Pre BGV Report';    
                                }

                            }
                            else
                            {  
                                if(isset($data_arry['address']))
                                {

                                    $email_tmpl_data['subject'] = 'REVISED Report of '.ucwords($details[0]['CandidateName']) . ' | Post BGV Report';
                                } 
                                else
                                {

                                    $email_tmpl_data['subject'] = 'REVISED Report of '.ucwords($details[0]['CandidateName']) . ' | Pre BGV Report';
                                }
                            }

                        }
                        elseif ($details[0]['clientid'] == '33') {
                            
                            if(empty($details[0]['final_qc_send_mail_timestamp']))
                            {
                                if(isset($data_arry['employment']))
                                {
                                    $email_tmpl_data['subject'] = 'Report of '.ucwords($details[0]['CandidateName']) . ' | Post BGV Report'; 
                                }
                                else
                                {
                                    $email_tmpl_data['subject'] = 'Report of '.ucwords($details[0]['CandidateName']) . ' | Pre BGV Report';    
                                }

                            }
                            else
                            {  
                                if(isset($data_arry['employment']))
                                {

                                    $email_tmpl_data['subject'] = 'REVISED Report of '.ucwords($details[0]['CandidateName']) . ' | Post BGV Report';
                                } 
                                else
                                {

                                    $email_tmpl_data['subject'] = 'REVISED Report of '.ucwords($details[0]['CandidateName']) . ' | Pre BGV Report';
                                }
                            } 
                        }
                        else
                        {
                            if(empty($details[0]['final_qc_send_mail_timestamp']))
                            {
                       
                                $email_tmpl_data['subject'] = 'Report of '.ucwords($details[0]['CandidateName']) . ' | Case Received date - ' . date('d-M-Y', strtotime($details[0]['caserecddate']));
                            }
                            else
                            {
                                $email_tmpl_data['subject'] = 'REVISED Report of '.ucwords($details[0]['CandidateName']) . ' | Case Received date - ' . date('d-M-Y', strtotime($details[0]['caserecddate']));
                            }
                        }

                        $email_tmpl_data['from_email'] = REPORTEMAIL;

                        $email_tmpl_data['detail_info'] = $details;

                        $email_tmpl_data['component_details'] = $data_arry;

                        $attachemnt = ucwords($details[0]['ClientRefNumber']).'_'.ucwords($details[0]['CandidateName']).'_Report'.'.pdf';

                        $email_tmpl_data['attachments'] = $attachemnt;
                   
                        $result = $this->email->candidate_report_mail_send($email_tmpl_data);

                        $this->email->clear(true);

                        if ($result) {
                     
                            $update_activity_log_candidate = $this->candidates_model->save(array('final_qc_send_mail' => 1,'final_qc_send_mail_timestamp' => date(DB_DATE_FORMAT)), array('id' => $details[0]['id']));

                            if(file_exists(SITE_BASE_PATH . CANDIDATES . $attachemnt)){
                                    unlink(SITE_BASE_PATH . CANDIDATES . $attachemnt);
                            }
                        }
                    }
                    if($case_status == '2')
                    { 

                          $update_activity_log_candidate = $this->candidates_model->save(array('final_qc_send_mail' => 2), array('id' => $details[0]['id']));

                    }
                }

           } catch (Exception $e) {
            log_message('error', 'Final QC::report_send_mail');
            log_message('error', $e->getMessage());
           }    
        
    }

    public function final_qc_send_report_mail_cron()
    {       
        
        
        $this->load->model('candidates_model');

        $this->load->model('Cron_job_model');

        $selected_qc =  $this->Cron_job_model->selected_final_qc_aq_component();

        if($selected_qc[0]['cron_job_component_selection'] == "Yes");
        {


            $candidate_ids = $this->candidates_model->select_final_approve_qc_run();

            $candidate_id = []; 
            foreach ($candidate_ids as $childArray) 
            { 
                foreach ($childArray as $value) 
                { 
                  $candidate_id[] = $value; 
                } 
            }
            
             
            $this->load->library('email');


            log_message('error', 'Candidate Send mail Final Approve');
            try {    

                foreach ($candidate_id as $key => $value) {
                   
                    $details = $this->candidates_model->select_candidate(array('candidates_info.id' => $value));

                  
                     
                        $create_file =  $this->report_generate($value,'final_report');
                       
                        $clients_details = $this->candidates_model->get_entitypackages(array('tbl_clients_id'=>$details[0]['clientid'],'entity'=>$details[0]['entity'],'package'=>$details[0]['package']));
                      

                        $component_id = explode(",", $clients_details[0]['component_id']);
                        
                        $data_arry = array();
                        if (in_array('addrver', $component_id)) {
                          
                            $result = $this->candidates_model->get_addres_ver_status_for_export(array('addrver.candsid' => $details[0]['id']));
                         

                            if (!empty($result)) {
                                $counter = 1;
                                foreach ($result as $key => $value) {  

                                    $data_arry["address"][] = $value['report_status'];
                                    $counter++;
                                }
                                   
                            }
                        }


                        if (in_array('eduver', $component_id)) {
                            $result = $this->candidates_model->get_education_ver_status_for_export(array('education.candsid' =>  $details[0]['id']));

                            if (!empty($result)) {

                                $counter = 1;
                                foreach ($result as $key => $value) {
                                
                                    $data_arry["education"][] = $value['report_status'];         
                           
                                    $counter++;
                                }
                              
                            }
                        }

                        if (in_array('empver', $component_id)) {
                            $result = $this->candidates_model->get_employment_ver_status_for_export(array('empver.candsid' => $details[0]['id']));

                          
                            if (!empty($result)) {
                                $counter = 1;
                                foreach ($result as $key => $value) {
                                    
                                   $data_arry["employment"][] = $value['report_status'];  
                                   
                                    $counter++;
                                }

                            }
                        }
             
                        if (in_array('refver', $component_id)) {
                            $result = $this->candidates_model->get_refver_ver_status_for_export(array('reference.candsid' => $details[0]['id']));

                            if (!empty($result)) {
                                $counter = 1;
                                foreach ($result as $key => $value) {
                                    
                                    $data_arry["reference"][] = $value['report_status']; 
                                    
                                    $counter++;
                                }
                              
                            }
                        }

                        if (in_array('courtver', $component_id)) {
                            $result = $this->candidates_model->get_court_ver_status_for_export(array('courtver.candsid' => $details[0]['id']));

                            if (!empty($result)) {
                                $counter = 1;
                                foreach ($result as $key => $value) {

                                    $data_arry["court"][] = $value['report_status']; 

                                    $counter++;
                                }
                               
                            }
                        }

                        if (in_array('crimver', $component_id)) {
                            $result = $this->candidates_model->get_pcc_ver_status_for_export(array('pcc.candsid' => $details[0]['id']));

                            
                            if (!empty($result)) {

                                $counter = 1;
                                foreach ($result as $key => $value) {
                                    
                                    $data_arry["pcc"][] = $value['report_status']; 
                                
                                    $counter++;
                                }
                                
                            }
                        }

                        if (in_array('globdbver', $component_id)) {
                            $result = $this->candidates_model->get_globdbver_ver_status_for_export(array('glodbver.candsid' => $details[0]['id']));
                            
                            if (!empty($result)) {

                                $counter = 1;
                                foreach ($result as $key => $value) {

                                    $data_arry["global"][] = $value['report_status'];

                                    $counter++;
                                }
                               
                            }
                        }

                        if (in_array('identity', $component_id)) {
                            $result = $this->candidates_model->get_identity_ver_status_for_export(array('identity.candsid' => $details[0]['id']));

                            if (!empty($result)) {

                                $counter = 1;
                                foreach ($result as $key => $value) {

                                    $data_arry["identity"][] = $value['report_status'];
                                   
                                    $counter++;
                                }
                               
                            }
                        }

                        if (in_array('cbrver', $component_id)) {
                            $result = $this->candidates_model->get_credit_report_ver_status_for_export(array('credit_report.candsid' => $details[0]['id']));


                            if (!empty($result)) {

                                $counter = 1;
                                foreach ($result as $key => $value) {

                                    $data_arry["credit_report"][] = $value['report_status'];
                                    $counter++;
                                }

                            }
                        }

                       
                        $this->load->model('employment_model');

                        $reportingmanager_user = $this->employment_model->get_reporting_manager_final_id(1);

                        $email_tmpl_data['user_profile_info'] = $reportingmanager_user[0];

                        $client_details_id = $this->candidates_model->select_client_details(array('tbl_clients_id'=>$details[0]['clientid'],'entity'=>$details[0]['entity'],'package'=>$details[0]['package']));
          
                        $client_actual_id = array();
                        foreach ($client_details_id as $key => $value) {
                          $client_actual_id[] = $value['id'];  
                        }
                        
                        $spoc_email_id = $this->candidates_model->select_spoc_mail_id($client_actual_id);
                       
                        $spoc_email = array();
                        $spoc_cc = array();
                        foreach ($spoc_email_id as $key => $value) {
                          $spoc_email[] = $value['spoc_email'];  
                          $spoc_cc[] = $value['spoc_manager_email'];  
                        }   

                        $spoc_email = array_unique($spoc_email);
                        $spoc_cc = array_unique($spoc_cc);

                        $client_details_id = $this->candidates_model->get_client_manager_id(array('id'=>$details[0]['clientid']));

                        $client_details_email_id = $this->candidates_model->get_reporting_manager_email_id($client_details_id[0]['clientmgr']);
                  
                        $email_tmpl_data['to_emails'] = implode(',',$spoc_email);
                                
                        $carc_mail =  implode(',',$spoc_cc).','.REPORTEMAIL.','.$client_details_email_id[0]['email'];
                                
                        $carc_mails = explode(',', $carc_mail); 
                          
                        $carboncopy_mails = array_unique($carc_mails);

                        $carboncopy_mail = implode(',',$carboncopy_mails);

                        $email_tmpl_data['cc_emails'] = $carboncopy_mail;


                        if($details[0]['clientid'] == '3' || $details[0]['clientid'] == '4' || $details[0]['clientid'] == '5')
                        {

                            if(empty($details[0]['final_qc_send_mail_timestamp']))
                            {
                                if(isset($data_arry['address']))
                                {
                                    $email_tmpl_data['subject'] = 'Report of '.ucwords($details[0]['CandidateName']) . ' | Post BGV Report'; 
                                }
                                else
                                {
                                    $email_tmpl_data['subject'] = 'Report of '.ucwords($details[0]['CandidateName']) . ' | Pre BGV Report';    
                                }

                            }
                            else
                            {  
                                if(isset($data_arry['address']))
                                {

                                    $email_tmpl_data['subject'] = 'REVISED Report of '.ucwords($details[0]['CandidateName']) . ' | Post BGV Report';
                                } 
                                else
                                {

                                    $email_tmpl_data['subject'] = 'REVISED Report of '.ucwords($details[0]['CandidateName']) . ' | Pre BGV Report';
                                }
                            }

                        }
                        elseif ($details[0]['clientid'] == '33') {
                            
                            if(empty($details[0]['final_qc_send_mail_timestamp']))
                            {
                                if(isset($data_arry['employment']))
                                {
                                    $email_tmpl_data['subject'] = 'Report of '.ucwords($details[0]['CandidateName']) . ' | Post BGV Report'; 
                                }
                                else
                                {
                                    $email_tmpl_data['subject'] = 'Report of '.ucwords($details[0]['CandidateName']) . ' | Pre BGV Report';    
                                }

                            }
                            else
                            {  
                                if(isset($data_arry['employment']))
                                {

                                    $email_tmpl_data['subject'] = 'REVISED Report of '.ucwords($details[0]['CandidateName']) . ' | Post BGV Report';
                                } 
                                else
                                {

                                    $email_tmpl_data['subject'] = 'REVISED Report of '.ucwords($details[0]['CandidateName']) . ' | Pre BGV Report';
                                }
                            } 
                        }
                        else
                        {
                            if(empty($details[0]['final_qc_send_mail_timestamp']))
                            {
                       
                                $email_tmpl_data['subject'] = 'Report of '.ucwords($details[0]['CandidateName']) . ' | Case Received date - ' . date('d-M-Y', strtotime($details[0]['caserecddate']));
                            }
                            else
                            {
                                $email_tmpl_data['subject'] = 'REVISED Report of '.ucwords($details[0]['CandidateName']) . ' | Case Received date - ' . date('d-M-Y', strtotime($details[0]['caserecddate']));
                            }
                        }

                        $email_tmpl_data['from_email'] = REPORTEMAIL;

                        $email_tmpl_data['detail_info'] = $details;

                        $email_tmpl_data['component_details'] = $data_arry;

                        $attachemnt = ucwords($details[0]['ClientRefNumber']).'_'.ucwords($details[0]['CandidateName']).'_Report'.'.pdf';

                        $email_tmpl_data['attachments'] = $attachemnt;
                   
                        $result = $this->email->candidate_report_mail_send($email_tmpl_data);

                        $this->email->clear(true);

                        if ($result) {
                     
                            $update_activity_log_candidate = $this->candidates_model->save(array('final_qc_send_mail' => 1,'final_qc_send_mail_timestamp' => date(DB_DATE_FORMAT)), array('id' => $details[0]['id']));

                            if(file_exists(SITE_BASE_PATH . CANDIDATES . $attachemnt)){
                                    unlink(SITE_BASE_PATH . CANDIDATES . $attachemnt);
                            }
                        }
                   
                }

                $this->Cron_job_model->save(array('executed_on'=>date(DB_DATE_FORMAT),'created_by'=> 1,'status'=> 1),array('id' => 6));

           } catch (Exception $e) {
            log_message('error', 'Final QC::report_send_mail');
            log_message('error', $e->getMessage());
           }

        }    
        
    }


    public function report_generate($candsid = null, $report_type)
    {
        if (!empty($candsid)) {

            $this->load->model('candidates_model');

            $id = decrypt($candsid);

            $this->load->model('first_qc_model');

            $this->load->library('example_zip');

            $this->load->library('example_client_zip');

            $report = array();

            $cands_result = $this->candidates_model->get_candidates_info_info_report(array('candidates_info.id' => $id));



            $report['address_info'] = array();
            $report['employment_info'] = array();
            $report['education_info'] = array();
            $report['references_info'] = array();
            $report['court_info'] = array();
            $report['global_db_info'] = array();
            $report['pcc_info'] = array();
            $report['identity_info'] = array();
            $report['credit_report_info'] = array();
            $report['drugs_info'] = array();
            $report['social_media_info'] = array();


            $report['report_type'] = $report_type;

            if ($cands_result) {
                $report['cand_info'] = $cands_result;

                $NA_array = array();

                $result = $this->first_qc_model->get_address_final_qc(array('addrverres.candsid' => $id));

                if (!empty($result)) {

                   /* $attachment = explode('||',$result[0]['add_attachments']);

                    foreach ($attachment as $key => $value) {
                        $value_attachment =  explode('/',$value);
                        $value_attachments =   $value_attachment[2];
                        $source_file_upload_path = SITE_BASE_PATH . ADDRESS . $value_attachment[1] .'/'.$value_attachment[2];

                        $destination_file_upload_path = SITE_BASE_PATH . ADDRESS_TEMP;
                        if (!folder_exist($destination_file_upload_path)) {
                            mkdir($destination_file_upload_path, 0777);
                        } else if (!is_writable($destination_file_upload_path)) {
                            array_push($error_msgs, 'Problem while uploading');
                        }

                        $this->convert_jpg_to_png($source_file_upload_path,$destination_file_upload_path);
                    }*/

                    $report['address_info'] = $result;
                } else {
                    $report['address_info'] = $result;
                    $NA_array[] = array('ADDRESS');
                }

                $result = $this->first_qc_model->get_emp_final_qc(array('empverres.candsid' => $id));
                if (!empty($result)) {
                    $report['employment_info'] = $result;
                } else {
                    $report['employment_info'] = $result;
                    $NA_array[] = array('EMPLOYMENT');
                }
                $result = $this->first_qc_model->get_education_final_qc(array('education_result.candsid' => $id));

                if (!empty($result)) {
                    $report['education_info'] = $result;
                } else {
                    $report['education_info'] = $result;
                    $NA_array[] = array('EDUCATION');
                }
                $result = $this->first_qc_model->get_reference_final_qc(array('reference_result.candsid' => $id));
                if (!empty($result)) {
                    $report['references_info'] = $result;
                } else {
                    $report['references_info'] = $result;
                    $NA_array[] = array('REFERENCES VERIFICATION');
                }

                $result = $this->first_qc_model->get_court_final_qc(array('courtver_result.candsid' => $id));

                if (!empty($result)) {
                    $report['court_info'] = $result;
                } else {
                    $report['court_info'] = $result;
                    $NA_array[] = array('COURT VERIFICATION');
                }
                $result = $this->first_qc_model->get_global_db_final_qc(array('glodbver_result.candsid' => $id));
                if (!empty($result)) {
                    $report['global_db_info'] = $result;
                } else {
                    $report['global_db_info'] = $result;
                    $NA_array[] = array('GLOBAL DATABASE VERIFICATION');
                }

                $result = $this->first_qc_model->get_drug_db_final_qc(array('drug_narcotis_result.candsid' => $id));

                if (!empty($result)) {
                    $report['drugs_info'] = $result;
                } else {
                    $report['drugs_info'] = $result;
                    $NA_array[] = array('DRUGS VERIFICATION');
                }
                $result = $this->first_qc_model->get_pcc_final_qc(array('pcc_result.candsid' => $id));

                if (!empty($result)) {
                    $report['pcc_info'] = $result;
                } else {
                    $report['pcc_info'] = $result;
                    $NA_array[] = array('POLICE VERIFICATION');
                }

                $result = $this->first_qc_model->get_identity_final_qc(array('identity_result.candsid' => $id));

                if (!empty($result)) {
                    $report['identity_info'] = $result;
                } else {
                    $report['identity_info'] = $result;
                    $NA_array[] = array('IDENTITY VERIFICATION');
                }

                $result = $this->first_qc_model->get_credit_report_final_qc(array('credit_report_result.candsid' => $id));

                if (!empty($result)) {
                    $report['credit_report_info'] = $result;
                } else {
                    $report['credit_report_info'] = $result;
                    $NA_array[] = array('CREDIT REPORT VERIFICATION');
                }


                $result = $this->first_qc_model->get_social_media_final_qc(array('social_media_result.candsid' => $id));

                if (!empty($result)) {
                    $report['social_media_info'] = $result;
                } else {
                    $report['social_media_info'] = $result;
                    $NA_array[] = array('SOCIAL MEDIA VERIFICATION');
                }

                $report['NA_COMPONENTS'] = $NA_array;

                $report['status'] = OVERALL_STATUS;

                if ($cands_result['comp_logo'] != "") {
                    $cleit_logo_path = SITE_URL . CLIENT_LOGO . '/' . $cands_result['comp_logo'];
                    if(!defined('CLIENT_LOGOS')) {
                        define('CLIENT_LOGOS', $cleit_logo_path);
                    }
                } else {
                    if(!defined('CLIENT_LOGOS')) {
                        define('CLIENT_LOGOS', '');
                    }
                }

                define('CUSTOM_CLINT_ID',$cands_result['clientid']);

                if($cands_result['clientid'] == "65")
                {

                   $this->example_client_zip->generate_pdf($report, 'admin');
                }
                else
                { 

                   $this->example_zip->generate_pdf($report, 'admin');
                }
                
               

               /* $result = $this->first_qc_model->get_address_final_qc(array('addrverres.candsid' => $id));

                if (!empty($result)) {

                    $attachment = explode('||',$result[0]['add_attachments']);
                    foreach ($attachment as $key => $value) {
                        $value_attachment =  explode('/',$value);

                        unlink($destination_file_upload_path.$value_attachment[2]);
                    }  
                } */

            } else {
                show_404();
            }
        } else {
            redirect('admin/candidates');
        }
       
    }

    public function create_closed_report($parameters_array = null, $client_id, $entity_id, $package_id, $client_name)
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $this->load->model('report_generated_user');

        $candidate_details =  $this->report_generated_user->select_candidate_deatils($client_id,$entity_id, $package_id); 

        if(file_exists(SITE_BASE_PATH . CANDIDATES . "Candidates_report.zip")){
            unlink(SITE_BASE_PATH . CANDIDATES . "Candidates_report.zip");
        } 

        foreach ($candidate_details as $key => $value) {
                   
            $create_file =  $this->zip_report_generate($value['id'],'final_report');

            $reportstatus = ($value["overallstatus"] == '1' || $value["overallstatus"] == '2' ||  $value["overallstatus"] == '5') ? "INTERIM" : "FINAL";
            
            if(file_exists(SITE_BASE_PATH . CANDIDATES . ucwords($value['ClientRefNumber']).'_'.ucwords($value['CandidateName']).'_'.$reportstatus.'_Report'.'.pdf')){
          
            unlink(SITE_BASE_PATH . CANDIDATES . ucwords($value['ClientRefNumber']).'_'.ucwords($value['CandidateName']).'_'.$reportstatus.'_Report'.'.pdf');
            }
   
        }
        $file_upload_path = SITE_BASE_PATH . CANDIDATES;
        $file_name="Candidates_report.zip";


        $this->update_request_data(array('file_name' => $file_name, 'folder_generated_status' => 1, 'folder_name' => $file_upload_path), array('id' => $parameters_array));

        $this->update_request_schedular_list(array('report_id' => $parameters_array, 'file_name' => $file_name, 'run_status' => 1, 'last_run_on' => date(DB_DATE_FORMAT)), array('id' => '2'));

    }

    public function create_closed_report_id($parameters_array = null,$mist_id)
    { 
        $this->load->library('zip');

        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $this->load->model('report_generated_user');

        $list = explode(',', $mist_id);
       
      
     
        $candidate_details =  $this->report_generated_user->select_candidate_deatils_id($list); 

        if(file_exists(SITE_BASE_PATH . CANDIDATES . "Candidates_report.zip")){
            unlink(SITE_BASE_PATH . CANDIDATES . "Candidates_report.zip");
        } 
        foreach ($candidate_details as $key => $value) {

            if($value['overallstatus'] == 3 || $value['overallstatus'] == 4 || $value['overallstatus'] == 6 || $value['overallstatus'] == 7 || $value['overallstatus'] == 8)
            
		    {
                
                $create_file =  $this->zip_report_generate($value['id'],'final_report');
                
            
                $reportstatus = ($value["overallstatus"] == '1' || $value["overallstatus"] == '2' ||  $value["overallstatus"] == '5') ? "INTERIM" : "FINAL";
                
                if(file_exists(SITE_BASE_PATH . CANDIDATES . ucwords($value['ClientRefNumber']).'_'.ucwords($value['CandidateName']).'_'.$reportstatus.'_Report'.'.pdf')){
            
                unlink(SITE_BASE_PATH . CANDIDATES . ucwords($value['ClientRefNumber']).'_'.ucwords($value['CandidateName']).'_'.$reportstatus.'_Report'.'.pdf');
                }
            }
   
        }
    
        require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
        // Create new Spreadsheet object
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        // Set document properties
        $spreadsheet->getProperties()->setCreator(CRMNAME)
            ->setLastModifiedBy(CRMNAME)
            ->setTitle(CRMNAME)
            ->setSubject('Candidate Records')
            ->setDescription('Candidate records with their status');

        // add style to the header
        $styleArray = array(
            'font' => array(
                'bold' => true,
            ),
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
                'top' => array(
                    'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ),
            ),
            'fill' => array(
                'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                'rotation' => 90,
                'startcolor' => array(
                    'argb' => 'FFA0A0A0',
                ),
                'endcolor' => array(
                    'argb' => 'FFFFFFFF',
                ),
            ),
        );
        $spreadsheet->getActiveSheet()->getStyle('A1:D1')->applyFromArray($styleArray);
        // auto fit column to content
        foreach(range('A','D') as $columnID) {
          $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
              ->setWidth(20);
        }

        // set the names of header cells
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue("A1",'Candidate Ref No')
            ->setCellValue("B1",'Candidate Name')
            ->setCellValue("C1",'Status')
            ->setCellValue("D1",'Approve/Reject Date');
        // Add some data
        $x= 2;
        foreach($candidate_details as $all_record){
 
            if($all_record['overallstatus'] == 3 || $all_record['overallstatus'] == 4 || $all_record['overallstatus'] == 6 || $all_record['overallstatus'] == 7 || $all_record['overallstatus'] == 8)
		    {
                $status = "Downloaded";
            }
            else{
                $status = "Not Downloaded"; 
            } 


            $spreadsheet->setActiveSheetIndex(0)

        
              ->setCellValue("A$x",$all_record['cmp_ref_no'])
              ->setCellValue("B$x",ucwords($all_record['CandidateName']))
              ->setCellValue("C$x",$status)
              ->setCellValue("D$x",convert_db_to_display_date($all_record['final_qc_approve_reject_timestamp'],DB_DATE_FORMAT,DISPLAY_DATE_FORMAT12));

            $x++;
        }
        // Rename worksheet
        $spreadsheet->getActiveSheet()->setTitle('Candidatea Records');

        $spreadsheet->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=Candidate  Records.xlsx");
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Excel2007');
   

        $file_upload_path = SITE_BASE_PATH . CANDIDATES;
        $file_name_exe = "Candidates_Records.xls";

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Excel2007');
        ob_start();
        $writer->save($file_upload_path . "/" . $file_name_exe);
        ob_end_clean();

        $zip=new ZipArchive();
        if ($zip->open(SITE_BASE_PATH . CANDIDATES . 'Candidates_report.zip' , ZIPARCHIVE::CREATE) === TRUE) {
         
           $zip->addFile(SITE_BASE_PATH . CANDIDATES .$file_name_exe, $file_name_exe);
           $zip->close();
        }
     
        if(file_exists(SITE_BASE_PATH . CANDIDATES .$file_name_exe)){
            unlink(SITE_BASE_PATH . CANDIDATES .$file_name_exe );
        } 
    
        $file_upload_path = SITE_BASE_PATH . CANDIDATES;
        $file_name="Candidates_report.zip";
        
        $this->update_request_data(array('file_name' => $file_name, 'folder_generated_status' => 1, 'folder_name' => $file_upload_path), array('id' => $parameters_array));

        $this->update_request_schedular_list(array('report_id' => $parameters_array, 'file_name' => $file_name, 'run_status' => 1, 'last_run_on' => date(DB_DATE_FORMAT)), array('id' => '2'));

    }


    public function zip_report_generate($candsid = null, $report_type)
    {
        if (!empty($candsid)) {

            $this->load->model('candidates_model');

            $id = decrypt($candsid);

            $this->load->model('first_qc_model');

            $this->load->library('example_zip_report');

            $report = array();

            $cands_result = $this->candidates_model->get_candidates_info_info_report(array('candidates_info.id' => $id));

            $report['address_info'] = array();
            $report['employment_info'] = array();
            $report['education_info'] = array();
            $report['references_info'] = array();
            $report['court_info'] = array();
            $report['global_db_info'] = array();
            $report['pcc_info'] = array();
            $report['identity_info'] = array();
            $report['credit_report_info'] = array();
            $report['drugs_info'] = array();
            

            $report['report_type'] = $report_type;

            if ($cands_result) {
                $report['cand_info'] = $cands_result;

                $NA_array = array();

                $result = $this->first_qc_model->get_address_final_qc(array('addrverres.candsid' => $id));

                if (!empty($result)) {

                    $report['address_info'] = $result;
                } else {
                    $report['address_info'] = $result;
                    $NA_array[] = array('ADDRESS');
                }

                $result = $this->first_qc_model->get_emp_final_qc(array('empverres.candsid' => $id));
                if (!empty($result)) {
                    $report['employment_info'] = $result;
                } else {
                    $report['employment_info'] = $result;
                    $NA_array[] = array('EMPLOYMENT');
                }
                $result = $this->first_qc_model->get_education_final_qc(array('education_result.candsid' => $id));

                if (!empty($result)) {
                    $report['education_info'] = $result;
                } else {
                    $report['education_info'] = $result;
                    $NA_array[] = array('EDUCATION');
                }
                $result = $this->first_qc_model->get_reference_final_qc(array('reference_result.candsid' => $id));
                if (!empty($result)) {
                    $report['references_info'] = $result;
                } else {
                    $report['references_info'] = $result;
                    $NA_array[] = array('REFERENCES VERIFICATION');
                }

                $result = $this->first_qc_model->get_court_final_qc(array('courtver_result.candsid' => $id));

                if (!empty($result)) {
                    $report['court_info'] = $result;
                } else {
                    $report['court_info'] = $result;
                    $NA_array[] = array('COURT VERIFICATION');
                }
                $result = $this->first_qc_model->get_global_db_final_qc(array('glodbver_result.candsid' => $id));
                if (!empty($result)) {
                    $report['global_db_info'] = $result;
                } else {
                    $report['global_db_info'] = $result;
                    $NA_array[] = array('GLOBAL DATABASE VERIFICATION');
                }

                $result = $this->first_qc_model->get_drug_db_final_qc(array('drug_narcotis_result.candsid' => $id));

                if (!empty($result)) {
                    $report['drugs_info'] = $result;
                } else {
                    $report['drugs_info'] = $result;
                    $NA_array[] = array('DRUGS VERIFICATION');
                }
                $result = $this->first_qc_model->get_pcc_final_qc(array('pcc_result.candsid' => $id));

                if (!empty($result)) {
                    $report['pcc_info'] = $result;
                } else {
                    $report['pcc_info'] = $result;
                    $NA_array[] = array('POLICE VERIFICATION');
                }

                $result = $this->first_qc_model->get_identity_final_qc(array('identity_result.candsid' => $id));

                if (!empty($result)) {
                    $report['identity_info'] = $result;
                } else {
                    $report['identity_info'] = $result;
                    $NA_array[] = array('IDENTITY VERIFICATION');
                }

                $result = $this->first_qc_model->get_credit_report_final_qc(array('credit_report_result.candsid' => $id));

                if (!empty($result)) {
                    $report['credit_report_info'] = $result;
                } else {
                    $report['credit_report_info'] = $result;
                    $NA_array[] = array('CREDIT REPORT VERIFICATION');
                }

                $report['NA_COMPONENTS'] = $NA_array;

                $report['status'] = OVERALL_STATUS;

                if ($cands_result['comp_logo'] != "") {
                    $cleit_logo_path = SITE_URL . CLIENT_LOGO . '/' . $cands_result['comp_logo'];
                    if(!defined('CLIENT_LOGOS')) {
                        define('CLIENT_LOGOS', $cleit_logo_path);
                    }
                } else {
                    if(!defined('CLIENT_LOGOS')) {
                        define('CLIENT_LOGOS', '');
                    }
                }

                define('CUSTOM_CLINT_ID',$cands_result['clientid']);

                $this->example_zip_report->generate_pdf($report, 'admin');
          
               /* $result = $this->first_qc_model->get_address_final_qc(array('addrverres.candsid' => $id));

                if (!empty($result)) {

                    $attachment = explode('||',$result[0]['add_attachments']);
                    foreach ($attachment as $key => $value) {
                        $value_attachment =  explode('/',$value);

                        unlink($destination_file_upload_path.$value_attachment[2]);
                    }  
                } */

            } else {
                show_404();
            }

             
        } else {
            redirect('admin/candidates');
        }
       
    }

    public function create_closure_report($parameters_array = null, $client_id, $entity_id, $package_id, $client_name)
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $this->load->model('report_generated_user');

        $file_upload_path = SITE_BASE_PATH . UPLOAD_FOLDER . 'bulk_reports';

        if (!folder_exist($file_upload_path)) {
            mkdir($file_upload_path, 0777);
        }
        if (!folder_exist($file_upload_path)) {
            mkdir($file_upload_path, 0777);
        } else if (!is_writable($file_upload_path)) {
            mkdir($file_upload_path, 0777);
        }

        if(file_exists( $file_upload_path . "/Candidates_Records_of_closure.xls")){
            unlink( $file_upload_path . "/Candidates_Records_of_closure.xls");
        } 


            $all_records = $this->get_all_client_data_for_closure($client_id, $entity_id, $package_id);


            require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
            // Create new Spreadsheet object
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

            // Set document properties
            $spreadsheet->getProperties()->setCreator(CRMNAME)
                ->setLastModifiedBy(CRMNAME)
                ->setTitle(CRMNAME)
                ->setSubject('Component records')
                ->setDescription('Component records with their status');

            // add style to the header
            $styleArray = array(
                'font' => array(
                    'bold' => true,
                ),
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ),
                'borders' => array(
                    'top' => array(
                        'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ),
                ),
                'fill' => array(
                    'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => array(
                        'argb' => 'FFA0A0A0',
                    ),
                    'endcolor' => array(
                        'argb' => 'FFFFFFFF',
                    ),
                ),
            );
            $spreadsheet->getActiveSheet()->getStyle('A1:AB1')->applyFromArray($styleArray);
            // auto fit column to content
            foreach (range('A', 'AB') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                    ->setWidth(20);
            }
            // set the names of header cells

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A1", 'Sr No.')
                ->setCellValue("B1", 'Mist ID')
                ->setCellValue("C1", 'Client ID')
                ->setCellValue("D1", 'Emp Name')
                ->setCellValue("E1", 'Overall Status')
                ->setCellValue("F1", 'Closure date')

                ->setCellValue("G1", 'Address Status')
                ->setCellValue("H1", 'Closure Date')

                ->setCellValue("I1", 'Employment Status')
                ->setCellValue("J1", 'Closure Date')

                ->setCellValue("K1", 'Education Status')
                ->setCellValue("L1", 'Closure Date')

                ->setCellValue("M1", 'Reference Status')
                ->setCellValue("N1", 'Closure Date')

                ->setCellValue("O1", 'Court Status')
                ->setCellValue("P1", 'Closure Date')

                ->setCellValue("Q1", 'Global Status')
                ->setCellValue("R1", 'Closure Date')

                ->setCellValue("S1", 'PCC Status')
                ->setCellValue("T1", 'Closure Date')

                ->setCellValue("U1", 'Identity Status')
                ->setCellValue("V1", 'Closure Date')

                ->setCellValue("W1", 'Credit Report Status')
                ->setCellValue("X1", 'Closure Date')

                ->setCellValue("Y1", 'Drugs Status')
                ->setCellValue("Z1", 'Closure Date')
                ->setCellValue("AA1", 'Insuff Raised Date')
                ->setCellValue("AB1", 'Insuff Clear Date');
            // Add some data
            $x = 2;
            foreach ($all_records as $all_record) {
                  
                if($all_record['overallstatus'] == "Clear")
                {
                    $overallstatus = "Green";

                }else{
                    $overallstatus = $all_record['overallstatus'];
                }

                if($all_record['overallstatus'] == "WIP" || $all_record['overallstatus'] == "Insufficiency")
                {
                    $overallclosuredate = "NA";
                }
                else{
                    $overallclosuredate = $all_record['overallclosuredate'];
                }
                
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A$x", $all_record['id'])
                    ->setCellValue("B$x", $all_record['cmp_ref_no'])
                    ->setCellValue("C$x", $all_record['ClientRefNumber'])
                    ->setCellValue("D$x", $all_record['CandidateName'])
                    ->setCellValue("E$x", $overallstatus)
                    ->setCellValue("F$x", $overallclosuredate)
                    ->setCellValue("G$x", $all_record['addrver'])
                    ->setCellValue("H$x", $all_record['addrver_closure'])
                    ->setCellValue("I$x", $all_record['empver'])
                    ->setCellValue("J$x", $all_record['empver_closure'])
                    ->setCellValue("K$x", $all_record['eduver'])
                    ->setCellValue("L$x", $all_record['eduver_closure'])
                    ->setCellValue("M$x", $all_record['refver'])
                    ->setCellValue("N$x", $all_record['reference_closure'])
                    ->setCellValue("O$x", $all_record['courtver'])
                    ->setCellValue("P$x", $all_record['courtver_closure'])
                    ->setCellValue("Q$x", $all_record['glodbver'])
                    ->setCellValue("R$x", $all_record['glodbver_closure'])
                    ->setCellValue("S$x", $all_record['crimver'])
                    ->setCellValue("T$x", $all_record['crimver_closure'])
                    ->setCellValue("U$x", $all_record['identity'])
                    ->setCellValue("V$x", $all_record['identity_closure'])
                    ->setCellValue("W$x", $all_record['cbrver'])
                    ->setCellValue("X$x", $all_record['cbrver_closure'])
                    ->setCellValue("Y$x", $all_record['drugs'])
                    ->setCellValue("Z$x", $all_record['drugs_closure'])
                    ->setCellValue("AA$x", convert_db_to_display_date($all_record['insuff_raised_date']))
                    ->setCellValue("AB$x", convert_db_to_display_date($all_record['insuff_clear_date']));

                $x++;
            }
            // Rename worksheet
            $spreadsheet->getActiveSheet()->setTitle('Component Records');

            $spreadsheet->setActiveSheetIndex(0);

            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment;filename=Component Records of.xlsx");
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0

             $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Excel2007');

            $file_name = "Candidates_Records_of_closure" . ".xls";

           $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Excel2007');
            ob_start();
            $writer->save($file_upload_path . "/" . $file_name);
            ob_end_clean();

            $this->update_request_data(array('file_name' => $file_name, 'folder_generated_status' => 1, 'folder_name' => $file_upload_path), array('id' => $parameters_array));

           $this->update_request_schedular_list(array('report_id' => $parameters_array, 'file_name' => $file_name, 'run_status' => 1, 'last_run_on' => date(DB_DATE_FORMAT)), array('id' => '3'));  

    }

    public function get_all_client_data_for_closure($clientid, $entity_id, $package_id)
    {
        $data_arry = array();
         
        $this->load->model('report_generated_user');
 
        $cands_results = $this->report_generated_user->get_all_cand_with_search($clientid, $entity_id, $package_id);
        $x = 0;
        foreach ($cands_results as $key => $cands_result) {

            $data_arry[$x]['id'] = $x + 1;
            $data_arry[$x]['CandidateName'] = ucwords(strtolower($cands_result['CandidateName']));
            $data_arry[$x]['ClientRefNumber'] = $cands_result['ClientRefNumber'];
            $data_arry[$x]['cmp_ref_no'] = $cands_result['cmp_ref_no'];
            $data_arry[$x]['overallstatus'] = $cands_result['status_value'];
            $data_arry[$x]['overallclosuredate'] = ($cands_result['overallclosuredate'] != "") ? convert_db_to_display_date($cands_result['overallclosuredate']) : 'NA';

            for ($i = 0; $i < 1; $i++) {

                $data_arry[$x]["addrver"] = 'NA';
                $data_arry[$x]["addrver_closure"] = 'NA';
                $data_arry[$x]["empver"] = 'NA';
                $data_arry[$x]["empver_closure"] = 'NA';
                $data_arry[$x]["eduver"] = 'NA';
                $data_arry[$x]["eduver_closure"] = 'NA';
                $data_arry[$x]["refver"] = 'NA';
                $data_arry[$x]["reference_closure"] = 'NA';
                $data_arry[$x]["courtver"] = 'NA';
                $data_arry[$x]["courtver_closure"] = 'NA';
                $data_arry[$x]["crimver"] = 'NA';
                $data_arry[$x]["crimver_closure"] = 'NA';
                $data_arry[$x]["glodbver"] = 'NA';
                $data_arry[$x]["glodbver_closure"] = 'NA';
                $data_arry[$x]["identity"] = 'NA';
                $data_arry[$x]["identity_closure"] = 'NA';
                $data_arry[$x]["cbrver"] = 'NA';
                $data_arry[$x]["cbrver_closure"] = 'NA';
                $data_arry[$x]["drugs"] = 'NA';
                $data_arry[$x]["drugs_closure"] = 'NA';

            }

            $insuff_raise_date = array();
            $insuff_clear_date = array();


            $component_id = explode(",", $cands_result['component_id']);

            if (in_array('addrver', $component_id)) {
                $result = $this->report_generated_user->get_addres_ver_status_for_export(array('addrver.candsid' => $cands_result['id']));

                if (!empty($result)) {

                    foreach ($result as $key => $value) {
                          
                        if(!empty($value['insuff_raised_date'])) 
                        {  
                            array_push($insuff_raise_date,explode('||',$value['insuff_raised_date']));
                        }
                        if(!empty($value['insuff_clear_date'])) 
                        {  
                            array_push($insuff_clear_date,explode('||',$value['insuff_clear_date']));
                        }
                    } 
                }

                if (!empty($result)) {
                    $counter = 1;
                    foreach ($result as $key => $value) {

                        if ($counter > 2) {
                            continue;
                        }

                        if($value['verfstatus'] != "")
                        {
                            if($value['verfstatus'] == "Major Discrepancy")
                            {
                                $status_value = $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "Insufficiency"){

                                $status_value = $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "WIP"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "Insufficiency Cleared"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "Final QC Reject"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "First QC Reject"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "New Check"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "YTR"){

                                $status_value = "WIP";
                            }  
                            elseif($value['verfstatus'] == "Follow Up"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "Re-Initiated"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "Clear"){

                                $status_value =  $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "Stop Check"){

                                $status_value =  $value['verfstatus'];
                            }
                             elseif($value['verfstatus'] == "Minor Discrepancy"){

                                $status_value = $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "Unable to verify"){

                                $status_value = $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "Worked with the same organization"){

                                $status_value = $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "Overseas check"){

                                $status_value = $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "No Record Found"){

                                $status_value = $value['verfstatus'];
                            }
                            else{
                                $status_value = $value['verfstatus'];
                            }
                            
                        }
                        else{
                            $status_value = "WIP";
                        }

                        if($value['closuredate'] != "")
                        {
                            if($value['verfstatus'] == "Major Discrepancy")
                            {
                                $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "Insufficiency"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "WIP"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "Insufficiency Cleared"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "Final QC Reject"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "First QC Reject"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "New Check"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "YTR"){

                                $closuredate = "NA";
                            }  
                            elseif($value['verfstatus'] == "Follow Up"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "Re-Initiated"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "Clear"){

                                $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "Stop Check"){

                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                             elseif($value['verfstatus'] == "Minor Discrepancy"){

                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "Unable to verify"){

                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "Worked with the same organization"){

                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "Overseas check"){

                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "No Record Found"){

                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            else{
                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            
                        }
                        else{
                            $closuredate = "NA";
                        }

                        
                        $data_arry[$x]["addrver"] =  $status_value;

                        $data_arry[$x]["addrver_closure"] =  $closuredate;


                        $counter++;
                    }
                }
            }
           
            if (in_array('eduver', $component_id)) {
                $result = $this->report_generated_user->get_education_ver_status_for_export(array('education.candsid' => $cands_result['id']));

                if (!empty($result)) {

                    foreach ($result as $key => $value) {

                        if(!empty($value['insuff_raised_date'])) 
                        {  
                            array_push($insuff_raise_date,explode('||',$value['insuff_raised_date']));
                        }
                        if(!empty($value['insuff_clear_date'])) 
                        {  
                            array_push($insuff_clear_date,explode('||',$value['insuff_clear_date']));
                        }
                    } 
                }

                if (!empty($result)) {

                    $counter = 1;
                    foreach ($result as $key => $value) {
                        if ($counter > 2) {
                            continue;
                        }

                        if($value['verfstatus'] != "")
                        {
                            if($value['verfstatus'] == "Major Discrepancy")
                            {
                                $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "Insufficiency"){

                                $status_value = $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "WIP"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "Insufficiency Cleared"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "Final QC Reject"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "First QC Reject"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "New Check"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "YTR"){

                                $status_value = "WIP";
                            }  
                            elseif($value['verfstatus'] == "Follow Up"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "Re-Initiated"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "Clear"){

                                $status_value =  $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "Stop Check"){

                                $status_value =  $value['verfstatus'];
                            }
                             elseif($value['verfstatus'] == "Minor Discrepancy"){

                                $status_value = $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "Unable to verify"){

                                $status_value = $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "Worked with the same organization"){

                                $status_value = $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "Overseas check"){

                                $status_value = $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "No Record Found"){

                                $status_value = $value['verfstatus'];
                            }
                            else{
                                $status_value = $value['verfstatus'];
                            }
                            
                        }
                        else{
                            $status_value = "WIP";
                        }

                        if($value['closuredate'] != "")
                        {
                            if($value['verfstatus'] == "Major Discrepancy")
                            {
                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "Insufficiency"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "WIP"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "Insufficiency Cleared"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "Final QC Reject"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "First QC Reject"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "New Check"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "YTR"){

                                $closuredate = "NA";
                            }  
                            elseif($value['verfstatus'] == "Follow Up"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "Re-Initiated"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "Clear"){

                                $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "Stop Check"){

                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                             elseif($value['verfstatus'] == "Minor Discrepancy"){

                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "Unable to verify"){

                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "Worked with the same organization"){

                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "Overseas check"){

                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "No Record Found"){

                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            else{
                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            
                        }
                        else{
                            $closuredate = "NA";
                        }

                        $data_arry[$x]["eduver"] =  $status_value;

                        $data_arry[$x]["eduver_closure"] =  $closuredate;

                        $counter++;
                    }
                }
            }

            if (in_array('empver', $component_id)) {
                $result = $this->report_generated_user->get_employment_ver_status_for_export(array('empver.candsid' => $cands_result['id']));

                if (!empty($result)) {

                    foreach ($result as $key => $value) {

                       
                        if(!empty($value['insuff_raised_date'])) 
                        {  
                            array_push($insuff_raise_date,explode('||',$value['insuff_raised_date']));
                        }
                        if(!empty($value['insuff_clear_date'])) 
                        {  
                            array_push($insuff_clear_date,explode('||',$value['insuff_clear_date']));
                        }
                    } 
                }
                if (!empty($result)) {
                    $counter = 1;
                    foreach ($result as $key => $value) {
                        if ($counter > 2) {
                            continue;
                        }

                        if($value['verfstatus'] != "")
                        {
                            if($value['verfstatus'] == "Major Discrepancy")
                            {
                                $status_value = $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "Insufficiency"){

                                $status_value = $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "WIP"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "Insufficiency Cleared"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "Final QC Reject"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "First QC Reject"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "New Check"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "YTR"){

                                $status_value = "WIP";
                            }  
                            elseif($value['verfstatus'] == "Follow Up"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "Re-Initiated"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "Clear"){

                                $status_value =  $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "Stop Check"){

                                $status_value =  $value['verfstatus'];
                            }
                             elseif($value['verfstatus'] == "Minor Discrepancy"){

                                $status_value = $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "Unable to verify"){

                                $status_value = $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "Worked with the same organization"){

                                $status_value = $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "Overseas check"){

                                $status_value = $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "No Record Found"){

                                $status_value = $value['verfstatus'];
                            }
                            else{
                                $status_value = $value['verfstatus'];
                            }
                            
                        }
                        else{
                            $status_value = "WIP";
                        }

                        if($value['closuredate'] != "")
                        {
                            if($value['verfstatus'] == "Major Discrepancy")
                            {
                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "Insufficiency"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "WIP"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "Insufficiency Cleared"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "Final QC Reject"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "First QC Reject"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "New Check"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "YTR"){

                                $closuredate = "NA";
                            }  
                            elseif($value['verfstatus'] == "Follow Up"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "Re-Initiated"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "Clear"){

                                $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "Stop Check"){

                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                             elseif($value['verfstatus'] == "Minor Discrepancy"){

                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "Unable to verify"){

                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "Worked with the same organization"){

                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "Overseas check"){

                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "No Record Found"){

                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            else{
                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            
                        }
                        else{
                            $closuredate = "NA";
                        }

                        $data_arry[$x]["empver"] =  $status_value;

                        $data_arry[$x]["empver_closure"] =  $closuredate;


                        $counter++;
                    }
                }
            }

            if (in_array('refver', $component_id)) {
                $result = $this->report_generated_user->get_refver_ver_status_for_export(array('reference.candsid' => $cands_result['id']));
                if (!empty($result)) {

                    foreach ($result as $key => $value) {

                        
                        if(!empty($value['insuff_raised_date'])) 
                        {  
                            array_push($insuff_raise_date,explode('||',$value['insuff_raised_date']));
                        }
                        if(!empty($value['insuff_clear_date'])) 
                        {  
                            array_push($insuff_clear_date,explode('||',$value['insuff_clear_date']));
                        }
                    } 
                }
                if (!empty($result)) {
                    $counter = 1;
                    foreach ($result as $key => $value) {
                        if ($counter > 2) {
                            continue;
                        }

                       
                        if($value['verfstatus'] != "")
                        {
                            if($value['verfstatus'] == "Major Discrepancy")
                            {
                                $status_value = $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "Insufficiency"){

                                $status_value = $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "WIP"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "Insufficiency Cleared"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "Final QC Reject"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "First QC Reject"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "New Check"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "YTR"){

                                $status_value = "WIP";
                            }  
                            elseif($value['verfstatus'] == "Follow Up"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "Re-Initiated"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "Clear"){

                                $status_value =  $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "Stop Check"){

                                $status_value =  $value['verfstatus'];
                            }
                             elseif($value['verfstatus'] == "Minor Discrepancy"){

                                $status_value = $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "Unable to verify"){

                                $status_value = $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "Worked with the same organization"){

                                $status_value = $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "Overseas check"){

                                $status_value = $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "No Record Found"){

                                $status_value = $value['verfstatus'];
                            }
                            else{
                                $status_value = $value['verfstatus'];
                            }
                            
                        }
                        else{
                            $status_value = "WIP";
                        }

                        if($value['closuredate'] != "")
                        {
                            if($value['verfstatus'] == "Major Discrepancy")
                            {
                                $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "Insufficiency"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "WIP"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "Insufficiency Cleared"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "Final QC Reject"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "First QC Reject"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "New Check"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "YTR"){

                                $closuredate = "NA";
                            }  
                            elseif($value['verfstatus'] == "Follow Up"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "Re-Initiated"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "Clear"){

                               $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "Stop Check"){

                                $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                             elseif($value['verfstatus'] == "Minor Discrepancy"){

                                $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "Unable to verify"){

                                $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "Worked with the same organization"){

                                $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "Overseas check"){

                                $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "No Record Found"){

                                $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            else{
                                $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            
                        }
                        else{
                            $closuredate = "NA";
                        }

                        $data_arry[$x]["refver"] =  $status_value;

                        $data_arry[$x]["refver_closure"] =  $closuredate;

                        $counter++;
                    }
                }
            }

            if (in_array('courtver', $component_id)) {
                $result = $this->report_generated_user->get_court_ver_status_for_export(array('courtver.candsid' => $cands_result['id']));
                if (!empty($result)) {

                    foreach ($result as $key => $value) {

                       
                        if(!empty($value['insuff_raised_date'])) 
                        {  
                            array_push($insuff_raise_date,explode('||',$value['insuff_raised_date']));
                        }
                        if(!empty($value['insuff_clear_date'])) 
                        {  
                            array_push($insuff_clear_date,explode('||',$value['insuff_clear_date']));
                        }
                    } 
                }
                if (!empty($result)) {
                    $counter = 1;
                    foreach ($result as $key => $value) {
                        if ($counter > 2) {
                            continue;
                        }

                      
                       
                        if($value['verfstatus'] != "")
                        {
                            if($value['verfstatus'] == "Major Discrepancy")
                            {
                                $status_value = $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "Insufficiency"){

                                $status_value = $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "WIP"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "Insufficiency Cleared"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "Final QC Reject"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "First QC Reject"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "New Check"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "YTR"){

                                $status_value = "WIP";
                            }  
                            elseif($value['verfstatus'] == "Follow Up"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "Re-Initiated"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "Clear"){

                                $status_value =  $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "Stop Check"){

                                $status_value =  $value['verfstatus'];
                            }
                             elseif($value['verfstatus'] == "Minor Discrepancy"){

                                $status_value = $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "Unable to verify"){

                                $status_value = $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "Worked with the same organization"){

                                $status_value = $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "Overseas check"){

                                $status_value = $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "No Record Found"){

                                $status_value = $value['verfstatus'];
                            }
                            else{
                                $status_value = $value['verfstatus'];
                            }
                            
                        }
                        else{
                            $status_value = "WIP";
                        }

                        if($value['closuredate'] != "")
                        {
                            if($value['verfstatus'] == "Major Discrepancy")
                            {
                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "Insufficiency"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "WIP"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "Insufficiency Cleared"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "Final QC Reject"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "First QC Reject"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "New Check"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "YTR"){

                                $closuredate = "NA";
                            }  
                            elseif($value['verfstatus'] == "Follow Up"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "Re-Initiated"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "Clear"){

                                $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "Stop Check"){

                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                             elseif($value['verfstatus'] == "Minor Discrepancy"){

                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "Unable to verify"){

                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "Worked with the same organization"){

                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "Overseas check"){

                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "No Record Found"){

                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            else{
                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            
                        }
                        else{
                            $closuredate = "NA";
                        }

                        $data_arry[$x]["courtver"] =  $status_value;

                        $data_arry[$x]["courtver_closure"] =  $closuredate;

                        $counter++;
                    }
                }
            }
            if (in_array('crimver', $component_id)) {
                $result = $this->report_generated_user->get_pcc_ver_status_for_export(array('pcc.candsid' => $cands_result['id']));
                if (!empty($result)) {

                    foreach ($result as $key => $value) {

                       
                        if(!empty($value['insuff_raised_date'])) 
                        {  
                            array_push($insuff_raise_date,explode('||',$value['insuff_raised_date']));
                        }
                        if(!empty($value['insuff_clear_date'])) 
                        {  
                            array_push($insuff_clear_date,explode('||',$value['insuff_clear_date']));
                        }
                    } 
                }
                if (!empty($result)) {

                    $counter = 1;
                    foreach ($result as $key => $value) {
                        if ($counter > 2) {
                            continue;
                        }
                       
                        if($value['verfstatus'] != "")
                        {
                            if($value['verfstatus'] == "Major Discrepancy")
                            {
                                $status_value = $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "Insufficiency"){

                                $status_value = $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "WIP"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "Insufficiency Cleared"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "Final QC Reject"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "First QC Reject"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "New Check"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "YTR"){

                                $status_value = "WIP";
                            }  
                            elseif($value['verfstatus'] == "Follow Up"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "Re-Initiated"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "Clear"){

                                $status_value =  $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "Stop Check"){

                                $status_value =  $value['verfstatus'];
                            }
                             elseif($value['verfstatus'] == "Minor Discrepancy"){

                                $status_value = $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "Unable to verify"){

                                $status_value = $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "Worked with the same organization"){

                                $status_value = $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "Overseas check"){

                                $status_value = $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "No Record Found"){

                                $status_value = $value['verfstatus'];
                            }
                            else{
                                $status_value = $value['verfstatus'];
                            }
                            
                        }
                        else{
                            $status_value = "WIP";
                        }

                        if($value['closuredate'] != "")
                        {
                            if($value['verfstatus'] == "Major Discrepancy")
                            {
                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "Insufficiency"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "WIP"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "Insufficiency Cleared"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "Final QC Reject"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "First QC Reject"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "New Check"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "YTR"){

                                $closuredate = "NA";
                            }  
                            elseif($value['verfstatus'] == "Follow Up"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "Re-Initiated"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "Clear"){

                                $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "Stop Check"){

                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                             elseif($value['verfstatus'] == "Minor Discrepancy"){

                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "Unable to verify"){

                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "Worked with the same organization"){

                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "Overseas check"){

                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "No Record Found"){

                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            else{
                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            
                        }
                        else{
                            $closuredate = "NA";
                        }

                        $data_arry[$x]["crimver"] =  $status_value;

                        $data_arry[$x]["crimver_closure"] =  $closuredate;

                        $counter++;
                    }
                }
            }

            if (in_array('globdbver', $component_id)) {
                $result = $this->report_generated_user->get_globdbver_ver_status_for_export(array('glodbver.candsid' => $cands_result['id']));
                if (!empty($result)) {

                    foreach ($result as $key => $value) {

                       
                        if(!empty($value['insuff_raised_date'])) 
                        {  
                            array_push($insuff_raise_date,explode('||',$value['insuff_raised_date']));
                        }
                        if(!empty($value['insuff_clear_date'])) 
                        {  
                            array_push($insuff_clear_date,explode('||',$value['insuff_clear_date']));
                        }
                    } 
                }
                if (!empty($result)) {

                    $counter = 1;
                    foreach ($result as $key => $value) {
                        if ($counter > 2) {
                            continue;
                        }

                        if($value['verfstatus'] != "")
                        {
                            if($value['verfstatus'] == "Major Discrepancy")
                            {
                                $status_value = $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "Insufficiency"){

                                $status_value = $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "WIP"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "Insufficiency Cleared"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "Final QC Reject"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "First QC Reject"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "New Check"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "YTR"){

                                $status_value = "WIP";
                            }  
                            elseif($value['verfstatus'] == "Follow Up"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "Re-Initiated"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "Clear"){

                                $status_value =  $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "Stop Check"){

                                $status_value =  $value['verfstatus'];
                            }
                             elseif($value['verfstatus'] == "Minor Discrepancy"){

                                $status_value = $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "Unable to verify"){

                                $status_value = $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "Worked with the same organization"){

                                $status_value = $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "Overseas check"){

                                $status_value = $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "No Record Found"){

                                $status_value = $value['verfstatus'];
                            }
                            else{
                                $status_value = $value['verfstatus'];
                            }
                            
                        }
                        else{
                            $status_value = "WIP";
                        }

                        if($value['closuredate'] != "")
                        {
                            if($value['verfstatus'] == "Major Discrepancy")
                            {
                                $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "Insufficiency"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "WIP"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "Insufficiency Cleared"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "Final QC Reject"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "First QC Reject"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "New Check"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "YTR"){

                                $closuredate = "NA";
                            }  
                            elseif($value['verfstatus'] == "Follow Up"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "Re-Initiated"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "Clear"){

                               $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "Stop Check"){

                                $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                             elseif($value['verfstatus'] == "Minor Discrepancy"){

                                $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "Unable to verify"){

                                $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "Worked with the same organization"){

                                $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "Overseas check"){

                                $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "No Record Found"){

                                $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            else{
                                $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            
                        }
                        else{
                            $closuredate = "NA";
                        }

                        $data_arry[$x]["glodbver"] =  $status_value;

                        $data_arry[$x]["glodbver_closure"] =  $closuredate;

                        $counter++;
                    }
                }
            }
            if (in_array('identity', $component_id)) {
                $result = $this->report_generated_user->get_identity_ver_status_for_export(array('identity.candsid' => $cands_result['id']));
                if (!empty($result)) {

                    foreach ($result as $key => $value) {

                        
                        if(!empty($value['insuff_raised_date'])) 
                        {  
                            array_push($insuff_raise_date,explode('||',$value['insuff_raised_date']));
                        }
                        if(!empty($value['insuff_clear_date'])) 
                        {  
                            array_push($insuff_clear_date,explode('||',$value['insuff_clear_date']));
                        }
                    } 
                }

                if (!empty($result)) {

                    $counter = 1;
                    foreach ($result as $key => $value) {
                        if ($counter > 2) {
                            continue;
                        }

                      
                        if($value['verfstatus'] != "")
                        {
                            if($value['verfstatus'] == "Major Discrepancy")
                            {
                                $status_value = $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "Insufficiency"){

                                $status_value = $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "WIP"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "Insufficiency Cleared"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "Final QC Reject"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "First QC Reject"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "New Check"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "YTR"){

                                $status_value = "WIP";
                            }  
                            elseif($value['verfstatus'] == "Follow Up"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "Re-Initiated"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "Clear"){

                                $status_value =  $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "Stop Check"){

                                $status_value =  $value['verfstatus'];
                            }
                             elseif($value['verfstatus'] == "Minor Discrepancy"){

                                $status_value = $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "Unable to verify"){

                                $status_value = $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "Worked with the same organization"){

                                $status_value = $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "Overseas check"){

                                $status_value = $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "No Record Found"){

                                $status_value = $value['verfstatus'];
                            }
                            else{
                                $status_value = $value['verfstatus'];
                            }
                            
                        }
                        else{
                            $status_value = "WIP";
                        }

                        if($value['closuredate'] != "")
                        {
                            if($value['verfstatus'] == "Major Discrepancy")
                            {
                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "Insufficiency"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "WIP"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "Insufficiency Cleared"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "Final QC Reject"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "First QC Reject"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "New Check"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "YTR"){

                                $closuredate = "NA";
                            }  
                            elseif($value['verfstatus'] == "Follow Up"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "Re-Initiated"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "Clear"){

                                $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "Stop Check"){

                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                             elseif($value['verfstatus'] == "Minor Discrepancy"){

                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "Unable to verify"){

                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "Worked with the same organization"){

                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "Overseas check"){

                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "No Record Found"){

                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            else{
                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            
                        }
                        else{
                            $closuredate = "NA";
                        }

                        $data_arry[$x]["identity"] =  $status_value;

                        $data_arry[$x]["identity_closure"] =  $closuredate;
                        $counter++;
                    }
                }
            }

            if (in_array('cbrver', $component_id)) {
                $result = $this->report_generated_user->get_credit_report_ver_status_for_export(array('credit_report.candsid' => $cands_result['id']));
                if (!empty($result)) {

                    foreach ($result as $key => $value) {

                       
                        if(!empty($value['insuff_raised_date'])) 
                        {  
                            array_push($insuff_raise_date,explode('||',$value['insuff_raised_date']));
                        }
                        if(!empty($value['insuff_clear_date'])) 
                        {  
                            array_push($insuff_clear_date,explode('||',$value['insuff_clear_date']));
                        }
                    } 
                }

                if (!empty($result)) {

                    $counter = 1;
                    foreach ($result as $key => $value) {

                        if ($counter > 2) {

                            continue;
                        }

                        
                        if($value['verfstatus'] != "")
                        {
                            if($value['verfstatus'] == "Major Discrepancy")
                            {
                                $status_value = $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "Insufficiency"){

                                $status_value = $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "WIP"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "Insufficiency Cleared"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "Final QC Reject"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "First QC Reject"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "New Check"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "YTR"){

                                $status_value = "WIP";
                            }  
                            elseif($value['verfstatus'] == "Follow Up"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "Re-Initiated"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "Clear"){

                                $status_value =  $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "Stop Check"){

                                $status_value =  $value['verfstatus'];
                            }
                             elseif($value['verfstatus'] == "Minor Discrepancy"){

                                $status_value = $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "Unable to verify"){

                                $status_value = $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "Worked with the same organization"){

                                $status_value = $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "Overseas check"){

                                $status_value = $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "No Record Found"){

                                $status_value = $value['verfstatus'];
                            }
                            else{
                                $status_value = $value['verfstatus'];
                            }
                            
                        }
                        else{
                            $status_value = "WIP";
                        }

                        if($value['closuredate'] != "")
                        {
                            if($value['verfstatus'] == "Major Discrepancy")
                            {
                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "Insufficiency"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "WIP"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "Insufficiency Cleared"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "Final QC Reject"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "First QC Reject"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "New Check"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "YTR"){

                                $closuredate = "NA";
                            }  
                            elseif($value['verfstatus'] == "Follow Up"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "Re-Initiated"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "Clear"){

                                $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "Stop Check"){

                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                             elseif($value['verfstatus'] == "Minor Discrepancy"){

                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "Unable to verify"){

                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "Worked with the same organization"){

                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "Overseas check"){

                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "No Record Found"){

                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            else{
                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            
                        }
                        else{
                            $closuredate = "NA";
                        }

                        $data_arry[$x]["cbrver"] =  $status_value;

                        $data_arry[$x]["cbrver_closure"] =  $closuredate;

                        $counter++;
                    }

                }
            }

            if (in_array('narcver', $component_id)) {
                $result = $this->report_generated_user->get_drugs_report_ver_status_for_export(array('drug_narcotis.candsid' => $cands_result['id']));
                if (!empty($result)) {

                    foreach ($result as $key => $value) {

                        
                        if(!empty($value['insuff_raised_date'])) 
                        {  
                            array_push($insuff_raise_date,explode('||',$value['insuff_raised_date']));
                        }
                        if(!empty($value['insuff_clear_date'])) 
                        {  
                            array_push($insuff_clear_date,explode('||',$value['insuff_clear_date']));
                        }
                    } 
                }

                if (!empty($result)) {

                    $counter = 1;
                    foreach ($result as $key => $value) {

                        if ($counter > 2) {

                            continue;
                        }

                      
                        if($value['verfstatus'] != "")
                        {
                            if($value['verfstatus'] == "Major Discrepancy")
                            {
                                $status_value = $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "Insufficiency"){

                                $status_value = $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "WIP"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "Insufficiency Cleared"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "Final QC Reject"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "First QC Reject"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "New Check"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "YTR"){

                                $status_value = "WIP";
                            }  
                            elseif($value['verfstatus'] == "Follow Up"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "Re-Initiated"){

                                $status_value = "WIP";
                            }
                            elseif($value['verfstatus'] == "Clear"){

                                $status_value =  $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "Stop Check"){

                                $status_value =  $value['verfstatus'];
                            }
                             elseif($value['verfstatus'] == "Minor Discrepancy"){

                                $status_value = $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "Unable to verify"){

                                $status_value = $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "Worked with the same organization"){

                                $status_value = $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "Overseas check"){

                                $status_value = $value['verfstatus'];
                            }
                            elseif($value['verfstatus'] == "No Record Found"){

                                $status_value = $value['verfstatus'];
                            }
                            else{
                                $status_value = $value['verfstatus'];
                            }
                            
                        }
                        else{
                            $status_value = "WIP";
                        }

                        if($value['closuredate'] != "")
                        {
                            if($value['verfstatus'] == "Major Discrepancy")
                            {
                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "Insufficiency"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "WIP"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "Insufficiency Cleared"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "Final QC Reject"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "First QC Reject"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "New Check"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "YTR"){

                                $closuredate = "NA";
                            }  
                            elseif($value['verfstatus'] == "Follow Up"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "Re-Initiated"){

                                $closuredate = "NA";
                            }
                            elseif($value['verfstatus'] == "Clear"){

                                $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "Stop Check"){

                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                             elseif($value['verfstatus'] == "Minor Discrepancy"){

                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "Unable to verify"){

                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "Worked with the same organization"){

                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "Overseas check"){

                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            elseif($value['verfstatus'] == "No Record Found"){

                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            else{
                                 $closuredate = convert_db_to_display_date($value['closuredate']);
                            }
                            
                        }
                        else{
                            $closuredate = "NA";
                        }

                        $data_arry[$x]["drugs"] =  $status_value;

                        $data_arry[$x]["drugs_closure"] =  $closuredate;

                        $counter++;
                    }

                }
            }

            $raisedsingleArray = []; 
            foreach ($insuff_raise_date as $childArray) 
            { 
                foreach ($childArray as $value) 
                { 
                $raisedsingleArray[] = $value; 
                } 
            }
            $clearsingleArray = []; 
            foreach ($insuff_clear_date as $childArray) 
            { 
                foreach ($childArray as $value) 
                { 
                $clearsingleArray[] = $value; 
                } 
            }
            $data_arry[$x]["insuff_raised_date"] =  min($raisedsingleArray);

            $data_arry[$x]["insuff_clear_date"] = max($clearsingleArray);


            $x++;
        }
        return $data_arry;
    }
    
    public function export_to_excel_component($parameters_array = null, $component_name, $client_id, $status_value, $from_date,$to_date, $client_name)
    {
        
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $this->load->model('report_generated_user');

        $file_upload_path = SITE_BASE_PATH . UPLOAD_FOLDER . 'bulk_reports';

        if (!folder_exist($file_upload_path)) {
            mkdir($file_upload_path, 0777);
        }
        if (!folder_exist($file_upload_path)) {
            mkdir($file_upload_path, 0777);
        } else if (!is_writable($file_upload_path)) {
            mkdir($file_upload_path, 0777);
        }


        
        if($component_name == "1")
        {
            $this->load->model('addressver_model');
            $all_records = $this->addressver_model->get_all_address_by_client($client_id, $status_value, $from_date, $to_date,false);

                require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
                // Create new Spreadsheet object
                $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

                // Set document properties
                $spreadsheet->getProperties()->setCreator(CRMNAME)
                    ->setLastModifiedBy(CRMNAME)
                    ->setTitle(CRMNAME)
                    ->setSubject('Address records')
                    ->setDescription('Address records with their status');

                // add style to the header
                $styleArray = array(
                    'font' => array(
                        'bold' => true,
                    ),
                    'alignment' => array(
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ),
                    'borders' => array(
                        'top' => array(
                            'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ),
                    ),
                    'fill' => array(
                        'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                        'rotation' => 90,
                        'startcolor' => array(
                            'argb' => 'FFA0A0A0',
                        ),
                        'endcolor' => array(
                            'argb' => 'FFFFFFFF',
                        ),
                    ),
                );
                $spreadsheet->getActiveSheet()->getStyle('A1:AA1')->applyFromArray($styleArray);
                // auto fit column to content
                foreach (range('A', 'AA') as $columnID) {
                    $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                        ->setWidth(20);
                }

                // set the names of header cells
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A1", 'Client Name')
                    ->setCellValue("B1", 'Entity')
                    ->setCellValue("C1", 'Package')
                    ->setCellValue("D1", REFNO)
                    ->setCellValue("E1", 'Component Ref No')
                    ->setCellValue("F1", 'Client Ref No')
                    ->setCellValue("G1", 'Transaction No')
                    ->setCellValue("H1", 'Comp Received date')
                    ->setCellValue("I1", 'Candidate Name')
                    ->setCellValue("J1", 'Fathers Name')
                    ->setCellValue("K1", 'Primary Contact')
                    ->setCellValue("L1", 'Contact No (2)')
                    ->setCellValue("M1", 'Contact No (3)')
                    ->setCellValue("N1", 'Address')
                    ->setCellValue("O1", 'City')
                    ->setCellValue("P1", 'Pincode')
                    ->setCellValue("Q1", 'State')
                    ->setCellValue("R1", 'Status')
                    ->setCellValue("S1", 'Sub Status')
                    ->setCellValue("T1", 'Executive Name')
                    ->setCellValue("U1", 'Vendor')
                    ->setCellValue("V1", 'Vendor Status')
                    ->setCellValue("W1", 'Vendor Assigned on')
                    ->setCellValue("X1", 'Closure Date')
                    ->setCellValue("Y1", 'Insuff Raised Date')
                    ->setCellValue("Z1", 'Insuff Clear Date')
                    ->setCellValue("AA1", 'Insuff Remark');
                // Add some data
                $x = 2;
                foreach ($all_records as $all_record) {

                    $ad_status = ($all_record['verfstatus'] != "") ? $all_record['verfstatus'] : "WIP";
                    $ad_filter_status = ($all_record['filter_status'] != "") ? $all_record['filter_status'] : "WIP";
                    $closuredate = ($all_record['filter_status'] == "Closed") ? convert_db_to_display_date($all_record['closuredate']) : "NA";
                    $insuff_remarks = $all_record['insuff_raise_remark'];

                    $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue("A$x", ucwords($all_record['clientname']))
                        ->setCellValue("B$x", ucwords($all_record['entity_name']))
                        ->setCellValue("C$x", ucwords($all_record['package_name']))
                        ->setCellValue("D$x", $all_record['cmp_ref_no'])
                        ->setCellValue("E$x", $all_record['add_com_ref'])
                        ->setCellValue("F$x", $all_record['ClientRefNumber'])
                        ->setCellValue("G$x", $all_record['transaction_id'])
                        ->setCellValue("H$x", convert_db_to_display_date($all_record['iniated_date']))
                        ->setCellValue("I$x", ucwords($all_record['CandidateName']))
                        ->setCellValue("J$x", ucwords($all_record['NameofCandidateFather']))
                        ->setCellValue("K$x", $all_record['CandidatesContactNumber'])
                        ->setCellValue("L$x", $all_record['ContactNo1'])
                        ->setCellValue("M$x", $all_record['ContactNo2'])
                        ->setCellValue("N$x", $all_record['address'])
                        ->setCellValue("O$x", $all_record['city'])
                        ->setCellValue("P$x", $all_record['pincode'])
                        ->setCellValue("Q$x", $all_record['state'])
                        ->setCellValue("R$x", $ad_filter_status)
                        ->setCellValue("S$x", $ad_status)
                        ->setCellValue("T$x", $all_record['executive_name'])
                        ->setCellValue("U$x", $all_record['vendor_name'])
                        ->setCellValue("V$x", ucwords($all_record['vendor_status']))
                        ->setCellValue("W$x", convert_db_to_display_date($all_record['vendor_assgined_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12))
                        ->setCellValue("X$x", $closuredate)
                        ->setCellValue("Y$x", $all_record['insuff_raised_date'])
                        ->setCellValue("Z$x", $all_record['insuff_clear_date'])
                        ->setCellValue("AA$x", $insuff_remarks);

                    $x++;
                }
                // Rename worksheet
                $spreadsheet->getActiveSheet()->setTitle('Address Records');

                $spreadsheet->setActiveSheetIndex(0);

        }

        if($component_name == "6")
        {

            $this->load->model('employment_model');
            $all_records = $this->employment_model->get_all_employment_by_client($client_id, $fil_by_status, $from_date, $to_date);

            require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
            // Create new Spreadsheet object
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            // Set document properties
            $spreadsheet->getProperties()->setCreator(CRMNAME)
                ->setLastModifiedBy(CRMNAME)
                ->setTitle(CRMNAME)
                ->setSubject('Employment records')
                ->setDescription('Employment records with their status');
            // add style to the header
            $styleArray = array(
                'font' => array(
                    'bold' => true,
                ),
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ),
                'borders' => array(
                    'top' => array(
                        'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ),
                ),
                'fill' => array(
                    'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => array(
                        'argb' => 'FFA0A0A0',
                    ),
                    'endcolor' => array(
                        'argb' => 'FFFFFFFF',
                    ),
                ),
            );
            $spreadsheet->getActiveSheet()->getStyle('A1:AF1')->applyFromArray($styleArray);
            // auto fit column to content
            foreach (range('A', 'AF') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                    ->setWidth(20);
            }
            // set the names of header cells
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A1", 'Client Name')
                ->setCellValue("B1", 'Entity')
                ->setCellValue("C1", 'Package')
                ->setCellValue("D1", REFNO)
                ->setCellValue("E1", 'Component Ref No')
                ->setCellValue("F1", 'Client Ref No')
                ->setCellValue("G1", 'Transaction No')
                ->setCellValue("H1", 'Comp Received date')
                ->setCellValue("I1", 'Candidate Name')
                ->setCellValue("J1", 'Company Name')
                ->setCellValue("K1", 'Deputed Company')
                ->setCellValue("L1", 'Previous Employee Code')
                ->setCellValue("M1", 'Employed From')
                ->setCellValue("N1", 'Employed To')
                ->setCellValue("O1", 'Designation')
                ->setCellValue("P1", 'Status')
                ->setCellValue("Q1", 'Sub Status')
                ->setCellValue("R1", 'Executive Name')
                ->setCellValue("S1", 'Site Visit')
                ->setCellValue("T1", 'Vendor')
                ->setCellValue("U1", 'Vendor Status')
                ->setCellValue("V1", 'Vendor Assigned on')
                ->setCellValue("W1", 'Closure Date')
                ->setCellValue("X1", 'Insuff Raised Date')
                ->setCellValue("Y1", 'Insuff Clear Date')
                ->setCellValue("Z1", 'Insuff Remark')
                ->setCellValue("AA1", 'Supervisor Name')
                ->setCellValue("AB1", 'Supervisor Contact')
                ->setCellValue("AC1", 'Designation')
                ->setCellValue("AD1", 'Supervisor Email ID')
                ->setCellValue("AE1", 'UAN No')
                ->setCellValue("AF1", 'UAN Remark');
            // Add some data
            $x = 2;

            foreach ($all_records as $all_record) {

                $select_supervisor_details = $this->employment_model->supervison_details(array('empver_id' => $all_record['id'],'status'=> 1 ));

                if(!empty($select_supervisor_details))
                {
                    $supervisor_name = $select_supervisor_details[0]['supervisor_name'];
                    $supervisor_designation = $select_supervisor_details[0]['supervisor_designation'];
                    $supervisor_contact_details = $select_supervisor_details[0]['supervisor_contact_details'];
                    $supervisor_email_id = $select_supervisor_details[0]['supervisor_email_id'];

                }
                else
                { 

                   $supervisor_name = "";
                   $supervisor_designation = "";
                   $supervisor_contact_details = "";
                   $supervisor_email_id = "";


                }

                $emp_status = ($all_record['verfstatus'] != "") ? $all_record['verfstatus'] : "WIP";
                $emp_filter_status = ($all_record['filter_status'] != "") ? $all_record['filter_status'] : "WIP";

                $closuredate = ($all_record['filter_status'] == "Closed") ? convert_db_to_display_date($all_record['closuredate']) : "NA";
                $insuff_remarks = $all_record['insuff_raise_remark'];

                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A$x", ucwords($all_record['clientname']))
                    ->setCellValue("B$x", ucwords($all_record['entity_name']))
                    ->setCellValue("C$x", ucwords($all_record['package_name']))
                    ->setCellValue("D$x", $all_record['cmp_ref_no'])
                    ->setCellValue("E$x", $all_record['emp_com_ref'])
                    ->setCellValue("F$x", $all_record['ClientRefNumber'])
                    ->setCellValue("G$x", $all_record['transaction_id'])
                    ->setCellValue("H$x", convert_db_to_display_date($all_record['iniated_date']))
                    ->setCellValue("I$x", ucwords($all_record['CandidateName']))
                    ->setCellValue("J$x", ucwords($all_record['coname']))
                    ->setCellValue("K$x", ucwords($all_record['deputed_company']))
                    ->setCellValue("L$x", $all_record['empid'])
                    ->setCellValue("M$x", $all_record['empfrom'])
                    ->setCellValue("N$x", $all_record['empto'])
                    ->setCellValue("O$x", ucwords($all_record['designation']))
                    ->setCellValue("P$x", $emp_filter_status)
                    ->setCellValue("Q$x", $emp_status)
                    ->setCellValue("R$x", $all_record['executive_name'])
                    ->setCellValue("S$x", $all_record['field_visit_status'])
                    ->setCellValue("T$x", $all_record['vendor_name'])
                    ->setCellValue("U$x", ucwords($all_record['vendor_status']))
                    ->setCellValue("V$x", convert_db_to_display_date($all_record['vendor_assgined_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12))
                    ->setCellValue("W$x", $closuredate)
                    ->setCellValue("X$x", $all_record['insuff_raised_date'])
                    ->setCellValue("Y$x", $all_record['insuff_clear_date'])
                    ->setCellValue("Z$x", $insuff_remarks)
                    ->setCellValue("AA$x", $supervisor_name)
                    ->setCellValue("AB$x", $supervisor_contact_details)
                    ->setCellValue("AC$x", $supervisor_designation)
                    ->setCellValue("AD$x", $supervisor_email_id)
                    ->setCellValue("AE$x", $all_record['uan_no'])
                    ->setCellValue("AF$x", $all_record['uan_remark']);
                $x++;
            } 

            $spreadsheet->getActiveSheet()->setTitle('Employment Records');

            $spreadsheet->setActiveSheetIndex(0);

        }

        if($component_name == "5")
        {
           $this->load->model('education_model');

            $all_records = $this->education_model->get_all_education_by_client($client_id, $fil_by_status, $from_date, $to_date);

            require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
            // Create new Spreadsheet object
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

            // Set document properties
            $spreadsheet->getProperties()->setCreator(CRMNAME)
                ->setLastModifiedBy(CRMNAME)
                ->setTitle(CRMNAME)
                ->setSubject('Education records')
                ->setDescription('Education records with their status');

            // add style to the header
            $styleArray = array(
                'font' => array(
                    'bold' => true,
                ),
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ),
                'borders' => array(
                    'top' => array(
                        'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ),
                ),
                'fill' => array(
                    'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => array(
                        'argb' => 'FFA0A0A0',
                    ),
                    'endcolor' => array(
                        'argb' => 'FFFFFFFF',
                    ),
                ),
            );
            $spreadsheet->getActiveSheet()->getStyle('A1:AE1')->applyFromArray($styleArray);
            // auto fit column to content
            foreach (range('A', 'AE') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                    ->setWidth(20);
            }

            // set the names of header cells
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A1", 'Client Name')
                ->setCellValue("B1", 'Entity')
                ->setCellValue("C1", 'Package')
                ->setCellValue("D1", REFNO)
                ->setCellValue("E1", 'Component Ref No')
                ->setCellValue("F1", 'Client Ref No')
                ->setCellValue("G1", 'Transaction No')
                ->setCellValue("H1", 'Comp Received date')
                ->setCellValue("I1", 'Candidate Name')
                ->setCellValue("J1", 'Fathers Name')
                ->setCellValue("K1", 'Candidate DOB')
                ->setCellValue("L1", 'University')
                ->setCellValue("M1", 'Qualification')
                ->setCellValue("N1", 'Year of Passing')
                ->setCellValue("O1", 'Status')
                ->setCellValue("P1", 'Sub Status')
                ->setCellValue("Q1", 'Executive Name')
                ->setCellValue("R1", 'Verifier/SPOC Assigned On')
                ->setCellValue("S1", 'Verifier/SPOC')
                ->setCellValue("T1", 'Verifier/SPOC Status')
                ->setCellValue("U1", 'Verifier/SPOC Actual Status')
                ->setCellValue("V1", 'Verifier/SPOC closure Date')
                ->setCellValue("W1", 'Vendor Assigned on')
                ->setCellValue("X1", 'Vendor')
                ->setCellValue("Y1", 'Vendor Status')
                ->setCellValue("Z1", 'Vendor Actual Status')
                ->setCellValue("AA1", 'Vendor Closure Date')
                ->setCellValue("AB1", 'Closure Date')
                ->setCellValue("AC1", 'Insuff Raised Date')
                ->setCellValue("AD1", 'Insuff Clear Date')
                ->setCellValue("AE1", 'Insuff Remark');
            // Add some data
            $x = 2;
            foreach ($all_records as $all_record) {

                if($all_record['verifiers_spoc_status'] == 1)
                {
                    $verifiers_spoc_assigned_on = convert_db_to_display_date($all_record['verifiers_spoc_created'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);
                }
                else
                {
                    $verifiers_spoc_assigned_on = convert_db_to_display_date($all_record['verifiers_spoc_modified'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);
                }

                $edu_status = ($all_record['verfstatus'] != "") ? $all_record['verfstatus'] : "WIP";
                $edu_filter_status = ($all_record['filter_status'] != "") ? $all_record['filter_status'] : "WIP";
                $closuredate = ($all_record['filter_status'] == "Closed") ? convert_db_to_display_date($all_record['closuredate']) : "NA";
                $insuff_remarks = $all_record['insuff_raise_remark'];



                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A$x", ucwords($all_record['clientname']))
                    ->setCellValue("B$x", ucwords($all_record['entity_name']))
                    ->setCellValue("C$x", ucwords($all_record['package_name']))
                    ->setCellValue("D$x", $all_record['cmp_ref_no'])
                    ->setCellValue("E$x", $all_record['education_com_ref'])
                    ->setCellValue("F$x", $all_record['ClientRefNumber'])
                    ->setCellValue("G$x", $all_record['transaction_id'])
                    ->setCellValue("H$x", convert_db_to_display_date($all_record['iniated_date']))
                    ->setCellValue("I$x", ucwords($all_record['CandidateName']))
                    ->setCellValue("J$x", ucwords($all_record['NameofCandidateFather']))
                    ->setCellValue("K$x", convert_db_to_display_date($all_record['DateofBirth']))
                    ->setCellValue("L$x", $all_record['university_name'])
                    ->setCellValue("M$x", $all_record['qualification_name'])
                    ->setCellValue("N$x", $all_record['year_of_passing'])
                    ->setCellValue("O$x", $edu_filter_status)
                    ->setCellValue("P$x", $edu_status)
                    ->setCellValue("Q$x", $all_record['executive_name'])
                    ->setCellValue("R$x", $verifiers_spoc_assigned_on)
                    ->setCellValue("S$x", ucwords($all_record['vendor_name']))
                    ->setCellValue("T$x", ucwords($all_record['verifier_vendor_status']))
                    ->setCellValue("U$x", ucwords($all_record['verifier_vendor_actual_status']))
                    ->setCellValue("V$x", convert_db_to_display_date($all_record['verifier_closure_date'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12))

                    ->setCellValue("W$x", convert_db_to_display_date($all_record['verifiers_stamp_created'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12))
                    ->setCellValue("X$x", ucwords($all_record['vendor_stamp_name']))
                    ->setCellValue("Y$x", ucwords($all_record['stamp_vendor_status']))
                    ->setCellValue("Z$x", ucwords($all_record['stamp_vendor_actual_status']))
                    ->setCellValue("AA$x", convert_db_to_display_date($all_record['stamp_closure_date'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12))

                    ->setCellValue("AB$x", $closuredate)
                    ->setCellValue("AC$x", $all_record['insuff_raised_date'])
                    ->setCellValue("AD$x", $all_record['insuff_clear_date'])
                    ->setCellValue("AE$x", $insuff_remarks);

                $x++;
            }
            // Rename worksheet
            $spreadsheet->getActiveSheet()->setTitle('Education Records');

            $spreadsheet->setActiveSheetIndex(0);
        }
        if($component_name == "9")
        {
            $this->load->model('reference_verificatiion_model');
            $all_records = $this->reference_verificatiion_model->get_all_reference_verification_by_client($client_id, $fil_by_status, $from_date, $to_date);

            require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
            // Create new Spreadsheet object
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

            // Set document properties
            $spreadsheet->getProperties()->setCreator(CRMNAME)
                ->setLastModifiedBy(CRMNAME)
                ->setTitle(CRMNAME)
                ->setSubject('Reference Verification records')
                ->setDescription('Reference Verification records with their status');

            // add style to the header
            $styleArray = array(
                'font' => array(
                    'bold' => true,
                ),
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ),
                'borders' => array(
                    'top' => array(
                        'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ),
                ),
                'fill' => array(
                    'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => array(
                        'argb' => 'FFA0A0A0',
                    ),
                    'endcolor' => array(
                        'argb' => 'FFFFFFFF',
                    ),
                ),
            );
            $spreadsheet->getActiveSheet()->getStyle('A1:Q1')->applyFromArray($styleArray);
            // auto fit column to content
            foreach (range('A', 'Q') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                    ->setWidth(20);
            }

            // set the names of header cells
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A1", 'Client Name')
                ->setCellValue("B1", 'Entity')
                ->setCellValue("C1", 'Package')
                ->setCellValue("D1", REFNO)
                ->setCellValue("E1", 'Component Ref No')
                ->setCellValue("F1", 'Client Ref No')
                ->setCellValue("G1", 'Comp Received date')
                ->setCellValue("H1", 'Candidate Name')
                ->setCellValue("I1", 'Fathers Name')
                ->setCellValue("J1", 'Reference Name')
                ->setCellValue("K1", 'Status')
                ->setCellValue("L1", 'Sub Status')
                ->setCellValue("M1", 'Executive Name')
                ->setCellValue("N1", 'Closure Date')
                ->setCellValue("O1", 'Insuff Raised Date')
                ->setCellValue("P1", 'Insuff Clear Date')
                ->setCellValue("Q1", 'Insuff Remark');
            // Add some data
            $x = 2;
            foreach ($all_records as $all_record) {

                $reference_status = ($all_record['verfstatus'] != "") ? $all_record['verfstatus'] : "WIP";
                $reference_filter_status = ($all_record['filter_status'] != "") ? $all_record['filter_status'] : "WIP";
                $closuredate = ($all_record['filter_status'] == "Closed") ? convert_db_to_display_date($all_record['closuredate']) : "NA";
                $insuff_remarks = $all_record['insuff_raise_remark'];

                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A$x", ucwords($all_record['clientname']))
                    ->setCellValue("B$x", ucwords($all_record['entity_name']))
                    ->setCellValue("C$x", ucwords($all_record['package_name']))
                    ->setCellValue("D$x", $all_record['cmp_ref_no'])
                    ->setCellValue("E$x", $all_record['reference_com_ref'])
                    ->setCellValue("F$x", $all_record['ClientRefNumber'])
                    ->setCellValue("G$x", convert_db_to_display_date($all_record['iniated_date']))
                    ->setCellValue("H$x", ucwords($all_record['CandidateName']))
                    ->setCellValue("I$x", ucwords($all_record['NameofCandidateFather']))
                    ->setCellValue("J$x", $all_record['name_of_reference'])
                    ->setCellValue("K$x", $reference_filter_status)
                    ->setCellValue("L$x", $reference_status)
                    ->setCellValue("M$x", $all_record['executive_name'])
                    ->setCellValue("L$x", $reference_status)
                    ->setCellValue("M$x", $all_record['executive_name'])
                    ->setCellValue("N$x", $closuredate)
                    ->setCellValue("O$x", $all_record['insuff_raised_date'])
                    ->setCellValue("P$x", $all_record['insuff_clear_date'])
                    ->setCellValue("Q$x", $insuff_remarks);
                $x++;
            }
            // Rename worksheet
            $spreadsheet->getActiveSheet()->setTitle('Reference Verification Records');

            $spreadsheet->setActiveSheetIndex(0);

        }

        if($component_name == "3")
        {
            $this->load->model('court_verificatiion_model');

            $all_records = $this->court_verificatiion_model->get_all_court_by_client($client_id, $fil_by_status, $from_date, $to_date);

            require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
            // Create new Spreadsheet object
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

            // Set document properties
            $spreadsheet->getProperties()->setCreator(CRMNAME)
                ->setLastModifiedBy(CRMNAME)
                ->setTitle(CRMNAME)
                ->setSubject('Court records')
                ->setDescription('Court records with their status');

            // add style to the header
            $styleArray = array(
                'font' => array(
                    'bold' => true,
                ),
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ),
                'borders' => array(
                    'top' => array(
                        'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ),
                ),
                'fill' => array(
                    'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => array(
                        'argb' => 'FFA0A0A0',
                    ),
                    'endcolor' => array(
                        'argb' => 'FFFFFFFF',
                    ),
                ),
            );
            $spreadsheet->getActiveSheet()->getStyle('A1:AA1')->applyFromArray($styleArray);
            // auto fit column to content
            foreach (range('A', 'AA') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                    ->setWidth(20);
            }

            // set the names of header cells
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A1", 'Client Name')
                ->setCellValue("B1", 'Entity')
                ->setCellValue("C1", 'Package')
                ->setCellValue("D1", REFNO)
                ->setCellValue("E1", 'Component Ref No')
                ->setCellValue("F1", 'Client Ref No')
                ->setCellValue("G1", 'Transaction No')
                ->setCellValue("H1", 'Comp Received date')
                ->setCellValue("I1", 'Candidate Name')
                ->setCellValue("J1", 'Fathers Name')
                ->setCellValue("K1", 'Candidate DOB')
                ->setCellValue("L1", 'Address')
                ->setCellValue("M1", 'City')
                ->setCellValue("N1", 'Pincode')
                ->setCellValue("O1", 'State')
                ->setCellValue("P1", 'Status')
                ->setCellValue("Q1", 'Sub Status')
                ->setCellValue("R1", 'Executive Name')
                ->setCellValue("S1", 'Vendor')
                ->setCellValue("T1", 'Vendor Status')
                ->setCellValue("U1", 'Vendor Final Status')
                ->setCellValue("V1", 'Vendor Assigned on')
                ->setCellValue("W1", 'Closure Date')
                ->setCellValue("X1", 'Insuff Raised Date')
                ->setCellValue("Y1", 'Insuff Clear Date')
                ->setCellValue("Z1", 'Insuff Remark')
                ->setCellValue("AA1", 'Gender');
            // Add some data
            $x = 2;
            foreach ($all_records as $all_record) {

                $court_status = ($all_record['verfstatus'] != "") ? $all_record['verfstatus'] : "WIP";
                $court_filter_status = ($all_record['filter_status'] != "") ? $all_record['filter_status'] : "WIP";
                $closuredate = ($all_record['filter_status'] == "Closed") ? convert_db_to_display_date($all_record['closuredate']) : "NA";
                $insuff_remarks = $all_record['insuff_raise_remark'];

                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A$x", ucwords($all_record['clientname']))
                    ->setCellValue("B$x", ucwords($all_record['entity_name']))
                    ->setCellValue("C$x", ucwords($all_record['package_name']))
                    ->setCellValue("D$x", $all_record['cmp_ref_no'])
                    ->setCellValue("E$x", $all_record['court_com_ref'])
                    ->setCellValue("F$x", $all_record['ClientRefNumber'])
                    ->setCellValue("G$x", $all_record['transaction_id'])
                    ->setCellValue("H$x", convert_db_to_display_date($all_record['iniated_date']))
                    ->setCellValue("I$x", ucwords($all_record['CandidateName']))
                    ->setCellValue("J$x", ucwords($all_record['NameofCandidateFather']))
                    ->setCellValue("K$x", convert_db_to_display_date($all_record['DateofBirth']))
                    ->setCellValue("L$x", $all_record['street_address'])
                    ->setCellValue("M$x", $all_record['city'])
                    ->setCellValue("N$x", $all_record['pincode'])
                    ->setCellValue("O$x", $all_record['state'])
                    ->setCellValue("P$x", $court_filter_status)
                    ->setCellValue("Q$x", $court_status)
                    ->setCellValue("R$x", $all_record['executive_name'])
                    ->setCellValue("S$x", $all_record['vendor_name'])
                    ->setCellValue("T$x", ucwords($all_record['vendor_actual_status']))
                    ->setCellValue("U$x", ucwords($all_record['vendor_status']))
                    ->setCellValue("V$x", convert_db_to_display_date($all_record['vendor_assgined_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12))
                    ->setCellValue("W$x", $closuredate)
                    ->setCellValue("X$x", $all_record['insuff_raised_date'])
                    ->setCellValue("Y$x", $all_record['insuff_clear_date'])
                    ->setCellValue("Z$x", $insuff_remarks)
                    ->setCellValue("AA$x", $all_record['gender']);

                $x++;
            }
            // Rename worksheet
            $spreadsheet->getActiveSheet()->setTitle('Court Records');

            $spreadsheet->setActiveSheetIndex(0);
 
        }
        if($component_name == "7")
        {
            $this->load->model('global_database_model');

            $all_records = $this->global_database_model->get_all_Global_Database_by_client($client_id, $fil_by_status, $from_date, $to_date);


            require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
            // Create new Spreadsheet object
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

            // Set document properties
            $spreadsheet->getProperties()->setCreator(CRMNAME)
                ->setLastModifiedBy(CRMNAME)
                ->setTitle(CRMNAME)
                ->setSubject('Global Database records')
                ->setDescription('Global Database records with their status');

            // add style to the header
            $styleArray = array(
                'font' => array(
                    'bold' => true,
                ),
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ),
                'borders' => array(
                    'top' => array(
                        'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ),
                ),
                'fill' => array(
                    'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => array(
                        'argb' => 'FFA0A0A0',
                    ),
                    'endcolor' => array(
                        'argb' => 'FFFFFFFF',
                    ),
                ),
            );
            $spreadsheet->getActiveSheet()->getStyle('A1:Z1')->applyFromArray($styleArray);
            // auto fit column to content
            foreach (range('A', 'Z') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                    ->setWidth(20);
            }

            // set the names of header cells
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A1", 'Client Name')
                ->setCellValue("B1", 'Entity')
                ->setCellValue("C1", 'Package')
                ->setCellValue("D1", REFNO)
                ->setCellValue("E1", 'Component Ref No')
                ->setCellValue("F1", 'Client Ref No')
                ->setCellValue("G1", 'Transaction No')
                ->setCellValue("H1", 'Comp Received date')
                ->setCellValue("I1", 'Candidate Name')
                ->setCellValue("J1", 'Fathers Name')
                ->setCellValue("K1", 'Candidate DOB')
                ->setCellValue("L1", 'Address')
                ->setCellValue("M1", 'City')
                ->setCellValue("N1", 'Pincode')
                ->setCellValue("O1", 'State')
                ->setCellValue("P1", 'Status')
                ->setCellValue("Q1", 'Sub Status')
                ->setCellValue("R1", 'Executive Name')
                ->setCellValue("S1", 'Vendor')
                ->setCellValue("T1", 'Vendor Status')
                ->setCellValue("U1", 'Vendor Assigned on')
                ->setCellValue("V1", 'Closure Date')
                ->setCellValue("W1", 'Insuff Raised Date')
                ->setCellValue("X1", 'Insuff Clear Date')
                ->setCellValue("Y1", 'Insuff Remark')
                ->setCellValue("Z1", 'Gender');
            // Add some data
            $x = 2;
            foreach ($all_records as $all_record) {

                $global_status = ($all_record['verfstatus'] != "") ? $all_record['verfstatus'] : "WIP";
                $global_filter_status = ($all_record['filter_status'] != "") ? $all_record['filter_status'] : "WIP";
                $closuredate = ($all_record['filter_status'] == "Closed") ? convert_db_to_display_date($all_record['closuredate']) : "NA";
                $insuff_remarks = $all_record['insuff_raise_remark'];

                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A$x", ucwords($all_record['clientname']))
                    ->setCellValue("B$x", ucwords($all_record['entity_name']))
                    ->setCellValue("C$x", ucwords($all_record['package_name']))
                    ->setCellValue("D$x", $all_record['cmp_ref_no'])
                    ->setCellValue("E$x", $all_record['global_com_ref'])
                    ->setCellValue("F$x", $all_record['ClientRefNumber'])
                    ->setCellValue("G$x", $all_record['transaction_id'])
                    ->setCellValue("H$x", convert_db_to_display_date($all_record['iniated_date']))
                    ->setCellValue("I$x", ucwords($all_record['CandidateName']))
                    ->setCellValue("J$x", ucwords($all_record['NameofCandidateFather']))
                    ->setCellValue("K$x", convert_db_to_display_date($all_record['DateofBirth']))
                    ->setCellValue("L$x", $all_record['street_address'])
                    ->setCellValue("M$x", $all_record['city'])
                    ->setCellValue("N$x", $all_record['pincode'])
                    ->setCellValue("O$x", $all_record['state'])
                    ->setCellValue("P$x", $global_filter_status)
                    ->setCellValue("Q$x", $global_status)
                    ->setCellValue("R$x", $all_record['executive_name'])
                    ->setCellValue("S$x", $all_record['vendor_name'])
                    ->setCellValue("T$x", ucwords($all_record['vendor_status']))
                    ->setCellValue("U$x", convert_db_to_display_date($all_record['vendor_assgined_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12))
                    ->setCellValue("V$x", $closuredate)
                    ->setCellValue("W$x", $all_record['insuff_raised_date'])
                    ->setCellValue("X$x", $all_record['insuff_clear_date'])
                    ->setCellValue("Y$x", $insuff_remarks)
                    ->setCellValue("Z$x", $all_record['gender']);

                $x++;
            }
            // Rename worksheet
            $spreadsheet->getActiveSheet()->setTitle('Global Database Records');

            $spreadsheet->setActiveSheetIndex(0);
        }  

        if($component_name == "4")
        {
            $this->load->model('pcc_verificatiion_model');

            $all_records = $this->pcc_verificatiion_model->get_all_pcc_by_client($client_id, $fil_by_status, $from_date, $to_date);

            require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
            // Create new Spreadsheet object
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

            // Set document properties
            $spreadsheet->getProperties()->setCreator(CRMNAME)
                ->setLastModifiedBy(CRMNAME)
                ->setTitle(CRMNAME)
                ->setSubject('PCC records')
                ->setDescription('PCC records with their status');

            // add style to the header
            $styleArray = array(
                'font' => array(
                    'bold' => true,
                ),
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ),
                'borders' => array(
                    'top' => array(
                        'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ),
                ),
                'fill' => array(
                    'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => array(
                        'argb' => 'FFA0A0A0',
                    ),
                    'endcolor' => array(
                        'argb' => 'FFFFFFFF',
                    ),
                ),
            );
            $spreadsheet->getActiveSheet()->getStyle('A1:Z1')->applyFromArray($styleArray);
            // auto fit column to content
            foreach (range('A', 'Z') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                    ->setWidth(20);
            }

            // set the names of header cells
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A1", 'Client Name')
                ->setCellValue("B1", 'Entity')
                ->setCellValue("C1", 'Package')
                ->setCellValue("D1", REFNO)
                ->setCellValue("E1", 'Component Ref No')
                ->setCellValue("F1", 'Client Ref No')
                ->setCellValue("G1", 'Transaction No')
                ->setCellValue("H1", 'Comp Received date')
                ->setCellValue("I1", 'Candidate Name')
                ->setCellValue("J1", 'Fathers Name')
                ->setCellValue("K1", 'Candidate DOB')
                ->setCellValue("L1", 'Address')
                ->setCellValue("M1", 'City')
                ->setCellValue("N1", 'Pincode')
                ->setCellValue("O1", 'State')
                ->setCellValue("P1", 'Status')
                ->setCellValue("Q1", 'Sub Status')
                ->setCellValue("R1", 'Executive Name')
                ->setCellValue("S1", 'Vendor')
                ->setCellValue("T1", 'Vendor Status')
                ->setCellValue("U1", 'Vendor Assigned on')
                ->setCellValue("V1", 'Closure Date')
                ->setCellValue("W1", 'Insuff Raised Date')
                ->setCellValue("X1", 'Insuff Clear Date')
                ->setCellValue("Y1", 'Insuff Remark')
                ->setCellValue("Z1", 'Gender');
            // Add some data
            $x = 2;
            foreach ($all_records as $all_record) {

                $PCC_status = ($all_record['verfstatus'] != "") ? $all_record['verfstatus'] : "WIP";
                $PCC_filter_status = ($all_record['filter_status'] != "") ? $all_record['filter_status'] : "WIP";
                $closuredate = ($all_record['filter_status'] == "Closed") ? convert_db_to_display_date($all_record['closuredate']) : "NA";
                $insuff_remarks = $all_record['insuff_raise_remark'];

                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A$x", ucwords($all_record['clientname']))
                    ->setCellValue("B$x", ucwords($all_record['entity_name']))
                    ->setCellValue("C$x", ucwords($all_record['package_name']))
                    ->setCellValue("D$x", $all_record['cmp_ref_no'])
                    ->setCellValue("E$x", $all_record['pcc_com_ref'])
                    ->setCellValue("F$x", $all_record['ClientRefNumber'])
                    ->setCellValue("G$x", $all_record['transaction_id'])
                    ->setCellValue("H$x", convert_db_to_display_date($all_record['iniated_date']))
                    ->setCellValue("I$x", ucwords($all_record['CandidateName']))
                    ->setCellValue("J$x", ucwords($all_record['NameofCandidateFather']))
                    ->setCellValue("K$x", convert_db_to_display_date($all_record['DateofBirth']))
                    ->setCellValue("L$x", $all_record['street_address'])
                    ->setCellValue("M$x", $all_record['city'])
                    ->setCellValue("N$x", $all_record['pincode'])
                    ->setCellValue("O$x", $all_record['state'])
                    ->setCellValue("P$x", $PCC_filter_status)
                    ->setCellValue("Q$x", $PCC_status)
                    ->setCellValue("R$x", $all_record['executive_name'])
                    ->setCellValue("S$x", $all_record['vendor_name'])
                    ->setCellValue("T$x", ucwords($all_record['vendor_status']))
                    ->setCellValue("U$x", convert_db_to_display_date($all_record['vendor_assgined_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12))
                    ->setCellValue("V$x", $closuredate)
                    ->setCellValue("W$x", $all_record['insuff_raised_date'])
                    ->setCellValue("X$x", $all_record['insuff_clear_date'])
                    ->setCellValue("Y$x", $insuff_remarks)
                    ->setCellValue("Z$x", $all_record['gender']);
                $x++;
            }
            // Rename worksheet
            $spreadsheet->getActiveSheet()->setTitle('PCC Records');

            $spreadsheet->setActiveSheetIndex(0);

        }


        if($component_name == "11")
        {
            $this->load->model('identity_model');

            $all_records = $this->identity_model->get_all_identity_by_client($client_id, $fil_by_status, $from_date, $to_date);

            require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
            // Create new Spreadsheet object
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

            // Set document properties
            $spreadsheet->getProperties()->setCreator(CRMNAME)
                ->setLastModifiedBy(CRMNAME)
                ->setTitle(CRMNAME)
                ->setSubject('Identity records')
                ->setDescription('Identity records with their status');

            // add style to the header
            $styleArray = array(
                'font' => array(
                    'bold' => true,
                ),
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ),
                'borders' => array(
                    'top' => array(
                        'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ),
                ),
                'fill' => array(
                    'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => array(
                        'argb' => 'FFA0A0A0',
                    ),
                    'endcolor' => array(
                        'argb' => 'FFFFFFFF',
                    ),
                ),
            );
            $spreadsheet->getActiveSheet()->getStyle('A1:W1')->applyFromArray($styleArray);
            // auto fit column to content
            foreach (range('A', 'W') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                    ->setWidth(20);
            }

            // set the names of header cells
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A1", 'Client Name')
                ->setCellValue("B1", 'Entity')
                ->setCellValue("C1", 'Package')
                ->setCellValue("D1", REFNO)
                ->setCellValue("E1", 'Component Ref No')
                ->setCellValue("F1", 'Client Ref No')
                ->setCellValue("G1", 'Transaction No')
                ->setCellValue("H1", 'Comp Received date')
                ->setCellValue("I1", 'Candidate Name')
                ->setCellValue("J1", 'Fathers Name')
                ->setCellValue("K1", 'Candidate DOB')
                ->setCellValue("L1", 'Document Submitted')
                ->setCellValue("M1", 'ID Number')
                ->setCellValue("N1", 'Status')
                ->setCellValue("O1", 'Sub Status')
                ->setCellValue("P1", 'Executive Name')
                ->setCellValue("Q1", 'Vendor')
                ->setCellValue("R1", 'Vendor Status')
                ->setCellValue("S1", 'Vendor Assigned on')
                ->setCellValue("T1", 'Closure Date')
                ->setCellValue("U1", 'Insuff Raised Date')
                ->setCellValue("V1", 'Insuff Clear Date')
                ->setCellValue("W1", 'Insuff Remark');
            // Add some data
            $x = 2;
            foreach ($all_records as $all_record) {

                $identity_status = ($all_record['verfstatus'] != "") ? $all_record['verfstatus'] : "WIP";
                $identity_filter_status = ($all_record['filter_status'] != "") ? $all_record['filter_status'] : "WIP";
                $closuredate = ($all_record['filter_status'] == "Closed") ? convert_db_to_display_date($all_record['closuredate']) : "NA";
                $insuff_remarks = $all_record['insuff_raise_remark'];

                $id_number = (strlen($all_record['id_number']) == 12) ?  wordwrap($all_record['id_number'] , 4 , '-' , true ) : strtoupper($all_record['id_number']);

                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A$x", ucwords($all_record['clientname']))
                    ->setCellValue("B$x", ucwords($all_record['entity_name']))
                    ->setCellValue("C$x", ucwords($all_record['package_name']))
                    ->setCellValue("D$x", $all_record['cmp_ref_no'])
                    ->setCellValue("E$x", $all_record['identity_com_ref'])
                    ->setCellValue("F$x", $all_record['ClientRefNumber'])
                    ->setCellValue("G$x", $all_record['transaction_id'])
                    ->setCellValue("H$x", convert_db_to_display_date($all_record['iniated_date']))
                    ->setCellValue("I$x", ucwords($all_record['CandidateName']))
                    ->setCellValue("J$x", ucwords($all_record['NameofCandidateFather']))
                    ->setCellValue("K$x", convert_db_to_display_date($all_record['DateofBirth']))
                    ->setCellValue("L$x", ucwords($all_record['doc_submited']))
                    ->setCellValue("M$x", $id_number)
                    ->setCellValue("N$x", $identity_filter_status)
                    ->setCellValue("O$x", $identity_status)
                    ->setCellValue("P$x", $all_record['executive_name'])
                    ->setCellValue("Q$x", $all_record['vendor_name'])
                    ->setCellValue("R$x", ucwords($all_record['vendor_status']))
                    ->setCellValue("S$x", convert_db_to_display_date($all_record['vendor_assgined_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12))
                    ->setCellValue("T$x", $closuredate)
                    ->setCellValue("U$x", $all_record['insuff_raised_date'])
                    ->setCellValue("V$x", $all_record['insuff_clear_date'])
                    ->setCellValue("W$x", $insuff_remarks);
                $x++;
            }
            // Rename worksheet
            $spreadsheet->getActiveSheet()->setTitle('Identity Records');

            $spreadsheet->setActiveSheetIndex(0);


        }


        if($component_name == "2")
        {

            $this->load->model('credit_report_model');

            $all_records = $this->credit_report_model->get_all_credit_report_by_client($client_id, $fil_by_status, $from_date, $to_date);

            require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
            // Create new Spreadsheet object
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

            // Set document properties
            $spreadsheet->getProperties()->setCreator(CRMNAME)
                ->setLastModifiedBy(CRMNAME)
                ->setTitle(CRMNAME)
                ->setSubject('Credit Report records')
                ->setDescription('Credit Report records with their status');

            // add style to the header
            $styleArray = array(
                'font' => array(
                    'bold' => true,
                ),
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ),
                'borders' => array(
                    'top' => array(
                        'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ),
                ),
                'fill' => array(
                    'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => array(
                        'argb' => 'FFA0A0A0',
                    ),
                    'endcolor' => array(
                        'argb' => 'FFFFFFFF',
                    ),
                ),
            );
            $spreadsheet->getActiveSheet()->getStyle('A1:V1')->applyFromArray($styleArray);
            // auto fit column to content
            foreach (range('A', 'V') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                    ->setWidth(20);
            }

            // set the names of header cells
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A1", 'Client Name')
                ->setCellValue("B1", 'Entity')
                ->setCellValue("C1", 'Package')
                ->setCellValue("D1", REFNO)
                ->setCellValue("E1", 'Component Ref No')
                ->setCellValue("F1", 'Client Ref No')
                ->setCellValue("G1", 'Transaction No')
                ->setCellValue("H1", 'Comp Received date')
                ->setCellValue("I1", 'Candidate Name')
                ->setCellValue("J1", 'Fathers Name')
                ->setCellValue("K1", 'Candidate DOB')
                ->setCellValue("L1", 'Document Submitted')
                ->setCellValue("M1", 'Status')
                ->setCellValue("N1", 'Sub Status')
                ->setCellValue("O1", 'Executive Name')
                ->setCellValue("P1", 'Vendor')
                ->setCellValue("Q1", 'Vendor Status')
                ->setCellValue("R1", 'Vendor Assigned on')
                ->setCellValue("S1", 'Closure Date')
                ->setCellValue("T1", 'Insuff Raised Date')
                ->setCellValue("U1", 'Insuff Clear Date')
                ->setCellValue("V1", 'Insuff Remark');
            // Add some data
            $x = 2;
            foreach ($all_records as $all_record) {

                $credit_report_status = ($all_record['verfstatus'] != "") ? $all_record['verfstatus'] : "WIP";
                $credit_report_filter_status = ($all_record['filter_status'] != "") ? $all_record['filter_status'] : "WIP";

                $closuredate = ($all_record['filter_status'] == "Closed") ? convert_db_to_display_date($all_record['closuredate']) : "NA";
                $insuff_remarks = $all_record['insuff_raise_remark'];

                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A$x", ucwords($all_record['clientname']))
                    ->setCellValue("B$x", ucwords($all_record['entity_name']))
                    ->setCellValue("C$x", ucwords($all_record['package_name']))
                    ->setCellValue("D$x", $all_record['cmp_ref_no'])
                    ->setCellValue("E$x", $all_record['credit_report_com_ref'])
                    ->setCellValue("F$x", $all_record['ClientRefNumber'])
                    ->setCellValue("G$x", $all_record['transaction_id'])
                    ->setCellValue("H$x", convert_db_to_display_date($all_record['iniated_date']))
                    ->setCellValue("I$x", ucwords($all_record['CandidateName']))
                    ->setCellValue("J$x", ucwords($all_record['NameofCandidateFather']))
                    ->setCellValue("K$x", convert_db_to_display_date($all_record['DateofBirth']))
                    ->setCellValue("L$x", ucwords($all_record['doc_submited']))
                    ->setCellValue("M$x", $credit_report_filter_status)
                    ->setCellValue("N$x", $credit_report_status)
                    ->setCellValue("O$x", $all_record['executive_name'])
                    ->setCellValue("P$x", $all_record['vendor_name'])
                    ->setCellValue("Q$x", ucwords($all_record['vendor_status']))
                    ->setCellValue("R$x", convert_db_to_display_date($all_record['vendor_assgined_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12))
                    ->setCellValue("S$x", $closuredate)
                    ->setCellValue("T$x", $insuff_remarks);
                $x++;
            }
            // Rename worksheet
            $spreadsheet->getActiveSheet()->setTitle('Credit Report Records');

            $spreadsheet->setActiveSheetIndex(0);


        }


        if($component_name == "8")
        {
            $this->load->model('drug_verificatiion_model');

             $all_records = $this->drug_verificatiion_model->get_all_drugs_by_client($client_id, $fil_by_status);

                require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
                // Create new Spreadsheet object
                $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

                // Set document properties
                $spreadsheet->getProperties()->setCreator(CRMNAME)
                    ->setLastModifiedBy(CRMNAME)
                    ->setTitle(CRMNAME)
                    ->setSubject('Drugs records')
                    ->setDescription('Drugs records with their status');

                // add style to the header
                $styleArray = array(
                    'font' => array(
                        'bold' => true,
                    ),
                    'alignment' => array(
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ),
                    'borders' => array(
                        'top' => array(
                            'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ),
                    ),
                    'fill' => array(
                        'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                        'rotation' => 90,
                        'startcolor' => array(
                            'argb' => 'FFA0A0A0',
                        ),
                        'endcolor' => array(
                            'argb' => 'FFFFFFFF',
                        ),
                    ),
                );
                $spreadsheet->getActiveSheet()->getStyle('A1:AE1')->applyFromArray($styleArray);
                // auto fit column to content
                foreach (range('A', 'AE') as $columnID) {
                    $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                        ->setWidth(20);
                }

                // set the names of header cells
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A1", 'Client Name')
                    ->setCellValue("B1", 'Entity')
                    ->setCellValue("C1", 'Package')
                    ->setCellValue("D1", REFNO)
                    ->setCellValue("E1", 'Component Ref No')
                    ->setCellValue("F1", 'Client Ref No')
                    ->setCellValue("G1", 'Transaction No')
                    ->setCellValue("H1", 'Comp Received date')
                    ->setCellValue("I1", 'Candidate Name')
                    ->setCellValue("J1", 'Fathers Name')
                    ->setCellValue("K1", 'Candidate DOB')
                    ->setCellValue("L1", 'Gender')
                    ->setCellValue("M1", 'Contact No 1')
                    ->setCellValue("N1", 'Contact No 2')
                    ->setCellValue("O1", 'Contact No 3')
                    ->setCellValue("P1", 'Panel')
                    ->setCellValue("Q1", 'Address')
                    ->setCellValue("R1", 'City')
                    ->setCellValue("S1", 'Pincode')
                    ->setCellValue("T1", 'State')
                    ->setCellValue("U1", 'Status')
                    ->setCellValue("V1", 'Sub Status')
                    ->setCellValue("W1", 'Executive Name')
                    ->setCellValue("X1", 'Vendor')
                    ->setCellValue("Y1", 'Vendor Status')
                    ->setCellValue("Z1", 'Vendor Final Status')
                    ->setCellValue("AA1", 'Vendor Assigned on')
                    ->setCellValue("AB1", 'Closure Date')
                    ->setCellValue("AC1", 'Insuff Raised Date')
                    ->setCellValue("AD1", 'Insuff Clear Date')
                    ->setCellValue("AE1", 'Insuff Remark');
                // Add some data
                $x = 2;
                foreach ($all_records as $all_record) {

                    $ad_status = ($all_record['verfstatus'] != "") ? $all_record['verfstatus'] : "WIP";
                    $ad_filter_status = ($all_record['filter_status'] != "") ? $all_record['filter_status'] : "WIP";
                    $closuredate = ($all_record['filter_status'] == "Closed") ? convert_db_to_display_date($all_record['closuredate']) : "NA";
                    $insuff_remarks = $all_record['insuff_raise_remark'];

                    $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue("A$x", ucwords($all_record['clientname']))
                        ->setCellValue("B$x", ucwords($all_record['entity_name']))
                        ->setCellValue("C$x", ucwords($all_record['package_name']))
                        ->setCellValue("D$x", $all_record['cmp_ref_no'])
                        ->setCellValue("E$x", $all_record['drug_com_ref'])
                        ->setCellValue("F$x", $all_record['ClientRefNumber'])
                        ->setCellValue("G$x", $all_record['transaction_id'])
                        ->setCellValue("H$x", convert_db_to_display_date($all_record['iniated_date']))
                        ->setCellValue("I$x", ucwords($all_record['CandidateName']))
                        ->setCellValue("J$x", ucwords($all_record['NameofCandidateFather']))
                        ->setCellValue("K$x", convert_db_to_display_date($all_record['DateofBirth']))
                        ->setCellValue("L$x", $all_record['gender'])
                        ->setCellValue("M$x", $all_record['CandidatesContactNumber'])
                        ->setCellValue("N$x", $all_record['ContactNo1'])
                        ->setCellValue("O$x", $all_record['ContactNo2'])
                        ->setCellValue("P$x", $all_record['drug_test_code'])
                        ->setCellValue("Q$x", $all_record['street_address'])
                        ->setCellValue("R$x", $all_record['city'])
                        ->setCellValue("S$x", $all_record['pincode'])
                        ->setCellValue("T$x", $all_record['state'])
                        ->setCellValue("U$x", $ad_filter_status)
                        ->setCellValue("V$x", $ad_status)
                        ->setCellValue("V$x", $all_record['executive_name'])
                        ->setCellValue("X$x", $all_record['vendor_name'])
                        ->setCellValue("Y$x", ucwords($all_record['vendor_status']))
                        ->setCellValue("Z$x", ucwords($all_record['final_status']))
                        ->setCellValue("AA$x", convert_db_to_display_date($all_record['vendor_assgined_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12))
                        ->setCellValue("AB$x", $closuredate)
                        ->setCellValue("AC$x", $all_record['insuff_raised_date'])
                        ->setCellValue("AD$x", $all_record['insuff_clear_date'])
                        ->setCellValue("AE$x", $insuff_remarks);

                    $x++;
                }
                // Rename worksheet
                $spreadsheet->getActiveSheet()->setTitle('Drugs Records');

                $spreadsheet->setActiveSheetIndex(0);
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=Candidates Records of .xlsx");
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Excel2007');

        $file_name = "Component_export.xls";

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Excel2007');
        ob_start();
        $writer->save($file_upload_path . "/" . $file_name);
        ob_end_clean();

        $this->update_request_data(array('file_name' => $file_name, 'folder_generated_status' => 1, 'folder_name' => $file_upload_path), array('id' => $parameters_array));

        $this->update_request_schedular_list(array('report_id' => $parameters_array, 'file_name' => $file_name, 'run_status' => 1, 'last_run_on' => date(DB_DATE_FORMAT)), array('id' => '4'));

    }
    
    public function export_to_excel_tracker($parameters_array = null, $client_id, $entity_id, $package_id, $client_name, $fil_by_status, $fil_by_sub_status)
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $this->load->model('report_generated_user');

        $file_upload_path = SITE_BASE_PATH . UPLOAD_FOLDER . 'bulk_reports';

        if (!folder_exist($file_upload_path)) {
            mkdir($file_upload_path, 0777);
        }
        if (!folder_exist($file_upload_path)) {
            mkdir($file_upload_path, 0777);
        } else if (!is_writable($file_upload_path)) {
            mkdir($file_upload_path, 0777);
        }

        $all_records = $this->get_all_client_data_for_export_tracker($client_id, $entity_id, $package_id, $fil_by_status, $fil_by_sub_status);


         require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
            // Create new Spreadsheet object
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

            // Set document properties
            $spreadsheet->getProperties()->setCreator(CRMNAME)
                ->setLastModifiedBy(CRMNAME)
                ->setTitle(CRMNAME)
                ->setSubject('Candidates records')
                ->setDescription('candidates records with their status');

            // add style to the header
            $styleArray = array(
                'font' => array(
                    'bold' => true,
                ),
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ),
                'borders' => array(
                    'allborders' => array(
                        'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ),
                ),
                'fill' => array(
                    'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => array(
                        'argb' => 'FFA500',
                    ),
                    'endcolor' => array(
                        'argb' => 'FFFFFFFF',
                    ),
                ),
            );

            $styleborder = array(
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ),
                'borders' => array(
                    'allborders' => array(
                        'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ),
                ),
            );
            $spreadsheet->getActiveSheet()->getStyle('A1:AR1')->applyFromArray($styleArray);
            // auto fit column to content
            foreach (range('A', 'AR') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                    ->setWidth(20);
            }
            // set the names of header cells

        // set the names of header cells

        $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A1", 'Client Ref No.')
                ->setCellValue("B1", REFNO)
                ->setCellValue("C1", 'Client Name')
                ->setCellValue("D1", 'Entity')
                ->setCellValue("E1", 'Spoc/Pack')
                ->setCellValue("F1", 'Case Initiated')
                ->setCellValue("G1", 'Latest Initiation Date')
                ->setCellValue("H1", 'Candidate Name')
                ->setCellValue("I1", 'Overall Status')
                ->setCellValue("J1", 'Overall Closure Date')

                ->setCellValue("K1", 'Address Status 1')

                ->setCellValue("L1", 'Address Status 2')

                ->setCellValue("M1", 'Address Status 3')

                ->setCellValue("N1", 'Employment Status 1')
        
                ->setCellValue("O1", 'Employment Status 2')

                ->setCellValue("P1", 'Employment Status 3')

                ->setCellValue("Q1", 'Education Status 1')

                ->setCellValue("R1", 'Education Status 2')
    
                ->setCellValue("S1", 'Education Status 3')

                ->setCellValue("T1", 'Reference Status 1')
             
                ->setCellValue("U1", 'Reference Status 2')

                ->setCellValue("V1", 'Reference Status 3')
         
                ->setCellValue("W1", 'Court Status 1')
        
                ->setCellValue("X1", 'Court Status 2')
    
                ->setCellValue("Y1", 'Court Status 3')

                ->setCellValue("Z1", 'Global Status 1')
               
                ->setCellValue("AA1", 'Global Status 2')

                ->setCellValue("AB1", 'Global Status 3')

                ->setCellValue("AC1", 'PCC Status 1')

                ->setCellValue("AD1", 'PCC Status 2')
    
                ->setCellValue("AE1", 'PCC Status 3')
        
                ->setCellValue("AF1", 'Identity Status 1')

                ->setCellValue("AG1", 'Identity Status 2')
            
                ->setCellValue("AH1", 'Identity Status 3')
            
                ->setCellValue("AI1", 'Credit Report Status 1')
        
                ->setCellValue("AJ1", 'Credit Report Status 2')

                ->setCellValue("AK1", 'Credit Report Status 3')


                ->setCellValue("AL1", 'Drugs Report Status 1')
        
                ->setCellValue("AM1", 'Drugs Report Status 2')

                ->setCellValue("AN1", 'Drugs Report Status 3')


                ->setCellValue("AO1", 'Discrepancy Details')

                ->setCellValue("AP1", 'Insuff Details')

                ->setCellValue("AQ1", 'Insuff Raised Date')

                ->setCellValue("AR1", 'Insuff Clear Date');

            // Add some data
            $x = 2;
            foreach ($all_records as $all_record) {

                $spreadsheet->getActiveSheet()->getStyle("A$x:AO$x")->applyFromArray($styleborder);
                $this->cellColorStatus("I$x",$all_record['overallstatus'],$spreadsheet);
                $this->cellColorStatus("K$x",$all_record['addrver0'],$spreadsheet);
                $this->cellColorStatus("L$x",$all_record['addrver1'],$spreadsheet);
                $this->cellColorStatus("M$x",$all_record['addrver2'],$spreadsheet);
                $this->cellColorStatus("N$x",$all_record['empver0'],$spreadsheet);
                $this->cellColorStatus("O$x",$all_record['empver1'],$spreadsheet);
                $this->cellColorStatus("P$x",$all_record['empver2'],$spreadsheet);
                $this->cellColorStatus("Q$x",$all_record['eduver0'],$spreadsheet);
                $this->cellColorStatus("R$x",$all_record['eduver1'],$spreadsheet);
                $this->cellColorStatus("S$x",$all_record['eduver2'],$spreadsheet);
                $this->cellColorStatus("T$x",$all_record['refver0'],$spreadsheet);
                $this->cellColorStatus("U$x",$all_record['refver1'],$spreadsheet);
                $this->cellColorStatus("V$x",$all_record['refver2'],$spreadsheet);
                $this->cellColorStatus("W$x",$all_record['courtver0'],$spreadsheet);
                $this->cellColorStatus("X$x",$all_record['courtver1'],$spreadsheet);
                $this->cellColorStatus("Y$x",$all_record['courtver2'],$spreadsheet);
                $this->cellColorStatus("Z$x",$all_record['glodbver0'],$spreadsheet);
                $this->cellColorStatus("AA$x",$all_record['glodbver1'],$spreadsheet);
                $this->cellColorStatus("AB$x",$all_record['glodbver2'],$spreadsheet);
                $this->cellColorStatus("AC$x",$all_record['crimver0'],$spreadsheet);
                $this->cellColorStatus("AD$x",$all_record['crimver1'],$spreadsheet);
                $this->cellColorStatus("AE$x",$all_record['crimver2'],$spreadsheet);
                $this->cellColorStatus("AF$x",$all_record['identity0'],$spreadsheet);
                $this->cellColorStatus("AG$x",$all_record['identity1'],$spreadsheet);
                $this->cellColorStatus("AH$x",$all_record['identity2'],$spreadsheet);
                $this->cellColorStatus("AI$x",$all_record['cbrver0'],$spreadsheet);
                $this->cellColorStatus("AJ$x",$all_record['cbrver1'],$spreadsheet);
                $this->cellColorStatus("AK$x",$all_record['cbrver2'],$spreadsheet);
                $this->cellColorStatus("AL$x",$all_record['drugs0'],$spreadsheet);
                $this->cellColorStatus("AM$x",$all_record['drugs1'],$spreadsheet);
                $this->cellColorStatus("AN$x",$all_record['drugs2'],$spreadsheet);
              
                
            
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A$x", $all_record['ClientRefNumber'])
                    ->setCellValue("B$x", $all_record['cmp_ref_no'])
                    ->setCellValue("C$x", $all_record['clientname'])
                    ->setCellValue("D$x", $all_record['entity_name'])
                    ->setCellValue("E$x", $all_record['package_name'])
                    ->setCellValue("F$x", $all_record['caserecddate'])
                    ->setCellValue("G$x", convert_db_to_display_date($all_record['latest_date']))
                    ->setCellValue("H$x", $all_record['CandidateName'])
                    ->setCellValue("I$x", $this->rename_status_value($all_record['overallstatus']))
                    ->setCellValue("J$x", $all_record['overallclosuredate'])
                    ->setCellValue("K$x", $this->rename_status_value($all_record['addrver0']))
                    ->setCellValue("L$x", $this->rename_status_value($all_record['addrver1']))
                    ->setCellValue("M$x", $this->rename_status_value($all_record['addrver2']))
                    ->setCellValue("N$x", $this->rename_status_value($all_record['empver0']))
                    ->setCellValue("O$x", $this->rename_status_value($all_record['empver1']))
                    ->setCellValue("P$x", $this->rename_status_value($all_record['empver2']))
                    ->setCellValue("Q$x", $this->rename_status_value($all_record['eduver0']))
                    ->setCellValue("R$x", $this->rename_status_value($all_record['eduver1']))
                    ->setCellValue("S$x", $this->rename_status_value($all_record['eduver2']))
                    ->setCellValue("T$x", $this->rename_status_value($all_record['refver0']))
                    ->setCellValue("U$x", $this->rename_status_value($all_record['refver1']))
                    ->setCellValue("V$x", $this->rename_status_value($all_record['refver2']))
                    ->setCellValue("W$x", $this->rename_status_value($all_record['courtver0']))
                    ->setCellValue("X$x", $this->rename_status_value($all_record['courtver1']))
                    ->setCellValue("Y$x", $this->rename_status_value($all_record['courtver2']))
                    ->setCellValue("Z$x", $this->rename_status_value($all_record['glodbver0']))
                    ->setCellValue("AA$x", $this->rename_status_value($all_record['glodbver1']))
                    ->setCellValue("AB$x", $this->rename_status_value($all_record['glodbver2']))
                    ->setCellValue("AC$x", $this->rename_status_value($all_record['crimver0']))
                    ->setCellValue("AD$x", $this->rename_status_value($all_record['crimver1']))
                    ->setCellValue("AE$x", $this->rename_status_value($all_record['crimver2']))
                    ->setCellValue("AF$x", $this->rename_status_value($all_record['identity0']))
                    ->setCellValue("AG$x", $this->rename_status_value($all_record['identity1']))
                    ->setCellValue("AH$x", $this->rename_status_value($all_record['identity2']))
                    ->setCellValue("AI$x", $this->rename_status_value($all_record['cbrver0']))
                    ->setCellValue("AJ$x", $this->rename_status_value($all_record['cbrver1']))
                    ->setCellValue("AK$x", $this->rename_status_value($all_record['cbrver2']))
                    ->setCellValue("AL$x", $this->rename_status_value($all_record['drugs0']))
                    ->setCellValue("AM$x", $this->rename_status_value($all_record['drugs1']))
                    ->setCellValue("AN$x", $this->rename_status_value($all_record['drugs2']))
                    ->setCellValue("AO$x", $all_record['DiscrepancyDetails'])
                    ->setCellValue("AP$x", $all_record['Details'])
                    ->setCellValue("AQ$x", $all_record['insuff_raise_date'])
                    ->setCellValue("AR$x", $all_record['insuff_clear_date']);

                $x++;
            }
            // Rename worksheet
            $spreadsheet->getActiveSheet()->setTitle('Candidate Records Tracker');

            $spreadsheet->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=Candidates Records of .xlsx");
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Excel2007');

        $file_name = "Candidates_Records_of_" . $client_name . "_" . DATE(UPLOAD_FILE_DATE_FORMAT) . ".xls";

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Excel2007');
        ob_start();
        $writer->save($file_upload_path . "/" . $file_name);
        ob_end_clean();

        $this->update_request_data(array('file_name' => $file_name, 'folder_generated_status' => 1, 'folder_name' => $file_upload_path), array('id' => $parameters_array));

        $this->update_request_schedular_list(array('report_id' => $parameters_array, 'file_name' => $file_name, 'run_status' => 1, 'last_run_on' => date(DB_DATE_FORMAT)), array('id' => '5'));

    }

    public function get_all_client_data_for_export_tracker($clientid, $entity_id, $package_id, $fil_by_status, $fil_by_sub_status)
    {
        $data_arry = array();

        $this->load->model(array('candidates_model'));


        $cands_results = $this->candidates_model->get_all_cand_with_search($clientid, $entity_id, $package_id, $fil_by_status, $fil_by_sub_status);
  
        $x = 0;
        foreach ($cands_results as $key => $cands_result) {

            $data_arry[$x]['id'] = $x + 1;
            $data_arry[$x]['candsid'] = $cands_result['id'];
            $data_arry[$x]['CandidateName'] = ucwords(strtolower($cands_result['CandidateName']));
            $data_arry[$x]['ClientRefNumber'] = $cands_result['ClientRefNumber'];
            $data_arry[$x]['cmp_ref_no'] = $cands_result['cmp_ref_no'];
            $data_arry[$x]['overallstatus'] = $cands_result['status_value'];
            $data_arry[$x]['entity_name'] = $cands_result['entity_name'];
            $data_arry[$x]['package_name'] = $cands_result['package_name'];
            $data_arry[$x]['overallstatus'] = $cands_result['status_value'];
            $data_arry[$x]['clientname'] = ucwords(strtolower($cands_result['clientname']));
            $data_arry[$x]['caserecddate'] = convert_db_to_display_date($cands_result['caserecddate']);
            $data_arry[$x]['overallclosuredate'] = ($cands_result['overallclosuredate'] != "") ? convert_db_to_display_date($cands_result['overallclosuredate']) : 'NA';

            for ($i = 0; $i < 3; $i++) {

                $data_arry[$x]["addrver$i"] = 'NA';
                $data_arry[$x]["empver$i"] = 'NA';
                $data_arry[$x]["eduver$i"] = 'NA';
                $data_arry[$x]["refver$i"] = 'NA';
                $data_arry[$x]["courtver$i"] = 'NA';
                $data_arry[$x]["crimver$i"] = 'NA';
                $data_arry[$x]["glodbver$i"] = 'NA';
                $data_arry[$x]["identity$i"] = 'NA';
                $data_arry[$x]["cbrver$i"] = 'NA';
                $data_arry[$x]["drugs$i"] = 'NA';

            }

            $component_id = explode(",", $cands_result['component_id']);

            $rename_status = array('NA' => 'N/A');

            $discrepancy_details = array();

            $insufficiency_details = array();

            $insuff_raise = array();

            $insuff_clear = array();

            $latest_date = array();


            if (in_array('addrver', $component_id)) {
                $result = $this->candidates_model->get_addres_ver_status_for_export(array('addrver.candsid' => $cands_result['id']));
                
                $result_insuff = $this->candidates_model->get_address_insuff_details(array('addrver.candsid' => $cands_result['id']));

                if (!empty($result)) {
                    $counter = 1;
                    foreach ($result as $key => $value) {
                        if ($counter > 3) {
                            continue;
                        }

                        if (array_key_exists($value['report_status'], $rename_status)) {
                            $data_arry[$x]["addrver$key"] = $rename_status[$value['report_status']];
                        } else {
                            $data_arry[$x]["addrver$key"] = ($value['report_status'] != "") ? $value['report_status'] : 'WIP';
                        }

                        if (($value['verfstatus'] == "Major Discrepancy") || ($value['verfstatus'] == "major discrepancy")) {

                            array_push($discrepancy_details, "Add " . $counter . " - " . $value['address'] . ',' . $value['city'] . ',' . $value['pincode'] . ',' . $value['state'] . " - " . $value['remarks']);

                        }

                        if (($value['verfstatus'] == "Insufficiency") || ($value['verfstatus'] == "insufficiency")) {

                            array_push($insufficiency_details, "Add " . $counter . " - " . $value['address'] . ',' . $value['city'] . ',' . $value['pincode'] . ',' . $value['state'] . " - " . $value['insuff_raise_remark']);

                        }

                        $counter++;
                    }
                    if(!empty($result_insuff))
                    { 
                        foreach ($result_insuff as  $value_insuff) {
                            if(!empty($value_insuff['insuff_raised_date']))
                            {
                            array_push($insuff_raise, convert_db_to_display_date($value_insuff['insuff_raised_date']));
                            }
                            if(!empty($value_insuff['insuff_clear_date']))
                            {
                            array_push($insuff_clear, convert_db_to_display_date($value_insuff['insuff_clear_date']));
                            }
                        }
                    }

                    foreach ($result as $key => $value_initiation) {

                        array_push($latest_date, $value_initiation['iniated_date']); 
                    }

                }
            }


            if (in_array('eduver', $component_id)) {
                $result = $this->candidates_model->get_education_ver_status_for_export(array('education.candsid' => $cands_result['id']));

                $result_insuff = $this->candidates_model->get_education_insuff_details(array('education.candsid' => $cands_result['id']));

                if (!empty($result)) {

                    $counter = 1;
                    foreach ($result as $key => $value) {
                        if ($counter > 3) {
                            continue;
                        }

                        if (array_key_exists($value['report_status'], $rename_status)) {
                            $data_arry[$x]["eduver$key"] = $rename_status[$value['report_status']];
                        } else {
                            $data_arry[$x]["eduver$key"] = ($value['report_status'] != "") ? $value['report_status'] : 'WIP';
                        }

                        if (($value['verfstatus'] == "Major Discrepancy") || ($value['verfstatus'] == "major discrepancy")) {

                            array_push($discrepancy_details, "Edu " . $counter . " - " . $value['universityname'] . ',' . $value['qualification_name'] . " - " . $value['remarks']);

                        }

                        if (($value['verfstatus'] == "Insufficiency") || ($value['verfstatus'] == "insufficiency")) {
                            array_push($insufficiency_details, "Edu " . $counter . " - " . $value['universityname'] . ',' . $value['qualification_name'] . " - " . $value['insuff_raise_remark']);
                        }

                        $counter++;
                    }
                    if(!empty($result_insuff))
                    { 
                        foreach ($result_insuff as  $value_insuff) {
                            if(!empty($value_insuff['insuff_raised_date']))
                            {
                            array_push($insuff_raise, convert_db_to_display_date($value_insuff['insuff_raised_date']));
                            }
                            if(!empty($value_insuff['insuff_clear_date']))
                            {
                            array_push($insuff_clear, convert_db_to_display_date($value_insuff['insuff_clear_date']));
                            }
                        }
                    }

                    foreach ($result as $key => $value_initiation) {

                        array_push($latest_date, $value_initiation['iniated_date']); 
                    }
                }
            }



            if (in_array('empver', $component_id)) {
                $result = $this->candidates_model->get_employment_ver_status_for_export(array('empver.candsid' => $cands_result['id']));

                $result_insuff = $this->candidates_model->get_employment_insuff_details(array('empver.candsid' => $cands_result['id']));

                if (!empty($result)) {
                    $counter = 1;
                    foreach ($result as $key => $value) {
                        if ($counter > 3) {
                            continue;
                        }

                        if (array_key_exists($value['report_status'], $rename_status)) {
                            $data_arry[$x]["empver$key"] = $rename_status[$value['report_status']];
                        } else {
                            $data_arry[$x]["empver$key"] = ($value['report_status'] != "") ? $value['report_status'] : 'WIP';
                        }

                        if (($value['verfstatus'] == "Major Discrepancy") || ($value['verfstatus'] == "major discrepancy")) {

                            array_push($discrepancy_details, "Emp " . $counter . " - " . $value['coname'] . " - " . $value['remarks']);

                        }

                        if (($value['verfstatus'] == "Insufficiency") || ($value['verfstatus'] == "insufficiency")) {

                            array_push($insufficiency_details, "Emp " . $counter . " - " . $value['coname'] . " - " . $value['insuff_raise_remark']);
                        }


                        $counter++;
                    }
                    if(!empty($result_insuff))
                    { 
                        foreach ($result_insuff as  $value_insuff) {
                            if(!empty($value_insuff['insuff_raised_date']))
                            {
                            array_push($insuff_raise, convert_db_to_display_date($value_insuff['insuff_raised_date']));
                            }
                            if(!empty($value_insuff['insuff_clear_date']))
                            {
                            array_push($insuff_clear, convert_db_to_display_date($value_insuff['insuff_clear_date']));
                            }
                        }
                    }

                    foreach ($result as $key => $value_initiation) {

                        array_push($latest_date, $value_initiation['iniated_date']); 
                    }
                }
            }
 
            if (in_array('refver', $component_id)) {
                $result = $this->candidates_model->get_refver_ver_status_for_export(array('reference.candsid' => $cands_result['id']));

                $result_insuff = $this->candidates_model->get_reference_insuff_details(array('reference.candsid' => $cands_result['id']));

                if (!empty($result)) {
                    $counter = 1;
                    foreach ($result as $key => $value) {
                        if ($counter > 3) {
                            continue;
                        }

                        if (array_key_exists($value['report_status'], $rename_status)) {
                            $data_arry[$x]["refver$key"] = $rename_status[$value['report_status']];
                        } else {
                            $data_arry[$x]["refver$key"] = ($value['report_status'] != "") ? $value['report_status'] : 'WIP';
                        }

                        if (($value['verfstatus'] == "Major Discrepancy") || ($value['verfstatus'] == "major discrepancy")) {

                            array_push($discrepancy_details, "Ref " . $counter . " - " . $value['name_of_reference'] . " - " . $value['remarks']);

                        }

                        if (($value['verfstatus'] == "Insufficiency") || ($value['verfstatus'] == "insufficiency")) {

                            array_push($insufficiency_details, "Ref " . $counter . " - " . $value['name_of_reference'] . " - " . $value['insuff_raise_remark']);
                        }

                        $counter++;
                    }
                    if(!empty($result_insuff))
                    { 
                        foreach ($result_insuff as  $value_insuff) {
                            if(!empty($value_insuff['insuff_raised_date']))
                            {
                            array_push($insuff_raise, convert_db_to_display_date($value_insuff['insuff_raised_date']));
                            }
                            if(!empty($value_insuff['insuff_clear_date']))
                            {
                            array_push($insuff_clear, convert_db_to_display_date($value_insuff['insuff_clear_date']));
                            }
                        }
                    }

                    foreach ($result as $key => $value_initiation) {

                        array_push($latest_date, $value_initiation['iniated_date']); 
                    }
                }
            }

            if (in_array('courtver', $component_id)) {
                $result = $this->candidates_model->get_court_ver_status_for_export(array('courtver.candsid' => $cands_result['id']));

                $result_insuff = $this->candidates_model->get_court_insuff_details(array('courtver.candsid' => $cands_result['id']));

                if (!empty($result)) {
                    $counter = 1;
                    foreach ($result as $key => $value) {
                        if ($counter > 3) {
                            continue;
                        }

                        if (array_key_exists($value['report_status'], $rename_status)) {
                            $data_arry[$x]["courtver$key"] = $rename_status[$value['report_status']];
                        } else {
                            $data_arry[$x]["courtver$key"] = ($value['report_status'] != "") ? $value['report_status'] : 'WIP';
                        }

                        if (($value['verfstatus'] == "Major Discrepancy") || ($value['verfstatus'] == "major discrepancy")) {

                            array_push($discrepancy_details, "Court " . $counter . " - " . $value['street_address'] . ',' . $value['city'] . ',' . $value['pincode'] . ',' . $value['state'] . " - " . $value['remarks']);
                        }

                        if (($value['verfstatus'] == "Insufficiency") || ($value['verfstatus'] == "insufficiency")) {

                            array_push($insufficiency_details, "Court " . $counter . " - " . $value['street_address'] . ',' . $value['city'] . ',' . $value['pincode'] . ',' . $value['state'] . " - " . $value['insuff_raise_remark']);
                        }

                        $counter++;
                    }
                    if(!empty($result_insuff))
                    { 
                        foreach ($result_insuff as  $value_insuff) {
                            if(!empty($value_insuff['insuff_raised_date']))
                            {
                            array_push($insuff_raise, convert_db_to_display_date($value_insuff['insuff_raised_date']));
                            }
                            if(!empty($value_insuff['insuff_clear_date']))
                            {
                            array_push($insuff_clear, convert_db_to_display_date($value_insuff['insuff_clear_date']));
                            }
                        }
                    }

                    foreach ($result as $key => $value_initiation) {

                        array_push($latest_date, $value_initiation['iniated_date']); 
                    }
                }
            }
            if (in_array('crimver', $component_id)) {
                $result = $this->candidates_model->get_pcc_ver_status_for_export(array('pcc.candsid' => $cands_result['id']));

                $result_insuff = $this->candidates_model->get_pcc_insuff_details(array('pcc.candsid' => $cands_result['id']));
                
                if (!empty($result)) {

                    $counter = 1;
                    foreach ($result as $key => $value) {
                        if ($counter > 3) {
                            continue;
                        }

                        if (array_key_exists($value['report_status'], $rename_status)) {
                            $data_arry[$x]["crimver$key"] = $rename_status[$value['report_status']];
                        } else {
                            $data_arry[$x]["crimver$key"] = ($value['report_status'] != "") ? $value['report_status'] : 'WIP';
                        }

                        if (($value['verfstatus'] == "Major Discrepancy") || ($value['verfstatus'] == "major discrepancy")) {

                            array_push($discrepancy_details, "PCC " . $counter . " - " . $value['street_address'] . ',' . $value['city'] . ',' . $value['pincode'] . ',' . $value['state'] . " - " . $value['remarks']);
                        }


                        if (($value['verfstatus'] == "Insufficiency") || ($value['verfstatus'] == "insufficiency")) {

                            array_push($insufficiency_details, "PCC " . $counter . " - " . $value['street_address'] . ',' . $value['city'] . ',' . $value['pincode'] . ',' . $value['state'] . " - " . $value['insuff_raise_remark']);
                        }

                        $counter++;
                    }
                    if(!empty($result_insuff))
                    { 
                        foreach ($result_insuff as  $value_insuff) {
                            if(!empty($value_insuff['insuff_raised_date']))
                            {
                            array_push($insuff_raise, convert_db_to_display_date($value_insuff['insuff_raised_date']));
                            }
                            if(!empty($value_insuff['insuff_clear_date']))
                            {
                            array_push($insuff_clear, convert_db_to_display_date($value_insuff['insuff_clear_date']));
                            }
                        }
                    }

                    foreach ($result as $key => $value_initiation) {

                        array_push($latest_date, $value_initiation['iniated_date']); 
                    }
                }
            }

            if (in_array('globdbver', $component_id)) {
                $result = $this->candidates_model->get_globdbver_ver_status_for_export(array('glodbver.candsid' => $cands_result['id']));

                $result_insuff = $this->candidates_model->get_global_insuff_details(array('glodbver.candsid' => $cands_result['id']));
                
                if (!empty($result)) {

                    $counter = 1;
                    foreach ($result as $key => $value) {
                        if ($counter > 3) {
                            continue;
                        }

                        if (array_key_exists($value['report_status'], $rename_status)) {
                            $data_arry[$x]["glodbver$key"] = $rename_status[$value['report_status']];
                        } else {
                            $data_arry[$x]["glodbver$key"] = ($value['report_status'] != "") ? $value['report_status'] : 'WIP';
                        }

                        if (($value['verfstatus'] == "Major Discrepancy") || ($value['verfstatus'] == "major discrepancy")) {

                            array_push($discrepancy_details, "Global Database " . $counter . " - " . $value['street_address'] . ',' . $value['city'] . ',' . $value['pincode'] . ',' . $value['state'] . " - " . $value['remarks']);
                        }

                        if (($value['verfstatus'] == "Insufficiency") || ($value['verfstatus'] == "insufficiency")) {

                            array_push($insufficiency_details, "Global Database " . $counter . " - " . $value['street_address'] . ',' . $value['city'] . ',' . $value['pincode'] . ',' . $value['state'] . " - " . $value['insuff_raise_remark']);
                        }

                        $counter++;
                    }
                    if(!empty($result_insuff))
                    { 
                        foreach ($result_insuff as  $value_insuff) {
                            if(!empty($value_insuff['insuff_raised_date']))
                            {
                            array_push($insuff_raise, convert_db_to_display_date($value_insuff['insuff_raised_date']));
                            }
                            if(!empty($value_insuff['insuff_clear_date']))
                            {
                            array_push($insuff_clear, convert_db_to_display_date($value_insuff['insuff_clear_date']));
                            }
                        }
                    }

                    foreach ($result as $key => $value_initiation) {

                        array_push($latest_date, $value_initiation['iniated_date']); 
                    }
                }
            }
            if (in_array('identity', $component_id)) {
                $result = $this->candidates_model->get_identity_ver_status_for_export(array('identity.candsid' => $cands_result['id']));

                $result_insuff = $this->candidates_model->get_identity_insuff_details(array('identity.candsid' => $cands_result['id']));
                

                if (!empty($result)) {

                    $counter = 1;
                    foreach ($result as $key => $value) {
                        if ($counter > 3) {
                            continue;
                        }

                        if (array_key_exists($value['report_status'], $rename_status)) {
                            $data_arry[$x]["identity$key"] = $rename_status[$value['report_status']];
                        } else {
                            $data_arry[$x]["identity$key"] = ($value['report_status'] != "") ? $value['report_status'] : 'WIP';
                        }

                        if (($value['verfstatus'] == "Major Discrepancy") || ($value['verfstatus'] == "major discrepancy")) {

                            array_push($discrepancy_details, "Identity " . $counter . " - " . $value['doc_submited'] . " - " . $value['remarks']);
                        }

                        if (($value['verfstatus'] == "Insufficiency") || ($value['verfstatus'] == "insufficiency")) {

                            array_push($insufficiency_details, "Identity " . $counter . " - " . $value['doc_submited'] . " - " . $value['insuff_raise_remark']);
                        }

                       
                        $counter++;
                    }
                    if(!empty($result_insuff))
                    { 
                        foreach ($result_insuff as  $value_insuff) {
                            if(!empty($value_insuff['insuff_raised_date']))
                            {
                            array_push($insuff_raise, convert_db_to_display_date($value_insuff['insuff_raised_date']));
                            }
                            if(!empty($value_insuff['insuff_clear_date']))
                            {
                            array_push($insuff_clear, convert_db_to_display_date($value_insuff['insuff_clear_date']));
                            }
                        }
                    }

                    foreach ($result as $key => $value_initiation) {

                        array_push($latest_date, $value_initiation['iniated_date']); 
                    }
                }
            }

            if (in_array('cbrver', $component_id)) {
                $result = $this->candidates_model->get_credit_report_ver_status_for_export(array('credit_report.candsid' => $cands_result['id']));

                $result_insuff = $this->candidates_model->get_credit_report_insuff_details(array('credit_report.candsid' => $cands_result['id']));

                if (!empty($result)) {

                    $counter = 1;
                    foreach ($result as $key => $value) {

                        if ($counter > 3) {

                            continue;
                        }

                        if (array_key_exists($value['report_status'], $rename_status)) {
                            $data_arry[$x]["cbrver$key"] = $rename_status[$value['report_status']];
                        } else {
                            $data_arry[$x]["cbrver$key"] = ($value['report_status'] != "") ? $value['report_status'] : 'WIP';
                        }

                        if (($value['verfstatus'] == "Major Discrepancy") || ($value['verfstatus'] == "major discrepancy")) {

                            array_push($discrepancy_details, "Credit Report " . $counter . " - " . " Cibil " . " - " . $value['remarks']);
                        }

                        if (($value['verfstatus'] == "Insufficiency") || ($value['verfstatus'] == "insufficiency")) {

                            array_push($insufficiency_details, "Credit Report " . $counter . " - " . " Cibil " . " - " . $value['insuff_raise_remark']);
                        }

                        $counter++;
                    }
                    if(!empty($result_insuff))
                    { 
                        foreach ($result_insuff as  $value_insuff) {
                            if(!empty($value_insuff['insuff_raised_date']))
                            {
                            array_push($insuff_raise, convert_db_to_display_date($value_insuff['insuff_raised_date']));
                            }
                            if(!empty($value_insuff['insuff_clear_date']))
                            {
                            array_push($insuff_clear, convert_db_to_display_date($value_insuff['insuff_clear_date']));
                            }
                        }
                    }

                    foreach ($result as $key => $value_initiation) {

                        array_push($latest_date, $value_initiation['iniated_date']); 
                    }

                }
            }

            if (in_array('narcver', $component_id)) {
                $result = $this->candidates_model->get_drugs_report_ver_status_for_export(array('drug_narcotis.candsid' => $cands_result['id']));

                $result_insuff = $this->candidates_model->get_drugs_report_insuff_details(array('drug_narcotis.candsid' => $cands_result['id']));

                if (!empty($result)) {

                    $counter = 1;
                    foreach ($result as $key => $value) {

                        if ($counter > 3) {

                            continue;
                        }

                        if (array_key_exists($value['report_status'], $rename_status)) {
                            $data_arry[$x]["drugs$key"] = $rename_status[$value['report_status']];
                        } else {
                            $data_arry[$x]["drugs$key"] = ($value['report_status'] != "") ? $value['report_status'] : 'WIP';
                        }

                        if (($value['verfstatus'] == "Major Discrepancy") || ($value['verfstatus'] == "major discrepancy")) {

                            array_push($discrepancy_details, "Drugs " . $counter . " - " . $value['drug_test_code'] . " - " . $value['remarks']);
                        }

                        if (($value['verfstatus'] == "Insufficiency") || ($value['verfstatus'] == "insufficiency")) {

                            array_push($insufficiency_details, "Drugs " . $counter . " - " . $value['drug_test_code'] . " - " . $value['insuff_raise_remark']);
                        }

                        $counter++;
                    }
                    if(!empty($result_insuff))
                    { 
                        foreach ($result_insuff as  $value_insuff) {
                            if(!empty($value_insuff['insuff_raised_date']))
                            {
                            array_push($insuff_raise, convert_db_to_display_date($value_insuff['insuff_raised_date']));
                            }
                            if(!empty($value_insuff['insuff_clear_date']))
                            {
                            array_push($insuff_clear, convert_db_to_display_date($value_insuff['insuff_clear_date']));
                            }
                        }
                    }

                    foreach ($result as $key => $value_initiation) {

                        array_push($latest_date, $value_initiation['iniated_date']); 
                    }

                }
            }
         
            $insufficiencys_details = array();
            foreach ($insufficiency_details as $key => $value) {
                $insufficiencys_details[] = $value . " || ";
            }

            $discrepancys_details = array();
            foreach ($discrepancy_details as $key => $value) {
                $discrepancys_details[] = $value . " || ";
            }

            asort($insuff_raise);
          
            $insuff_raise_date = array();
            foreach ($insuff_raise as $key => $value) {
                $insuff_raise_date[] = $value . " & ";
            }
            
            arsort($insuff_clear);

            $insuff_clear_date = array();
            foreach ($insuff_clear as $key => $value) {
                $insuff_clear_date[] = $value . " & ";
            }
      

            $data_arry[$x]['Details'] = implode('', $insufficiencys_details);
            unset($insufficiencys_details);

            $data_arry[$x]['DiscrepancyDetails'] = implode('', $discrepancys_details);
            unset($discrepancys_details);



            $data_arry[$x]['insuff_raise_date'] = implode('', $insuff_raise_date);
            unset($insuff_raise_date);


            $data_arry[$x]['insuff_clear_date'] = implode('', $insuff_clear_date);
            unset($insuff_clear_date);
            if(!empty($latest_date))
            {
                $data_arry[$x]['latest_date'] = max($latest_date);
            }
            else{
                $data_arry[$x]['latest_date'] = "";
            }

            $x++;
        }

        return $data_arry;
    }

    public function approve_queue_component()
    {
         
        $this->load->library('email');
        $this->load->model('addressver_model');
        $this->load->model('common_model');

        $this->load->model('Cron_job_model');

    $selected_qc =  $this->Cron_job_model->selected_aq_component(); 

    $cron_job_component_selection = $selected_qc[0]['cron_job_component_selection'];

    $component_selection = explode(',',$cron_job_component_selection);

    if(in_array('addrver',$component_selection))
    {

        $list_approve_case =  $this->addressver_model->select_address_approve_list(array('address_vendor_log.status' => 0));

        $list_address = []; 
        foreach ($list_approve_case as $childArray_address) 
        { 
            foreach ($childArray_address as $value_address) 
            { 
              $list_address[] = $value_address; 
            } 
        }
                   
        $update_counter = 0;

        foreach ($list_address as $key => $value_address) {

            $select_address_id = $this->addressver_model->select_address_dt('address_vendor_log',array('case_id') ,array('id' => $value_address));

            $cands_id = $this->addressver_model->select_address_dt('addrver',array('candsid'),array('id' => $select_address_id[0]['case_id']));
                           
            $cands_details = $this->addressver_model->select_address_dt('candidates_info',array('clientid','entity','package'),array('id' => $cands_id[0]['candsid']));

                          
            $address_details[] = $this->addressver_model->get_address_details_for_approval($value_address);
 
                     
        }
     
        if(isset($address_details) && !empty($address_details))
        {
                            

            $address_detail =  (array_map('current', $address_details));
                          
                                       
            foreach( $address_detail  as $k => $v_address) {
                $new_arr_address[$v_address['vendor_id']][]=$v_address;
            }

                           
            foreach ($new_arr_address as $key => $value) {

                $vendor_name = $this->addressver_model->vendor_email_id(array('vendors.id' => $key));

                $email_tmpl_data['subject'] = 'Address - '.ucwords($vendor_name[0]['vendor_name']).' - New case/s initiated by '.CRMNAME;
                $message = "<p>Team,</p><p> The below case/s have been initiated to you. Kindly have them closed within TAT.</p>";
                                        
                $message .= "<table border = '2'  style='border-spacing: 10; border-collapse: collapse;'>
                    <tr>
                    <th style='background-color: #EDEDED;text-align:center'>Sr No</th>
                    <th style='background-color: #EDEDED;text-align:center'>Client Name</th>
                    <th style='background-color: #EDEDED;text-align:center'>Component Ref No</th>
                    <th style='background-color: #EDEDED;text-align:center'>Candidate Name</th>
                    <th style='background-color: #EDEDED;text-align:center'>Primary Contact</th>
                    <th style='background-color: #EDEDED;text-align:center'>Contact No (2)</th>
                    <th style='background-color: #EDEDED;text-align:center'>Contact No (3)</th>
                    <th style='background-color: #EDEDED;text-align:center'>Address</th>
                    <th style='background-color: #EDEDED;text-align:center'>City</th>
                    <th style='background-color: #EDEDED;text-align:center'>Pincode</th>
                    <th style='background-color: #EDEDED;text-align:center'>State</th>
                    </tr>";

                $address_vendor_log_id = array();
                $m = 1;
                foreach ($value as $address_key => $address_value) {
                                   
                    $message .= '<tr>
                        <td style="text-align:center">'.$m.'</td>
                        <td style="text-align:center">'.ucwords($address_value['clientname']). '</td>
                        <td style="text-align:center">'.$address_value['add_com_ref'] . '</td>
                        <td style="text-align:center">'.ucwords($address_value['CandidateName']) . '</td>
                        <td style="text-align:center">'.$address_value['CandidatesContactNumber'] . '</td>
                        <td style="text-align:center">'.$address_value['ContactNo1'] . '</td>
                        <td style="text-align:center">'.$address_value['ContactNo2'] . '</td>
                        <td style="text-align:center">'.ucwords($address_value['address']) . '</td>
                        <td style="text-align:center">'.ucwords($address_value['city']) . '</td>
                        <td style="text-align:center">'.$address_value['pincode'] . '</td>
                        <td style="text-align:center">'.ucwords($address_value['state']) . '</td>
                    </tr>';

                    $address_vendor_log_id[] = $address_value['address_vendor_log_id'];
                                        
                    $m++; 
                                           
                } 

                             
                $message .= "</table>";

                $message .= "<br><br><p><b>Note :</b> <I>This is an auto generated email. Request you to write back within 24 hrs with any discrepancy.</I>";
                                        
                $to_emails = $this->addressver_model->vendor_email_id(array('vendors.id' => $key));
             
                $email_tmpl_data['to_emails'] = $to_emails[0]['email_id'];

                $email_tmpl_data['message'] = $message;
                  
                $result = $this->email->address_approve_vendor_cases_mail_send($email_tmpl_data);
              
                if(!empty($result) && $result == "Success")
                {
                    if(!empty($address_vendor_log_id))
                    {
                        foreach($address_vendor_log_id as $address_vendor_log_key => $address_vendor_log_value)
                        {

                                              
                            $select_address_id = $this->addressver_model->select_address_dt('address_vendor_log',array('case_id') ,array('id' => $address_vendor_log_value));

                            $cands_id = $this->addressver_model->select_address_dt('addrver',array('candsid'),array('id' => $select_address_id[0]['case_id']));
                                            
                            $cands_details = $this->addressver_model->select_address_dt('candidates_info',array('clientid','entity','package'),array('id' => $cands_id[0]['candsid']));
        
                            $mode_of_verification = $this->addressver_model->get_mode_of_verification(array('tbl_clients_id' =>  $cands_details[0]['clientid'],'entity' =>$cands_details[0]['entity'],'package' =>$cands_details[0]['package'])); 
        
                            if(!empty($mode_of_verification))
                            {
                                $mode_of_verification_value = json_decode($mode_of_verification[0]['mode_of_verification']);
                            }
        
                            $update = $this->addressver_model->upload_vendor_assign('address_vendor_log', array('status' => 1, 'approval_by' => 1, "modified_on" => date(DB_DATE_FORMAT)), array('id' => $address_vendor_log_value));
        
                                if ($update) {
                                    $update_counter++;
                                    $field_array = array('component' => 'addrver',
                                        'component_tbl_id' => '1',
                                        'case_id' => $address_vendor_log_value,
                                        'trasaction_id' => 'Txn',
                                        "status" => 1,
                                        "remarks" => '',
                                        "approval_by" => 1,
                                        "approval_on" => date(DB_DATE_FORMAT),
                                        "created_by" => 1,
                                        "created_on" => date(DB_DATE_FORMAT),
                                        "vendor_status" => 0,
                                        "modified_on" => null,
                                        "modified_by" => '',
                                    );
                                    $insert_id = $this->common_model->common_insert_transaction_no('view_vendor_master_log', $field_array);
                                    $update_transaction_id = $this->common_model->update_transaction_id(array('trasaction_id' => "Txn" . $insert_id), array('id' => $insert_id));
                                } 
                        }

                    }
                }
                                
                $this->email->clear(true);
                                                                  
            }
        } 
    } 
    

    if(in_array('courtver',$component_selection))
    {
     
       $this->load->model('court_verificatiion_model');

        $list_approve_case_court =  $this->court_verificatiion_model->select_court_approve_list(array('courtver_vendor_log.status' => 0));

        $list_court = []; 
        foreach ($list_approve_case_court as $childArray_court) 
        { 
            foreach ($childArray_court as $value_court) 
            { 
              $list_court[] = $value_court; 
            } 
        }
       
        $update_counter = 0;

        foreach ($list_court as $key => $value_court) {
                            
            $court_details[] = $this->court_verificatiion_model->get_court_details_for_approval($value_court);
        }

                            
        if(isset($court_details) && !empty($court_details))
        {
            $court_detail =  (array_map('current', $court_details));
                                
            foreach($court_detail as $k => $v_court) {
                $new_arr_court[$v_court['vendor_id']][]=$v_court;
            }

            foreach ($new_arr_court as $key => $value) {

                $vendor_name = $this->court_verificatiion_model->vendor_email_id(array('vendors.id' => $key));


                $email_tmpl_data['subject'] = 'Court Verification - '.ucwords($vendor_name[0]['vendor_name']).' - New case/s initiated by '.CRMNAME;
                $message = "<p>Team,</p><p> The below case/s have been initiated to you. Kindly have them closed within TAT.</p>";
                                        
                $message .= "<table border = '2'  style='border-spacing: 10; border-collapse: collapse;'>
                    <tr>
                        <th style='background-color: #EDEDED;text-align:center'>Sr No</th>
                        <th style='background-color: #EDEDED;text-align:center'>Component Ref No</th>
                        <th style='background-color: #EDEDED;text-align:center'>Candidate Name</th>
                        <th style='background-color: #EDEDED;text-align:center'>Date of Birth</th>
                        <th style='background-color: #EDEDED;text-align:center'>Father's Name</th>
                        <th style='background-color: #EDEDED;text-align:center'>Address</th>
                        <th style='background-color: #EDEDED;text-align:center'>City</th>
                        <th style='background-color: #EDEDED;text-align:center'>Pincode</th>
                        <th style='background-color: #EDEDED;text-align:center'>State</th>
                    </tr>";
                $court_vendor_log_id = array();
                $m = 1;
                foreach ($value as $court_key => $court_value) {
                                    
                    $message .= '<tr>
                        <td style="text-align:center">'.$m.'</td>
                        <td style="text-align:center">'.$court_value['court_com_ref'] . '</td>
                        <td style="text-align:center">'.ucwords($court_value['CandidateName']). '</td>
                        <td style="text-align:center">'.convert_db_to_display_date($court_value['DateofBirth']). '</td>
                        <td style="text-align:center">'.ucwords($court_value['NameofCandidateFather']) . '</td>
                        <td style="text-align:center">'.ucwords($court_value['street_address']) . '</td>
                        <td style="text-align:center">'.ucwords($court_value['city']) . '</td>
                        <td style="text-align:center">'.$court_value['pincode'] . '</td>
                        <td style="text-align:center">'.ucwords($court_value['state']) . '</td>
                    </tr>';

                    $court_vendor_log_id[] = $court_value['courtver_vendor_log_id'];
                                        
                    $m++; 
                                        
                } 
                $message .= "</table>";

                $message .= "<br><br><p><b>Note :</b> <I>This is an auto generated email. Request you to write back within 24 hrs with any discrepancy.</I>";
                                        
                $to_emails = $this->court_verificatiion_model->vendor_email_id(array('vendors.id' => $key));
            
                $email_tmpl_data['to_emails'] = $to_emails[0]['email_id'];

                $email_tmpl_data['message'] = $message;
                
                $result = $this->email->address_approve_vendor_cases_mail_send($email_tmpl_data);
                              
                if(!empty($result) &&  $result == "Success")
                {
                    if(!empty($court_vendor_log_id))
                    {
                        foreach($court_vendor_log_id as $court_vendor_log_key => $court_vendor_log_value)
                        {
                            $update = $this->court_verificatiion_model->upload_vendor_assign('courtver_vendor_log', array('status' => 1, 'approval_by' => 1, "modified_on" => date(DB_DATE_FORMAT)), array('id' => $court_vendor_log_value));

                            if ($update) {

                                $update_counter++;
                                $field_array = array('component' => 'courtver',
                                    'component_tbl_id' => '5',
                                    'case_id' => $court_vendor_log_value,
                                    'trasaction_id' => 'Txn',
                                    "status" => 1,
                                    "remarks" => '',
                                    "approval_by" => 1,
                                    "approval_on" => date(DB_DATE_FORMAT),
                                    "created_by" => 1,
                                    "created_on" => date(DB_DATE_FORMAT),
                                    "vendor_status" => 0,
                                    "modified_on" => null,
                                    "modified_by" => '',
                                );

                                $insert_id = $this->common_model->common_insert_transaction_no('view_vendor_master_log', $field_array);
                                $update_transaction_id = $this->common_model->update_transaction_id(array('trasaction_id' => "Txn" . $insert_id), array('id' => $insert_id));
                            }
                                                
                        }
                    } 
                }

                $this->email->clear(true);
            }
        }
    }
    if(in_array('globdbver',$component_selection))
    {    
        $this->load->model('global_database_model');

        $list_approve_case_global =  $this->global_database_model->select_global_approve_list(array('glodbver_vendor_log.status' => 0));

        $list_global = []; 
        foreach ($list_approve_case_global as $childArray_global) 
        { 
            foreach ($childArray_global as $value_global) 
            { 
               $list_global[] = $value_global; 
            } 
        }
      

        $update_counter = 0;

        foreach ($list_global as $key => $value_global) {

            $global_details[] = $this->global_database_model->get_global_database_details_for_approval($value_global);

        }

        if(isset($global_details) && !empty($global_details))
        {
                            
            $global_detail =  (array_map('current', $global_details));
                                
            foreach($global_detail as $k => $v_global) {
                $new_arr_global[$v_global['vendor_id']][]=$v_global;
            } 


            foreach ($new_arr_global as $key => $value) {

                $vendor_name = $this->global_database_model->vendor_email_id(array('vendors.id' => $key));

                $email_tmpl_data['subject'] = 'Global Database - '.ucwords($vendor_name[0]['vendor_name']).' - New case/s initiated by '.CRMNAME;
                $message = "<p>Team,</p><p> The below case/s have been initiated to you. Kindly have them closed within TAT.</p>";
                                        
                $message .= "<table border = '2'  style='border-spacing: 10; border-collapse: collapse;'>
                    <tr>
                        <th style='background-color: #EDEDED;text-align:center'>Sr No</th>
                        <th style='background-color: #EDEDED;text-align:center'>Component Ref No</th>
                        <th style='background-color: #EDEDED;text-align:center'>Candidate Name</th>
                        <th style='background-color: #EDEDED;text-align:center'>Date of Birth</th>
                        <th style='background-color: #EDEDED;text-align:center'>Gender</th>
                        <th style='background-color: #EDEDED;text-align:center'>Father's Name</th>
                        <th style='background-color: #EDEDED;text-align:center'>Address</th>
                        <th style='background-color: #EDEDED;text-align:center'>City</th>
                        <th style='background-color: #EDEDED;text-align:center'>Pincode</th>
                        <th style='background-color: #EDEDED;text-align:center'>State</th>
                    </tr>";

                $global_vendor_log_id = array();
                $m = 1;

                foreach ($value as $global_key => $global_value) {
                                    
                    $message .= '<tr>
                        <td style="text-align:center">'.$m.'</td>
                        <td style="text-align:center">'.$global_value['global_com_ref'] . '</td>
                        <td style="text-align:center">'.ucwords($global_value['CandidateName']). '</td>
                        <td style="text-align:center">'.convert_db_to_display_date($global_value['DateofBirth']). '</td>
                        <td style="text-align:center">'.$global_value['gender']. '</td>
                        <td style="text-align:center">'.ucwords($global_value['NameofCandidateFather']) . '</td>
                        <td style="text-align:center">'.ucwords($global_value['street_address']) . '</td>
                        <td style="text-align:center">'.ucwords($global_value['city']) . '</td>
                        <td style="text-align:center">'.$global_value['pincode'] . '</td>
                        <td style="text-align:center">'.ucwords($global_value['state']) . '</td>
                    </tr>';

                    $global_vendor_log_id[] = $global_value['glodbver_vendor_log_id'];
                                        
                    $m++; 
                                        
                } 
                $message .= "</table>";

                $message .= "<br><br><p><b>Note :</b> <I>This is an auto generated email. Request you to write back within 24 hrs with any discrepancy.</I>";
                                        
                $to_emails = $this->global_database_model->vendor_email_id(array('vendors.id' => $key));
            
                $email_tmpl_data['to_emails'] = $to_emails[0]['email_id'];

                $email_tmpl_data['message'] = $message;
                
                $result = $this->email->address_approve_vendor_cases_mail_send($email_tmpl_data);

                if(!empty($result) &&  $result == "Success")
                {
                    if(!empty($global_vendor_log_id))
                    {
                        foreach($global_vendor_log_id as $global_vendor_log_key => $global_vendor_log_value)
                        {
                            $update = $this->global_database_model->upload_vendor_assign('glodbver_vendor_log', array('status' => 1, 'approval_by' => 1, "modified_on" => date(DB_DATE_FORMAT)), array('id' => $global_vendor_log_value));

                            if ($update) {
                                $update_counter++;
                                $field_array = array('component' => 'globdbver',
                                    'component_tbl_id' => '6',
                                    'case_id' => $global_vendor_log_value,
                                    'trasaction_id' => 'Txn',
                                    "status" => 1,
                                    "remarks" => '',
                                    "approval_by" => 1,
                                    "approval_on" => date(DB_DATE_FORMAT),
                                    "created_by" => 1,
                                    "created_on" => date(DB_DATE_FORMAT),
                                    "vendor_status" => 0,
                                    "modified_on" => null,
                                    "modified_by" => '',
                                );
                                $insert_id = $this->common_model->common_insert_transaction_no('view_vendor_master_log', $field_array);
                                $update_transaction_id = $this->common_model->update_transaction_id(array('trasaction_id' => "Txn" . $insert_id), array('id' => $insert_id));
                            } 
                        }
                    }

                }

                $this->email->clear(true);
            } 
        }
    } 
       
    if(in_array('crimver',$component_selection))
    {     

        $this->load->model('pcc_verificatiion_model');

        $list_approve_case_pcc =  $this->pcc_verificatiion_model->select_pcc_approve_list(array('pcc_vendor_log.status' => 0));

        $list_pcc = []; 
        foreach ($list_approve_case_pcc as $childArray_pcc) 
        { 
            foreach ($childArray_pcc as $value_pcc) 
            { 
               $list_pcc[] = $value_pcc; 
            } 
        }
      

        $update_counter = 0;

        foreach ($list_pcc as $key => $value_pcc) {

            $pcc_details[] = $this->pcc_verificatiion_model->get_pcc_details_for_approval($value_pcc);

        }

        if(isset($pcc_details) && !empty($pcc_details))
        {
            $pcc_detail =  (array_map('current', $pcc_details));
            
            foreach($pcc_detail as $k => $v_pcc) {
                $new_arr_pcc[$v_pcc['vendor_id']][]=$v_pcc;
            }

            foreach ($new_arr_pcc as $key => $value) {

                $vendor_name = $this->pcc_verificatiion_model->vendor_email_id(array('vendors.id' => $key));


                $email_tmpl_data['subject'] = 'PCC Verification - '.ucwords($vendor_name[0]['vendor_name']).' - New case/s initiated by '.CRMNAME;
                $message = "<p>Team,</p><p> The below case/s have been initiated to you. Kindly have them closed within TAT.</p>";
                                        
                $message .= "<table border = '2'  style='border-spacing: 10; border-collapse: collapse;'>
                    <tr>
                        <th style='background-color: #EDEDED;text-align:center'>Sr No</th>
                        <th style='background-color: #EDEDED;text-align:center'>Component Ref No</th>
                        <th style='background-color: #EDEDED;text-align:center'>Candidate Name</th>
                        <th style='background-color: #EDEDED;text-align:center'>Date of Birth</th>
                        <th style='background-color: #EDEDED;text-align:center'>Father's Name</th>
                        <th style='background-color: #EDEDED;text-align:center'>Address</th>
                        <th style='background-color: #EDEDED;text-align:center'>City</th>
                        <th style='background-color: #EDEDED;text-align:center'>Pincode</th>
                        <th style='background-color: #EDEDED;text-align:center'>State</th>
                    </tr>";
                $pcc_vendor_log_id = array();
                $m = 1;
                foreach ($value as $pcc_key => $pcc_value) {
                                    
                    $message .= '<tr>
                        <td style="text-align:center">'.$m.'</td>
                        <td style="text-align:center">'.$pcc_value['pcc_com_ref'] . '</td>
                        <td style="text-align:center">'.ucwords($pcc_value['CandidateName']). '</td>
                        <td style="text-align:center">'.convert_db_to_display_date($pcc_value['DateofBirth']). '</td>
                        <td style="text-align:center">'.ucwords($pcc_value['NameofCandidateFather']) . '</td>
                        <td style="text-align:center">'.ucwords($pcc_value['street_address']) . '</td>
                        <td style="text-align:center">'.ucwords($pcc_value['city']) . '</td>
                        <td style="text-align:center">'.$pcc_value['pincode'] . '</td>
                        <td style="text-align:center">'.ucwords($pcc_value['state']) . '</td>
                    </tr>';

                    $pcc_vendor_log_id[] = $pcc_value['pcc_vendor_log_id'];
                                        
                    $m++; 
                                        
                } 
                    $message .= "</table>";

                    $message .= "<br><br><p><b>Note :</b> <I>This is an auto generated email. Request you to write back within 24 hrs with any discrepancy.</I>";
                                        
                    $to_emails = $this->pcc_verificatiion_model->vendor_email_id(array('vendors.id' => $key));
            
                    $email_tmpl_data['to_emails'] = ($to_emails[0]['pcc_mov_email'] == "") ? $to_emails[0]['email_id'] : $to_emails[0]['pcc_mov_email'];

                    $email_tmpl_data['message'] = $message;
                
                    $result = $this->email->address_approve_vendor_cases_mail_send($email_tmpl_data);
                              
                    if(!empty($result) &&  $result == "Success")
                    {
                        if(!empty($pcc_vendor_log_id))
                        {
                            foreach($pcc_vendor_log_id as $pcc_vendor_log_key => $pcc_vendor_log_value)
                            {
                                $update = $this->pcc_verificatiion_model->upload_vendor_assign('pcc_vendor_log', array('status' => 1, 'approval_by' => 1, "modified_on" => date(DB_DATE_FORMAT)), array('id' => $pcc_vendor_log_value));

                                if ($update) {

                                    $update_counter++;
                                    $field_array = array('component' => 'crimver',
                                        'component_tbl_id' => '8',
                                        'case_id' => $pcc_vendor_log_value,
                                        'trasaction_id' => 'Txn',
                                        "status" => 1,
                                        "remarks" => '',
                                        "approval_by" => 1,
                                        "approval_on" => date(DB_DATE_FORMAT),
                                        "created_by" => 1,
                                        "created_on" => date(DB_DATE_FORMAT),
                                        "vendor_status" => 0,
                                        "modified_on" => null,
                                        "modified_by" => '',
                                    );

                                    $insert_id = $this->common_model->common_insert_transaction_no('view_vendor_master_log', $field_array);
                                    $update_transaction_id = $this->common_model->update_transaction_id(array('trasaction_id' => "Txn" . $insert_id), array('id' => $insert_id));
                                }
                                                
                            }
                        } 
                    }

                $this->email->clear(true);
            }
        }
    }     
    if(in_array('cbrver',$component_selection))
    {


        $this->load->model('credit_report_model');

        $list_approve_case_credit =  $this->credit_report_model->select_credit_approve_list(array('credit_report_vendor_log.status' => 0));

        $list_credit = []; 
        foreach ($list_approve_case_credit as $childArray_credit) 
        { 
            foreach ($childArray_credit as $value_credit) 
            { 
               $list_credit[] = $value_credit; 
            } 
        }

        $update_counter = 0;

        foreach ($list_credit as $key => $value_credit) {

            $credit_report_details[] = $this->credit_report_model->get_credit_report_details_for_approval($value_credit);
        }

        if(isset($credit_report_details) && !empty($credit_report_details))
        {
                              
                $credit_report_detail =  (array_map('current', $credit_report_details));
                                
                foreach($credit_report_detail as $k => $v_credit) {
                    $new_arr_credit[$v_credit['vendor_id']][]=$v_credit;
                }
                                
            foreach ($new_arr_credit as $key => $value) {

                $vendor_name = $this->credit_report_model->vendor_email_id(array('vendors.id' => $key));

                $email_tmpl_data['subject'] = 'Credit Report - '.ucwords($vendor_name[0]['vendor_name']).' - New case/s initiated by '.CRMNAME;
                $message = "<p>Team,</p><p> The below case/s have been initiated to you. Kindly have them closed within TAT.</p>";
                                        
                $message .= "<table border = '2'  style='border-spacing: 10; border-collapse: collapse;'>
                   <tr>
                        <th style='background-color: #EDEDED;text-align:center'>Sr No</th>
                        <th style='background-color: #EDEDED;text-align:center'>Component Ref No</th>
                        <th style='background-color: #EDEDED;text-align:center'>Candidate Name</th>
                        <th style='background-color: #EDEDED;text-align:center'>Date of Birth</th>
                        <th style='background-color: #EDEDED;text-align:center'>Father's Name</th>
                        <th style='background-color: #EDEDED;text-align:center'>Pan No</th>
                        <th style='background-color: #EDEDED;text-align:center'>Address</th>
                    </tr>";

                $credit_report_vendor_log_id = array();
                $m = 1;

                foreach ($value as $credit_report_key => $credit_report_value) {
                                    
                    $message .= '<tr>
                        <td style="text-align:center">'.$m.'</td>
                        <td style="text-align:center">'.$credit_report_value['credit_report_com_ref'] . '</td>
                        <td style="text-align:center">'.ucwords($credit_report_value['CandidateName']). '</td>
                        <td style="text-align:center">'.convert_db_to_display_date($credit_report_value['DateofBirth']). '</td>
                        <td style="text-align:center">'.ucwords($credit_report_value['NameofCandidateFather']) . '</td>
                        <td style="text-align:center">'.ucwords($credit_report_value['id_number']) . '</td>
                        <td style="text-align:center">'.ucwords($credit_report_value['street_address']) . '</td>

                    </tr>';

                    $credit_report_vendor_log_id[] = $credit_report_value['credit_report_vendor_log_id'];
                                        
                    $m++; 
                                        
                } 
                $message .= "</table>";

                $message .= "<br><br><p><b>Note :</b> <I>This is an auto generated email. Request you to write back within 24 hrs with any discrepancy.</I>";
                                        
                $to_emails = $this->credit_report_model->vendor_email_id(array('vendors.id' => $key));
            
                $email_tmpl_data['to_emails'] = $to_emails[0]['email_id'];

                $email_tmpl_data['message'] = $message;
                
                $result = $this->email->address_approve_vendor_cases_mail_send($email_tmpl_data);

                if(!empty($result) &&  $result == "Success")
                {
                    if(!empty($credit_report_vendor_log_id))
                    {
                        foreach($credit_report_vendor_log_id as $credit_report_vendor_log_key => $credit_report_vendor_log_value)
                        {

                            $update = $this->credit_report_model->upload_vendor_assign('credit_report_vendor_log', array('status' => 1, 'approval_by' => 1 , "modified_on" => date(DB_DATE_FORMAT)), array('id' => $credit_report_vendor_log_value));

                            if ($update) {

                                $update_counter++;
                                $field_array = array('component' => 'cbrver',
                                    'component_tbl_id' => '10',
                                    'case_id' => $credit_report_vendor_log_value,
                                    'trasaction_id' => 'Txn',
                                    "status" => 1,
                                    "remarks" => '',
                                    "approval_by" => 1,
                                    "approval_on" => date(DB_DATE_FORMAT),
                                    "created_by" => 1,
                                    "created_on" => date(DB_DATE_FORMAT),
                                    "vendor_status" => 0,
                                    "modified_on" => null,
                                    "modified_by" => '',
                                );

                                $insert_id = $this->common_model->common_insert_transaction_no('view_vendor_master_log', $field_array);
                                $update_transaction_id = $this->common_model->update_transaction_id(array('trasaction_id' => "Txn" . $insert_id), array('id' => $insert_id));

                            }

                        }
                    }
                }

                $this->email->clear(true);  
            }
        }
    }
    if(in_array('narcver',$component_selection))
    {
       
        $this->load->model('drug_verificatiion_model');

        $list_approve_case_drugs =  $this->drug_verificatiion_model->select_drugs_approve_list(array('drug_narcotis_vendor_log.status' => 0));
        
        $list_drugs = []; 
        foreach ($list_approve_case_drugs as $childArray_drugs) 
        { 
            foreach ($childArray_drugs as $value_drugs) 
            { 
               $list_drugs[] = $value_drugs; 
            } 
        }
        
        $update_counter = 0;

        foreach ($list_drugs as $key => $value_drugs) {

            $drugs_details[] = $this->drug_verificatiion_model->get_drugs_details_for_approval($value_drugs);
        }

        if(isset($drugs_details) && !empty($drugs_details))
        {
   
            $drugs_detail =  (array_map('current', $drugs_details));
                               
            foreach($drugs_detail as $k => $v_drugs) {
                $new_arr_drugs[$v_drugs['vendor_id']][]=$v_drugs;
            }
                         
            foreach ($new_arr_drugs as $key => $value) {

                $vendor_name = $this->drug_verificatiion_model->vendor_email_id(array('vendors.id' => $key));

                $email_tmpl_data['subject'] = 'Drugs - '.ucwords($vendor_name[0]['vendor_name']).' - New case/s initiated by '.CRMNAME;
                $message = "<p>Team,</p><p> The below case/s have been initiated to you. Kindly have them closed within TAT.</p>";
                                    
                $message .= "<table border = '2'  style='border-spacing: 10; border-collapse: collapse;'>
                    <tr>
                        <th style='background-color: #EDEDED;text-align:center'>Sr No</th>
                        <th style='background-color: #EDEDED;text-align:center'>Client Name</th>
                        <th style='background-color: #EDEDED;text-align:center'>Component Ref No</th>
                        <th style='background-color: #EDEDED;text-align:center'>Panel</th>
                        <th style='background-color: #EDEDED;text-align:center'>Candidate Name</th>
                        <th style='background-color: #EDEDED;text-align:center'>Primary Contact</th>
                        <th style='background-color: #EDEDED;text-align:center'>Contact No (2)</th>
                        <th style='background-color: #EDEDED;text-align:center'>Contact No (3)</th>
                        <th style='background-color: #EDEDED;text-align:center'>Date of Birth</th>
                        <th style='background-color: #EDEDED;text-align:center'>Father's Name</th>
                        <th style='background-color: #EDEDED;text-align:center'>Address</th>
                        <th style='background-color: #EDEDED;text-align:center'>City</th>
                        <th style='background-color: #EDEDED;text-align:center'>Pincode</th>
                        <th style='background-color: #EDEDED;text-align:center'>State</th>
                    </tr>";
                                   
                $drug_narcotis_vendor_log_id = array();
                $m = 1;
                foreach ($value as $drugs_key => $drugs_value) {
                                
                    $message .= '<tr>
                        <td style="text-align:center">'.$m.'</td>
                        <td style="text-align:center">'.ucwords($drugs_value['clientname']). '</td>
                        <td style="text-align:center">'.$drugs_value['drug_com_ref'] . '</td>
                        <td style="text-align:center">'.$drugs_value['drug_test_code'] . '</td>
                        <td style="text-align:center">'.ucwords($drugs_value['CandidateName']) . '</td>
                        <td style="text-align:center">'.$drugs_value['CandidatesContactNumber'] . '</td>
                        <td style="text-align:center">'.$drugs_value['ContactNo1'] . '</td>
                        <td style="text-align:center">'.$drugs_value['ContactNo2'] . '</td>
                        <td style="text-align:center">'.convert_db_to_display_date($drugs_value['DateofBirth']) . '</td>
                        <td style="text-align:center">'.ucwords($drugs_value['NameofCandidateFather']) . '</td>
                        <td style="text-align:center">'.ucwords($drugs_value['street_address']) . '</td>
                        <td style="text-align:center">'.ucwords($drugs_value['city']) . '</td>
                        <td style="text-align:center">'.$drugs_value['pincode'] . '</td>
                        <td style="text-align:center">'.ucwords($drugs_value['state']) . '</td>
                        </tr>';

                    $drug_narcotis_vendor_log_id[] = $drugs_value['drug_narcotis_vendor_log_id'];
                                        
                    $m++;
                                       
                } 

                    $message .= "</table>";

                    $message .= "<br><br><p><b>Note :</b> <I>This is an auto generated email. Request you to write back within 24 hrs with any discrepancy.</I>";
                                    
                    $to_emails = $this->drug_verificatiion_model->vendor_email_id(array('vendors.id' => $key));
         
                    $email_tmpl_data['to_emails'] = $to_emails[0]['email_id'];

                    $email_tmpl_data['message'] = $message;

                    $result = $this->email->address_approve_vendor_cases_mail_send($email_tmpl_data);

                    if(!empty($result) &&  $result == "Success")
                    {
                        if(!empty($drug_narcotis_vendor_log_id))
                        {
                            foreach($drug_narcotis_vendor_log_id as $drug_narcotis_vendor_log_key => $drug_narcotis_vendor_log_value)
                            {
                                $update = $this->drug_verificatiion_model->upload_vendor_assign('drug_narcotis_vendor_log', array('status' => 1, 'approval_by' => 1, "modified_on" => date(DB_DATE_FORMAT)), array('id' => $drug_narcotis_vendor_log_value));

                                if ($update) {
                
                                    $update_counter++;
                                    $field_array = array('component' => 'narcver',
                                        'component_tbl_id' => '7',
                                        'case_id' => $drug_narcotis_vendor_log_value,
                                        'trasaction_id' => 'Txn',
                                        "status" => 1,
                                        "remarks" => '',
                                        "approval_by" => 1,
                                        "approval_on" => date(DB_DATE_FORMAT),
                                        "created_by" => 1,
                                        "created_on" => date(DB_DATE_FORMAT),
                                        "vendor_status" => 0,
                                        "modified_on" => null,
                                        "modified_by" => 1,
                                    );
                                    $insert_id = $this->common_model->common_insert_transaction_no('view_vendor_master_log', $field_array);
                                    $update_transaction_id = $this->common_model->update_transaction_id(array('trasaction_id' => "Txn" . $insert_id), array('id' => $insert_id));
                                }

                            }
                        }
                    }
                    $this->email->clear(true);
            }                                                 
        }        
      }

        $this->Cron_job_model->save(array('executed_on'=>date(DB_DATE_FORMAT),'created_by'=> 1,'status'=> 1),array('id' => 5));
    }   
}

//D:\xampp/php\php.exe D:\xampp\htdocs\client_bgv\index.php cli_request_only tat_schedular
