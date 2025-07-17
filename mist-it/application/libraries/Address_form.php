<?php if(!defined('BASEPATH')) die("No direct Script Access allowed"); 
include_once dirname(__FILE__).'/tcpdf_min/include/tcpdf_include.php';
ini_set("allow_url_fopen", 1);
class Address_form extends TCPDF {
	var $fontname = '';
	function __construct()
	{
		parent::__construct();
		//$this->fontname = TCPDF_FONTS::addTTFfont(dirname(__FILE__).'/tcpdf_min/fonts/arial.ttf', 'TrueTypeUnicode', '', 96);
	}
	public function Header() {
		// $html = '<table cellspacing="0" cellpadding="1" border="0"><tr><td rowspan="3"></td><td><b></b></td></tr></table>';
  //    	$this->writeHTMLCell($w = 230, $h = 30, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = 'top', $autopadding = true);
     	/*if(CLIENT_LOGO != "")
		{
		    $image_file = CLIENT_LOGO_PATH.CLIENT_LOGO;
		    $this->Image($image_file, 1, 2, 27, '', '', '', 'T', false, 210, 'L', false, false, 0, false, false, false);
		}*/
		
		$image_file = 'assets/images/logo.jpeg';
		$this->Image($image_file, 5, 7, 50, '', 'jpeg', '', 'T', false, 300, 'R', false, false, 0, false, false, false);
     
		$this->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP+3, PDF_MARGIN_RIGHT);

		$this->SetLineStyle(array('width' => 1.2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(5, 46, 125)));
		$this->Line(16, 22, $this->getPageWidth()-8, 22);

		//$this->Line(14, $this->getPageHeight()-15, $this->getPageWidth()-14, $this->getPageHeight()-14);
		//$this->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP+3, PDF_MARGIN_RIGHT);
	}	
	public function Footer() {
		$this->SetY(-15);
		$this->SetFont($this->fontname, '', 10, false);
		$this->Cell(50, 10, 'www.mistitservices.com' , 0, false, 'L', 0, '', 0, false, 'T', 'M');   
		$this->Cell(90, 10, '', 0, false, 'C', 0, '', 0, false, 'T', 'M'); 
		$this->Cell(50, 9, 'I-185,Garwhali Mohalla, Block I, Laxmi Nagar, Delhi-110092', 0, false, 'R', 0, '', 0, false, 'T', 'M' );
	}
	public function border($pdf)
	{
		$pdf->Line(15, 35, 195, 35); //top
		$pdf->Line(195, 35, 195,  242); //left
		$pdf->Line(15, 35,15, 242); //right
		$pdf->Line(15, 242, 195, 242);//bottom
		$pdf->Cell(0, 0, 'Annexure',0, 1, 'C');
		$pdf->writeHTML('<br/><br/>', true, false, false, false, '');
	}
	public function generate_pdf($reports_data)
	{
		$address_info = $reports_data;
		
        $pdf = new Address_form(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('MIST IT Services');
		$pdf->SetTitle('MIST IT SERVICES PVT LTD ADDRESS VERIFICATION FORM');
		$pdf->SetSubject('MIST IT SERVICES PVT LTD ADDRESS VERIFICATION FORM');
		$pdf->SetKeywords('Design and developed by tcpdf');
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
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		if (@file_exists(dirname(__FILE__).'/tcpdf_min/lang/eng.php')) {
		    require_once(dirname(__FILE__).'/tcpdf_min/lang/eng.php');
		    $pdf->setLanguageArray($l);
		}
		$pdf->AddPage();
		$pdf->setCellMargins(1,1,1,1);
		//$bgcolor = '#f2f2f2';
		$bgcolor = '#dbd7d7';
		$text_color = '#000000';
		$x = 90;
		$y = 165;
		$w = 70;
		$h = 40;

		$pdf->SetFont($this->fontname,'B',14,false);
		$pdf->SetFillColor(237,237,237);
		$pdf->Cell(186, 11, "MIST IT SERVICES PVT LTD ADDRESS VERIFICATION FORM",0, 1, 'C', 1, 2);
         
        $check_tbl = '<style>table {border-collapse: collapse;} th, td {text-align:left;border: 1px solid #0018;}</style><br><table width="98%">
		<tr>
			<th bgcolor="'.$bgcolor.'" ><font color="'.$text_color.'"><b>Check id</b></font></th>
			<th bgcolor="'.$bgcolor.'" ><font color="'.$text_color.'"><b>Initiation date (DD/MM/YY)</b></font></th>
			<th bgcolor="'.$bgcolor.'" ><font color="'.$text_color.'"><b>Date of Visit (DD/MM/YYYY)</b></font></th>
			<th bgcolor="'.$bgcolor.'" ><font color="'.$text_color.'"><b>Status</b></font></th>
	    </tr>
	   	<tr>
		    <td> '.$address_info['add_com_ref'].'</td>
		    <td> '.$address_info['iniated_date'].' </td>
		    <td> '.$address_info['date_of_visit'].' </td>
		    <td> '.$address_info['mode_of_verification'].' - '.$address_info['status'].' </td>
		 </tr>
		</table>';
		$pdf->SetFont($this->fontname,'',10,false);
		$pdf->writeHTML($check_tbl, true, false, false, false, '');

		$pdf->SetFont($this->fontname,'B',14,false);

		$pdf->Cell(186, 11, "Candidate details",0, 1, 'C', 1, 2);

		$cand_tbl = '<style>table {border-collapse: collapse;} th, td {text-align:left;border: 1px solid #0018;}</style><br><table width="98%">
		<tr>
			<td bgcolor="'.$bgcolor.'" width="200px"><b> Name of the candidate</b> </td>
		    <td width="440px"> '.$address_info['candidate_name'].' </td>
		</tr> 
		<tr>   
		    <td bgcolor="'.$bgcolor.'" width="200px"><b> Provided Address </b></td>
		    <td width="440px"> '.$address_info['provided_address'].$address_info['address_action'].'</td>
		</tr> 	
	    <tr>   
		    <td bgcolor="'.$bgcolor.'" width="200px"><b> Contact No  </b></td>
		    <td width="440px"> '.$address_info['candidate_contact'].' </td>
		</tr> 
	   
		</table>';
		$pdf->SetFont($this->fontname,'',10,false);
		$pdf->writeHTML($cand_tbl, true, false, false, false, '');

		$pdf->SetFont($this->fontname,'B',14,false);


		$pdf->Cell(186, 11, "Verified Information",0, 1, 'C', 1, 2);

		$verification_tbl = '<style>table {border-collapse: collapse;} th, td {text-align:left;border: 1px solid #0018;}</style><br><table width="98%">
		<tr>
			<td bgcolor="'.$bgcolor.'" width="200px"><b> Period of Stay </b></td>
		    <td width="440px"> '.$address_info['period_stay_from'].' </td>
		</tr> 
		<tr>   
		    <td bgcolor="'.$bgcolor.'" width="200px"><b> Residency status </b></td>
		    <td width="440px"> '.$address_info['resident_status'].' </td>
		</tr> 	
	    <tr>   
		    <td bgcolor="'.$bgcolor.'" width="200px"><b> Document proof provided </b></td>
		    <td width="440px"> '.$address_info['addr_proof_collected'].' </td>
		</tr> 
	    
	    <tr>   
		    <td bgcolor="'.$bgcolor.'" width="200px"><b> Verified by </b></td>
		    <td width="440px"> '.$address_info['verified_by'].' </td>
		</tr> 
	   
		</table>';
		$pdf->SetFont($this->fontname,'',10,false);
		$pdf->writeHTML($verification_tbl, true, false, false, false, '');
		$pdf->SetFont($this->fontname,'B',14,false);
        
		
		 $response_tbl = '<style>table {border-collapse: collapse;} th, td {text-align:left;border: 1px solid #0018;}</style><br><table width="98%">
		<tr>
			<th bgcolor="'.$bgcolor.'" ><font color="'.$text_color.'"><b>Respondent’s Name</b></font></th>
			<th bgcolor="'.$bgcolor.'" ><font color="'.$text_color.'"><b>Contact details</b></font></th>
			<th bgcolor="'.$bgcolor.'" ><font color="'.$text_color.'"><b>Field Executive Name</b></font></th>
	    </tr>
	   	<tr>
	   	    <td> '.$address_info['verified_by'].' </td>
		    <td> '.$address_info['candidate_contact'].'</td>
		    <td> '.$address_info['executive_name'].' </td>  
		 </tr>
		</table>';
		$pdf->SetFont($this->fontname,'',10,false);
		$pdf->writeHTML($response_tbl, true, false, false, false, ''); 

		 $remark_tbl = '<style>table {border-collapse: collapse;} th, td {text-align:left;border: 1px solid #0018;}</style><br><table width="98%">
		<tr>
			<td bgcolor="'.$bgcolor.'" width="200px" height="150px"><b>Signature</b></td>
		    <td width="440px" height="150px">';

		  $upath = SITE_BASE_PATH . ADDRESS .'vendor_file/';

   
		  $pdf->Image($upath.$address_info['transaction_id'].'_signature.png', $x, $y, $w, $h,  'png' , '', '', true, 150, '', false, false, false, false, false, false); 
          
		  
		$remark_tbl .= '</td>
		</tr> 
		<tr>   
		    <td bgcolor="'.$bgcolor.'" width="200px"><b>Remarks</b></td>
		    <td width="440px"> '.$address_info['remarks'].' </td>
		</tr> 	
	   
	   
		</table>';
		$pdf->SetFont($this->fontname,'',10,false);
		$pdf->writeHTML($remark_tbl, true, false, false, false, '');

		/*$pdf->Cell(186, 11, "Instructions to be Read and Signed by Respondent",0, 1, 'C', 1, 2);


		$html_instructions_1 ='<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;No Fees are payable to the “Field Executive” during address verification. It is FREE. Please answer questions stated on the form. Do not provide any additional information which has no relation to address verification.</p> <br>';
		 
        $pdf->writeHTML($html_instructions_1, false, false, true, false, '');

        $html_instructions_2 ='<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;External house photograph is required for authenticity of the report. Please do not allow any other picture to be taken. For Example: - Inside house photo.</p
        >';
		 
        $pdf->writeHTML($html_instructions_2, false, false, true, false, '');*/
	

		$filename = SITE_BASE_PATH . ADDRESS . 'vendor_file/'.$address_info['transaction_id'].'_Report'.'.pdf';

        $pdf->Output($filename, 'F');

	}
}