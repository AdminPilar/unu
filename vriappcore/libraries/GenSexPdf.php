
<?php if ( ! defined('BASEPATH')) exit('No se permite el acceso directo al script');


//--------------------------------------------------------------------------------
//  Custom Library : Fast and Sexy PDF maker
//   coded by: M.Sc. Ramiro Pedro Laura Murillo
//   dated on: 21/02/2017
//--------------------------------------------------------------------------------

include( "absmain/fpdf/fpdf.php" );
//include( "absmain/qrclass.php" );



class GenSexPdf extends FPDF {

    public $numhead = 1;
    public $numfoot = 5;

    public $LeftMargin  = 15;
    public $RightMargin = 194;
    public $width       = 177;

    public $imgWidth = 198;

    // para el parte
    public $fac, $car, $tip, $fech;


    function __construct($orientation='P', $unit='mm', $size='A4')
    {
        parent::__construct( $orientation, $unit, $size );
    }

    public function isOn()
    {
        echo "custom lib: GenSexPdf loaded.<br>";
    }

    //-------------------------------------------------------------------------
    function Header()
    {
        // ojo se construye y se fija para todo el doc
        if( $this->DefOrientation == 'L' )
        {
            $this->RightMargin = 284;
            $this->width = 272;
            $this->imgWidth = 284;
        }

        // saltamos a la cabecera adecuada por hoja
        if( $this->numhead == 1 )  $this->Header1();  // formal
        if( $this->numhead == 2 )  $this->Header2();  // iconos estilizados
        if( $this->numhead == 3 )  $this->Header3();  // RRHH
    }

    function Footer()
    {
        if( $this->numhead == 0 ) return;
        if( $this->numfoot == 0 ) return;

        /*
        $this->Cell(0,10, toUTF('Página - ').$this->PageNo(), 0, 1, 'C');
        $this->Cell(0,0, "Fecha: ".date('Y-m-d\ H:i:s'), 0, 0, 'C');
        */

        // Position at 1.5 cm from bottom
        $this->SetY(-12);
        $this->SetFont('Arial','B',6);

        // Page number
        $this->SetDrawColor(90,90,90);
        if( $this->DefOrientation == 'L' )
            $this->Line( $this->LeftMargin, 193, $this->RightMargin, 193 );
        else
            $this->Line( $this->LeftMargin, 287, $this->RightMargin, 287 );

        //$this->Cell(0,10, toUTF('Página - ').$this->PageNo(), 0, 0, 'C');
       // $this->Cell(170,10, toUTF('[Oficina de Plataforma de Investigación y Desarrollo ]'), 0, 0, 'C');
    }

    //Agregado unuv1.0 - estado acta de proyecto
    function AddPageEx2( $orient='', $size='', $head=1, $foot=10, $fac, $car, $idFac)
    {   
        $this->numhead = $head;
        
       
        $this->numfoot = $foot;

        $this->fac  = $fac;
        $this->car  = $car;
        $this->idFac = $idFac;
        //$this->tip  = $tip;
        //$this->fech = $fech;

        $this->AddPage( $orient, $size );
        $this->DefOrientation = $orient;
    }

    // composed function isn't derived
    function Header1()
    {
        $this->Ln(1);
        $this->SetFont('Arial','B',9);

        $this->Image('absmain/imgs/unap.png', $this->LeftMargin,  10, 20);
        $this->Image('absmain/imgs/'.$this->idFac.'.png' , $this->RightMargin-19, 10, 18); //modificado unuv1.0

        //$this->Ln(1);
        //$this->SetFont('Arial','B',10);
        $this->Cell( $this->width, 6, toUTF("UNIVERSIDAD NACIONAL DE UCAYALI"), 0, 1,'C',0);
        //agregado unuv1.0
        $this->SetFont('Arial','B',10);
        $this->SetTextColor(140,140,140);
        $this->Cell( $this->width, 6, toUTF("FACULTAD DE $this->fac"), 0, 1,'C',0);

        /*$this->SetFont('Arial','B',13);
        $this->SetTextColor(140,140,140);
        $this->Cell( $this->width, 6, toUTF("VICERRECTORADO DE INVESTIGACIÓN"), 0, 1,'C',0);//comentado unuv1.0*/

        $this->SetFont('Arial','',10);
        $this->SetTextColor(100,100,100);
        //$this->Cell( $this->width, 6, toUTF("Plataforma de Investigación Universitaria Integrada a la Labor Académica"), 0, 1,'C',0);
        $this->Cell( $this->width, 6, toUTF("ESCUELA PROFESIONAL DE $this->car"), 0, 1,'C',0);

        $this->SetTextColor(0,0,0);
        $this->SetFont('Arial','',6);
        $this->Cell( $this->width, 5, toUTF("Carretera Federico Basadre Km 6200 - Pucallpa - Ucayali"), 0, 1,'C',0);

        $this->SetDrawColor(70,70,70);
        $this->Line( $this->LeftMargin, 35, $this->RightMargin, 35 );
        $this->Ln(12);
    }

    function Header2()
    {
        $this->Image( "vriadds/pilar/imag/pilar-head.jpg", 10, 7, 190 );
        $this->SetDrawColor( 120, 120, 120 );
        $this->Line( $this->LeftMargin-5, 27, $this->RightMargin+5, 27 );
    }

    function Header3()
    {
        $this->SetFont('Arial','',7);
        $this->Cell( 110, 4,'Universidad Nacional de Ucayali - PUCALLPA', 0, 0, "C", 0 );  $this->Cell( 80, 4,'' );  $this->Cell( 80, 4,'OFICINA DE RECURSOS HUMANOS', 0, 1, "C", 0 );
        $this->Cell( 110, 4, toUTF("FACULTAD DE $this->fac"), 0, 0, "C", 0 );             $this->Cell( 80, 4,'' );  $this->Cell( 80, 4,'UNIDAD DE CONTROL DE ASISTENCIA', 0, 1, "C", 0 );
        $this->Cell( 110, 4, toUTF("ESCUELA PROFESIONAL DE $this->car"), 0, 1, "C", 0 );


        $this->Ln(5);
        $this->SetFont('Arial','B',11);
        $this->Cell( 267, 7, toUTF("PARTE DE ASISTENCIA DE DOCENTES $this->tip"), 0, 1, "C", 0 );
        $this->Cell( 178, 7, toUTF("FECHA: " . mlFechaSolo($this->fech) ), 0, 1, "L", 0 );
    }

    //-------------------------------------------------------------------------
    function AddPageEx( $orient='', $size='', $head=1, $foot=10, $fac="", $car="", $tip="", $fech="" )
    {
        $this->numhead = $head;
        $this->numfoot = $foot;

        $this->fac  = $fac;
        $this->car  = $car;
        $this->tip  = $tip;
        $this->fech = $fech;

        $this->AddPage( $orient, $size );
        $this->DefOrientation = $orient;
    }


    function CodeQR( $xpos, $ypos, $code, $siz=152 )
    {
        /*
        $urlCode = urlencode ( $code );
        // "http://chart.googleapis.com/chart?chs=150x150&cht=qr&chl=PedroEstuvo&.png";
        //$urlImage = "http://chart.googleapis.com/chart?"
        //          . "cht=qr&chs=$siz" ."x" ."$siz&chl=$urlCode&.png";
        $urlImage = "https://api.qrserver.com/v1/create-qr-code/?"
                  . "size=$siz" ."x" ."$siz&data=$urlCode&.png";

        $this->Image( $urlImage, $xpos, $ypos);
        */


        ////$urlImage = "http://phpqrcode.sourceforge.net/qrsample.php?data=$code&ecc=M&matrix=5&.png";

        $urlCode = urlencode ( $code );
        $urlImage = "https://chart.googleapis.com/chart?chs=$siz" ."x" ."$siz&cht=qr&chl=$urlCode&choe=UTF-8&.png";
        $this->Image( $urlImage, $xpos-2, $ypos-2);


        //$qr = new QRcode( $code, 'Q'); // error level : L, M, Q, H
        //$qr->displayFPDF($pdf, $xpos, $ypos, 16, array(255,255,255), array(0,0,0) );
        ////$qr->displayFPDF( $this, );
        //$this->Text( $xpos, $ypos, $code );
    }

    //-------------------------------------------------------------------------
    function BarCode39( $xpos, $ypos, $code, $baseline=0.7, $height=8 )
    {

        $barChar['0'] = 'nnnwwnwnn';
        $barChar['1'] = 'wnnwnnnnw';
        $barChar['2'] = 'nnwwnnnnw';
        $barChar['3'] = 'wnwwnnnnn';
        $barChar['4'] = 'nnnwwnnnw';
        $barChar['5'] = 'wnnwwnnnn';
        $barChar['6'] = 'nnwwwnnnn';
        $barChar['7'] = 'nnnwnnwnw';
        $barChar['8'] = 'wnnwnnwnn';
        $barChar['9'] = 'nnwwnnwnn';
        $barChar['A'] = 'wnnnnwnnw';
        $barChar['B'] = 'nnwnnwnnw';
        $barChar['C'] = 'wnwnnwnnn';
        $barChar['D'] = 'nnnnwwnnw';
        $barChar['E'] = 'wnnnwwnnn';
        $barChar['F'] = 'nnwnwwnnn';
        $barChar['G'] = 'nnnnnwwnw';
        $barChar['H'] = 'wnnnnwwnn';
        $barChar['I'] = 'nnwnnwwnn';
        $barChar['J'] = 'nnnnwwwnn';
        $barChar['K'] = 'wnnnnnnww';
        $barChar['L'] = 'nnwnnnnww';
        $barChar['M'] = 'wnwnnnnwn';
        $barChar['N'] = 'nnnnwnnww';
        $barChar['O'] = 'wnnnwnnwn'; 
        $barChar['P'] = 'nnwnwnnwn';
        $barChar['Q'] = 'nnnnnnwww';
        $barChar['R'] = 'wnnnnnwwn';
        $barChar['S'] = 'nnwnnnwwn';
        $barChar['T'] = 'nnnnwnwwn';
        $barChar['U'] = 'wwnnnnnnw';
        $barChar['V'] = 'nwwnnnnnw';
        $barChar['W'] = 'wwwnnnnnn';
        $barChar['X'] = 'nwnnwnnnw';
        $barChar['Y'] = 'wwnnwnnnn';
        $barChar['Z'] = 'nwwnwnnnn';
        $barChar['-'] = 'nwnnnnwnw';
        $barChar['.'] = 'wwnnnnwnn';
        $barChar[' '] = 'nwwnnnwnn';
        $barChar['*'] = 'nwnnwnwnn';
        $barChar['$'] = 'nwnwnwnnn';
        $barChar['/'] = 'nwnwnnnwn';
        $barChar['+'] = 'nwnnnwnwn';
        $barChar['%'] = 'nnnwnwnwn';
        //-----------------------------------------------
        $wide   = $baseline;
        $narrow = $baseline / 3 ;
        $gap    = $narrow;
        //-----------------------------------------------
        $this->SetFont('Arial','',6);
        $this->Text($xpos+16, $ypos + $height + 2.3, $code);
        $this->SetFillColor(0);

        $this->Rect( $xpos-2, $ypos-1, 1+strlen($code)*4.9, $height+4 );
        //-----------------------------------------------
        $code = '*'.strtoupper($code).'*';
        for($i=0; $i<strlen($code); $i++) {
            $char = $code[$i];
            if( !isset($barChar[$char]) ) {
                $this->Error('Invalid char in code: '.$char);
            }
            $seq = $barChar[$char];
            for($bar=0; $bar<9; $bar++){
                if($seq[$bar] == 'n'){
                    $lineWidth = $narrow;
                }else{
                    $lineWidth = $wide;
                }
                if($bar % 2 == 0){
                    $this->Rect($xpos, $ypos, $lineWidth, $height, 'F');
                }
                $xpos += $lineWidth;
            }
            $xpos += $gap;
        }// en for bar code
    }
    function BarCode40( $xpos, $ypos, $code, $baseline=1.8, $height=15 )
    {

        $barChar['0'] = 'nnnwwnwnn';
        $barChar['1'] = 'wnnwnnnnw';
        $barChar['2'] = 'nnwwnnnnw';
        $barChar['3'] = 'wnwwnnnnn';
        $barChar['4'] = 'nnnwwnnnw';
        $barChar['5'] = 'wnnwwnnnn';
        $barChar['6'] = 'nnwwwnnnn';
        $barChar['7'] = 'nnnwnnwnw';
        $barChar['8'] = 'wnnwnnwnn';
        $barChar['9'] = 'nnwwnnwnn';
        $barChar['A'] = 'wnnnnwnnw';
        $barChar['B'] = 'nnwnnwnnw';
        $barChar['C'] = 'wnwnnwnnn';
        $barChar['D'] = 'nnnnwwnnw';
        $barChar['E'] = 'wnnnwwnnn';
        $barChar['F'] = 'nnwnwwnnn';
        $barChar['G'] = 'nnnnnwwnw';
        $barChar['H'] = 'wnnnnwwnn';
        $barChar['I'] = 'nnwnnwwnn';
        $barChar['J'] = 'nnnnwwwnn';
        $barChar['K'] = 'wnnnnnnww';
        $barChar['L'] = 'nnwnnnnww';
        $barChar['M'] = 'wnwnnnnwn';
        $barChar['N'] = 'nnnnwnnww';
        $barChar['O'] = 'wnnnwnnwn'; 
        $barChar['P'] = 'nnwnwnnwn';
        $barChar['Q'] = 'nnnnnnwww';
        $barChar['R'] = 'wnnnnnwwn';
        $barChar['S'] = 'nnwnnnwwn';
        $barChar['T'] = 'nnnnwnwwn';
        $barChar['U'] = 'wwnnnnnnw';
        $barChar['V'] = 'nwwnnnnnw';
        $barChar['W'] = 'wwwnnnnnn';
        $barChar['X'] = 'nwnnwnnnw';
        $barChar['Y'] = 'wwnnwnnnn';
        $barChar['Z'] = 'nwwnwnnnn';
        $barChar['-'] = 'nwnnnnwnw';
        $barChar['.'] = 'wwnnnnwnn';
        $barChar[' '] = 'nwwnnnwnn';
        $barChar['*'] = 'nwnnwnwnn';
        $barChar['$'] = 'nwnwnwnnn';
        $barChar['/'] = 'nwnwnnnwn';
        $barChar['+'] = 'nwnnnwnwn';
        $barChar['%'] = 'nnnwnwnwn';
        //-----------------------------------------------
        $wide   = $baseline;
        $narrow = $baseline / 3 ;
        $gap    = $narrow;
        //-----------------------------------------------
        $this->SetFont('Arial','',8);
        $this->Text($xpos, $ypos + $height + 2.3, $code);
        // $this->SetFillColor(0);

        // $this->Rect( $xpos-2, $ypos-1, 1+strlen($code)*4.9, $height+4 );
        //-----------------------------------------------
        $code = '*'.strtoupper($code).'*';
        for($i=0; $i<strlen($code); $i++) {
            $char = $code[$i];
            if( !isset($barChar[$char]) ) {
                $this->Error('Invalid char in code: '.$char);
            }
            $seq = $barChar[$char];
            for($bar=0; $bar<9; $bar++){
                if($seq[$bar] == 'n'){
                    $lineWidth = $narrow;
                }else{
                    $lineWidth = $wide;
                }
                if($bar % 2 == 0){
                    $this->Rect($xpos, $ypos, $lineWidth, $height, 'F');
                }
                $xpos += $lineWidth;
            }
            $xpos += $gap;
        }// en for bar code
    }
    // Adding MultiCell height

    var $widths;
    var $aligns;
    var $fonti;
    function SetWidths($w)
    {
        //Set the array of column widths
        $this->widths=$w;
    }

    function SetAligns($a)
    {
        //Set the array of column alignments
        $this->aligns=$a;
    }
    function setFontSize($f){
        $this->fonti=$f;
    }

    function Row($data)
    {
        //Calculate the height of the row
        $nb=0;
        for($i=0;$i<count($data);$i++)
            $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
        $h=5*$nb;
        //Issue a page break first if needed
        $this->CheckPageBreak($h);
        //Draw the cells of the row
        for($i=0;$i<count($data);$i++)
        {
            $f=$this->fonti[$i];
            $w=$this->widths[$i];
            $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
            //Save the current position
            $x=$this->GetX();
            $y=$this->GetY();
            //Draw the border
            $this->Rect($x,$y,$w,$h);
            $this->SetFont( "Arial", '', $f );
            //Print the text
            $this->MultiCell($w,5,$data[$i],0,$a);
            //Put the position to the right of the cell
            $this->SetXY($x+$w,$y);
        }
        //Go to the next line
        $this->Ln($h);
    }

    function CheckPageBreak($h)
    {
        //If the height h would cause an overflow, add a new page immediately
        if($this->GetY()+$h>$this->PageBreakTrigger)
            $this->AddPage($this->CurOrientation);
    }


     function EstablecerFuente($nombre_f,$estilo_f,$narchivo_f)
    {
       
        $this->AddFont($nombre_f,$estilo_f,$narchivo_f);
    }

    function NbLines($w,$txt)
    {
        //Computes the number of lines a MultiCell of width w will take
        $cw=&$this->CurrentFont['cw'];
        if($w==0)
            $w=$this->w-$this->rMargin-$this->x;
        $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
        $s=str_replace("\r",'',$txt);
        $nb=strlen($s);
        if($nb>0 and $s[$nb-1]=="\n")
            $nb--;
        $sep=-1;
        $i=0;
        $j=0;
        $l=0;
        $nl=1;
        while($i<$nb)
        {
            $c=$s[$i];
            if($c=="\n")
            {
                $i++;
                $sep=-1;
                $j=$i;
                $l=0;
                $nl++;
                continue;
            }
            if($c==' ')
                $sep=$i;
            $l+=$cw[$c];
            if($l>$wmax)
            {
                if($sep==-1)
                {
                    if($i==$j)
                        $i++;
                }
                else
                    $i=$sep+1;
                $sep=-1;
                $j=$i;
                $l=0;
                $nl++;
            }
            else
                $i++;
        }
        return $nl;
    }

    function cabeceraHorizontal($cabecera)
    {
        $this->SetXY(20,90);
        $this->SetFont('Arial','B',10);
        $this->SetFillColor(2,157,116);//Fondo verde de celda
        $this->SetTextColor(240, 255, 240);
        foreach($cabecera as $fila)
        {
            //Atención!! el parámetro valor 0, hace que sea horizontal
            $this->Cell(35,7, utf8_decode($fila),1, 0 , 'L',true );
        }
    }
 
    function datosHorizontal($datos)
    {
        $this->SetXY(20,97);
        $this->SetFont('Arial','',10);
        $this->SetTextColor(3, 3, 3); //Color del texto: Negro
        //Siendo un array tipo: $datos => $fila
        //Significa que $datos tiene 'nombre' 'apellido' 'matricula'
        //$fila tiene cada valor de los antes mencionados
        foreach($datos as $fila)
        {

            $this->Cell(35,7, utf8_decode($fila[0]),1, 0 , 'L' );
            $this->Cell(35,7, utf8_decode($fila[1]),1, 0 , 'L' );
            $this->Cell(35,7, utf8_decode($fila[2]),1, 0 , 'L' );
            $this->Cell(35,7, utf8_decode($fila[3]),1, 0 , 'L' );
            $this->SetFont('Arial','B',10);
            $this->Cell(35,7, utf8_decode($fila[4]),1, 0 , 'L' );
            $this->SetFont('Arial','',10);
            $this->Ln();//Salto de línea para generar otra fila
        }
    }
 
    //Integrando cabecera y datos en un solo método
    function tablaHorizontal($cabeceraHorizontal, $datosHorizontal)
    {
        $this->cabeceraHorizontal($cabeceraHorizontal);
        $this->datosHorizontal($datosHorizontal);
    }
}



?>
