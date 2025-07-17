<?php
require_once dirname(__FILE__).'/tcpdf_min/include/tcpdf_include.php';

// Extend the TCPDF class to create custom Header and Footer
class Employment_field_visit extends TCPDF {

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

	public function generate_pdf($reports_data)
	{
 
	   set_time_limit(0);
		 
        $candidate_details = $reports_data;
      
        
		$pdf = new Employment_field_visit(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

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

       

        $header_name = '<style>th, td {text-align: left; }</style><table cellpadding="5" width="98%">';
        $header_name .= '<tr>';
        $header_name .= '<td bgcolor="#f2f2f2" style="text-align: center;height: 35px;"><b>Field Visit Form - '.ucwords($candidate_details["candsid"]).'</b></td>';
        $header_name .= '</tr>';    
        $header_name .= '</table>';

        $pdf->writeHTML($header_name, true, false, false, false, '');
       
        $pdf->SetFont($this->fontname, '', 10);

        if(!empty($candidate_details['empfrom']) && !empty($candidate_details['empto']))
        {

            if((strpos($candidate_details['empfrom'],"-") == 4 || strpos($candidate_details['empfrom'],"-") == 2) &&  (strpos($candidate_details['empto'],"-") == 4 || strpos($candidate_details['empto'],"-") == 2))
            {          
                $period_of_employed = date('d-M-Y', strtotime($candidate_details['empfrom'])).' to '.date('d-M-Y', strtotime($candidate_details['empto']));
            }
            else
            {
                $period_of_employed = $candidate_details['empfrom'].' to '.$candidate_details['empto'];      
            }
        }
        else{
             $period_of_employed = "";
        }

        
        $company_desc = '<b>MIST IT Services Private Ltd</b> is a background verification company conducting employment verification services for their clients We authenticate the details that were presented by candidates during their selection process. These candidates have either joined or have been shortlisted by our clients for employment.';

        $pdf->writeHTML($company_desc, true, false, false, false, '');
        $pdf->Cell(0, 4, '', 0, 1, 'C');
        $full_address = $candidate_details["locationaddr"].','.$candidate_details["citylocality"].','.$candidate_details["state"].','.$candidate_details["pincode"];
       
          $address_tbl = '<table cellpadding="4" width="98%">
        <tr>
            <td bgcolor="#f2f2f2" border = "1" width="30%"><b>Address</b></td>
            <td  border = "1" width="68%">'.$full_address.'</td>
        </tr>
        <tr>
            <td bgcolor="#f2f2f2" border = "1" width="30%"><b>Company Name</b></td>
            <td border = "1" width="68%"><b>'.ucwords($candidate_details['actual_company_name']).'</b></td>
            
        </tr>
   
        </table>';
       

        $pdf->writeHTML($address_tbl, true, false, false, false, '');

       
        $cans_tbl = '<table cellpadding="4" width="98%">
        <tr>
            <th bgcolor="#f2f2f2" border = "1" width="20%"><b></b></th>
            <th bgcolor="#f2f2f2" border = "1" width="35%"><b>Provided by the candidate</b></th>
            <th bgcolor="#f2f2f2" border = "1" width="43%"><b>Your input</b></th>
        </tr>
        <tr>

            <td bgcolor="#f2f2f2" border = "1" width="20%"><b>Name of Candidate</b></td>
            <td colspan="2" border = "1" width="79%">'.ucwords($candidate_details['candsid']).'</td>
            
        </tr>';
        if($candidate_details['client_disclosure'] == "yes")
        {
            $cans_tbl .= '<tr>
                        <td bgcolor="#f2f2f2" border = "1" width="20%"><b>Client Name</b></td>
                        <td colspan="2" border = "1" width="79%">'.ucwords($candidate_details["clientname"]).'</td>
                    
                        </tr>';
        }

        $cans_tbl .=  '<tr>
            <td bgcolor="#f2f2f2" border = "1" width="20%"><b>Component Ref No</b></td>
            <td colspan="2" border = "1" width="79%">'.$candidate_details["emp_com_ref"].'</td>
            
        </tr>
        <tr>
            <td bgcolor="#f2f2f2" border = "1" width="20%"><b>Previous Employee Code</b></td>
            <td colspan="2" border = "1" width="35%"> '.$candidate_details["empid"].'</td>
            <td border = "1" width="43%"></td>
        </tr>
        <tr>    
            <td bgcolor="#f2f2f2" border = "1" width="20%"><b>Employment Type</b></td>
            <td colspan="2" border = "1" width="35%">'.$candidate_details["employment_type"].'</td>
            <td border = "1" width="43%"></td>
        </tr>
        <tr>
            <td bgcolor="#f2f2f2" border = "1" width="20%"><b>Period of Employment</b></td>
            <td colspan="2" border = "1" width="35%">'.$period_of_employed.'</td>
            <td border = "1" width="43%"></td>
        </tr>
        <tr>   
            <td bgcolor="#f2f2f2" border = "1" width="20%"><b>Designation</b></td>
            <td colspan="2" border = "1" width="35%">'.$candidate_details["designation"].'</td>
            <td border = "1" width="43%"></td>
        </tr>
        <tr>    
            <td bgcolor="#f2f2f2" border = "1" width="20%"><b>Remuneration</b></td>
            <td colspan="2" border = "1" width="35%">'.$candidate_details["remuneration"].'</td>
            <td border = "1" width="43%"></td>
        </tr>
        <tr>
            <td bgcolor="#f2f2f2" border = "1" width="20%"><b>Reporting Manager Name</b></td>
            <td colspan="2" border = "1" width="35%">'.$candidate_details["r_manager_name"].'</td>
            <td border = "1" width="43%"></td>
        </tr>
        <tr>   
            <td bgcolor="#f2f2f2" border = "1" width="20%"><b>Reason for Leaving</b></td>
            <td colspan="2" border = "1" width="35%">'.$candidate_details["reasonforleaving"].'</td>
            <td border = "1" width="43%"></td>
        </tr>
        <tr>   
            <td bgcolor="#f2f2f2" border = "1" width="30%"><b>Exit formalities completed? (If No, please provide whether pending from Company or Candidate end)</b></td>
            <td colspan="2" border = "1" width="69%"></td>
        
        </tr>
        <tr>   
            <td bgcolor="#f2f2f2" border = "1" width="30%"><b>If any Integrity/Disciplinary issues</b></td>
            <td colspan="2" border = "1"  width="69%"></td>
        
        </tr>
        <tr>   
            <td bgcolor="#f2f2f2" border = "1" width="30%"><b>Eligible for re-hire (If No, please provide reason)</b></td>
            <td colspan="2" border = "1" width="69%"></td>
        
        </tr>
        <tr>   
            <td bgcolor="#f2f2f2" border = "1"  width="30%"><b>Verifiers Name & Designation with Comapny Seal</b></td>
            <td colspan="2" border = "1" width="69%"></td>
        </tr>
        </table>';
       

        $pdf->writeHTML($cans_tbl, true, false, false, false, '');
        
        ob_start(); 
		
		$filename = SITE_BASE_PATH . EMPLOYMENT . $candidate_details['clientid'].'/'.$candidate_details["emp_com_ref"].'_Form'.'.pdf';

        $pdf->Output($filename, 'F');
		
	}

   
}
 