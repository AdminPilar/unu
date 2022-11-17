<?php

/*
include( "qrclass.php" );

//public function displayPNG($w=100, $background=array(255,255,255), $color=array(0,0,0), $filename = null, $quality = 0)


$qr = new QRcode( "cadena 10 20", "Q" );

//$qr->displayPNG( 100 );
print_r( $qr );
*/


//$qr = new QRcode( $code, 'Q'); // error level : L, M, Q, H
        //$qr->displayFPDF($pdf, $xpos, $ypos, 16, array(255,255,255), array(0,0,0) );
        ////$qr->displayFPDF( $this, );
        //$this->Text( $xpos, $ypos, $code );


include('fpdf/fpdf.php');
include('qrclass.php');

$qrcode = new QRcode('http://vriunap.pe/cursos/?qr=PIL-001', 'Q'); // error level : L, M, Q, H
$qrcode->displayPNG( 100 );

/*
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',8);
$pdf->Image('http://rpedrolm-001-site5.itempurl.com/sap/imgs/bold.jpg',0,0,85,54); //background
$qrcode->displayFPDF($pdf, 60, 30, 20, array(255,255,255), array(0,0,0));
$pdf->Output();
*/

?>
