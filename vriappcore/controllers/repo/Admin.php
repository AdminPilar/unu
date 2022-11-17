<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/****************************************************************************
 *
 *   Project: Integrated VRI - [codename: BoobieMovie]  ::: core 3.1.3
 *
 *   Software Architects:
 *     M.Sc. Ramiro Pedro Laura Murillo
 *     Ing. Fred Torres Cruz
 *     Ing. Julio Cesar Tisnado Puma
 *
 *   Begin coding Date: 20 - 03 - 2017
 *
 ***************************************************************************/


include( "absmain/mlLibrary.php" );
include( "absmain/mlotiapi.php" );


define( "REPO_ADMIN", "AdmRepos" );
define( "ANIO_PILAR", "2017" );

$arrMod = array(
            array( 'Id' => 1, 'Nombre' => "Tesis de pregrado"),
            array( 'Id' => 2, 'Nombre' => "Tesis de segunda especialidad"),
            array( 'Id' => 3, 'Nombre' => "Trabajo de investigación"),
            array( 'Id' => 4, 'Nombre' => "Trabajo de suficiencia profesional"),
            array( 'Id' => 5, 'Nombre' => "Trabajo académico (tesina)"),
            array( 'Id' => 10, 'Nombre' => "Tesis de Maestria"),
            array( 'Id' => 11, 'Nombre' => "Tesis de Doctorado")
        );



class Admin extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('dbPilar');
        $this->load->model('dbRepo');

        $this->load->library("GenSession");
        //$this->load->library("GenMailer");
        $this->load->library( "GenSexPdf" );
    }

    public function index()
    {
        if( mlPoorURL() )
            redirect( mlCorrectURL() );

        // no hay datos
        /*
        if( ! $this->gensession->GetData(REPO_ADMIN) ){

            $this->load->view("pilar/admin/header");
            $this->load->view("pilar/admin/login");
            return;
        }
        */

        $this->load->view( "pilar/web/header" );
        $this->load->view( "repos/repos" );
    }

    public function login()
    {
        $user = mlSecurePost("user");
        $pass = mlSecurePost("pass");
        if( !$user or !$pass ) exit;

        if( $user == "flor" && $pass=="lanalgona" )
        {
            $this->gensession->SetAdminLogin (
                REPO_ADMIN, 1,
                "Admin Repos",
                "Romelillo"
            );
        }

        //redirect( "repositorio/admin", "refresh" );
        redirect( "repo/admin", "refresh" );
    }

    // Salir de Admin
    public function logout()
    {
        $this->gensession->SessionDestroy( REPO_ADMIN );
        redirect( base_url("repositorio/admin"), 'refresh');
    }

    /*
    public function ver()
    {
        $this->gensession->IsLoggedAccess(REPO_ADMIN);
        $sess = $this->gensession->GetData(REPO_ADMIN);

        print_r( $_SESSION );
        echo "<hr>";
        print_r( $sess );
    }*/

    public function repolista()
    {
        $nro = 1;
        $table = $this->dbPilar->getSnapView( "Repositorio", "1 ORDER BY IdCarrera, FechaReg DESC" );

        echo "<body style='font-family: Arial; font-size: 11px'> <center>";
        echo "<b> Listado de Tesis Registrados en Repositorio Institucional </b> <br>";
        echo "<b> Universidad Nacional de Ucayali - PUCALLPA </b> <br><br>";

        echo "<table class='table table-condensed' style='font-size: 12px'>";
        echo "<tr style='font-weight:bold'>";
        echo "<td> Nro </td>";
        echo "<td> Codigo </td>";
        echo "<td> Fecha de Registro </td>";
        echo "<td> Nombres y Apellidos </td>";
        echo "<td> Sustentacion </td>";
        echo "<td> Escuela Profesional </td>";
        echo "</tr>";

        foreach( $table->result() as $row )
        {
            $tesistas = str_replace( "\n", "<br>", ("$row->Nombres $row->Apellidos") );
            $fechareg = date_format( date_create($row->FechaReg), 'd/m/Y - H:i' );
            $carrerax = $this->dbRepo->inCarrera( $row->IdCarrera );

            echo "<tr>";
            echo "<td> $nro </td>";
            echo "<td> $row->Codigo </td>";
            echo "<td> $fechareg </td>";
            echo "<td> $tesistas </td>";
            echo "<td> $row->FechaSus </td>";
            echo "<td> $carrerax </td>";
            echo "</tr>";
            $nro++;
        }

        echo "</table>";
        echo "<br> Vicerrectorado de Investigacion";
        echo "<br> <b>Repositorio & Plataforma PILAR</b>";
        echo "<center>";
        echo "</body>";
    }


    public function constancia( $id=0, $rm=0 )
    {
        if( !$id ) return;


        $row = $this->dbPilar->getSnapRow( "Repositorio", "Id=$id" );
        if( !$row ){ echo "Empty"; return; }

        $rep = $this->dbRepo->getSnapRow( "vwLstCarreras", "IdCarrera=$row->IdCarrera" );


        // mostremos el PDF
        //$pdf = new FPDF();

        $pdf = new GenSexPdf();
        $pdf->SetMargins(22, 35, 22);


        for( $i=0; $i<2; $i++ )
        {
            //$pdf->Image( 'includefile/imgs/logoFrmt.png', 7, 10 );
//            $pdf->Image( 'vriadds/pilar/imag/pilar-head.jpg', 7, 10, 200 );

            $pdf->AddPageEx( 'P', '', 2 );


            $str = sprintf( "%04d", $row->Id );

            $pdf->Ln(5);
            $pdf->SetFont( "Times", "B", 11 );
            $pdf->Cell( 40, 7, toUTF("Nro $str"), 1, 1, 'C' );

            $pdf->Ln(10);
            $pdf->SetFont( "Courier", "B", 23 );
            $pdf->Cell( 170, 10, toUTF("CONSTANCIA"), 0, 1, 'C' );


            $str = "El Repositorio Institucional de la Universidad Nacional de Ucayali. "
                 . "Hace constar que:";

            $pdf->Ln(5);
            $pdf->SetFont( "Courier", "", 14 );
            $pdf->MultiCell( 172, 7, toUTF($str), 0, 'J' );

            $pdf->Ln(5);
            $pdf->SetFont( "Courier", "B", 14 );
            //$pdf->Cell( 170, 10, toUTF("$row->Nombres $row->Apellidos"), 0, 1, 'C' );
            $pdf->MultiCell( 172, 6, toUTF("$row->Nombres $row->Apellidos"), 0, 'C' );


            global $arrMod;  //$arrMod=json_decode( json_encode($arrMod) );


            $tipoProy = "Tesis";
            if( $row->IdModal >= 1 && $row->IdModal <= 10 )
                $tipoProy = $arrMod[ $row->IdModal-1 ]["Nombre"];



            $str = "Ha cumplido con registrar su $tipoProy, adjuntando el material electrónico "
                . "cumpliendo con los requisitos exigidos por esta oficina. Los archivos pueden visualizarse en "
                . "el Repositorio Institucional en los siguientes enlaces: ";

            $pdf->Ln(4);
            $pdf->SetFont( "Courier", "", 14 );
            $pdf->MultiCell( 172, 7, toUTF($str), 0, 'J' );

            $pdf->Ln(7);
            $pdf->SetFont( "Courier", "B", 11 );
            $pdf->Cell( 39, 10, toUTF("URL de Tesis:"), 0, 0, 'L' );
            $pdf->Cell( 100, 10, toUTF("$row->Link1"), 0, 1, 'C' );

            $str = "Registra además la fecha de sustentación: $row->FechaSus";

            $pdf->Ln(5);
            $pdf->SetFont( "Courier", "", 14 );
            $pdf->MultiCell( 172, 7, toUTF($str), 0, 'L' );

            // pie de guia
            $pdf->Ln(6);
            $pdf->SetFont( "Courier", "B", 8 );


            $pdf->Cell( 25, 4.5, "MODALIDAD", 0, 0 );
            $pdf->Cell( 30, 4.5, toUTF(": ".strtoupper($tipoProy)), 0, 1 );

            $pdf->Cell( 25, 4.5, "CODIGO", 0, 0 );
            $pdf->Cell( 30, 4.5, toUTF(": $row->Codigo"), 0, 1 );

            $pdf->Cell( 25, 4.5, "FACULTAD", 0, 0 );
            $pdf->Cell( 30, 4.5, toUTF(": $rep->Facultad"), 0, 1 );

            $pdf->Cell( 25, 4.5, "E.PROFESIONAL", 0, 0 );
            $pdf->Cell( 30, 4.5, toUTF(": $rep->Carrera"), 0, 1 );

            $pdf->Cell( 25, 4.5, "Registro", 0, 0 );
            $pdf->Cell( 30, 4.5, toUTF(": $row->FechaReg"), 0, 1 );


            //$this->qrImage( $pdf, 23, 200, $row->Link1, "RP$row->Id" );
            /*
            if( $rm )
                $pdf->Image( "vriadds/pilar/imag/firmaRom.jpg", 90, 220, 70 );
            else
            */

            $pdf->Image( "vriadds/pilar/imag/firmaLeo.jpg", 86, 216 );

            mlQrRotulo( $pdf, 23, 202, $row->Link1 );
            //mlQrRotulo( $pdf, 123, 200, "PIL2-0088" );
        }

        $pdf->Output("I","ConstanciaRepo.pdf");
    }


    public function jsBusqa()
    {
        $this->gensession->IsLoggedAccess(REPO_ADMIN);

        // no logueado
        $codigo = mlSecurePost("codtes");
        $numdni = mlSecurePost("dnites");
        if( ! $codigo ) return;

        // search at internal rep, be sure of it
        $tesist = $this->dbPilar->getSnapView( "Repositorio", "Codigo='$codigo'" );
        if( $tesist->num_rows() >= 1 ) {
            $row = $tesist->row();
            echo sprintf( "Ya fue Registrado con fecha: %s", $row->FechaReg );
            return;
        }

        $subfunc = "repoLoad('dvDisplay','admin/jsSaveNew',new FormData(this))";
        echo '<form class="form-horizontal" method="POST" onsubmit="return '.$subfunc.'">';
        echo '<fieldset>';


        global $arrMod;  $arrMod=json_decode( json_encode($arrMod) );

        if( $codigo == "00" ) {
            echo "<b> Can't connect to: unap.edu.pe </b>";

            echo "<br>Use solo en caso de emergencia. <hr>";

            //--------------------------------------------------------------
            $mod = "<select class='form-control' name=idmod required>"
                . "<option value> (seleccione modalidad) </option>";
            foreach( $arrMod as $ron ) {
                $mod .= "<option value=$ron->Id> $ron->Nombre </option>";
            }
            $mod .= "</select>";
            //--------------------------------------------------------------

            $this->ctrlEdit( 0, 0, 0, "idfacu" );
            $this->ctrlEdit( 0, 0, 0, "idcarr" );

            $this->ctrlInsert( "Modalidad", $mod );
            $this->ctrlEdit( 1, "Escuela Profesional:" );
            $this->ctrlEdit( 1, "Ultimo Semestre:", "", "ultsem" );

            $this->ctrlEdit( 1, "Código de M.:",  "", "codigo" );
            $this->ctrlEdit( 1, "Número de DNI:", "", "numdni" );
            $this->ctrlEdit( 2, "Sustentante(s):", "", "apells" );

            $this->ctrlEdit( 3, "Fecha de Sust:", "", "fechasus" );
            $this->ctrlEdit( 1, "Nro Celular:",   "", "celula" );
            $this->ctrlEdit( 1, "Su E-Mail:",     "", "correo" );
            $this->ctrlEdit( 1, "Enlace URL:",    "", "link1" );
            $this->ctrlEdit( 1, "Enlace URL:",    "", "link2" );

            $this->ctrlSubm( "Registar en Repositorio" );

            echo "</fieldset>";
            echo "</form>";

            return;
        }


        // DATA obtained from OTI
        //
        //$alumno = json_decode( otiGetAlumno($codigo) );
        $alumno = otiGetData( $codigo );

        /*
        if( $alumno->success )
        {
            echo "error: no server";
            return;
        }*/

        echo "<b>Warning 12:</b> JSON Result it has some errors in definition.<hr>";

        if( $alumno->success == false )
        {
            echo "<b> Crap: Argument Error! </b>";
            return;
        }



        // copiar datos y verificacion de DNI
        $data = $alumno->items[0];
        if( $data->documento_numero != $numdni ){
        }


        $arrSemes = array( "DECIMO", "DECIMO PRIMERO", "DECIMO SEGUNDO", "DECIMO TERCERO", "DECIMO CUARTO" );
        if( !in_array($data->matricula->semestre, $arrSemes) ) {
            echo "<b>Error: Son candidatos estudiantes egresados</b> <small><br>Ud. está en: "
                 .$data->matricula->semestre."</small>";
        }


        // revisar carreras permitidas
        $carres = $this->dbRepo->getSnapView( "vwLstCarreras", "Carrera = '$data->escuela'" );
        if( $carres->num_rows() == 0 ){
            echo "Error.07 : Career not found, please ask in VRI";
            return;
        }

        $row = $carres->row();
        //---------------------------------------------------------------------
        $mod = "<select class='form-control' name=idmod required>"
             . "<option value> (seleccione modalidad) </option>";

        /////global $arrMod;  $arrMod=json_decode( json_encode($arrMod) );

        foreach( $arrMod as $ron ) {
            $mod .= "<option value=$ron->Id> $ron->Nombre </option>";
        }
        $mod .= "</select>";
        //---------------------------------------------------------------------

        $ultsem = $data->matricula->anio."-".$data->matricula->periodo." (".$data->matricula->semestre.")";
        $apells = "$data->apellidos $data->nombres";

        $this->ctrlEdit( 0, 0, $row->IdFacultad, "idfacu" );
        $this->ctrlEdit( 0, 0, $row->IdCarrera,  "idcarr" );

        $this->ctrlInsert( "Modalidad", $mod );

        $this->ctrlEdit( 1, "Escuela Profesional:", $data->escuela );
        $this->ctrlEdit( 1, "Ultimo Semestre:",  $ultsem, "ultsem" );

        $this->ctrlEdit( 1, "Código de M.:",  $data->codigo, "codigo" );
        $this->ctrlEdit( 1, "Número de DNI:", $data->documento_numero, "numdni" );
        $this->ctrlEdit( 2, "Sustentante(s):", $apells, "apells" );

        $this->ctrlEdit( 3, "Fecha de Sust:", "", "fechasus" );
        $this->ctrlEdit( 1, "Nro Celular:",   "", "celula" );
        $this->ctrlEdit( 1, "Su E-Mail:",     "", "correo" );
        $this->ctrlEdit( 1, "Enlace URL:",    "", "link1" );
        $this->ctrlEdit( 1, "Enlace URL:",    "", "link2" );

        $this->ctrlSubm( "Registar en Repositorio" );
        echo "</fieldset>";
        echo "</form>";
    }




    function jsSeeRepo()
    {
        $this->gensession->IsLoggedAccess(REPO_ADMIN);

        echo "<table style='text-align:left' class='table table-striped'>";
        echo "<tr>";
        echo "<th> Nro </th>";
        echo "<th> Codigo </th>";
        echo "<th> DNI </th>";
		echo "<th> Const </th>";
        echo "<th> Apellidos y Nombres </th>";
        echo "<th> Opciones </th>";
        echo "</tr>";

        $table = $this->dbPilar->getSnapView('Repositorio',"1 ORDER BY Id DESC");
        $nro = $table->num_rows();
        foreach( $table->result() as $row ){

            $btnPdf = "<a href='admin/constancia/$row->Id' target=_blank class='btn btn-success btn-xs'> Constancia </a>";
            //$btnRom = "<a href='constancia/$row->Id/1' target=_blank class='btn btn-success btn-xs'> Ro </a>";
            $btnEdi = "<button onclick=\"repoLoad('dvDisplay','admin/jsEditar/$row->Id',null)\" class='btn btn-danger btn-xs'> Edita </button>";


			$nombes = "$row->Apellidos $row->Nombres";
			$nombes = str_replace( "\n", "<br>", $nombes );
			$nombes = "<span style='font-weight:bold;font-size:11px;border-bottom:1px solid #AABBFF'> $nombes </span>";

            echo "<tr>";
            echo "<td> $nro </td>";
            echo "<td> $row->Codigo </td>";
            echo "<td> $row->DNI </td>";
            echo "<td> <b>" .sprintf("%04d", $row->Id ). "</b> </td>";
			echo "<td> $nombes </td>";
			echo "<td> $btnPdf | $btnEdi </td>";
            echo "</tr>";

			$nro--;
        }

        echo "</table>";
    }


    public function jsEditar( $id=0 )
    {
        $this->gensession->IsLoggedAccess(REPO_ADMIN);
        if( $id == null ) return;

        $rowda = $this->dbPilar->getSnapRow( "Repositorio", "Id=$id" );
        $table = $this->dbRepo->getSnapView( "dicCarreras" );


        $subfunc = "repoLoad('dvDisplay','admin/jsExecEdit',new FormData(this))";

        echo '<form class="form-horizontal" method="POST" onsubmit="return '.$subfunc.'">';
        echo '<fieldset>';

        //---------------------------------------------------------------------
        $cbo = "<select class='form-control' name=idcarr required>"
             . "<option value> (seleccione carrera) </option>";

        foreach( $table->result() as $row ) {
            $sel = ($rowda->IdCarrera == $row->Id)? "selected" : "";
            $cbo .= "<option value=$row->Id $sel> $row->Nombre </option>";
        }
        $cbo .= "</select>";
        //---------------------------------------------------------------------
        $mod = "<select class='form-control' name=idmod required>"
             . "<option value> (seleccione modalidad) </option>";

        global $arrMod;  $arrMod=json_decode( json_encode($arrMod) );

        foreach( $arrMod as $row ) {
            $sel = ($rowda->IdModal == $row->Id)? "selected" : "";
            $mod .= "<option value=$row->Id $sel> $row->Nombre </option>";
        }
        $mod .= "</select>";
        //---------------------------------------------------------------------

        $this->ctrlInsert( "Carrera", $cbo );
        $this->ctrlInsert( "Modalididad", $mod );

        $this->ctrlEdit( 0, 0, $id, "idrepo" );
        $this->ctrlEdit( 1, "Código:",        $rowda->Codigo,    "codigo" );
        $this->ctrlEdit( 1, "Número de DNI:", $rowda->DNI,       "eldni" );
        $this->ctrlEdit( 1, "Nombres:",       $rowda->Nombres,   "nombes" );
        $this->ctrlEdit( 2, "Apellidos:",     $rowda->Apellidos, "apells" );

        $this->ctrlEdit( 1, "e-mail:",        $rowda->Correo,   "correo" );
        $this->ctrlEdit( 1, "Registro:",      $rowda->FechaReg, "fechreg" );
        $this->ctrlEdit( 1, "Sustentación:",  $rowda->FechaSus, "fechsus" );
        $this->ctrlEdit( 1, "URL Link:",      $rowda->Link1,    "linkes" );

        $this->ctrlSubm( "Modificar Datos" );

        // <a href='javascript:window.close()'> CERRAR </a>";
        echo "</fieldset>";
        echo "</form>";
    }


    private function ctrlInsert( $label, $ctrl )
    {
          echo "<!-- Text input-->";
          echo "<div class='form-group'>";
          echo   "<label class='col-md-3 control-label'> $label </label>";
          echo   "<div class='col-md-8'> $ctrl </div>";
          echo "</div>";
    }

    private function ctrlEdit( $type, $label, $val=null, $name=null, $tip=null, $extFrmt=null )
    {
        switch( $type ) {
            case 0 :
                echo "<input type=hidden name='$name' value='$val'>"; break;
            case 1 :
                $ctrl = "<input type=text name='$name' id='$name' value='$val' placeholder='$tip' class='form-control input-md' $extFrmt>";
                $this->ctrlInsert( $label, $ctrl );
                break;
            case 2 :
                $ctrl = "<textarea class='form-control' name='$name' value='$val' placeholder='$tip' rows=3>$val</textarea>";
                $this->ctrlInsert( $label, $ctrl );
                break;
            case 3:
                $ctrl = "<input name='$name' id='$name' type='date' class='form-control input-md'>";
                $this->ctrlInsert( $label, $ctrl );
                break;
        }
    }

    private function ctrlSubm( $value )
    {
        echo
        "<div class='form-group'>
            <label class='col-md-6 control-label' for='textinput'></label>
            <div class='col-md-5'>
                <input type='submit' class='btn btn-primary col-xs-12' value='$value'>
            </div>
        </div>";
    }

    public function jsExecEdit()
    {
        $this->gensession->IsLoggedAccess(REPO_ADMIN);

        $idrepo = mlSecurePost("idrepo");
        $codigo = mlSecurePost("codigo");
        $idcarr = mlSecurePost("idcarr");
        $eldni  = mlSecurePost("eldni");
        $linke  = mlSecurePost("linkes");

        $correo  = mlSecurePost("correo");
        $fechreg = mlSecurePost("fechreg");
        $fechsus = mlSecurePost("fechsus");

        $modalid = mlSecurePost("idmod");

        $apels  = mlSecurePost("apells");
        $nombe  = mlSecurePost("nombes");


        $this->dbPilar->Update( "Repositorio", array(
            'IdCarrera' => $idcarr,
            'Codigo'    => $codigo,
            'Apellidos' => $apels,
            'Nombres'   => $nombe,
            'IdModal'   => $modalid,
            'Link1'     => $linke,
            'DNI'       => $eldni,
            'Correo'    => $correo,
            'FechaReg'  => $fechreg,
            'FechaSus'  => $fechsus
        ), $idrepo );

        echo "* Revisando duplicados <br>";
        echo "* Los datos de <b>$codigo</b> fueron Guardados <br><br>";
        echo "<button class='btn btn-default' onclick=\"repoLoad('dvDisplay','admin/jsSeeRepo')\"> <span class='glyphicon glyphicon-chevron-right'></span> Hecho!, ahora listar </button>";
    }


    function jsSaveNew()
    {
        //echo "sin implementar";
        $codigo = mlSecurePost( "codigo" );

        // 2. revisar que no existan correo
        if( $this->dbPilar->getSnapRow('Repositorio', "Codigo='$codigo'") ){
            echo "Ya existen datos para este Código";
            return;
        }


        $nombres = "";//strtoupper( mlSecurePost("nombes") );
        $apellid = mb_strtoupper( mlSecurePost("apells") );
        $multixs = mb_strtoupper( mlSecurePost("multis") );


        // gardar ne la base de datos
        //
        $this->dbPilar->Insert( 'Repositorio', array(
            'DNI'        => mlSecurePost( "numdni" ),
            'Codigo'     => mlSecurePost( "codigo" ),
            'NroCelular' => mlSecurePost( "celula" ),
            'IdFacultad' => mlSecurePost( "idfacu" ),
            'IdCarrera'  => mlSecurePost( "idcarr" ),
            'SemReg'     => mlSecurePost( "ultsem" ),
            'Correo'     => mlSecurePost( "correo" ),
            'Link1'      => mlSecurePost( "link1" ),
            'Link2'      => mlSecurePost( "link2" ),
            'FechaSus'   => mlSecurePost( "fechasus" ),
            'IdModal'    => mlSecurePost( "idmod" ),
            'FechaReg'   => mlCurrentDate(),
            'Nombres'    => $nombres,
            'Apellidos'  => $apellid
        ));

        echo "Datos almacenados correctamente. <small>(causilla...)</small>";
    }

}

