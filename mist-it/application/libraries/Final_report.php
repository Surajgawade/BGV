<?php if(!defined('BASEPATH')) die("No direct Script Access allowed"); 
include_once dirname(__FILE__).'/tcpdf_min/include/tcpdf_include.php';
ini_set("allow_url_fopen", 1);
class Final_report extends TCPDF {
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
		if(DIGITAL_CLIENT_ID != 53) {
		    $image_file = 'assets/images/logo.jpeg';
		      $this->Image($image_file, 5, 7, 50, '', 'jpeg', '', 'T', false, 300, 'R', false, false, 0, false, false, false);
     	}
		$this->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP+3, PDF_MARGIN_RIGHT);

		$this->SetLineStyle(array('width' => 1.2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(5, 46, 125)));
		$this->Line(16, 22, $this->getPageWidth()-8, 22);

		//$this->Line(14, $this->getPageHeight()-15, $this->getPageWidth()-14, $this->getPageHeight()-14);
		//$this->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP+3, PDF_MARGIN_RIGHT);
	}	
	public function Footer() {
		$this->SetY(-15);
		$this->SetFont($this->fontname, '', 10, false);
		$this->Cell(50, 10, 'Confidential' , 0, false, 'L', 0, '', 0, false, 'T', 'M');   
		$this->Cell(90, 10, '', 0, false, 'C', 0, '', 0, false, 'T', 'M'); 
		$this->Cell(50, 9, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M' );
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
		$address_info = $reports_data['address_info'];
		
        $pdf = new Final_report(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('MIST IT Services');
		$pdf->SetTitle('Employee Residential Address Verification Report');
		$pdf->SetSubject('Report of '.$address_info["CandidateName"]);
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
		$bgcolor = '#105cb3';
		$text_color = '#fff';

		$pdf->SetFont($this->fontname,'B',14,false);
		$pdf->SetFillColor(237,237,237);
		$pdf->Cell(186, 11, "Employee Residential Address Verification Report",0, 1, 'C', 1, 2);

		
		$created_on = date('d-M-Y',strtotime($address_info['created_on']));

		
		$period_stay = $address_info["period_stay"]. ' to '.$address_info["period_to"];
		
		$comments = ($address_info['candidate_remarks'] != "") ? $address_info['candidate_remarks'] : "NA";

		$nature_of_residence = ($address_info['nature_of_residence'] == "rwned") ? "Owned" : ucwords($address_info["nature_of_residence"]);


	    $cans_tbl = '<style>table {border-collapse: collapse;} th, td {text-align:left;border: 1px solid #0018;}</style><table width="98%" cellpadding="4">
		<tr>
			<th bgcolor="'.$bgcolor.'" width="120px"><font color="'.$text_color.'"><b>Profile Name</b></font></th>
			<td colspan="3" width="525px">'.ucwords($address_info["CandidateName"]).'</td>
		</tr>
		<tr>
			<th bgcolor="'.$bgcolor.'" width="120px"><font color="'.$text_color.'"><b>Address</b></font></th>
			<td colspan="3" width="525px"> '.ucwords($address_info["address"]).' </td>
		</tr>
		<tr>
			<th bgcolor="'.$bgcolor.'" width="120px"><font color="'.$text_color.'"><b>Address Type</b></font></th>
			<td width="180px">'.ucwords($address_info['address_type']).'</td>
			<th bgcolor="'.$bgcolor.'" width="151px"><font color="'.$text_color.'"><b>Relation With Verifier</b></font></th>
			<td width="180px"> '.ucwords($address_info["relation_verifier_name"]).'</td>
		</tr>
		<tr>
			<th bgcolor="'.$bgcolor.'" width="120px"><font color="'.$text_color.'"><b>Mobile</b></font></th>
			<td width="180px"> '.$address_info['CandidatesContactNumber'].' </td>
			<th bgcolor="'.$bgcolor.'" width="151px"><font color="'.$text_color.'"><b>Reference ID</b></font></th>
			<td width="180px"> '.strtoupper($address_info["cmp_ref_no"]).'</td>
		</tr>
		<tr>
			<th bgcolor="'.$bgcolor.'" width="120px"><font color="'.$text_color.'"><b>Period of Stay</b></font></th>
			<td width="180px"> '.$period_stay.'</td>
			<th bgcolor="'.$bgcolor.'" width="151px"><font color="'.$text_color.'"><b>Verification Date</b></font></th>
			<td width="180px"> '.convert_db_to_display_date($address_info["verification_update_on"],DB_DATE_FORMAT,DISPLAY_DATE_FORMATDATE).'</td>
		</tr>
		<tr>
			
			<th bgcolor="'.$bgcolor.'" width="120px"><font color="'.$text_color.'"><b>Nature of Residence</b></font></th>
			<td width="180px"> '.$nature_of_residence.'</td>
			<th bgcolor="'.$bgcolor.'" width="151px"><font color="'.$text_color.'"><b>Nearest Landmark</b></font></th>
			<td width="180px"> '.ucwords($comments).'</td>
		</tr>
		
		</table>';
		$pdf->SetFont($this->fontname,'',10,false);
		$pdf->writeHTML($cans_tbl, true, false, false, false, '');

		$pdf->SetFont($this->fontname,'B',14,false);
		$pdf->SetFillColor(237,237,237);
		$pdf->Cell(186, 11, "Address shown on the map",0, 1, 'C', 1, 1);

		$address_table = '<style>span {height: 25px;width: 25px;border-radius: 50%;display: inline-block; } table {border-collapse: collapse;} th, td {text-align:left;border: 1px solid #0018;}</style><table width="98%" cellpadding="4">
        		<tr bgcolor="'.$bgcolor.'" color="'.$text_color.'">
        			<th width="320px"> Address</th>
        			<th width="75px"> Source</th>
        			<th width="65px"> Distance</th>
        			<th width="100px"> Location API</th>
        			<th width="60px"> Legend</th>
        		</tr>
        	
        		<tr>
        			<td width="320px">'.ucwords($address_info['address']).'</td>
            		<td width="75px"> Input address</td>
            		<td width="65px"> 0km</td>
            		<td width="100px"> Google Location API</td>
            		<td width="60px"> <span style="background-color:#fab505"> &nbsp;&nbsp;&nbsp;&nbsp;</span></td>
        		</tr>
        		<tr>
        			<td width="320px">'.$address_info['latitude'].','.$address_info['longitude'].'</td>
            		<td width="75px"> GPS</td>
            		<td width="65px"> '.$address_info['place_distance'].'km</td>
            		<td width="100px"> Google Location API</td>
            		<td width="60px"> <span style="background-color:#3595db"> &nbsp;&nbsp;&nbsp;&nbsp;</span></td>
        		</tr>
        	</table>';

        $pdf->SetFont($this->fontname,'',10,false);
        $pdf->writeHTML($address_table, true, false, false, false, '');
        
        $upath = SITE_BASE_PATH . ADDRESS .'address_verification/';
        
		if(file_exists($upath.'capture_'.$address_info['address_id'].'_map.png')) {
			$pdf->Image($upath.'capture_'.$address_info['address_id'].'_map.png', 14, '', 190, 100, '', '', '', false, 150, '', false, false, false, false, false, false);
       	}
        $x = 10;
		$y = '';
		$w = 86;
		$h = 90;
        $attacments = get_attacments($address_info);

		if(!empty($attacments))
		{	
			$pdf->StartTransform();
			$angle= 180;
			$px= 105;
			$py= 36;
			$pdf->Rotate($angle, $px, $py);

			$pdf->AddPage();

			// $html1 = '<style>body{background-color: #ffffff;}</style>';
			// $pdf->writeHTML($html1, true, false, false, false, '');
			// $pdf->writeHTML('<div style="text-align:center;"><b> Photographic Evidence</b> <br />', true, false, false, false, '');

			$pdf->SetFont($this->fontname,'B',14,false);
			
			$pdf->Image($upath.'pic_'.$address_info['address_id'].'_thumbnail.png', $x, $y, 340, 297, '', '', '', false, 150, '', false, false, false, false, false, false);

			$table_html = '<style>table {border-collapse: collapse;} img{height:310px; width:320px;} </style><br><table>';
				$table_html .= '<tr>';
					$table_html .= '<td>
										<img src="'.$upath.$attacments[5]['filename'].'" alt="Snow">
										<p>
											Date and Time : '.$address_info["verification_update_on"].'
											<br>Location : '.$attacments[5]['lat_long'].'
										</p> </td>';
					
				$table_html .= '</tr>';
			$table_html .= '</table>';
			
		/*	$pdf->AddPage();
			$pdf->Cell(96, 11, "Signature",0, 1, 'C', 1, 1);
			$pdf->SetFont($this->fontname,'',11,false);
			$pdf->writeHTML($table_html, true, false, false, false, '');
			$pdf->StopTransform();
			$pdf->ln(18);
			$pdf->SetFont($this->fontname,'B',14,false);
			$pdf->SetFillColor(237,237,237);
			$pdf->Cell(186, 11, "End of Report",0, 1, 'C', 1, 1);*/

			// $counter = 0;
			// foreach ($attacments as $key => $attacment) {
			// 	$counter++;
			// 	$pdf->Image($upath.$attacment['filename'], $x, $y, $w, $h, '', '', '', false, 150, '', false, false, false, false, false, false);
				
			// 	if($counter == 1 || $counter == 3)	
			// 		$x = $x+100;
			// 	if($counter == 2 || $counter == 4) {
			// 		$x = 10;
			// 		$y = $y+130;
			// 	}
			// }
		}
		$filename = SITE_BASE_PATH . ADDRESS . 'address_verification/'.$address_info['address_id'].'_Report'.'.pdf';

        $pdf->Output($filename, 'F');

	}
}