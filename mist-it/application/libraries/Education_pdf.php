<?php
require_once dirname(__FILE__).'/tcpdf_min/include/tcpdf_include.php';

// Extend the TCPDF class to create custom Header and Footer
class Education_pdf extends TCPDF {

	//Page header
	var $fontname = '';
    
	function __construct()
	{

		parent::__construct();
    
		//$this->fontname = TCPDF_FONTS::addTTFfont(APPPATH.'libraries/tcpdf_min/fonts/arial.ttf', 'TrueTypeUnicode', '', 96);


	}

	public function Header() {
   	
		$this->SetProtection(array('copy','modify'),null, null, 0, null);
        $this->SetFont($this->fontname, '', 15); // Title      
		/*if(CLIENT_LOGO != '')
		{
			$this->Image(CLIENT_LOGO, 5, 5, 30, '', false, '', 'T', false, 300, 'L', false, false, 0, false, false, false);
		}*/
		if(EDUCATION_REF_NO != '') {
			
            $this->Cell(50, 10, EDUCATION_REF_NO, 1, false, 'L', 0, '', 0, false, 'T', 'M');  

		}
		
		

	}	

    public function Footer() {
        $this->SetY(-15);
       
        $this->SetFont($this->fontname, 'I', 8);
       
        
    }

	public function generate_education_pdf($image_file_array,$file_uploaded_path,$file_name_pdf)
	{
 
	   set_time_limit(0);
		

		$pdf = new EDUCATION_PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		$pdf->SetCreator(PDF_CREATOR);

		$pdf->SetAuthor(AUTHOR);
		$pdf->SetTitle('EDUCATION PDF');
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


        $x = 25;
        $y = '';
        $w = 155;
        $h = 170;
        


		$pdf->AddPage();
		
		//$pdf->setCellMargins(0, 0, 0, 0);
        $pdf->setCellMargins(1, 1, 1, 1);

		$pdf->SetFont($this->fontname,'B',14);   

		$pdf->SetFillColor(237,237,237);
       
        if(!empty($image_file_array))
        {

            $pdf->SetAutoPageBreak(false, 0);

            for ($i = 0; $i < count($image_file_array); $i++) {
            

            $extension = strtolower(pathinfo($image_file_array[$i]['file_name'], PATHINFO_EXTENSION)); 

            $pdf->Image($image_file_array[$i]['folder_path'].'/'.$image_file_array[$i]['file_name'], $x, $y, 0, 0,  $extension , '', '', true, 150, '', false, false, false, false, false, true);
                if($i < count($image_file_array)  - 1)
                {
                 $pdf->AddPage();
                }
            }    
        } 

        $filename = $file_uploaded_path .'/'. $file_name_pdf;

        $pdf->Output($filename, 'F');       

    }
		

}
 