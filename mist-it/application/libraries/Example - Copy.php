<?php
require_once dirname(__FILE__).'/tcpdf_min/include/tcpdf_include.php';

// Extend the TCPDF class to create custom Header and Footer
class Example extends TCPDF {

	//Page header
	var $fontname = '';
    
	function __construct()
	{

		parent::__construct();

		$this->fontname = TCPDF_FONTS::addTTFfont(APPPATH.'libraries\tcpdf_min\fonts\arial.ttf', 'TrueTypeUnicode', '', 96);
	}

	public function Header() {
	
		$this->SetProtection(array('copy','modify'), null, null, 0, null);
		// Logo

		if(CLIENT_LOGOS != '')
		{
			 // $this->Image(CLIENT_LOGOS, 5, 5, 30, '', false, '', 'T', false, 300, 'L', false, false, 0, false, false, false);
			 // if(getimagesize(CLIENT_LOGOS)[0] > 300)
		  //    {
			 // 	$this->Image(CLIENT_LOGOS, 150, 5, 20, '', false, '', 'T', false, 300, 'L', false, false, 0, false, false, false);
			 // }
			 // else
			 // {
			 // 	$this->Image(CLIENT_LOGOS, 5, 5, 40, '', false, '', 'T', false, 300, 'L', false, false, 0, false, false, false);
			 // }
		}
       
		
                   
		$image_file =  'assets/images/demo.jpg';
         

		//$this->Image($image_file, 5, 7, 25, '', 'jpg', '', 'T', false, 300, 'R', false, false, 0, false, false, false);
		
		$this->SetFont($this->fontname, 'B', 20); // Title
		
		$this->Line(5, 5, $this->getPageWidth()-5, 5);
		$this->Line(5.8, 5.8, $this->getPageWidth()-5.8, 5.8);

		$this->Line($this->getPageWidth()-5, 5, $this->getPageWidth()-5,  $this->getPageHeight()-5);
		$this->Line($this->getPageWidth()-5.8, 5.8, $this->getPageWidth()-5.8,  $this->getPageHeight()-5.8);

		$this->Line(5, $this->getPageHeight()-5, $this->getPageWidth()-5, $this->getPageHeight()-5);
		$this->Line(5, $this->getPageHeight()-5.8, $this->getPageWidth()-5.8, $this->getPageHeight()-5.8);

		$this->Line(5, 5, 5, $this->getPageHeight()-5);
		$this->Line(5.8, 5.8, 5.8, $this->getPageHeight()-5.8);
	}	

	// Page footer
	public function Footer() {
		// Position at 15 mm from bottom
		$this->SetY(-15);
		// Set font
		$this->SetFont($this->fontname, 'I', 8);
		// Page number
		$this->Cell(0, 10, 'Confidential', 0, false, 'C', 0, '', 0, false, 'T', 'M');

		$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
	}

	public function generate_pdf($reports_data,$portal_type)
	{
 
	   set_time_limit(0);
		$cand_info = $reports_data['cand_info'];

		$education_info = $reports_data['education_info'];
	
		$employment_info = $reports_data['employment_info'];

		$address_info = $reports_data['address_info'];

		$pcc_info = $reports_data['pcc_info'];

		$court_info = $reports_data['court_info'];

		$references_info = $reports_data['references_info'];

		$global_db_info = $reports_data['global_db_info'];

		$identity_info = $reports_data['identity_info'];

		$credit_report_info = $reports_data['credit_report_info'];

		$drugs_info = $reports_data['drugs_info'];


		$pdf = new Example(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		$pdf->SetCreator(PDF_CREATOR);

		$pdf->SetAuthor(CRMNAME);
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
       $pdf->setCellMargins(1, 0, 1, 0);

		$pdf->SetFont($this->fontname,'B',14);   

		$pdf->SetFillColor(237,237,237);
		
		$overallstatus = status_color($cand_info["overallstatus_value"]);

		$reportstatus = ($cand_info["overallstatus"] == '1' || $cand_info["overallstatus"] == '2' ||  $cand_info["overallstatus"] == '5') ? "INTERIM" : "FINAL";


		
       
	//	$this->Image($client_image_file, 5, 7, 25, '', 'jpg', '', 'T', false, 300, 'L', false, false, 0, false, false, false);

		//$pdf->Cell(0, 11, strtoupper($cand_info["clientname"]),1, 1, 'C', 1, 0);

	//	$pdf->Cell(0, 11, "BACKGROUND VERIFICATION $reportstatus REPORT","LTRB EXT", 1, 'C', 1, 0);

   $client_name = '<style> table, td{ border: border: 1.3px solid black; }</style><table width="98%" cellpadding="5" cellspacing="pixels" cellpadding="5">';
      
   $client_name .='<tr>';
   $client_name .= '<td bgcolor="#ccc" style="text-align: center;height: 35px;" ><b>'.strtoupper($cand_info["clientname"]).'</b></td>';
   $client_name .= '</tr>';    
   $client_name .= '</table>';

    $report_status = '<style> table, td{ border: border: 1.3px solid black; }</style><table width="98%" cellpadding="5" cellspacing="pixels" cellpadding="5">';
      
   $report_status .= '<tr>';
   $report_status .= '<td bgcolor="#ccc" style="text-align: center;height: 35px;" ><b>BACKGROUND VERIFICATION '.$reportstatus. ' REPORT</b></td>';
   $report_status .= '</tr>';    
   $report_status .= '</table>';

   $pdf->writeHTML($client_name, true, false, false, false, '');

   $pdf->writeHTML($report_status, true, false, false, false, '');

		$pdf->SetFont($this->fontname, '', 11);

		$dob = ($cand_info["DateofBirth"] != '') ? $cand_info["DateofBirth"] : 'NA';
		$overallclosuredate = ($reportstatus == 'FINAL') ? date('d-M-Y', strtotime($cand_info["overallclosuredate"])) : 'NA';
		$cans_tbl = '<style> table, td{ border: border: 1.3px solid black; }</style> <table width="97%" cellpadding="5">
		<tr>
			<td bgcolor="#ccc"  style="text-align: center;"><b>NAME OF CANDIDATE</b></td>
			<td colspan="3"> '.ucwords($cand_info["CandidateName"]).' </td>
		</tr>
		<tr>
			<td bgcolor="#ccc" style="text-align: center;"><b>GENDER</b></td>
			<td> '.ucwords($cand_info["gender"]).'</td>
			<td bgcolor="#ccc" style="text-align: center;">DATE OF BIRTH</td>
			<td width="140px"> '.date('d-M-Y', strtotime($dob)).'</td>
		</tr>
		<tr>
			<td bgcolor="#ccc" style="text-align: center;"><b>DATE INITIATED</b></td>
			<td> '.date('d-M-Y', strtotime($cand_info["caserecddate"])).'</td>
			<td bgcolor="#ccc" style="text-align: center;"><b>DATE OF COMPLETION</b></td>
			<td width="140px"> '.$overallclosuredate.'</td>
		</tr>
		<tr>
			<td bgcolor="#ccc" style="text-align: center;"><b>'.REFNO.'</b></td>
			<td>'.$cand_info["cmp_ref_no"].'</td>
			<td bgcolor="#ccc" style="text-align: center;"><b>CLIENT REF NO</b></td>
			<td width="140px"> '.$cand_info["ClientRefNumber"].'</td>
		</tr>
		
		<tr>
			<td bgcolor="#ccc" style="text-align: center;">ENTITY / PACKAGE</td>
			<td>'.$cand_info["entity_name"].'/'.$cand_info["package_name"].'</td>
			<td bgcolor="#ccc" style="text-align: center;"><b>CASE STATUS</b></td>';

            if($cand_info['overallstatus_value'] == "Major Discrepancy")
            {
              $cans_tbl .= '<td bgcolor= "#ff2300">'.$cand_info["overallstatus_value"].'</td>';
            }
            elseif($cand_info['overallstatus_value'] == "Minor Discrepancy")
            {

               $cans_tbl .= '<td bgcolor= "#ff9933">'.$cand_info["overallstatus_value"].'</td>';
                 
            }
            elseif($cand_info['overallstatus_value'] == "Unable to verify")
            {
               $cans_tbl .= '<td bgcolor= "#ffff00">'.$cand_info["overallstatus_value"].'</td>';
              
            }
             elseif($cand_info['overallstatus_value'] == "Clear")
            {
              $cans_tbl .= '<td bgcolor= "#4c9900">'.$cand_info["overallstatus_value"].'</td>';
                
            }
            else
            {
              $cans_tbl .= '<td>'.$cand_info["overallstatus_value"].'</td>';
            
            }
                
			
		$cans_tbl .='</tr>
		</table>';
        
		$pdf->writeHTML($cans_tbl, true, false, false, false, '');

		$x = 25;
		$y = '';
		$w = 155;
		$h = 170;
		

		    $class_tbl = '<style> table, td{ border: 1.3px solid black; }</style><table width="98%" cellpadding="5" >
              <tr>
                <td colspan="4" bgcolor="#ccc" style="text-align: center;height: 35px;"><b> CLASSIFICATION OF REPORT STATUS</b></td>
              </tr>
              <tr style="text-align: center;">
                <td bgcolor= "#ff2300" width="30%"><b>MAJOR DISCREPANCY</b></td>
                <td bgcolor= "#ff9933" width="30%"><b>MINOR DISCREPANCY</b></td>
                <td bgcolor= "#ffff00" width="24%"><b>UNABLE TO VERIFY</b></td>
                <td bgcolor= "#4c9900" width="12.5%"><b>Clear</b></td>
              </tr>
            </table>';


            $pdf->writeHTML($class_tbl, true, false, false, false, '');
       
          //  $pdf->writeHTML($cans_tbl, true, false, false, false, '');

           // $pdf->writeHTML($cans_tbl, true, false, false, false, '');

		//	$pdf->Cell(0, 8, 'EXECUTIVE SUMMARY', 1, 1, 'C');
       $executive_summary = '<style> table, td{ border: border: 1.3px solid black; }</style><table width="98%" cellpadding="5" >';
       $executive_summary .= '<tr>';
       $executive_summary .= '<td bgcolor="#ccc" style="text-align: center;height: 35px;" ><b>EXECUTIVE SUMMARY</b></td>';
       $executive_summary .= '</tr>';    
       $executive_summary .= '</table>';

      $pdf->writeHTML($executive_summary, true, false, false, false, '');

			$educ_tbl = '<style> table { margin-top: 15px; } table, td{ border: 1.3px solid black; padding-left: 5px; }</style><table width="96%" border="1" cellpadding="5">

			<tr>
				<td  bgcolor="#ccc" width="180px" style="text-align: center;"> TYPE OF CHECK</td>
				<td  bgcolor="#ccc" width="285px" style="text-align: center;"> BRIEF DETAILS</td>
				<td bgcolor="#ccc" width="150px" style="text-align: center;"> STATUS</td>
			</tr>';

			  $component_name  = json_decode($cand_info['component_name'],true);

              $component_name = array_map('strtoupper', $component_name);


    
        
			foreach ($employment_info as $key => $value) 
			{	
				$value = array_map('ucwords', $value);
                $counter = ($key == 0) ? ''  : $key+1;


			    $educ_tbl .= '<tr><td bgcolor="#ccc" style="text-align: center;"><b>'.$component_name["empver"].$counter.'</b></td>';
                $educ_tbl .= '<td>'.$value["coname"].'</td>';

                if($value['verfstatus'] == "Major Discrepancy")
                {
                $educ_tbl .= '<td bgcolor="#ff2300">'.$value["verfstatus"].'</td></tr>';
                }
                elseif($value['verfstatus'] == "Minor Discrepancy")
                {
                $educ_tbl .= '<td bgcolor="#ff9933">'.$value["verfstatus"].'</td></tr>';
                }
                elseif($value['verfstatus'] == "Unable To Verify")
                {
                 $educ_tbl .='<td bgcolor="#ffff00">'.$value["verfstatus"].'</td></tr>';
                }
                elseif($value['verfstatus'] == "Clear")
                {
                  $educ_tbl .= '<td bgcolor="#4c9900">'.$value["verfstatus"].'</td></tr>';
                }
                else
                { 
                $educ_tbl .= '<td bgcolor="#ccc">'.$value["verfstatus"].'</td></tr>';
                }


			}
 
 
			foreach ($address_info as $key => $value) 
			{	
				
				 $full_address = $value['address'].' '.$value['city'].' '.$value['pincode'].' '.$value['address_state'];
                $value = array_map('ucwords', $value);
                $counter = ($key == 0) ? ''  : $key+1;
                $educ_tbl .='<tr><td bgcolor="#ccc" style="text-align: center;"><b>'.$component_name["addrver"].$counter.'</b></td>';
                $educ_tbl .= '<td>'.$full_address.'</td>';
                if($value['verfstatus'] == "Major Discrepancy")
                {
                 $educ_tbl .= '<td bgcolor="#ff2300">'.$value["verfstatus"].'</td></tr>';
                }
                elseif($value['verfstatus'] == "Minor Discrepancy")
                {
                 $educ_tbl .= '<td bgcolor="#ff9933">'.$value["verfstatus"].'</td></tr>';
                }
                elseif($value['verfstatus'] == "Unable To Verify")
                {
                 $educ_tbl .='<td bgcolor="#ffff00">'.$value["verfstatus"].'</td></tr>';
                }
                elseif($value['verfstatus'] == "Clear")
                {
                 $educ_tbl .= '<td bgcolor="#4c9900">'.$value["verfstatus"].'</td></tr>';
                }
                else
                { 
                 $educ_tbl .= '<td bgcolor="#ccc">'.$value["verfstatus"].'</td></tr>';
                }
			} 

   
			foreach ($education_info as $key => $value) 
			{	
				$value = array_map('ucwords', $value);
                $counter = ($key == 0) ? ''  : $key+1;
                $educ_tbl .= '<tr><td bgcolor="#ccc" style="text-align: center;"><b>'.$component_name["eduver"].$counter.'</b></td>';
                $educ_tbl .= '<td>'.$value['qualification'].'</td>';
                if($value['verfstatus'] == "Major Discrepancy")
                {
                 $educ_tbl .= '<td bgcolor="#ff2300">'.$value["verfstatus"].'</td></tr>';
                }
                elseif($value['verfstatus'] == "Minor Discrepancy")
                {
                 $educ_tbl .= '<td bgcolor="#ff9933">'.$value["verfstatus"].'</td></tr>';
                }
                elseif($value['verfstatus'] == "Unable To Verify")
                {
                 $educ_tbl .='<td bgcolor="#ffff00">'.$value["verfstatus"].'</td></tr>';
                }
                elseif($value['verfstatus'] == "Clear")
                {
                 $educ_tbl .= '<td bgcolor="#4c9900">'.$value["verfstatus"].'</td></tr>';
                }
                else
                { 
                 $educ_tbl .= '<td bgcolor="#ccc">'.$value["verfstatus"].'</td></tr>';
                }
			}
	

			foreach ($references_info  as $key => $value)  {
                $value = array_map('ucwords', $value);
                $counter = ($key == 0) ? ''  : $key+1;
                $educ_tbl .= '<tr><td bgcolor="#ccc" style="text-align: center;"><b>'.$component_name["refver"].$counter.'</b></td>';
                $educ_tbl .= '<td>'.$value['name_of_reference'].' </td>';
                if($value['verfstatus'] == "Major Discrepancy")
                {
                 $educ_tbl .= '<td bgcolor="#ff2300">'.$value["verfstatus"].'</td></tr>';
                }
                elseif($value['verfstatus'] == "Minor Discrepancy")
                {
                 $educ_tbl .= '<td bgcolor="#ff9933">'.$value["verfstatus"].'</td></tr>';
                }
                elseif($value['verfstatus'] == "Unable To Verify")
                {
                 $educ_tbl .='<td bgcolor="#ffff00">'.$value["verfstatus"].'</td></tr>';
                }
                elseif($value['verfstatus'] == "Clear")
                {
                 $educ_tbl .= '<td bgcolor="#4c9900">'.$value["verfstatus"].'</td></tr>';
                }
                else
                { 
                 $educ_tbl .= '<td bgcolor="#ccc">'.$value["verfstatus"].'</td></tr>';
                }
              }
              
              foreach ($court_info  as $key => $value)  {
                $value = array_map('ucwords', $value);
                $counter = ($key == 0) ? ''  : $key+1;
                $full_address = $value['street_address'].' '.$value['city'].' '.$value['pincode'].' '.$value['state'];

                $educ_tbl .= '<tr><td bgcolor="#ccc" style="text-align: center;"><b>'.$component_name["courtver"].$counter.'</b></td>';
                $educ_tbl .= '<td>'.$full_address.'</td>';
                if($value['verfstatus'] == "Major Discrepancy")
                {
                 $educ_tbl .= '<td bgcolor="#ff2300">'.$value["verfstatus"].'</td></tr>';
                }
                elseif($value['verfstatus'] == "Minor Discrepancy")
                {
                 $educ_tbl .= '<td bgcolor="#ff9933">'.$value["verfstatus"].'</td></tr>';
                }
                elseif($value['verfstatus'] == "Unable To Verify")
                {
                 $educ_tbl .='<td bgcolor="#ffff00">'.$value["verfstatus"].'</td></tr>';
                }
                elseif($value['verfstatus'] == "Clear")
                {
                 $educ_tbl .= '<td bgcolor="#4c9900">'.$value["verfstatus"].'</td></tr>';
                }
                else
                { 
                 $educ_tbl .= '<td bgcolor="#ccc">'.$value["verfstatus"].'</td></tr>';
                }
              }

          		
	   
              foreach ($global_db_info  as $key => $value)  {
                $value = array_map('ucwords', $value);
                $counter = ($key == 0) ? ''  : $key+1;
                $full_address = $value['street_address'].' '.$value['city'].' '.$value['pincode'].' '.$value['state'];

                $educ_tbl .='<tr><td bgcolor="#ccc" style="text-align: center;"><b>'.$component_name["globdbver"].$counter.'</b></td>';
                $educ_tbl .='<td>'.$full_address.'</td>';
                if($value['verfstatus'] == "Major Discrepancy")
                {
                 $educ_tbl .= '<td bgcolor="#ff2300">'.$value["verfstatus"].'</td></tr>';
                }
                elseif($value['verfstatus'] == "Minor Discrepancy")
                {
                 $educ_tbl .= '<td bgcolor="#ff9933">'.$value["verfstatus"].'</td></tr>';
                }
                elseif($value['verfstatus'] == "Unable To Verify")
                {
                 $educ_tbl .='<td bgcolor="#ffff00">'.$value["verfstatus"].'</td></tr>';
                }
                elseif($value['verfstatus'] == "Clear")
                {
                 $educ_tbl .= '<td bgcolor="#4c9900">'.$value["verfstatus"].'</td></tr>';
                }
                else
                { 
                 $educ_tbl .= '<td bgcolor="#ccc">'.$value["verfstatus"].'</td></tr>';
                }
              }
		
              foreach ($drugs_info as $key => $value)  {
                $value = array_map('ucwords', $value);
                $counter = ($key == 0) ? ''  : $key+1;
                $educ_tbl .='<tr><td bgcolor="#ccc" style="text-align: center;"><b>'.$component_name["narcver"].$counter.'</b></td>';
                $educ_tbl .='<td>'.$value['drug_test_code'].'</td>';
                if($value['verfstatus'] == "Major Discrepancy")
                {
                 $educ_tbl .= '<td bgcolor="#ff2300">'.$value["verfstatus"].'</td></tr>';
                }
                elseif($value['verfstatus'] == "Minor Discrepancy")
                {
                 $educ_tbl .= '<td bgcolor="#ff9933">'.$value["verfstatus"].'</td></tr>';
                }
                elseif($value['verfstatus'] == "Unable To Verify")
                {
                 $educ_tbl .='<td bgcolor="#ffff00">'.$value["verfstatus"].'</td></tr>';
                }
                elseif($value['verfstatus'] == "Clear")
                {
                 $educ_tbl .= '<td bgcolor="#4c9900">'.$value["verfstatus"].'</td></tr>';
                }
                else
                { 
                 $educ_tbl .= '<td bgcolor="#ccc">'.$value["verfstatus"].'</td></tr>';
                }
              }
          
              foreach ($pcc_info as $key => $value)  {
                $value = array_map('ucwords', $value);
                $counter = ($key == 0) ? ''  : $key+1;
                $full_address = $value['street_address'].' '.$value['city'].' '.$value['state'].' '.$value['pincode'];
                $educ_tbl .='<tr><td bgcolor="#ccc" style="text-align: center;"><b>'.$component_name["crimver"].$counter.'</b></td>';
                $educ_tbl .= '<td>'.$full_address.'</td>';
                if($value['verfstatus'] == "Major Discrepancy")
                {
                 $educ_tbl .= '<td bgcolor="#ff2300">'.$value["verfstatus"].'</td></tr>';
                }
                elseif($value['verfstatus'] == "Minor Discrepancy")
                {
                 $educ_tbl .= '<td bgcolor="#ff9933">'.$value["verfstatus"].'</td></tr>';
                }
                elseif($value['verfstatus'] == "Unable To Verify")
                {
                 $educ_tbl .='<td bgcolor="#ffff00">'.$value["verfstatus"].'</td></tr>';
                }
                elseif($value['verfstatus'] == "Clear")
                {
                 $educ_tbl .= '<td bgcolor="#4c9900">'.$value["verfstatus"].'</td></tr>';
                }
                else
                { 
                 $educ_tbl .= '<td bgcolor="#ccc">'.$value["verfstatus"].'</td></tr>';
                }
              }


              foreach ($identity_info as $key => $value)  {
                $value = array_map('ucwords', $value);
                $counter = ($key == 0) ? ''  : $key+1;
                $educ_tbl .= '<tr><td bgcolor="#ccc" style="text-align: center;"><b>'.$component_name["identity"].$counter.'</b></td>';
                $educ_tbl .= '<td>'.$value['doc_submited'].'</td>';
                 if($value['verfstatus'] == "Major Discrepancy")
                {
                 $educ_tbl .= '<td bgcolor="#ff2300">'.$value["verfstatus"].'</td></tr>';
                }
                elseif($value['verfstatus'] == "Minor Discrepancy")
                {
                 $educ_tbl .= '<td bgcolor="#ff9933">'.$value["verfstatus"].'</td></tr>';
                }
                elseif($value['verfstatus'] == "Unable To Verify")
                {
                 $educ_tbl .='<td bgcolor="#ffff00">'.$value["verfstatus"].'</td></tr>';
                }
                elseif($value['verfstatus'] == "Clear")
                {
                 $educ_tbl .= '<td bgcolor="#4c9900">'.$value["verfstatus"].'</td></tr>';
                }
                else
                { 
                 $educ_tbl .= '<td bgcolor="#ccc">'.$value["verfstatus"].'</td></tr>';
                }
              }
             

             foreach ($credit_report_info as $key => $value)  {
                $value = array_map('ucwords', $value);
                $counter = ($key == 0) ? ''  : $key+1;
                $educ_tbl .= '<tr><td bgcolor="#ccc" style="text-align: center;"><b>'.$component_name["cbrver"].$counter.'</b></td>';
                $educ_tbl .= '<td>'.$value['doc_submited'].'</td>';
                 if($value['verfstatus'] == "Major Discrepancy")
                {
                 $educ_tbl .= '<td bgcolor="#ff2300">'.$value["verfstatus"].'</td></tr>';
                }
                elseif($value['verfstatus'] == "Minor Discrepancy")
                {
                 $educ_tbl .= '<td bgcolor="#ff9933">'.$value["verfstatus"].'</td></tr>';
                }
                elseif($value['verfstatus'] == "Unable To Verify")
                {
                 $educ_tbl .='<td bgcolor="#ffff00">'.$value["verfstatus"].'</td></tr>';
                }
                elseif($value['verfstatus'] == "Clear")
                {
                 $educ_tbl .= '<td bgcolor="#4c9900">'.$value["verfstatus"].'</td></tr>';
                }
                else
                { 
                 $educ_tbl .= '<td bgcolor="#ccc">'.$value["verfstatus"].'</td></tr>';
                }
              }
        
           
          

			$educ_tbl .='</table>';

			$pdf->writeHTML($educ_tbl, true, false, false, false, '');

			$pdf->SetFillColor(237,237,237);


    
           foreach ($employment_info as $key => $value) 
			{	
				$pdf->AddPage();

				$counter = ($key == 0) ? ''  : $key+1;

			//	$pdf->Cell(0, 11, "EMPLOYMENT VERIFICATION - ".$key ,1, 1, 'C', 1, 0);

			   $Verified_status = ($value['verfstatus'] == "" || $value['verfstatus'] == "WIP" || $value['verfstatus'] == "Insufficiency" || $value['verfstatus'] == "Stop Check" || $value['verfstatus'] == 'Unable to verify' || $value['verfstatus'] == 'Overseas check' || $value['verfstatus'] == 'YTR' || str_replace(" ",'', $value['verfstatus']) == "Workedwiththesameorganization") ? "NA" : "Verified";

                $value = array_map('ucwords', $value);

				$empid =  ($value['empid'] == "" || $value['empid'] == "please provide" || $value['empid'] == "Please Provide") ? 'Not Provided' :$value['empid'];
                $designation =  ($value['designation'] == "" || $value['designation'] == "please provide" || $value['designation'] == "Please Provide") ? 'Not Provided' :$value['designation'];
                $remuneration =  ($value['remuneration'] == "" || $value['remuneration'] == "please provide" || $value['remuneration'] == "Please Provide") ? 'Not Provided' :$value['remuneration'];
                $reasonforleaving =  ($value['reasonforleaving'] == "" || $value['reasonforleaving'] == "please provide" || $value['reasonforleaving'] == "Please Provide") ? 'Not Provided' :$value['reasonforleaving'];
                $r_manager_name =  ($value['r_manager_name'] == "" || $value['r_manager_name'] == "please provide" || $value['r_manager_name'] == "Please Provide") ? 'Not Provided' : ucwords($value['r_manager_name']);

                $reportingmanager = ($value['reportingmanager'] == "") ? 'Not Disclosed' :$value['reportingmanager'];
                $reportingmanager = ($Verified_status == "NA") ? 'NA' :  $reportingmanager;
                
                $period_of_stay = $value['empfrom'].' to '.$value['empto'];
                if((strpos($value['empfrom'],"-") == 4 || strpos($value['empfrom'],"-") == 2) &&  (strpos($value['empto'],"-") == 4 || strpos($value['empto'],"-") == 2))
				{
				   
				   
                   $period_of_stay1 = date('d-M-Y', strtotime($value['empfrom'])).' to '.date('d-M-Y', strtotime($value['empto']));
				}
				else
				{

					$period_of_stay1 = $value['empfrom'].' to '.$value['empto'];
				  
				}
                $res_period_of_stay = $value['employed_from'].' to '.$value['employed_to'];
                 if((strpos($value['employed_from'],"-") == 4 || strpos($value['employed_from'],"-") == 2) &&  (strpos($value['employed_to'],"-") == 4 || strpos($value['employed_to'],"-") == 2))
				{
				   
				   
                   $res_period_of_stay1 = date('d-M-Y', strtotime($value['employed_from'])).' to '.date('d-M-Y', strtotime($value['employed_to']));
				}
				else
				{

					$res_period_of_stay1 = $value['employed_from'].' to '.$value['employed_to'];
				  
				} 
                         
                $verified = ($period_of_stay == $res_period_of_stay) ? 'Verified' : $res_period_of_stay1;
                
                $verified_company = ($value['coname'] == $value['res_coname']) ? 'Verified' : $value['res_coname'];
                $verified_company = ($Verified_status == "NA") ? 'NA' : ucwords($verified_company);
                $res_empid = ($Verified_status == "NA") ? 'NA' : $value['res_empid'];


                $verified = ($period_of_stay == $res_period_of_stay) ? 'Verified' : $res_period_of_stay1;
                $verified = ($Verified_status == "NA") ? 'NA' : $verified;

                $emp_designation = ($Verified_status == "NA") ? 'NA' : $value['emp_designation'];

                $res_remuneration = ($Verified_status == "NA") ? 'NA' : $value['res_remuneration'];
                $res_reasonforleaving = ($Verified_status == "NA") ? 'NA' : $value['res_reasonforleaving'];
                $info_integrity_disciplinary_issue = ($Verified_status == "NA") ? 'NA' :  $value['info_integrity_disciplinary_issue'];
                $info_exitformalities = ($Verified_status == "NA") ? 'NA' :  $value['info_exitformalities'];

                $mcaregn = ($Verified_status == "NA") ? 'NA' :  $value['mcaregn'];
                $domainname = ($value['domainname'] == "Na.na.com") ? 'NA' :  $value['domainname'];
                $domainname = ($Verified_status == "NA") ? 'NA' :  $domainname;
                $justdialwebcheck = ($Verified_status == "NA") ? 'NA' :  $value['justdialwebcheck'];
                $fmlyowned = ($Verified_status == "NA") ? 'NA' :  $value['fmlyowned'];

                $verifiers_details =  $value['verfname']."/".$value['verifiers_role']."/".$value['verfdesgn'];
                $verifiers_details = ($Verified_status == "NA") ? 'NA' :  $verifiers_details;

                $modeofverification = ($value['modeofverification'] == "") ? 'NA' :  $value['modeofverification'];
                $modeofverification = ($Verified_status == "NA") ? 'NA' :  $modeofverification;

                $res_remarks = ($value['res_remarks'] == "") ? 'NA' :  $value['res_remarks'];
              
                if($value['verfstatus'] == "Insufficiency")
		        {
		            $res_remarks = $value['res_remarks']; 
		        }
		        else
		        {
		            $res_remarks = ($Verified_status == "NA") ? $res_remarks :  $res_remarks;
		        }

                $emp_name = '<style> table, td{ border: 1.3px solid black; }</style><table width="98%" cellpadding="5" cellspacing="pixels">';
               
                $emp_name .='<tr>';
                $emp_name .= '<td bgcolor="#ccc" style="text-align: center;height: 35px;" ><b>'.$component_name["empver"].' VERIFICATION '.$counter.'</b></td>';
                $emp_name .= '</tr>';    
                $emp_name .= '</table>';

                $pdf->writeHTML($emp_name, true, false, false, false, '');


                $emp_full_info = '<style> table, td{ border: 1.3px solid black; }</style><table width="96%" cellpadding="5" cellspacing="pixels" >';
                $emp_full_info .= '<tr>';
                $emp_full_info .= '<td bgcolor="#ccc" style="text-align: center;"><b> COMPONENTS</b></td>';
                $emp_full_info .= '<td bgcolor="#ccc" style="text-align: center;"><b> INFORMATION PROVIDED</b></td>';
                $emp_full_info .= '<td bgcolor="#ccc" style="text-align: center;"><b> INFORMATION VERIFIED</b></td>';
                $emp_full_info .= '</tr>'; 
                $emp_full_info .= '<tr>';
                 
                $emp_full_info .=  '<td bgcolor="#ccc" style="text-align: center;"><b>NAME OF THE COMPANY</b></td>';
                $emp_full_info .=  '<td>'.$value["coname"].'</td>';
                $emp_full_info .=  '<td>'.$verified_company.'</td>';
                $emp_full_info .=  '</tr>';
                $emp_full_info .=  '<tr>';
                $emp_full_info .=  '<td bgcolor="#ccc" style="text-align: center;"><b>EMPLOYEE ID</b></td>';
                $emp_full_info .=  '<td>'.$empid.'</td>';
                $emp_full_info .=  '<td>'.$res_empid.'</td>';
                $emp_full_info .=  '</tr>';
                $emp_full_info .=  '<tr>';
                $emp_full_info .=  '<td bgcolor="#ccc" style="text-align: center;"><b>PERIOD OF EMPLOYMENT</b></td>';
                $emp_full_info .=  '<td>'.$period_of_stay1.'</td>';
                $emp_full_info .=  '<td>'.$verified.'</td>';
                $emp_full_info .=  '</tr>';
                $emp_full_info .=  '<tr>';
                $emp_full_info .=  '<td bgcolor="#ccc" style="text-align: center;"><b>DESIGNATION</b></td>';
                $emp_full_info .=  '<td>'.$designation.'</td>';
                $emp_full_info .=  '<td>'.$emp_designation.'</td>';
                $emp_full_info .=  '</tr>';
                $emp_full_info .=  '<tr>';
                $emp_full_info .=  '<td bgcolor="#ccc" style="text-align: center;"><b>REMUNERATION</b></td>';
                $emp_full_info .=  '<td>'.$remuneration.'</td>';
                $emp_full_info .=  '<td>'.$res_remuneration.'</td>';
                $emp_full_info .=  '</tr>';
                $emp_full_info .=  '<tr>';
                $emp_full_info .=  '<td bgcolor="#ccc" style="text-align: center;"><b>REPORTING MANAGER</b></td>';
                $emp_full_info .=  '<td>'.$r_manager_name.'</td>';
                $emp_full_info .=  '<td>'.$reportingmanager.'</td>';
                $emp_full_info .=  '</tr>';
                $emp_full_info .=  '<tr>';
                $emp_full_info .=  '<td bgcolor="#ccc" style="text-align: center;"><b>REASON OF LEAVING</b></td>';
                $emp_full_info .=  '<td>'.$reasonforleaving.'</td>';
                $emp_full_info .=  '<td>'.$res_reasonforleaving.'</td>';
                $emp_full_info .=  '</tr>';
                $emp_full_info .=  '</table>';

                $pdf->writeHTML($emp_full_info, true, false, false, false, '');

                $emp_full_details =  '<style> table, td{ border: 1.3px solid black; }</style><table width="97%" cellpadding="5" cellspacing="pixels" >';
                $emp_full_details .=  '<tr>';
                $emp_full_details .=  '<td bgcolor="#ccc" style="text-align: center;"><b> DETAILS</b></td>';
                $emp_full_details .=  '<td bgcolor="#ccc" style="text-align: center;"><b> FINDINGS</b></td>';
                $emp_full_details .=  '</tr>'; 
                $emp_full_details .=  '<tr>';
                $emp_full_details .=  '<td bgcolor="#ccc" style="text-align: center;"><b>ANY INTEGRITY/DISCIPLINARY ISSUES</b></td>';
                $emp_full_details .=  '<td>'.$info_integrity_disciplinary_issue.'</td>';
                $emp_full_details .=  '</tr>';
                $emp_full_details .=  '<tr>';
                $emp_full_details .=  '<td bgcolor="#ccc" style="text-align: center;"><b>EXIT FORMALITIES COMPLETED</b></td>';
                $emp_full_details .=  '<td>'.$info_exitformalities.'</td>';
                $emp_full_details .=  '</tr>';
            
                $emp_full_details .=  '<tr>';
                $emp_full_details .=  '<td bgcolor="#ccc" style="text-align: center;"><b>THE COMPANY IS REGISTERED  IN  MCA?</b></td>';
                $emp_full_details .=  '<td>'.$mcaregn.'</td>';
                $emp_full_details .=  '</tr>';
                $emp_full_details .=  '<tr>';
                $emp_full_details .=  '<td bgcolor="#ccc" style="text-align: center;"><b>DOMAIN NAME</b></td>';
                $emp_full_details .=  '<td>'.$domainname.'</td>';
                $emp_full_details .=  '</tr>';
                $emp_full_details .=  '<tr>';
                $emp_full_details .=  '<td bgcolor="#ccc" style="text-align: center;"><b>THE COMPANY IS LISTED IN JUSTDIAL AND INTERNET SEARCHES?</b></td>';
                $emp_full_details .=  '<td>'.$justdialwebcheck.'</td>';
                $emp_full_details .=  '</tr>';
                $emp_full_details .=  '<tr>';
                $emp_full_details .=  '<td bgcolor="#ccc" style="text-align: center;"><b>IS IT FAMILY OWNED  BUSSINESS?</b></td>';
                $emp_full_details .=  '<td>'.$fmlyowned.'</td>';
                $emp_full_details .=  '</tr>';

                $emp_full_details .=  '<tr>';
                $emp_full_details .=  '<td bgcolor="#ccc" style="text-align: center;"><b>VERIFIED BY</b></td>';
                $emp_full_details .=  '<td>'.$verifiers_details.'</td>';
                $emp_full_details .=  '</tr>';
                $emp_full_details .=  '<tr>';
                $emp_full_details .=  '<td bgcolor="#ccc" style="text-align: center;"><b>MODE OF VERIFICATION</b></td>';
                $emp_full_details .=  '<td>'.$modeofverification.'</td>';
                $emp_full_details .=  '</tr>';
                $emp_full_details .=  '<tr>';
                $emp_full_details .=  '<td bgcolor="#ccc" style="text-align: center;"><b>REMARKS</b></td>';
                $emp_full_details .=  '<td>'.$res_remarks.'</td>';
                $emp_full_details .=  '</tr>';
                $emp_full_details .=  '</table>';
     // $pdf->writeHTML($educ_tbl, true, false, false, false, '');

				$pdf->writeHTML($emp_full_details, true, false, false, false, '');

          
				$attachments = ($value['attachments'] != NULL && $value['attachments'] != '') ?  explode('||', $value['attachments']) : '';

				$employment_vendor_attachments = ($value['vendor_attachments'] != NULL && $value['vendor_attachments'] != '') ?  explode('||', $value['vendor_attachments']) : '';

                if(!empty($attachments))
		        {

		          $pdf->SetAutoPageBreak(false, 0);

		          for ($i = 0; $i < count($attachments); $i++) {
		            $pdf->AddPage();
		            $pdf->Cell(0, 0, 'Annexure',0, 1, 'C');
		            $images_explode = explode('/',$attachments[$i]);

		          $extension = strtolower(pathinfo($images_explode[2], PATHINFO_EXTENSION)); 

		           $pdf->Image(IP_SITE_URL.EMPLOYMENT.$images_explode[1].'/'.$images_explode[2], $x, $y, $w, $h,  $extension , '', '', true, 150, '', false, false, false, false, false, false);
		           }    
		        }
		        if(!empty($employment_vendor_attachments))
		        {

		           $pdf->SetAutoPageBreak(false, 0);

		            for ($i = 0; $i < count($employment_vendor_attachments); $i++) {
				        $pdf->AddPage();
				        $pdf->Cell(0, 0, 'Annexure',0, 1, 'C');
				            

				        $extension = strtolower(pathinfo($employment_vendor_attachments[$i], PATHINFO_EXTENSION)); 

				        $pdf->Image(IP_SITE_URL.EMPLOYMENT.'vendor_file'.'/'.$employment_vendor_attachments[$i], $x, $y, $w, $h,  $extension , '', '', true, 150, '', false, false, false, false, false, false);
				    }    
		        }  
			}



     
       foreach ($address_info as $key => $value) 
      {
        $pdf->AddPage();
       // $pdf->Cell(0, 11, "ADDRESS VERIFICATION - ".$key ,1, 1, 'C', 1, 0);

        $counter = ($key == 0) ? ''  : $key+1;

       

        $Verified_status = ($value['verfstatus'] == "" || $value['verfstatus'] == "WIP" || $value['verfstatus'] == "Insufficiency" || $value['verfstatus'] == "Stop Check" || $value['verfstatus'] == 'Unable to verify' || $value['verfstatus'] == 'Overseas check' || $value['verfstatus'] == 'YTR' || str_replace(" ",'', $value['verfstatus']) == "Workedwiththesameorganization") ? "NA" : "Verified";

         $value = array_map('ucwords', $value);

        $period_of_stay = $value['stay_from'].' to '.$value['stay_to'];

        $res_period_of_stay = $value['res_stay_from'].' to '.$value['res_stay_to'];

        $verified = ($period_of_stay == $res_period_of_stay) ? 'Verified' : $res_period_of_stay; 
        $verified = ($Verified_status == "NA") ? 'NA' :   $verified;
  

        $full_address = $value['address'].' '.$value['city'].' '.$value['pincode'].' '.$value['address_state'];
        $res_full_address = $value['res_address'].' '.$value['res_city'].' '.$value['res_pincode'].' '.$value['res_state'];
        $verified_address_status = ($full_address == $res_full_address) ? 'Verified' : $res_full_address;
        $verified_address_status = ($Verified_status == "NA") ? 'NA' :  $verified_address_status;

        $verified_by = ($Verified_status == "NA") ? 'NA' :  $value['verified_by'];
        if($value['verfstatus'] == "Insufficiency")
        {
            $res_remarks = $value['insuff_raise_remark']; 
        }
        else
        {
            $res_remarks = ($Verified_status == "NA") ?  $value['res_remarks'] :  $value['res_remarks'];
        }
        $mode_of_verification = ($Verified_status == "NA") ? 'NA' :  $value['mode_of_verification'];



          $address_name = '<style> table, td{ border: 1.3px solid black; }</style><table width="98%" cellpadding="5" cellspacing="pixels">';
          $address_name .='<tr>';
          $address_name .= '<td bgcolor="#ccc" style="text-align: center;height: 35px;" ><b>'.$component_name["addrver"].' VERIFICATION '.$counter.'</b></td>';
          $address_name .= '</tr>';    
          $address_name .= '</table>';

          $pdf->writeHTML($address_name, true, false, false, false, '');

          $address_full_info = '<style> table, td{ border: 1.3px solid black; }</style><table width="96%" cellpadding="5" cellspacing="pixels">';
          $address_full_info .= '<tr>';
          $address_full_info .= '<td bgcolor="#ccc" style="text-align: center;"><b> COMPONENTS</b></td>';
          $address_full_info .= '<td bgcolor="#ccc" style="text-align: center;"><b> INFORMATION PROVIDED</b></td>';
          $address_full_info .= '<td bgcolor="#ccc" style="text-align: center;"><b> INFORMATION VERIFIED</b></td>';
          $address_full_info .= '</tr>'; 
          $address_full_info .= '<tr>';
          $address_full_info .= '<td bgcolor="#ccc" style="text-align: center;"><b> ADDRESS</b> </td>';
          $address_full_info .= '<td>'.$full_address.'</td>';
          $address_full_info .= '<td>'.$verified_address_status.'</td>';
          $address_full_info .= '</tr>';
          $address_full_info .= '<tr>';
          $address_full_info .= '<td bgcolor="#ccc" style="text-align: center;"><b>PERIOD OF STAY</b></td>';
          $address_full_info .= '<td>'.$period_of_stay.'</td>';
          $address_full_info .= '<td>'.$verified.'</td>';
          $address_full_info .= '</tr>';
          $address_full_info .= '</table>';

          $pdf->writeHTML($address_full_info, true, false, false, false, '');

          $address_full_details = '<style> table, td{ border: 1.3px solid black; }</style><table width="97%" cellpadding="5" cellspacing="pixels">';
          $address_full_details .= '<tr>';
          $address_full_details .= '<td bgcolor="#ccc" style="text-align: center;"><b> DETAILS</b></td>';
          $address_full_details .= '<td bgcolor="#ccc" style="text-align: center;"><b> FINDINGS</b></td>';
          $address_full_details .= '</tr>'; 
          $address_full_details .= '<tr>';
          $address_full_details .= '<td bgcolor="#ccc" style="text-align: center;"><b>VERIFIED BY</b></td>';
          $address_full_details .= '<td>'.$verified_by.'</td>';
          $address_full_details .= '</tr>';
          $address_full_details .= '<tr>';
          $address_full_details .= '<td bgcolor="#ccc" style="text-align: center;"><b>MODE OF VERIFICATION</b></td>';
          $address_full_details .= '<td>'.$mode_of_verification.'</td>';
          $address_full_details .= '</tr>';
          $address_full_details .= '<tr>';
          $address_full_details .= '<td bgcolor="#ccc" style="text-align: center;"><b>REMARKS</b></td>';
          $address_full_details .= '<td>'.$res_remarks.'</td>';
          $address_full_details .= '</tr>';
               
          $address_full_details .= '</table>';

        $pdf->writeHTML($address_full_details, true, false, false, false, '');


        $attachments = ($value['add_attachments'] != NULL && $value['add_attachments'] != '') ?  explode('||', $value['add_attachments']) : '';

        $address_vendor_attachments = ($value['vendor_attachments'] != NULL && $value['vendor_attachments'] != '') ?  explode('||', $value['vendor_attachments']) : '';

        if(!empty($attachments))
        {

          $pdf->SetAutoPageBreak(false, 0);

          for ($i = 0; $i < count($attachments); $i++) {
            $pdf->AddPage();
            $pdf->Cell(0, 0, 'Annexure',0, 1, 'C');
            $images_explode = explode('/',$attachments[$i]);

          $extension = strtolower(pathinfo($images_explode[2], PATHINFO_EXTENSION)); 

           $pdf->Image(IP_SITE_URL.ADDRESS.$images_explode[1].'/'.$images_explode[2], $x, $y, $w, $h,  $extension , '', '', true, 150, '', false, false, false, false, false, false);
           }    
        }

        if(!empty($address_vendor_attachments))
		{

		    $pdf->SetAutoPageBreak(false, 0);

		    for ($i = 0; $i < count($address_vendor_attachments); $i++) {
		        $pdf->AddPage();
		        $pdf->Cell(0, 0, 'Annexure',0, 1, 'C');
		            

		        $extension = strtolower(pathinfo($address_vendor_attachments[$i], PATHINFO_EXTENSION)); 

		        $pdf->Image(IP_SITE_URL.ADDRESS.'vendor_file'.'/'.$address_vendor_attachments[$i], $x, $y, $w, $h,  $extension , '', '', true, 150, '', false, false, false, false, false, false);
		    }    
		} 
      }

    
		foreach ($education_info as $key => $value) 
		{	
		   $pdf->AddPage();

		$counter = ($key == 0) ? ''  : $key+1;
			

        $Verified_status = ($value['verfstatus'] == "" || $value['verfstatus'] == "WIP" || $value['verfstatus'] == "Insufficiency" || $value['verfstatus'] == "Stop Check" || $value['verfstatus'] == 'Unable to verify' || $value['verfstatus'] == 'Overseas check' || $value['verfstatus'] == 'YTR' || str_replace(" ",'', $value['verfstatus']) == "Workedwiththesameorganization") ? "NA" : "Verified";

        $value = array_map('ucwords',$value);


        $school_college_univerity = ($value['school_college'] != '') ? $value['school_college'].'/' : "";
        $school_college_univerity .= ($value['university_board'] != '') ? $value['university_board'] : "NA";

        $res_school_college_univerity = ($value['res_school_college'] != '') ? $value['res_school_college'].'/' : "";
        $res_school_college_univerity .= ($value['res_university_board'] != '') ? $value['res_university_board'] : "NA";


        $school_college = $value['school_college'].'/'.$value['university_board'];
        $res_school_college = $value['res_school_college']. '/'.$value['res_university_board'];
        $verified = ($school_college == $res_school_college) ? 'Verified' : $res_school_college_univerity;
        $verified = ($Verified_status == "NA") ? 'NA' :   $verified;
        $res_qualification = ($Verified_status == "NA") ? 'NA' :   $value['res_qualification'];
        $res_year_of_passing = ($Verified_status == "NA") ? 'NA' :   $value['res_year_of_passing'];

        $verf_details = ($value['verified_by'] != '') ? $value['verified_by'] : "";
        $verf_details .= ($value['verifier_designation'] != '') ? ','.$value['verifier_designation'] : "";
        $verf_details .= ($value['verifier_contact_details'] != '') ? ','.$value['verifier_contact_details'] : "";

        $verf_details = ($Verified_status == "NA") ? 'NA' :   $verf_details;
        $res_mode_of_verification = ($Verified_status == "NA") ? 'NA' :   $value['res_mode_of_verification'];
        
        if($value['verfstatus'] == "Insufficiency")
	    {
		    $res_remarks = $value['insuff_raise_remark']; 
		}
		else
		{
		     $res_remarks = ($Verified_status == "NA") ? $value['res_remarks'] :  $value['res_remarks'];
	    }


       
         $educ_name = '<style> table, td{ border: 1.3px solid black; }</style><table width="98%" cellpadding="5" cellspacing="pixels">';
         $educ_name .= '<tr>';
         $educ_name .= '<td bgcolor="#ccc" style="text-align: center;height: 35px;"><b>'.$component_name["eduver"].' VERIFICATION '.$counter.'</b></td>';
         $educ_name .= '</tr>';    
         $educ_name .= '</table>';

          $pdf->writeHTML($educ_name, true, false, false, false, '');


         $educ_full_info = '<style> table, td{ border: 1.3px solid black; }</style><table width="96%" cellpadding="5" cellspacing="pixels" >';
         $educ_full_info .= '<tr>';
         $educ_full_info .= '<td bgcolor="#ccc" style="text-align: center;"><b> COMPONENTS</b></td>';
         $educ_full_info .= '<td bgcolor="#ccc" style="text-align: center;"><b> INFORMATION PROVIDED</b></td>';
         $educ_full_info .= '<td bgcolor="#ccc" style="text-align: center;"><b> INFORMATION VERIFIED</b></td>';
         $educ_full_info .= '</tr>'; 
         $educ_full_info .= '<tr>';
         $educ_full_info .= '<td bgcolor="#ccc" style="text-align: center;"><b>NAME OF THE COLLEGE / UNIVERSITY</b></td>';
         $educ_full_info .= '<td>'.$school_college.'</td>';
         $educ_full_info .= '<td>'.$verified.'</td>';
         $educ_full_info .= '</tr>';
         $educ_full_info .= '<tr>';
         $educ_full_info .= '<td bgcolor="#ccc" style="text-align: center;"><b>QUALIFICATION ATTAINED</b></td>';
         $educ_full_info .= '<td>'.$value['qualification'].'</td>';
         $educ_full_info .= '<td>'.$res_qualification.'</td>';
         $educ_full_info .= '</tr>';
         $educ_full_info .= '<tr>';
         $educ_full_info .= '<td bgcolor="#ccc" style="text-align: center;"><b>YEAR OF PASSING</b></td>';
         $educ_full_info .= '<td>'.$value["year_of_passing"].'</td>';
         $educ_full_info .= '<td>'.$res_year_of_passing.'</td>';
         $educ_full_info .= '</tr>';
         $educ_full_info .= '</table>';
         $pdf->writeHTML($educ_full_info, true, false, false, false, '');


    
       $educ_full_details = '<style> table, td{ border: 1.3px solid black; }</style><table width="97%" cellpadding="5" cellspacing="pixels">';
         $educ_full_details .= '<tr>';
         $educ_full_details .= '<td bgcolor="#ccc" style="text-align: center;"><b> DETAILS</b></td>';
         $educ_full_details .= '<td bgcolor="#ccc" style="text-align: center;"><b> FINDINGS </b></td>';
         $educ_full_details .= '</tr>'; 
        // $educ_full_details .= '<tr>';
        // $educ_full_details .= '<td bgcolor="#ccc" style="text-align: center;"><b> ADDITIONAL COMMENTS (IF ANY)</b></td>';
        // $educ_full_details .= '<td >'.$value['res_remarks'].'</td>';
        // $educ_full_details .= '</tr>';
         $educ_full_details .= '<tr>';
         $educ_full_details .= '<td bgcolor="#ccc" style="text-align: center;"><b>VERIFIED BY</b></td>';
         $educ_full_details .= '<td>'.$verf_details.'</td>';
         $educ_full_details .= '</tr>';
         $educ_full_details .= '<tr>';
         $educ_full_details .= '<td bgcolor="#ccc" style="text-align: center;"><b>MODE OF VERIFICATION</b></td>';
         $educ_full_details .= '<td>'.$res_mode_of_verification.'</td>';
         $educ_full_details .= '</tr>';
         $educ_full_details .= '<tr>';
         $educ_full_details .= '<td bgcolor="#ccc" style="text-align: center;"><b>REMARKS</b></td>';
         $educ_full_details .= '<td>'.$res_remarks.'</td>';
         $educ_full_details .= '</tr>';
         $educ_full_details .= '</table>';        


         $pdf->writeHTML($educ_full_details, true, false, false, false, '');

        $attachments = ($value['attachments'] != NULL && $value['attachments'] != '') ?  explode('||', $value['attachments']) : '';

        $education_vendor_attachments = ($value['vendor_attachments'] != NULL && $value['vendor_attachments'] != '') ?  explode('||', $value['vendor_attachments']) : '';

                 if(!empty($attachments))
		         {

		          $pdf->SetAutoPageBreak(false, 0);

		          for ($i = 0; $i < count($attachments); $i++) {
		            $pdf->AddPage();
		            $pdf->Cell(0, 0, 'Annexure',0, 1, 'C');
		            $images_explode = explode('/',$attachments[$i]);

		          $extension = strtolower(pathinfo($images_explode[2], PATHINFO_EXTENSION)); 

		           $pdf->Image(IP_SITE_URL.EDUCATION.$images_explode[1].'/'.$images_explode[2], $x, $y, $w, $h,  $extension , '', '', true, 150, '', false, false, false, false, false, false);
		           }    
		         }

		         if(!empty($education_vendor_attachments))
		         {

		          $pdf->SetAutoPageBreak(false, 0);

		          for ($i = 0; $i < count($education_vendor_attachments); $i++) {
		            $pdf->AddPage();
		            $pdf->Cell(0, 0, 'Annexure',0, 1, 'C');
		            

		          $extension = strtolower(pathinfo($education_vendor_attachments[$i], PATHINFO_EXTENSION)); 

		           $pdf->Image(IP_SITE_URL.EDUCATION.'vendor_file'.'/'.$education_vendor_attachments[$i], $x, $y, $w, $h,  $extension , '', '', true, 150, '', false, false, false, false, false, false);
		           }    
		         }     
			}
			
      foreach ($references_info as $key => $value) 
      { 
        $pdf->AddPage();

        $counter = ($key == 0) ? ''  : $key+1;

        $Verified_status = ($value['verfstatus'] == "" || $value['verfstatus'] == "WIP" || $value['verfstatus'] == "Insufficiency" || $value['verfstatus'] == "Stop Check" || $value['verfstatus'] == 'Unable to verify' || $value['verfstatus'] == 'Overseas check' || $value['verfstatus'] == 'YTR' || str_replace(" ",'', $value['verfstatus']) == "Workedwiththesameorganization") ? "NA" : "Verified";

        $value = array_map('ucwords', $value);

                $name_of_reference = ($Verified_status == "NA") ?  "NA" : $value['name_of_reference'];
                $designation = ($Verified_status == "NA") ?  "NA" : $value['designation'];
                $handle_pressure_value = ($Verified_status == "NA") ?  "NA" : $value['handle_pressure_value'];
                $attendance_value = ($Verified_status == "NA") ?  "NA" : $value['attendance_value'];
                $integrity_value = ($Verified_status == "NA") ?  "NA" : $value['integrity_value'];
                $leadership_skills_value = ($Verified_status == "NA") ?  "NA" : $value['leadership_skills_value'];
                $responsibilities_value = ($Verified_status == "NA") ?  "NA" : $value['responsibilities_value'];
                $achievements_value = ($Verified_status == "NA") ?  "NA" : $value['achievements_value'];
                $strengths_value = ($Verified_status == "NA") ?  "NA" : $value['strengths_value'];
                $weakness_value = ($Verified_status == "NA") ?  "NA" : $value['weakness_value'];
                $team_player_value = ($Verified_status == "NA") ?  "NA" : $value['team_player_value'];
                $overall_performance = ($Verified_status == "NA") ?  "NA" : $value['overall_performance'];
                $mode_of_verification = ($Verified_status == "NA") ?  "NA" : $value['mode_of_verification'];
             

                if($value['verfstatus'] == "Insufficiency")
			    {
				    $res_remarks = $value['insuff_raise_remark']; 
				}
				else
				{
				    $res_remarks = ($Verified_status == "NA") ? $value['res_remarks'] :  $value['res_remarks'];
			    }


                $additional_comments = ($value['additional_comments'] != '') ? $value['additional_comments'] : "Not Provided";

                $additional_comments = ($Verified_status == "NA") ?  "NA" : $additional_comments;
        
              
        $ref_name ='<style> table, td{ border: 1.3px solid black; }</style><table width="98%" cellpadding="5" cellspacing="pixels">';
        $ref_name .='<tr>';
        $ref_name .='<td bgcolor="#ccc" style="text-align: center;height: 35px;"><b>'.$component_name['refver'].' VERIFICATION '.$counter.'</b></td>';
        $ref_name .='</tr>';    
        $ref_name .='</table>';

        $pdf->writeHTML($ref_name, true, false, false, false, '');


        $ref_full_details ='<style> table, td{ border: 1.3px solid black; }</style><table width="97%" cellpadding="5" cellspacing="pixels">';
        $ref_full_details .='<tr>';
        $ref_full_details .='<td bgcolor="#ccc" style="text-align: center;"><b> PARTICULARS</b></td>';
        $ref_full_details .='<td bgcolor="#ccc" style="text-align: center;"><b>  VERIFIED INFORMATION</b></td>';
        $ref_full_details .='</tr>'; 
        $ref_full_details .='<tr>';
        $ref_full_details .='<td bgcolor="#ccc" style="text-align: center;"><b> REFERENCE NAME</b></td>';
        $ref_full_details .='<td>'.$name_of_reference.'</td>';
        $ref_full_details .='</tr>';
        $ref_full_details .='<tr>';
        $ref_full_details .='<td bgcolor="#ccc" style="text-align: center;"><b>DESIGNATION</b></td>';
        $ref_full_details .='<td>'.$designation.'</td>';
        $ref_full_details .='</tr>';
        $ref_full_details .='<tr>';
        $ref_full_details .='<td  bgcolor="#ccc" style="text-align: center;"><b>ABILITY TO HANDLE PRESSURE</b></td>';
        $ref_full_details .='<td>'.$handle_pressure_value."</td>";
        $ref_full_details .='</tr>';
        $ref_full_details .='<tr>';
        $ref_full_details .='<td bgcolor="#ccc" style="text-align: center;"><b>ATTENDANCE/PUNCTUALITY</b></td>';
        $ref_full_details .='<td>'.$attendance_value.'</td>';
        $ref_full_details .='</tr>';
        $ref_full_details .='<tr>';
        $ref_full_details .='<td bgcolor="#ccc" style="text-align: center;"><b>INTEGRITY, CHARACTER & HONESTY</b></td>';
        $ref_full_details .='<td>'.$integrity_value.'</td>';
        $ref_full_details .='</tr>';
        $ref_full_details .='<tr>';
        $ref_full_details .='<td bgcolor="#ccc" style="text-align: center;"><b>LEADERSHIP SKILLS</b></td>';
        $ref_full_details .='<td>'.$leadership_skills_value.'</td>';
        $ref_full_details .='</tr>';
        $ref_full_details .='<tr>';
        $ref_full_details .='<td bgcolor="#ccc" style="text-align: center;"><b>RESPONSIBILITIES & DUTIES</b></td>';
        $ref_full_details .='<td>'.$responsibilities_value.'</td>';
        $ref_full_details .='</tr>';
        $ref_full_details .='<tr>';
        $ref_full_details .='<td bgcolor="#ccc" style="text-align: center;"><b>SPECIFIC ACHIEVEMENTS</b></td>';
        $ref_full_details .='<td>'.$achievements_value.'</td>';
        $ref_full_details .='</tr>';
        $ref_full_details .='<tr>';
        $ref_full_details .='<td bgcolor="#ccc" style="text-align: center;"><b>STRENGTHS</b></td>';
        $ref_full_details .='<td>'.$strengths_value.'</td>';
        $ref_full_details .='</tr>';
        $ref_full_details .='<tr>';
        $ref_full_details .='<td bgcolor="#ccc" style="text-align: center;"><b>WEAKNESSES</b></td>';
        $ref_full_details .='<td>'.$weakness_value.'</td>';
        $ref_full_details .='</tr>';
        $ref_full_details .='<tr>';
        $ref_full_details .='<td  bgcolor="#ccc" style="text-align: center;"><b>TEAM PLAYER</b></td>';
        $ref_full_details .='<td>'.$team_player_value.'</td>';
        $ref_full_details .='</tr>';
        $ref_full_details .='<tr>';
        $ref_full_details .='<td  bgcolor="#ccc" style="text-align: center;"><b>OVERALL PERFORMANCE</b></td>';
        $ref_full_details .='<td>'.$overall_performance.'</td>';
        $ref_full_details .='</tr>';
        $ref_full_details .='<tr>';
        $ref_full_details .='<td  bgcolor="#ccc" style="text-align: center;"><b>MODE OF VERIFICATION</b></td>';
        $ref_full_details .='<td>'.$mode_of_verification.'</td>';
        $ref_full_details .='</tr>';
        $ref_full_details .='<tr>';
        $ref_full_details .='<td bgcolor="#ccc" style="text-align: center;"><b>ADDITIONAL COMMENTS</b></td>';
        $ref_full_details .='<td>'.$additional_comments.'</td>';
        $ref_full_details .='</tr>';
        $ref_full_details .='<tr>';
        $ref_full_details .='<td bgcolor="#ccc" style="text-align: center;"><b>REMARKS</b></td>';
        $ref_full_details .='<td>'.$res_remarks.'</td>';
        $ref_full_details .='</tr>';
       
        $ref_full_details .='</table>';

        $pdf->writeHTML($ref_full_details, true, false, false, false, '');

        $attachments = ($value['attachments'] != NULL && $value['attachments'] != '') ?  explode('||', $value['attachments']) : '';
         
      

              if(!empty($attachments))
		         {

		          $pdf->SetAutoPageBreak(false, 0);

		          for ($i = 0; $i < count($attachments); $i++) {
		            $pdf->AddPage();
		            $pdf->Cell(0, 0, 'Annexure',0, 1, 'C');
		            $images_explode = explode('/',$attachments[$i]);

		          $extension = strtolower(pathinfo($images_explode[2], PATHINFO_EXTENSION)); 

		           $pdf->Image(IP_SITE_URL.REFERENCES.$images_explode[1].'/'.$images_explode[2], $x, $y, $w, $h,  $extension , '', '', true, 150, '', false, false, false, false, false, false);
		           }    
		         }   

        
      }
    

			foreach ($court_info as $key => $value) 
			{	
				$pdf->AddPage();
				$counter = ($key == 0) ? ''  : $key+1;

				$Verified_status = ($value['verfstatus'] == "" || $value['verfstatus'] == "WIP" || $value['verfstatus'] == "Insufficiency" || $value['verfstatus'] == "Stop Check" || $value['verfstatus'] == 'Unable to verify' || $value['verfstatus'] == 'Overseas check' || $value['verfstatus'] == 'YTR' || str_replace(" ",'', $value['verfstatus']) == "Workedwiththesameorganization") ? "NA" : "Verified";

				$value = array_map('ucwords', $value);
      
       
		//		$pdf->Cell(0, 11, "COURT RECORD VERIFICATION" ,1, 1, 'C', 1, 0);

		        $full_address = $value['street_address'].' '.$value['city'].' '.$value['pincode'].' '.$value['state'];
                $full_address = ($Verified_status == "NA") ?  "NA" : $full_address;

                $mode_of_verification = ($value['mode_of_verification'] != '') ? $value['mode_of_verification'] : "NA";
                $mode_of_verification = ($Verified_status == "NA") ?  "NA" : $mode_of_verification;

                $remarks = ($value['remarks'] != '') ?  $value['remarks'] : "NA";

                if($value['verfstatus'] == "Insufficiency")
			    {
				    $remarks = $value['insuff_raise_remark']; 
				}
				else
				{
				     $remarks = ($Verified_status == "NA") ?  $remarks : $remarks;
			    }

               

		$court_name = '<style> table, td{ border: 1.3px solid black; }</style><table width="98%" border="1" cellpadding="5">';
        $court_name .= '<tr>';
        $court_name .= '<td bgcolor="#ccc" style="text-align: center;height: 35px;"><b>'.$component_name["courtver"].' VERIFICATION  '.$counter.'</b></td>';
        $court_name .= '</tr>';    
        $court_name .= '</table>';

        $pdf->writeHTML($court_name, true, false, false, false, '');


        $court_full_details = '<style> table, td{ border: 1.3px solid black; }</style><table width="97%" border="1" cellpadding="5">';
				$court_full_details .= '<tr>';
			  $court_full_details .= '<td bgcolor="#ccc" style="text-align: center;"><b> PARTICULARS</b></td>';
        $court_full_details .= '<td bgcolor="#ccc" style="text-align: center;"><b> VERIFIED INFORMATION</b></td>';
        $court_full_details .= '</tr>'; 
        $court_full_details .= '<tr>';
        $court_full_details .= '<td  bgcolor="#ccc" style="text-align: center;"><b>ADDRESS</b></td>';
        $court_full_details .= '<td>'.$full_address.'</td>';
        $court_full_details .= '</tr>';
              
        $court_full_details .= '<tr>';
        $court_full_details .= '<td  bgcolor="#ccc" style="text-align: center;"><b>MODE OF VERIFICATION</b></td>';
        $court_full_details .= '<td>'.$mode_of_verification.'</td>';
        $court_full_details .= '</tr>';
        $court_full_details .= '<tr>';
        $court_full_details .= '<td  bgcolor="#ccc" style="text-align: center;"><b>REMARKS</b></td>';
        $court_full_details .= '<td>'.$remarks.'</td>';
        $court_full_details .= '</tr>';
        $court_full_details .= '</table>';

				
	    $pdf->writeHTML($court_full_details, true, false, false, false, '');

        $attachments = ($value['attachments'] != NULL && $value['attachments'] != '') ?  explode('||', $value['attachments']) : '';

        $court_vendor_attachments = ($value['vendor_attachments'] != NULL && $value['vendor_attachments'] != '') ?  explode('||', $value['vendor_attachments']) : '';

                if(!empty($attachments))
		        {

		         $pdf->SetAutoPageBreak(false, 0);

		         for ($i = 0; $i < count($attachments); $i++) {
		            $pdf->AddPage();
		            $pdf->Cell(0, 0, 'Annexure',0, 1, 'C');
		            $images_explode = explode('/',$attachments[$i]);

		         $extension = strtolower(pathinfo($images_explode[2], PATHINFO_EXTENSION)); 

		         $pdf->Image(IP_SITE_URL.COURT_VERIFICATION.$images_explode[1].'/'.$images_explode[2], $x, $y, $w, $h,  $extension , '', '', true, 150, '', false, false, false, false, false, false);
		           }    
		        }
		        
		        if(!empty($court_vendor_attachments))
		        {

		          $pdf->SetAutoPageBreak(false, 0);

		          for ($i = 0; $i < count($court_vendor_attachments); $i++) {
		            $pdf->AddPage();
		            $pdf->Cell(0, 0, 'Annexure',0, 1, 'C');
		            

		          $extension = strtolower(pathinfo($court_vendor_attachments[$i], PATHINFO_EXTENSION)); 

		           $pdf->Image(IP_SITE_URL.COURT_VERIFICATION.'vendor_file'.'/'.$court_vendor_attachments[$i], $x, $y, $w, $h,  $extension , '', '', true, 150, '', false, false, false, false, false, false);
		           }    
		        }     
			}

       foreach ($global_db_info as $key => $value) 
      { 
        $pdf->AddPage();
       
        $counter = ($key == 0) ? ''  : $key+1;

        $Verified_status = ($value['verfstatus'] == "" || $value['verfstatus'] == "WIP" || $value['verfstatus'] == "Insufficiency" || $value['verfstatus'] == "Stop Check" || $value['verfstatus'] == 'Unable to verify' || $value['verfstatus'] == 'Overseas check' || $value['verfstatus'] == 'YTR' || str_replace(" ",'', $value['verfstatus']) == "Workedwiththesameorganization") ? "NA" : "Verified";

        $value = array_map('ucwords', $value);


        $full_address = $value['street_address'].' '.$value['city'].' '.$value['pincode'].' '.$value['state'];
        $full_address = ($Verified_status == "NA") ?  "NA" : $full_address;


        $mode_of_verification = ($value['mode_of_verification'] != '') ? $value['mode_of_verification'] : "NA";
        $mode_of_verification = ($Verified_status == "NA") ?  "NA" : $mode_of_verification;

        $remarks = ($value['remarks'] != '') ?  $value['remarks'] : "NA";

        if($value['verfstatus'] == "Insufficiency")
	    {
		    $remarks = $value['insuff_raise_remark']; 
	    }
	    else 
	    {
		    $remarks = ($Verified_status == "NA") ?  $remarks : $remarks;
	    }
       

        $global_name = '<style> table, td{ border: 1.3px solid black; }</style><table width="98%" border="1" cellpadding="5">';
        $global_name .= '<tr>';
        $global_name .= '<td bgcolor="#ccc" style="text-align: center;height: 35px;"><b>'.$component_name["globdbver"].' VERIFICATION  '.$counter.'</b></td>';
        $global_name .= '</tr>';    
        $global_name .= '</table>';

        $pdf->writeHTML($global_name, true, false, false, false, '');

        $global_full_details = '<style> table, td{ border: 1.3px solid black; }</style><table width="97%" border="1" cellpadding="5">';
        $global_full_details .= '<tr>';
        $global_full_details .= '<td bgcolor="#ccc" style="text-align: center;"><b> DETAILS</b></td>';
        $global_full_details .= '<td bgcolor="#ccc" style="text-align: center;"><b> FINDINGS</b></td>';
        $global_full_details .= '</tr>'; 
        $global_full_details .= '<tr>';
        $global_full_details .= '<td  bgcolor="#ccc" style="text-align: center;"><b>ADDRESS</b></td>';
        $global_full_details .= '<td>'.$full_address.'</td>';
        $global_full_details .= '</tr>';
          
        $global_full_details .= '<tr>';
        $global_full_details .= '<td  bgcolor="#ccc" style="text-align: center;"><b>MODE OF VERIFICATION</b></td>';
        $global_full_details .= '<td>'.$mode_of_verification.'</td>';
        $global_full_details .= '</tr>';
       
       
        $global_full_details .= '<tr>';
        $global_full_details .= '<td  bgcolor="#ccc" style="text-align: center;"><b>REMARKS</b></td>';
        $global_full_details .= '<td>'.$remarks.'</td>';
        $global_full_details .= '</tr>';
              
        $global_full_details .= '</table>';

        
        $pdf->writeHTML($global_full_details, true, false, false, false, '');

        $attachments = ($value['attachments'] != NULL && $value['attachments'] != '') ?  explode('||', $value['attachments']) : '';

        $global_vendor_attachments = ($value['vendor_attachments'] != NULL && $value['vendor_attachments'] != '') ?  explode('||', $value['vendor_attachments']) : '';

    
                if(!empty($attachments))
		        {

		         $pdf->SetAutoPageBreak(false, 0);

		         for ($i = 0; $i < count($attachments); $i++) {
		            $pdf->AddPage();
		            $pdf->Cell(0, 0, 'Annexure',0, 1, 'C');
		            $images_explode = explode('/',$attachments[$i]);

		         $extension = strtolower(pathinfo($images_explode[2], PATHINFO_EXTENSION)); 

		         $pdf->Image(IP_SITE_URL.GLOBAL_DB.$images_explode[1].'/'.$images_explode[2], $x, $y, $w, $h,  $extension , '', '', true, 150, '', false, false, false, false, false, false);
		           }    
		        } 


		        if(!empty($global_vendor_attachments))
		        {

		          $pdf->SetAutoPageBreak(false, 0);

		          for ($i = 0; $i < count($global_vendor_attachments); $i++) {
		            $pdf->AddPage();
		            $pdf->Cell(0, 0, 'Annexure',0, 1, 'C');
		            

		          $extension = strtolower(pathinfo($global_vendor_attachments[$i], PATHINFO_EXTENSION)); 

		           $pdf->Image(IP_SITE_URL.GLOBAL_DB.'vendor_file'.'/'.$global_vendor_attachments[$i], $x, $y, $w, $h,  $extension , '', '', true, 150, '', false, false, false, false, false, false);
		           }    
		        }  
      }


      foreach ($drugs_info as $key => $value) 
      { 
        $pdf->AddPage();
       
        $counter = ($key == 0) ? ''  : $key+1;
    //    $pdf->Cell(0, 11, "COURT RECORD VERIFICATION" ,1, 1, 'C', 1, 0);

        $Verified_status = ($value['verfstatus'] == "" || $value['verfstatus'] == "WIP" || $value['verfstatus'] == "Insufficiency" || $value['verfstatus'] == "Stop Check" || $value['verfstatus'] == 'Unable to verify' || $value['verfstatus'] == 'Overseas check' || $value['verfstatus'] == 'YTR' || str_replace(" ",'', $value['verfstatus']) == "Workedwiththesameorganization") ? "NA" : "Verified";

        $value = array_map('ucwords', $value);

        $amphetamine_screen = ($Verified_status == "NA") ?  "NA" : $value['amphetamine_screen'];
        $cannabinoids_screen = ($Verified_status == "NA") ?  "NA" : $value['cannabinoids_screen'];
        $cocaine_screen = ($Verified_status == "NA") ?  "NA" : $value['cocaine_screen'];
        $opiates_screen = ($Verified_status == "NA") ?  "NA" : $value['opiates_screen'];
        $phencyclidine_screen = ($Verified_status == "NA") ?  "NA" : $value['phencyclidine_screen'];
      
        if($value['verfstatus'] == "Insufficiency")
	    {
		    $remarks = $value['insuff_raise_remark']; 
	    }
	    else 
	    {
		    $remarks = ($Verified_status == "NA") ?  $value['remarks'] : $value['remarks'];

	    }
       

        $drugs_name = '<style> table, td{ border: 1.3px solid black; }</style><table width="98%" border="1" cellpadding="5">';
        $drugs_name .= '<tr>';
        $drugs_name .= '<td bgcolor="#ccc" style="text-align: center;height: 35px;"><b>'.$component_name["narcver"].' VERIFICATION  '.$counter.'</b></td>';
        $drugs_name .= '</tr>';    
        $drugs_name .= '</table>';

        $pdf->writeHTML($drugs_name, true, false, false, false, '');


        $drugs_full_details = '<style> table, td{ border: 1.3px solid black; }</style><table width="97%" border="1" cellpadding="5">';
        $drugs_full_details .= '<tr>';
        $drugs_full_details .= '<td bgcolor="#ccc" style="text-align: center;"><b> 5 DRUG PANEL</b></td>';
        $drugs_full_details .= '<td bgcolor="#ccc" style="text-align: center;"><b> STATUS </b></td>';
        $drugs_full_details .= '</tr>'; 
        $drugs_full_details .= '<tr>';
        $drugs_full_details .= '<td bgcolor="#ccc" style="text-align: center;"><b> AMPHETAMINE SCREEN, URINE</b></td>';
        $drugs_full_details .= '<td>'.$amphetamine_screen.'</td>';
        $drugs_full_details .= '</tr>';
        $drugs_full_details .= '<tr>';
        $drugs_full_details .= '<td bgcolor="#ccc" style="text-align: center;"><b> CANNABINOIDS SCREEN, URINE</b></td>';
        $drugs_full_details .= '<td>'.$cannabinoids_screen.'</td>';
        $drugs_full_details .= '</tr>';
        $drugs_full_details .= '<tr>';
        $drugs_full_details .= '<td bgcolor="#ccc" style="text-align: center;"><b>  COCAINE SCREEN, URINE</b></td>';
        $drugs_full_details .= '<td>'.$cocaine_screen.'</td>';
        $drugs_full_details .= '</tr>';
        $drugs_full_details .= '<tr>';
        $drugs_full_details .= '<td bgcolor="#ccc" style="text-align: center;"><b>  OPIATES SCREEN, URINE</b></td>';
        $drugs_full_details .= '<td>'.$opiates_screen.'</td>';
        $drugs_full_details .= '</tr>';
        $drugs_full_details .= '<tr>';
        $drugs_full_details .= '<td bgcolor="#ccc" style="text-align: center;"><b> PHENCYCLIDINE SCREEN, URINE</b></td>';
        $drugs_full_details .= '<td>'.$phencyclidine_screen.'</td>';
        $drugs_full_details .= '</tr>';
        $drugs_full_details .= '<tr>';
        $drugs_full_details .= '<td bgcolor="#ccc" style="text-align: center;"><b> REMARKS</b></td>';
        $drugs_full_details .= '<td>'.$remarks.'</td>';
        $drugs_full_details .= '</tr>';
                
        $drugs_full_details .= '</table>';

        
        $pdf->writeHTML($drugs_full_details, true, false, false, false, '');

        $attachments = ($value['attachments'] != NULL && $value['attachments'] != '') ?  explode('||', $value['attachments']) : '';

       

             if(!empty($attachments))
		        {

		         $pdf->SetAutoPageBreak(false, 0);

		         for ($i = 0; $i < count($attachments); $i++) {
		            $pdf->AddPage();
		            $pdf->Cell(0, 0, 'Annexure',0, 1, 'C');
		            $images_explode = explode('/',$attachments[$i]);

		         $extension = strtolower(pathinfo($images_explode[2], PATHINFO_EXTENSION)); 

		         $pdf->Image(IP_SITE_URL.DRUGS.$images_explode[1].'/'.$images_explode[2], $x, $y, $w, $h,  $extension , '', '', true, 150, '', false, false, false, false, false, false);
		           }    
		         }  
      }

			foreach ($pcc_info as $key => $value) 
			{	
				$pdf->AddPage();
				
       
          $counter = ($key == 0) ? ''  : $key+1;

          $Verified_status = ($value['verfstatus'] == "" || $value['verfstatus'] == "WIP" || $value['verfstatus'] == "Insufficiency" || $value['verfstatus'] == "Stop Check" || $value['verfstatus'] == 'Unable to verify' || $value['verfstatus'] == 'Overseas check' || $value['verfstatus'] == 'YTR' || str_replace(" ",'', $value['verfstatus']) == "Workedwiththesameorganization") ? "NA" : "Verified";

          $value = array_map('ucwords', $value);

                $police_station_visit_date = ($value['police_station_visit_date'] != '') ? date('d-M-Y', strtotime($value['police_station_visit_date'])) : "NA";
                $police_station_visit_date = ($Verified_status == "NA") ?  "NA" : $police_station_visit_date;


                $full_address = $value['street_address'].' '.$value['city'].' '.$value['pincode'].' '.$value['state'];
                $full_address = ($Verified_status == "NA") ?  "NA" : $full_address;

                $name_designation_police = ($Verified_status == "NA") ?  "NA" :  $value['name_designation_police'];
                $contact_number_police = ($Verified_status == "NA") ?  "NA" :  $value['contact_number_police'];
                $mode_of_verification = ($Verified_status == "NA") ?  "NA" :  $value['mode_of_verification'];
               

                if($value['verfstatus'] == "Insufficiency")
			    {
				    $remarks = $value['insuff_raise_remark']; 
			    }
			    else 
			    {
				    $remarks = ($Verified_status == "NA") ?  $value['remarks'] :  $value['remarks'];
			    }
           
          $pcc_name = '<style> table, td{ border: 1.3px solid black; }</style><table width="98%" cellpadding="5" cellspacing="pixels" >';
          $pcc_name .= '<tr>';  
          $pcc_name .= '<td bgcolor="#ccc" style="text-align: center;height: 35px;"><b>'.$component_name['crimver'].' VERIFICATION '.$counter.'</b></td>';
          $pcc_name .= '</tr>';    
          $pcc_name .= '</table>';

          $pdf->writeHTML($pcc_name, true, false, false, false, '');


          $pcc_full_details = '<style> table, td{ border: 1.3px solid black; }</style><table width="97%" cellpadding="5" cellspacing="pixels" >';
          $pcc_full_details .= '<tr>';
          $pcc_full_details .= '<td bgcolor="#ccc" style="text-align: center;"><b> PARTICULARS</b></td>';
          $pcc_full_details .= '<td bgcolor="#ccc" style="text-align: center;"><b> VERIFIED INFORMATION</b></td>';
          $pcc_full_details .= '</tr>'; 
          $pcc_full_details .= '<tr>';
          $pcc_full_details .= '<td bgcolor="#ccc" style="text-align: center;"><b>DATE OF VISIT POLICE STATION</b></td>';
          $pcc_full_details .= '<td>'.$police_station_visit_date.'</td>';
          $pcc_full_details .= '</tr>';
          $pcc_full_details .= '<tr>';
          $pcc_full_details .= '<td bgcolor="#ccc" style="text-align: center;"><b>ADDRESS</b></td>';
          $pcc_full_details .= '<td>'.$full_address.'</td>';
          $pcc_full_details .= '</tr>';
          $pcc_full_details .= '<tr>';
          $pcc_full_details .= '<td bgcolor="#ccc" style="text-align: center;"><b>NAME AND DESIGNATION OF THE POLICE OFFICER</b></td>';
          $pcc_full_details .= '<td>'.$name_designation_police.'</td>';
          $pcc_full_details .= '</tr>';
          $pcc_full_details .= '<tr>';
          $pcc_full_details .= '<td bgcolor="#ccc" style="text-align: center;"><b>CONTACT NUMBER OF POLICE</b></td>';
          $pcc_full_details .= '<td>'.$contact_number_police.'</td>';
          $pcc_full_details .= '</tr>';
              
          $pcc_full_details .= '<tr>';
          $pcc_full_details .= '<td bgcolor="#ccc" style="text-align: center;"><b>MODE OF VERIFICATION</b></td>';
          $pcc_full_details .= '<td>'.$mode_of_verification.'</td>';
          $pcc_full_details .= '</tr>';
          $pcc_full_details .= '<tr>';
          $pcc_full_details .= '<td bgcolor="#ccc" style="text-align: center;"><b>REMARKS</b></td>';
          $pcc_full_details .= '<td>'.$remarks.'</td>';
          $pcc_full_details .= '</tr>';
               
               
          $pcc_full_details .= '</table>';

				$pdf->writeHTML($pcc_full_details, true, false, false, false, '');

				$attachments = ($value['attachments'] != NULL && $value['attachments'] != '') ?  explode('||', $value['attachments']) : '';
                $pcc_vendor_attachments = ($value['vendor_attachments'] != NULL && $value['vendor_attachments'] != '') ?  explode('||', $value['vendor_attachments']) : '';

      

                if(!empty($attachments))
		        {

		         $pdf->SetAutoPageBreak(false, 0);

		         for ($i = 0; $i < count($attachments); $i++) {
		            $pdf->AddPage();
		            $pdf->Cell(0, 0, 'Annexure',0, 1, 'C');
		            $images_explode = explode('/',$attachments[$i]);

		         $extension = strtolower(pathinfo($images_explode[2], PATHINFO_EXTENSION)); 

		         $pdf->Image(IP_SITE_URL.PCC.$images_explode[1].'/'.$images_explode[2], $x, $y, $w, $h,  $extension , '', '', true, 150, '', false, false, false, false, false, false);
		           }    
		        }

		        if(!empty($pcc_vendor_attachments))
		        {

		          $pdf->SetAutoPageBreak(false, 0);

		          for ($i = 0; $i < count($pcc_vendor_attachments); $i++) {
		            $pdf->AddPage();
		            $pdf->Cell(0, 0, 'Annexure',0, 1, 'C');
		            

		          $extension = strtolower(pathinfo($pcc_vendor_attachments[$i], PATHINFO_EXTENSION)); 

		           $pdf->Image(IP_SITE_URL.PCC.'vendor_file'.'/'.$pcc_vendor_attachments[$i], $x, $y, $w, $h,  $extension , '', '', true, 150, '', false, false, false, false, false, false);
		           }    
		        }    

       }

      foreach ($identity_info as $key => $value) 
      { 
          $pdf->AddPage();
          
          $counter = ($key == 0) ? ''  : $key+1;

          $Verified_status = ($value['verfstatus'] == "" || $value['verfstatus'] == "WIP" || $value['verfstatus'] == "Insufficiency" || $value['verfstatus'] == "Stop Check" || $value['verfstatus'] == 'Unable to verify' || $value['verfstatus'] == 'Overseas check' || $value['verfstatus'] == 'YTR' || str_replace(" ",'', $value['verfstatus']) == "Workedwiththesameorganization") ? "NA" : "Verified";

          $value = array_map('ucwords', $value);

          $doc_submited = ($value['doc_submited'] != '') ? $value['doc_submited'] : "NA";
          $doc_submited = ($Verified_status == "NA") ?  "NA" : $doc_submited;

          $mode_of_verification = ($value['mode_of_verification'] != '') ? $value['mode_of_verification'] : "NA";
          $mode_of_verification = ($Verified_status == "NA") ?  "NA" : $mode_of_verification;

          $remarks = ($value['remarks'] != '') ? $value['remarks'] : "NA";

          if($value['verfstatus'] == "Insufficiency")
		  {
		    $remarks = $value['insuff_raise_remark']; 
		  }
		  else 
		  {
		     $remarks = ($Verified_status == "NA") ?  $remarks : $remarks;
		  }
           
          $identity_name = '<style> table, td{ border: 1.3px solid black; }</style><table width="98%" cellpadding="5" cellspacing="pixels">';
          $identity_name .= '<tr>';
          $identity_name .= '<td bgcolor="#ccc" style="text-align: center;height: 35px;"><b>'.$component_name["identity"].' VERIFICATION '.$counter.'</b></td>';
          $identity_name .= '</tr>'; 
          $identity_name .= '</table>';

          $pdf->writeHTML($identity_name, true, false, false, false, '');


          $identity_full_details = '<style> table, td{ border: 1.3px solid black; }</style><table width="97%" cellpadding="5" cellspacing="pixels">';
          $identity_full_details .= '<tr>';
          $identity_full_details .= '<td bgcolor="#ccc" style="text-align: center;"><b> PARTICULARS</b></td>';
          $identity_full_details .= '<td bgcolor="#ccc" style="text-align: center;"><b> VERIFIED INFORMATION</b></td>';
          $identity_full_details .= '</tr>';
          $identity_full_details .= '<tr>';
          $identity_full_details .= '<td bgcolor="#ccc" style="text-align: center;"><b> DOCUMENT PROVIDED</b></td>';
          $identity_full_details .= '<td>'.$doc_submited.'</td>';
          $identity_full_details .= '</tr>';
          $identity_full_details .= '<tr>';
          $identity_full_details .= '<td bgcolor="#ccc" style="text-align: center;"><b> MODE OF VERIFICATION</b></td>';
          $identity_full_details .= '<td>'.$mode_of_verification.'</td>';
          $identity_full_details .= '</tr>';
          $identity_full_details .= '<tr>';
          $identity_full_details .= '<td bgcolor="#ccc" style="text-align: center;"><b> REMARKS</b></td>';
          $identity_full_details .= '<td>'.$remarks.'</td>';
          $identity_full_details .= '</tr>';
                
                 
          $identity_full_details .= '</table>';


        $pdf->writeHTML($identity_full_details, true, false, false, false, '');

        $attachments = ($value['attachments'] != NULL && $value['attachments'] != '') ?  explode('||', $value['attachments']) : '';
        
        $identity_vendor_attachments = ($value['vendor_attachments'] != NULL && $value['vendor_attachments'] != '') ?  explode('||', $value['vendor_attachments']) : '';
    
                if(!empty($attachments))
		        {

		         $pdf->SetAutoPageBreak(false, 0);

		         for ($i = 0; $i < count($attachments); $i++) {
		            $pdf->AddPage();
		            $pdf->Cell(0, 0, 'Annexure',0, 1, 'C');
		            $images_explode = explode('/',$attachments[$i]);

		         $extension = strtolower(pathinfo($images_explode[2], PATHINFO_EXTENSION)); 

		         $pdf->Image(IP_SITE_URL.IDENTITY.$images_explode[1].'/'.$images_explode[2], $x, $y, $w, $h,  $extension , '', '', true, 150, '', false, false, false, false, false, false);
		           }    
		        } 

		        if(!empty($identity_vendor_attachments))
		        {

		          $pdf->SetAutoPageBreak(false, 0);

		          for ($i = 0; $i < count($identity_vendor_attachments); $i++) {
		            $pdf->AddPage();
		            $pdf->Cell(0, 0, 'Annexure',0, 1, 'C');
		            

		          $extension = strtolower(pathinfo($identity_vendor_attachments[$i], PATHINFO_EXTENSION)); 

		           $pdf->Image(IP_SITE_URL.IDENTITY.'vendor_file'.'/'.$identity_vendor_attachments[$i], $x, $y, $w, $h,  $extension , '', '', true, 150, '', false, false, false, false, false, false);
		           }    
		        }  

      }

      foreach ($credit_report_info as $key => $value) 
      { 
          $pdf->AddPage();
          
          $counter = ($key == 0) ? ''  : $key+1;

          $Verified_status = ($value['verfstatus'] == "" || $value['verfstatus'] == "WIP" || $value['verfstatus'] == "Insufficiency" || $value['verfstatus'] == "Stop Check" || $value['verfstatus'] == 'Unable to verify' || $value['verfstatus'] == 'Overseas check' || $value['verfstatus'] == 'YTR' || str_replace(" ",'', $value['verfstatus']) == "Workedwiththesameorganization") ? "NA" : "Verified";

          $value = array_map('ucwords', $value);

          $doc_submited = ($value['doc_submited'] != '') ? $value['doc_submited'] : "NA";
          $doc_submited = ($Verified_status == "NA") ?  "NA" : $doc_submited;

          $mode_of_verification = ($value['mode_of_verification'] != '') ? $value['mode_of_verification'] : "NA";
          $mode_of_verification = ($Verified_status == "NA") ?  "NA" : $mode_of_verification;

          $remarks = ($value['remarks'] != '') ? $value['remarks'] : "NA";

          if($value['verfstatus'] == "Insufficiency")
		  {
		    $remarks = $value['insuff_raise_remark']; 
		  }
		  else 
		  {
		    $remarks = ($Verified_status == "NA") ?  $remarks : $remarks;
		  }
         

           
          $credit_report_name = '<style> table, td{ border: 1.3px solid black; }</style><table width="98%" cellpadding="5" cellspacing="pixels">';
          $credit_report_name .= '<tr>';
          $credit_report_name .= '<td bgcolor="#ccc" style="text-align: center;height: 35px;"><b>'.$component_name["cbrver"].' VERIFICATION '.$counter.'</b></td>';
          $credit_report_name .= '</tr>'; 
          $credit_report_name .= '</table>';

          $pdf->writeHTML($credit_report_name, true, false, false, false, '');


          $credit_report_full_details = '<style> table, td{ border: 1.3px solid black; }</style><table width="97%" cellpadding="5" cellspacing="pixels">';
          $credit_report_full_details .= '<tr>';
          $credit_report_full_details .= '<td bgcolor="#ccc" style="text-align: center;"><b> PARTICULARS</b></td>';
          $credit_report_full_details .= '<td bgcolor="#ccc" style="text-align: center;"><b> VERIFIED INFORMATION</b></td>';
          $credit_report_full_details .= '</tr>';
          $credit_report_full_details .= '<tr>';
          $credit_report_full_details .= '<td bgcolor="#ccc" style="text-align: center;"><b> DOCUMENT PROVIDED</b></td>';
          $credit_report_full_details .= '<td>'.$doc_submited.'</td>';
          $credit_report_full_details .= '</tr>';
          $credit_report_full_details .= '<tr>';
          $credit_report_full_details .= '<td bgcolor="#ccc" style="text-align: center;"><b> MODE OF VERIFICATION</b></td>';
          $credit_report_full_details .= '<td>'.$mode_of_verification.'</td>';
          $credit_report_full_details .= '</tr>';
          $credit_report_full_details .= '<tr>';
          $credit_report_full_details .= '<td bgcolor="#ccc" style="text-align: center;"><b> REMARKS</b></td>';
          $credit_report_full_details .= '<td>'.$remarks.'</td>';
          $credit_report_full_details .= '</tr>';
                
                 
          $credit_report_full_details .= '</table>';


        $pdf->writeHTML($credit_report_full_details, true, false, false, false, '');

        $attachments = ($value['attachments'] != NULL && $value['attachments'] != '') ?  explode('||', $value['attachments']) : '';

        $credit_report_vendor_attachments = ($value['vendor_attachments'] != NULL && $value['vendor_attachments'] != '') ?  explode('||', $value['vendor_attachments']) : '';
 
               if(!empty($attachments))
		        {

		         $pdf->SetAutoPageBreak(false, 0);

		         for ($i = 0; $i < count($attachments); $i++) {
		            $pdf->AddPage();
		            $pdf->Cell(0, 0, 'Annexure',0, 1, 'C');
		            $images_explode = explode('/',$attachments[$i]);

		         $extension = strtolower(pathinfo($images_explode[2], PATHINFO_EXTENSION)); 

		         $pdf->Image(IP_SITE_URL.CREDIT_REPORT.$images_explode[1].'/'.$images_explode[2], $x, $y, $w, $h,  $extension , '', '', true, 150, '', false, false, false, false, false, false);
		           }    
		        }  
                
                if(!empty($credit_report_vendor_attachments))
		        {

		          $pdf->SetAutoPageBreak(false, 0);

		          for ($i = 0; $i < count($credit_report_vendor_attachments); $i++) {
		            $pdf->AddPage();
		            $pdf->Cell(0, 0, 'Annexure',0, 1, 'C');
		            

		          $extension = strtolower(pathinfo($credit_report_vendor_attachments[$i], PATHINFO_EXTENSION)); 

		           $pdf->Image(IP_SITE_URL.CREDIT_REPORT.'vendor_file'.'/'.$credit_report_vendor_attachments[$i], $x, $y, $w, $h,  $extension , '', '', true, 150, '', false, false, false, false, false, false);
		           }    
		        } 
          }
			
	   
	/*	else
		{
			foreach ($education_info as $key => $value) 
			{
				if($value['file_names'] != "")
				{
					$images_explode = explode(',', $value['file_names']);

					for ($i = 0; $i < count($images_explode); $i++) {
						
							$pdf->AddPage();
							$pdf->Cell(0, 11, 'Education Annexure',0, 1, 'C');
							$pdf->Image(EDUCATION_COM.$value['clientid'].'/'.$images_explode[$i], $x, $y, $w, $h, '', '', '', true, 150, '', false, false, false, false, false, false);
						}
				}
			}

			foreach ($employment_info as $key => $value) 
			{
				if($value['file_names'] != "")
				{
					$images_explode = explode(',', $value['file_names']);

					$pdf->SetAutoPageBreak(false, 0);

					for ($i = 0; $i < count($images_explode); $i++) {
						$pdf->AddPage();
						$pdf->Cell(0, 0, 'Employment Annexure',0, 1, 'C');
						
						$pdf->Image(EMPLOYMENT_COM.$value['clientid'].'/'.$images_explode[$i], $x, $y, $w, $h, '', '', '', true, 150, '', false, false, false, false, false, false);
					}
				}
			}

			foreach ($address_info as $key => $value) 
			{
				if($value['file_names'])
				{
					$images_explode = explode(',', $value['file_names']);
					
					$pdf->SetAutoPageBreak(TRUE, 0);

					for ($i=0; $i < count($images_explode); $i++) {

						$pdf->AddPage();
						$pdf->Cell(0, 0, 'Address Annexure',0, 1, 'C');
						$pdf->Image(ADDRESS_COM.$value['clientid'].'/'.$images_explode[$i], $x, $y, $w, $h, '', '', '', true, 150, '', false, false, false, false, false, false);
					}
				}
			}
			
			foreach ($court_info as $key => $value) 
			{
				if($value['file_names'])
				{
					$images_explode = explode(',', $value['file_names']);
					
					$pdf->SetAutoPageBreak(TRUE, 0);

					for ($i=0; $i < count($images_explode); $i++) {

						$pdf->AddPage();
						$pdf->Cell(0, 0, 'Court Record Verification Annexure',0, 1, 'C');
						$pdf->Image(COURT_VERIFICATION_COM.$value['clientid'].'/'.$images_explode[$i], $x, $y, $w, $h, '', '', '', true, 150, '', false, false, false, false, false, false);
					}
				}
			}

			foreach ($pcc_info as $key => $value) 
			{
				if($value['attchments_ver'])
				{
					$pdf->SetAutoPageBreak(TRUE, 0);
					$pdf->AddPage();
					$pdf->Cell(0, 0, 'PCC Annexure',0, 1, 'C');
					$pdf->Image(PCC_COM.$value['clientid'].'/'.$value['attchments_ver'], $x, $y, $w, $h, '', '', '', true, 150, '', false, false, false, false, false, false);
				}
			}

			foreach ($references_info as $key => $value) 
			{
				if($value['file_names'])
				{
					$images_explode = explode(',', $value['file_names']);
					
					$pdf->SetAutoPageBreak(TRUE, 0);

					for ($i=0; $i < count($images_explode); $i++) {

						$pdf->AddPage();
						$pdf->Cell(0, 0, 'Reference Annexure',0, 1, 'C');
						$pdf->Image(REFERENCES_COM.$value['clientid'].'/'.$images_explode[$i], $x, $y, $w, $h, '', '', '', true, 150, '', false, false, false, false, false, false);
					}
				}
			}
		}*/

		$pdf->SetFont($this->fontname, '', 14);

		$pdf->AddPage();
		
		$pdf->Write(0, '*** END OF REPORT ***', '', 0, 'C', true, 0, false, false, 0);

		$pdf->SetFont($this->fontname, '', 12);

		$end_of_file = '<br><div style="text-align:center;"></div><span style="font-weight: bold;">Disclaimer :</span> <span style="text-align:justify;">All services and reports are provided in a professional manner in accordance with industry standards. Except as expressly provided, Service Provider and its affiliates make no and disclaim any and all warranties and representations with respect to the services provided herein, whether such warranties and representations are express or implied in fact or by operation of law or otherwise, including without limitation implied warranties of merchantability and fitness for a particular purpose and implied warranties arising from the course of dealing or a course of performance with respect to the accuracy, validity, or completeness of any service or report. Furthermore, Service Provider and its affiliates expressly disclaim that the services will meet the clients needs and Service Provider and its affiliates express disclaim all such representations and warranties. Service Provider merely passes on to client information in the form of reports which Service Provider has obtained in respect of the subject of a background screening verification. Service Provider is not the author or creator of that information.Given the limitations, no responsibility will be taken by Service Provider for the consequences of client in reliance upon information contained in this information or report.However, Service Provider will provide reasonable procedures to protect against any false information being provided to the client. The information within the reports is strictly confidential and is intended to be for the sole purpose of the clients evaluation. The information and the reports are not intended for public dissemination.</span>';
	
		$pdf->writeHTMLCell(0, 8, '', '', $end_of_file, 0, 0, false, true, 10, false);

	//	$reportstatus = ($cand_info["overallstatus"] == 'WIP') ? "Interim_Report_" : "Final_Report_";

		//$pdf->Output($reportstatus.$cand_info['ClientRefNumber'].'_'.$cand_info['CandidateName'].'.pdf', 'I');

      ob_start();

         if($portal_type == 'admin')
        {
           $pdf->Output(ucwords($cand_info['ClientRefNumber']).'_'.ucwords($cand_info['CandidateName']).'_'.$reportstatus.'_Report'.'.pdf', 'D');
        }
        else
        {
        	$pdf->Output(ucwords($cand_info['ClientRefNumber']).'_'.ucwords($cand_info['CandidateName']).'_'.$reportstatus.'_Report'.'.pdf', 'I');
        }

		
		//$pdf->Output(SITE_BASE_PATH.UPLOAD_FOLDER.candidate_report_file.$reportstatus.$cand_info['CandidateName'].'.pdf', 'F');
		

	}
}