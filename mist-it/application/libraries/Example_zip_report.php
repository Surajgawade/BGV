<?php
require_once dirname(__FILE__).'/tcpdf_min/include/tcpdf_include.php';

// Extend the TCPDF class to create custom Header and Footer
class Example_zip_report extends TCPDF {

	//Page header
	var $fontname = '';
    
	function __construct()
	{

		parent::__construct();
    
	//	$this->fontname = TCPDF_FONTS::addTTFfont(APPPATH.'libraries/tcpdf_min/fonts/arial.ttf', 'TrueTypeUnicode', '', 96);


	}

	public function Header() {
   	
		$this->SetProtection(array('copy','modify'),null, null, 0, null);
              
		if(CLIENT_LOGOS != '')
		{

			$this->Image(CLIENT_LOGOS, 5, 5, 25, '', false, '', 'T', false, 300, 'L', false, false, 0, false, false, false);
		}
		if(CUSTOM_CLINT_ID != 53) {
			$image_file = 'assets/images/logo.jpeg';
			$this->Image($image_file, 5, 7, 50, '', 'jpeg', '', 'T', false, 300, 'R', false, false, 0, false, false, false);
		}
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
		$this->SetFont($this->fontname, 'I', 8);
		// Page number
		if(CUSTOM_CLINT_ID != 53) {
            $this->Cell(50, 10, WEBSITE, 0, false, 'L', 0, '', 0, false, 'T', 'M');  
            
            $this->Cell(90, 10, 'Confidential', 0, false, 'C', 0, '', 0, false, 'T', 'M'); 
            $this->Cell(50, 9, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M' );
        }
        else
        {
            $this->Cell(150, 10, 'Confidential', 0, false, 'C', 0, '', 0, false, 'T', 'M'); 
            $this->Cell(40, 9, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M' );
        }
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

        $social_media_info = $reports_data['social_media_info'];


		$pdf = new Example_zip_report(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

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

		// set marginss
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
		   // $pdf->setLanguageArray($l);
		}

		$pdf->AddPage();
		
		//$pdf->setCellMargins(0, 0, 0, 0);
        $pdf->setCellMargins(1, 1, 1, 1);

		$pdf->SetFont($this->fontname,'B',14);   

		$pdf->SetFillColor(237,237,237);


    if($cand_info["clientname"] == "credence resource management")
    {
       $overallstatus = overall_status_color($cand_info["overallstatus_value"]); 
    }
    else
    {
      if($cand_info["overallstatus_value"] == "Clear")
      {
        $overallstatus = '<td bgcolor="#4c9900" color = "#000000"><b>'.$cand_info["overallstatus_value"].'</b></td>';
      }
      elseif($cand_info["overallstatus_value"] == "Major Discrepancy")
      {
        $overallstatus = '<td bgcolor="#ff2300" color = "#000000"><b>'.$cand_info["overallstatus_value"].'</b></td>';
      }
      else
      {
        $overallstatus = "<td><b>".$cand_info["overallstatus_value"]."</b></td>";
      }
    }
		
		$reportstatus = ($cand_info["overallstatus"] == '1' || $cand_info["overallstatus"] == '2' ||  $cand_info["overallstatus"] == '5') ? "INTERIM" : "FINAL";

       $pdf->SetFont($this->fontname, '', 11);

       if(!empty($cand_info['overallclosuredate']) && $cand_info['overallclosuredate'] != "0000-00-00")
       {  
          $overallclosuredate =   date('d-M-Y', strtotime($cand_info['overallclosuredate'])); 
       }
       else 
       { 
          $overallclosuredate =   date('d-M-Y'); 
       }

       if($cand_info['clientid'] == '23' && $cand_info['entity'] == '68' && $cand_info['package'] == '152')
       {
          $client_package_name = ucwords($cand_info["clientname"]) . " ( ". ucwords($cand_info["package_name"]). " ) ";
       } 
       elseif($cand_info['clientid'] == '53')
       {
          $client_package_name = ucwords($cand_info["package_name"]);
       }
       else{
          $client_package_name = ucwords($cand_info["clientname"]);
       }
		
		
       
	//	$this->Image($client_image_file, 5, 7, 25, '', 'jpg', '', 'T', false, 300, 'L', false, false, 0, false, false, false);

		//$pdf->Cell(0, 11, strtoupper($cand_info["clientname"]),1, 1, 'C', 1, 0);

	//	$pdf->Cell(0, 11, "BACKGROUND VERIFICATION $reportstatus REPORT","LTRB EXT", 1, 'C', 1, 0);

       $dob = ($cand_info["DateofBirth"] != '') ? $cand_info["DateofBirth"] : 'NA';

      $client_name = '<style> table, td{ border: border: 1.3px solid black; }</style><table width="98%" cellpadding="5" cellspacing="pixels" cellpadding="5">';
      
     $cans_tbl = '<style>td {color: #5d5959;}</style><table cellpadding="4">
        <tr>
            <th bgcolor="#f2f2f2">CLIENT NAME</th>
            <th colspan="3">'.$client_package_name.' </th>
        </tr>
        <tr>
            <th bgcolor="#f2f2f2">CANDIDATE NAME</th>
            <th colspan="3"><strong>'.ucwords($cand_info["CandidateName"]).'</strong></th>
        </tr>
        <tr>
            <th bgcolor="#f2f2f2">'.COMPANYREFNO.'</th>
            <td>'.$cand_info["cmp_ref_no"].'</td>
            <th bgcolor="#f2f2f2"> CLIENT REF. NO</th>
            <td width="140px"> '.strtoupper($cand_info["ClientRefNumber"]).'</td>
        </tr>
        <tr>
            <th bgcolor="#f2f2f2">DATE INITIATED</th>
            <td>'.date('d-M-Y', strtotime($cand_info["caserecddate"])).'</td>
            <th bgcolor="#f2f2f2"> DATE OF BIRTH</th>
            <td width="140px"> '.date('d-M-Y', strtotime($dob)).'</td>
        </tr>
        <tr>
            <th bgcolor="#f2f2f2">DATE OF REPORT</th>
            <td>'.$overallclosuredate.'</td>
            <th bgcolor="#f2f2f2"> REPORT STATUS</th>
            '.$overallstatus.'
        </tr>
        </table><br><hr bgcolor="#052e70">';

        $x = 25;
        $y = '';
        $w = 155;
        $h = 170;

        
        $pdf->writeHTML($cans_tbl, true, false, false, false, '');

		
		$overallclosuredate = ($reportstatus == 'FINAL') ? date('d-M-Y', strtotime($cand_info["overallclosuredate"])) : 'NA';
		
		$x = 25;
		$y = '';
		$w = 155;
		$h = 170;
		

		   
          //  $pdf->writeHTML($cans_tbl, true, false, false, false, '');

           // $pdf->writeHTML($cans_tbl, true, false, false, false, '');

		//	$pdf->Cell(0, 8, 'EXECUTIVE SUMMARY', 1, 1, 'C');
        $pdf->SetFont($this->fontname,'B',12);
        $pdf->Cell(0, 4, 'EXECUTIVE SUMMARY', 0, 1, 'C');

        $pdf->SetFont($this->fontname, 'B', 11);

			$educ_tbl = '<style>th, td {text-align: left;border-top: 1px solid #000;border-bottom: 1px solid #000;}</style><table cellpadding="4" width="98%">
            <tr>
                <td width="33%" bgcolor="#f2f2f2">TYPE OF CHECK</td>
                <td width="50%" bgcolor="#f2f2f2">BRIEF DETAILS</td>
                <td width="15%" bgcolor="#f2f2f2">STATUS</td>
            </tr>';

			  $component_name  = json_decode($cand_info['component_name'],true);

        $component_name = array_map('strtoupper', $component_name);

    
      foreach ($education_info as $key => $value) 
      { 
        $value = array_map('ucwords', $value);
                $counter = ($key == 0) ? ''  : $key+1;
                $educ_tbl .= '<tr><td bgcolor="#f2f2f2" style="text-align: center;"><b>'.$component_name["eduver"].$counter.'</b></td>';
                $educ_tbl .= '<td>'.$value['qualification'].'</td>';
                if($value['verfstatus'] == "Major Discrepancy")
                {
                   if($cand_info["clientname"] == "credence resource management")
                   {
                     $educ_tbl .= '<td bgcolor="#ff2300">Red</td></tr>';
                   }
                   else
                   {
                     $educ_tbl .= '<td bgcolor="#ff2300">'.$value["verfstatus"].'</td></tr>';
                   }
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
                   if($cand_info["clientname"] == "credence resource management")
                   {

                       $educ_tbl .= '<td bgcolor="#4c9900">Green</td></tr>';
                   }
                   else
                   {
                       $educ_tbl .= '<td bgcolor="#4c9900">'.$value["verfstatus"].'</td></tr>';
                   }
                }
                else
                { 
                 $educ_tbl .= '<td bgcolor="#f2f2f2">'.$value["verfstatus"].'</td></tr>';
                }
      }
    
         
			foreach ($employment_info as $key => $value) 
			{	
				$value = array_map('ucwords', $value);
                $counter = ($key == 0) ? ''  : $key+1;


			    $educ_tbl .= '<tr><td bgcolor="#f2f2f2" style="text-align: center;"><b>'.$component_name["empver"].$counter.'</b></td>';
                $educ_tbl .= '<td>'.$value["coname"].'</td>';

                if($value['verfstatus'] == "Major Discrepancy")
                {    
                    if($cand_info["clientname"] == "credence resource management")
                   {
                     $educ_tbl .= '<td bgcolor="#ff2300">Red</td></tr>';
                   }
                   else
                   { 
                    $educ_tbl .= '<td bgcolor="#ff2300">'.$value["verfstatus"].'</td></tr>';
                   }
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
                   if($cand_info["clientname"] == "credence resource management")
                   {

                       $educ_tbl .= '<td bgcolor="#4c9900">Green</td></tr>';
                   }
                   else
                   {
                    $educ_tbl .= '<td bgcolor="#4c9900">'.$value["verfstatus"].'</td></tr>';
                   }
                }
                else
                { 
                $educ_tbl .= '<td bgcolor="#f2f2f2">'.$value["verfstatus"].'</td></tr>';
                }


			}
 
			foreach ($address_info as $key => $value) 
			{	
				
				 $full_address = $value['address'].' '.$value['city'].' '.$value['pincode'].' '.$value['address_state'];
                $value = array_map('ucwords', $value);
                $counter = ($key == 0) ? ''  : $key+1;
                $educ_tbl .='<tr><td bgcolor="#f2f2f2" style="text-align: center;"><b>'.$component_name["addrver"].$counter.'</b></td>';
                $educ_tbl .= '<td>'.$full_address.'</td>';
                if($value['verfstatus'] == "Major Discrepancy")
                {  

                   if($cand_info["clientname"] == "credence resource management")
                   {
                     $educ_tbl .= '<td bgcolor="#ff2300">Red</td></tr>';
                   }
                   else
                   {
                    $educ_tbl .= '<td bgcolor="#ff2300">'.$value["verfstatus"].'</td></tr>';
                   }
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
                   if($cand_info["clientname"] == "credence resource management")
                   {

                       $educ_tbl .= '<td bgcolor="#4c9900">Green</td></tr>';
                   }
                   else
                   {
                     $educ_tbl .= '<td bgcolor="#4c9900">'.$value["verfstatus"].'</td></tr>';
                   }
                }
                else
                { 
                 $educ_tbl .= '<td bgcolor="#f2f2f2">'.$value["verfstatus"].'</td></tr>';
                }
			} 

         foreach ($court_info  as $key => $value)  {
                $value = array_map('ucwords', $value);
                $counter = ($key == 0) ? ''  : $key+1;
                $court_full_address = $value['street_address'].' '.$value['city'].' '.$value['pincode'].' '.$value['state'];

                $educ_tbl .= '<tr><td bgcolor="#f2f2f2" style="text-align: center;"><b>'.$component_name["courtver"].$counter.'</b></td>';
                $educ_tbl .= '<td>'.$court_full_address.'</td>';
                if($value['verfstatus'] == "Major Discrepancy")
                {
                   if($cand_info["clientname"] == "credence resource management")
                   {
                     $educ_tbl .= '<td bgcolor="#ff2300">Red</td></tr>';
                   }
                   else
                   {
                     $educ_tbl .= '<td bgcolor="#ff2300">'.$value["verfstatus"].'</td></tr>';
                   }
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
                   if($cand_info["clientname"] == "credence resource management")
                   {

                       $educ_tbl .= '<td bgcolor="#4c9900">Green</td></tr>';
                   }
                   else
                   {

                     $educ_tbl .= '<td bgcolor="#4c9900">'.$value["verfstatus"].'</td></tr>';
                   }
                }
                else
                { 
                 $educ_tbl .= '<td bgcolor="#f2f2f2">'.$value["verfstatus"].'</td></tr>';
                }
              }

              
     
          foreach ($global_db_info  as $key => $value)  {
                $value = array_map('ucwords', $value);
                $counter = ($key == 0) ? ''  : $key+1;
                $full_address = $value['street_address'].' '.$value['city'].' '.$value['pincode'].' '.$value['state'];

                if(!empty($value['street_address']) && !empty($value['city']) && !empty($value['pincode']) && !empty($value['state']))
                {
                   $global_full_address = $full_address;
                }
                else
                {
                    $global_full_address = $court_full_address;
                }
   

                $educ_tbl .='<tr><td bgcolor="#f2f2f2" style="text-align: center;"><b>'.$component_name["globdbver"].$counter.'</b></td>';
                $educ_tbl .='<td>'.$global_full_address.'</td>';
                if($value['verfstatus'] == "Major Discrepancy")
                {
                    if($cand_info["clientname"] == "credence resource management")
                   {
                     $educ_tbl .= '<td bgcolor="#ff2300">Red</td></tr>';
                   }
                   else
                   {
                      $educ_tbl .= '<td bgcolor="#ff2300">'.$value["verfstatus"].'</td></tr>';
                   }
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
                    if($cand_info["clientname"] == "credence resource management")
                   {

                       $educ_tbl .= '<td bgcolor="#4c9900">Green</td></tr>';
                   }
                   else
                   {
                     $educ_tbl .= '<td bgcolor="#4c9900">'.$value["verfstatus"].'</td></tr>';
                   }
                }
                else
                { 
                 $educ_tbl .= '<td bgcolor="#f2f2f2">'.$value["verfstatus"].'</td></tr>';
                }
          }
      

	

			       foreach ($references_info  as $key => $value)  {
                $value = array_map('ucwords', $value);
                $counter = ($key == 0) ? ''  : $key+1;
                $educ_tbl .= '<tr><td bgcolor="#f2f2f2" style="text-align: center;"><b>'.$component_name["refver"].$counter.'</b></td>';
                $educ_tbl .= '<td>'.$value['name_of_reference'].' </td>';
                if($value['verfstatus'] == "Major Discrepancy")
                {
                    if($cand_info["clientname"] == "credence resource management")
                   {
                     $educ_tbl .= '<td bgcolor="#ff2300">Red</td></tr>';
                   }
                   else
                   {
                     $educ_tbl .= '<td bgcolor="#ff2300">'.$value["verfstatus"].'</td></tr>';
                   }
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
                   if($cand_info["clientname"] == "credence resource management")
                   {

                       $educ_tbl .= '<td bgcolor="#4c9900">Green</td></tr>';
                   }
                   else
                   {
                    $educ_tbl .= '<td bgcolor="#4c9900">'.$value["verfstatus"].'</td></tr>';
                   }
                }
                else
                { 
                 $educ_tbl .= '<td bgcolor="#f2f2f2">'.$value["verfstatus"].'</td></tr>';
                }
              }
              
             
              foreach ($drugs_info as $key => $value)  {
                $value = array_map('ucwords', $value);
                $counter = ($key == 0) ? ''  : $key+1;
                $educ_tbl .='<tr><td bgcolor="#f2f2f2" style="text-align: center;"><b>'.$component_name["narcver"].$counter.'</b></td>';
                $educ_tbl .='<td>'.$value['drug_test_code'].'</td>';
                if($value['verfstatus'] == "Major Discrepancy")
                {
                   if($cand_info["clientname"] == "credence resource management")
                   {
                     $educ_tbl .= '<td bgcolor="#ff2300">Red</td></tr>';
                   }
                   else
                   {
                      $educ_tbl .= '<td bgcolor="#ff2300">'.$value["verfstatus"].'</td></tr>';
                   }
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
                   if($cand_info["clientname"] == "credence resource management")
                   {

                       $educ_tbl .= '<td bgcolor="#4c9900">Green</td></tr>';
                   }
                   else
                   {
                    $educ_tbl .= '<td bgcolor="#4c9900">'.$value["verfstatus"].'</td></tr>';
                   }
                }
                else
                { 
                 $educ_tbl .= '<td bgcolor="#f2f2f2">'.$value["verfstatus"].'</td></tr>';
                }
              }
          
              foreach ($pcc_info as $key => $value)  {
                $value = array_map('ucwords', $value);
                $counter = ($key == 0) ? ''  : $key+1;
                $full_address = $value['street_address'].' '.$value['city'].' '.$value['state'].' '.$value['pincode'];
                $educ_tbl .='<tr><td bgcolor="#f2f2f2" style="text-align: center;"><b>'.$component_name["crimver"].$counter.'</b></td>';
                $educ_tbl .= '<td>'.$full_address.'</td>';
                if($value['verfstatus'] == "Major Discrepancy")
                {
                   if($cand_info["clientname"] == "credence resource management")
                   {
                     $educ_tbl .= '<td bgcolor="#ff2300">Red</td></tr>';
                   }
                   else
                   {
                     $educ_tbl .= '<td bgcolor="#ff2300">'.$value["verfstatus"].'</td></tr>';
                   }
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
                   if($cand_info["clientname"] == "credence resource management")
                   {
                       $educ_tbl .= '<td bgcolor="#4c9900">Green</td></tr>';
                   }
                   else
                   {
                     $educ_tbl .= '<td bgcolor="#4c9900">'.$value["verfstatus"].'</td></tr>';
                   }
                }
                else
                { 
                 $educ_tbl .= '<td bgcolor="#f2f2f2">'.$value["verfstatus"].'</td></tr>';
                }
              }


              foreach ($identity_info as $key => $value)  {
                $value = array_map('ucwords', $value);
                $counter = ($key == 0) ? ''  : $key+1;
                $educ_tbl .= '<tr><td bgcolor="#f2f2f2" style="text-align: center;"><b>'.$component_name["identity"].$counter.'</b></td>';
                $educ_tbl .= '<td>'.$value['doc_submited'].'</td>';
                 if($value['verfstatus'] == "Major Discrepancy")
                {
                   if($cand_info["clientname"] == "credence resource management")
                   {
                     $educ_tbl .= '<td bgcolor="#ff2300">Red</td></tr>';
                   }
                   else
                   {
                    $educ_tbl .= '<td bgcolor="#ff2300">'.$value["verfstatus"].'</td></tr>';
                   }
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
                   if($cand_info["clientname"] == "credence resource management")
                   {
                       $educ_tbl .= '<td bgcolor="#4c9900">Green</td></tr>';
                   }
                   else
                   {
                     $educ_tbl .= '<td bgcolor="#4c9900">'.$value["verfstatus"].'</td></tr>';
                   }
                }
                else
                { 
                 $educ_tbl .= '<td bgcolor="#f2f2f2">'.$value["verfstatus"].'</td></tr>';
                }
              }
             

             foreach ($credit_report_info as $key => $value)  {
                $value = array_map('ucwords', $value);
                $counter = ($key == 0) ? ''  : $key+1;
                $educ_tbl .= '<tr><td bgcolor="#f2f2f2" style="text-align: center;"><b>'.$component_name["cbrver"].$counter.'</b></td>';
                $educ_tbl .= '<td>'.$value['doc_submited'].'</td>';
                 if($value['verfstatus'] == "Major Discrepancy")
                {
                   if($cand_info["clientname"] == "credence resource management")
                   {
                     $educ_tbl .= '<td bgcolor="#ff2300">Red</td></tr>';
                   }
                   else
                   {
                      $educ_tbl .= '<td bgcolor="#ff2300">'.$value["verfstatus"].'</td></tr>';
                   }
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
                    if($cand_info["clientname"] == "credence resource management")
                   {
                       $educ_tbl .= '<td bgcolor="#4c9900">Green</td></tr>';
                   }
                   else
                   {
                      $educ_tbl .= '<td bgcolor="#4c9900">'.$value["verfstatus"].'</td></tr>';
                   }
                }
                else
                { 
                 $educ_tbl .= '<td bgcolor="#f2f2f2">'.$value["verfstatus"].'</td></tr>';
                }
              }
        
             foreach ($social_media_info  as $key => $value)  {
                $value = array_map('ucwords', $value);
                $counter = ($key == 0) ? ''  : $key+1;
                $educ_tbl .= '<tr><td bgcolor="#f2f2f2" style="text-align: center;"><b>'.$component_name["social_media"].$counter.'</b></td>';
                $educ_tbl .= '<td> Social Media </td>';
                if($value['verfstatus'] == "Major Discrepancy")
                {
                    if($cand_info["clientname"] == "credence resource management")
                   {
                     $educ_tbl .= '<td bgcolor="#ff2300">Red</td></tr>';
                   }
                   else
                   {
                     $educ_tbl .= '<td bgcolor="#ff2300">'.$value["verfstatus"].'</td></tr>';
                   }
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
                   if($cand_info["clientname"] == "credence resource management")
                   {

                       $educ_tbl .= '<td bgcolor="#4c9900">Green</td></tr>';
                   }
                   else
                   {
                    $educ_tbl .= '<td bgcolor="#4c9900">'.$value["verfstatus"].'</td></tr>';
                   }
                }
                else
                { 
                 $educ_tbl .= '<td bgcolor="#f2f2f2">'.$value["verfstatus"].'</td></tr>';
                }
            }
           
          

			$educ_tbl .='</table>';

			$pdf->writeHTML($educ_tbl, true, false, false, false, '');

			$pdf->SetFont($this->fontname,'B',14);
            $pdf->Cell(0, 8, 'LEGEND', 0, 1, 'C');

            $pdf->SetFont($this->fontname, '', 11);
            
            $template = '<style>{text-align: centre;}</style><table cellpadding="8" width="98%">
            <tr>
                <td width="7%"><img src="assets/images/write.png" width="20" height="20" ></td> 
                <td width="15%" bgcolor="#10840C" >GREEN</td>
                <td width="78%">Details provided by the candidate are correct / matching.</td>
            </tr>
            <tr>
                <td width="7%"><img src="assets/images/wrong.png" width="20" height="20"></td>
                <td width="15%" bgcolor="#ed0000" >RED</td>
                <td width="78%">Details provided by the candidate are incorrect / not matching.</td>
            </tr>

            
            <tr>
                <td width="7%"> <img src="assets/images/exclamation.png" width="20" height="20" ></td>
                <td width="15%" bgcolor="#e58210" >ORANGE</td>
                <td width="78%">Details provided require supporting documents / additional information required to verify / Unable to Verify / Source refuse to disclose information.</td>
            </tr>
            </table>';
            $pdf->writeHTML($template, true, false, false, false, '');



    foreach ($education_info as $key => $value) 
    { 
       $pdf->AddPage();

       $counter = ($key == 0) ? ''  : $key+1;
        
       $attachments = ($value['attachments'] != NULL && $value['attachments'] != '') ?  explode('||', $value['attachments']) : '';

        $education_vendor_attachments = ($value['vendor_attachments'] != NULL && $value['vendor_attachments'] != '') ?  explode('||', $value['vendor_attachments']) : '';


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


       
         $educ_name = '<style>th, td {text-align: left;}</style><table width="97%" cellpadding="5" width="98%">';
         $educ_name .= '<tr>';
         $educ_name .= '<td bgcolor="#f2f2f2" style="text-align: center;height: 35px;"><b>'.$component_name["eduver"].' VERIFICATION '.$counter.'</b></td>';
         $educ_name .= '</tr>';    
         $educ_name .= '</table>';

          $pdf->writeHTML($educ_name, true, false, false, false, '');


         $educ_full_info = '<style>th, td {text-align: left;border-top: 1px solid #000;border-bottom: 1px solid #000;}</style><table width="97%" cellpadding="5" width="96%">';
         $educ_full_info .= '<tr>';
         $educ_full_info .= '<td bgcolor="#f2f2f2" style="text-align: center;"><b> COMPONENTS</b></td>';
         $educ_full_info .= '<td bgcolor="#f2f2f2" style="text-align: center;"><b> INFORMATION PROVIDED</b></td>';
         $educ_full_info .= '<td bgcolor="#f2f2f2" style="text-align: center;"><b> INFORMATION VERIFIED</b></td>';
         $educ_full_info .= '</tr>'; 
         $educ_full_info .= '<tr>';
         $educ_full_info .= '<td bgcolor="#f2f2f2" style="text-align: center;"><b>NAME OF THE COLLEGE / UNIVERSITY</b></td>';
         $educ_full_info .= '<td>'.$school_college.'</td>';
         $educ_full_info .= '<td>'.$verified.'</td>';
         $educ_full_info .= '</tr>';
         $educ_full_info .= '<tr>';
         $educ_full_info .= '<td bgcolor="#f2f2f2" style="text-align: center;"><b>QUALIFICATION ATTAINED</b></td>';
         $educ_full_info .= '<td>'.$value['qualification'].'</td>';
         $educ_full_info .= '<td>'.$res_qualification.'</td>';
         $educ_full_info .= '</tr>';
         $educ_full_info .= '<tr>';
         $educ_full_info .= '<td bgcolor="#f2f2f2" style="text-align: center;"><b>YEAR OF PASSING</b></td>';
         $educ_full_info .= '<td>'.$value["year_of_passing"].'</td>';
         $educ_full_info .= '<td>'.$res_year_of_passing.'</td>';
         $educ_full_info .= '</tr>';
         $educ_full_info .= '</table>';
         $pdf->writeHTML($educ_full_info, true, false, false, false, '');


    
       $educ_full_details = '<style>th, td {text-align: left;border-top: 1px solid #000;border-bottom: 1px solid #000;}</style><table width="97%" cellpadding="5" width="97%">';
         $educ_full_details .= '<tr>';
         $educ_full_details .= '<td bgcolor="#f2f2f2" style="text-align: center;"><b> DETAILS</b></td>';
         $educ_full_details .= '<td bgcolor="#f2f2f2" style="text-align: center;"><b> FINDINGS </b></td>';
         $educ_full_details .= '</tr>'; 
        // $educ_full_details .= '<tr>';
        // $educ_full_details .= '<td bgcolor="#f2f2f2" style="text-align: center;"><b> ADDITIONAL COMMENTS (IF ANY)</b></td>';
        // $educ_full_details .= '<td >'.$value['res_remarks'].'</td>';
        // $educ_full_details .= '</tr>';
         $educ_full_details .= '<tr>';
         $educ_full_details .= '<td bgcolor="#f2f2f2" style="text-align: center;"><b>VERIFIED BY</b></td>';
         $educ_full_details .= '<td>'.$verf_details.'</td>';
         $educ_full_details .= '</tr>';
         $educ_full_details .= '<tr>';
         $educ_full_details .= '<td bgcolor="#f2f2f2" style="text-align: center;"><b>MODE OF VERIFICATION</b></td>';
         $educ_full_details .= '<td>'.$res_mode_of_verification.'</td>';
         $educ_full_details .= '</tr>';
         $educ_full_details .= '<tr>';
         $educ_full_details .= '<td bgcolor="#f2f2f2" style="text-align: center;"><b>REMARKS</b></td>';
         $educ_full_details .= '<td>'.$res_remarks.'</td>';
         $educ_full_details .= '</tr>';
         $educ_full_details .= '</table>';        


         $pdf->writeHTML($educ_full_details, true, false, false, false, '');

        
            if(!empty($attachments))
            {

              $pdf->SetAutoPageBreak(false, 0);

              for ($i = 0; $i < count($attachments); $i++) {
                $pdf->AddPage();
                $pdf->Cell(0, 0, 'Annexure',0, 1, 'C');
                $images_explode = explode('/',$attachments[$i]);

              $extension = strtolower(pathinfo($images_explode[2], PATHINFO_EXTENSION)); 

               $pdf->Image(SITE_BASE_PATH.EDUCATION.$images_explode[1].'/'.$images_explode[2], $x, $y, $w, $h,  $extension , '', '', true, 150, '', false, false, false, false, false, false);
               }    
            }

            if(!empty($education_vendor_attachments))
            {

              $pdf->SetAutoPageBreak(false, 0);

              for ($i = 0; $i < count($education_vendor_attachments); $i++) {
                $pdf->AddPage();
                $pdf->Cell(0, 0, 'Annexure',0, 1, 'C');
                

              $extension = strtolower(pathinfo($education_vendor_attachments[$i], PATHINFO_EXTENSION)); 

               $pdf->Image(SITE_BASE_PATH.EDUCATION.'vendor_file'.'/'.$education_vendor_attachments[$i], $x, $y, $w, $h,  $extension , '', '', true, 150, '', false, false, false, false, false, false);
               }    
            } 
            
            if(!empty($university_attachments))
            {

              $pdf->SetAutoPageBreak(false, 0);

              for ($i = 0; $i < count($university_attachments); $i++) {
                $pdf->AddPage();
                $pdf->Cell(0, 0, 'Annexure',0, 1, 'C');
                
              $images_explode_university = explode('/',$university_attachments[$i]);

              $extension = strtolower(pathinfo($images_explode_university[1], PATHINFO_EXTENSION)); 

               $pdf->Image(SITE_BASE_PATH.UNIVERSITY_PIC.$images_explode_university[1], $x, $y, $w, $h,  $extension , '', '', true, 150, '', false, false, false, false, false, false);
               }    
            }     
      }      


    
      foreach ($employment_info as $key => $value) 
			{	
				$pdf->AddPage();

				$counter = ($key == 0) ? ''  : $key+1;

			//	$pdf->Cell(0, 11, "EMPLOYMENT VERIFICATION - ".$key ,1, 1, 'C', 1, 0);
        $attachments = ($value['attachments'] != NULL && $value['attachments'] != '') ?  explode('||', $value['attachments']) : '';

        $employment_vendor_attachments = ($value['vendor_attachments'] != NULL && $value['vendor_attachments'] != '') ?  explode('||', $value['vendor_attachments']) : '';


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
                    $res_remarks = $value['insuff_raise_remark']; 
                }
                else
                {
                    $res_remarks = ($Verified_status == "NA") ? $res_remarks :  $res_remarks;
                }

                $emp_name = '<style>th, td {text-align: left;}</style><table width="98%" cellpadding="5">';
               
                $emp_name .='<tr>';
                $emp_name .= '<td bgcolor="#f2f2f2" style="text-align: center;height: 35px;" ><b>'.$component_name["empver"].' VERIFICATION '.$counter.'</b></td>';
                $emp_name .= '</tr>';    
                $emp_name .= '</table>';

                $pdf->writeHTML($emp_name, true, false, false, false, '');


                $emp_full_info = '<style>th, td {text-align: left;border-top: 1px solid #000;border-bottom: 1px solid #000;}</style><table width="96%" cellpadding="5">';
                $emp_full_info .= '<tr>';
                $emp_full_info .= '<td bgcolor="#f2f2f2" style="text-align: center;"><b> COMPONENTS</b></td>';
                $emp_full_info .= '<td bgcolor="#f2f2f2" style="text-align: center;"><b> INFORMATION PROVIDED</b></td>';
                $emp_full_info .= '<td bgcolor="#f2f2f2" style="text-align: center;"><b> INFORMATION VERIFIED</b></td>';
                $emp_full_info .= '</tr>'; 
                $emp_full_info .= '<tr>';
                 
                $emp_full_info .=  '<td bgcolor="#f2f2f2" style="text-align: center;"><b>NAME OF THE COMPANY</b></td>';
                $emp_full_info .=  '<td>'.$value["coname"].'</td>';
                $emp_full_info .=  '<td>'.$verified_company.'</td>';
                $emp_full_info .=  '</tr>';
                $emp_full_info .=  '<tr>';
                $emp_full_info .=  '<td bgcolor="#f2f2f2" style="text-align: center;"><b>EMPLOYEE ID</b></td>';
                $emp_full_info .=  '<td>'.$empid.'</td>';
                $emp_full_info .=  '<td>'.$res_empid.'</td>';
                $emp_full_info .=  '</tr>';
                $emp_full_info .=  '<tr>';
                $emp_full_info .=  '<td bgcolor="#f2f2f2" style="text-align: center;"><b>PERIOD OF EMPLOYMENT</b></td>';
                $emp_full_info .=  '<td>'.$period_of_stay1.'</td>';
                $emp_full_info .=  '<td>'.$verified.'</td>';
                $emp_full_info .=  '</tr>';
                $emp_full_info .=  '<tr>';
                $emp_full_info .=  '<td bgcolor="#f2f2f2" style="text-align: center;"><b>DESIGNATION</b></td>';
                $emp_full_info .=  '<td>'.$designation.'</td>';
                $emp_full_info .=  '<td>'.$emp_designation.'</td>';
                $emp_full_info .=  '</tr>';
                $emp_full_info .=  '<tr>';
                $emp_full_info .=  '<td bgcolor="#f2f2f2" style="text-align: center;"><b>REMUNERATION</b></td>';
                $emp_full_info .=  '<td>'.$remuneration.'</td>';
                $emp_full_info .=  '<td>'.$res_remuneration.'</td>';
                $emp_full_info .=  '</tr>';
                $emp_full_info .=  '<tr>';
                $emp_full_info .=  '<td bgcolor="#f2f2f2" style="text-align: center;"><b>REPORTING MANAGER</b></td>';
                $emp_full_info .=  '<td>'.$r_manager_name.'</td>';
                $emp_full_info .=  '<td>'.$reportingmanager.'</td>';
                $emp_full_info .=  '</tr>';
                $emp_full_info .=  '<tr>';
                $emp_full_info .=  '<td bgcolor="#f2f2f2" style="text-align: center;"><b>REASON OF LEAVING</b></td>';
                $emp_full_info .=  '<td>'.$reasonforleaving.'</td>';
                $emp_full_info .=  '<td>'.$res_reasonforleaving.'</td>';
                $emp_full_info .=  '</tr>';
                $emp_full_info .=  '</table>';

                $pdf->writeHTML($emp_full_info, true, false, false, false, '');

                $emp_full_details =  '<style>th, td {text-align: left;border-top: 1px solid #000;border-bottom: 1px solid #000;}</style><table width="97%" cellpadding="5">';
                $emp_full_details .=  '<tr>';
                $emp_full_details .=  '<td bgcolor="#f2f2f2" style="text-align: center;"><b> DETAILS</b></td>';
                $emp_full_details .=  '<td bgcolor="#f2f2f2" style="text-align: center;"><b> FINDINGS</b></td>';
                $emp_full_details .=  '</tr>'; 
                $emp_full_details .=  '<tr>';
                $emp_full_details .=  '<td bgcolor="#f2f2f2" style="text-align: center;"><b>ANY INTEGRITY/DISCIPLINARY ISSUES</b></td>';
                $emp_full_details .=  '<td>'.$info_integrity_disciplinary_issue.'</td>';
                $emp_full_details .=  '</tr>';
                $emp_full_details .=  '<tr>';
                $emp_full_details .=  '<td bgcolor="#f2f2f2" style="text-align: center;"><b>EXIT FORMALITIES COMPLETED</b></td>';
                $emp_full_details .=  '<td>'.$info_exitformalities.'</td>';
                $emp_full_details .=  '</tr>';
            
                $emp_full_details .=  '<tr>';
                $emp_full_details .=  '<td bgcolor="#f2f2f2" style="text-align: center;"><b>THE COMPANY IS REGISTERED  IN  MCA?</b></td>';
                $emp_full_details .=  '<td>'.$mcaregn.'</td>';
                $emp_full_details .=  '</tr>';
                $emp_full_details .=  '<tr>';
                $emp_full_details .=  '<td bgcolor="#f2f2f2" style="text-align: center;"><b>DOMAIN NAME</b></td>';
                $emp_full_details .=  '<td>'.$domainname.'</td>';
                $emp_full_details .=  '</tr>';
                $emp_full_details .=  '<tr>';
                $emp_full_details .=  '<td bgcolor="#f2f2f2" style="text-align: center;"><b>THE COMPANY IS LISTED IN JUSTDIAL AND INTERNET SEARCHES?</b></td>';
                $emp_full_details .=  '<td>'.$justdialwebcheck.'</td>';
                $emp_full_details .=  '</tr>';
                $emp_full_details .=  '<tr>';
                $emp_full_details .=  '<td bgcolor="#f2f2f2" style="text-align: center;"><b>IS IT FAMILY OWNED  BUSSINESS?</b></td>';
                $emp_full_details .=  '<td>'.$fmlyowned.'</td>';
                $emp_full_details .=  '</tr>';

                $emp_full_details .=  '<tr>';
                $emp_full_details .=  '<td bgcolor="#f2f2f2" style="text-align: center;"><b>VERIFIED BY</b></td>';
                $emp_full_details .=  '<td>'.$verifiers_details.'</td>';
                $emp_full_details .=  '</tr>';
                $emp_full_details .=  '<tr>';
                $emp_full_details .=  '<td bgcolor="#f2f2f2" style="text-align: center;"><b>MODE OF VERIFICATION</b></td>';
                $emp_full_details .=  '<td>'.$modeofverification.'</td>';
                $emp_full_details .=  '</tr>';
                $emp_full_details .=  '<tr>';
                $emp_full_details .=  '<td bgcolor="#f2f2f2" style="text-align: center;"><b>REMARKS</b></td>';
                $emp_full_details .=  '<td>'.$res_remarks.'</td>';
                $emp_full_details .=  '</tr>';
                $emp_full_details .=  '</table>';
     // $pdf->writeHTML($educ_tbl, true, false, false, false, '');

				$pdf->writeHTML($emp_full_details, true, false, false, false, '');

          
		
           if(!empty($attachments))
		        {

		          $pdf->SetAutoPageBreak(false, 0);

		          for ($i = 0; $i < count($attachments); $i++) {
		            $pdf->AddPage();
		            $pdf->Cell(0, 0, 'Annexure',0, 1, 'C');
		            $images_explode = explode('/',$attachments[$i]);

		          $extension = strtolower(pathinfo($images_explode[2], PATHINFO_EXTENSION)); 
     
		           $pdf->Image(SITE_BASE_PATH.EMPLOYMENT.$images_explode[1].'/'.$images_explode[2], $x, $y, $w, $h,  $extension , '', '', true, 150, '', false, false, false, false, false, false);
		           }    
		        }
		        if(!empty($employment_vendor_attachments))
		        {

		           $pdf->SetAutoPageBreak(false, 0);

		            for ($i = 0; $i < count($employment_vendor_attachments); $i++) {
				        $pdf->AddPage();
				        $pdf->Cell(0, 0, 'Annexure',0, 1, 'C');
				            

				        $extension = strtolower(pathinfo($employment_vendor_attachments[$i], PATHINFO_EXTENSION)); 

				        $pdf->Image(SITE_BASE_PATH.EMPLOYMENT.'vendor_file'.'/'.$employment_vendor_attachments[$i], $x, $y, $w, $h,  $extension , '', '', true, 150, '', false, false, false, false, false, false);
				    }    
		        }  
			}



     
       foreach ($address_info as $key => $value) 
      {
        $pdf->AddPage();
       // $pdf->Cell(0, 11, "ADDRESS VERIFICATION - ".$key ,1, 1, 'C', 1, 0);

        $counter = ($key == 0) ? ''  : $key+1;

        $attachments = ($value['add_attachments'] != NULL && $value['add_attachments'] != '') ?  explode('||', $value['add_attachments']) : '';

        $address_vendor_attachments = ($value['vendor_attachments'] != NULL && $value['vendor_attachments'] != '') ?  explode('||', $value['vendor_attachments']) : '';


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



          $address_name = '<style>th, td {text-align: left;}</style><table width="97%" cellpadding="5" width="98%">';
          $address_name .='<tr>';
          $address_name .= '<td bgcolor="#f2f2f2" style="text-align: center;height: 35px;" ><b>'.$component_name["addrver"].' VERIFICATION '.$counter.'</b></td>';
          $address_name .= '</tr>';    
          $address_name .= '</table>';

          $pdf->writeHTML($address_name, true, false, false, false, '');

          $address_full_info = '<style>th, td {text-align: left;border-top: 1px solid #000;border-bottom: 1px solid #000;}</style><table width="96%" cellpadding="5">';
          $address_full_info .= '<tr>';
          $address_full_info .= '<td bgcolor="#f2f2f2" style="text-align: center;"><b> COMPONENTS</b></td>';
          $address_full_info .= '<td bgcolor="#f2f2f2" style="text-align: center;"><b> INFORMATION PROVIDED</b></td>';
          $address_full_info .= '<td bgcolor="#f2f2f2" style="text-align: center;"><b> INFORMATION VERIFIED</b></td>';
          $address_full_info .= '</tr>'; 
          $address_full_info .= '<tr>';
          $address_full_info .= '<td bgcolor="#f2f2f2" style="text-align: center;"><b> ADDRESS</b> </td>';
          $address_full_info .= '<td>'.$full_address.'</td>';
          $address_full_info .= '<td>'.$verified_address_status.'</td>';
          $address_full_info .= '</tr>';
          $address_full_info .= '<tr>';
          $address_full_info .= '<td bgcolor="#f2f2f2" style="text-align: center;"><b>PERIOD OF STAY</b></td>';
          $address_full_info .= '<td>'.$period_of_stay.'</td>';
          $address_full_info .= '<td>'.$verified.'</td>';
          $address_full_info .= '</tr>';
          $address_full_info .= '</table>';

          $pdf->writeHTML($address_full_info, true, false, false, false, '');

          $address_full_details = '<style>th, td {text-align: left;border-top: 1px solid #000;border-bottom: 1px solid #000;}</style><table width="97%" cellpadding="5">';
          $address_full_details .= '<tr>';
          $address_full_details .= '<td bgcolor="#f2f2f2" style="text-align: center;"><b> DETAILS</b></td>';
          $address_full_details .= '<td bgcolor="#f2f2f2" style="text-align: center;"><b> FINDINGS</b></td>';
          $address_full_details .= '</tr>'; 
          $address_full_details .= '<tr>';
          $address_full_details .= '<td bgcolor="#f2f2f2" style="text-align: center;"><b>VERIFIED BY</b></td>';
          $address_full_details .= '<td>'.$verified_by.'</td>';
          $address_full_details .= '</tr>';
          $address_full_details .= '<tr>';
          $address_full_details .= '<td bgcolor="#f2f2f2" style="text-align: center;"><b>MODE OF VERIFICATION</b></td>';
          $address_full_details .= '<td>'.$mode_of_verification.'</td>';
          $address_full_details .= '</tr>';
          $address_full_details .= '<tr>';
          $address_full_details .= '<td bgcolor="#f2f2f2" style="text-align: center;"><b>REMARKS</b></td>';
          $address_full_details .= '<td>'.$res_remarks.'</td>';
          $address_full_details .= '</tr>';
               
          $address_full_details .= '</table>';

        $pdf->writeHTML($address_full_details, true, false, false, false, '');

        if(!empty($attachments))
        {

          $pdf->SetAutoPageBreak(false, 0);

          for ($i = 0; $i < count($attachments); $i++) {
            $pdf->AddPage();
            $pdf->Cell(0, 0, 'Annexure',0, 1, 'C');
            $images_explode = explode('/',$attachments[$i]);

          $extension = strtolower(pathinfo($images_explode[2], PATHINFO_EXTENSION)); 

           $pdf->Image(SITE_BASE_PATH.ADDRESS.$images_explode[1].'/'.$images_explode[2], $x, $y, $w, $h,  $extension , '', '', true, 150, '', false, false, false, false, false, false);
           }    
        }

        if(!empty($address_vendor_attachments))
		   {

		    $pdf->SetAutoPageBreak(false, 0);

		    for ($i = 0; $i < count($address_vendor_attachments); $i++) {
		        $pdf->AddPage();
		        $pdf->Cell(0, 0, 'Annexure',0, 1, 'C');
		            

		        $extension = strtolower(pathinfo($address_vendor_attachments[$i], PATHINFO_EXTENSION)); 

		        $pdf->Image(SITE_BASE_PATH.ADDRESS.'vendor_file'.'/'.$address_vendor_attachments[$i], $x, $y, $w, $h,  $extension , '', '', true, 150, '', false, false, false, false, false, false);
		      }    
		   } 
      }
     
      foreach ($court_info as $key => $value) 
      { 
        $pdf->AddPage();
        $counter = ($key == 0) ? ''  : $key+1;

        $attachments = ($value['attachments'] != NULL && $value['attachments'] != '') ?  explode('||', $value['attachments']) : '';

        $court_vendor_attachments = ($value['vendor_attachments'] != NULL && $value['vendor_attachments'] != '') ?  explode('||', $value['vendor_attachments']) : '';


        $Verified_status = ($value['verfstatus'] == "" || $value['verfstatus'] == "WIP" || $value['verfstatus'] == "Insufficiency" || $value['verfstatus'] == "Stop Check" || $value['verfstatus'] == 'Unable to verify' || $value['verfstatus'] == 'Overseas check' || $value['verfstatus'] == 'YTR' || str_replace(" ",'', $value['verfstatus']) == "Workedwiththesameorganization") ? "NA" : "Verified";

        $value = array_map('ucwords', $value);
      
       
    //    $pdf->Cell(0, 11, "COURT RECORD VERIFICATION" ,1, 1, 'C', 1, 0);

            $full_address = $value['street_address'].' '.$value['city'].' '.$value['pincode'].' '.$value['state'];
            $court_full_address = ($Verified_status == "NA") ?  "NA" : $full_address;

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

               

    $court_name = '<style>th, td {text-align: left;}</style><table  cellpadding="5" width="98%">';
        $court_name .= '<tr>';
        $court_name .= '<td bgcolor="#f2f2f2" style="text-align: center;height: 35px;"><b>'.$component_name["courtver"].' VERIFICATION  '.$counter.'</b></td>';
        $court_name .= '</tr>';    
        $court_name .= '</table>';

        $pdf->writeHTML($court_name, true, false, false, false, '');


        $court_full_details = '<style>th, td {text-align: left;border-top: 1px solid #000;border-bottom: 1px solid #000;}</style><table  cellpadding="5" width="97%">';
        $court_full_details .= '<tr>';
        $court_full_details .= '<td bgcolor="#f2f2f2" style="text-align: center;"><b> PARTICULARS</b></td>';
        $court_full_details .= '<td bgcolor="#f2f2f2" style="text-align: center;"><b> VERIFIED INFORMATION</b></td>';
        $court_full_details .= '</tr>'; 
        $court_full_details .= '<tr>';
        $court_full_details .= '<td  bgcolor="#f2f2f2" style="text-align: center;"><b>ADDRESS</b></td>';
        $court_full_details .= '<td>'.$court_full_address.'</td>';
        $court_full_details .= '</tr>';
              
        $court_full_details .= '<tr>';
        $court_full_details .= '<td  bgcolor="#f2f2f2" style="text-align: center;"><b>MODE OF VERIFICATION</b></td>';
        $court_full_details .= '<td>'.$mode_of_verification.'</td>';
        $court_full_details .= '</tr>';
        $court_full_details .= '<tr>';
        $court_full_details .= '<td  bgcolor="#f2f2f2" style="text-align: center;"><b>REMARKS</b></td>';
        $court_full_details .= '<td>'.$remarks.'</td>';
        $court_full_details .= '</tr>';
        $court_full_details .= '</table>';

        
      $pdf->writeHTML($court_full_details, true, false, false, false, '');

        
            if(!empty($attachments))
            {

             $pdf->SetAutoPageBreak(false, 0);

             for ($i = 0; $i < count($attachments); $i++) {
                $pdf->AddPage();
                $pdf->Cell(0, 0, 'Annexure',0, 1, 'C');
                $images_explode = explode('/',$attachments[$i]);

             $extension = strtolower(pathinfo($images_explode[2], PATHINFO_EXTENSION)); 

             $pdf->Image(SITE_BASE_PATH.COURT_VERIFICATION.$images_explode[1].'/'.$images_explode[2], $x, $y, $w, $h,  $extension , '', '', true, 150, '', false, false, false, false, false, false);
               }    
            }
            
            if(!empty($court_vendor_attachments))
            {

              $pdf->SetAutoPageBreak(false, 0);

              for ($i = 0; $i < count($court_vendor_attachments); $i++) {
                $pdf->AddPage();
                $pdf->Cell(0, 0, 'Annexure',0, 1, 'C');
                

              $extension = strtolower(pathinfo($court_vendor_attachments[$i], PATHINFO_EXTENSION)); 

               $pdf->Image(SITE_BASE_PATH.COURT_VERIFICATION.'vendor_file'.'/'.$court_vendor_attachments[$i], $x, $y, $w, $h,  $extension , '', '', true, 150, '', false, false, false, false, false, false);
               }    
            }     
      }


       foreach ($global_db_info as $key => $value) 
      { 
        $pdf->AddPage();
       
        $counter = ($key == 0) ? ''  : $key+1;

        $Verified_status = ($value['verfstatus'] == "" || $value['verfstatus'] == "WIP" || $value['verfstatus'] == "Insufficiency" || $value['verfstatus'] == "Stop Check" || $value['verfstatus'] == 'Unable to verify' || $value['verfstatus'] == 'Overseas check' || $value['verfstatus'] == 'YTR' || str_replace(" ",'', $value['verfstatus']) == "Workedwiththesameorganization") ? "NA" : "Verified";

      
        $full_address = $value['street_address'].' '.$value['city'].' '.$value['pincode'].' '.$value['state'];
        $full_address = ($Verified_status == "NA") ?  "NA" : $full_address;
    
        if(!empty($value['street_address']) && !empty($value['city']) && !empty($value['pincode']) && !empty($value['state']))
        {
            $global_full_address = $full_address;
        }
        else
        {
            $global_full_address = $court_full_address;
        }
   
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
       

        $global_name = '<style>th, td {text-align: left;}</style><table  cellpadding="5" width="98%">';
        $global_name .= '<tr>';
        $global_name .= '<td bgcolor="#f2f2f2" style="text-align: center;height: 35px;"><b>'.$component_name["globdbver"].' VERIFICATION  '.$counter.'</b></td>';
        $global_name .= '</tr>';    
        $global_name .= '</table>';

        $pdf->writeHTML($global_name, true, false, false, false, '');

        $global_full_details = '<style>th, td {text-align: left;border-top: 1px solid #000;border-bottom: 1px solid #000;}</style><table width="97%" cellpadding="5">';
        $global_full_details .= '<tr>';
        $global_full_details .= '<td bgcolor="#f2f2f2" style="text-align: center;"><b> DETAILS</b></td>';
        $global_full_details .= '<td bgcolor="#f2f2f2" style="text-align: center;"><b> FINDINGS</b></td>';
        $global_full_details .= '</tr>'; 
        $global_full_details .= '<tr>';
        $global_full_details .= '<td  bgcolor="#f2f2f2" style="text-align: center;"><b>ADDRESS</b></td>';
        $global_full_details .= '<td>'.$global_full_address.'</td>';
        $global_full_details .= '</tr>';
          
        $global_full_details .= '<tr>';
        $global_full_details .= '<td  bgcolor="#f2f2f2" style="text-align: center;"><b>MODE OF VERIFICATION</b></td>';
        $global_full_details .= '<td>'.$mode_of_verification.'</td>';
        $global_full_details .= '</tr>';
       
       
        $global_full_details .= '<tr>';
        $global_full_details .= '<td  bgcolor="#f2f2f2" style="text-align: center;"><b>REMARKS</b></td>';
        $global_full_details .= '<td>'.$remarks.'</td>';
        $global_full_details .= '</tr>';
              
        $global_full_details .= '</table>';

        
        $pdf->writeHTML($global_full_details, true, false, false, false, '');

        if($cand_info["clientname"] == "credence resource management")
        {

            $global_full_details = '<style>th, td {text-align: left;border-top: 1px solid #000;border-bottom: 1px solid #000;}</style> <table width="97%" cellpadding="5">
            <tr>
              <td bgcolor="#f2f2f2"> DATABASE</td>
              <td bgcolor="#f2f2f2"> STATUS</td>
            </tr>
            <tr>
              <td bgcolor="#f2f2f2">Serious and Organized Crimes Database</td>
              <td>Verified Clear</td>
            </tr>
            <tr>
              <td bgcolor="#f2f2f2">Regulatory Database</td>
              <td>Verified Clear</td>
            </tr>
            <tr>
              <td bgcolor="#f2f2f2">Compliance Database</td>
              <td>Verified Clear</td>
            </tr>
            <tr>
              <td bgcolor="#f2f2f2">Indian Specific Criminal Records Database</td>
              <td>Verified Clear</td>
            </tr>
            <tr>
              <td bgcolor="#f2f2f2">Global Web & Media Searches</td>
              <td>Verified Clear</td>
            </tr></table>';
            
            $pdf->writeHTML($global_full_details, true, false, false, false, '');
        
        }
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

             $pdf->Image(SITE_BASE_PATH.GLOBAL_DB.$images_explode[1].'/'.$images_explode[2], $x, $y, $w, $h,  $extension , '', '', true, 150, '', false, false, false, false, false, false);
               }    
            } 


            if(!empty($global_vendor_attachments))
            {

              $pdf->SetAutoPageBreak(false, 0);

              for ($i = 0; $i < count($global_vendor_attachments); $i++) {
                $pdf->AddPage();
                $pdf->Cell(0, 0, 'Annexure',0, 1, 'C');
                

              $extension = strtolower(pathinfo($global_vendor_attachments[$i], PATHINFO_EXTENSION)); 

               $pdf->Image(SITE_BASE_PATH.GLOBAL_DB.'vendor_file'.'/'.$global_vendor_attachments[$i], $x, $y, $w, $h,  $extension , '', '', true, 150, '', false, false, false, false, false, false);
               }    
            } 

      if($cand_info["clientname"] == "credence resource management")
      {   
        $pdf->AddPage();
        $pdf->Cell(0, 11, "GLOBAL SERIOUS & ORGANIZED CRIMES DATABASES".$counter ,0, 1, 'C', 1, 0); 
        $global_full_details = '<style>th, td {text-align:left;border: 1px solid #000;}</style> <table width="97%">
          <tr>
            <td colspan=2> Organized Crime is one of the major factors in money laundering and its move into ‘legitimate’ business has seen a number of corporations becoming embroiled in business dealings with such groups. This database is comprised of individuals that have been arrested or are ‘wanted’ by international law enforcement agencies such as the FBI, UK’s Serious Fraud Office,India’s Central Bureau of Investigations and the Royal Canadian Mounted Police. </td>
          </tr>
          <tr>
            <td colspan=2> Most Wanted Lists: Global</td>
          </tr>
          <tr>
            <td style="width:80%"> Interpol Most Wanted</td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td colspan=2 style="width:100%"> Most Wanted Lists: US and Canada</td>
          </tr>
          <tr>
            <td style="width:80%"> Air Force Fugitives: Air Force Office of Special Investigation, USA </td>
          <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Boston Police, USA </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Bureau of Alcohol, Tobacco and Firearms, USA </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> City of South Portland Police, USA </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Delaware State Police, USA </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Department of Illinois Corrections, USA </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Drug Enforcement Administration, USA </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> FBI’s Most Wanted: Milwaukee, USA </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Federal Bureau of Investigation, USA [includes hijack suspects, most wanted & FBI seeking information] </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Fort Lauderdale Police Department, USA </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Honolulu Police, USA </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> International Broadcasting Bureau (IBB), USA </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Kansas Bureau of Investigation, USA </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> New Jersey Division of Criminal Justice, USA </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Massachusetts State Police, USA </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Michigan State Police, USA </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Michigan State Police, USA </td>
            <td style="width:19%"> No Record</td>
            </tr>
          <tr>
            <td style="width:80%"> Mississippi Department of Public Safety, USA </td>
            <td style="width:19%"> No Record</td>
            </tr>
          <tr>
            <td style="width:80%"> Modesto Police Department, USA </td>
            <td style="width:19%"> No Record</td>
            </tr>
          <tr>
            <td style="width:80%"> Monterey County Sheriff’s Department, USA </td>
            <td style="width:19%"> No Record</td>
            </tr>
          <tr>
            <td style="width:80%"> Montgomery County Sheriffs Office, USA </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> New Jersey State Police, USA </td>
            <td style="width:19%"> No Record</td>
            </tr>
          <tr>
            <td style="width:80%"> Office of New York City Police Department, USA </td>
            <td style="width:19%"> No Record</td>
            </tr>
          </table>';
        $pdf->writeHTML($global_full_details, true, false, false, false, '');


        $pdf->AddPage();
        $pdf->Cell(0, 11, "GLOBAL SERIOUS & ORGANIZED CRIMES DATABASES [CONTD.]".$counter ,0, 1, 'C', 1, 0);  
        $global_full_details = '<style>th, td {text-align:left;border: 1px solid #000;}</style> <table width="97%">
          <tr>
            <td style="width:80%"> The State Criminal Police Agency of North Rhine, Westphalia (Nordrhein Westfalen), Germany</td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> International Police Cooperation Division, Greece</td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> The Netherlands Police Department, The Netherlands</td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Swiss Police, Switzerland</td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Proscribed Organizations, United Kingdom</td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Scotland Yard’s Most Wanted, United Kingdom</td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> The Metropolitan Police Service, United Kingdom</td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Serious Frauds Office, United Kingdom</td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> National Crime Squad, United Kingdom</td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Ministry of the Interior, Russia</td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td colspan=2 style="width:100%"> Most Wanted Lists: Africa</td>
          </tr>
          <tr>
            <td style="width:80%"> South African Police Service, South Africa</td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td colspan=2 style="width:100%"> Most Wanted Lists: Asia Pacific</td>
          </tr>
          <tr>
            <td style="width:80%"> Criminal Investigation Bureau, Taiwan</td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Bureau of Investigation, Ministry of Justice, Taiwan</td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Dubai Police, UAE</td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Hong Kong Police Force, Hong Kong</td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> New Zealand Police, New Zealand</td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> National Bureau Of Investigation, Philippines</td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Philippines National Police, Philippines</td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Central Narcotics Bureau, Singapore</td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Crime Net, Australia</td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Queensland Police Service, Australia</td>
            <td style="width:19%"> No Record</td>
          </tr>
        </table>';
        $pdf->writeHTML($global_full_details, true, false, false, false, '');

        $pdf->AddPage();
        $pdf->Cell(0, 11, "GLOBAL SERIOUS & ORGANIZED CRIMES DATABASES [CONTD.]".$counter ,0, 1, 'C', 1, 0);  
        $global_full_details = '<style>th, td {text-align:left;border: 1px solid #000;}</style> <table width="97%">
          <tr>
            <td colspan=2> This database details companies and individuals who have been censored by financial regulatory authorities for regulatory breaches. The database is sourced from information provided by the numerous international regulatory authorities. A scan through this database addresses the reputation, financial and legal risks associated with a new business relationship when it is instituted by a financial institution or corporation</td>
          </tr>
          <tr>
            <td colspan=2 style="width:100%"> Global Regulatory Bodies</td>
          </tr>
          <tr>
            <td style="width:80%"> Bureau of Industry and Security</td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> World Bank Debarred Parties</td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Defense Trade Controls (DTC) Debarred Parties</td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td colspan=2 style="width:100%"> US and Canadian Regulatory Bodies</td>
          </tr>
          <tr>
            <td style="width:80%"> American Stock Exchange, USA</td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Commodities and Futures Trading Commission (CFTC), USA</td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Excluded Parties List System [includes General Services Administration (GSA)], USA</td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Federal Deposit and Insurance Corporation (FDIC), USA</td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Federal Reserve Board (FRB), USA</td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Financial Crimes Enforcement Network, USA</td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> HHS-Office of Inspector General (OIG), USA</td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> National Association of Securities Dealers (NASD), USA</td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> National Credit Union Association (NCUA), USA</td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> New York Stock Exchange (NYSE), USA</td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Office Comptroller of Currency (OCC), USA</td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Office of Thrift Supervision (OTS), USA</td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Oregon Department of Consumer & Business Services, USA</td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> The Illinois Office of Banks and Real Estate, USA</td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> US Securities and Exchange Commission, USA</td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> New York State Insurance Department, USA</td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> US Food & Drug Administration</td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Alberta Securities Commission, Canada</td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> British Columbia Securities Commission (BCSC), Canada</td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Investment Dealers Association of Canada (IDA), Canada</td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Office of Superintendents of Financial Institutions (OSFI), Canada</td>
            <td style="width:19%"> No Record</td>
          </tr>
          </table>';
        $pdf->writeHTML($global_full_details, true, false, false, false, '');
        
        $pdf->AddPage();
        $pdf->Cell(0, 11, "GLOBAL REGULATORY AUTHORITIES DATABASES [CONTD.]".$counter ,0, 1, 'C', 1, 0);  
        $global_full_details = '<style>th, td {text-align:left;border: 1px solid #000;}</style> <table width="97%">
          <tr>
            <td style="width:80%"> Ontario Securities Commission (OSC), Canada </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Toronto Stock Exchange, Canada </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Canada Revenue Agency, Canada </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Cayman Islands Monetary Authority, Cayman Islands </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Central Bank of Bahamas, Bahamas </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td colspan=2 style="width:100%"> European Regulatory Bodies </td>
          </tr>
          <tr>
            <td style="width:80%"> Companies House, United Kingdom </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Financial Services Authority (FSA), United Kingdom </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Lloyds of London (Lloyds), United Kingdom </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Financial Services Compensation Scheme, United Kingdom </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Assets Recovery Agency, United Kingdom </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Personal Investment Authority, United Kingdom </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Securities and Futures Authority, United Kingdom </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Charity Commission for England and Wales, United Kingdom </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> HM Customs and Excise, United Kingdom </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Malta Financial Services Authority, Malta </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> The Netherlands Authority For the Financial Markets, Netherlands </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Office of the Director of Corporate Enforcement (ODCE), Ireland </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> The Irish Financial Services Regulatory Authority, Ireland </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> The Financial Supervision Commission, Isle of Man </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Italian Securities Commission (Consob), Italy </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Jersey Financial Securities Commission,Jersey </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Gibraltar Financial Services Commission, Gibraltar </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td colspan=2 style="width:100%"> Asia Pacific Regulatory Bodies </td>
          </tr>
          <tr>
            <td style="width:80%"> Australian Stock Exchange, Australia </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Australian Securities and Investment Commission (ASIC), Australia </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Bangladesh Securities and Commission, Bangladesh </td>
            <td style="width:19%"> No Record</td>
          </tr>
        </table>';
        $pdf->writeHTML($global_full_details, true, false, false, false, '');

        $pdf->AddPage();
        $pdf->Cell(0, 11, "GLOBAL REGULATORY AUTHORITIES DATABASES [CONTD.]".$counter ,0, 1, 'C', 1, 0);  
        $global_full_details = '<style>th, td {text-align:left;border: 1px solid #000;}</style> <table width="97%">
          <tr>
            <td style="width:80%"> China Customs, China </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> CSRC (China Securities Regulatory Commission), China </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Hong Kong Securities & Futures Commission (HKSFC), Hong Kong </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Hong Kong Monetary Authority – Warnings, Hong Kong </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Independent Commission against Corruption, Hong Kong </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td colspan=2 style="width:100%"> Securities Exchange Board of India (SEBI), India </td>
          </tr>
          <tr>
            <td style="width:80%"> Indonesian Capital Market Supervisory Agency (BAPEPAM), Indonesia </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Tokyo Stock Exchange (TSE), Japan </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Japan Securities and Exchange Surveillance Commission, Japan </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Financial Supervisory Service, Korea Republic </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Malaysia Securities Commission (MSC), Malaysia </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> New Zealand Companies House, New Zealand </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> New Zealand Securities Commission (NZSC), New Zealand </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> New Zealand Serious Fraud Office, New Zealand </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Securities Exchange Commission of Pakistan (SECP), Pakistan </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Singapore Stock Exchange, Singapore </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Monetary Authority of Singapore - Investors Alert, Singapore </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Thailand Securities and Exchange Commission, Thailand </td>
            <td style="width:19%"> No Record</td>
          </tr>
        </table>';
        $pdf->writeHTML($global_full_details, true, false, false, false, '');

        $pdf->AddPage();
        $pdf->Cell(0, 11, "GLOBAL SERIOUS & ORGANIZED CRIMES DATABASES".$counter ,0, 1, 'C', 1, 0); 
        $global_full_details = '<style>th, td {text-align:left;border: 1px solid #000;}</style> <table width="97%">
          <tr>
            <td colspan=2> This database is comprised of Government Prohibited Persons Lists, Terrorists, International Narcotics Traffickers and those engaged in activities related to the proliferation of weapons of mass destruction. It also includes Companies and individual who have been named in legal documents and press reports as having aided the 9/11 attacks and other terrorist outrages. The war crime database herein contains information on individuals and entities that have been responsible for serious violations of international humanitarian law and genocide globally.</td>
          </tr>
          <tr>
            <td style="width:80%"> Office of Foreign Assets Control (OFAC): Specially Designated Nationals & Blocked Persons and names that have been deleted from the OFAC list </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Officials of OFAC Blocked Countries </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Bank of England Sanctions List </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> United Nations Consolidated List </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> European Union Terrorist List </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Monetary Authority of Singapore </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Hong Kong Monetary Authority </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Australian Department of Foreign Affairs and Trade (DFAT) </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Central Bank of UAE </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> The Australian Transaction Reports and Analysis Centre (Austrac) </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Reserve Bank of Australia </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> OSFI Consolidated List, Canada </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Rwanda International War Crimes Database </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Yugoslavia International War Crimes Database </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Arms Trafficking and Weapons of Mass Destruction </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> 9/11 Subpoena Database </td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Global Money Laundering Database </td>
            <td style="width:19%"> No Record</td>
          </tr>
          </table>';
        $pdf->writeHTML($global_full_details, true, false, false, false, '');

        $pdf->AddPage();
        $pdf->Cell(0, 11, "INDIA-SPECIFIC CRIMINAL RECORDS DATABASES".$counter,0, 1, 'C', 1, 0); 
        $global_full_details = '<style>th, td {text-align:left;border: 1px solid #000;}</style> <table width="97%">
          <tr>
            <td colspan=2 style="width:100%"> Central Bureau of Investigation Most Wanted List</td>
          </tr>
          <tr>
            <td style="width:80%"> A comprehensive database maintained by Central Bureau of Investigation (CBI), India’s premier investigating agency, responsible for a wide variety of criminal and national security matters consisting records of most wanted criminals and terrorists</td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td colspan=2 style="width:100%"> Supreme Court Records Check</td>
          </tr>
          <tr>
            <td style="width:80%"> A comprehensive collection of the full text of case laws on all subjects, compiled from 29 different acts related to Supreme Court case laws dating back to 1950.</td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td colspan=2 style="width:100%"> High Court Records Check</td>
          </tr>
          <tr>
            <td style="width:80%"> This database consists of comprehensive records of the full text of High Court cases dating back to 1971, compiled from 4 different acts.</td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td colspan=2 style="width:100%"> Central Vigilance Commission Corrupt Officers Database</td>
          </tr>
          <tr>
            <td style="width:80%"> Records of Government or Public Sector Officials that the Central Vigilance Commission of the Indian Government has taken action against for corruption since the year 2000.</td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td colspan=2 style="width:100%"> Most Wanted Criminals</td>
          </tr>
          <tr>
            <td style="width:80%"> Database of most wanted persons compiled from various sources of the Indian Police Department, including Delhi Police, Tripura Police, Himachal Pradesh Police, Punjab Police, Bangalore City Police and CID - Andhra Pradesh State.</td>
            <td style="width:19%"> No Record</td>
          </tr>
        </table>';
        $pdf->writeHTML($global_full_details, true, false, false, false, '');

        $pdf->AddPage();
        $pdf->Cell(0, 11, "SANCTIONS DATABASE CHECKS".$counter ,0, 1, 'C', 1, 0); 
        $global_full_details = '<style>th, td {text-align:left;border: 1px solid #000;}</style> <table width="97%">
          <tr>
            <td colspan=2 style="width:100%"> This Sanctions List Search application ("Sanctions List Search") is designed to facilitate the use of the Specially Designated Nationals and Blocked Persons list ("SDN List") and all other sanctions lists administered by OFAC, including the Foreign Sanctions Evaders List, the List of Persons Identified as Blocked Solely Pursuant to E.O. 13599. Sanctions List Search is one tool offered to assist users in utilizing the SDN List and/or the various other sanctions lists; use of Sanctions List Search is not a substitute for undertaking appropriate due diligence. The use of Sanctions List Search does not limit any criminal or civil liability for any act undertaken as a result of, or in reliance on, such use.</td>
          </tr>
          <tr>
            <td style="width:80%"> Office of Foreign Assets Control (OFAC): specifically Designated Nationals & Blocked Persons and names that have been deleted from the OFAC list. Sanctions checks are specialized searches that include a number of government sanction databases that identify individuals who are prohibited from certain activities or industries</td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Officials of OFAC Blocked Countries(Office of Foreign Assets Control (OFAC)</td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td style="width:80%"> Healthcare Sanctions Checks</td>
            <td style="width:19%"> No Record</td>
          </tr>
        </table>';
        $pdf->writeHTML($global_full_details, true, false, false, false, '');

        $pdf->AddPage();
        $pdf->Cell(0, 11, "GLOBAL WEB & MEDIA SEARCHES".$counter ,0, 1, 'C', 1, 0); 
        $global_full_details = '<style>th, td {text-align:left;border: 1px solid #000;}</style> <table width="97%">
          <tr>
            <td colspan=2 style="width:100%"> Media Searches</td>
          </tr>
          <tr>
            <td style="width:80%"> '.CRMNAME.' has access to the most comprehensive global media database. Web and media searches includes global news and articles from over 9,000 authoritative sources and websites to identify individuals and entities associated with crimes of any nature such as civil & criminal matters, corruption, money laundering, serious & Organized crime, terrorism etc. The sources provide news going back up to 25 years and also cover local language articles.</td>
            <td style="width:19%"> No Record</td>
          </tr>
          <tr>
            <td colspan=2 style="width:100%"> Internet Searches</td>
          </tr>
          <tr>
            <td style="width:80%"> '.CRMNAME.'  uses a number of advanced and programmable Internet search tools, which allows it to search through numerous international as well as India-specific search engines on the Internet. This comprehensive search methodology covers both the web as well as newsgroups No record and ensures as complete coverage of the net as is possible.</td>
            <td style="width:19%"> No Record</td>
          </tr>
        </table>';
        $pdf->writeHTML($global_full_details, true, false, false, false, ''); 


      }   
    }

  
			
      foreach ($references_info as $key => $value) 
      { 
        $pdf->AddPage();

        $counter = ($key == 0) ? ''  : $key+1;

        $attachments = ($value['attachments'] != NULL && $value['attachments'] != '') ?  explode('||', $value['attachments']) : '';


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
        
              
        $ref_name ='<style>th, td {text-align: left;}</style><table width="97%" cellpadding="5" width="98%">';
        $ref_name .='<tr>';
        $ref_name .='<td bgcolor="#f2f2f2" style="text-align: center;height: 35px;"><b>'.$component_name['refver'].' VERIFICATION '.$counter.'</b></td>';
        $ref_name .='</tr>';    
        $ref_name .='</table>';

        $pdf->writeHTML($ref_name, true, false, false, false, '');


        $ref_full_details ='<style>th, td {text-align: left;border-top: 1px solid #000;border-bottom: 1px solid #000;}</style><table width="97%" cellpadding="5" width="97%">';
        $ref_full_details .='<tr>';
        $ref_full_details .='<td bgcolor="#f2f2f2" style="text-align: center;"><b> PARTICULARS</b></td>';
        $ref_full_details .='<td bgcolor="#f2f2f2" style="text-align: center;"><b>  VERIFIED INFORMATION</b></td>';
        $ref_full_details .='</tr>'; 
        $ref_full_details .='<tr>';
        $ref_full_details .='<td bgcolor="#f2f2f2" style="text-align: center;"><b> REFERENCE NAME</b></td>';
        $ref_full_details .='<td>'.$name_of_reference.'</td>';
        $ref_full_details .='</tr>';
        $ref_full_details .='<tr>';
        $ref_full_details .='<td bgcolor="#f2f2f2" style="text-align: center;"><b>DESIGNATION</b></td>';
        $ref_full_details .='<td>'.$designation.'</td>';
        $ref_full_details .='</tr>';
        $ref_full_details .='<tr>';
        $ref_full_details .='<td  bgcolor="#f2f2f2" style="text-align: center;"><b>ABILITY TO HANDLE PRESSURE</b></td>';
        $ref_full_details .='<td>'.$handle_pressure_value."</td>";
        $ref_full_details .='</tr>';
        $ref_full_details .='<tr>';
        $ref_full_details .='<td bgcolor="#f2f2f2" style="text-align: center;"><b>ATTENDANCE/PUNCTUALITY</b></td>';
        $ref_full_details .='<td>'.$attendance_value.'</td>';
        $ref_full_details .='</tr>';
        $ref_full_details .='<tr>';
        $ref_full_details .='<td bgcolor="#f2f2f2" style="text-align: center;"><b>INTEGRITY, CHARACTER & HONESTY</b></td>';
        $ref_full_details .='<td>'.$integrity_value.'</td>';
        $ref_full_details .='</tr>';
        $ref_full_details .='<tr>';
        $ref_full_details .='<td bgcolor="#f2f2f2" style="text-align: center;"><b>LEADERSHIP SKILLS</b></td>';
        $ref_full_details .='<td>'.$leadership_skills_value.'</td>';
        $ref_full_details .='</tr>';
        $ref_full_details .='<tr>';
        $ref_full_details .='<td bgcolor="#f2f2f2" style="text-align: center;"><b>RESPONSIBILITIES & DUTIES</b></td>';
        $ref_full_details .='<td>'.$responsibilities_value.'</td>';
        $ref_full_details .='</tr>';
        $ref_full_details .='<tr>';
        $ref_full_details .='<td bgcolor="#f2f2f2" style="text-align: center;"><b>SPECIFIC ACHIEVEMENTS</b></td>';
        $ref_full_details .='<td>'.$achievements_value.'</td>';
        $ref_full_details .='</tr>';
        $ref_full_details .='<tr>';
        $ref_full_details .='<td bgcolor="#f2f2f2" style="text-align: center;"><b>STRENGTHS</b></td>';
        $ref_full_details .='<td>'.$strengths_value.'</td>';
        $ref_full_details .='</tr>';
        $ref_full_details .='<tr>';
        $ref_full_details .='<td bgcolor="#f2f2f2" style="text-align: center;"><b>WEAKNESSES</b></td>';
        $ref_full_details .='<td>'.$weakness_value.'</td>';
        $ref_full_details .='</tr>';
        $ref_full_details .='<tr>';
        $ref_full_details .='<td  bgcolor="#f2f2f2" style="text-align: center;"><b>TEAM PLAYER</b></td>';
        $ref_full_details .='<td>'.$team_player_value.'</td>';
        $ref_full_details .='</tr>';
        $ref_full_details .='<tr>';
        $ref_full_details .='<td  bgcolor="#f2f2f2" style="text-align: center;"><b>OVERALL PERFORMANCE</b></td>';
        $ref_full_details .='<td>'.$overall_performance.'</td>';
        $ref_full_details .='</tr>';
        $ref_full_details .='<tr>';
        $ref_full_details .='<td  bgcolor="#f2f2f2" style="text-align: center;"><b>MODE OF VERIFICATION</b></td>';
        $ref_full_details .='<td>'.$mode_of_verification.'</td>';
        $ref_full_details .='</tr>';
        $ref_full_details .='<tr>';
        $ref_full_details .='<td bgcolor="#f2f2f2" style="text-align: center;"><b>ADDITIONAL COMMENTS</b></td>';
        $ref_full_details .='<td>'.$additional_comments.'</td>';
        $ref_full_details .='</tr>';
        $ref_full_details .='<tr>';
        $ref_full_details .='<td bgcolor="#f2f2f2" style="text-align: center;"><b>REMARKS</b></td>';
        $ref_full_details .='<td>'.$res_remarks.'</td>';
        $ref_full_details .='</tr>';
       
        $ref_full_details .='</table>';

        $pdf->writeHTML($ref_full_details, true, false, false, false, '');

         
              if(!empty($attachments))
		         {

		          $pdf->SetAutoPageBreak(false, 0);

		          for ($i = 0; $i < count($attachments); $i++) {
		            $pdf->AddPage();
		            $pdf->Cell(0, 0, 'Annexure',0, 1, 'C');
		            $images_explode = explode('/',$attachments[$i]);

		          $extension = strtolower(pathinfo($images_explode[2], PATHINFO_EXTENSION)); 

		           $pdf->Image(SITE_BASE_PATH.REFERENCES.$images_explode[1].'/'.$images_explode[2], $x, $y, $w, $h,  $extension , '', '', true, 150, '', false, false, false, false, false, false);
		           }    
		         }   

        
      }
  

      foreach ($drugs_info as $key => $value) 
      { 
        $pdf->AddPage();
       
        $counter = ($key == 0) ? ''  : $key+1;
    //    $pdf->Cell(0, 11, "COURT RECORD VERIFICATION" ,1, 1, 'C', 1, 0);
        $attachments = ($value['attachments'] != NULL && $value['attachments'] != '') ?  explode('||', $value['attachments']) : '';
        $drugs_vendor_attachments = ($value['vendor_attachments'] != NULL && $value['vendor_attachments'] != '') ?  explode('||', $value['vendor_attachments']) : '';

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
       

        $drugs_name = '<style>th, td {text-align: left;}</style><table width="97%" cellpadding="5" width="98%">';
        $drugs_name .= '<tr>';
        $drugs_name .= '<td bgcolor="#f2f2f2" style="text-align: center;height: 35px;"><b>'.$component_name["narcver"].' VERIFICATION  '.$counter.'</b></td>';
        $drugs_name .= '</tr>';    
        $drugs_name .= '</table>';

        $pdf->writeHTML($drugs_name, true, false, false, false, '');


        $drugs_full_details = '<style>th, td {text-align: left;border-top: 1px solid #000;border-bottom: 1px solid #000;}</style><table cellpadding="5" width="97%">';
        $drugs_full_details .= '<tr>';
        $drugs_full_details .= '<td bgcolor="#f2f2f2" style="text-align: center;"><b> 5 DRUG PANEL</b></td>';
        $drugs_full_details .= '<td bgcolor="#f2f2f2" style="text-align: center;"><b> STATUS </b></td>';
        $drugs_full_details .= '</tr>'; 
        $drugs_full_details .= '<tr>';
        $drugs_full_details .= '<td bgcolor="#f2f2f2" style="text-align: center;"><b> AMPHETAMINE SCREEN, URINE</b></td>';
        $drugs_full_details .= '<td>'.$amphetamine_screen.'</td>';
        $drugs_full_details .= '</tr>';
        $drugs_full_details .= '<tr>';
        $drugs_full_details .= '<td bgcolor="#f2f2f2" style="text-align: center;"><b> CANNABINOIDS SCREEN, URINE</b></td>';
        $drugs_full_details .= '<td>'.$cannabinoids_screen.'</td>';
        $drugs_full_details .= '</tr>';
        $drugs_full_details .= '<tr>';
        $drugs_full_details .= '<td bgcolor="#f2f2f2" style="text-align: center;"><b>  COCAINE SCREEN, URINE</b></td>';
        $drugs_full_details .= '<td>'.$cocaine_screen.'</td>';
        $drugs_full_details .= '</tr>';
        $drugs_full_details .= '<tr>';
        $drugs_full_details .= '<td bgcolor="#f2f2f2" style="text-align: center;"><b>  OPIATES SCREEN, URINE</b></td>';
        $drugs_full_details .= '<td>'.$opiates_screen.'</td>';
        $drugs_full_details .= '</tr>';
        $drugs_full_details .= '<tr>';
        $drugs_full_details .= '<td bgcolor="#f2f2f2" style="text-align: center;"><b> PHENCYCLIDINE SCREEN, URINE</b></td>';
        $drugs_full_details .= '<td>'.$phencyclidine_screen.'</td>';
        $drugs_full_details .= '</tr>';
        $drugs_full_details .= '<tr>';
        $drugs_full_details .= '<td bgcolor="#f2f2f2" style="text-align: center;"><b> REMARKS</b></td>';
        $drugs_full_details .= '<td>'.$remarks.'</td>';
        $drugs_full_details .= '</tr>';
                
        $drugs_full_details .= '</table>';

        
        $pdf->writeHTML($drugs_full_details, true, false, false, false, '');

        
             if(!empty($attachments))
		        {

		         $pdf->SetAutoPageBreak(false, 0);

		         for ($i = 0; $i < count($attachments); $i++) {
		            $pdf->AddPage();
		            $pdf->Cell(0, 0, 'Annexure',0, 1, 'C');
		            $images_explode = explode('/',$attachments[$i]);

		         $extension = strtolower(pathinfo($images_explode[2], PATHINFO_EXTENSION)); 

		         $pdf->Image(SITE_BASE_PATH.DRUGS.$images_explode[1].'/'.$images_explode[2], $x, $y, $w, $h,  $extension , '', '', true, 150, '', false, false, false, false, false, false);
		           }    
		        }
            
            if(!empty($drugs_vendor_attachments))
            {

              $pdf->SetAutoPageBreak(false, 0);

              for ($i = 0; $i < count($drugs_vendor_attachments); $i++) {
                $pdf->AddPage();
                $pdf->Cell(0, 0, 'Annexure',0, 1, 'C');
                

              $extension = strtolower(pathinfo($drugs_vendor_attachments[$i], PATHINFO_EXTENSION)); 

               $pdf->Image(SITE_BASE_PATH.DRUGS.'vendor_file'.'/'.$drugs_vendor_attachments[$i], $x, $y, $w, $h,  $extension , '', '', true, 150, '', false, false, false, false, false, false);
               }    
            }      
      }

			foreach ($pcc_info as $key => $value) 
			{	
				$pdf->AddPage();
				
       
          $counter = ($key == 0) ? ''  : $key+1;

          $attachments = ($value['attachments'] != NULL && $value['attachments'] != '') ?  explode('||', $value['attachments']) : '';
          $pcc_vendor_attachments = ($value['vendor_attachments'] != NULL && $value['vendor_attachments'] != '') ?  explode('||', $value['vendor_attachments']) : '';
  

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
           
          $pcc_name = '<style>th, td {text-align: left;}</style><table  cellpadding="5" width="98%">';
          $pcc_name .= '<tr>';  
          $pcc_name .= '<td bgcolor="#f2f2f2" style="text-align: center;height: 35px;"><b>'.$component_name['crimver'].' VERIFICATION '.$counter.'</b></td>';
          $pcc_name .= '</tr>';    
          $pcc_name .= '</table>';

          $pdf->writeHTML($pcc_name, true, false, false, false, '');


          $pcc_full_details = '<style>th, td {text-align: left;border-top: 1px solid #000;border-bottom: 1px solid #000;}</style><table  cellpadding="5" width="97%">';
          $pcc_full_details .= '<tr>';
          $pcc_full_details .= '<td bgcolor="#f2f2f2" style="text-align: center;"><b> PARTICULARS</b></td>';
          $pcc_full_details .= '<td bgcolor="#f2f2f2" style="text-align: center;"><b> VERIFIED INFORMATION</b></td>';
          $pcc_full_details .= '</tr>'; 
          $pcc_full_details .= '<tr>';
          $pcc_full_details .= '<td bgcolor="#f2f2f2" style="text-align: center;"><b>DATE OF VISIT POLICE STATION</b></td>';
          $pcc_full_details .= '<td>'.$police_station_visit_date.'</td>';
          $pcc_full_details .= '</tr>';
          $pcc_full_details .= '<tr>';
          $pcc_full_details .= '<td bgcolor="#f2f2f2" style="text-align: center;"><b>ADDRESS</b></td>';
          $pcc_full_details .= '<td>'.$full_address.'</td>';
          $pcc_full_details .= '</tr>';
          $pcc_full_details .= '<tr>';
          $pcc_full_details .= '<td bgcolor="#f2f2f2" style="text-align: center;"><b>NAME AND DESIGNATION OF THE POLICE OFFICER</b></td>';
          $pcc_full_details .= '<td>'.$name_designation_police.'</td>';
          $pcc_full_details .= '</tr>';
          $pcc_full_details .= '<tr>';
          $pcc_full_details .= '<td bgcolor="#f2f2f2" style="text-align: center;"><b>CONTACT NUMBER OF POLICE</b></td>';
          $pcc_full_details .= '<td>'.$contact_number_police.'</td>';
          $pcc_full_details .= '</tr>';
              
          $pcc_full_details .= '<tr>';
          $pcc_full_details .= '<td bgcolor="#f2f2f2" style="text-align: center;"><b>MODE OF VERIFICATION</b></td>';
          $pcc_full_details .= '<td>'.$mode_of_verification.'</td>';
          $pcc_full_details .= '</tr>';
          $pcc_full_details .= '<tr>';
          $pcc_full_details .= '<td bgcolor="#f2f2f2" style="text-align: center;"><b>REMARKS</b></td>';
          $pcc_full_details .= '<td>'.$remarks.'</td>';
          $pcc_full_details .= '</tr>';
               
               
          $pcc_full_details .= '</table>';

				$pdf->writeHTML($pcc_full_details, true, false, false, false, '');

			
            if(!empty($attachments))
		        {

		         $pdf->SetAutoPageBreak(false, 0);

		         for ($i = 0; $i < count($attachments); $i++) {
		            $pdf->AddPage();
		            $pdf->Cell(0, 0, 'Annexure',0, 1, 'C');
		            $images_explode = explode('/',$attachments[$i]);

		         $extension = strtolower(pathinfo($images_explode[2], PATHINFO_EXTENSION)); 

		         $pdf->Image(SITE_BASE_PATH.PCC.$images_explode[1].'/'.$images_explode[2], $x, $y, $w, $h,  $extension , '', '', true, 150, '', false, false, false, false, false, false);
		           }    
		        }

		        if(!empty($pcc_vendor_attachments))
		        {

		          $pdf->SetAutoPageBreak(false, 0);

		          for ($i = 0; $i < count($pcc_vendor_attachments); $i++) {
		            $pdf->AddPage();
		            $pdf->Cell(0, 0, 'Annexure',0, 1, 'C');
		            

		          $extension = strtolower(pathinfo($pcc_vendor_attachments[$i], PATHINFO_EXTENSION)); 

		           $pdf->Image(SITE_BASE_PATH.PCC.'vendor_file'.'/'.$pcc_vendor_attachments[$i], $x, $y, $w, $h,  $extension , '', '', true, 150, '', false, false, false, false, false, false);
		           }    
		        }    

       }

      foreach ($identity_info as $key => $value) 
      { 
          $pdf->AddPage();
          
          $counter = ($key == 0) ? ''  : $key+1;

          $attachments = ($value['attachments'] != NULL && $value['attachments'] != '') ?  explode('||', $value['attachments']) : '';
        
          $identity_vendor_attachments = ($value['vendor_attachments'] != NULL && $value['vendor_attachments'] != '') ?  explode('||', $value['vendor_attachments']) : '';
      

          $Verified_status = ($value['verfstatus'] == "" || $value['verfstatus'] == "WIP" || $value['verfstatus'] == "Insufficiency" || $value['verfstatus'] == "Stop Check" || $value['verfstatus'] == 'Unable to verify' || $value['verfstatus'] == 'Overseas check' || $value['verfstatus'] == 'YTR' || str_replace(" ",'', $value['verfstatus']) == "Workedwiththesameorganization") ? "NA" : "Verified";

          $value = array_map('ucwords', $value);

          $doc_submited = ($value['doc_submited'] != '') ? $value['doc_submited'] : "NA";
          $doc_submited = ($Verified_status == "NA") ?  "NA" : $doc_submited;

          $id_number = ($value['id_number'] != '') ? $value['id_number'] : "NA";
          $id_number = ($Verified_status == "NA") ?  "NA" : $id_number;

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
           
          $identity_name = '<style>th, td {text-align: left;}</style><table  cellpadding="5" width="98%">';
          $identity_name .= '<tr>';
          $identity_name .= '<td bgcolor="#f2f2f2" style="text-align: center;height: 35px;"><b>'.$component_name["identity"].' VERIFICATION '.$counter.'</b></td>';
          $identity_name .= '</tr>'; 
          $identity_name .= '</table>';

          $pdf->writeHTML($identity_name, true, false, false, false, '');


          $identity_full_details = '<style>th, td {text-align: left;border-top: 1px solid #000;border-bottom: 1px solid #000;}</style><table  cellpadding="5" width="97%">';
          $identity_full_details .= '<tr>';
          $identity_full_details .= '<td bgcolor="#f2f2f2" style="text-align: center;"><b> PARTICULARS</b></td>';
          $identity_full_details .= '<td bgcolor="#f2f2f2" style="text-align: center;"><b> VERIFIED INFORMATION</b></td>';
          $identity_full_details .= '</tr>';
          $identity_full_details .= '<tr>';
          $identity_full_details .= '<td bgcolor="#f2f2f2" style="text-align: center;"><b> DOCUMENT PROVIDED</b></td>';
          $identity_full_details .= '<td>'.$doc_submited.'</td>';
          $identity_full_details .= '</tr>';
          $identity_full_details .= '<tr>';
          $identity_full_details .= '<td bgcolor="#f2f2f2" style="text-align: center;"><b> DOCUMENT NUMBER</b></td>';
          $identity_full_details .= '<td>'.$id_number.'</td>';
          $identity_full_details .= '</tr>';
          $identity_full_details .= '<tr>';
          $identity_full_details .= '<td bgcolor="#f2f2f2" style="text-align: center;"><b> MODE OF VERIFICATION</b></td>';
          $identity_full_details .= '<td>'.$mode_of_verification.'</td>';
          $identity_full_details .= '</tr>';
          $identity_full_details .= '<tr>';
          $identity_full_details .= '<td bgcolor="#f2f2f2" style="text-align: center;"><b> REMARKS</b></td>';
          $identity_full_details .= '<td>'.$remarks.'</td>';
          $identity_full_details .= '</tr>';
                
                 
          $identity_full_details .= '</table>';


        $pdf->writeHTML($identity_full_details, true, false, false, false, '');

      
            if(!empty($attachments))
		        {

		         $pdf->SetAutoPageBreak(false, 0);

		         for ($i = 0; $i < count($attachments); $i++) {
		            $pdf->AddPage();
		            $pdf->Cell(0, 0, 'Annexure',0, 1, 'C');
		            $images_explode = explode('/',$attachments[$i]);

		         $extension = strtolower(pathinfo($images_explode[2], PATHINFO_EXTENSION)); 

		         $pdf->Image(SITE_BASE_PATH.IDENTITY.$images_explode[1].'/'.$images_explode[2], $x, $y, $w, $h,  $extension , '', '', true, 150, '', false, false, false, false, false, false);
		           }    
		        } 

		        if(!empty($identity_vendor_attachments))
		        {

		          $pdf->SetAutoPageBreak(false, 0);

		          for ($i = 0; $i < count($identity_vendor_attachments); $i++) {
		            $pdf->AddPage();
		            $pdf->Cell(0, 0, 'Annexure',0, 1, 'C');
		            

		          $extension = strtolower(pathinfo($identity_vendor_attachments[$i], PATHINFO_EXTENSION)); 

		           $pdf->Image(SITE_BASE_PATH.IDENTITY.'vendor_file'.'/'.$identity_vendor_attachments[$i], $x, $y, $w, $h,  $extension , '', '', true, 150, '', false, false, false, false, false, false);
		           }    
		        }  

      }

      foreach ($credit_report_info as $key => $value) 
      { 
          $pdf->AddPage();
          
          $counter = ($key == 0) ? ''  : $key+1;

          $attachments = ($value['attachments'] != NULL && $value['attachments'] != '') ?  explode('||', $value['attachments']) : '';

          $credit_report_vendor_attachments = ($value['vendor_attachments'] != NULL && $value['vendor_attachments'] != '') ?  explode('||', $value['vendor_attachments']) : '';

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
         

          $credit_report_name = '<style>th, td {text-align: left;}</style><table  cellpadding="5" width="98%">';
          $credit_report_name .= '<tr>';
          $credit_report_name .= '<td bgcolor="#f2f2f2" style="text-align: center;height: 35px;"><b>'.$component_name["cbrver"].' VERIFICATION '.$counter.'</b></td>';
          $credit_report_name .= '</tr>'; 
          $credit_report_name .= '</table>';

          $pdf->writeHTML($credit_report_name, true, false, false, false, '');


          $credit_report_full_details = '<style>th, td {text-align: left;border-top: 1px solid #000;border-bottom: 1px solid #000;}</style><table  cellpadding="5" width="97%">';
          $credit_report_full_details .= '<tr>';
          $credit_report_full_details .= '<td bgcolor="#f2f2f2" style="text-align: center;"><b> PARTICULARS</b></td>';
          $credit_report_full_details .= '<td bgcolor="#f2f2f2" style="text-align: center;"><b> VERIFIED INFORMATION</b></td>';
          $credit_report_full_details .= '</tr>';
          $credit_report_full_details .= '<tr>';
          $credit_report_full_details .= '<td bgcolor="#f2f2f2" style="text-align: center;"><b> DOCUMENT PROVIDED</b></td>';
          $credit_report_full_details .= '<td>'.$doc_submited.'</td>';
          $credit_report_full_details .= '</tr>';
          $credit_report_full_details .= '<tr>';
          $credit_report_full_details .= '<td bgcolor="#f2f2f2" style="text-align: center;"><b> MODE OF VERIFICATION</b></td>';
          $credit_report_full_details .= '<td>'.$mode_of_verification.'</td>';
          $credit_report_full_details .= '</tr>';
          $credit_report_full_details .= '<tr>';
          $credit_report_full_details .= '<td bgcolor="#f2f2f2" style="text-align: center;"><b> REMARKS</b></td>';
          $credit_report_full_details .= '<td>'.$remarks.'</td>';
          $credit_report_full_details .= '</tr>';
                
                 
          $credit_report_full_details .= '</table>';


          $pdf->writeHTML($credit_report_full_details, true, false, false, false, '');

        
            if(!empty($attachments))
		        {

		         $pdf->SetAutoPageBreak(false, 0);

		         for ($i = 0; $i < count($attachments); $i++) {
		            $pdf->AddPage();
		            $pdf->Cell(0, 0, 'Annexure',0, 1, 'C');
		            $images_explode = explode('/',$attachments[$i]);

		         $extension = strtolower(pathinfo($images_explode[2], PATHINFO_EXTENSION)); 

		         $pdf->Image(SITE_BASE_PATH.CREDIT_REPORT.$images_explode[1].'/'.$images_explode[2], $x, $y, $w, $h,  $extension , '', '', true, 150, '', false, false, false, false, false, false);
		           }    
		        }  
                
            if(!empty($credit_report_vendor_attachments))
		        {

		          $pdf->SetAutoPageBreak(false, 0);

		          for ($i = 0; $i < count($credit_report_vendor_attachments); $i++) {
		            $pdf->AddPage();
		            $pdf->Cell(0, 0, 'Annexure',0, 1, 'C');
		            

		          $extension = strtolower(pathinfo($credit_report_vendor_attachments[$i], PATHINFO_EXTENSION)); 

		           $pdf->Image(SITE_BASE_PATH.CREDIT_REPORT.'vendor_file'.'/'.$credit_report_vendor_attachments[$i], $x, $y, $w, $h,  $extension , '', '', true, 150, '', false, false, false, false, false, false);
		           }    
		        } 
          }
		

        foreach ($social_media_info as $key => $value) 
        { 
          $pdf->AddPage();
          
          $counter = ($key == 0) ? ''  : $key+1;

          $attachments = ($value['attachments'] != NULL && $value['attachments'] != '') ?  explode('||', $value['attachments']) : '';
   

          $Verified_status = ($value['verfstatus'] == "" || $value['verfstatus'] == "WIP" || $value['verfstatus'] == "Insufficiency" || $value['verfstatus'] == "Stop Check" || $value['verfstatus'] == 'Unable to verify' || $value['verfstatus'] == 'Overseas check' || $value['verfstatus'] == 'YTR' || str_replace(" ",'', $value['verfstatus']) == "Workedwiththesameorganization") ? "NA" : "Verified";

          $value = array_map('ucwords', $value);

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
         

           
          $social_media_name = '<style>th, td {text-align: left;}</style><table  cellpadding="5" width="98%">';
          $social_media_name .= '<tr>';
          $social_media_name .= '<td bgcolor="#f2f2f2" style="text-align: center;height: 35px;"><b>'.$component_name["social_media"].' VERIFICATION '.$counter.'</b></td>';
          $social_media_name .= '</tr>'; 
          $social_media_name .= '</table>';

          $pdf->writeHTML($social_media_name, true, false, false, false, '');


          $social_media_full_details = '<style>th, td {text-align: left;border-top: 1px solid #000;border-bottom: 1px solid #000;}</style><table  cellpadding="5" width="97%">';
          $social_media_full_details .= '<tr>';
          $social_media_full_details .= '<td bgcolor="#f2f2f2" style="text-align: center;"><b> PARTICULARS</b></td>';
          $social_media_full_details .= '<td bgcolor="#f2f2f2" style="text-align: center;"><b> VERIFIED INFORMATION</b></td>';
          $social_media_full_details .= '</tr>';
          $social_media_full_details .= '<tr>';
          $social_media_full_details .= '<td bgcolor="#f2f2f2" style="text-align: center;"><b> MODE OF VERIFICATION</b></td>';
          $social_media_full_details .= '<td>'.$mode_of_verification.'</td>';
          $social_media_full_details .= '</tr>';
          $social_media_full_details .= '<tr>';
          $social_media_full_details .= '<td bgcolor="#f2f2f2" style="text-align: center;"><b> REMARKS</b></td>';
          $social_media_full_details .= '<td>'.$remarks.'</td>';
          $social_media_full_details .= '</tr>';
                
                 
          $social_media_full_details .= '</table>';


          $pdf->writeHTML($social_media_full_details, true, false, false, false, '');

       
               if(!empty($attachments))
                {

                 $pdf->SetAutoPageBreak(false, 0);

                 for ($i = 0; $i < count($attachments); $i++) {
                    $pdf->AddPage();
                    $pdf->Cell(0, 0, 'Annexure',0, 1, 'C');
                    $images_explode = explode('/',$attachments[$i]);

                 $extension = strtolower(pathinfo($images_explode[2], PATHINFO_EXTENSION)); 

                 $pdf->Image(SITE_BASE_PATH.SOCIAL_MEDIA.$images_explode[1].'/'.$images_explode[2], $x, $y, $w, $h,  $extension , '', '', true, 150, '', false, false, false, false, false, false);
                   }    
                }  
                
              
        } 	
	
		$pdf->SetFont($this->fontname, '', 14);

		$pdf->AddPage();
            $pdf->Ln(40);
            $pdf->Write(0, '*** DISCLAIMER ***', '', 0, 'C', true, 0, false, false, 0);

            $pdf->SetFont($this->fontname, '', 12);


            if($cand_info['clientid'] == '53')
            { 
                $end_of_file = '<p style="text-align:justify;">All services and reports are provided in a professional manner in accordance with industry standards. Except as expressly provided, <b> Expo Job Consultancy </b> and its affiliates make no and disclaim any and all warranties and representations with respect to the services provided herein, whether such warranties and representations are express or implied in fact or by operation of law or otherwise, including without limitation implied warranties of merchantability and fitness for a particular purpose and implied warranties arising from the course of dealing or a course of performance with respect to the accuracy, validity, or completeness of any service or report.</p>
                
                <p style="text-align:justify;">
                Furthermore, <b> Expo Job Consultancy </b> and its affiliates
                expressly disclaim that the services will meet the 
                clients requirements and <b> Expo Job Consultancy </b> and its 
                affiliates express disclaim all such representations and 
                warranties. <b> Expo Job Consultancy </b> merely passes on to client 
                information in the form of reports which <b> Expo Job Consultancy </b> 
                has obtained in respect of the subject of a background 
                screening verification. <b> Expo Job Consultancy </b> is not the author 
                or creator of that information. Given the limitations, no 
                responsibility will be taken by <b> Expo Job Consultancy </b> for the 
                consequences of client in reliance upon information 
                contained in this information or report. However, <b> Expo Job Consultancy </b> will provide reasonable procedures to protect 
                against any false information being provided to the client. 
                The information within the reports is strictly confidential 
                and is intended to be for the sole purpose of the clients 
                evaluation. The information and the reports are not 
                intended for public dissemination.</p>
                <p style="text-align:justify;">
                Any questions or clarifications with respect to the report 
                can be addressed to <b> Expo Job Consultancy </b></p>';

            }
            else
            {


                $end_of_file = '<p style="text-align:justify;">All services and reports are provided in a professional manner in accordance with industry standards. Except as expressly provided, <b>'.CRMNAME.'</b> and its affiliates make no and disclaim any and all warranties and representations with respect to the services provided herein, whether such warranties and representations are express or implied in fact or by operation of law or otherwise, including without limitation implied warranties of merchantability and fitness for a particular purpose and implied warranties arising from the course of dealing or a course of performance with respect to the accuracy, validity, or completeness of any service or report.</p>
                    
                    <p style="text-align:justify;">
                    Furthermore, <b>'.CRMNAME.'</b> and its affiliates
                    expressly disclaim that the services will meet the 
                    clients requirements and <b>'.CRMNAME.'</b> and its 
                    affiliates express disclaim all such representations and 
                    warranties. <b>'.CRMNAME.'</b> merely passes on to client 
                    information in the form of reports which <b>'.CRMNAME.'</b> 
                    has obtained in respect of the subject of a background 
                    screening verification. <b>'.CRMNAME.'</b> is not the author 
                    or creator of that information. Given the limitations, no 
                    responsibility will be taken by <b>'.CRMNAME.'</b> for the 
                    consequences of client in reliance upon information 
                    contained in this information or report. However, <b>'.CRMNAME.'</b> will provide reasonable procedures to protect 
                    against any false information being provided to the client. 
                    The information within the reports is strictly confidential 
                    and is intended to be for the sole purpose of the clients 
                    evaluation. The information and the reports are not 
                    intended for public dissemination.</p>
                    <p style="text-align:justify;">
                    Any questions or clarifications with respect to the report 
                    can be addressed to <b>'.CRMNAME.'</b></p>';
            }
        
            $pdf->writeHTMLCell(0, 8, '', '', $end_of_file, 0, 0, false, true, 10, false);

	
        ob_start();

        $zip = new ZipArchive();

        $DelFilePath="Candidates_report.zip";

        if ($zip->open(SITE_BASE_PATH . CANDIDATES .$DelFilePath, ZIPARCHIVE::CREATE) != TRUE) {
           die ("Could not open archive");
        }

        $filename = SITE_BASE_PATH . CANDIDATES . ucwords($cand_info['ClientRefNumber']).'_'.ucwords($cand_info['CandidateName']).'_'.$reportstatus.'_Report'.'.pdf';

        $pdf->Output($filename, 'F');

        $zip->addFile(SITE_BASE_PATH . CANDIDATES .ucwords($cand_info['ClientRefNumber']).'_'.ucwords($cand_info['CandidateName']).'_'.$reportstatus.'_Report'.'.pdf',ucwords($cand_info['ClientRefNumber']).'_'.ucwords($cand_info['CandidateName']).'_'.$reportstatus.'_Report'.'.pdf');

        
        
	}

  
}
 