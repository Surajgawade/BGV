<?php defined('BASEPATH') or exit('No direct script access allowed');
class Digital_closure extends MY_Controller
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


        $this->perm_array = array('page_id' => 4);
        if ($this->input->is_ajax_request()) {
            $this->perm_array = array('direct_access' => true);

        }
        $this->load->model(array('addressver_model'));
    }

    public function index()
    {
    

        $details['vendor_digital_vendor_list'] = $this->addressver_model->address_case_global_list_closed(array('addrver.vendor_digital_id' => 25,'addrver.verification_status' => 2,'address_details.status' => 1));

        $this->load->view('admin/header', $details);
        $this->load->view('admin/address_digital_vendor_closure_entries');
        $this->load->view('admin/footer');

    }

    public function view_vendor_log_digital($where_id)
    {
        
        $details = $this->addressver_model->address_case_global_list_closed(array('addrver.vendor_digital_id' => 25,'addrver.verification_status' => 2,'addrver.id' => $where_id));
        if ($where_id && !empty($details)) {


            $details['js_library'] = array('html2canvase.js');
            $details['header_title'] = ucwords($details[0]['CandidateName']).' Profile';  
            $details['details'] = $details[0];
            $latitude = $details[0]['latitude'];
            $longitude = $details[0]['longitude'];
           
            exec("python3 /var/www/html/mist-it/convert.py $latitude $longitude 2>&1", $address, $ret_code);

            $details['details'] = $details[0];
            $details['address'] = $address[0];


            $this->load->view('admin/header',$details);

            $this->load->view('admin/address_digital_vednor_approve_tab');
              
            $this->load->view('admin/footer');
  
           
        } else {
            echo "<h4>Record Not Found</h4>";
        }

    }

    public function export_to_excel()
    {
        $json_array = array();

        if ($this->input->is_ajax_request()) {

            log_message('error', 'Digital Address Export To Excel');
            try {

                ini_set('memory_limit', '-1');
                set_time_limit(0);

                $where_arry = array();

              
                $all_records = $this->addressver_model->get_all_digital_address(array('addrver.vendor_digital_id' => 25,'addrver.verification_status' => 1,'a1.var_filter_status'=> "WIP"));

                require_once APPPATH . '/third_party/Phpexcel/Bootstrap.php';
                // Create new Spreadsheet object
                $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

                // Set document properties
                $spreadsheet->getProperties()->setCreator(CRMNAME)
                    ->setLastModifiedBy(CRMNAME)
                    ->setTitle(CRMNAME)
                    ->setSubject('Digital Address records')
                    ->setDescription('Digital Address records with their status');

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
                $spreadsheet->getActiveSheet()->getStyle('A1:T1')->applyFromArray($styleArray);
                // auto fit column to content
                foreach (range('A', 'T') as $columnID) {
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
                    ->setCellValue("I1", 'Primary Contact')
                    ->setCellValue("J1", 'Contact No (2)')
                    ->setCellValue("K1", 'Contact No (3)')
                    ->setCellValue("L1", 'Address')
                    ->setCellValue("M1", 'City')
                    ->setCellValue("N1", 'Pincode')
                    ->setCellValue("O1", 'State')
                    ->setCellValue("P1", 'Status')
                    ->setCellValue("Q1", 'Sub Status')
                    ->setCellValue("R1", 'Executive Name')
                    ->setCellValue("S1", 'Activity Remark')
                    ->setCellValue("T1", 'Digital Link');
                // Add some data
                $x = 2;
                foreach ($all_records as $all_record) {
                      
                    $logs = $this->addressver_model->follow_up_activity_log_records(array('comp_table_id' => $all_record['id'], 'component_type' => 'address','action'=>"Follow Up"));
                    $activity_remark = array();
                    if(!empty( $logs))
                    {

                        foreach ($logs as $key => $value) {
                            
                            array_push($activity_remark,$value['created_on'].' - '.$value['remarks']);
                         
                        }
                    }           
                    $ad_status = ($all_record['verfstatus'] != "") ? $all_record['verfstatus'] : "WIP";
                    $ad_filter_status = ($all_record['filter_status'] != "") ? $all_record['filter_status'] : "WIP";
                    
                    $digital_link = SITE_URL.'av/'.base64_encode($all_record['id']);

                    $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue("A$x", ucwords($all_record['clientname']))
                        ->setCellValue("B$x", ucwords($all_record['entity_name']))
                        ->setCellValue("C$x", ucwords($all_record['package_name']))
                        ->setCellValue("D$x", $all_record['cmp_ref_no'])
                        ->setCellValue("E$x", $all_record['add_com_ref'])
                        ->setCellValue("F$x", $all_record['ClientRefNumber'])
                        ->setCellValue("G$x", convert_db_to_display_date($all_record['iniated_date']))
                        ->setCellValue("H$x", ucwords($all_record['CandidateName']))
                        ->setCellValue("I$x", $all_record['CandidatesContactNumber'])
                        ->setCellValue("J$x", $all_record['ContactNo1'])
                        ->setCellValue("K$x", $all_record['ContactNo2'])
                        ->setCellValue("L$x", $all_record['address'])
                        ->setCellValue("M$x", $all_record['city'])
                        ->setCellValue("N$x", $all_record['pincode'])
                        ->setCellValue("O$x", $all_record['state'])
                        ->setCellValue("P$x", $ad_filter_status)
                        ->setCellValue("Q$x", $ad_status)
                        ->setCellValue("R$x", $all_record['executive_name'])
                        ->setCellValue("S$x", implode(' || ', $activity_remark))
                        ->setCellValue("T$x", $digital_link);

                    $x++;
                }
                // Rename worksheet
                $spreadsheet->getActiveSheet()->setTitle('Digital Records');

                $spreadsheet->setActiveSheetIndex(0);

                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header("Content-Disposition: attachment;filename=Digital Records.xlsx");
                header('Cache-Control: max-age=0');
                // If you're serving to IE 9, then the following may be needed
                header('Cache-Control: max-age=1');

                // If you're serving to IE over SSL, then the following may be needed
                header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
                header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                header('Pragma: public'); // HTTP/1.0

                $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Excel2007');

                ob_start();
                $writer->save('php://output');
                $xlsData = ob_get_contents();
                ob_end_clean();

                $json_array['file'] = "data:application/vnd.ms-excel;base64," . base64_encode($xlsData);

                $json_array['file_name'] = "Digital Records";

                $json_array['message'] = "File downloaded successfully,please check in download folder";

                $json_array['status'] = SUCCESS_CODE;

            } catch (Exception $e) {
                log_message('error', 'Digital closure::export_to_excel');
                log_message('error', $e->getMessage());
            }

        } else {
            $json_array['message'] = "Something went wrong,please try again";
            $json_array['status'] = ERROR_CODE;
        }

        echo_json($json_array);
    }

    public function rotate_image() {
       

        if ($this->input->is_ajax_request()) {
          
            $frm_details =  $this->input->post();
            
            if(!empty($frm_details))
            {
                $info = pathinfo($frm_details['file_name']);
                
                $path =  SITE_BASE_PATH . ADDRESS . "address_verification/";
                $file_name = $info['basename'];
           
                if ($info['extension'] == 'jpeg') 
                {
                    $image = imagecreatefromjpeg($path.$file_name);
                }
                elseif ($info['extension'] == 'jpg') 
                {
                    $image = imagecreatefromjpeg($path.$file_name);
                }
                elseif ($info['extension'] == 'gif') 
                {
                    $image = imagecreatefromgif($path.$file_name);
                }

                elseif ($info['extension'] == 'png') 
                {
                    $image = imagecreatefrompng($path.$file_name);
                }
                $img = imagerotate($image, 90,0 );
              
                imagejpeg($img,  $path.$file_name );
                  
                //imagejpeg($image,$path.$file_name, 100);
               // header("Content-type: image/".$info['extension']); 
                  
                //imagepng($img); 
                //echo '<img src="'.SITE_URL.ADDRESS . "address_verification/".$file_name.'" width="450" height="300" />';

             //  echo SITE_URL.ADDRESS . "address_verification/".$file_name;
              //return SITE_URL.ADDRESS . "address_verification/".$file_name; 
                echo SITE_URL.ADDRESS . "address_verification/".$file_name;

            } 
            else{

                echo "Something went wrong";
            }
           
  
        } else {
             echo "Something went wrong";
        }
        
    }

}


