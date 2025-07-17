<?php
require_once dirname(__FILE__) . '/vendor/autoload.php';
use setasign\Fpdi\Fpdi;

class Pdf_text extends Fpdi {
    function __construct()
    {
        parent::__construct();
    }

    


    function watermark_pdf($folder_path,$filename,$watermark_text)
    {


        // initiate FPDI
        $pdf = new Fpdi();

        $pagecount = $pdf->setSourceFile($folder_path.'/'.$filename);
        for($i = 1; $i <= $pagecount; $i++){
            $pdf->addPage();
            $tplidx = $pdf->importPage($i);
            $pdf->useTemplate($tplidx, 0, 3, null);
            // now write some text above the imported page
            $pdf->SetFont('', '', 0);
            if ($pdf->PageNo() == 1 ) {
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(0, 0,$watermark_text, 0, 0, 'L');
                $pdf->Ln();
            }
        }


        $pdf->Output($folder_path.'/'.$filename, 'F');

    }
}
?>