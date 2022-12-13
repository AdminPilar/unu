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
 ************************************************************************************************************/


// modificacion 2018-04 : Cambios y Edades docente
// modificacion 2018-07 : Reportes y correos de vencimiento

/*

SELECT IdDocente,
       ( SELECT count(*) FROM docEstudios WHERE docEstudios.IdDocente=vxDatDocentes.IdDocente ) AS Grados
  FROM vxDatDocentes ORDER BY Grados


SELECT * FROM tesTramites AS A, tblTesistas AS T WHERE A.IdTesista1=T.Id AND T.Codigo LIKE '14%' ORDER BY T.Codigo


SELECT A.Codigo, A.IdCarrera AS Car, C.Nombre, A.DNI, T.Codigo AS CodTramite,
       T.Estado, A.Apellidos, A.Nombres, A.NroCelular, A.SemReg
  FROM tesTramites AS T, tblTesistas AS A, desarrollo_absmain_testing.dicCarreras AS C
 WHERE T.IdTesista1 = A.Id
   AND A.Codigo LIKE '14%'
   AND T.Estado >= 6
   AND T.IdCarrera = C.Id
 ORDER BY T.IdCarrera, T.Codigo

*/



include( "absmain/mlLibrary.php" );
date_default_timezone_set('America/Lima'); //Agregado unuv1.0


define( "PILAR_ADMIN", "AdmPilar-III" );
define( "ANIO_PILAR", "2020" );
// AJAX


class Admin extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->model('dbPilar');
        $this->load->model('dbRepo');
        $this->load->model('dbWeb');

        $this->load->library("GenSession");
        $this->load->library("GenMailer");
        $this->load->library("GenSexPdf");
        $this->load->library("GenApi");     // api++

        $this->load->library("apismss");
    }

    //-------------------------------------------------------------------------------------
    // Entrar al Admin
    //-------------------------------------------------------------------------------------
    public function login()
    {
        $user = mlSecurePost("user");
        $pass = mlSecurePost("pass");


        $pass = sqlPassword( $pass );
        if( $row = $this->dbPilar->loginByUser('tblManagers',$user,$pass) ) {
            //
            // datos base de usuario $row->Correo, $row->Nivel, etc.
            //
            $this->gensession->SetAdminLogin (
                PILAR_ADMIN,
                $row->Id,
                $row->Responsable,
                $row->Usuario,
                $row->Nivel
            );
        }

        redirect( base_url("pilar/admin") );
    }

    // Salir de Admin
    public function logout()
    {
        $this->gensession->SessionDestroy( PILAR_ADMIN );
        redirect( base_url("pilar/admin"), 'refresh');
    }


    /*
    public function probarMail()
    {
        $this->genmailer->mailPilar( "vriunap@yahoo.com", "Pepito", "El grillo magico" );
    }
    */

    private function logCorreo( $idUser, $correo, $titulo, $mensaje )
    {
        if( !$correo ) return;

        $this->dbPilar->Insert (
            'logCorreos', array(
            'IdDocente' => $idUser,
            'IdTesista' => $idUser,
            'Fecha'   => mlCurrentDate(),
            'Correo'  => $correo,
            'Titulo'  => $titulo,
            'Mensaje' => $mensaje
        ) );

        // enviamos mail
        $this->genmailer->mailPilar( $correo, $titulo, $mensaje );
    }
    private function logTramites( $idUser, $tram, $accion, $detall )
    {
        $this->dbPilar->Insert(
            'logTramites', array(
                'Tipo'      => 'A',      // T D C A
                'IdUser'    => $idUser,
                'IdTramite' => $tram,
                'Quien'     => 'Pilar',
                'Accion'    => $accion,
                'Detalle'   => $detall,
                'Fecha'     => mlCurrentDate()
        ) );
    }
    //---------------------------------------------------------------------------------------


    public function index()
    {
        if( mlPoorURL() )
            redirect( mlCorrectURL() );

        // en caso de admin crear nueva session admin por App
        //

        if( ! $this->gensession->GetSessionData( PILAR_ADMIN ) ){

            $this->load->view("pilar/admin/header");
            $this->load->view("pilar/admin/login");
            return;
        }

        // logged into admin
        //
        $this->load->view( "pilar/admin/header" );
        $this->load->view( "pilar/admin/panel" );
    }


    //creado 22/02/2022 para listar posibles tesistas
    public function panelListaPosibles()
    {
        $this->gensession->IsLoggedAccess( PILAR_ADMIN );

        $facul = mlSecurePost( "epss" );
        $filtro='';
        if($facul!=''){
            $filtro='IdFacultad='.$facul;
        }
        $tdocen = $this->dbRepo->getSnapView( "tblcandidatostesistas",$filtro);

        $this->load->view( "pilar/admin/repoPosiblesTesista", array (                
                'tfacus' => $this->dbRepo->getTable( "dicFacultades" ),
                'tdocen' => $tdocen,
                'facul'  => $facul
            ) );
    }


    //CREADO EL 04/10/2021
    public function execNewPosibleTesista()
    {
        $this->gensession->IsLoggedAccess( PILAR_ADMIN );

        $facult = mlSecurePost( "facul" );
        $carrer = $this->dbRepo->inCarrera(mlSecurePost( "carre" ));

        $dni    = mlSecurePost( "dni" );
        $codigo = mlSecurePost( "codigo" );
        $apells = mlSecurePost( "apellidos" );
        $nombres = mlSecurePost( "nombres" );
        $matriculanio = mlSecurePost( "matriculasanio" );
        $matriculaperiodo = mlSecurePost( "matriculaperiodo" );  


        if( $this->dbRepo->getSnapRow("tblcandidatostesistas","documento_numero='$dni' OR Codigo='$codigo'") ) {
            echo "Existe uno identico con DNI y Codigo";
            return;
        }

        $this->dbRepo->Insert( "tblcandidatostesistas", array(
                'codigo'     => $codigo,
                'documento_numero'     => $dni,
                'apellidos'  => $apells,
                'nombres'  => $nombres,
                'escuela' => $carrer,
                'matricula_especialidad'  => '1',
                'matricula_semestre'   => 'EGRESADO',
                'matricula_anio'   => $matriculanio,
                'matricula_periodo'     => $matriculaperiodo,
                'foto'    => base_url("vriadds/pilar/imag/foto/estudiante/ImagenGenerica.jpg")
                
            ) );

        $msg = "Se grabo el nuevo posible estudiante";           ;

        echo $msg;
    }


    public function panelCaduc()
    {
        $tbl = $this->dbPilar->getTable( "tesTramites", "Tipo < 0" );

        foreach( $tbl->result() as $row ){
            $autor = $this->dbPilar->inTesistas( $row->Id );
            echo "* (Id:$row->Id) -- $row->Codigo :: $row->Estado -- $autor <br>";
        }
    }

    public function panelBusqa()
    {
        $this->load->view( "pilar/admin/verBusqTes" );
    }

    public function panelOnBor()
    {
        $this->load->view( "pilar/admin/verActBorr" );
    }

    //entra
    public function innerTrams( $tipo=null )
    {
        $this->gensession->IsLoggedAccess( PILAR_ADMIN );

        $estado = mlSecurePost( "estado" );
        $carrer = mlSecurePost( "carrer" );
        //$jurado = mlSecurePost( "jurado" );
        $codigo = mlSecurePost( "codigo" );

        // en casi interno con envio de FormData
        //
        if( $tipo==1 && $estado==null && $carrer==null )  $estado = 0;
        if( $tipo==2 && $estado==null && $carrer==null )  $estado = 0;

         if( strlen($codigo) )
        {
            $filtro = "Tipo='$tipo' AND codigo = '$codigo'";
             $estado = $carrer = 0;
        }else{
        $filtro = " Tipo='$tipo' ";
        //----------------------------------------------------------------
        if( $estado >= 1 )
        {
           $codigo=""; $filtro .= " AND Estado='$estado' ";
        }
        if( $carrer >= 1 )
        {
            $codigo=""; $filtro .= " AND IdCarrera='$carrer' ";
        }
         }
       /* if( strlen($jurado) ) {
            $idDocn = $this->dbRepo->inByDatos( $jurado );
            if( ! $idDocn ) $idDocn=-101;
            $filtro = "Tipo='$tipo' AND (IdJurado1=$idDocn OR
                                         IdJurado2=$idDocn OR
                                         IdJurado3=$idDocn OR
                                         IdJurado4=$idDocn) ";
            $estado = $carrer = 0;
        }*/
       
        //----------------------------------------------------------------
        $filtro .= " ORDER BY Estado DESC, FechModif DESC ";


        //
        // por tipo de tramite y fecha de modif la que se controla en cada cambio
        // mas rapido y detallado que obtenerlo de la ultima iteracion
        //
        $tproys = $this->dbPilar->getTable( 'tesTramites', $filtro );

        $this->load->view( "pilar/admin/verTrams", array (
                'tcarrs' => $this->dbRepo->getTable( "dicCarreras", "1 ORDER BY Nombre" ),
                'tEstadotip' => $this->dbPilar->getTable( "dicestadtram", "Tipo=$tipo" ),
                'tproys' => $tproys,
                'carrer' => $carrer,
                'estado' => $estado,
                'codigo' => $codigo,
                'tipo'   => $tipo,
            ) );
    }

    // entra
    public function panelProys()
    {
        // todos acceden

        $this->innerTrams( 1 );
    }

    public function panelBorrs()
    {
        // todos acceden

        $this->innerTrams( 2 );
    }

    public function panelSuste()
    {
        // todos acceden

        $this->innerTrams( 3 );
    }

    public function panelLinea()
    {
        $idcar = mlSecurePost( "idcar", 0 );
        $idlin = mlSecurePost( "idlin", 0 );

        $filtr = "IdCarrera=$idcar AND Estado=1";
        $lines = $this->dbRepo->getTable("tblLineas", $filtr );

        $this->load->view( "pilar/admin/reports/repLineas", array(
            'idcar' => $idcar,
            'lines' => $lines,
            'carre' => $this->dbRepo->getTable("dicCarreras", "1 ORDER BY Nombre" ),
            'profs' => $this->dbPilar->getTable("vxDocInLin","IdLinea='$idlin' AND Activo>='5' ORDER BY IdCategoria")
        ) );
    }

    public function panelGeren()
    {
        $this->load->view("pilar/admin/reports/repGeren");
    }

    public function tesEdiPass( $idtes=0 )
    {
        $this->load->view( "pilar/admin/edtPass", array('idtes'=>$idtes) );
    }

    public function tesEdiTitu( $idtram=0 )
    {
        $args = [
            'idtram' => $idtram,
            'titulo' => $this->dbPilar->getOneField( "tesTramsDet", "Titulo", "IdTramite=$idtram ORDER BY Id DESC" )
        ];

        $this->load->view( "pilar/admin/edtTitu", $args );
    }

    public function tesHistory( $idtram=0 )
    {
        $args = [
            'histo' => $this->dbPilar->getSnapView( "logTramites", "IdTramite=$idtram" )
        ];

        $this->load->view( "pilar/admin/edtLogHist", $args );
    }

    public function tesCambios( $idtram=0 )
    {
        $tram = $this->dbPilar->getSnapRow( "tesTramites", "Id=$idtram" );

        $tdir = $this->dbPilar->getSnapView(
                          "vxDocInLin",
                          "IdCategoria<='9' AND Activo>=5 AND IdLinea='$tram->IdLinea' AND IdCarrera=$tram->IdCarrera",
                          "ORDER BY TipoDoc, CategAbrev DESC, DatosPers" );

        $tjur = $this->dbPilar->getSnapView(
                          "vxDocInLin",
                          "Activo=6 AND IdLinea='$tram->IdLinea'",
                          "ORDER BY TipoDoc, CategAbrev, DatosPers" );

        $args = [
            'tram' => $tram,
            'tdir' => $tdir,
            'tjur' => $tjur,
            'camb' => $this->dbPilar->getTable( "tesJuCambios", "IdTramite=$idtram" )
        ];

        $this->load->view( "pilar/admin/edtCamJur", $args );
    }

    public function inSavCamJur()
    {
        $this->gensession->IsLoggedAccess( PILAR_ADMIN );
        $sess = $this->gensession->GetData( PILAR_ADMIN );

        $idtram = mlSecurePost("idtram");
        $jurad1 = mlSecurePost("jurado1");
        $jurad2 = mlSecurePost("jurado2");
        $jurad3 = mlSecurePost("jurado3");
        $jurad4 = mlSecurePost("jurado4");
        $motivo = mlSecurePost("motivo");
        $fechax = mlCurrentDate();

        echo "<pre>";
        if( !$jurad1 or !$jurad2 or !$jurad3 or !$jurad4 ){
            echo "No hay cambios en los Jurados Contratados\n\n";
        }


        if( !$motivo ){
            echo "Redacte el motivo de cambio";
            return;
        }

        // procesamos a verificar
        $tram = $this->dbPilar->getSnapRow( "tesTramites", "Id=$idtram" );

        if( $jurad1 == null ) $jurad1 = $tram->IdJurado1;
        if( $jurad2 == null ) $jurad2 = $tram->IdJurado2;
        if( $jurad3 == null ) $jurad3 = $tram->IdJurado3;
        if( $jurad4 == null ) $jurad4 = $tram->IdJurado4;

        // revisar si hay cambios en la distribución
        if( $jurad1 == $tram->IdJurado1 &&
            $jurad2 == $tram->IdJurado2 &&
            $jurad3 == $tram->IdJurado3 &&
            $jurad4 == $tram->IdJurado4 ){

            echo "No hay cambios en el jurado";
            return;
        }

        $jurs = "P: " .$this->dbRepo->inDocenteEx($jurad1)."\n".
                "1: " .$this->dbRepo->inDocenteEx($jurad2)."\n".
                "2: " .$this->dbRepo->inDocenteEx($jurad3)."\n".
                "A: " .$this->dbRepo->inDocenteEx($jurad4)."\n\n".$motivo;


        // Cambio de jurado
        $this->dbPilar->Insert("tesJuCambios", array(
            'Referens'  => "PILAR3",     // Mejorar tipo doc
            'IdTramite' => $tram->Id,    // guia de Tramite
            'Tipo'      => $tram->Tipo,  // en el momento
            'IdJurado1' => $jurad1,
            'IdJurado2' => $jurad2,
            'IdJurado3' => $jurad3,
            'IdJurado4' => $jurad4,
            'Motivo'    => $motivo,
            'Fecha'     => $fechax
        ) );

        $this->dbPilar->Insert( "logTramites", array(
            'Tipo'      => 'A',
            'Quien'     => $sess->userName,
            'IdUser'    => $sess->userId,
            'IdTramite' => $tram->Id,
            'Detalle'   => $jurs,
            'Accion'    => "Cambio de Jurado",
            'Fecha'     => $fechax
        ) );

        $arrCam = array(
                'IdJurado1' => $jurad1,
                'IdJurado2' => $jurad2,
                'IdJurado3' => $jurad3,
                'IdJurado4' => $jurad4
            );
        $this->dbPilar->Update( "tesTramites", $arrCam, $tram->Id );

        echo $jurs;
        echo "</pre>";
    }

    public function inSavePass()
    {
        $this->gensession->IsLoggedAccess( PILAR_ADMIN );

        $ldni = mlSecurePost("ldni");
        $mail = mlSecurePost("mail");
        $pass = mlSecurePost("pass");
        $idte = mlSecurePost("idte");

        if( $idte == 0 ){ echo "No existe el IdTesista"; return; }
        if( !$pass and !$mail and !$ldni ){ echo "Sin cambios."; return; }

        $args = null;
        if( $ldni ) $args = array( 'DNI'    => $ldni );
        if( $mail ) $args = array( 'Correo' => $mail );
        if( $pass ) $args = array( 'Clave'  => sqlPassword($pass) );

        // enviamos los datos a modificar
        $this->dbPilar->Update("tblTesistas", $args, $idte);

        // mensaje de salida
        if( $ldni ) echo "<b>DNI fue cambiado</b>";
        if( $mail ) echo "<b>Correo fue cambiado</b>";
        if( $pass ) echo "<b>Clave fue cambiada</b>";
    }


    public function inSaveTitu()
    {
        $this->gensession->IsLoggedAccess( PILAR_ADMIN );
        $sess = $this->gensession->GetData( PILAR_ADMIN );
        $titulo = mb_strtoupper( mlSecurePost("titulo") );
        $motivo = mlSecurePost("motivo");
        $idtram = mlSecurePost("idtram");

        if( !$idtram ){ echo "Error: no hay Id de Tramite."; return; }
        if( !$titulo ){ echo "Error: no hay contenido(s)."; return; }

        // corregir titulo
        $tram = $this->dbPilar->inLastTramDet( $idtram );
        $this->dbPilar->Update( "tesTramsDet", array("Titulo"=>$titulo) ,$tram->Id );
        $this->logTramites( $sess->userId, $tram->Id, "Cambio de Titulo", $motivo );

        echo "Los datos se guardaron <b>correctamente</b>.";
    }

    public function inSaveHabil()
    {
        $this->gensession->IsLoggedAccess( PILAR_ADMIN );
        $sess = $this->gensession->GetData( PILAR_ADMIN );

        $idtram = mlSecurePost("idtram");
        $codigo = mlSecurePost("codigo");
        $sorteo = mlSecurePost("fechso");
        $motivo = mlSecurePost("motivo");
        $jurad1 = mlSecurePost("jurad1");
        $estad1 = mlSecurePost("estad1");
        $jurad2 = mlSecurePost("jurad2");
        $estad2 = mlSecurePost("estad2");
        $jurad3 = mlSecurePost("jurad3");
        $estad3 = mlSecurePost("estad3");

        //echo "$idtram :: $motivo ::> $jurad1 : $estad1";
        //echo "$jurad1 $jurad2 $jurad3 / $estad1 $estad2 $estad3";

        if( ! $estad1 ){
            $args = array(
                'IdTram'    => $idtram,
                'Codigo'    => $codigo,
                'IdDocente' => $jurad1,
                'PosJurado' => 1,
                'Motivo'    => $motivo,
                'FechSort'  => $sorteo,
                'Fecha'     => mlCurrentDate()
            );
            $this->dbPilar->Insert( "tesProyHabs", $args );
        }

        if( ! $estad2 ){
            $args = array(
                'IdTram'    => $idtram,
                'Codigo'    => $codigo,
                'IdDocente' => $jurad2,
                'PosJurado' => 2,
                'Motivo'    => $motivo,
                'FechSort'  => $sorteo,
                'Fecha'     => mlCurrentDate()
            );
            $this->dbPilar->Insert( "tesProyHabs", $args );
        }

        if( ! $estad3 ){
            $args = array(
                'IdTram'    => $idtram,
                'Codigo'    => $codigo,
                'IdDocente' => $jurad3,
                'PosJurado' => 3,
                'Motivo'    => $motivo,
                'FechSort'  => $sorteo,
                'Fecha'     => mlCurrentDate()
            );
            $this->dbPilar->Insert( "tesProyHabs", $args );
        }


        $this->logTramites( $sess->userId, $idtram, "Habilitación de Subida", $motivo );
        if( ! $estad1 ) $this->logCorreo( $idtram, $this->dbRepo->inCorreo($jurad1) , "Habilitacion por Omisión", $motivo );
        if( ! $estad2 ) $this->logCorreo( $idtram, $this->dbRepo->inCorreo($jurad2) , "Habilitacion por Omisión", $motivo );
        if( ! $estad3 ) $this->logCorreo( $idtram, $this->dbRepo->inCorreo($jurad3) , "Habilitacion por Omisión", $motivo );

        $idDet = $this->dbPilar->getOneField("tesTramsDet","Id","IdTramite='$idtram' AND Iteracion='1'");
        if( ! $estad1 ) $this->dbPilar->Update( "tesTramsDet", array('vb1'=>1), $idDet );
        if( ! $estad2 ) $this->dbPilar->Update( "tesTramsDet", array('vb2'=>1), $idDet );
        if( ! $estad3 ) $this->dbPilar->Update( "tesTramsDet", array('vb3'=>1), $idDet );

        echo $motivo;
    }


    public function panelTrafi()
    {
        $tblRes1 = $this->dbPilar->Analytics( "OS" );
        $tblRes2 = $this->dbPilar->Analytics( "DATE(Fecha)", "ORDER BY DATE(Fecha) DESC" );
        $tblRes3 = $this->dbPilar->Analytics( "LEFT(Browser, position(' ' in Browser))" );

        $nro = 1;
        echo "<table class='table table-striped'>";

        $suma = 0;
        foreach( $tblRes2->result() as $row )
            $suma += $row->Fi;

        foreach( $tblRes2->result() as $row ) {

            $por = ($row->Fi*100) / $suma;
            $bar = "<div class='progress'>
                    <div class='progress-bar progress-bar-primary' aria-valuenow=$por
                        aria-valuemin=0 aria-valuemax=100 style='width:$por%'>
                        $row->Fi
                    </div>
                    </div>";

            echo "<tr>";
            echo "<td> $nro </td> ";
            echo "<td class='col-md-1'> $row->Item </td> ";
            echo "<td class='col-md-10'> $bar </td> ";
            echo "</tr>";
            $nro++;
        }
        echo "</table>";
    }

    public function panelRepos()
    {
        $carre = mlSecurePost( "carre", 0 );
        $espec = mlSecurePost( "espec", 0 );
        $progs = mlSecurePost( "progs", 0 );
        $datos = mlSecurePost( "datos", 0 );

        $proy = NULL;

        if( $progs ) {

            $prog = $this->dbRepo->getSnapRow( "dicEspecialis", "Id=$progs" );
            $espe = ($progs>40)? 0 : $progs;
            $proy = $this->dbPilar->getSnapView( "vxTesTramites", "IdCarrera='$prog->IdCarrera' AND IdEspec='$espe' AND Estado>='6' ORDER BY Estado" );

        } else if( $espec ) {

            $proy = $this->dbPilar->getSnapView( "vxTesTramites", "IdCarrera='$carre' AND IdEspec='$espec' AND Estado>='6' ORDER BY Estado" );

        } else {

            $proy = $this->dbPilar->getSnapView( "vxTesTramites", "IdCarrera='$carre' AND Estado>='6' ORDER BY Estado" );
        }


        $this->load->view( "pilar/admin/repoProys", array(
                'carre' => $carre,
                'espec' => $espec,
                'progs' => $progs,
                'datos' => $datos,
                'tcarr' => $this->dbRepo->getTable( "dicCarreras", "1 ORDER BY Nombre" ),
                'tespe' => $this->dbRepo->getTable( "dicEspecialis", "IdCarrera=$carre" ),
                'tprog' => $this->dbRepo->getTable( "dicEspecialis", "Cod<>'' ORDER BY Cod" ),
                'tproy' => $proy
            ) );
    }

    public function panelRechz()
    {
        $this->gensession->IsLoggedAccess( PILAR_ADMIN );
        $this->innerTrams( 0 );
    }


    //--- codigo repositorio General de Docentes y activacion
    public function panelLista()
    {
        $this->gensession->IsLoggedAccess( PILAR_ADMIN );

        $facul = mlSecurePost( "epss" );
        $filtro='';
        if($facul!=''){
            $filtro='AND IdFacultad='.$facul;
        }
        $tdocen = $this->dbRepo->getSnapView( "vwDocentes", "Edad<=150 ".$filtro." ORDER BY IdCarrera, Edad DESC ");
        $tuniver = $this->dbRepo->getSnapView( "dicuniversidades","", "ORDER BY Nombre ");
         $tlinea = $this->dbRepo->getSnapView( "tbllineas","", "ORDER BY Nombre ");

        $this->load->view( "pilar/admin/repoDocen", array (
                'tcateg' => $this->dbRepo->getTable( "dicCategorias" ),
                'tfacus' => $this->dbRepo->getTable( "dicFacultades" ),
                'tdocen' => $tdocen,
                'tuniversidad' =>  $tuniver,
                'tlinea' => $tlinea ,
                'facul'  => $facul
            ) );
    }
    //modificado unuv1-0 - mantenimiento docente
    public function getLeData( $dni="" )
    {
        $this->gensession->IsLoggedAccess( PILAR_ADMIN );

        if( ! $dni ) return;
       // echo $this->genapi->getDataBasic( $dni ); - Comentado por Bet
        $res =$this->dbRepo->getSnapRow( "tblcandidatosdocentes", "dni=$dni" );
        echo json_encode($res);
    }

    public function panelPilar()
    {
        $this->gensession->IsLoggedAccess( PILAR_ADMIN );
        echo "Pilar pilar pilar osea Pilar**3";
    }

    public function panelLogsD()
    {
        $this->gensession->IsLoggedAccess( PILAR_ADMIN );

        $this->load->view( "pilar/admin/repoLogins", array (
                'tlogTes' => $this->dbPilar->getTable( "vxZumLoginTes", "1 LIMIT 500" ),
                'tlogIns' => $this->dbPilar->getTable( "vxZumLoginDoc", "1 LIMIT 500" ),
                'tlogSum' => $this->dbPilar->getTable( "vxZumLoginDocEx" )
            ) );
    }

     public function panelLogsT()
    {
        $this->gensession->IsLoggedAccess( PILAR_ADMIN );

        $this->load->view( "pilar/admin/repoLoginsTes", array (
                'tlogTes' => $this->dbPilar->getTable( "vxZumLoginTes", "1 LIMIT 500" ),
                'tlogSum' => $this->dbPilar->getTable( "vxZumLogintesEx" )
            ) );
    }


    public function verLinea( $linea=0 )
    {
        if( !$linea ) return;

        //$tlineas = $this->dbPilar->getSnapView( 'vxLineas', "IdCarrera=$carre" );
        //foreach( $tlineas->result() as $row ) {
        $this->load->view("pilar/head");
        echo "<div class='col-md-3'> </div>";
        echo "<div class='col-md-6'> ";
        echo "<img class='img-responsive' src='".base_url("vriadds/pilar/imag/pilar-head.jpg")."'></img>";

        $row = $this->dbRepo->getSnapRow( 'tblLineas', "Id=$linea" );
        if( !$row ){ echo "No linea"; return; }
        echo "<h5 class='text-center'> LINEA DE INVESTIGACIÓN </h5>";
        echo "<h4 class='text-center'> <small>($row->Id)</small> $row->Nombre</h4>";

        // echo " $row->Id :: $row->Nombre ";
        $nro = 1;
        $tdocs = $this->dbPilar->getSnapView( 'vxDocInLin', "IdLinea=$linea", "ORDER BY IdCategoria, DatosPers" );
        echo "<table class='table table-striped ' border=1 cellSpacing=0 cellPadding=5 style='font: 12px Arial'>";
        foreach( $tdocs->result() as $doc ) {

            $nproys = $this->dbPilar->totProys($doc->IdDocente);
            if( $nproys < 5 ) $nproys = "<b> $nproys </b> << ";

            $tacher = $this->dbRepo->inDocenteEx($doc->IdDocente);
            if( $doc->Activo <= 5 )  $tacher = "<i>$tacher</i>";
            else                     $tacher = "<b>$tacher</b>";

            $carrer = "<br><small>".$this->dbRepo->inCarreDoc($doc->IdDocente);

            echo "<tr>";
            echo "<td> $nro </td>";
            echo "<td> (id: $doc->IdDocente) </td>";
            echo "<td> $doc->CategAbrev </td>";
            echo "<td> $doc->TipoDoc </td>";
            echo "<td> $doc->Activo </td>";
            echo "<td> $tacher $carrer </td>";
            echo "<td> $nproys </td>"; 
            echo "<td> $doc->LinEstado</td>";
            echo "<td> " .$this->dbRepo->inCorreo($doc->IdDocente). " </td>";
            $cel=$this->dbRepo->inCelu($doc->IdDocente);
            if($cel){
                echo "<td> " .$this->dbRepo->inCelu($doc->IdDocente). " </td>";

            }
            echo "</tr>";
            $nro++;
        }
        echo "</table>";
    }

    public function tesisLista()
    {
        $nro = 1;
        $table = $this->dbPilar->getSnapView( 'tesTramites', "Tipo=1 AND Estado>=2 AND Estado<=5", "ORDER BY IdCarrera, Estado" );

        echo "<table border=1 cellSpacing=0 cellPadding=5>";
        foreach( $table->result() as $row ){

            $tesista = $this->dbPilar->inTesistas( $row->Id );
            $carrera = $this->dbRepo->inCarrera( $row->IdCarrera );

            echo "<tr>";
            echo "<td> $nro </td>";
            echo "<td> $row->Estado </td>";
            echo "<td> $tesista </td>";
            echo "<td> $carrera </td>";
            echo "</tr>";
            $nro++;
        }
        echo "</table>";
    }



    //Utilizado : intermediario popues
    public function popExec( )
    {
        $this->gensession->IsLoggedAccess( PILAR_ADMIN );
        $sess = $this->gensession->GetData( PILAR_ADMIN );

        $event = mlSecurePost( "evt" );
        $idtram = mlSecurePost( "idtram" );

        if( !$event ){ echo "Error: sin acción definida."; return; }

        $tram = $this->dbPilar->inProyTram($idtram);
        if(!$tram){ echo "No existe el tramite ($idtram)"; return; }

        switch( $event )
        {
            case 10 : $this->inRechaza($tram,$sess);   break; //rechazo por formato
            case 11 : $this->listPyDire($tram,$sess);   break; //pasar al asesor
            case 12 : $this->inRechaDire($tram,$sess); break;  // exceso de tiempo en el asesor
            case 31 : $this->inSorteo($tram,$sess);    break; //sorteo
            case 51 : $this->inPasar6($tram,$sess);    break;   // pasar a 6
            case 50 : $this->inArchiva($tram,$sess);   break;   // 5 archivar
            case 40 : $this->inCancel4($tram,$sess);   break;   // Notificar o Borrar

            default: echo "VRI: Undefined Hook";
        }
    }




    //-----------------------------------------------------------------------
    // Area de funciones de exportacion AJAX
    //-----------------------------------------------------------------------
    public function execAprobPy( $idtram=0 ) // pasar6
    {
        $this->gensession->IsLoggedAccess( PILAR_ADMIN, array(1,2) );
        if( !$idtram ) return;

        $tram = $this->dbPilar->inProyTram( $idtram );
        if( !$tram ) return;


        $dets = $this->dbPilar->inProyDetail( $idtram );
        $dias = mlDiasTranscHoy( $tram->FechModif );

        echo "Codigo de Proyecto: <b>$tram->Codigo</b>  (E: $tram->Estado)";
        echo "<br><b>$dias dias</b> en dictaminación";
        //$fecha  = mlFechaNorm( $row->FechModif );

        foreach( $dets->result() as $row ) {
            echo "<br> <b>Iter($row->Iteracion)</b> : [ $row->vb1 / $row->vb2 / $row->vb3 / $row->vb4 ] -- $row->Fecha";
        }

        // solo el primero
        $row = $dets->row();
        if( $row->Iteracion != 2 ) {
            echo "<br> No corresponde la Iteracion (ó Previa aprobación)";
            return;
        }

        if( $tram->Estado != 5 ){
            echo "<br> El estado no es de dictaminación";
            return;
        }

        if( ($row->vb1+$row->vb2+$row->vb3) < 0 ) {
            echo "<h4>Proyecto Desaprobado</h4>";
            return;
        }

        // detallaremos evento interno Ev31
        echo "<input type=hidden name=evt value='51'>";
        echo "<input type=hidden name=idtram value='$idtram'>";

        echo "<br><br><b>Para aprobar presione: (dale OK)</b>";
    }

    //utilizado
    public function execCorrec( $idtram=0 )
    {
        //$this->gensession->IsLoggedAccess( PILAR_ADMIN, array(1,2) );
       /* if( !$idtram ) return;

        $tram = $this->dbPilar->inProyTram( $idtram );
        if( !$tram ) return;

        $dets = $this->dbPilar->inLastTramDet( $idtram );
        if( !$dets ) return;

        echo "<B>$tram->Codigo</B> :: <small>$dets->Titulo</small>";
        echo "<br> <b>IdTramite:</b> $tram->Id -  <b>e-mail:</b> " . $this->dbPilar->inCorreo($tram->IdTesista1);
        echo "<br> <b>Linea:</b> $tram->IdLinea </b> - <b>Jurados</b> : [ $tram->IdJurado1 / $tram->IdJurado2 / $tram->IdJurado3 / $tram->IdJurado4 ] ";

        // inCorrecs
        $corr1 = $this->dbPilar->inNCorrecs( $idtram, $tram->IdJurado1, 1 );
        $corr2 = $this->dbPilar->inNCorrecs( $idtram, $tram->IdJurado2, 1 );
        $corr3 = $this->dbPilar->inNCorrecs( $idtram, $tram->IdJurado3, 1 );
        $corr4 = $this->dbPilar->inNCorrecs( $idtram, $tram->IdJurado4, 1 );

        
        // $telf1='920101015';

        // echo "<a onclick=\"lodPanel('admin/panelLista')\" href=\"javascript:void(0)\" class=\"btn btn-info\"> Refrezcar </a>";
        // $booton="<a onclick=\"lodHere('','admin/notiCelu/')\" href=\"javascript:void(0)\" class=\"btn btn-info\"> Refrezcar </a>";
        echo "<br> C1: " . $corr1."<a onclick=\"lodNoti('admin/notiCelu/$tram->IdJurado1/2')\" href=\"javascript:void(0)\" class=\"btn btn-xs btn-info\"> Notificar </a>";
        echo "<br> C2: " . $corr2."<a onclick=\"lodNoti('admin/notiCelu/$tram->IdJurado2/2')\" href=\"javascript:void(0)\" class=\"btn btn-xs btn-info\"> Notificar </a>";
        echo "<br> C3: " . $corr3."<a onclick=\"lodNoti('admin/notiCelu/$tram->IdJurado3/2')\" href=\"javascript:void(0)\" class=\"btn btn-xs btn-info\"> Notificar </a>";
        echo "<br> C4: " . $corr4."<a onclick=\"lodNoti('admin/notiCelu/$tram->IdJurado4/2')\" href=\"javascript:void(0)\" class=\"btn btn-xs btn-info\"> Notificar </a>";*/
        if( !$idtram ) return;

  $tram = $this->dbPilar->inProyTram( $idtram );
  if( !$tram ) return;

  $dets = $this->dbPilar->inProyDetail( $idtram );
  $dias = mlDiasTranscHoy( $tram->FechModif );
  $nombreestado =$this->dbPilar->getSnapRow("dicestadtram","Id=$tram->Estado");

  echo "Codigo de Proyecto: <b>$tram->Codigo</b>  (Estado: ".$nombreestado->Nombre.")";// (E: $tram->Estado) - COMENTO
  echo "<br><b>$dias dias</b> en proceso  <br>";
  //echo  $nombreestado->Nombre;

  /* .............Bet.........................
    1. Agregado la tabla 
    2. Creado la funcion MostrarEstado() 
    3. Una condicion cuando es 4ta iteracion : Dictaminacion
  */
  echo "<table class='table table-responsive table-bordered'>
          <tr>
            <th scope='col'></th>
            <th scope='col'>Presidente</th>
            <th scope='col'>Segundo Miembro</th>
            <th scope='col'>Tercer Miembro</th>
            <th scope='col'>Fecha subida Proy.</th>
          </tr>";
          foreach( $dets->result() as $row ) 
          {
            if($row->Iteracion==4)
            {
              $most='Dictaminación';
            }
            else 
            {
              $most='Observación('.$row->Iteracion.')';
            }
            echo "<tr>";
              echo "<td><b>$most</b> </td>";
              echo "<td>".$this->MostrarEstado($row->vb1)."</td>";
              echo "<td>".$this->MostrarEstado($row->vb2)."</td>";
              echo "<td>".$this->MostrarEstado($row->vb3)."</td>";
              echo "<td>$row->Fecha</td>";
            echo "</tr>";   
          }
  echo "</table>";
    // solo el primero
  $row = $dets->row();
  
  echo "<input type=hidden name=evt value='51'>";
  echo "<input type=hidden name=idtram value='$idtram'>";   
    }

    //agregado unuv2.0 - Estado revision 1
public function MostrarEstado($esta){
   if($esta==0){
     return "Sin revisar";
   }
   else if($esta==1)
   {
     return "Observado";
   }
   else if($esta==-1)
   {
     return "Desaprobado";
   }
   else{
     return "Aprobado";        
   }  
   return 0;    
 }

    public function execRech4($idtram)
    {
        //$this->gensession->IsLoggedAccess( PILAR_ADMIN );
        if( !$idtram ) return;

        $tram = $this->dbPilar->inProyTram($idtram);
        if(!$tram){ echo "No registro"; return; }

        echo "<b>Codigo :</b> $tram->Codigo ";
        echo "<br><b>Linea ($tram->IdLinea) :</b> " . $this->dbRepo->inLineaInv($tram->IdLinea);
        echo "<br><b>Tesista(s) :</b> "             . $this->dbPilar->inTesistas($tram->Id);
        echo "<br><b>Correo :</b> "             . $this->dbPilar->inCorreo($tram->IdTesista1);
        echo "<br>";
        //echo "<br><b>Presidente(a)   :</b> " . $this->dbRepo->indocente( $tram->IdJurado1 );
        //echo "<br><b>Primer miembro  :</b> " . $this->dbRepo->indocente( $tram->IdJurado2 );
        //echo "<br><b>Segundo miembro :</b> " . $this->dbRepo->indocente( $tram->IdJurado3 );
        echo "<br><b>Asesor(a)     :</b> " . $this->dbRepo->indocente( $tram->IdJurado4 );

        echo "<p> <br><b>Se notificará la cancelación por exceso de tiempo.</b></p>";
        //echo "<br><br>FALTA COMPLETAR CODIGO: ENVIO DE MAILS Y LOG";

        // detallaremos evento interno Ev40
        echo "<input type=hidden name=evt value='40'>";
        echo "<input type=hidden name=idtram value='$idtram'>";
        echo "<input type=checkbox name=borrt> <b>Archivar Trámite</b>";

        /*
        $ci =& get_instance();
        print_r( $ci->router->fetch_class() );
        */
    }
 
    // Utilizado : Mostrar para rechazar proyecto por mal formato
    public function execRechaza( $idtram=0 )
    {
        $this->gensession->IsLoggedAccess( PILAR_ADMIN );
        if( !$idtram ) return;
        $tram = $this->dbPilar->inProyTram($idtram);
        $msg = "";
        if(!$tram){ echo "No registro"; return; }
        echo "<b>Codigo :</b> $tram->Codigo ";
        echo "<br><b>Linea :</b> " . $this->dbRepo->inLineaInv($tram->IdLinea);
        echo "<br><b>Tesista(s) :</b> "             . $this->dbPilar->inTesistas($tram->Id);
        echo "<hr>";
        echo "<input type=hidden name=evt value='10'>";
        echo "<input type=hidden name=idtram value='$idtram'>";
        echo "<div class='form-group'>";
        echo    "<label for='comment'>Mensaje a enviar:</label>";
        echo " <label for='comment'><small class='help-block'> Indique el o los motivos del rechazo.</small></label>";
        echo    "<textarea class='form-control' rows=8 name='msg' required>$msg</textarea>";
        echo "</div>";
    }

    // Utilizado : Mostrar enviar al asesor
    public function execEnvia( $idtram=0 ){
       $tram=$this->dbPilar->getSnapRow("tesTramites","Id=$idtram");
       $titulo='ENVIAR PROYECTO';
       $advertencia='¿Esta seguro de enviar este proyecto de tesis al Asesor?';
       $estadotram=$tram->Estado;
       $botoncancelar='cancelar()';
       if($tram->Estado>=9){
           $titulo='ENVIAR BORRADOR DE TESIS';
           $advertencia ='¿Esta seguro de enviar el Borrador de Tesis a Revisión?';
       }
       echo "        
       <div id='indecisosisi'>
       <div class ='modal-body' id='popis'>
       <form id='corazon' method='POST'>
       <b>Codigo :</b> $tram->Codigo 
       <br><b>Linea ($tram->IdLinea) :</b> " . $this->dbRepo->inLineaInv($tram->IdLinea)."
       <br><b>Titulo :</b>   ".$this->dbPilar->inTitulo($idtram)."
       <hr>
       <div class='form-group col-md-12'>
       <input type=hidden name=evt value='11'>
       <input type=hidden name='idtram' id='idtram' value='$idtram'>
       <label for='comment'><span class='glyphicon glyphicon-warning-sign'></span> $advertencia</label>
       </div>
       <br><br>
       </div>
       </form>
       </div>
       </div>";

    }
    //utilizado
    public function execCancelPy(  $idtram=0 )
    {
        $this->gensession->IsLoggedAccess( PILAR_ADMIN );
        if( !$idtram ) return;

        $tram = $this->dbPilar->inProyTram($idtram);
        if(!$tram){ echo "No registro"; return; }
        echo "<b>Codigo :</b> $tram->Codigo ";
        echo "<br><b>Linea :</b> " . $this->dbRepo->inLineaInv($tram->IdLinea);
        echo "<br><b>Tesista(s) :</b> "             . $this->dbPilar->inTesistas($tram->Id);
        echo "<hr>";

        $dets = $this->dbPilar->inLastTramDet($idtram);

        $fechPy = $tram->FechRegProy;
        $fechCo = $dets->Fecha ;


        // mensaje editable
        $msg="";
        


        // detallaremos evento interno Ev31
        echo "<input type=hidden name=evt value='50'>";
        echo "<input type=hidden name=idtram value='$idtram'>";

        echo "<div class='form-group'>";
        echo    "<label for='comment'>Mensaje a enviar:</label>";
        echo    "<textarea class='form-control' rows=8 name='msg' id='msg'>$msg</textarea>";
        echo "</div>";
    }

    //utilizado : para sorteo
    public function execSorteo( $idtram=0 )
    {
        $this->gensession->IsLoggedAccess( PILAR_ADMIN );

        if( !$idtram ) return;

        $tram = $this->dbPilar->inProyTram($idtram);
        if(!$tram){ echo "No registro"; return; }
        $tramDet=$this->dbPilar->getSnapRow("tesTramsDet","IdTramite='$tram->Id' ORDER BY Iteracion desc");
        $intentos=$this->dbPilar->getSnapView("tesJuCambios","IdTramite=$tram->Id")->num_rows()+1;

        echo " <div class='modal-content'>
            <div class='modal-header'>
               <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
               <h4 class='modal-title text-primary' id='myModalLabel'>SORTEO DE JURADOS - PILAR </h4>
            </div>
            <div class ='modal-body' id='sortis'> <form name='sorT' id='sorT' method='post'>";
   
   if($intentos>=4){
        echo "El proyecto cuenta con $intentos intentos, para salvaguardar la integridad del sorteo aleatorio no podrá visualizar a los miembros del jurado hasta que sea procesado. Por favor, clic en botón Procesar";
        exit(0);
    }

    $tpres = $this->dbPilar->getSnapView( 'vxDocInLin', "Activo=6 AND LinEstado=2 AND IdLinea='$tram->IdLinea' AND IdCarrera='$tram->IdCarrera' and IdDocente!='".$tram->IdJurado4."'" ); //agregado unuv1.0

    if($tpres->num_rows() < 3 ){ //futuro cambiar 
      echo "<h3>No se puede sortear por motivo que no hay suficientes docentes activos en la linea de investigación</h3>";
      return;
   }

   $tpres = $this->dbPilar->getSnapView( 'vxDocInLin', "TipoDoc='N' AND Activo=6 AND LinEstado=2 AND IdLinea='$tram->IdLinea' AND IdCarrera='$tram->IdCarrera' and IdDocente!='".$tram->IdJurado4."'" );
  
   echo "<b>Codigo :</b> $tram->Codigo ";
   echo "<br><b>Linea  :</b> " . $this->dbRepo->inLineaInv($tram->IdLinea);
   // echo "<br><b>Tesista(s) :</b> "             . $this->dbPilar->inTesistas($tram->Id);
   echo "<br><b>Asesor :</b> "             . $this->dbRepo->inDocenteEx($tram->IdJurado4);
   $archi =base_url( "/repositor/docs/$tramDet->Archivo");
   echo "<br><b>Archivo de Tesis :</b><a href='$archi' class='btn btn-xs btn-info no-print' target=_blank> Ver PDF Click Aquí</a>";
   //echo "<br><b>Jurado :</b> [ $tram->IdJurado1 / $tram->IdJurado2 / $tram->IdJurado3 / $tram->IdJurado4 ]"; //comentado unuv1.0


   if( $tram->IdJurado1+$tram->IdJurado2+$tram->IdJurado3 > 0 ) {
       echo "<br><b>No se puede sortear.";
       return;
   }

   // detallaremos evento interno Ev31
   echo "<input type=hidden name=evt value='31'>";
   echo "<input type=hidden name=idtram value='$idtram'>";
   // *************************************PRESIS
   $presis= array();
   $sumpres=0;

   //  Excepción Arte
   /*if($tram->IdCarrera!= 11){
       // SELECT * FROM desarrollo_pilar3.vxDocInLinXX WHERE TipoDoc='N' AND Activo=6 AND LinEstado=2 AND IdLinea='182' AND IdCarrera='1'
       $tpres = $this->dbPilar->getSnapView( 'vxDocInLin', "TipoDoc='N' AND Activo=6 AND LinEstado=2 AND IdLinea='$tram->IdLinea' AND IdCarrera='$tram->IdCarrera' " );
   }else{

       $tpres = $this->dbPilar->getSnapView( 'vxDocInLin', "Activo=6 AND LinEstado=2 AND IdLinea='$tram->IdLinea' AND IdCarrera='$tram->IdCarrera' " );
   }

   if($tpres->num_rows() < 1 ){
       echo "<h3>Debe validar a los docentes de la Linea</h3>";
       return;
   }

   if($tpres->num_rows() < 1 ){
       echo "<h3>Pocos docentes en Linea </h3>";
       return;
   }//comentado unuv1.0 */

   //echo "N: " . $tpres->num_rows();
   //return;

   foreach($tpres->result() as $rino){

       if($tram->IdJurado4!=$rino->IdDocente )
       {
           $val = (int)$this->dbPilar->totProys( $rino->IdDocente );
           $presis[ $rino->IdDocente ] = $val;
           $sumpres += $val;
       }
   }
   $totalpres= count($presis);
   $mediapres= $sumpres/$totalpres;
   // echo "Presis : $totalpres - $mediapres <br>";
   $pmenors = array();
   $pmayors = array();
   // $mediapres=13;

   foreach( $presis as $k => $v) { // id
       if( $v<$mediapres ) $pmenors[] = $k;
       else            $pmayors[] = $k;
   }
   // al ser muy pocos ponerlos a todos los weyes de eMe.
   if( count($pmenors) <= 2 )
       $pmenors = array_merge($pmenors,$pmayors);

   // retomar el conteo del array general
   $totalpres = count( $pmenors );

   srand( time() ); 
   $j1 = rand( 0, $totalpres-1 );

   $presi=$pmenors[$j1];
   // ************************************************************NORMAL

   $tdocs = $this->dbPilar->getSnapView( 'vxDocInLin', "Activo=6 AND LinEstado=2 AND IdLinea=$tram->IdLinea AND IdDocente<>$presi" );
   if($tdocs->num_rows()<1){
       echo "<h3>Debe validar a los docentes de la Linea</h3>";
       return;
   }
   $lista = array();
   $suma  = 0;
   foreach( $tdocs->result() as $row ){
       // echo "$row->DatosPers <br>"; 
       if($tram->IdJurado4!=$row->IdDocente )
       {
           $val = (int)$this->dbPilar->totProys( $row->IdDocente );
           $lista[ $row->IdDocente ] = $val;
           $suma += $val;
       }
   }
   $total = count( $lista );
   $media = $suma / $total;

  // echo sprintf("<br>Carrera : $tram->IdCarrera <b>Docentes en la linea:</b> (%d)  |  <b>Media:</b> (%.3f)", $total, $media );

   $menors = array();
   $mayors = array();

   foreach( $lista as $k => $v) { // id
       if( $v<$media ) $menors[] = $k;
       else            $mayors[] = $k;
       // $var=$var+1/count($docentes)*(($v-$media)*($v-$media));
   }
   // al ser muy pocos ponerlos a todos los weyes de eMe.
   if( count($menors) <= 3 )
       $menors = array_merge($menors,$mayors);

   // retomar el conteo del array general
   $total = count( $menors );

   // echo " | <b class='text-success'>N - poca carga: </b> ($total)";

   // semilla, nunca se repetiran los indices
   srand( time() );

   do {
       $j2= rand( 0, $total-1 );
       $j3 = rand( 0, $total-1 );

   }while( $j2 == $j3 );


   $idDocs = array($presi, $menors[$j2], $menors[$j3], $tram->IdJurado4);


   $arrRes = array();

   $strsor = "<table class='table table-bordered' cellPadding=0>";
   for( $i=0; $i<4; $i++ ) {

       $idDocente = $idDocs[$i];

       $nombe = $this->dbRepo->inDocenteEx($idDocente);

       $grado = $this->dbPilar->getOneField( "docEstudios", "IdGrado", "IdDocente=$idDocente ORDER BY IdGrado" );
       $categ = $this->dbRepo->getOneField( "vwDocentes", "IdCategoria", "Id=$idDocente" );
       $antig = $this->dbRepo->getOneField( "vwDocentes", "Antiguedad", "Id=$idDocente" );
       $carr=$this->dbRepo->getOneField( "tblDocentes", "IdCarrera", "Id=$idDocente" );

       // grado = 0 poner grado alto hasta registrar
       if( !$grado ) $grado  = 7;

       $ponAn = sprintf( "%.3f", 1 - ($antig/15000) );
       $ponde = (($categ*10) + $grado)*10 + $ponAn;

       $arrRes[$i] = array( $idDocente, $ponde,$carr);


       $strsor .= "<tr>";
       $strsor .= "<td> $nombe </td>";
       //echo "<td> <b>$doc->TipoDoc</b> <br><small>$doc->CategAbrev</small> </td>";
       $strsor .= "<td> $categ </td>";
       $strsor .= "<td> $grado </td>";
       $strsor .= "<td> $ponAn </td>";
       $strsor .= "<td> $ponde </td>";
       $strsor .= "</tr>";
   }
   $strsor .= "</table>";
   // echo"Docinti:";
   //  print_r($arrRes[0][2]);


   //-----------------------------------------------------------------------------------
   for( $i=0; $i<3; $i++ ) for( $j=$i+1; $j<3; $j++ ){
       if( $arrRes[$i][1] > $arrRes[$j][1] )
       {
           $temp = $arrRes[$i];
           $arrRes[$i] = $arrRes[$j];
           $arrRes[$j] = $temp;
       }
   }

   if($arrRes[0][2]!=$tram->IdCarrera){
       $eliza=$arrRes[0];
       $arrRes[0]=$arrRes[1];
       $arrRes[1]=$eliza;
   }

   if($arrRes[0][2]!=$tram->IdCarrera){
       $eliza=$arrRes[0];
       $arrRes[0]=$arrRes[2];
       $arrRes[2]=$eliza;
   }

   //-----------------------------------------------------------------------------------
   $arrRes[3] = array( $tram->IdJurado4, 0 );

   // $idDocP = $arrRes[1-1][0];

   // $ejne=$this->dbRepo->getOneField( "tblDocentes", "IdCategoria", "Id=$idDocP" );
   //   echo "IdCat Jurado : $ejne| Carrera : $ej1<br>";
   //   if($tram->IdCarrera!= $ej1){
   //    goto againplease; 
   // }
   // if ($ejne>10) {
   //    $media = $media +1;
   //    goto againplease;
   // }
   // echo "<br>:::".$idDocP." / $ej1 / $tram->IdCarrera<br>";
   //GUARDAR REGISTRO
   // Insertar Intentos de sorteos en Historial de Jurados
   $this->dbPilar->Insert("tesJuCambios", array(
       'Referens'  => "PILAR3",        // Mejorar tipo doc
       'IdTramite' => $tram->Id,    // guia de Tramite
       'Tipo'      => $tram->Tipo,  // en el momento
       'IdJurado1' => $arrRes[0][0],
       'IdJurado2' => $arrRes[1][0],
       'IdJurado3' => $arrRes[2][0],
       'IdJurado4' => $tram->IdJurado4,
       'Motivo'    => "Intento $intentos",
       'Fecha'     => mlCurrentDate()
   ) );
   //FIN GUARDAR 
   echo "<table class='table table-bordered' cellPadding=0>";
   echo "<tr style='background-color: #FAEFEF;'>";
       echo "<td style='display:none;' > Id</td>";
       echo "<td > Nombre</td>";
       echo "<td> Categoria </td>";
       echo "<td>N° de Proy. </td>";
       echo "</tr>";
   for( $i=1; $i<=3; $i++ ) {

       $idDoc = $arrRes[$i-1][0];
       $posis = "<input type='hidden' name='j$i' value='$idDoc'>";

       $conte = sprintf( "%02d", $this->dbPilar->totProys($idDoc) );
       $nombe = $this->dbRepo->inDocenteEx($idDoc);
       $carre = $this->dbRepo->inCarreDoc($idDoc);
       $carre = "<br><p style='font-size:9px'> $carre </p>";

       $doc = $this->dbRepo->inDocenteRow($idDoc);

       if( $tram->IdJurado4 == $idDoc )
           $nombe = "<b>$nombe (Asesor) </b>";

       echo "<tr>";
       echo "<td style='display:none;'> $idDoc $posis </td>";
       echo "<td > $nombe $carre </td>";
       echo "<td> ($doc->Tipo) <small>$doc->CategAbrev</small> </td>";
       echo "<td> <b>$conte</b> </td>";
       echo "</tr>";
   }
   echo "</table>";
   echo "</form>";

  /* echo "<b>Declaración Jurada :</b>";
   echo "<br><input type='checkbox' class='form-check-input' id='linC' onclick='enableSave()'>";
   echo "<label class='form-check-label'>El proyecto corresponde a la <b class='text-warning'>Linea de Investigación</b>? </label>";

   echo "<br><input type='checkbox' class='form-check-input' id='directC' onclick='enableSave()'>";
    echo "<label class='form-check-label'>El Asesor es idoneo para el proyecto de tesis ? </label>";

    echo "<br><input type='checkbox' class='form-check-input' id='cumpleC' onclick='enableSave()'>";
    echo " <label class='form-check-label'>El proyecto de tesis cumple con lo establecido por la Escuela Profesional ?</label>";

    echo "<br><input type='checkbox' class='form-check-input' id='aceptoC'  onclick='enableSave()'>";
    echo " <label class='form-check-label'>Estoy deacuerdo con el proyecto de tesis para su calificación por los jurados.</label>";

    echo "<button type='button'class='btn btn-success' disabled='' id='modal-btn-si' onclick='popSaveSort(\"$idtram\")'>GUARDAR</button>"; //Comentado unuv1.0 */
     //echo "<BR>Varianza =".$var;
     // echo "<div id='fred'></div>";

    echo "</div>";
    }


    // nuevo reglamento sorteo de 3 miembros
    //
    public function execSorteito( $idtram=0 )
    {
        // $this->gensession->IsLoggedAccess( PILAR_ADMIN );
        if( !$idtram ) return;

        $tram = $this->dbPilar->inProyTram($idtram);
        if(!$tram){ echo "No registro"; return; }

        echo "<b>Codigo :</b> $tram->Codigo ";
        echo "<br><b>Linea ($tram->IdLinea) :</b> " . $this->dbRepo->inLineaInv($tram->IdLinea);
        echo "<br><b>Tesista(s) :</b> "             . $this->dbPilar->inTesistas($tram->Id);
        echo "<br><b>Jurado :</b> [ $tram->IdJurado1 / $tram->IdJurado2 / $tram->IdJurado3 / $tram->IdJurado4 ]";

        if( $tram->IdJurado1+$tram->IdJurado2+$tram->IdJurado3 > 0 ) {
            echo "<br><b>No se puede sortear por Asignacion en uno.";
            return;
        }

        // detallaremos evento interno Ev31
        echo "<input type=hidden name=evt value='31'>";
        echo "<input type=hidden name=idtram value='$idtram'>";

        $tdocs = $this->dbPilar->getSnapView( 'vxDocInLin', "Activo=6 AND IdLinea=$tram->IdLinea" );

        $lista = array();
        $suma  = 0;
        foreach( $tdocs->result() as $row ){

            if( $tram->IdJurado3!=$row->IdDocente && $tram->IdJurado4!=$row->IdDocente )
            {
                $val = (int)$this->dbPilar->totProys( $row->IdDocente );
                $lista[ $row->IdDocente ] = $val;
                $suma += $val;
            }
        }
        $total = count( $lista );
        $media = $suma / $total;


        echo sprintf("<br><b>Docentes en la linea:</b> (%d)  |  <b>Media:</b> (%.3f)", $total, $media );


        $menors = array();
        $mayors = array();

        foreach( $lista as $k => $v) { // id
            if( $v<$media ) $menors[] = $k;
            else            $mayors[] = $k;
            // $var=$var+1/count($docentes)*(($v-$media)*($v-$media));
        }

        // al ser muy pocos ponerlos a todos los weyes de eMe.
        if( count($menors) <= 1 )
            $menors = array_merge($menors,$mayors);

        // retomar el conteo del array general
        $total = count( $menors );

        // semilla, nunca se repetiran los indices
        srand( time() );

        // revisar repitencia 1
        do {
            $j1 = rand( 0, $total-1 );
            $j2 = rand( 0, $total-1 );
            $j3 = rand( 0, $total-1 );

        }while( $j1 == $j2 | $j2=$j3 | $j3=$j1 );

        // revisar repitencia 2
  //       do {
  //           $j3 = rand( 0, $total-1 );
        // }while( $j2 == $j3 );




        echo " | <b>N</b> - poca carga: ($total)";


        // Ubicamos los Ids de los jurados para organizarlos y detallar
        //
        //// $idDocs = array( 0, 0, $tram->IdJurado3, $tram->IdJurado4);

        //$idDocs = array($menors[$j1], $menors[$j2], $tram->IdJurado3, $tram->IdJurado4);
        $idDocs = array($menors[$j1], $menors[$j2], $menors[$j3], $tram->IdJurado4);

        // ojo verificar que no haya repitencia en los jurados...


        $arrRes = array();

        $strsor = "<table class='table table-bordered' cellPadding=0>";
        for( $i=0; $i<3; $i++ ) {

            $idDocente = $idDocs[$i];

            $nombe = $this->dbRepo->inDocenteEx($idDocente);

            $grado = $this->dbPilar->getOneField( "docEstudios", "IdGrado", "IdDocente=$idDocente ORDER BY IdGrado" );
            $categ = $this->dbRepo->getOneField( "vwDocentes", "IdCategoria", "Id=$idDocente" );
            $antig = $this->dbRepo->getOneField( "vwDocentes", "Antiguedad", "Id=$idDocente" );

            // grado = 0 poner grado alto hasta registrar
            if( !$grado ) $grado  = 7;

            $ponAn = sprintf( "%.3f", 1 - ($antig/15000) );
            $ponde = (($categ*10) + $grado)*10 + $ponAn;

            $arrRes[$i] = array( $idDocente, $ponde );


            $strsor .= "<tr>";
            $strsor .= "<td> $nombe </td>";
            //echo "<td> <b>$doc->TipoDoc</b> <br><small>$doc->CategAbrev</small> </td>";
            $strsor .= "<td> $categ </td>";
            $strsor .= "<td> $grado </td>";
            $strsor .= "<td> $antig </td>";
            $strsor .= "<td> $ponAn </td>";
            $strsor .= "<td> $ponde </td>";
            $strsor .= "</tr>";
        }
        $strsor .= "</table>";


        //-----------------------------------------------------------------------------------
        for( $i=0; $i<3; $i++ ) for( $j=$i+1; $j<3; $j++ )
            if( $arrRes[$i][1] > $arrRes[$j][1] )
            {
                $temp = $arrRes[$i];
                $arrRes[$i] = $arrRes[$j];
                $arrRes[$j] = $temp;
            }
        //-----------------------------------------------------------------------------------
        $arrRes[3] = array( $tram->IdJurado4, 0 );

        echo "<table class='table table-bordered' cellPadding=0>";
        for( $i=1; $i<=4; $i++ ) {

            $idDoc = $arrRes[$i-1][0];
            $posis = "<input type='hidden' name='j$i' value='$idDoc'>";

            $conte = sprintf( "%02d", $this->dbPilar->totProys($idDoc) );
            $nombe = $this->dbRepo->inDocenteEx($idDoc);
            $carre = $this->dbRepo->inCarreDoc($idDoc);
            $carre = "<br><p style='font-size:9px'> $carre </p>";

            $doc = $this->dbRepo->inDocenteRow($idDoc);

            if( $tram->IdJurado3 == $idDoc )
                $nombe = "<b>$nombe</b> (E)";

            echo "<tr>";
            echo "<td> $idDoc $posis </td>";
            echo "<td> $nombe $carre </td>";
            echo "<td> <small>$doc->Antiguedad</small> </td>";
            echo "<td> ($doc->Tipo) <small>$doc->CategAbrev</small> </td>";
            echo "<td> <b>$conte</b> </td>";
            echo "</tr>";
        }
        echo "</table>";

        //echo "<BR>Varianza =".$var;
    }


    public function execSorteo_Antiguo( $idtram=0 )
    {
        // $this->gensession->IsLoggedAccess( PILAR_ADMIN );
        if( !$idtram ) return;

        $tram = $this->dbPilar->inProyTram($idtram);
        if(!$tram){ echo "No registro"; return; }

        echo "<b>Codigo :</b> $tram->Codigo ";
        echo "<br><b>Linea ($tram->IdLinea) :</b> " . $this->dbRepo->inLineaInv($tram->IdLinea);
        echo "<br><b>Tesista(s) :</b> "             . $this->dbPilar->inTesistas($tram->Id);
        echo "<br><b>Jurado :</b> [ $tram->IdJurado1 / $tram->IdJurado2 / $tram->IdJurado3 / $tram->IdJurado4 ]";

        if( $tram->IdJurado1+$tram->IdJurado2 > 0 ) {
            echo "<br><b>No se puede sortear.";
            return;
        }

        // detallaremos evento interno Ev31
        echo "<input type=hidden name=evt value='31'>";
        echo "<input type=hidden name=idtram value='$idtram'>";

        $tdocs = $this->dbPilar->getSnapView( 'vxDocInLin', "Activo=6 AND IdLinea=$tram->IdLinea" );

        $lista = array();
        $suma  = 0;
        foreach( $tdocs->result() as $row ){

            if( $tram->IdJurado3!=$row->IdDocente && $tram->IdJurado4!=$row->IdDocente )
            {
                $val = (int)$this->dbPilar->totProys( $row->IdDocente );
                $lista[ $row->IdDocente ] = $val;
                $suma += $val;
            }
        }
        $total = count( $lista );
        $media = $suma / $total;


        echo sprintf("<br><b>Docentes en la linea:</b> (%d)  |  <b>Media:</b> (%.3f)", $total, $media );


        $menors = array();
        $mayors = array();

        foreach( $lista as $k => $v) { // id
            if( $v<$media ) $menors[] = $k;
            else            $mayors[] = $k;
            // $var=$var+1/count($docentes)*(($v-$media)*($v-$media));
        }

        // al ser muy pocos ponerlos a todos los weyes de eMe.
        if( count($menors) <= 1 )
            $menors = array_merge($menors,$mayors);

        // retomar el conteo del array general
        $total = count( $menors );

        // semilla, nunca se repetiran los indices
        srand( time() );
        do {
            $j1 = rand( 0, $total-1 );
            $j2 = rand( 0, $total-1 );
        }while( $j1 == $j2 );

        echo " | <b>N</b> - poca carga: ($total)";


        // Ubicamos los Ids de los jurados para organizarlos y detallar
        //
        //// $idDocs = array( 0, 0, $tram->IdJurado3, $tram->IdJurado4);
        $idDocs = array($menors[$j1], $menors[$j2], $tram->IdJurado3, $tram->IdJurado4);


        $arrRes = array();

        $strsor = "<table class='table table-bordered' cellPadding=0>";
        for( $i=0; $i<3; $i++ ) {

            $idDocente = $idDocs[$i];

            $nombe = $this->dbRepo->inDocenteEx($idDocente);

            $grado = $this->dbPilar->getOneField( "docEstudios", "IdGrado", "IdDocente=$idDocente ORDER BY IdGrado" );
            $categ = $this->dbRepo->getOneField( "vwDocentes", "IdCategoria", "Id=$idDocente" );
            $antig = $this->dbRepo->getOneField( "vwDocentes", "Antiguedad", "Id=$idDocente" );

            // grado = 0 poner grado alto hasta registrar
            if( !$grado ) $grado  = 7;

            $ponAn = sprintf( "%.3f", 1 - ($antig/15000) );
            $ponde = (($categ*10) + $grado)*10 + $ponAn;

            $arrRes[$i] = array( $idDocente, $ponde );


            $strsor .= "<tr>";
            $strsor .= "<td> $nombe </td>";
            //echo "<td> <b>$doc->TipoDoc</b> <br><small>$doc->CategAbrev</small> </td>";
            $strsor .= "<td> $categ </td>";
            $strsor .= "<td> $grado </td>";
            $strsor .= "<td> $antig </td>";
            $strsor .= "<td> $ponAn </td>";
            $strsor .= "<td> $ponde </td>";
            $strsor .= "</tr>";
        }
        $strsor .= "</table>";


        //-----------------------------------------------------------------------------------
        for( $i=0; $i<3; $i++ ) for( $j=$i+1; $j<3; $j++ )
            if( $arrRes[$i][1] > $arrRes[$j][1] )
            {
                $temp = $arrRes[$i];
                $arrRes[$i] = $arrRes[$j];
                $arrRes[$j] = $temp;
            }
        //-----------------------------------------------------------------------------------
        $arrRes[3] = array( $tram->IdJurado4, 0 );

        echo "<table class='table table-bordered' cellPadding=0>";
        for( $i=1; $i<=4; $i++ ) {

            $idDoc = $arrRes[$i-1][0];
            $posis = "<input type='hidden' name='j$i' value='$idDoc'>";

            $conte = sprintf( "%02d", $this->dbPilar->totProys($idDoc) );
            $nombe = $this->dbRepo->inDocenteEx($idDoc);
            $carre = $this->dbRepo->inCarreDoc($idDoc);
            $carre = "<br><p style='font-size:9px'> $carre </p>";

            $doc = $this->dbRepo->inDocenteRow($idDoc);

            if( $tram->IdJurado3 == $idDoc )
                $nombe = "<b>$nombe</b> (E)";

            echo "<tr>";
            echo "<td> $idDoc $posis </td>";
            echo "<td> $nombe $carre </td>";
            echo "<td> <small>$doc->Antiguedad</small> </td>";
            echo "<td> ($doc->Tipo) <small>$doc->CategAbrev</small> </td>";
            echo "<td> <b>$conte</b> </td>";
            echo "</tr>";
        }
        echo "</table>";

        //echo "<BR>Varianza =".$var;
    }
    //Utilizado : Mostrar para Camcelacion por tiempo
    public function execNoDirec( $idtram=0 )
    {
        $this->gensession->IsLoggedAccess( PILAR_ADMIN );
        if( !$idtram ) return;

        echo "<h4>Archivado por exceso de Tiempo por el Asesor</h4>";

        $tram = $this->dbPilar->inProyTram($idtram);
        if(!$tram) { echo "No registro"; return; }

        echo "<b>Codigo :</b> $tram->Codigo ";
        echo "<br><b>Linea :</b> " . $this->dbRepo->inLineaInv($tram->IdLinea);
        echo "<br><b>Tesista(s) :</b> "             . $this->dbPilar->inTesistas($tram->Id);

        ///echo "<br><b>Jurado :</b> [ $tram->IdJurado1 / $tram->IdJurado2 / $tram->IdJurado3 / $tram->IdJurado4 ]";
        echo "<br><b>Asesor(a) :</b> " . $this->dbRepo->indocente( $tram->IdJurado4 );
        echo "<p><br><b>Se notificará al Asesor y Tesistas, indicando que se archiva el proyecto por exceso de tiempo. ";
        // detallaremos evento interno Ev12 no Asesor rechazar
        echo "<input type=hidden name=evt value='12'>";
        echo "<input type=hidden name=idtram value='$idtram'>";
    }

    //agregado unuv1.0 - Recuperacion de contraseñas tesista
    public function panelListaTesista()
    {
        $this->gensession->IsLoggedAccess( PILAR_ADMIN );

        $facul = mlSecurePost( "epss" );
        $filtro='';
        if($facul!=''){
            $filtro='IdFacultad='.$facul;
        }
        $tdocen = $this->dbPilar->getSnapView( "vxdattesistas",$filtro);
        $tuniver = $this->dbRepo->getSnapView( "dicuniversidades","", "ORDER BY Nombre ");

        $this->load->view( "pilar/admin/repoTesista", array (
                'tcateg' => $this->dbRepo->getTable( "dicCategorias" ),
                'tfacus' => $this->dbRepo->getTable( "dicFacultades" ),
                'tdocen' => $tdocen,
                'tuniversidad' =>  $tuniver,
                'facul'  => $facul
            ) );
    }
    //agregado unuv1.0 - Recuperacion de contraseñas tesista
    public function RestaurarContrase( $codigo=0)
    {
       $this->gensession->IsLoggedAccess( PILAR_ADMIN );
        $expr = $codigo;
        $nuevacon = mlSecureRequest('contra')  ;
       
        if( ! $expr ) return;

        if( $row = $this->dbPilar->getSnapRow("tblTesistas","Id='$expr'") ) 
        {
           $this->dbPilar->Update( 'tbltesistas', array(
                'Clave'    => sqlPassword($nuevacon)
            ), $expr);           

              $msg = "Hola $row->Nombres <br><br>"
              ."Solicitó un restablecimiento de contraseña para el ingreso a la Plataforma PILAR <br> "
             . "Contraseña por defecto : <b>$nuevacon</b><br><br>"
             . "Nota : Se recomienda modificar su contraseña. 
                <br>"  ;

           $this->logLogin( $row->Id, "Restablecer Contaseña" );
           $this->logCorreos( 0,$row->Id, $row->Correo, "Restablecer Contaseña", $msg );

            echo "Se notifico al correo del tesista la contraseña por defecto.";
        }
        else{       
        echo "Error no existe tesista, por favor revisar la base de datos. ";  
         }             
    }

    //agregado unuv1.0 - Recuperacion de contraseñas Docente
    public function RestaurarContraseDocente( $codigo=0)
    {
       $this->gensession->IsLoggedAccess( PILAR_ADMIN );
        $expr = $codigo;
        $nuevacon = mlSecureRequest('contra')  ;
       
        if( ! $expr ) return;

        if( $row = $this->dbRepo->getSnapRow("tbldocentes","Id='$expr'") ) 
        {
           $this->dbRepo->Update( 'tbldocentes', array(
                'Clave'    => sqlPassword($nuevacon)
            ), $expr);           

              $msg = "Hola $row->Nombres <br><br>"
              ."Solicitó un restablecimiento de contraseña para el ingreso a la Plataforma PILAR <br> "
             . "Contraseña por defecto : <b>$nuevacon</b><br><br>"
             . "Nota : Se recomienda modificar su contraseña. 
                <br>"  ;

           $this->logLogin( $row->Id, "Restablecer Contaseña Docente" );
           $this->logCorreos( 0,$row->Id, $row->Correo, "Restablecer Contaseña Docente", $msg );

            echo "Se notifico al correo del Docente la contraseña por defecto.";
        }
        else{       
        echo "Error no existe Docente, por favor revisar la base de datos. ";  
         }             
    }
      public function EnviarCuenta( $codigo=0)
    {
       $this->gensession->IsLoggedAccess( PILAR_ADMIN );
        $expr = $codigo;
        //$nuevacon = mlSecureRequest('contra')  ;
       
        if( ! $expr ) return;

        if( $row = $this->dbRepo->getSnapRow("tbldocentes","Id='$expr'") ) 
        {
                   
            $celu1 = $this->dbRepo->inCelu( $row->Id ); 
        //    $a =$this->notiCeluNuevo($celu1,1);

             $msg1 = "<h4>Bienvenido</h4>"
            . "Estimado(a)  <b>$row->Nombres $row->Apellidos</b> <br>"
            . "Ud. ha sido agregado como Docente en la <b>Plataforma PILAR</b>."
            . "<br><br><b>Datos de su Cuenta:</b><br>"
                . "  * usuario: $row->Correo<br>"
                . "  * contraseña: Usu@rioUNU<br>"
           . "<br> Para ingresar a la plataforma mediante el siguiente enlace http://pilar.unu.edu.pe/unu/pilar <br>Nota: Se recomienda cambiar la contraseña una vez ingresada a la Plataforma PILAR."
            ;

           $this->logLogin( $row->Id, "Registro Nuevo Docente" );
           $this->logCorreos( $row->Id,0, $row->Correo, "Credenciales PILAR", $msg1 );

            echo "Se notifico al correo y celular las Credenciales.";
        }
        else{       
        echo "Error no existe Docente, por favor revisar la base de datos. ";  
         }             
    }

    //agregado unuv1.0 - Recuperacion de contraseñas tesista
    public function logLogin( $idUser, $obs )
    {
        $this->load->library('user_agent');

        $agent = 'Unknowed UA';
        if ($this->agent->is_browser())
        {
            $agent = $this->agent->browser().' '.$this->agent->version();
        }
        elseif ($this->agent->is_robot())
        {
            $agent = $this->agent->robot();
        }
        elseif ($this->agent->is_mobile())
        {
            $agent = $this->agent->mobile();
        }

        //-----------------------------------------------------
        // echo $agent ." // ". $this->agent->platform();
        //-----------------------------------------------------
        $this->dbPilar->Insert( "logLogins", array(
                'Tipo'    => 'A',
                'IdUser'  => $idUser,
                'Accion'  => $obs,
                'IP'      => mlClientIP(),
                'OS'      => $this->agent->platform(),
                'Browser' => $agent,
                'Fecha'   => mlCurrentDate()
            ) );
    }

   //agregado unuv1.0 - Recuperacion de contraseñas tesista
    private function logCorreos( $idDocente,$IdTesista, $correo, $titulo, $mensaje )
    {
        if( !$correo ) return;

        $this->dbPilar->Insert (
            'logCorreos', array(
            'IdDocente' => $idDocente,
            'IdTesista' => $IdTesista,
            'Fecha'   => mlCurrentDate(),
            'Correo'  => $correo,
            'Titulo'  => $titulo,
            'Mensaje' => $mensaje
        ) );
        
        $this->genmailer->mailPilar( $correo, $titulo, $mensaje );
    }
    //agregado unuv2.0 -Buscar Tesista 
    public function BuscarTesista( $codigo)
    {

       $this->gensession->IsLoggedAccess( PILAR_ADMIN );
        $expr = $codigo;
       
        if( ! $expr ) return;

        $data['DatosTesista'] = $this->dbPilar->getResultSet( "tbltesistas", "Id=$codigo" );
        echo json_encode($data);
    }

     //agregado unuv1.0 - Listar sus accesos a PILAR
    public function Acceso( $codigo)
    {

       $this->gensession->IsLoggedAccess( PILAR_ADMIN );
        $expr = $codigo;
       
        if( ! $expr ) return;

        $data['logintesistas'] = $this->dbPilar->getResultSet( "vxZumLoginTes", "Tipo='T' and IdUser =$expr order by Id desc LIMIT 5" );
        echo json_encode($data);
    }
    public function AccesoDoc( $codigo)
    {

       $this->gensession->IsLoggedAccess( PILAR_ADMIN );
        $expr = $codigo;
       
        if( ! $expr ) return;

        $data['logindocentes'] = $this->dbPilar->getResultSet( "vxZumLoginDoc", "Tipo='D' and IdUser =$expr order by Id desc LIMIT 5" );
        echo json_encode($data);
    }     

    private function inSorteo( $rowTram, $sess )
    {
       
        $j1 = mlSecurePost( "j1" );
        $j2 = mlSecurePost( "j2" );
        $j3 = mlSecurePost( "j3" );
        $j4 = mlSecurePost( "j4" );

        // revisar que no haya sido ya enviado y modificado
        if( $this->dbPilar->getSnapRow("tesTramites","Id=$rowTram->Id AND Estado=4") ) {
            echo "Este trámite ya se actualizo.";
            return;
        }

        $this->dbPilar->Update( "tesTramites", array(
                'Estado'    => 4, // sorteado enviado a revisar 1
                "IdJurado1" => $j1,
                "IdJurado2" => $j2,
                "IdJurado3" => $j3,
                "FechModif" => mlCurrentDate()
            ), $rowTram->Id );

        $rowTram = $this->dbPilar->inProyTram( $rowTram->Id );

        $nroMemo = $this->inGenMemo( $rowTram, 1 );
        $anio  = date("Y");
        $Mostrarnro =str_pad($nroMemo, 3, "0", STR_PAD_LEFT);//agregado unuv2.0
        echo "Cod de Tramite: <b>$rowTram->Codigo</b><br>";
        echo "Memo Circular: <b>$Mostrarnro -  $anio -VRI-UNU</b><br>";

        $msgenviar="El proyecto de tesis con codigo  <b>$rowTram->Codigo</b> ha sido sorteado.";

        $this->logTramites( $sess->userId, $rowTram->Id, "Proyecto enviado a Revisión", $msgenviar );

        $Rowdicestatramite = $this->dbPilar->getSnapRow( "dicestadtram", "Id=$rowTram->Estado"); 
         $det = $this->dbPilar->inLastTramDet( $rowTram->Id );

        $msg = "<h4>Proyecto enviado a Revisión</h4><br>"
                     . "Por la presente se le comunica que su Proyecto de Tesis: <b>$rowTram->Codigo</b> ha sido sorteado  y esta conformado por los siguientes docentes:<br><br>   "
                     . "Memo Circular: <b>$Mostrarnro-$anio-VRI-UNU</b><br>"
                     . "Codigo : <b> $rowTram->Codigo </b><br>"
                      . "Miembros del Jurado : &nbsp; <b>" . $this->dbRepo->inDocenteNom($rowTram->IdJurado1) . " ( PRESIDENTE )</b><br>"
                       . " &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &nbsp;  <b>" . $this->dbRepo->inDocenteNom($rowTram->IdJurado2) . " ( PRIMER MIEMBRO )</b><br>"
                       . " &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &nbsp; <b>" . $this->dbRepo->inDocenteNom($rowTram->IdJurado3) . " ( SEGUNDO MIEMBRO )</b><br>"

                     . "Título : <b> $det->Titulo </b><br><br>"
                     . "Su proyecto será revisado en un plazo de ".$Rowdicestatramite->Plazo." dias calendarios mediante la <b>Plataforma PILAR</b> ."
                     ;

        if($rowTram->IdTesista2 !=0)
          {
            $mail = $this->dbPilar->inCorreo( $rowTram->IdTesista1);
            $mail2 = $this->dbPilar->inCorreo( $rowTram->IdTesista2);
            $cel= $this->dbPilar->inCelTesista( $rowTram->IdTesista1);
            $cel2= $this->dbPilar->inCelTesista( $rowTram->IdTesista2);
            $this->logCorreos(0, $rowTram->IdTesista1, $mail, "Proyecto enviado a Revisión", $msg );
            $this->logCorreos(0, $rowTram->IdTesista2, $mail2, "Proyecto enviado a Revisión", $msg );
           // $a=$this->notiCelu($cel,4);
           // $a=$a." - ".$this->notiCelu($cel2,4);
          }
        else
          {
            $mail = $this->dbPilar->inCorreo( $rowTram->IdTesista1);
            $cel= $this->dbPilar->inCelTesista( $rowTram->IdTesista1);
            $this->logCorreos( 0,$rowTram->IdTesista1,$mail, "Proyecto enviado a Revisión", $msg );
            //$a=$this->notiCelu($cel,4);
          }

       
          $msg = "<h4>Revisión Electrónica</h4><br>"
                     . "Por la presente se le comunica que se le ha enviado a su cuenta de Docente en la "
                     . "<b>Plataforma PILAR</b> el proyecto de tesis con el siguiente detalle:<br><br>   "
                     . "Memo Circular: <b>$Mostrarnro-$anio-VRI-UNU</b><br>"
                     . "Codigo : <b> $rowTram->Codigo </b><br>"
                      . "Miembros del Jurado : &nbsp; <b>" . $this->dbRepo->inDocenteNom($rowTram->IdJurado1) . " ( PRESIDENTE )</b><br>"
                       . " &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &nbsp;  <b>" . $this->dbRepo->inDocenteNom($rowTram->IdJurado2) . " ( PRIMER MIEMBRO )</b><br>"
                       . " &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &nbsp; <b>" . $this->dbRepo->inDocenteNom($rowTram->IdJurado3) . " ( SEGUNDO MIEMBRO )</b><br>"

                     . "Título : <b> $det->Titulo </b><br><br>"
                     . "Ud. tiene un plazo  de ".$Rowdicestatramite->Plazo." dias calendarios para realizar las revisiones mediante la Plataforma."
                     ;
           $msgasesor = "<h4>Notificacion asesoria</h4><br>"
                     . "Por la presente se le comunica que el proyecto con codigo $rowTram->Codigo de la cual Ud. es asesor, ha sido sorteado y esta conformado por los siguientes docentes:<br><br>   "
                     . "Memo Circular: <b>$Mostrarnro-$anio-VRI-UNU</b><br>"
                     . "Codigo : <b> $rowTram->Codigo </b><br>"
                      . "Miembros del Jurado : &nbsp; <b>" . $this->dbRepo->inDocenteNom($rowTram->IdJurado1) . " ( PRESIDENTE )</b><br>"
                       . " &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &nbsp;  <b>" . $this->dbRepo->inDocenteNom($rowTram->IdJurado2) . " ( PRIMER MIEMBRO )</b><br>"
                       . " &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &nbsp; <b>" . $this->dbRepo->inDocenteNom($rowTram->IdJurado3) . " ( SEGUNDO MIEMBRO )</b><br>"

                     . "Título : <b> $det->Titulo </b><br><br>"
                     . "Los miebros del jurado tiene un plazo  de ".$Rowdicestatramite->Plazo." dias calendarios para realizar las revisiones mediante la Plataforma PILAR."
                     ;

          $corr1 = $this->dbRepo->inCorreo( $rowTram->IdJurado1 );
          $corr2 = $this->dbRepo->inCorreo( $rowTram->IdJurado2 );
          $corr3 = $this->dbRepo->inCorreo( $rowTram->IdJurado3 );
          $corr4 = $this->dbRepo->inCorreo( $rowTram->IdJurado4 );
             // logCorreo( $idTes,$idDoc, $correo, $titulo, $mensaje )
          $celu1 = $this->dbRepo->inCelu( $rowTram->IdJurado1 );
          $celu2 = $this->dbRepo->inCelu( $rowTram->IdJurado2 );
          $celu3 = $this->dbRepo->inCelu( $rowTram->IdJurado3 );
          $celu4 = $this->dbRepo->inCelu( $rowTram->IdJurado4 ); //comentado unuv1.0
        //  $a =$this->notiCelu($celu1,5);
        //  $b =$this->notiCelu($celu2,5);
        //  $c =$this->notiCelu($celu3,5);
        //  $d =$this->notiCelu($celu4,6); //comentado unuv1.0

          $this->logCorreos( $rowTram->IdJurado1,0, $corr1, "Revisión de Proyecto de Tesis", $msg);
          $this->logCorreos( $rowTram->IdJurado2,0, $corr2,"Revisión de Proyecto de Tesis", $msg);
          $this->logCorreos( $rowTram->IdJurado3,0, $corr3,"Revisión de Proyecto de Tesis", $msg );
          $this->logCorreos( $rowTram->IdJurado4,0, $corr4, "Proyecto Asesorado enviado a Revision", $msgasesor);

          echo $msgenviar;

        // Finalmente
        //-----------
        //
        // Insertar nuevos sorteos en Historial de Jurados
        $this->dbPilar->Insert("tesJuCambios", array(
            'Referens'  => "PILAR3",        // Mejorar tipo doc
            'IdTramite' => $rowTram->Id,    // guia de Tramite
            'Tipo'      => $rowTram->Tipo,  // en el momento
            'IdJurado1' => $rowTram->IdJurado1,
            'IdJurado2' => $rowTram->IdJurado2,
            'IdJurado3' => $rowTram->IdJurado3,
            'IdJurado4' => $rowTram->IdJurado4,
            'Motivo'    => "Sorteo",
            'Fecha'     => mlCurrentDate()
        ) );
    }


    private function inGenMemo( $rowTram, $iterMemo )
    {
        $anio  = date("Y");
        $orden = 1 + $this->dbPilar->getOneField( "tblMemos", "Ordinal", "Anio=$anio ORDER BY Ordinal DESC" );

        $this->dbPilar->Insert( "tblMemos", array(
                'Tipo'      => $iterMemo,   //1 - 4 - 5,
                'IdTramite' => $rowTram->Id,
                'IdCarrera' => $rowTram->IdCarrera,
                'Anio'      => $anio,
                'Ordinal'   => $orden,
                'Fecha'     => mlCurrentDate(),
            ) );

        //echo "Cod de Tramite: <b>$rowTram->Codigo</b> <br>";
        //echo "Memo Generado: <b>$orden-$anio</b> <br>";

        return sprintf("%03d-%d", $orden, $anio);
    }


    private function inPasar6( $tram, $sess )
    {
        $dets = $this->dbPilar->inProyDetail( $tram->Id );

        // solo el primero (descendente)
        $row = $dets->row();

        if($row->vb1==0 || $row->vb2==0  || $row->vb3==0){
          echo "No se puede Procesar, uno de los jurados todavia no revisa el proyecto de tesis.....";
          return;
        }
        if($row->vb1==1 || $row->vb2==1  || $row->vb3==1){
          echo "No se puede Procesar, uno de los jurados ha realizado observaciones.....";
          return;
        }
        if($row->vb1==2 && $row->vb2==2  && $row->vb3==2){

          $this->dbPilar->Update( 'tesTramites', array(
                'Estado'    => 8,
                'FechModif' => mlCurrentDate(),
                'FechActProy' =>mlCurrentDate()
            ) , $tram->Id );
          $titulo='Aprobación de Proyecto';

          $msg = "<h4> Felicitaciones </h4><br>"
             . "Su proyecto <b>$tram->Codigo</b>, ha sido aprobado ya puede visualizarlo"
             . " y descargarlo desde su cuenta de la <b>Plataforma PILAR</b>.";
          $msg1 = "<h4> Proyecto Aprobado</h4><br>"
             . "El proyecto <b>$tram->Codigo</b>, ha sido aprobado. ";
          $men=7;
        }
        else{
          $fechPy = mlFechaNorm( $tram->FechRegProy );
          $titulo='Desaprobación de Proyecto';
          $this->dbPilar->Update( "tesTramites", array('Tipo'=>0,'Estado'=>0), $tram->Id );
           $msg = "El Proyecto de Tesis con código <b>$tram->Codigo</b> <br>\n"
             . "presentado el <b>$fechPy</b> con </b><br>titulado: <b>".$row->Titulo."</b>.<br><br>\n\n"
             . "Ha sido desaprobado, por lo que se procedera Anular el presente trámite.<br><br>"
             . "Por la presente le comunicamos que queda habilitada la cuenta para realizar un nuevo trámite.\n"
             ; 
           $msg1= $msg; 
           $men=8;
        }
        /*if($row->vb1==-1 || $row->vb2==-1  || $row->vb3==-1){
         echo "Por favor comunicarse con soporte PILAR ";
         return;
         }*/


        $this->logTramites( $sess->userId, $tram->Id, $titulo, $msg );
         //------------------Correo a los tesistas -------------
       
          if($tram->IdTesista2 !=0)
            {
              $mail = $this->dbPilar->inCorreo( $tram->IdTesista1);
              $mail2 = $this->dbPilar->inCorreo( $tram->IdTesista2);
              $this->logCorreos(0, $tram->IdTesista1, $mail, $titulo, $msg );
              $this->logCorreos(0,$tram->IdTesista2, $mail2, $titulo, $msg );
               $cel= $this->dbPilar->inCelTesista( $tram->IdTesista1);
               $cel2= $this->dbPilar->inCelTesista( $tram->IdTesista2);
            //   $a=$this->notiCelu($cel, $men);
            //   $a=$a." - ".$this->notiCelu($cel2, $men);

            }
          else
            {
              $mail = $this->dbPilar->inCorreo( $tram->IdTesista1);
              $cel= $this->dbPilar->inCelTesista( $tram->IdTesista1);
              $this->logCorreos(0,$tram->IdTesista1, $mail,$titulo, $msg );
            // $a=$this->notiCelu($cel, $men);
            }
       //---------------------FIN----------------------------      
        echo $msg1;
    }


    public function inArchiva( $tram, $sess )
    {
        $dets = $this->dbPilar->inLastTramDet($tram->Id);

        $fechPy = mlFechaNorm( $tram->FechRegProy);
        $fechCo = mlFechaNorm( $dets->Fecha) ;

        //$msg = mlSecurePost("msg");
        $msg = $_POST["msg"];
        $asesor="El Proyecto de Tesis con código <b>$tram->Codigo</b> <br><br>\n"
             . "Presentado el <b>$fechPy</b> <br>titulado: <b>$dets->Titulo,de la cual usted es asesor.</b>.<br><br>\n\n"
             . "Ha sido Anulado segun $msg , por lo que se procedera a archivar el presente trámite.<br>";
        $jurado = "El Proyecto de Tesis con código <b>$tram->Codigo</b> <br><br>\n"
             . "Presentado el <b>$fechPy</b> <br>titulado: <b>$dets->Titulo,de la cual usted es miembro de jurado.</b>.<br><br>\n\n"
             . "Ha sido Anulado segun $msg , por lo que se procedera a archivar el presente trámite.<br>";
        $msg1 = "Su Proyecto de Tesis con código <b>$tram->Codigo</b> <br><br>\n"
             . "Presentado el <b>$fechPy</b> <br>titulado: <b>$dets->Titulo.</b>.<br><br>\n\n"
             . "Ha sido Anulado segun $msg , por lo que se procedera a archivar el presente trámite.<br>";
        $mesj2= $msg1 ."<br>Por la presente le comunicamos que queda habilitada su cuenta en la Plataforma para realizar un nuevo trámite.\n"
             ;


        // archivarlo en historial
        

        // enviamos al tesista y a los jurados  
        

        // actualizamos el estado del tramite
        $this->dbPilar->Update( "tesTramites", array('Tipo'=>0), $tram->Id );
        $this->logTramites( $sess->userId, $tram->Id, "Desaprobación de Proyecto",$msg1 );

        if($tram->IdTesista2 !=0)
      {        
         $cel= $this->dbPilar->inCelTesista( $tram->IdTesista1);
         $cel2= $this->dbPilar->inCelTesista( $tram->IdTesista2);

         $this->logCorreos( 0,$tram->IdTesista1, $this->dbPilar->inCorreo($tram->IdTesista1), "Desaprobación de Proyecto", $mesj2 );
         $this->logCorreos( 0,$tram->IdTesista2, $this->dbPilar->inCorreo($tram->IdTesista2), "Desaprobación de Proyecto", $mesj2 );
        // $this->notiCeluNuevo($cel,2);
        // $this->notiCeluNuevo($cel2,2);
      }
      else{
            $cel= $this->dbPilar->inCelTesista( $tram->IdTesista1);
            $this->logCorreos( 0,$tram->IdTesista1, $this->dbPilar->inCorreo($tram->IdTesista1), "Desaprobación de Proyecto", $mesj2 );
        //    $this->notiCeluNuevo($cel,2);
      }

       $this->logCorreos( $tram->IdJurado1,0, $this->dbRepo->inCorreo($tram->IdJurado1) , "Desaprobación de Proyecto", $jurado );
        $this->logCorreos( $tram->IdJurado2,0, $this->dbRepo->inCorreo($tram->IdJurado2) , "Desaprobación de Proyecto", $jurado );
        $this->logCorreos( $tram->IdJurado3,0, $this->dbRepo->inCorreo($tram->IdJurado3) , "Desaprobación de Proyecto", $jurado);
        $this->logCorreos( $tram->IdJurado4,0, $this->dbRepo->inCorreo($tram->IdJurado4) , "Desaprobación de Proyecto", $asesor );
        echo "El trámite <b>$tram->Codigo</b> ha sido Archivado segun : ".$msg;
    }

    public function vwInfo($idPy){
        $this->load->view("pilar/admin/infopb",array('IdProyect'=>$idPy));
    }

    //Utilizado : Proyecto rechazado por exceso de tiempo en revisiones 
    private function inCancel4( $tram, $sess )
    {
        $borr = mlSecurePost( "borrt" );

        $dias = mlDiasTranscHoy( $tram->FechModif );
        $dets = $this->dbPilar->inLastTramDet( $tram->Id );
        //$mssg = "El trámite <b>$tram->Codigo</b> con el proyecto: <b>$dets->Titulo</b>. Ya cuenta con $dias dias y el tesista no ha procedido con subir las correcciones. Por lo que se informa que:";
         $fin = "<br><br>Se ha procedido con la eliminación del trámite por exceso de tiempo.";
        // aplicar el borrar trámite      
        if( $borr ){
            $fin = "Se ha Archivado el proyecto con codigo <b>$tram->Codigo</b> por Exceso de Tiempo en Revision.";

            $this->dbPilar->Update( "tesTramites", array(
                'Tipo'    => 0,
            ), $tram->Id );
          $Titulo ='Archivado por Exceso de Tiempo en Revision';
           $this->logTramites( $sess->userId, $tram->Id, "Archivado por Exceso de tiempo en Revision", $fin );          
            //verificar si el tesista falta subir su proyecto o el 
              $msgTesista = "<h4>  Archivado por Exceso de Tiempo en Revision  </h4><br>"
        . "Su proyecto con codigo <b>".$tram->Codigo."</b> ha execedido el tiempo maximo en revision,por lo que se procede con el registro del hecho y la archivacion del trámite.<br><br>"
        ."<em>Nota : Para la activacion del proyecto deberá presentar documentos sustentadorios ante su Comision de Grados y Titulos de su Facultad, caso contrario podra iniciar un nuevo tramite.</em>.</b></p>";

         $msgAsesor = "<h4>Archivado por Exceso de Tiempo en Revision</h4><br>"
        . "El proyecto con codigo <b>".$tram->Codigo."</b> la cual usted es Asesor ha execedido el tiempo maximo de revision, por lo que se procede con el registro del hecho y la archivacion del trámite.<br><br>"
        ."<em>Nota : Para la activacion del proyecto se debera presentar documentos sustentadorios ante su Comision de Grados y Titulos de su Facultad, caso contrario podra iniciar un nuevo tramite.</em>.</b></p>";

            $msgJurado = "<h4> Archivado por Exceso de Tiempo en Revision</h4><br>"
        . "El proyecto con codigo <b>".$tram->Codigo."</b> la cual usted es Jurado ha execedido el tiempo maximo de revision, por lo que se procede con el registro del hecho y la archivacion del trámite.<br><br>"
         ."<em>Nota : Para la activacion del proyecto se debera presentar documentos sustentadorios ante su Comision de Grados y Titulos de su Facultad.</em>.</b></p>";

                 $corr1 = $this->dbRepo->inCorreo( $tram->IdJurado1 );
                $celu1 = $this->dbRepo->inCelu( $tram->IdJurado1 ); 
                $corr2 = $this->dbRepo->inCorreo($tram->IdJurado2);
                $celu2 = $this->dbRepo->inCelu( $tram->IdJurado2 );  
                 $corr3 = $this->dbRepo->inCorreo( $tram->IdJurado3);
                $celu3 = $this->dbRepo->inCelu( $tram->IdJurado3 );
                $corr4 = $this->dbRepo->inCorreo( $tram->IdJurado4);
                $celu4 = $this->dbRepo->inCelu( $tram->IdJurado4 );

                 $this->logCorreos( $tram->IdJurado1,0, $corr1,  $Titulo, $msgJurado);
                $this->logCorreos( $tram->IdJurado2,0, $corr2,  $Titulo, $msgJurado);
                $this->logCorreos( $tram->IdJurado3,0, $corr3,  $Titulo,$msgJurado);
                                // $c =$this->notiCelu($celu3,5);                
                $this->logCorreos( $tram->IdJurado4,0, $corr4,  $Titulo,$msgAsesor);

            if($tram->IdTesista2 !=0)
                {
                    $mail = $this->dbPilar->inCorreo( $tram->IdTesista1);
                    $mail2 = $this->dbPilar->inCorreo( $tram->IdTesista2);
                    $cel= $this->dbPilar->inCelTesista( $tram->IdTesista1);
                    $cel2= $this->dbPilar->inCelTesista( $tram->IdTesista2);
                    $this->logCorreos( 0,$tram->IdTesista1,$mail,  $Titulo, $msgTesista );
                    $this->logCorreos( 0,$tram->IdTesista2, $mail2,  $Titulo, $msgTesista );
                //    $this->notiCelu($cel,2);
                //    $this->notiCelu($cel2,2);
                }
                else
                {
                    $mail = $this->dbPilar->inCorreo( $tram->IdTesista1);
                    $cel= $this->dbPilar->inCelTesista( $tram->IdTesista1);
                    $this->logCorreos( 0,$tram->IdTesista1,$mail,  $Titulo, $msgTesista);
                    $mail = $this->dbPilar->inCorreo( $tram->IdTesista1);
                //    $this->notiCelu($cel,2);
                }             
            echo $fin;
            return;
            } 
        else //Notificacion 
        {   
            $Titulo ='Notificacion de Revision de Proyecto';          
            //verificar si el tesista falta subir su proyecto o el 
              $msgTesista = "<h4> Notificacion por Exceso de Tiempo </h4><br>"
        . "Su proyecto con codigo <b>".$tram->Codigo."</b> ha execedido el tiempo maximo,Usted tiene 48 horas para subir su proyecto corregido, caso contrario se archivara.<br><br>";

         $msgAsesor = "<h4> Notificacion por Exceso de Tiempo </h4><br>"
        . "El proyecto con codigo <b>".$tram->Codigo."</b> la cual usted es Asesor ha execido el tiempo maximo, tiene 48 horas para coordinar con su Asesorado para que suba su proyecto corregido, caso contrario se archivara.<br><br>";

            $msgJurado = "<h4> Notificacion por Exceso de Tiempo </h4><br>"
        . "El proyecto con codigo <b>".$tram->Codigo."</b> la cual usted es Jurado ha execido el tiempo maximo, tiene 48 horas para realizar observaciones y/o aprobación , caso contrario se archivara.<br><br>";

             $fin = "Se Notifico sobre el exceso de tiempo del proyecto con codigo <b>$tram->Codigo</b> a los interesados. ";

             $this->logTramites( $sess->userId, $tram->Id, "Notificacion de Revision de Proyecto", $fin );
        }   

            if($dets->vb1==0)
            {
                $corr1 = $this->dbRepo->inCorreo( $tram->IdJurado1 );
                $celu1 = $this->dbRepo->inCelu( $tram->IdJurado1 );  
            //    $a =$this->notiCelu($celu1,5);             
                $this->logCorreos( $tram->IdJurado1,0, $corr1,  $Titulo, $msgJurado);
            }

            if($dets->vb2==0)
            {
                $corr2 = $this->dbRepo->inCorreo($tram->IdJurado2);
                $celu2 = $this->dbRepo->inCelu( $tram->IdJurado2 );  
            //     $b =$this->notiCelu($celu2,5);              
                $this->logCorreos( $tram->IdJurado2,0, $corr2,  $Titulo, $msgJurado);
            }

            if($dets->vb3==0)
            {
                $corr3 = $this->dbRepo->inCorreo( $tram->IdJurado3);
                $celu3 = $this->dbRepo->inCelu( $tram->IdJurado3 );
            //     $c =$this->notiCelu($celu3,5);                
                $this->logCorreos( $tram->IdJurado3,0, $corr3,  $Titulo,$msgJurado);
            }
             if($dets->vb1!=0 && $dets->vb2!=0 && $dets->vb3!=0)
            {            
                if($tram->IdTesista2 !=0)
                {
                    $mail = $this->dbPilar->inCorreo( $tram->IdTesista1);
                    $mail2 = $this->dbPilar->inCorreo( $tram->IdTesista2);
                    $cel= $this->dbPilar->inCelTesista( $tram->IdTesista1);
                    $cel2= $this->dbPilar->inCelTesista( $tram->IdTesista2);
                    $this->logCorreos( 0,$tram->IdTesista1,$mail,  $Titulo, $msgTesista );
                    $this->logCorreos( 0,$tram->IdTesista2, $mail2,  $Titulo, $msgTesista );
                //    $this->notiCelu($cel,2);
                //    $this->notiCelu($cel2,2);
                }
                else
                {
                    $mail = $this->dbPilar->inCorreo( $tram->IdTesista1);
                    $cel= $this->dbPilar->inCelTesista( $tram->IdTesista1);
                    $this->logCorreos( 0,$tram->IdTesista1,$mail,  $Titulo, $msgTesista);
                    $mail = $this->dbPilar->inCorreo( $tram->IdTesista1);
                //    $this->notiCelu($cel,2);
                }
                //Notificacion Asesor
                 $corr4 = $this->dbRepo->inCorreo( $tram->IdJurado4);
                $celu4 = $this->dbRepo->inCelu( $tram->IdJurado4 );
                // $c =$this->notiCelu($celu3,5);                
                $this->logCorreos( $tram->IdJurado4,0, $corr4,  $Titulo,$msgAsesor);
            }
        
        echo $fin;
        }      



    //
    // envia que revisen borrador los jurados completos
    //
    public function listBrDire( $idtram=0 )
    {
        $this->gensession->IsLoggedAccess( PILAR_ADMIN );
        if( !$idtram ) return;

        $tram = $this->dbPilar->inProyTram($idtram);
        if(!$tram){ echo "No registro"; return; }

        // solo los que estan en espera pasan
        if( $tram->Estado != 11 ) {
            echo "Error: su estado no era en espera, no enviado.";
            return;
        }

        //
        // pasamos estado a revision de borrador
        //
        $this->dbPilar->Update( "tesTramites", array(
                'Estado'    => 12,
                'FechModif' => mlCurrentDate()
            ), $tram->Id );


        // generamos el memo borrados revis
        $nroMemo = $this->inGenMemo( $tram, 4 );

        echo "Cod de Tramite: <b>$tram->Codigo</b><br>";
        echo "Memo Circular: <b>$nroMemo</b><br>";


        $msg = "<h4>Borrador enviado a Revisión</h4><br>"
             . "Su Borrador de Tesis: <b>$tram->Codigo</b> ha sido enviado a los cuatro miembros de su Jurado. "
             . "El mismo que será revisado en un plazo de 10 dias habiles mediante la <b>Plataforma PILAR</b>."
             ;

        $mail = $this->dbPilar->inCorreo( $tram->IdTesista1 );
        $this->logCorreo( $tram->Id, $mail, "Borrador enviado a Revisión", $msg );

        // envio a jurados
        //
        $det = $this->dbPilar->inLastTramDet( $tram->Id );
        $msg = "<h4>Revisión Electrónica</h4><br>"
             . "Por la presente se le comunica que se le ha enviado a su cuenta de Docente en la "
             . "<b>Plataforma PILAR</b> el borrador de tesis con el siguiente detalle:<br><br>   "
             . "Memo Circular: <b>$nroMemo-VRINV-UNU</b><br>"
             . "Tesista(s) : <b>" . $this->dbPilar->inTesistas($tram->Id) . "</b><br>"
             . "Título : <b> $det->Titulo </b><br><br>"
             . "Ud. tiene un plazo de 10 dias hábiles para realizar las revisiones mediante la Plataforma."
             ;

        $corr1 = $this->dbRepo->inCorreo( $tram->IdJurado1 );
        $corr2 = $this->dbRepo->inCorreo( $tram->IdJurado2 );
        $corr3 = $this->dbRepo->inCorreo( $tram->IdJurado3 );
        $corr4 = $this->dbRepo->inCorreo( $tram->IdJurado4 );

        $this->logCorreo( $tram->Id, $corr1, "Revisión de Borrador de Tesis", $msg );
        $this->logCorreo( $tram->Id, $corr2, "Revisión de Borrador de Tesis", $msg );
        $this->logCorreo( $tram->Id, $corr3, "Revisión de Borrador de Tesis", $msg );
        $this->logCorreo( $tram->Id, $corr4, "Revisión de Borrador de Tesis", $msg );

        //echo $tram->Codigo . " fue Enviado a su Asesor";
        echo "Correos enviados correctamente<br>";
        echo "El Borrador está en Revisión desde Hoy.<br>";
    }

    //Utilizado : Proyecto enviado al asesor
    public function listPyDire( $rowTram, $sess )
    {
        $this->gensession->IsLoggedAccess( PILAR_ADMIN );
        $sess = $this->gensession->GetData( PILAR_ADMIN  );
        if( !$rowTram->Id ) return;

        $tram = $this->dbPilar->inProyTram($rowTram->Id);
        if(!$tram){ echo "No registro"; return; }
      
       $this->dbPilar->Update( "tesTramites", array(
          'Estado'    => 2,
          'FechModif' => mlCurrentDate()
        ), $tram->Id ); 
        $tram = $this->dbPilar->inProyTram($rowTram->Id);
        $Rowdicestatramite = $this->dbPilar->getSnapRow( "dicestadtram", "Id=$tram->Estado"); 
        //------------------Correo a los tesistas -------------
        $msg = "<h4> Enviado al Asesor </h4><br>"
        . "Su proyecto con codigo <b>".$tram->Codigo."</b> ha sido enviado a su Asesor con la "
        . "aprobacion del administrador de la Plataforma PILAR, su Asesor tendra un plazo de ". $Rowdicestatramite->Plazo." dias calendarios para determinar la aprobación o rechazo mediante la <b>Plataforma PILAR</b>.";

         $msgEnviar="El proyecto ".$tram->Codigo ." ha sido enviado al Asesor con el formato ya revisado, el Asesor tiene ". $Rowdicestatramite->Plazo." dias calendarios para revisarlo y determinar la aprobación o rechazo mediante la <b>Plataforma PILAR</b>.";
        //------------------------------------------------------------------------------------------------
        $this->logTramites( $sess->userId, $tram->Id, "Enviado al Asesor", $msgEnviar ); 

        if($tram->IdTesista2 !=0)
        {
            $mail = $this->dbPilar->inCorreo( $tram->IdTesista1);
            $mail2 = $this->dbPilar->inCorreo( $tram->IdTesista2);
            $cel= $this->dbPilar->inCelTesista( $tram->IdTesista1);
            $cel2= $this->dbPilar->inCelTesista( $tram->IdTesista2);
            $this->logCorreos( 0,$tram->IdTesista1,$mail, "Proyecto para Asesoria", $msg );
            $this->logCorreos( 0,$tram->IdTesista2, $mail2, "Proyecto para Asesoria", $msg );
        //    $this->notiCelu($cel,2);
        //    $this->notiCelu($cel2,2);
        }
        else
        {
            $mail = $this->dbPilar->inCorreo( $tram->IdTesista1);
            $cel= $this->dbPilar->inCelTesista( $tram->IdTesista1);
            $this->logCorreos( 0,$tram->IdTesista1,$mail, "Proyecto para Asesoria", $msg );
            $mail = $this->dbPilar->inCorreo( $tram->IdTesista1);
        //    $this->notiCelu($cel,2);
        }
        //---------------------FIN----------------------------   
       //----------------Correo al Asesor---------------------
        $msgAsesor = "<h4> Proyecto para Asesoria </h4><br>"
              . "Se le ha remitido el proyecto con código <b>$tram->Codigo</b> "
              . "Ud. tiene ". $Rowdicestatramite->Plazo." días calendarios para revisarlo y determinar la aprobación o rechazo mediante la <b>Plataforma PILAR</b>.";
        $mail = $this->dbRepo->inCorreo( $tram->IdJurado4 );
        $celu = $this->dbRepo->inCelu( $tram->IdJurado4 );
        $this->logCorreos( $tram->IdJurado4,0, $mail, "Proyecto para Asesoria", $msgAsesor );
  //---------------------FIN----------------------------  
        echo $msgEnviar;
    }

    //Utilizado : Proyecto rechazado por formato 
    private function inRechaza( $rowTram, $sess )
    {
        $tram = $this->dbPilar->inProyTram( $rowTram->Id );
        if( $tram->Estado >= 2 ) {
            echo "Error: No es borrable";
            return;
        }
        $titulo='Corregir Formato de Proyecto de Tesis'; //agregado unuv.2.0
        $msg = $_POST["msg"];

        $this->dbPilar->Update( "tesTramites", array('Tipo'=>0,'Estado'=>0,'FechModif' => mlCurrentDate()), $tram->Id );
        $msg1="el proyecto de tesis con codigo <b class='text-danger'>$tram->Codigo</b> fue Retornado,se notifico mediante correo electronico y mensaje de texto al Tesista";
        $msgenviar ="<b>Saludos.</b><br>\nSu proyecto con codigo <b>".$tram->Codigo."</b> ha sido rechazado, contiene los siguientes errores:  <br><br>\n".$msg
         ." <br><br> <em>Nota : Debera corregir las observaciones de su proyecto e iniciar nuevamente su tramite.</em>"; //Agregado unuv1.0

         $this->logTramites( $sess->userId, $tram->Id, "Retorna Proyecto : Corregir Observaciones", $msgenviar ); 
        if($tram->IdTesista2 !=0)
          {
            $mail = $this->dbPilar->inCorreo( $tram->IdTesista1);
            $mail2 = $this->dbPilar->inCorreo( $tram->IdTesista2);
            $cel= $this->dbPilar->inCelTesista( $tram->IdTesista1);
            $cel2= $this->dbPilar->inCelTesista( $tram->IdTesista2);
            $this->logCorreos( 0,$tram->IdTesista1, $mail,  $titulo, $msgenviar );
            $this->logCorreos( 0,$tram->IdTesista2, $mail2,  $titulo, $msgenviar );
        //    $this->notiCelu($cel,1);
        //    $this->notiCelu($cel2,1);
          }
        else
        {
            $cel= $this->dbPilar->inCelTesista( $tram->IdTesista1);
            $mail = $this->dbPilar->inCorreo( $tram->IdTesista1);
            $this->logCorreos(0,$tram->IdTesista1,$mail,  $titulo, $msgenviar );
        //    $this->notiCelu($cel,1);
          }           
        echo  $msg1;
    }
    

    //Utilizado : Proyecto rechazado por exceso de tiempo
    public function inRechaDire( $rowTram, $sess )
    {
        $tram = $this->dbPilar->inProyTram( $rowTram->Id );
        if( $tram->Estado != 2 ) {
            echo "Error: No es retornable.";
            return;
        }
        $fec = mlFechaNorm($rowTram->FechModif);
        $pas = mlDiasTranscHoy($rowTram->FechModif);
             ;
        // no borramos pero dejamos para consultas de eliminacion
        $this->dbPilar->Update( "tesTramites", array('Tipo'=>0,'FechModif' => mlCurrentDate()), $tram->Id );

         $msgEnviar="El proyecto ".$tram->Codigo ." ha sido archivado por exceso de tiempo del proyecto en la bandeja del Asesor.";    
        $this->logTramites($sess->userId, $tram->Id, "Exceso de tiempo Asesor", $msgEnviar);

         $msg = "<h4> Rechazado por Exceso de Tiempo </h4><br>"
        . "Su proyecto con codigo <b>".$tram->Codigo."</b> se encuentra archivado por exceso de tiempo en la bandeja del Asesor.<br><br>"
        ."<em>Nota : Para la activacion del proyecto el docente deberá presentar documentos sustentadorios ante su Comision de Grados y Titulos de su Facultad, caso contrario podra iniciar nuevamente su tramite.</em>.</b></p>";

        if($tram->IdTesista2 !=0)
        {
            $mail = $this->dbPilar->inCorreo( $tram->IdTesista1);
            $mail2 = $this->dbPilar->inCorreo( $tram->IdTesista2);
            //$cel= $this->dbPilar->inCelTesista( $tram->IdTesista1);
           // $cel2= $this->dbPilar->inCelTesista( $tram->IdTesista2);
            $this->logCorreos( 0,$tram->IdTesista1,$mail, "Exceso de tiempo Asesor", $msg );
            $this->logCorreos( 0,$tram->IdTesista2, $mail2, "Exceso de tiempo Asesor", $msg );
           // $this->notiCelu($cel,2);
           // $this->notiCelu($cel2,2);
        }
        else
        {
            $mail = $this->dbPilar->inCorreo( $tram->IdTesista1);
            $cel= $this->dbPilar->inCelTesista( $tram->IdTesista1);
            $this->logCorreos( 0,$tram->IdTesista1,$mail, "Exceso de tiempo Asesor", $msg );
            $mail = $this->dbPilar->inCorreo( $tram->IdTesista1);
            //$this->notiCelu($cel,2);
        }
        //---------------------FIN----------------------------   
       //----------------Correo al Asesor---------------------
        $msgAse = "<h4> Rechazado por Exceso de Tiempo </h4><br>"
              . "El proyecto con codigo <b>$tram->Codigo</b> "
              . "se ha archivado por motivo de exceso de tiempo en su bandeja de la Plataforma PILAR.<br><br>"
              ."<em>Nota : Para la activacion del proyecto el deberá presentar documentos sustentadorios ante su Comision de Grados y Titulos de su Facultad, caso contrario el tesista podra iniciar nuevamente su tramite.</em>.</b></p>";;
        $mail = $this->dbRepo->inCorreo( $tram->IdJurado4 );
        $celu = $this->dbRepo->inCelu( $tram->IdJurado4 );
        $this->logCorreos( $tram->IdJurado4,0, $mail, "Exceso de tiempo Asesor", $msgAse );
  //---------------------FIN----------------------------
  
        echo $msgEnviar;
    }

    public function tesHabili( $idtram=0 )
    {
        $this->gensession->IsLoggedAccess( PILAR_ADMIN );
        if( !$idtram ){
            echo "No tiene tramite activo";
            return;
        }

        $args = array(
            'tram' => $this->dbPilar->inProyTram($idtram)    ,  // full
            'habs' => $this->dbPilar->inHabilits($idtram)    ,  // Habils
            'dets' => $this->dbPilar->inTramDetIter($idtram)    // it:1
        );
   
        $this->load->view( "pilar/admin/edtHabSub", $args );
        // echo " Nolo hagas ";
    }

    //Agregado unuv2.0
    public function habilitarProyecto( $idtram=0 )
    {
        $this->gensession->IsLoggedAccess( PILAR_ADMIN );
        if( !$idtram ){
            echo "No tiene tramite activo";
            return;
        }

        $args = array(
            'tram' => $this->dbPilar->inProyTram($idtram)    ,  // full
            'dets' => $this->dbPilar->inLastTramDet($idtram)    // it:1
        );
   
        $this->load->view( "pilar/admin/HabProy", $args );
        // echo " Nolo hagas ";
    }

    public function tesRenunc( $idtram=0 )
    {
        $this->gensession->IsLoggedAccess( PILAR_ADMIN );
        if( !$idtram ){
            echo "No tiene tramite activo";
            return;
        }

        $args = [
            'idtram' => $idtram,
            'titulo' => $this->dbPilar->getOneField( "tesTramsDet", "Titulo", "IdTramite=$idtram ORDER BY Id DESC" )
        ];

        $this->load->view( "pilar/admin/edtRenun", $args );
    }

    public function inSaveRenun()
    {
        $this->gensession->IsLoggedAccess( PILAR_ADMIN );
        $sess = $this->gensession->GetData( PILAR_ADMIN );

        $idtram = mlSecurePost("idtram");
        $motivo = mlSecurePost("motivo");

        $tram = $this->dbPilar->inProyTram($idtram);
        $this->dbPilar->Update( "tesTramites", array("Tipo"=>0,"Estado" =>0,'FechModif'  => mlCurrentDate()), $idtram );

        $motivo = "<b>Renucia a Proyecto de Tesis</b><br><br><b>Motivo:</b> ".$motivo . "<br><b>Codigo de Proyecto:</b> $tram->Codigo<br><br>". "Por tal motivo se procede a anular el presente trámite";

        // al log
        $this->logTramites( $sess->userId, $tram->Id, "Renuncia a Proyecto de Tesis", $motivo );

         if($tram->IdTesista2 !=0)
          {
            $mail = $this->dbPilar->inCorreo( $tram->IdTesista1);
            $mail2 = $this->dbPilar->inCorreo( $tram->IdTesista2);
            $cel= $this->dbPilar->inCelTesista( $tram->IdTesista1);
            $cel2= $this->dbPilar->inCelTesista( $tram->IdTesista2);
            $this->logCorreos(0, $tram->IdTesista1, $mail, "Renuncia a Proyecto de Tesis", $motivo );
            $this->logCorreos(0, $tram->IdTesista2, $mail2, "Renuncia a Proyecto de Tesis",$motivo );
            //$a=$this->notiCelu($cel,4);
           // $a=$a." - ".$this->notiCelu($cel2,4);
          }
        else
          {
            $mail = $this->dbPilar->inCorreo( $tram->IdTesista1);
            $cel= $this->dbPilar->inCelTesista( $tram->IdTesista1);
            $this->logCorreos( 0,$tram->IdTesista1,$mail, "Renuncia a Proyecto de Tesis", $motivo);
           // $a=$this->notiCelu($cel,4);
          }

        // enviamos al tesista y a los jurados

       // $this->logCorreo( $tram->Id, $this->dbPilar->inCorreo($tram->IdTesista1), "Renuncia a Proyecto de Tesis", $motivo );
        $this->logCorreos($tram->IdJurado1,0,$this->dbRepo->inCorreo($tram->IdJurado1) , "Renuncia a Proyecto de Tesis", $motivo );
        $this->logCorreos( $tram->IdJurado2,0,$this->dbRepo->inCorreo($tram->IdJurado2) , "Renuncia a Proyecto de Tesis", $motivo );
        $this->logCorreos( $tram->IdJurado3,0, $this->dbRepo->inCorreo($tram->IdJurado3) , "Renuncia a Proyecto de Tesis", $motivo );
        $this->logCorreos( $tram->IdJurado4,0, $this->dbRepo->inCorreo($tram->IdJurado4) , "Renuncia a Proyecto de Tesis", $motivo );

        //echo $motivo;
        echo "<br>El proyecto se <b>Anulo Correctamente</b>.";
    }


    //agregadounuv2.0
    public function inSaveHabilitarPro()
    {
        $this->gensession->IsLoggedAccess( PILAR_ADMIN );
        $sess = $this->gensession->GetData( PILAR_ADMIN );

        $idtram = mlSecurePost("idtram");
        $motivo = mlSecurePost("motivo");

        $tram = $this->dbPilar->inProyTram($idtram);
        $this->dbPilar->Update( "tesTramites", array("Tipo"=>1,'FechModif'  => mlCurrentDate()), $idtram );

        $motivo = "<b>Halibitación de Proyecto de Tesis</b><br><br><b>Motivo:</b> ".$motivo . "<br><b>Codigo de Proyecto:</b> $tram->Codigo<br><br>". "Por tal motivo se procede a anular el presente trámite";

        // al log
        $this->logTramites( $sess->userId, $tram->Id, "Habilitación de Proyecto de Tesis", $motivo );

         if($tram->IdTesista2 !=0)
          {
            $mail = $this->dbPilar->inCorreo( $tram->IdTesista1);
            $mail2 = $this->dbPilar->inCorreo( $tram->IdTesista2);
            $cel= $this->dbPilar->inCelTesista( $tram->IdTesista1);
            $cel2= $this->dbPilar->inCelTesista( $tram->IdTesista2);
            $this->logCorreos(0, $tram->IdTesista1, $mail, "Habilitación de Proyecto de Tesis", $motivo );
            $this->logCorreos(0, $tram->IdTesista2, $mail2, "Habilitación de Proyecto de Tesis",$motivo );
            //$a=$this->notiCelu($cel,4);
           // $a=$a." - ".$this->notiCelu($cel2,4);
          }
        else
          {
            $mail = $this->dbPilar->inCorreo( $tram->IdTesista1);
            $cel= $this->dbPilar->inCelTesista( $tram->IdTesista1);
            $this->logCorreos( 0,$tram->IdTesista1,$mail, "Habilitación de Proyecto de Tesis", $motivo);
           // $a=$this->notiCelu($cel,4);
          }

        // enviamos al tesista y a los jurados

       // $this->logCorreo( $tram->Id, $this->dbPilar->inCorreo($tram->IdTesista1), "Renuncia a Proyecto de Tesis", $motivo );
        $this->logCorreos($tram->IdJurado1,0,$this->dbRepo->inCorreo($tram->IdJurado1) , "Habilitación de Proyecto de Tesis", $motivo );
        $this->logCorreos( $tram->IdJurado2,0,$this->dbRepo->inCorreo($tram->IdJurado2) , "Habilitación de Proyecto de Tesis", $motivo );
        $this->logCorreos( $tram->IdJurado3,0, $this->dbRepo->inCorreo($tram->IdJurado3) , "Habilitación de Proyecto de Tesis", $motivo );
        $this->logCorreos( $tram->IdJurado4,0, $this->dbRepo->inCorreo($tram->IdJurado4) , "Habilitación de Proyecto de Tesis", $motivo );

        //echo $motivo;
        echo "<br>El proyecto se <b>Habilito Correctamente</b>.";
    }


    public function listBusqTesi()
    {
        // mostrar ampliación si tiene

        // busqueda detallada del estado de tesistas
        //
        $this->gensession->IsLoggedAccess( PILAR_ADMIN );

        $cod = mlSecurePost( "cod" );
        $dat = mlSecurePost( "dni" );
        if( !$cod && !$dat ) return;

        $idtes = 0;
        $datas = 0;
        if( $cod ) {
            $trams = $this->dbPilar->inTramByCodigo( $cod );
            if( $trams ){
                $datas = $this->dbPilar->getSnapRow( "vxDatTesistas", "Id=$trams->IdTesista1" );
                $idtes = $trams->IdTesista1;
            }
        }
        else {

            if( is_numeric($dat) and strlen($dat)==6 ){

                $datas = $this->dbPilar->getSnapRow( "vxDatTesistas", "Codigo='$dat'" );
                $idtes = ($datas)? $datas->Id : 0;

            } else {

                $filto = is_numeric($dat)? "DNI LIKE '$dat%'" : "DatosPers LIKE '%$dat%'";
                $datas = $this->dbPilar->getSnapRow( "vxDatTesistas", $filto );
                $idtes = ($datas)? $datas->Id : 0;
            }

            $trams = (!$datas)? null : $this->dbPilar->inTramByTesista( $datas->Id );
        }

        if( !$trams && !$datas ) {
            echo "Sin registros";
            return;
        }

        // verificar que exista un trámite
        $idTram = ($trams)? $trams->Id : 0;

        // renderizamos los resultados
        $this->load->view( "pilar/admin/verResTram", array(
                'idtes' => $idtes,
                'tdata' => $datas,
                'ttram' => $trams,
                'proyA' => $this->dbPilar->inTramDetIter($idTram,3),
                'tamps' => $this->dbPilar->inAmpliacion($idTram),
                'tdets' => $this->dbPilar->inProyDetail( $trams? $trams->Id : 0 )
            ) );
    }

    public function listBusqTram()
    {
        // control de activaciones de borrados
        $this->gensession->IsLoggedAccess( PILAR_ADMIN );

        $cod = mlSecurePost( "cod" );
        $dni = mlSecurePost( "dni" );
        if( !$cod && !$dni ) return;

        // 1. si ya esta activado fecha
        // 2. mostar datos
        // 3. despues de 30 mostra boton


        // obtener tramite por codigo
        //
        $tram = $this->dbPilar->inTramByCodigo( $cod );
        if( !$tram ) {
            echo "Sin resultados para: <b>$cod</b>.";
            return;
        }

        // verificar si ya fue activado
        //
        if( $tram->Tipo == 2 or $tram->Estado >= 10 ) {
            $FechaAct = mlFechaNorm( $tram->FechActBorr );
            echo "El trámite ya se activo el: <b>$FechaAct</b>";
            return;
        }

        if( $tram->Estado <= 5 ) {
            echo "Aun no tiene acta de Aprobación de proyecto";
            return;
        }


        $det = $this->dbPilar->inLastTramDet( $tram->Id );
        $dias = mlDiasTranscHoy( $det->Fecha );
        $fech = mlFechaNorm( $det->Fecha );

        echo "<small>";
        echo "Proyecto: " .substr($det->Titulo,0,90). " ... <br>";
        echo "Trasncurrieron: <b>$dias dias</b> - desde $fech";
        echo "</small>";

        // para los casos de Enfermeria
        if( $dias >= 40 ) {
            // if($det->row()->IdCarrera==35){

            echo "<br><br>";
            echo "<button onclick=\"actiTram()\" class='btn btn-success'>"
               . " Activar Tramite de Borrador </button>";
            // }
        }

        // almacenar datos en sesion para activar
        mlSetGlobalVar( "pCodTram", $tram->Codigo );
    }


    // activacion de tramite de borradores
    //
    public function lisActTram()
    {
        $this->gensession->IsLoggedAccess( PILAR_ADMIN );

        $codTram = mlGetGlobalVar( "pCodTram" );
        if( !$codTram ) return;


        // tramite por session y codigo seguro
        //
        $tram = $this->dbPilar->inTramByCodigo($codTram);
        if( $tram->Tipo == 2 and $tram->Estado >= 10 )
            return;

        // procedemos actualizar esta wada
        //
        $this->dbPilar->Update( "tesTramites", array(
                'Tipo'       => 2,
                'Estado'     => 10,
                'FechModif'  => mlCurrentDate(),
                'FechActBorr'=> mlCurrentDate()
            ), $tram->Id );


        echo "Acción completada con éxito.";
        $idTram = mlGetGlobalVar( "pCodTram", null );
    }

    //modificado unuv1.0 - Mantenimiento Docente
    public function dataDocen( $id=0 )
    {
        $this->gensession->IsLoggedAccess( PILAR_ADMIN );



        $rowDoc = $this->dbRepo->getSnapRow( "vwDocentes", "Id=$id" );
        if( !$rowDoc ) return;

        //$media = $this->genapi->getDataPer( $rowDoc->DNI ); Comentado por bet
         $media =$this->dbRepo->getSnapRow( "tblcandidatosdocentes", "dni=$rowDoc->DNI" );
        // compatible con tblEstadoDocente
        //
        if( $rowDoc->Activo  < 0 )  $estado = "Fallecido" ;
        if( $rowDoc->Activo == 0 )  $estado = "Deshabilitado" ;
        if( $rowDoc->Activo == 1 )  $estado = "Sin actividad (CESADO)" ;
        if( $rowDoc->Activo == 2 )  $estado = "Sancionado" ;
        if( $rowDoc->Activo == 3 )  $estado = "Licencia/Sabatico" ;
        if( $rowDoc->Activo == 4 )  $estado = "Autoridad Universitaria" ;
        if( $rowDoc->Activo == 5 )  $estado = "Cargo/Jefatura" ;
        if( $rowDoc->Activo == 6 )  $estado = "Docente Ordinario" ;


        // renderizar
        //
        $this->load->view( "pilar/admin/repoEdiDoc", array(
                'estado' => "($rowDoc->Tipo) - $estado",
                'testas' => $this->dbRepo->getTable( "dicEstadosDoc" ),
                'tcateg' => $this->dbRepo->getTable( "dicCategorias" ),
                'tfacus' => $this->dbRepo->getTable( "dicFacultades" ),
                'tcarre' => $this->dbRepo->getTable( "dicCarreras" ),
                'testdc' => $this->dbRepo->getTable( "dicEstadosDoc" ),
                'media'  => $media,
                'rowDoc' => $rowDoc
            ) );

    }

    // activacion de docentes
    //
    //modificado unuv1.0 - Mantenimiento Docente
    public function listDocRepo( $codigo)
    {

       $this->gensession->IsLoggedAccess( PILAR_ADMIN );
        $expr = $codigo;

        if( !$expr ) return;
        $filtro = is_numeric($expr)? "DNI LIKE '$expr%'" : "DatosPers LIKE '%$expr%'";

        // listado por grupos de nombres
        //
        $rowDoc = $this->dbRepo->getSnapView( "vwDocentes", "Id=$codigo" );
        if( $rowDoc->num_rows() >= 2 ){
            $nro = 0;
            echo "<table class='table table-striped table-bordered' style='font-size: 12px'>";
            foreach( $rowDoc->result() as $row ){
                $nro += 1;
                $evt = "$('#tblist').load('admin/dataDocen/$row->Id')";
                $btn = "<button onclick=\"$evt\" class='btn btn-warning btn-xs'> VER </button>";
                echo "<tr>";
                echo "<td> $nro </td>";
                echo "<td> $btn </td>";
                echo "<td> $row->DatosPers </td>";
                echo "<td> $row->Facultad </td>";
                echo "</tr>";
            }
            echo "</table>";
            return;
        }

        if( $rowDoc->num_rows() )
            $this->dataDocen( $rowDoc->Row()->Id );
    }


    // editar datos de docentes e historia de cambios
    //Modificacion unuv1.0 - Mantenimiento Docente
    public function execEditDocRepo()
    {
        $this->gensession->IsLoggedAccess( PILAR_ADMIN );

        $idDoc  = mlSecurePost("id");

        $categ  = mlSecurePost( "categ" );
        $facult = mlSecurePost( "facul" );
        $carrer = mlSecurePost( "carre" );

        $dni    = mlSecurePost( "dni" );
        $codigo = mlSecurePost( "codigo" );
        $apells = mlSecurePost( "apels" );
        $nombes = mlSecurePost( "nomes" );
        $fechNa = mlSecurePost( "fechaNac" );
        $direcc = mlSecurePost( "direcc" );
        $correo = mlSecurePost( "mail" );
        $celula = mlSecurePost( "celu" );
        $resolC = mlSecurePost( "resolCon" );
        $fechaC = mlSecurePost( "fechaCon" );
        $resolA = mlSecurePost( "resolAsc" );
        $fechaA = mlSecurePost( "fechaAsc" );
        $fechaI = mlSecurePost( "fechaIn" );

        $cambest = mlSecurePost( "cambest" );
        $nuvesta = mlSecurePost( "nesta" );
        $descrip = mlSecurePost( "desc" );
        $documen = mlSecurePost( "docu" );

        $clave = mlSecurePost( "clave" );


        $rowDoc = $this->dbRepo->getSnapRow( "tblDocentes", "Id='$idDoc'" );


        //echo "Procesando...";
        if( $cambest == "si" ) {

            $this->dbRepo->Insert( "tblLogDocentes", array(
                    'IdDocente' => $idDoc,
                    'EstadoAnt' => $rowDoc->Activo,
                    'EstadoNvo' => $nuvesta,
                    'Detalle'   => $descrip,
                    'Documento' => $documen,
                    'Fecha'  => mlCurrentDate()
                ) );

            $this->dbRepo->Update( "tblDocentes", array(
                'Activo'   => $nuvesta,
                'FechaCon' => $fechaC,
                'FechaNac' => $fechNa,
                'ResolCon' => $resolC
            ), $idDoc );

            echo "<br>* Log Agregado";
            echo "<br>* Estado cambiado a $nuvesta";
            
        }


        // edicion final de datos
        $this->dbRepo->Update( "tblDocentes", array(
                'DNI' => $dni,
                'IdCategoria' => $categ,

                'FechaIn'    => $fechaI,
                'ResolCon'   => $resolC,
                'FechaAsc'   => $fechaA,
                'ResolAsc'   => $resolA,

                'Apellidos'  => mb_strtoupper($apells),
                'Nombres'    => mb_strtoupper($nombes),
                'Codigo'     => $codigo,
                'Correo'     => $correo,
                'NroCelular' => $celula,
                'FechaNac'   => $fechNa,
                'Direccion'  => $direcc
            ), $idDoc );

        echo "<br>* Datos Pers editados";


        // final
        if( $clave ) {
            // actualizamos solo si puso
            $this->dbRepo->Update( "tblDocentes", array('Clave'=>sqlPassword($clave)), $idDoc );
            echo "<br>* Contraseña cambiada";
        }

        $msg = "<h4>Actualizacion de Datos</h4><br>"
                . "Sr(a). <b>$nombes $apells</b> se ha actualizado sus datos en la PLATAFORMA PILAR."
                ;

            // grabar en LOG de correos y envio mail.
            $this->logCorreos( $idDoc,0,$correo, "Modificación en Datos Docente", $msg );
        echo "<br><b>fin!</b> <hr>";
        echo "<a onclick=\"lodPanel('admin/panelLista')\" href=\"javascript:void(0)\" class=\"btn btn-info\"> Refrezcar </a>";
    }


   //Agregar unuv1.0 - Mantenimiento Docente
    public function DocenteGrados($iddocen)
    {
        $this->gensession->IsLoggedAccess( PILAR_ADMIN );
        $graDoc = $this->dbPilar->getTable("docEstudios","IdDocente=$iddocen");
        
        foreach( $graDoc->result() as $row ) 
            {              
              echo "<tr>";
                echo "<td> $row->AbrevGrado   </td>";
                echo "<td>$row->Mencion</td>";
                echo "<td>$row->Universidad</td>";
                echo "<td>$row->Fecha</td>";
                echo "<td>$row->Archivo</td>";
              echo "</tr>";   
            }    

    }
     //Agregar unuv2.0 - Mantenimiento Docente
    public function DocenteLineas($iddocen)
    {
        $this->gensession->IsLoggedAccess( PILAR_ADMIN );
        $graDoc = $this->dbPilar->getTable("doclineas","IdDocente=$iddocen");
         
        foreach( $graDoc->result() as $row ) 
            { $nombreLinea = $this->dbRepo->getSnapRow("tbllineas","Id=$row->IdLinea");
              echo "<tr>";
                echo "<td> $row->Id   </td>";
                echo "<td>$nombreLinea->Nombre</td>";                
                echo "<td>$row->Estado</td>";
                echo "<td>$row->Fecha</td>";
              echo "</tr>";   
            }    

    }
    //Agregar unuv1.0 - Mantenimiento Docente
    public function AgregarGrados()
    {
        $this->gensession->IsLoggedAccess( PILAR_ADMIN );

        $iddoce = mlSecurePost( "doc" );
        $universidad = mlSecurePost( "universidad" );
        $abrev = mlSecurePost( "abrev" );
        $fecha = mlSecurePost( "fecha" );
        $mencion = mlSecurePost( "mencion" );
        $nomarch = mlSecurePost( "nomarch" );

        $Doc = $this->dbRepo->getTable("tblDocentes","Id=$iddoce");
        if( !$Doc ) return;

        $nombresAbrev = array( 1 => 'DR.',
                               2 => 'MG.',
                               3 => 'ING.',
                               4 => 'BACH.');
        $nombreUni = $this->dbRepo->getSnapRow("dicuniversidades","Id=$universidad");
        $Docente1 = $this->dbRepo->getSnapRow("tblDocentes","Id=$iddoce");
        $this->dbPilar->Insert("docEstudios", array(
            'IdDocente'  => $Docente1->Id,     
            'Universidad' => $nombreUni->Nombre,    
            'IdGrado'      => $abrev,  
            'AbrevGrado' => $nombresAbrev[$abrev],
            'Mencion' => $mencion,
            'Archivo' => '--',
            'Fecha' => $fecha
        )); 
        echo $iddoce;
    }

    public function AgregarLinea()
    {
        $this->gensession->IsLoggedAccess( PILAR_ADMIN );

        $iddoce = mlSecurePost( "doc1" );
        $linea = mlSecurePost( "linea1" );
        
        $Doc = $this->dbRepo->getTable("tblDocentes","Id=$iddoce");
        if( !$Doc ) return;

       
        
        if( $this->dbPilar->getSnapRow("doclineas","IdDocente='$iddoce' and IdLinea='$linea'") ) 
        {
            echo "no";
            return;
        }
        
        

        $this->dbPilar->Insert("doclineas", array(
            'IdDocente'  => $iddoce,     
            'IdLinea' => $linea,   
            'Tipo'      => '1',  
            'Estado' => '1',
            'Fecha' => date('Y-m-d H:i:s'),
            'Obs' => 'Sin Obervacion'
        )); 
        echo $iddoce;
        
    }

    //agregado unuv2.0

public function ModificarTesista()
    {
        $this->gensession->IsLoggedAccess( PILAR_ADMIN );

        $id = mlSecurePost( "id" );
        $codigo = mlSecurePost( "codigo" );
        $dni = mlSecurePost( "dni" );
        $nombres = mlSecurePost( "nombres" );
        $apellidos = mlSecurePost( "apellidos" );
        $correo = mlSecurePost( "correo" );
        $direccion = mlSecurePost( "direccion" );
        $celular = mlSecurePost( "celular" );
        $motivo = mlSecurePost( "motivo" );


     $Tes = $this->dbPilar->getSnapRow("tbltesistas","Id=$id");
        if( !$Tes ) return;

        $this->dbPilar->Update("tbltesistas", array(
            'DNI'  => $dni,    
            'Codigo' => $codigo,               
            'Apellidos' => $apellidos,  
            'Nombres' => $nombres,             
            'Direccion' => $direccion,
            'NroCelular' => $celular,
            'Correo' => $correo
        ),$id);
        $Tes = $this->dbPilar->getSnapRow("tbltesistas","Id=$id");
        $msg = "<h4>Saludos</h4><br>"
                . "Sr(a). <b>$nombres $apellidos</b> <br>"
                . "Se ha actualizado sus datos: $motivo";

        $this->logCorreos( 0,$Tes->Id, $Tes->Correo, "Modificación Datos Tesista ", $msg );
        echo "Se actualizado los datos del Tesista.";
    }
    // ingresar nuevo docente en repositior pass 123
    //Modificado unuv1.0 - mantenimiento Docente
    public function execNewDocRepo()
    {
        $this->gensession->IsLoggedAccess( PILAR_ADMIN );


        $categ  = mlSecurePost( "categ" );
        $facult = mlSecurePost( "facul" );
        $carrer = mlSecurePost( "carre" );

        $dni    = mlSecurePost( "dni" );
        $codigo = mlSecurePost( "codigo" );
        $apells = mlSecurePost( "apels" );
        $nombes = mlSecurePost( "nomes" );
        $fechNa = mlSecurePost( "fechaNac" );
        $direcc = mlSecurePost( "direcc" );
        $correo = mlSecurePost( "mail" );
        $celula = mlSecurePost( "celu" );
        $resolC = mlSecurePost( "resolCon" );
        $fechaC = mlSecurePost( "fechaCon" );
        $resolA = mlSecurePost( "resolAsc" );
        $fechaA = mlSecurePost( "fechaAsc" );
        $fechaI = mlSecurePost( "fechaIn" );

        $clave = mlSecurePost( "clave" );


        if( $this->dbRepo->getSnapRow("tblDocentes","DNI='$dni' AND Codigo='$codigo'") ) {
            echo "Existe uno identico con DNI y Codigo";
            return;
        }

        $this->dbRepo->Insert( "tblDocentes", array(
                'DNI'     => $dni,
                'Activo'  => 0,
                'Codigo'  => $codigo,
                'IdCategoria' => $categ,
                'IdFacultad'  => $facult,
                'IdCarrera'   => $carrer,
                'Apellidos'   => mb_strtoupper($apells),
                'Nombres'     => mb_strtoupper($nombes),
                'FechaCon'    => $fechaC,
                'ResolCon'    => $resolC,
                'Resolucion'  => "",
                'FechaIn'     => $fechaI,
                'FechaAsc'    => $fechaA,
                'ResolAsc'    => $resolA,
                'FechaNac'    => $fechNa,
                'Direccion'   => $direcc,
                'NroCelular'  => $celula,
                'Correo'      => $correo,
                'Clave'       => sqlPassword($clave),
                'Clavealeatorio' => sqlPassword($clave)
            ) );


            $msg = "<h4>Bienvenido</h4>"
            . "Sr(a). <b>$nombes $apells</b> <br>"
            . "Ud. ha sido agregado como Docente y Jurado a la <b>Plataforma PILAR</b>.";

       $msg1 = "<h4>Bienvenido</h4>"
            . "Sr(a). <b>$nombes $apells</b> <br>"
            . "Ud. ha sido agregado como Docente y Jurado a la <b>Plataforma PILAR</b>."
            . "<br><br><b>Datos de su Cuenta:</b><br>"
                . "  * usuario: $correo<br>"
                . "  * contraseña: $clave<br>"
           . "<br> Nota: Se recomienda cambiar la contraseña una vez ingresada a la Plataforma PILAR."
            ;



       // grabar en LOG de correos y envio mail.
       $this->logCorreo( 0, $correo, "Inscripcion de Docente Nuevo", $msg1 );

       echo "Se ha creado al docente como parte de PILAR";
    }

    public function listCboCarrs( $idFacu=0, $marcado=0 )
    {
        //                                  -- ojo --
        // cuidado con estas funciones no sean riesgo
        //
        if( ! $idFacu ) return;

        $table = $this->dbRepo->getTable( "dicCarreras", "IdFacultad=$idFacu" );

        foreach( $table->result() as $row ) {
            $sel = ($marcado==$row->Id)?  "selected" : "";
            echo "<option value=$row->Id $sel> $row->Nombre </option>";
        }
    }


    /*
    public function notiAnun()
    {
        echo "Non";
        return;

        $nro = 1;
        $tbl = $this->dbPilar->getTable( "tblTesistas" );
        //$tbl = $this->dbPilar->getTable( "tblTesistas", "Id<=7" );


        foreach( $tbl->result() as $row ){
            $tram = $this->dbPilar->inTramByTesista( $row->Id );

            if( $tram )
            if( $tram->Estado>=3 AND $tram->Estado<=11 ) {

                $this->notiEnviar( $row->Correo );

                echo "$nro) $row->Id ($tram->Estado) ::: $row->Apellidos $row->Nombres ::: $row->Correo <br>";
                $nro++;
            }
        }
    }
    */

    private function notiEnviar( $correo )
    {
        $arch = "vriadds/vri/web/promomail/cooreoconcursos.html";
        $f1 = fopen( $arch, "r" );
        $html = fread( $f1, filesize($arch) );

        $this->genmailer->sendHtml( $correo, "Convocatoria a Concursos", $html );
    }
    public function notiCeluNuevo($cel,$tip)
    {
         $this->load->library('apismss'); 
         $number   = "0051$cel";
            if($tip==1){ // Tesista : mensaje rechazo del proyecto
               $mensaje  = "UNU -PILAR \nEstimado(a) Docente ha sido registrado en la Plafotma PILAR, para mayor informacion de su usuario y contraseña revise su correo institucional.  \n\n".date("d-m-Y")."\nPlataforma PILAR.";
            }
            else if($tip==2){ // Tesista : mensaje rechazo del proyecto
               $mensaje  = "UNU -PILAR \nEstimado(a) Tesista  su proyecto de tesis ha sido archivado, para mayor informacion revise su correo.  \n\n".date("d-m-Y")."\nPlataforma PILAR.";
            }
       
            else{
               $mensaje  = "UNU PILAR \no(a) Docente se le recuerda revisar la plataforma PILAR en http://pilar.unu.edu.pe/unu/pilar y verificar los proyectos de tesis pendientes.\n\n".date("d-m-Y")."\nPILAR.";
            }
        $result   = $this->apismss->sendMessageToNumber2($number,$mensaje);

        if ($result) {
           return "Mensaje Enviado al $number";
        }else{
           return  "Error al enviar mensaje : $number";
        }
    }

    public function notiCelu($cel,$tip)
    {
       $this->load->library('apismss'); 
         $number   = "0051$cel";
         if($tip==1){ // Tesista : mensaje rechazo del proyecto
           $mensaje  = "UNU -PILAR \nEstimado(a) Tesista su proyecto fue rechazado por la comisión de Grados y Titulos, revisar su correo para mas detalles del rechazo. \nDebera corregir y subir nuevamente su proyecto en la plataforma PILAR en http://pilar.unu.edu.pe/unu/pilar  \n\n".date("d-m-Y")."\nPlataforma PILAR.";
        }
        else if($tip==2){
           $mensaje  = "UNU PILAR \nEstimado(a) Tesista su proyecto ha sido enviado a su Asesor con la aprobacion de la comision de Grados y Titulos, su Asesor tendra un plazo de 3 dias calendarios para determinar la aprobación o rechazo mediante la Plataforma PILAR  en http://pilar.unu.edu.pe/unu/pilar  \n\n".date("d-m-Y")."\nPILAR.";
        }
        else if($tip==3){
           $mensaje  = "UNU PILAR \nEstimado(a) Docente Se le ha remitido un proyecto para asesoria, Ud. tiene 7 días calendarios para revisarlo y determinar la aprobación o rechazo mediante la Plataforma PILAR en http://pilar.unu.edu.pe/unu/pilar  \n\n".date("d-m-Y")."\nPILAR.";
        }else if($tip==4){ //jurado - tesista
           $mensaje  = "UNU PILAR \nEstimado(a) Tesista su Proyecto de Tesis ha sido enviado a los miembros de su Jurado,será revisado en un plazo de 15 dias Calendarios mediante la Plataforma PILAR en http://pilar.unu.edu.pe/unu/pilar  \n\n".date("d-m-Y")."\nPILAR.";
        }
        else if($tip==5){ //jurados  
            $mensaje  = "UNU PILAR \nEstimado(a) Docente usted fué SORTEADO como JURADO de un proyecto de tesis,Ud. tiene 15 días calendarios para revisarlo en la plataforma PILAR en http://pilar.unu.edu.pe/unu/pilar  \n\n".date("d-m-Y")."\nPILAR.";
        }
        else if($tip==6){ //jurados- asesor
           $mensaje  = "UNU PILAR \nEstimado(a) Docente se le notifica que uno de los proyecto de la cual Ud. asesora ha sido enviado a los miembros del Jurado puede revisarlo en la plataforma PILAR en http://pilar.unu.edu.pe/unu/pilar \n\n".date("d-m-Y")."\nPILAR.";
        }
        else if($tip==7){ //jurados- asesor
           $mensaje  = "UNU PILAR \nEstimado(a) Tesista Felicitaciones su proyecto ha sido aprobado ya puede visualizar y descargar su Acta de Aprobacion en la plataforma PILAR en http://pilar.unu.edu.pe/unu/pilar \n\n".date("d-m-Y")."\nPILAR.";
        }
        else if($tip==8){ //jurados- asesor
           $mensaje  = "UNU PILAR \nEstimado(a) Tesista su proyecto ha sido desaprobado por lo que se procedera a archivar el presente trámite y queda habilitad@ para realizar un nuevo trámite en la plataforma PILAR en http://pilar.unu.edu.pe/unu/pilar \n\n".date("d-m-Y")."\nPILAR.";
        }
        else{
           $mensaje  = "UNU PILAR \nEstimado(a) Docente se le recuerda revisar la plataforma PILAR en http://pilar.unu.edu.pe/unu/pilar y verificar los proyectos de tesis pendientes.\n\n".date("d-m-Y")."\nPILAR.";
        }
        $result   = $this->apismss->sendMessageToNumber2($number,$mensaje);

        if ($result) {
           return "Mensaje Enviado al $number";
        }else{
           return  "Error al enviar mensaje : $number";
        }
    }

    // los que estan excediendo en tiempos 730 dias
    public function verTiempos()
    {
        $this->gensession->IsLoggedAccess( PILAR_ADMIN );
        //-------------------------------------------------------
        // array de indices de ampliaciones
        //-------------------------------------------------------
        $arrAm = array();
        $table = $this->dbPilar->getTable( "dicAmpliaciones" );
        foreach( $table->result() as $row ){
            $arrAm[] = $row->IdTram;
        }

        $table = $this->dbPilar->getSnapView(
            "tesTramites",
            "Tipo>=1 AND Tipo<=2 AND Estado>=6 AND Anio=2016",
            "ORDER BY Tipo, Estado, Id" );


        echo "Total: ".$table->num_rows();

        echo "<table border=1 cellSpacing=0 cellPadding=6>";

        $nro = 1;
        foreach( $table->result() as $row ){

            $aler = "";
            $dets = $this->dbPilar->inTramDetIter($row->Id, 3);
            $dias = mlDiasTranscHoy( $dets->Fecha );

            // tramites con ampliacion
            //
            if( in_array( $row->Id, $arrAm ) ){
                $aler = ">> Ampliacion";
                echo "<BR>* (Id:$row->Id) $row->Codigo ::(Id:$row->Id) -- E($row->Estado) :: $dets->Fecha ($dias) dias $aler";
                continue;
            }

            if( $dias>=700 && $dias<=730 ){


                /*
                $aler = ":ALERTA";


                $msgx = "Su proyecto esta por cumplir los 2 años de ejecucíón (máximo de 730 dias), recuerde que al cumplir este plazo su proyecto sera archivado.<br><br>"
                      . "Dias de ejecución: $dias dias<br>"
                      . "Fecha de aprobación: $dets->Fecha<br>"
                      . "Codigo: $row->Codigo<br>"
                      . "Titulo: $dets->Titulo<br>"
                      ;

//                $mail = $this->dbPilar->inCorreo( $row->IdTesista1 );
//                $this->logCorreo( 0, $mail, "Alerta de Fin de Plazo de Ejecución", $msgx );

//                $dets = $this->dbPilar->inLastTramDet( $row->Id );
//                $autr = $this->dbPilar->inTesistas( $row->Id );
                //
                //*** echo "<BR>* $row->Codigo ::(Id:$row->Id) -- E($row->Estado) :: $dets->Fecha ($dias) dias $aler";
                //
                echo "<tr>";
                echo "<td> $nro </td>";
                echo "<td width=9%> $row->Codigo<br><small>(Id:$row->Id) </td>";
                echo "<td> $row->Estado </td>";
                echo "<td width=9%> $dets->Fecha </td>";
                echo "<td> $dias </td>";
                echo "<td> $dets->Titulo <br><small>:$autr</small </td>";
                echo "<td> $aler </td>";
                echo "</tr>";

                $nro++;
                */

            }
            if( $dias > 730 ){

                $aler = ">> ELIMINACIÓN";
                $autr = $this->dbPilar->inTesistas( $row->Id );

                if( $row->Estado >= 11 )
                    1;//$aler = ">> REVISAR";
                else{


                    echo "<tr>";
                    echo "<td> $nro </td>";
                    echo "<td width=9%> $row->Codigo<br><small>(Id:$row->Id) </td>";
                    echo "<td> $row->Estado </td>";
                    echo "<td width=9%> $dets->Fecha </td>";
                    echo "<td> $dias </td>";
                    echo "<td> $dets->Titulo <br><small>:$autr</small </td>";
                    echo "<td> $aler </td>";
                    echo "</tr>";

                    $nro++;


                    $msgx = "Su proyecto ha excedido los 2 años de ejecucíón (730 dias), se le notificará 3 veces antes de que sea archivado.<br><br>"
                      . "Notificación: Final<br>"
                      . "Dias de ejecución: $dias dias<br>"
                      . "Fecha de aprobación: $dets->Fecha<br>"
                      . "Codigo: $row->Codigo<br>"
                      . "Titulo: $dets->Titulo<br>"
                      ;


                    /*
                    $mail = $this->dbPilar->inCorreo( $row->IdTesista1 );
                    $this->dbPilar->Update( "tesTramites", ['Tipo'=>-2], $row->Id );
                    $this->logCorreo( $row->Id, $mail, "Proyecto de Tesis Archivado", $msgx );
                    $this->logTramites( 0, $row->Id, "Proyecto de Tesis Archivado", $msgx );
                    */
                }

            }
        }

        echo "</table>";
    }


    public function verAmpliados()
    {
        // 730 + 180 >> 910 eliminar tramites

        $nro = 1;
        $table = $this->dbPilar->getTable( "dicAmpliaciones" );
        foreach( $table->result() as $row ){

            $dets = $this->dbPilar->inTramDetIter($row->IdTram,3);
            $tram = $this->dbPilar->getSnapRow( "tesTramites", "Id=$row->IdTram" );
            $dias = mlDiasTranscHoy( $dets->Fecha ); // de aprobacion
            $aler = $dias>=910? "ALERTA" : "...";


            if( $tram->Tipo>=1 && $tram->Estado>0 && $tram->Estado<=13 && $dias>=910 )
            {
                echo "$nro) E:$tram->Estado | $tram->Codigo |($dias dias)| (i:$dets->Iteracion)  $dets->Titulo <br>($aler)";
                echo ">> Borrar";

                    $msgx = "Su proyecto ha excedido los 2 años de ejecucíón y la única ampliación (730+180 dias), se le notifica que el trámite será archivado.<br><br>"
                      . "Notificación: Archivamiento de trámite<br>"
                      . "Dias de ejecución: $dias dias<br>"
                      . "Fecha de aprobación: $dets->Fecha<br>"
                      . "Fecha de ampliación registrada: <b>$row->FechaPre</b> <br><br>"
                      . "Codigo: <b>$tram->Codigo</b> <br>"
                      . "Titulo: <b>$dets->Titulo</b> <br>"
                      ;

                /*
                    $mail = $this->dbPilar->inCorreo( $tram->IdTesista1 );
                    $this->logCorreo( 0, $mail, "Notificación de Cancelación", $msgx );

                    //$mail = $this->dbPilar->inCorreo( $row->IdTesista1 );

                    $this->dbPilar->Update( "tesTramites", ['Tipo'=>-2], $tram->Id );
                    $this->logCorreo( $row->Id, $mail, "Trámite Archivado", $msgx );
                    $this->logTramites( 0, $row->Id, "Trámite Archivado", $msgx );
                */

            }
            echo "<hr>";

            $nro++;
        }
    }

    public function archivarTram( $idtram=0 )
    {
        if( !$idtram ){
            echo "Id Tram inválido";
            return;
        }

            $dets = $this->dbPilar->inTramDetIter($idtram,3);
            $tram = $this->dbPilar->getSnapRow( "tesTramites", "Id=$idtram" );
            $dias = mlDiasTranscHoy( $dets->Fecha ); // de aprobacion
            $aler = $dias>=910? "ALERTA" : "...";

                echo "00) E:$tram->Estado | $tram->Codigo |($dias dias)| (i:$dets->Iteracion)  $dets->Titulo <br>($aler)";
                echo ">> Borrar";

                    //$msgx = "Su proyecto ha excedido los 2 años de ejecucíón y la única ampliación (730+180 dias), se le notifica que el trámite será archivado.<br><br>"
                    $msgx = "Su proyecto ha excedido los 2 años de ejecucíón (730), se le notifica que el trámite será archivado.<br><br>"
                      . "Notificación: Archivamiento de trámite<br>"
                      . "Dias de ejecución: $dias dias<br>"
                      . "Fecha de aprobación: $dets->Fecha<br>"
                      //. "Fecha de ampliación registrada: <b>$row->FechaPre</b> <br><br>"
                      . "Codigo: <b>$tram->Codigo</b> <br>"
                      . "Titulo: <b>$dets->Titulo</b> <br>"
                      ;


                    $mail = $this->dbPilar->inCorreo( $tram->IdTesista1 );
                    $this->logCorreo( 0, $mail, "Notificación de Cancelación", $msgx );

                    //$mail = $this->dbPilar->inCorreo( $row->IdTesista1 );

                    $this->dbPilar->Update( "tesTramites", ['Tipo'=>-2], $idtram );
                    $this->logCorreo( $idtram, $mail, "Trámite Archivado", $msgx );
                    $this->logTramites( 0, $idtram, "Trámite Archivado", $msgx );

    }

    public function addAmpliac( $idTram=0 )
    {
        $this->gensession->IsLoggedAccess( PILAR_ADMIN );
        if( ! $idTram ) return;

        if( $this->dbPilar->getSnapRow("dicAmpliaciones", "IdTram=$idTram" ) ){
            echo "Ya cuenta con ampliación";
            return;
        }

        $tram = $this->dbPilar->inProyTram( $idTram );
        $dets = $this->dbPilar->inTramDetIter( $idTram, 3 );
        $dias = mlDiasTranscHoy( $dets->Fecha );

        $args = array(
            'IdTram'    => $idTram,
            'FechaApro' => $dets->Fecha,
            'FechaPre'  => mlCurrentDate(),
            'Dias'      => 6*30,
            'Doc'       => '*'
        );


        $id = $this->dbPilar->Insert( "dicAmpliaciones", $args );


        $msgx = "Su proyecto ha sido ampliado al haber realizado la solicitud por un plazo máximo de (180 dias - 6 meses).<br>"
              . "<b>NOTA:</b> Por el estado de emergencia se dará una prorroga extra de 90 dias, vencido este plazo no habrá mas consideración y el trámite será <b>archivado definitivamente</b>.<br><br>"
              . "Dias de ejecución: $dias dias<br>"
              . "Fecha de aprobación: $dets->Fecha<br>"
              . "Codigo: $tram->Codigo<br>"
              . "Titulo: $dets->Titulo<br>"
              ;

        $mail = $this->dbPilar->inCorreo( $tram->IdTesista1 );
        $this->logCorreo( $idTram, $mail, "Ampliación de Proyecto de Tesis", $msgx );
        $this->logTramites( 0, $idTram, "Ampliación de Proyecto de Tesis", $msgx );

        echo "Ampliacion $id Efectuada <hr>$msgx";
    }

    public function sendMySms()
    {
        $num = mlSecurePost("num");
        $sms = mlSecurePost("sms");

        //echo "$num : $sms";
        $config['charset']  = 'UTF-8';
        $config['mailtype'] = "html";

        $this->genmailer->initialize($config);

        $this->genmailer->from('vriunap@yahoo.com');
        $this->genmailer->to( "enviarsms@mimensajito.com" );
        $this->genmailer->cc('vriunap@yahoo.com');

        $this->genmailer->subject( $num );
        $this->genmailer->message( $sms );

        echo "enviado a: $num";
    }
}


//- EOF
