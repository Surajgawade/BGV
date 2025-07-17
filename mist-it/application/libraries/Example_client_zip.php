<?php
require_once dirname(__FILE__).'/tcpdf_min/include/tcpdf_include.php';

// Extend the TCPDF class to create custom Header and Footer
class Example_client_zip extends TCPDF {

	//Page header
	var $fontname = '';
    
	function __construct()
	{

		parent::__construct();
    
		//$this->fontname = TCPDF_FONTS::addTTFfont(APPPATH.'libraries/tcpdf_min/fonts/arial.ttf', 'TrueTypeUnicode', '', 96);


	}

	public function Header() {
   	
		$this->SetProtection(array('copy','modify'),null, null, 0, null);
              
	
			$image_file = 'assets/images/logo.jpeg';
			$this->Image($image_file, 5, 7, 50, '', 'jpeg', '', 'T', false, 300, 'R', false, false, 0, false, false, false);
      
		$this->SetFont($this->fontname, 'B', 20); // Title
		
		$this->SetLineStyle(array('width' => 1.2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(5, 46, 125)));

		$this->Line(14, 22, $this->getPageWidth()-14, 22);

		$this->Line(14, $this->getPageHeight()-15, $this->getPageWidth()-14, $this->getPageHeight()-14);
		$this->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP+3, PDF_MARGIN_RIGHT);

	}	

	// Page footer
	public function Footer() {
		$this->SetY(-15);
		// Set font
		$this->SetFont($this->fontname, 'I', 12);
		// Page number
		
			$this->Cell(50, 10, WEBSITE, 0, false, 'L', 0, '', 0, false, 'T', 'M');  
            
			$this->Cell(90, 10, 'Confidential', 0, false, 'C', 0, '', 0, false, 'T', 'M'); 
			$this->Cell(50, 9, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M' );
	
        
	}

	public function generate_pdf($reports_data,$portal_type)
	{
 
	   set_time_limit(0);
		 
        $cand_info = $reports_data['cand_info'];

        $pcc_info = $reports_data['pcc_info'];

        $education_info = $reports_data['education_info'];
    
        $employment_info = $reports_data['employment_info'];

		$pdf = new Example_client_zip(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		$pdf->SetCreator(PDF_CREATOR);

		$pdf->SetAuthor(AUTHOR);
		$pdf->SetTitle('BGV Reports');
		$pdf->SetSubject('Reports');
		//$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

		// set default header data
		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 048', PDF_HEADER_STRING);

		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));


		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// set some language-dependent strings (optional)
		
		if (@file_exists(dirname(__FILE__).'/tcpdf_min/lang/eng.php')) {
		    require_once(dirname(__FILE__).'/tcpdf_min/lang/eng.php');
		    $pdf->setLanguageArray($l);
		}

		$pdf->AddPage();
		
		//$pdf->setCellMargins(0, 0, 0, 0);
        $pdf->setCellMargins(1, 1, 1, 1);

		$pdf->SetFont($this->fontname,'B',10);   

		$pdf->SetFillColor(237,237,237);

        $dob = ($cand_info["DateofBirth"] != '') ? $cand_info["DateofBirth"] : 'NA';
        $employeeCode =  ($cand_info["EmployeeCode"] != '') ? $cand_info["EmployeeCode"] : 'NA';

        $header_name = '<style>th, td {text-align: left; }</style><table cellpadding="5" width="98%">';
        $header_name .= '<tr>';
        $header_name .= '<td bgcolor="#f2f2f2" style="text-align: center;height: 35px;"><b> Antecedent Credentialing & BGV of '.ucwords($cand_info["CandidateName"]).'</b></td>';
        $header_name .= '</tr>';    
        $header_name .= '</table>';

        $pdf->writeHTML($header_name, true, false, false, false, '');

        $pdf->SetFont($this->fontname, '', 10);
       
       $cans_tbl = '<table cellpadding="4" width="95%" >
        <tr>
            <td bgcolor="#f2f2f2" border = "1">Employee Name</td>
            <td border = "1">'.ucwords($cand_info["CandidateName"]).'</td>
            <td bgcolor="#f2f2f2" border = "1"> Date Of Initiation </td>
            <td border = "1">'.date('d-M-Y', strtotime($cand_info["caserecddate"])).'</td>
        </tr>
        <tr>
            <td bgcolor="#f2f2f2" border = "1"> Employee Code </td>
            <td border = "1">'.strtoupper($cand_info["ClientRefNumber"]).'</td>
            <td bgcolor="#f2f2f2" border = "1"> Date of Report </td>
            <td border = "1">'.date('d-M-Y', strtotime($cand_info['overallclosuredate'])).'</td>
        </tr>
        <tr>
            <td bgcolor="#f2f2f2" border = "1"> Gender </td>
            <td border = "1"> '.strtoupper($cand_info["gender"]).'</td>
            <td bgcolor="#f2f2f2" border = "1"> Client Name </td>
            <td border = "1">'.ucwords($cand_info["clientname"]).'</td>
        </tr>
        <tr>
            <td bgcolor="#f2f2f2" border = "1"> Date of Birth </td>
            <td border = "1">'.date('d-M-Y', strtotime($dob)).'</td>
            <td bgcolor="#f2f2f2" border = "1"> Ref. No </td>
            <td border = "1">'.ucwords($cand_info["cmp_ref_no"]).'</td>
        </tr>
        
        </table>';


        
        $pdf->writeHTML($cans_tbl, true, false, false, false, '');

    if(!empty($pcc_info))  
    {     

    foreach ($pcc_info as $key => $value) 
    { 
        $full_address = $value['street_address'].' '.$value['city'].' '.$value['pincode'].' '.$value['state'];

        $verification_date = ($value['closuredate'] != '') ? date('d-M-Y', strtotime($value['closuredate'])) : "NA";

        $Verified_status = ($value['verfstatus'] != '') ? $value['verfstatus'] : "NA";
        $Verified_status_clear = ($Verified_status == "Clear") ?  '<img src="assets/images/check.jpg" width="20px;" height="20px;"/>' : '<img src="assets/images/uncheck.jpg"  width="20px;" height="20px;"/>';
        $Verified_status_minor = ($Verified_status == "Minor Discrepancy") ? '<img src="assets/images/check.jpg" width="20px;" height="20px;"/>' : '<img src="assets/images/uncheck.jpg"  width="20px;" height="20px;"/>';
        $Verified_status_major = ($Verified_status == "Major Discrepancy") ? '<img src="assets/images/check.jpg" width="20px;" height="20px;"/>' :  '<img src="assets/images/uncheck.jpg"  width="20px;" height="20px;"/>';
         $pcc_tbl = '<table  cellpadding="4"  width="98%">
         <tr>
            <td bgcolor="#f2f2f2" colspan = "4" border = "1" style="text-align: center;" >PCC Check</td>  
        </tr>
        <tr>
            <td bgcolor="#f2f2f2" rowspan = "3" border = "1" width="24%">PCC Check</td>
            <td  border = "1" width="24%">Full Address</td>
            <td  border = "1" width="24%">Verification Date : '.$verification_date.' </td>
            <td border = "1" width="24.5%">'.$Verified_status_clear.' Green</td>
        </tr>
        <tr>
         <td  rowspan = "2" border = "1" width="24%">'.$full_address.'</td>
         <td  border = "1" width="24%">Verification Mode</td> 
         <td border = "1" width="24.5%">'.$Verified_status_minor.' Orange</td>

        </tr>

        
        <tr>
            <td  border = "1" width="24%"><img src="assets/images/uncheck.jpg"  width="20px;" height="20px;"/> Inperson  <img src="assets/images/check.jpg" width="20px;" height="20px;"/> Police  <img src="assets/images/check.jpg" width="20px;" height="20px;"/> Online</td>
            <td border = "1"  width="24.5%">'.$Verified_status_major.' Red</td>
        </tr>
    
        </table>';
         $pdf->writeHTML($pcc_tbl, true, false, false, false, '');
        
     }

    
    
    }

    if(!empty($education_info))  
    {

        $education_tbl = '<table  cellpadding="4"  width="98%">
         <tr>
            <td bgcolor="#f2f2f2" colspan = "6" border = "1" style="text-align: center;" >Credential & Education Check</td>  
        </tr>
        <tr>
            <td bgcolor="#f2f2f2" rowspan = "10" border = "1"  width="14.3%">Credential & Education Check</td>
            <td bgcolor="#f2f2f2" border = "1" width="16%">Level Of Education</td>
            <td bgcolor="#f2f2f2" border = "1" width="16%">Course</td>
            <td bgcolor="#f2f2f2" border = "1" width="16%">Specialization</td>
            <td bgcolor="#f2f2f2" border = "1"  width="16%">Verification Mode</td>
            <td bgcolor="#f2f2f2" border = "1"  width="16%">Status</td>
        </tr>';
    foreach ($education_info as $key => $value) 
    { 
        $res_mode_of_verification = ($value['res_mode_of_verification'] != " ") ?  $value['res_mode_of_verification'] : 'NA' ;

        $res_mode_of_verification_verbal = ($res_mode_of_verification == "verbal") ?  '<img src="assets/images/check.jpg" width="20px;" height="20px;"/>' : '<img src="assets/images/uncheck.jpg"  width="20px;" height="20px;"/>';
        $res_mode_of_verification_online = ($res_mode_of_verification == "online") ? '<img src="assets/images/check.jpg" width="20px;" height="20px;"/>' : '<img src="assets/images/uncheck.jpg"  width="20px;" height="20px;"/>';
        $Verified_status_clear = ($Verified_status == "Clear") ?  '<img src="assets/images/check.jpg" width="20px;" height="20px;"/>' : '<img src="assets/images/uncheck.jpg"  width="20px;" height="20px;"/>';


        $Verified_status = ($value['verfstatus'] != '') ? $value['verfstatus'] : "NA";

        $Verified_status_clear = ($Verified_status == "Clear") ?  '<img src="assets/images/check.jpg" width="20px;" height="20px;"/>' : '<img src="assets/images/uncheck.jpg"  width="20px;" height="20px;"/>';
        $Verified_status_minor = ($Verified_status == "Minor Discrepancy") ? '<img src="assets/images/check.jpg" width="20px;" height="20px;"/>' : '<img src="assets/images/uncheck.jpg"  width="20px;" height="20px;"/>';
        $Verified_status_major = ($Verified_status == "Major Discrepancy") ? '<img src="assets/images/check.jpg" width="20px;" height="20px;"/>' : '<img src="assets/images/uncheck.jpg"  width="20px;" height="20px;"/>';
      
      $education_tbl .= '<tr>
         <td   rowspan = "3" border = "1" width="16%">NA</td>
         <td   rowspan = "3" border = "1" width="16%">'.$value['university_board'].'</td> 
         <td  rowspan = "3" border = "1" width="16%">'.$value['qualification'].'</td> 
        
         <td  border = "1" width="16%">'.$res_mode_of_verification_verbal.' Inperson</td>
          <td  border = "1" width="16%">'.$Verified_status_clear.' Green</td>
        </tr>
 
        <tr>
            <td  border = "1" width="16%">'.$res_mode_of_verification_online.' Online</td>
            <td border = "1" width="16%">'.$Verified_status_minor.' Orange</td>
          
           
        </tr>
        <tr>
            <td  border = "1" width="16%">-</td>
            <td border = "1" width="16%">'.$Verified_status_major.' Red</td>
           
        
        </tr>';
    }
            
        
    $education_tbl .= '</table>';


        
    $pdf->writeHTML($education_tbl, true, false, false, false, '');
    }
    
     if(!empty($employment_info))  
    {
   
        $pdf->AddPage();


       $background_tbl = '<table  cellpadding="4" width="98%">
         <tr>
            <td bgcolor="#f2f2f2" colspan = "6" border = "1" style="text-align: center;">Background Verification</td>  
        </tr>
        <tr>
            <td bgcolor="#f2f2f2" border = "1" width="14.3%">Employer Name</td>
            <td bgcolor="#f2f2f2" border = "1" width="16%">Work Location</td>
            <td bgcolor="#f2f2f2" border = "1" width="16%">Date of joining (DD-MMM-YYYY)</td>
            <td bgcolor="#f2f2f2" border = "1" width="16%">Date of Exit (DD-MMM-YYYY)</td>
            <td bgcolor="#f2f2f2" border = "1" width="16%">Total Tenure (In Years)</td>
            <td bgcolor="#f2f2f2" border = "1" width="16%">Status</td>
        </tr>';
      foreach ($employment_info as $key => $value) 
    { 
        $Verified_status = ($value['verfstatus'] == "" || $value['verfstatus'] == 'NA') ? "NA" :  $value['verfstatus'];

        if((strpos($value['employed_from'],"-") == 4 || strpos($value['employed_from'],"-") == 2))
        {
            $employed_from = date('d-M-Y', strtotime($value['employed_from']));
        }
        else{
             $employed_from = $value['employed_from'];
        }
        if((strpos($value['employed_to'],"-") == 4 || strpos($value['employed_to'],"-") == 2))
        {
            $employed_to = date('d-M-Y', strtotime($value['employed_to']));
        }
        else{
            $employed_to = $value['employed_to'];
        }

        if((strpos($value['employed_from'],"-") == 4 || strpos($value['employed_from'],"-") == 2) &&  (strpos($value['employed_to'],"-") == 4 || strpos($value['employed_to'],"-") == 2))
        {
                       
            $date1 = $value['employed_from'];
            $date2 = $value['employed_to'];

            $diff = abs(strtotime($date2) - strtotime($date1));

            $years = floor($diff / (365*60*60*24));
            $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
            //$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

        }

        $Verified_status = ($value['verfstatus'] != '') ? $value['verfstatus'] : "NA";

        $Verified_status_clear = ($Verified_status == "Clear") ?  '<img src="assets/images/check.jpg" width="20px;" height="20px;"/>' : '<img src="assets/images/uncheck.jpg"  width="20px;" height="20px;"/>';;
        $Verified_status_minor = (($Verified_status == "Minor Discrepancy") || ($Verified_status == "Unable to verify")) ? '<img src="assets/images/check.jpg" width="20px;" height="20px;"/>' : '<img src="assets/images/uncheck.jpg"  width="20px;" height="20px;"/>';
        $Verified_status_details = (($Verified_status == "Unable to verify")) ? 'Unable to verify' : 'Orange';
        $Verified_status_major = ($Verified_status == "Major Discrepancy") ? '<img src="assets/images/check.jpg" width="20px;" height="20px;"/>' : '<img src="assets/images/uncheck.jpg"  width="20px;" height="20px;"/>';
         
        $Verified_status_na = ($Verified_status == "Not Applicable") ? '<img src="assets/images/check.jpg" width="20px;" height="20px;"/>' : '<img src="assets/images/uncheck.jpg"  width="20px;" height="20px;"/>';


        $citylocality = ($value['citylocality'] != '') ? ucwords($value["citylocality"]) : "NA";
        $citylocality = ($Verified_status == "Not Applicable") ? 'Not Applicable' :   $citylocality;
        $employed_from = ($Verified_status == "Not Applicable") ? 'Not Applicable' :   $employed_from;
        $employed_to = ($Verified_status == "Not Applicable") ? 'Not Applicable' :   $employed_to;
        $years_month = ($Verified_status == "Not Applicable") ? 'Not Applicable' :  $years.'.'.$months;
               
       $background_tbl .=  '<tr>
          <td  rowspan = "3" border = "1">'.ucwords($value["coname"]).'</td>
          <td  rowspan = "3" border = "1">'.$citylocality.'</td>
          <td  rowspan = "3" border = "1">'.$employed_from.'</td>
          <td  rowspan = "3" border = "1">'.$employed_to.'</td>
          <td  rowspan = "3" border = "1">'.$years_month.'</td>
          <td  border = "1">'.$Verified_status_clear.' Green</td>
          
        </tr>
         <tr>
            <td  border = "1">'. $Verified_status_minor.' '.$Verified_status_details.'</td>
           
        </tr>
        <tr>
            <td  border = "1">'.$Verified_status_major.' Red</td>
        
        </tr>  
        <tr>
            <td  border = "1">'.$Verified_status_na.' Not Applicable</td>
        
        </tr>';


          
    }
    $background_tbl .=  '</table>';
    $pdf->writeHTML($background_tbl, true, false, false, false, '');
  }      
    

        
       
           
            $pdf->Write(0, '*** DISCLAIMER ***', '', 0, 'C', true, 0, false, false, 0);

            $pdf->SetFont($this->fontname, '', 10);
        
         $end_of_file = '<p style="text-align:justify;">   All services and reports are provided in a professional manner in accordance with industry standards. Except as expressly provided, MIST IT Services Private Ltd and its affiliates make no and disclaim any and all warranties and representations with respect to the services provided herein, whether such warranties and representations are express or implied in fact or by operation of law or otherwise, including without limitation implied warranties of merchantability and fitness for a particular purpose and implied warranties arising from the course of dealing or a course of performance with respect to the accuracy, validity, or completeness of any service or report.
Furthermore, MIST IT Services Private Ltd and its affiliates expressly disclaim that the services will meet the clients requirements and MIST IT Services Private Ltd and its affiliates express disclaim all such representations and warranties. MIST IT Services Private Ltd merely passes on to client information in the form of reports which MIST IT Services Private Ltd has obtained in respect of the subject of a background screening verification. MIST IT Services Private Ltd is not the author or creator of that information. Given the limitations, no responsibility will be taken by MIST IT Services Private Ltd for the consequences of client in reliance upon information contained in this information or report. However, MIST IT Services Private Ltd will provide reasonable procedures to protect against any false information being provided to the client. The information within the reports is strictly confidential and is intended to be for the sole purpose of the clients evaluation. The information and the reports are not intended for public dissemination.
Any questions or clarifications with respect to the report can be addressed to MIST IT Services Private Ltd.
 </b></p>';

        $pdf->writeHTMLCell(0, 8, '', '', $end_of_file, 0, 0, false, true, 10, false);

        $image_signature_file = 'assets/images/seal.jpg';
        $pdf->Image($image_signature_file, 5, 230, 40, '', 'jpeg', '', 'B', false, 300, 'C', false, false, 0, false, false, false);
      

     
        ob_start();


        $filename = SITE_BASE_PATH . CANDIDATES . ucwords($cand_info['ClientRefNumber']).'_'.ucwords($cand_info['CandidateName']).'_Report'.'.pdf';

        $pdf->Output($filename, 'F');
		
		//$pdf->Output(SITE_BASE_PATH.UPLOAD_FOLDER.candidate_report_file.$reportstatus.$cand_info['CandidateName'].'.pdf', 'F');
		

	}

   /* public function fine_title($title) {

        if($title == "female")
        {
          $return_title = "Miss";
        }
        elseif($title == "male")
        {
           $return_title = "Mr"; 
        }
        return $return_title;
    }*/

}
 