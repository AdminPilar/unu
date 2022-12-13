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
 *   Modificado por Ing. Betxy Rojas 
 ***************************************************************************/


include( "absmain/mlLibrary.php" );
include( "absmain/mlotiapi.php" );


// tesBorrador
// Edicion 2018.a
define( "ANIO_PILAR", "2020" );
date_default_timezone_set('America/Lima'); //Agregado unuv1.0


class Tesistas extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->model('dbPilar');
        $this->load->model('dbRepo');
        $this->load->library("GenSession");
        $this->load->library("GenMailer");
        $this->load->library("GenSexPdf");
    }

    // Entrar al Admin //Modificado unuv1.0 -- (3.2)
    public function login() {

        $mail = mlSecurePost("mail");
        $pass = mlSecurePost("pass");
        if( !$mail ) return;

        // por DNI profesores $filto = is_numeric($dat)? "DNI LIKE '$dat%'" : "DatosPers LIKE '%$dat%'";

        // verificar existencia de correo
        if( ! $this->dbPilar->getSnapRow( "vxDatTesistas", "Correo='$mail'" ) ) {
            echo '[{"error":true, "msg":"Este Correo no está registrado, por favor comunicarse a soporte_pilar@unu.edu.pe"}]';
            return;
        }

        // ahora si comprobar cuenta
        $row = $this->dbPilar->loginByMail( "vxDatTesistas", $mail, sqlPassword($pass) );
        if( ! $row ) {
            $IdTesista = $this->dbPilar->getOneField( "vxDatTesistas", "Id", "Correo='$mail'"  );
            $this->logLogin( $IdTesista, "Clave incorrecta" );
            echo '[{"error":true, "msg":"Su clave es incorrecta"}]';
            return;
        }
        if($row->Activo == 0){ //agregado unuv1.0
            echo '[{"error":true, "msg":"Su cuenta esta desactivada por favor comunicarse al correo soporte_pilar@unu.edu.pe"}]';
            return;
        }

        //----------------------------------------------------------------
        // como todo esta correcto creamos la sesion usuario general
        //----------------------------------------------------------------
        /*
            'IdService' => 0x10
            'servName'  => 'utf8'
            'userLevel' => 0
            'userType'  => 0
            'userId'    => $userId,
            'userCod'   => $userCod
            'userDesc'  => $userDesc
            'userName'  => $userName
            'userMail'  => $userMail
            'userDNI'   => $userDNI
            'islogged'  => true
        */

        $this->gensession->SetUserLogin(
            'tesistas',
            $row->Id,
            $row->DatosPers,
            $row->Correo,
            $row->DNI,
            $row->Codigo,
            $row->IdCarrera
        );

        $this->logLogin( $row->Id, "Ingreso" );

        echo '[{"error":false, "msg":"OK, Estamos redireccionando..."}]';
    }

    // Salir de Tesistas
    public function logout() {

        $this->gensession->SessionDestroy();
        redirect( base_url("pilar"), 'refresh');
    }


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
                'Tipo'    => 'T',
                'IdUser'  => $idUser,
                'Accion'  => $obs,
                'IP'      => mlClientIP(),
                'OS'      => $this->agent->platform(),
                'Browser' => $agent,
                'Fecha'   => mlCurrentDate()
            ) );
    }
    //Modificado unuv1.0 -- (2.3)
    private function logCorreo( $idUser, $correo, $titulo, $mensaje )
    {
        // enviamos mail204800
        $this->genmailer->mailPilar( $correo, $titulo, $mensaje );

		// procedemos a grabarlo
        $this->dbPilar->Insert(
            'logCorreos', array(
            'IdDocente' => 0,
            'IdTesista' => $idUser,
            'Fecha'   => mlCurrentDate(),
            'Correo'  => $correo,
            'Titulo'  => $titulo,
            'Mensaje' => $mensaje
        ) );
    }

    private function logCorreoDoce( $iddoce,$idUser, $correo, $titulo, $mensaje )
    {
        // enviamos mail
        $this->genmailer->mailPilar( $correo, $titulo, $mensaje );

        // procedemos a grabarlo
        $this->dbPilar->Insert(
            'logCorreos', array(
            'IdDocente' => $iddoce,
            'IdTesista' => $idUser,
            'Fecha'   => mlCurrentDate(),
            'Correo'  => $correo,
            'Titulo'  => $titulo,
            'Mensaje' => $mensaje
        ) );
    }


    private function logTramites( $idUser, $tram, $accion, $detall )
    {
        $this->dbPilar->Insert(
            'logTramites', array(
                'Tipo'      => 'T',      // T D C A
                'IdUser'    => $idUser,
                'IdTramite' => $tram,
                'Quien'     => 'Tesista',
                'Accion'    => $accion,
                'Detalle'   => $detall,
                'Fecha'     => mlCurrentDate()
        ) );
    }
    //-----------------------------------------------------------------------------


    /*
    public function verx()
    {
        $sess = $this->gensession->GetData();
        print_r( $sess );
    }
    */
    //Modificado unuv1.0 -- (3.3)
    public function index()
    {
        if( mlPoorURL() )
            redirect( mlCorrectURL() );

        //
        // session usuario simple terminable
        //
        $sess = $this->gensession->GetData();

        if( !$sess ){
            redirect( base_url("pilar"), 'refresh');
            return;
        }

        // otro que no sea tesista kill
        if( $sess->userDesc != "tesistas" ) {
            $this->logout();
            return;
        }

        $this->inicia();
    }

    //------------------------------------------------------------------------------
    //Modificado unuv1.0 -- (3.4) - modificado unuv2.0
    public function inicia()
    {
        $this->gensession->IsLoggedAccess();
        $sess = $this->gensession->GetData();
        //agregado unuv1..0 - recuperacion de contraseña tesista
        $row = $this->dbPilar->getSnapRow("tblTesistas","Id='$sess->userId'"); //busca datos del tesista
        if($row->Clave == $row->Clavealeatorio) //creado 06/10/2021
        { 
            $this->load->view("pilar/tes/header", array('sess'=>$sess) );
            $this->load->view("pilar/tes/RecuperarPass");
         }
        else
        {
            $this->load->view("pilar/tes/header", array('sess'=>$sess) );
            $this->load->view("pilar/tes/menu");
            $this->load->view("pilar/tes/panelWork");
            $this->load->view("pilar/tes/footer");
        }  
    }

    //Agregado unuv1.0 - recuperacion de contraseña tesista - modificado unuv2.0
    public function CambiarPass()
    {
        $this->gensession->IsLoggedAccess();
        $sess = $this->gensession->GetData();
        if(!$sess){ echo "Por favor Logearse para poder realizar la modificación ";}        
        $passCambio = mlSecurePost( "passCambio" );
        $passCambio2 = mlSecurePost( "passCambio2" );       
        if($passCambio == $passCambio2)
        {            
            if($row = $this->dbPilar->getSnapRow("tblTesistas","Id='$sess->userId'"))
            {
                              
                    $this->dbPilar->Update( "tbltesistas", array(
                        'Clave'    =>sqlPassword($passCambio),
                        'Clavealeatorio' => NULL
                    ), $row->Id);

                    $msg=  "Estimado(a) .$row->Nombres.\n"
                    .'Se realizo exitosamente el cambio de contraseña. :   <br><b> Su nueva contraseña es :'.$passCambio.'</b>'          
                    ."<br><br> *Este es un mensaje automático, no responda por favor." ;

                    $msgcel = "Estimado(a) " .$row->Nombres."\n"
                    ."Se realizo exitosamente el cambio de contraseña. \n
                    \n".date("d-m-Y")." \nVRI - Plataforma PILAR UNU.  
                    \n*Este es un mensaje automático, no responda por favor.";

                    $cel= $this->dbPilar->inCelTesista( $row->Id);
                    $resCel= $this->NotificacionCelular($cel,$msgcel);
                    $this->logCorreo( $row->Id, $row->Correo, "Cambio de contraseña", $msg);
                    $this->logLogin( $row->Id, "Cambio de contraseña" );
                    echo "";
                    return;                
            }
        }
        echo "La nuevas contraseñas no coinciden ";
    }

    public function lineasTes()
    {
        $this->gensession->IsLoggedAccess();
        $sess = $this->gensession->GetData();

		// hack para E. Inicial
		$carre = $sess->IdCarrera;
		if( $sess->IdCarrera == 19 )
			$carre = 18;

        $lineas = $this->dbRepo->getTable("tblLineas","IdCarrera='$carre' AND Estado = '1'");
        $this->load->view("pilar/tes/tesLineas",array('lineas'=>$lineas));
    }

    // modificado unuv1 --(3.5)
    public function tesHerramientas()
    {
        $this->gensession->IsLoggedAccess();
        $this->load->view("pilar/tes/tesHerramientas");
    }

    // modificado unuv1 --(3.6)
    public function tesContacto()
    {
        $this->gensession->IsLoggedAccess();
        $sess = $this->gensession->GetData();
        $tram = $this->dbPilar->inRowTesista( $sess->userId );

        $contacto=$this->dbPilar->getSnapRow('tblSecres',"Id_Facultad=$tram->IdFacultad AND UserLevel=4");
        $this->load->view("pilar/tes/contactoCords",array(
            'nombre'=>$contacto->Resp,
            'mail'=>$contacto->Correo,
            'celular'=>$contacto->Celular,
        ));
    }

    // evaluar el proyecto y cada estado
    //modificado unuv1 --(3.7)
    //modificacion unuv1.o - Estado revision 2
    //modificacion unuv1.o - Estado revision 3
    public function tesProyecto()
    {
        $this->gensession->IsLoggedAccess();

        $sess = $this->gensession->GetData();
        $tram = $this->dbPilar->inTramByTesista( $sess->userId ); // Tipo > 0
        $estados = $this->dbPilar->getTable("dicEstadTram");
        $esta='';
        $plazo='';
        

        // no hay tramite disponble nuevo tramite
        if( ! $tram ) {

            $prev = $this->dbPilar->getTable( "tesTramites", "Tipo<='0' AND (IdTesista1=$sess->userId OR IdTesista2=$sess->userId)" );
            $this->load->view( "pilar/tes/proc/0_regproy", ['prev'=>$prev] );

            //echo "<h3> Se  esta preparando un módulo de acuerdo al nuevo reglamento en debate el 20-03-18 en Consejo Universitario.</h3>";
            // echo "Desde este punto si los proyectos no cumplen el nuevo formato serán rechazados. Los que cumplen seguirán su tramite hasta concluir el semestre.";
            return;
        }

        $fech=strtotime($tram->FechModif);
        $fech= date("d/m/Y",$fech);
        $diasp  = mlDiasTranscHoy( $tram->FechModif );
         $archivo =$this->dbPilar->inLastTramDet( $tram->Id )->Archivo;
                $link = base_url( "repositor/docs/$archivo" );

        foreach($estados->result() as $row){
            if($row->Id ==$tram->Estado){
                $esta=$row->Descrip;
                $plazo = $row->Plazo ." ".$row->Tipo;
            }
            }


		// si existe ver Iteraciones
		$dets = $this->dbPilar->inLastTramDet( $tram->Id );

		// sumar 3 revisiones para correcciones
		if( $tram->Estado == 4 || $tram->Estado == 5 || $tram->Estado==6) {
             $link = base_url( "repositor/docs/$dets->Archivo" );
            

                   
           
           // echo "Aqui puedes ver tu <b>proyecto</b> en Revisión: <a href='$link' target=_blank class='btn btn-warning'> Ver/Descargar Proyecto de Tesis </a><br>";
            // echo "<br><img class='img-responsive' src='http://vriunap.pe/vriadds/vri/web/convocatorias/comunicadoenero.png'</h4>";
            $iter=1;
            if($tram->Estado==5) //aumentado bet
            { 
                $iter=2; //echo "-------------- 2 da observaciones de jurados --------------------------";
            }
            if($tram->Estado==6) //aumentado bet
            { 
                $iter=3; //echo "-------------- 3 da observaciones de jurados --------------------------";
            }
			$this->load->view( "pilar/tes/proc/4_subcorr", array(
                            'sess'    => $sess,
			 		        'detTram' => $dets,
			 		        'arrCorr' => array(
			 				// enviamos un array organizado de correcciones
			 				1 => $this->dbPilar->inCorrecs( $tram->Id, 1,$iter),
			 				2 => $this->dbPilar->inCorrecs( $tram->Id, 2,$iter ),
			 				3 => $this->dbPilar->inCorrecs( $tram->Id, 3,$iter ),
                            4 => $this->dbPilar->inCorrecs( $tram->Id, 4,$iter )
			 	    ) ) );

			return;
		}

        // mostrar acte de aprobación
        if( $tram->Estado >= 8 ) {
            $link2 = base_url( "repositor/docs/$dets->Archivo" );
			$link = base_url( "pilar/tesistas/actaProyIn");
            $det = $this->dbPilar->inLastTramDet( $tram->Id );
            $dias = mlDiasTranscHoy( $det->Fecha );
            echo "<div class='text-center'>";
            echo "<center><img class='img-responsive' style='height:70px;' src='".base_url('vriadds/pilar/imag/pilar-tes.png')."'/> </center>";
			echo "<h1>¡Felicitaciones! </h1>";
            echo "<h4>Su Proyecto de Tesis ha sido <b class='text-success'>Aprobado</b></h4> Puede descargar su Acta de aprobación de Proyecto de Tesis. </h4>";
			echo "<hr> <a href='$link' target=_blank class='btn btn-info'> Ver/Descargar Acta </a>";
            echo " | <a href='$link2' target=_blank class='btn btn-success'> Ver Proyecto de Tesis </a> ";
            echo "</div>";
            return;
        }
        // ya existe como tramite
        
        /*switch ( $tram->Estado ) {
            case '1':
                $esta = "Proyecto de Tesis en revisión por la Comisión de Grados y Títulos.";
                $plazo ='2 dias calendarios';
                break;
            case '2':
                $esta = "Proyecto de Tesis en revisión por el Asesor";
                $plazo ='3 dias calendarios';
                break;
            case '3':
                $esta = "Proyecto de Tesis aprobado por el asesor y listo para sorteo";
                $plazo='2 dias calendarios'
                break;
            case '4':
                $esta = "En Revisión (E: $tram->Estado)";
                break;
            case '7':
                $esta = "En Dictaminación";
                break;
            default:
                break;
        }*/
       
        echo " <div class='panel panel-info'>
                    <div class='panel-heading'>
                        <h2 class='panel-title'> Estado del Tramite </h2>
                    </div>
                    <div class='panel-body' >
                        <b>Codigo proyecto : </b>".$tram->Codigo."<br>
                        <b>Titulo : </b>".$dets->Titulo."<br>
                        <b>Estado : </b>".$esta."<br>
                         <b>Fecha Actualización : </b>".$fech."<br>
                        <b>Plazo: </b>". $plazo." <br>
                        <b>Tiempo: </b>". $diasp ." dia(s)<br>
                        <b>Archivo PDF: </b><a href='$link' target=_blank title='Proyecto de Tesis' ><span class='glyphicon glyphicon-list-alt'></span> </a> <br> 
                    </div>
                </div>";
        return;    
    }

    // modificado unuv1 --(3.9.2)
    public function ValidarCodigo($extCod=0)
    {
        $this->gensession->IsLoggedAccess();
        $sess = $this->gensession->GetData();
        $tes1 = $this->dbPilar->inTesistByCod( $sess->userCod );
        $tes2 = $this->dbPilar->inTesistByCod( secureString($extCod) ); 
        $errorMsg = '';
        if( $extCod && !$tes2 )
            { 
                $errorMsg = "el $extCod no esta registrado aún"; 
            }
        if( $tes2 ) if( $tes1->IdCarrera != $tes2->IdCarrera )
            { 
                $errorMsg = "No son de la misma carrera"; $tes2 = null; 
            }
        if( $sess->userCod == $extCod )
            {  $errorMsg = "No debe repetir su Código"; $tes2 = null;              }
       if( $tes2 ) 
       { 
            $tram = $this->dbPilar->inTramByTesista( $tes2->Id );
            if($tram)                 
                { $errorMsg = "El $extCod , ya integra el trámite: <b>$tram->Codigo</b> "; $tes2 = null; }
          }
        echo $errorMsg;
    }

    // Modificado unuv1.0 --(4.1.1)
    ///modificado unuv1.0 - esatdo proyecto aprobado
    //modificado unuv2.0
    public function tesBorrador()
    {
        $this->gensession->IsLoggedAccess();

        $sess = $this->gensession->GetSessionData();
        $tram = $this->dbPilar->inTramByTesista( $sess->userId );

        
        // no hay tramite disponble nuevo tramite
        if( $tram == null ) 
        {
            echo "<br><br><center><h3>  ¡Lo sentimos! <br> Usted  aún no ha iniciado su tramite para su Tesis. </h3></center>";
            return;
        }

        /*Modificado 20/07/2022*/
        $det = $this->dbPilar->inLastTramDet( $tram->Id );
        //$det = $this->dbPilar->inTramDetIter($tram->Id, 3);
        $dias  = mlDiasTranscHoy( $det->Fecha );
        /*Fin*/

        // Anuncio para tesistas sin activacion de tram Borr
        if($tram->Tipo == 1 && $tram->Estado <= 8)
        {
            echo "<br><br><center><h3>  ¡Lo sentimos! <br> Usted aún no cumple los requisitos para este proceso (Borrador de Tesis). </h3></center>";
            /*echo "<hr><div class='alert alert-warning'>
            <span class='glyphicon glyphicon-exclamation-sign'></span> <b>Importante</b><p>Antes de continuar con el proceso usted deberá: <br><b>(a)</b> Completar el tiempo mínimo <br><b>(b)</b> Poseer el grado académico de Bachiller<br><br> Si cumple con los requisitos <b>(a) y (b)</b> está apto para proseguir con su trámite, de lo contrario deberá esperar hasta cumplir lo estipulado.</p></div>";*/
        }
        
        if( $tram->Tipo == 1 && $tram->Estado == 8  ) {

            // $det = $this->dbPilar->inLastTramDet( $tram->Id );
            // //$det = $this->dbPilar->inTramDetIter($tram->Id, 3);
            // $dias  = mlDiasTranscHoy( $det->Fecha );
            
            /**/ if($dias>=180){ /**/
            $this->load->view( "pilar/tes/proc/8_subbach", array(
                    'tram'    => $tram,
                    'detTram' => $det,
                    'dias' => $dias

                     ) );
            return;
            } /**/ else{
                echo "<br><br><center><h3>  ¡Lo sentimos! <br> Usted aún no cumple los requisitos para este proceso.</h3></center>";
                echo "<center><h4> Su proyecto tiene ".$dias." dia(s) de Ejecución de un total de 180 mínimos.</h4></center>";
                echo "<hr><div class='alert alert-warning'>
            <span class='glyphicon glyphicon-exclamation-sign'></span> <b>Importante</b><p>Antes de continuar con el proceso usted deberá: <br><b>(a)</b> Completar el tiempo mínimo <br><b>(b)</b> Poseer el grado académico de Bachiller<br><br> Si cumple con los requisitos <b>(a) y (b)</b> está apto para proseguir con su trámite, de lo contrario deberá esperar hasta cumplir lo estipulado.</p></div>";
            }
            /**/

            // $dias  = mlDiasTranscHoy( $tram->FechModif );
           /* echo "<center><img class='img-responsive' style='height:70px;' src='".base_url('vriadds/pilar/imag/pilar-tes.png')."'/> </center>";
            echo "<center><h2 class='text'>¿Presentación de Borrador de Tesis?</h2>";
            echo "<h4> Su proyecto tiene $dias dia(s) de Ejecución de un total de 180 mínimos. </h4> </center>";
            echo "<p>Antes de continuar con el proceso usted deberá : <br>(a) Completar el tiempo mínimo. <br>(b) Poseer el grado académico de Bachiller.<br> Si cumple con los requisitos (a) y (b) está apto para proseguir con su trámite, de lo contrario deberá esperar hasta cumplir lo estipulado. <br> <div class='alert alert-warning'><b>Nota :</b> La información registrada será responsabilidad del usuario y tienen caracter de <b>Declaración Jurada</b>, de lo contrario estará sujeto a las sanciones que determine la Universidad Nacional de Ucayali. </p></div>";

            $consulta=$this->dbPilar->getOneField('tesTramsBach',"Id","Estado=1 AND IdTesista=$sess->userId");

            if ($dias>=1 AND !$consulta) { //modificar 
                echo "<center><br>
                        <a  class='btn btn-lg btn-success'
                            href='javascript:void(0)' 
                            onclick=\"lodPanel('panelTesis','tesistas/uploadBachiller')\" '>
                            <span class='glyphicon glyphicon-upload'></span>
                            Cargar Bachiller
                        </a>
                     </center";
                }*/

        }
        //-------------------------------------------------------------

        // ahora si los estados una vez activados
		$dets = $this->dbPilar->inLastTramDet( $tram->Id );

        // sustentado y ejemplar entregado
        if( $tram->Tipo == 3 ) {
			echo "<h4>Felicitaciones</h4> Su trámite ha concluido en la Plataforma PILAR del Vicerrectorado de Investigación.";
        }

        if( $tram->Tipo != 2 ) return;

        

        if( $tram->Estado == 9 ){
        /**/ if( $dias>=180 ) { /**/       
            $this->load->view( "pilar/tes/proc/0_regborr",array(
                'doc' => array(
                    1=>$this->dbRepo->inDocenteRow($tram->IdJurado1),
                    2=>$this->dbRepo->inDocenteRow($tram->IdJurado2),
                    3=>$this->dbRepo->inDocenteRow($tram->IdJurado3),
                    4=>$this->dbRepo->inDocenteRow($tram->IdJurado4),
                    ),
            ));
        }/**/else{
                echo "<br><br><center><h3> ¡Lo sentimos! <br> Usted no puede continuar con el proceso hasta completar los días mínimos de ejecucion de Tesis, aunque haya subido su Grado de Bachiller.</h3></center>";
                echo "<center><h4>Su proyecto tiene ".$dias." dia(s) de Ejecución de un total de 180 mínimos.</h4></center>";
                echo "<hr><div class='alert alert-warning'>
                <span class='glyphicon glyphicon-exclamation-sign'></span> <b>Importante</b><p>Antes de continuar con el proceso usted deberá: <br><b>(a)</b> Completar el tiempo mínimo <br><b>(b)</b> Poseer el grado académico de Bachiller<br><br> Si cumple con los requisitos <b>(a) y (b)</b> está apto para proseguir con su trámite, de lo contrario deberá esperar hasta cumplir lo estipulado.</p></div>";
            }
            /**/
        }

        if( $tram->Estado == 10 ) {
                $TipoMiembro = array (
        "Presidente",
        "Primer Miembro",
        "Segundo Miembro",
        "Asesor"
    );
                echo "<div class='panel panel-info'>";
                echo "<div class='panel-heading'>
        <h2 class='panel-title'> <b>Revisión de Composición de Jurado</b> </h2>
    </div>";
              echo "<div class='panel-body' id='plops'>";
                echo "<ul>";
                $count=0;
                $doc = array(
                    1=>$this->dbRepo->inDocenteRow($tram->IdJurado1),
                    2=>$this->dbRepo->inDocenteRow($tram->IdJurado2),
                    3=>$this->dbRepo->inDocenteRow($tram->IdJurado3),
                    4=>$this->dbRepo->inDocenteRow($tram->IdJurado4),
                );
                for ($i=1; $i <=4 ; $i++) { 
                    if($doc[$i]){
                        $status=($doc[$i]->Activo >= 5)?"(Docente Habilitado)":"(<b>OBSERVADO</b>Necesita Cambio)";
                        $kind=($doc[$i]->Activo >= 5)?"success":"danger";
                        echo "<li class='text-$kind'> $status |  ".$TipoMiembro[$i-1] ." | ".$doc[$i]->DatosPers."  </li>";
                    }
                }
                echo "</ul>";

            echo "Borrador Subido a PILAR, a la espera de la validación de Formato y Composición de Jurados, Cualquier duda o consulta comunicarse con la Comision de Grados y Titulos de su Facultad </div></div> </div>";
        }

        if( $tram->Estado == 11 || $tram->Estado == 12 || $tram->Estado == 13 ) {
            if($dets->vb1==2 && $dets->vb2==2 && $dets->vb3==2)
            {
                echo "<div class='panel panel-info'>";
                echo "<div class='panel-heading'>
                        <h2 class='panel-title'> <b>Borrador de Tesis</b> </h2>
                    </div>";
              echo "<div class='panel-body' id='plops'>";
              echo "Estimado(a) Tesista su borrador de Tesis ha sido aprobado, ahora se encuentra en proceso de Dictamen, la Comisión de Grados y Titulos de su Facultad tiene 7 dias calendarios para notificar a sus miembros de jurado para una Revision Presencial y/o virtual";
              echo "</div> </div>";

            } else{

            $iter=5;
            if($tram->Estado==12) //aumentado bet
            { 
                $iter=6; //echo "-------------- 2 da observaciones de jurados --------------------------";
            }
            if($tram->Estado==13) //aumentado bet
            { 
                $iter=7; //echo "-------------- 3 da observaciones de jurados --------------------------";
            }

            // $tram->IdJurado1
            // $tram->IdJurado2
            // $tram->IdJurado3
            // $tram->IdJurado4
            // echo "$tram->IdJurado1 :: $tram->IdJurado2 :: $tram->IdJurado3 :: $tram->IdJurado4 ";
            // if()
            // echo "sI YA CImprimir 4 ejemplares y llevar a la coordinación de Investigación"

			$this->load->view( "pilar/tes/proc/12_subcorr", array(
                    'tram'    => $tram,
					'detTram' => $dets,
					'arrCorr' => array(
							// enviamos un array organizado de correcciones borrador
							//
							1 => $this->dbPilar->inCorrecs( $tram->Id, 1, $iter ),
							2 => $this->dbPilar->inCorrecs( $tram->Id, 2, $iter ),
							3 => $this->dbPilar->inCorrecs( $tram->Id, 3, $iter ),
							4 => $this->dbPilar->inCorrecs( $tram->Id, 4, $iter )
				) ) );
			return;
            }
        }
        if($tram->Estado == 14)
        {
           $msg = "<br>Estimado(a) Tesista su borradorde Tesis ahora se encuentra en proceso de Dictamen, la Comisión de Grados y Titulos de su Facultad tiene 7 dias calendarios para notificar a sus miembros de jurado para una Revision Presencial y/o virtual." ;
           echo $msg;
        }
        if($tram->Estado == 15)
        {
           $iter=5;
            if($tram->Estado==12) //aumentado bet
            { 
                $iter=6; //echo "-------------- 2 da observaciones de jurados --------------------------";
            }
            if($tram->Estado==13) //aumentado bet
            { 
                $iter=7; //echo "-------------- 3 da observaciones de jurados --------------------------";
            }

            // $tram->IdJurado1
            // $tram->IdJurado2
            // $tram->IdJurado3
            // $tram->IdJurado4
            // echo "$tram->IdJurado1 :: $tram->IdJurado2 :: $tram->IdJurado3 :: $tram->IdJurado4 ";
            // if()
            // echo "sI YA CImprimir 4 ejemplares y llevar a la coordinación de Investigación"

            $this->load->view( "pilar/tes/proc/15_subcorr", array(
                    'tram'    => $tram,
                    'detTram' => $dets,
                    'arrCorr' => array(
                            // enviamos un array organizado de correcciones borrador
                            //
                            1 => $this->dbPilar->inCorrecs( $tram->Id, 1, $iter ),
                            2 => $this->dbPilar->inCorrecs( $tram->Id, 2, $iter ),
                            3 => $this->dbPilar->inCorrecs( $tram->Id, 3, $iter ),
                            4 => $this->dbPilar->inCorrecs( $tram->Id, 4, $iter )
                ) ) );
            return;
        }


        if( $tram->Estado >= 16 ) {
            $link = base_url( "repositor/docs/$dets->Archivo" );
            $link2 = base_url( "vriadds/vri/reglamentos/ReglamentoDefensaNP.pdf" );
            echo "<center><img class='img-responsive' style='height:70px;' src='".base_url('vriadds/pilar/imag/pilar-tes.png')."'/> </center>";
            echo "<center><h2 class='text'>Exposición y Defensa de Tesis</h2>";
            echo "<h4> Usted ha cargado su Borrador de Tesis Final, Debe esperar a su comision de Grados Y titulos para la validacion del documento.</h4></center> ";
            

        }



    }

    public function sorry()
    {
        echo "...";
    }


    public function loadRegBorr()
    {
        $this->gensession->IsLoggedAccess();
        $this->load->view( "pilar/tes/regBorr" );
    }


	public function actaProyIn()
	{
		$this->gensession->IsLoggedAccess();
		$sess = $this->gensession->GetData();
		$tram = $this->dbPilar->inTramByTesista( $sess->userId );
		$this->actaProy( $tram->Id );
	}

    public function constanciaSorteokkkkk( $idTram=0 )
    {
        // libre nada de sesiones
        if( !$idTram ) return;

        $tram = $this->dbPilar->inProyTram($idTram);
        if( !$tram ){ echo "Inexistente"; return;}
        if( $tram->Estado >=4 ){ 
            $dets = $this->dbPilar->inLastTramDet($idTram);
            $pdf = new GenSexPdf();

            //$pdf->AddPage();
            $pdf->AddPageEx( 'P', '', 2 );
            $pdf->SetMargins( 18, 40, 20 );

            $pdf->Ln( 25 );
            $pdf->SetFont( "Times", 'B', 15 );

            $pdf->Cell( 2,  9, "" );
            $pdf->Cell( 28, 9, $tram->Codigo, 1, 0, 'C' );
            $pdf->BarCode39( 150, 34, $tram->Codigo );
            mlQrRotulo( $pdf, 19, 220, $tram->Codigo );



            $pdf->Ln( 19 );
            $pdf->SetFont( "Arial", 'B', 14 );
            $pdf->Cell( 174, 5, toUTF("CONSTANCIA"), 0, 1, 'C' );


            $dia = (int) substr( $dets->Fecha, 8, 2 );
            $mes = mlNombreMes( substr($dets->Fecha,5,2) );
            $ano = (int) substr( $dets->Fecha, 0, 4 );
            $hor = substr( $dets->Fecha, 11, 8 );

            $jurado4 = $this->dbRepo->inDocenteEx( $tram->IdJurado4 );
            $tes = $this->dbPilar->inTesista($tram->IdTesista1, true);
            if( $tram->IdTesista2 != null ){
                $str = "Presentado por los Bachilleres:";
                $tes = $tes .",". $this->dbPilar->inTesista($tram->IdTesista2, true);
                // revisa modo de aprobacion
                $strConst = "La presente es la contancia que los tesistas: $tes respectivamente. Han iniciado de forma grupal el trámite electrónico "
                      . "para la presentación y revisión de su Proyecto de Tesis en la Plataforma de Investigación. Este proyecto ha sido aprobado por el Asesor de tesis  $jurado4 y se realizó la asignación de jurados correspondiente con fecha $tram->FechModif, el mismo que se encuentra en revisión.";
            }
            $strConst = "La presente es la contancia que: $tes  ha iniciado el trámite electrónico para la presentación "
                      . "y revisión de su Proyecto de Tesis en la Plataforma de Investigación. Este proyecto ha sido aprobado por el Asesor de tesis  $jurado4 y se realizó la asignación de jurados correspondiente  con fecha $tram->FechModif, el mismo que se encuentra en revisión."
                      . ""
                      ;
        
            $pdf->Ln(5);
            $pdf->SetFont( "Arial", "", 12 );
            $pdf->MultiCell( 174, 5.5, toUTF($strConst), 0, 'J' );

            $pdf->Ln(8);
            $pdf->SetFont( "Arial", "B", 11 );
            $pdf->MultiCell( 174, 5.5, toUTF("Puno, $mes de $ano"), 0, 'R' );

            $pdf->Image( 'vriadds/pilar/imag/aprofirma.jpg', 75, 230, 80 );

            $pdf->Output();
        }else{
            echo "No puede tener constancia";
        }
    }

    // acta borr
    public function actaBorr( $idTram=0 )
    {
        // libre nada de sesiones
        if( !$idTram ) return;

        $tram = $this->dbPilar->inProyTram($idTram);
        if( !$tram ){ echo "Inexistente"; return;}
        if( $tram->Estado < 6 ){ echo "No Aprobado"; return;}

		// Borr iteracion N :: la ultima.
		//
        $dets = $this->dbPilar->inLastTramDet($idTram);

        $pdf = new GenSexPdf();

        //$pdf->AddPage();
        $pdf->AddPageEx( 'P', '', 2 );
        $pdf->SetMargins( 18, 40, 20 );

        $pdf->Ln( 25 );
        $pdf->SetFont( "Times", 'B', 15 );

        $pdf->Cell( 2,  9, "" );
        $pdf->Cell( 28, 9, $tram->Codigo, 1, 0, 'C' );
        $pdf->BarCode39( 150, 34, $tram->Codigo );
        mlQrRotulo( $pdf, 19, 220, $tram->Codigo );



        $pdf->Ln( 19 );
        $pdf->SetFont( "Arial", 'B', 14 );
        //$pdf->Cell( 174, 5, toUTF("ACTA  DE BORRADOR DE TESIS (_DEVELOP_)"), 0, 1, 'C' );
        $pdf->Cell( 174, 5, toUTF("ACTA DE APROBACIÓN BORRADOR DE TESIS"), 0, 1, 'C' );


        $dia = (int) substr( $dets->Fecha, 8, 2 );
        $mes = mlNombreMes( substr($dets->Fecha,5,2) );
        $ano = (int) substr( $dets->Fecha, 0, 4 );
        $hor = substr( $dets->Fecha, 11, 8 );


        // revisa modo de aprobacion
        //
        $modo = (($dets->vb1+$dets->vb2+$dets->vb3)==3)?"UNANIMIDAD":"MAYORIA";
        if( ($dets->vb1 + $dets->vb2 + $dets->vb3) <= 1 )
            $modo = "REGLAMENTO";


        $str = "En la Ciudad Universitaria, a los $dia dias del mes $mes del $ano "
             . "siendo horas $hor. Se presentó el Borrador de tesis titulado:";

        $pdf->Ln( 7 );
        $pdf->SetFont( "Arial", '', 10 );
        $pdf->MultiCell( 174, 5, toUTF($str) );


        $pdf->Ln( 4 );
        $pdf->SetFont( "Arial", 'B', 10 );
        $pdf->MultiCell( 174, 6, toUTF($dets->Titulo), 1, 'C' );

        $str = "Presentado por el(la) Bachiller:";
        $tes = $this->dbPilar->inTesista($tram->IdTesista1, true);
        if( $tram->IdTesista2 ){
            $str = "Presentado por los Bachilleres:";
            $tes = $tes ."\n". $this->dbPilar->inTesista($tram->IdTesista2, true);
        }

        $pdf->Ln( 4 );
        $pdf->SetFont( "Arial", '', 10 );
        $pdf->MultiCell( 174, 5, toUTF($str) );

        $pdf->Ln( 4 );
        $pdf->SetFont( "Arial", 'B', 10 );
        $pdf->MultiCell( 174, 6, toUTF($tes), 1, 'C' );

		// carrera
        $pdf->Ln(4);
        $pdf->SetFont( "Arial", "", 10 );
        $pdf->MultiCell( 174, 5, toUTF("De la Escuela Profesional de:"), 0, 'L' );

		$Carrera = $this->dbRepo->inCarrera($tram->IdCarrera);

        $pdf->Ln(4);
        $pdf->SetFont( "Arial", "B", 10 );
        $pdf->MultiCell( 174, 5, toUTF($Carrera), 1, 'C' );


        $pdf->Ln( 4 );
        $pdf->SetFont( "Arial", '', 10 );
        $pdf->MultiCell( 174, 5, toUTF("Siendo el Jurado Dictaminador, conformado por:") );


        $jurado1 = $this->dbRepo->inDocenteEx( $tram->IdJurado1 );
        $jurado2 = $this->dbRepo->inDocenteEx( $tram->IdJurado2 );
        $jurado3 = $this->dbRepo->inDocenteEx( $tram->IdJurado3 );
        $jurado4 = $this->dbRepo->inDocenteEx( $tram->IdJurado4 );

        $pdf->Ln(4);
        $pdf->Cell( 50, 6, "Presidente", 0, 0, "L" );
        $pdf->Cell( 100, 6, ": " .toUTF($jurado1), 0, 1, "L" );

        $pdf->Cell( 50, 6, "Primer Miembro", 0, 0, "L" );
        $pdf->Cell( 100, 6, ": " .toUTF($jurado2), 0, 1, "L" );

        $pdf->Cell( 50, 6, "Segundo Miembro", 0, 0, "L" );
        $pdf->Cell( 100, 6, ": " .toUTF($jurado3), 0, 1, "L" );

        $pdf->Cell( 50, 6, "Asesor", 0, 0, "L" );
        $pdf->Cell( 100, 6, ": " .toUTF($jurado4), 0, 1, "L" );


        $strBloq = "Para dar fe de este proceso electrónico, el Vicerrectorado de Investigación de la Universidad "
                 . "Nacional del Altiplano - PUCALLPA, mediante la Plataforma de Investigación se le asigna la presente "
                 . "constancia y a partir de la presente fecha queda expedito para la ejecución de su PROYECTO DE INVESTIGACIÓN DE TESIS.";

        $pdf->Ln(5);
        $pdf->SetFont( "Arial", "", 10 );
        $pdf->MultiCell( 174, 5.5, toUTF($strBloq), 0, 'J' );

        $pdf->Ln(8);
        $pdf->SetFont( "Arial", "B", 11 );
        $pdf->MultiCell( 174, 5.5, toUTF("Puno, $mes de $ano"), 0, 'R' );

        $pdf->Image( 'vriadds/pilar/imag/aprofirma.jpg', 75, 230, 80 );

        $pdf->Output();
    }


    // ver acta proy
    //modificacion unuv1.0 - estado aprobacion de proyecto
    public function actaProy( $idTram=0 )
    {
        // libre nada de sesiones
        if( !$idTram ) return;

        $tram = $this->dbPilar->inProyTram($idTram);
        if( !$tram ){ echo "Inexistente"; return;}
        if( $tram->Estado < 8 ){ echo "No Aprobado"; return;}

        //Agregado unuv1.0 
        $Carrera = $this->dbRepo->inCarrera($tram->IdCarrera);
        $facultad = $this->dbRepo->inFacultad($tram->IdCarrera);
        $idFac = $this->dbRepo->inIdFacultad($tram->IdCarrera);
		$dets = $this->dbPilar->inLastTramDet($idTram);
        //fin

		// ACTA iteracion 3 :: no la ultima.
		//

        // ni se te ocurra cambiarlo, por la fecha en la iteracion 3

        $pdf = new GenSexPdf();

        //$pdf->AddPage();
        $pdf->AddPageEx2( 'P','A4',1,1, $facultad , $Carrera, $idFac);
       // $pdf->AddPageEx( 'P', '', 2 );
        $pdf->SetMargins( 18, 40, 20 );

        $pdf->Ln( -5 );
        $pdf->SetFont( "Times", 'B', 16 );

        $pdf->Cell( 2,  0, "" );
        $pdf->Cell( 28, 10, $tram->Codigo, 1, 0, 'C' );
        //$pdf->BarCode39( 150, 40, $tram->Codigo );
       // mlQrRotulo( $pdf, 19, 235, $tram->Codigo);



        $pdf->Ln( 19 );
        $pdf->SetFont( "Arial", 'B', 14 );
        $pdf->Cell( 174, 5, toUTF("ACTA DE APROBACION DE PROYECTO DE TESIS"), 0, 1, 'C' );


        $dia = (int) substr( $tram->FechActProy, 8, 2 );
        $mes = mlNombreMes( substr($tram->FechActProy,5,2) );
        $ano = (int) substr( $tram->FechActProy, 0, 4 );
        $hor = substr( $tram->FechActProy, 11, 8 );


        // revisa modo de aprobacion
        //
        //$modo = (($dets->vb1+$dets->vb2+$dets->vb3)==3)?"UNANIMIDAD":"MAYORIA";
        $modo="UNANIMIDAD";
        if( ($dets->vb1 + $dets->vb2 + $dets->vb3) <= 1 )
            $modo = "REGLAMENTO";


        $str = "En la Ciudad Universitaria, a los $dia dias del mes $mes del $ano "
             . "siendo horas $hor. Los miembros del Jurado, declaran APROBADO POR $modo "
             . "el PROYECTO DE INVESTIGACIÓN DE TESIS titulado:";

        $pdf->Ln( 7 );
        $pdf->SetFont( "Arial", '', 10 );
        $pdf->MultiCell( 174, 5, toUTF($str) );


        $pdf->Ln( 4 );
        $pdf->SetFont( "Arial", 'B', 10 );
        $pdf->MultiCell( 174, 6, toUTF($dets->Titulo), 1, 'C' );

        $str = "Presentado por el(la) Bachiller:";
        $tes = $this->dbPilar->inTesista($tram->IdTesista1, true);
        if( $tram->IdTesista2 ){
            $str = "Presentado por los Bachilleres:";
            $tes = $tes ."\n". $this->dbPilar->inTesista($tram->IdTesista2, true);
        }

        $pdf->Ln( 4 );
        $pdf->SetFont( "Arial", '', 10 );
        $pdf->MultiCell( 174, 5, toUTF($str) );

        $pdf->Ln( 4 );
        $pdf->SetFont( "Arial", 'B', 10 );
        $pdf->MultiCell( 174, 6, toUTF($tes), 1, 'C' );

		// carrera
        $pdf->Ln(4);
        $pdf->SetFont( "Arial", "", 10 );
        $pdf->MultiCell( 174, 5, toUTF("De la Escuela Profesional de:"), 0, 'L' );

		$Carrera = $this->dbRepo->inCarrera($tram->IdCarrera);

        $pdf->Ln(4);
        $pdf->SetFont( "Arial", "B", 10 );
        $pdf->MultiCell( 174, 5, toUTF($Carrera), 1, 'C' );


        $pdf->Ln( 4 );
        $pdf->SetFont( "Arial", '', 10 );
        $pdf->MultiCell( 174, 5, toUTF("Siendo el Jurado Dictaminador, conformado por:") );


        $jurado1 = $this->dbRepo->inDocenteEx( $tram->IdJurado1 );
        $jurado2 = $this->dbRepo->inDocenteEx( $tram->IdJurado2 );
        $jurado3 = $this->dbRepo->inDocenteEx( $tram->IdJurado3 );
        $jurado4 = $this->dbRepo->inDocenteEx( $tram->IdJurado4 );

        $pdf->Ln(4);
        $pdf->Cell( 50, 6, "Presidente", 0, 0, "L" );
        $pdf->Cell( 100, 6, ": " .toUTF($jurado1), 0, 1, "L" );

        $pdf->Cell( 50, 6, "Primer Miembro", 0, 0, "L" );
        $pdf->Cell( 100, 6, ": " .toUTF($jurado2), 0, 1, "L" );

        $pdf->Cell( 50, 6, "Segundo Miembro", 0, 0, "L" );
        $pdf->Cell( 100, 6, ": " .toUTF($jurado3), 0, 1, "L" );

        $pdf->Cell( 50, 6, "Asesor", 0, 0, "L" );
        $pdf->Cell( 100, 6, ": " .toUTF($jurado4), 0, 1, "L" );


        $strBloq = "Para dar fe de este proceso electrónico, el Vicerrectorado de Investigación de la Universidad "
        . "Nacional de Ucayali - Pucallpa, mediante la Plataforma de Investigación se le asigna la presente "
        . "acta y a partir de la presente fecha queda expedito para la ejecución de su PROYECTO DE INVESTIGACIÓN DE TESIS.";

        $pdf->Ln(5);
        $pdf->SetFont( "Arial", "", 10 );
        $pdf->MultiCell( 174, 5.5, toUTF($strBloq), 0, 'J' );

        $pdf->Ln(8);
        $pdf->SetFont( "Arial", "B", 11 );
        $pdf->MultiCell( 174, 5.5, toUTF("Pucallpa, $mes de $ano"), 0, 'R' );
        $pdf->Image( 'vriadds/pilar/imag/Firmas/'.$idFac.'.jpg', 75, 230, 80 );


        
        //$pdf->Image( 'vriadds/pilar/imag/aprofirma.jpg', 75, 230, 80 ); comentado unuv1.0 -aprobacion de proyecto

        $pdf->Output();
    }
    

    //agregado subida de coti- unuv2.0
    public function SubirCoti(){
        $this->gensession->IsLoggedAccess();

        $sess  = $this->gensession->GetData();
        $archi = $this->subirArchevo( 6,'cotiarch' );
        if( ! $archi ) return;
        echo $archi;
    }

    //agregado subida de anexo- unuv2.0
    public function SubirAnexos(){
        $archi = $this->subirArchevo( 7,'anexarch' );
        if( ! $archi ) return;
        echo $archi;
    }

    // get aware with reescribe data...
    //Subir borrador de tesis
     public function execInBorr()
    {
        $this->gensession->IsLoggedAccess();

        $sess  = $this->gensession->GetData();


        // si falla al subir Borrador termina
        $archi = $this->subirArchevo( 2 );
        if( ! $archi ) return;


        $nomcoti = mlSecurePost( "nomcoti" );  //siempre es NULL arriba lo asignaremos
        $nomanexo = mlSecurePost( "nomanexo" ); 
        $resum = mlSecurePost( "resumen" );
        $clave = mlSecurePost( "pclaves" );
        $concl = mlSecurePost( "conclus" );
        $titul = mb_strtoupper( mlSecurePost( "nomproy" ) );



        // 1. verificar previo
        // 2. insertar Tramite
        // 3. insertar detTramite
        // 4. insertar TramiteDoc  mining
        // 5. log de tramites
        // 6. enviar correo...
        // 7. log de correos

        $tram = $this->dbPilar->inTramByTesista($sess->userId);

        // check if we had a prevoius activation
        if( $tram->Tipo == 2 && $tram->Estado >= 12 ) return;

        ///echo  "t:$titul  r:$resum   k:$clave  c:$concl <br>";
        ///return;


        $this->dbPilar->Update( 'tesTramites', array(
                'Tipo'   => 2,
                'Estado' => 10,
                'FechModif' => mlCurrentDate()
            ), $tram->Id );


        $this->dbPilar->Insert( 'tesTramsDet', array(
            'Iteracion' => 5,
            'IdTramite' => $tram->Id,
            'Archivo'   => $archi,
            'Coti'   => $nomcoti,
            'Anexo'   => $nomanexo,
            'Titulo'    => $titul,
            'Fecha'     => mlCurrentDate()
        ));

        // para mineria de datos
        $this->dbPilar->Insert( 'tesTramDoc', array(
            'Tipo'      => 2,
            'IdTramite' => $tram->Id,
            'Title'     => $titul,
            'Abstract'  => $resum,
            'Conclus'   => $concl,
            'Keywords'  => $clave
        ));

        $msg = "Estimado(a) Tesista se ha subido su borrador de tesis en el codigo de tramite <b>$tram->Codigo</b>.<br>" 
            . " Recuerde que la Comision de Grados y Titulos de su Facultad tiene un plazo de 7 dias calendarios para revisar el formato de borrados de tesis y otros criterios que ellos consideren."
            ."<br><br><b>Nota: </b> Cualquier duda o consulta debe comunicarse con su Comision de Grados y Titulos de su Facultad";



        $msgcel =" UNU -PILAR \nEstimado(a) Tesista Ud. se ha subido su borrador de tesis en el codigo de tramite <b>$tram->Codigo</b>,<br>" 
            . " Recuerde que la comision de grados y titulos de su facultad tiene un plazo de 7 dias calendarios para revisar el formato de borrados de tesis y otros criterios que ellos consideren.";
        
         $this->logTramites( $sess->userId, $tram->Id, "Subida de Borrador", $msg );

         $mail = $this->dbPilar->inCorreo( $tram->IdTesista1);
        $this->logCorreo( $tram->IdTesista1, $mail, "Subida de Borrador", $msg );   

        if($tram->IdTesista2!=0)
        {
            $mail = $this->dbPilar->inCorreo( $tram->IdTesista2);
            $this->logCorreo( $tram->IdTesista2, $mail, "Subida de Borrador", $msg );
        }
       


        
       /* $msg = "<br>Se ha actualizado el trámite: <b>$tram->Codigo</b><br><br> "
             . "Título de Borrador de Tesis: <b>$titul</b> <br><br>       "
             . "Ud. debe apersonarse a Plataforma para revisar el formato "
             . "y la conformación de su <b>Jurado Evaluador</b> "
             . "de lo contrario no se procede con el envio para "
             . "que el tramite de su borrador continue."  ;


        // agregar tramite
        /*$this->logTramites( $sess->userId, $tram->Id, "Subida de Borrador", $msg );

        // grabar y enviar en LOG de correos.
        $this->logCorreo( $tram->Id, $sess->userMail, "Subida de Borrador", $msg );

        // finalmente
        echo $msg . "<br><b>hecho !</b>";*/
        echo $msg;
    }


    //
    // real carga proyecto de tesis
    //// modificado unuv1 --(3.8.2)
    //// modificado unuv1 --(3.9.3)
    public function loadRegProy( $extCod=0 )
    {
        $this->gensession->IsLoggedAccess();
        $sess = $this->gensession->GetData();

        // buscamos los records de cada uno
        $tes1 = $this->dbPilar->inTesistByCod( $sess->userCod );
        $tes2 = $this->dbPilar->inTesistByCod( secureString($extCod) );

        // antes era:
        // SELECT abc
        //   union
        // SELECT bcd


        $errorMsg = null;

        if( $extCod && !$tes2 )
            { $errorMsg = "el $extCod no esta registrado aún"; }

        if( $tes2 ) if( $tes1->IdCarrera != $tes2->IdCarrera )
            { $errorMsg = "No son de la misma carrera"; $tes2 = null; }

        if( $sess->userCod == $extCod )
            { $errorMsg = "No debe repetir su Código"; $tes2 = null;  }


		// hack para E. Inicial
		$carre = $sess->IdCarrera;
		/*if( $sess->IdCarrera == 19 )
			$carre = 18; Modificado unuv1.0*/

        $args = array(
            // 'tlineas' => $this->dbPilar->getSnapView( 'vxLineas', "IdCarrera=$carre" ),
                'tlineas' => $this->dbRepo->getTable("tblLineas","IdCarrera='$carre' AND Estado = '1'"),
                'tbltes' => (!$tes2)?array($tes1):array($tes1,$tes2),
                'errmsg' => $errorMsg,
            );

        // no hiddens : sessVars sí
        mlSetGlobalVar( 'datTest', array(
                'user1' => ($tes1)? $tes1->Id : 0,
                'user2' => ($tes2)? $tes2->Id : 0,
                'carre' => $tes1->IdCarrera
            ) );

        $this->load->view( "pilar/tes/regProy", $args );
    }

    //modificado unuv1 --(3.8.7)
    // procedimiento externo Upload
   public function subirArchevo( $tipo=1,$nom='nomarch' ,$kind="pdf" )
    {
        $sess = $this->gensession->GetData();

        if( $kind=="pdf" ){
            $config['upload_path']   = './repositor/docs/';
        } else {
            $config['upload_path']   = './repositor/foto/';
        }

        // generamos el nombre Aleatorio: 5 Caracteres - Aleatorizados + 3 DNI
        //$str = mlRandomStr(12);

        $config['allowed_types'] = 'jpg|png|pdf';  // ext
        $config['max_size']      = '204800';         // KB
        $config['overwrite']     = TRUE;

        $config['file_name']     = sprintf("d%08s-Proy.pdf", $sess->userId );
        //$config['max_width']  = '2024';
        //$config['max_height'] = '2008';


        if( $tipo == 2 )
            $config['file_name']     = sprintf("d%08s-Borr.pdf", $sess->userId );
        // Carga de Bachiller como requisito.
        if( $tipo == 3 ){
            $config['upload_path']   = './repositor/bach/';
            $config['file_name']     = sprintf("d%08s-Bach.pdf", $sess->userId );
        }
        // Carga Correcciones de Borrador
        if( $tipo == 4 )
            $config['file_name']     = sprintf("d%08s-Final.pdf", $sess->userId );

        // Carga Correcciones de Borrador
        if( $tipo == 5 )
            $config['file_name']     = sprintf("d%08s-Diapo.pdf", $sess->userId );
        
        if( $tipo == 6 ){//agregado unuv2.0
            $config['upload_path']   = './repositor/coti/';
            $config['file_name']     = sprintf("d%08s-Coti.pdf", $sess->userId );
        }
        if( $tipo == 7 ){//agregado unuv2.0
            $config['file_name']     = sprintf("d%08s-Anexos.pdf", $sess->userId );
        }
        // finalmente subir archivo
        $this->load->library('upload', $config);
        if ( !$this->upload->do_upload($nom) ) { // input field

            $data['uploadError'] = $this->upload->display_errors();
            echo "Error: " . $this->upload->display_errors();
            return null;

        } else {
            $file_info = $this->upload->data();
            //echo "Archivo Subido Exitoso !! <br>";
        }

        // devolvemos el nombre del archivo
        return  $config['file_name'];
    }

     
    //modificado unuv1 --(3.8.6)
    //modificado unuv1 --(3.9.5)
    public function execInProy()
    {
        $this->gensession->IsLoggedAccess();


        //
        // AÑO LECTIVO
        //
        $anio =date("Y");


        $sess  = $this->gensession->GetData();
        $users = mlGetGlobalVar( 'datTest' );


        // si falla al subir termina
        $archi = $this->subirArchevo( 1 );
        if( ! $archi ) return;


        // buscamos ultimo registro de tramite del año y procedemos
        $orden = $this->dbPilar->getOneField( "tesTramites", "Orden", "Anio=$anio ORDER BY Orden DESC" );
        $codigo = sprintf("%04d-%03d", $anio, $orden + 1 );


        $tesi1 = $users['user1'];
        $tesi2 = $users['user2'];
        $carre = $users['carre'];

        $linea = mlSecurePost( "cbolin" );
        $jura4 = mlSecurePost( "jurado4" );
        //$jura3 = mlSecurePost( "jurado3" );
        //$archi = mlSecurePost( "nomarch" );  siempre es NULL arriba lo asignaremos
        $resum = mlSecurePost( "resumen" );
        $clave = mlSecurePost( "pclaves" );
        $titul = mb_strtoupper( mlSecurePost( "nomproy" ) );


        // 1. verificar previo
        // 2. insertar Tramite
        // 3. insertar detTramite
        // 4. insertar TramiteDoc  mining
        // 5. log de tramites
        // 6. enviar correo...
        // 7. log de correos


        // el control de 0 el model devuelve null
      /*  if( $rwtes = $this->dbPilar->inTramByTesista($tesi1) )
        {
            echo "Error : El primer tesista, ya integra el trámite: <b>$rwtes->Codigo</b>";
            return;
        }

        // el control de 0 el model devuelve null
        if( $rwtes = $this->dbPilar->inTramByTesista($tesi2) )
        {
            echo "Error : El segundo tesista, ya integra el trámite: <b>$rwtes->Codigo</b>";
            return;
        }  Modificado unuv1.0 */


        // guardar trámite
        $idTram = $this->dbPilar->Insert( 'tesTramites', array(
            'Tipo'       => 1,        // Proys
            'Estado'     => 1,        // Inciamos
            'Anio'       => $anio,    // lectivo
            'Orden'      => $orden+1,
            'Codigo'     => $codigo,
            'IdCarrera'  => $carre,
            'IdTesista1' => $tesi1,
            'IdTesista2' => $tesi2,
            'IdLinea'    => $linea,
            'IdLinAlte'  => 0,
            //'IdJurado3'  => $jura3,
            'IdJurado4'  => $jura4,
            'FechRegProy' => mlCurrentDate(),
            'FechModif'   => mlCurrentDate()
        ));

        //$idTram = mysql_insert_id();
        $this->dbPilar->Insert( 'tesTramsDet', array(
            'Iteracion' => 1,
            'IdTramite' => $idTram,
            'Archivo'   => $archi,
            'Titulo'    => $titul,
            'Fecha'     => mlCurrentDate()
        ));

        // para mineria de datos proy
        $this->dbPilar->Insert( 'tesTramDoc', array(
            'Tipo'      => 1,
            'IdTramite' => $idTram,
            'Title'     => $titul,
            'Abstract'  => "+", //$resum
            'Conclus'   => "",
            'Keywords'  => "+" //$clave
        ));


        //
        // $mail = $this->dbPilar->getOneField( 'tblTesistas', 'Correo', "Id=$tesi1" );
        //
        $msg = "<br>Se ha registrado el proyecto: <b>$codigo</b><br><br> "
             . "Título de Proyecto: <b>$titul</b> <br><br>"
             . "La comisión de GyT de su facultad tendra 2 dias calendarios para revisar el formato y otros criterios, antes de enviar su proyecto a su Asesor.";


        // agregar tramite
        $this->logTramites( $tesi1, $idTram, "Subida de Proyecto", $msg );

        // grabar y enviamos mail en LOG
        //$this->logCorreo( $idTram, $sess->userMail, "Subida de Proyecto", $msg ); //Modificado unuv1.0
        //------------------Correo a los tesistas -------------
        if($tesi2 != 0)
          {
            $mail = $this->dbPilar->inCorreo( $tesi1);
            $mail2 = $this->dbPilar->inCorreo( $tesi2);
            $cel= $this->dbPilar->inCelTesista( $tesi1);
            $cel2= $this->dbPilar->inCelTesista( $tesi2);
            $this->logCorreo( $tesi1, $mail, "Subida de Proyecto", $msg );
            $this->logCorreo( $tesi2, $mail2, "Subida de Proyecto", $msg );
        //    $this->notiCelu($cel,2);
        //    $this->notiCelu($cel2,2);
          }
        else
          {
            $cel= $this->dbPilar->inCelTesista( $tesi1);
             $mail = $this->dbPilar->inCorreo( $tesi1);
            $this->logCorreo( $tesi1, $mail, "Subida de Proyecto", $msg );
        //    $this->notiCelu($cel,2);
          }

         $msg = "Se ha registrado el proyecto: <b>$codigo</b><br> "
             . "Título de Proyecto: <b>$titul</b> <br>"
             . "Estado : La comisión de GyT de su facultad tendra 2 dias calendarios para revisar el formato y otros criterios, antes de enviar su proyecto a su Asesor.";
        //---------------------FIN----------------------------       

        // finalmente
        echo $msg ;
    }


	// correcciones Proyecto
    //unuv1.0 - estado revision 1
    //unuv1.0 - estado revision 2
       //unuv1.0 - estado revision 3
          //unuv1.0 - estado dictaminacion
    public function execInCorr()
    {
        $this->gensession->IsLoggedAccess();

        $sess  = $this->gensession->GetData();


        // si falla al subir Borrador termina
        $archi = $this->subirArchevo( 1 );
        if( ! $archi ) return;


        //$archi = mlSecurePost( "nomarch" );  siempre es NULL arriba lo asignaremos
        //$resum = mlSecurePost( "resumen" ); //Modificado Oliver
        //$clave = mlSecurePost( "pclaves" ); //Modificado Oliver
        //$concl = mlSecurePost( "conclus" );


        $titul = mb_strtoupper( mlSecurePost( "nomproy" ) );



        // 1. verificar previo
        // 2. insertar Tramite
        // 3. insertar detTramite
        // 4. insertar TramiteDoc  mining
        // 5. log de tramites
        // 6. enviar correo...
        // 7. log de correos

        $tram = $this->dbPilar->inTramByTesista($sess->userId);
        $msg = "<br>El tesista ha subido el proyecto corregido en el trámite:<br><br>"
             . "Codigo: <b>$tram->Codigo</b><br> "
             . "Título de Proyecto : <b>$titul</b> <br><br>  "
             . "A partir de la fecha tiene un plazo de 15 días calendarios "
             . " para realizar las correciones y/o observalas."  ;

        $msgtesista = "<br>Ud. ha subido el proyecto corregido en el trámite:<br><br>"
             . "Codigo: <b>$tram->Codigo</b><br> "
             . "Título de Proyecto : <b>$titul</b> <br><br>  "
             . "A partir de la fecha tiene un plazo de 15 días calendarios "
             . " para la verifacion de su proyecto."  ;


        // check if we had a prevoius activation
        if( $tram->Tipo == 1 && $tram->Estado >= 7 ) return;
        $estado=5;
        $iteracion=2;
        $titulomen='Subida de Correcion';
        
        if($tram->Estado==5){ //agregado unuv1.0 - estado revision 2
            $estado=6;
            $iteracion=3;

        }
        if($tram->Estado==6){   //subida de dictamen
            $estado=7;
            $iteracion=4;
             $titulomen='Dictaminación de Proyecto';
             $msg = "<br>El tesista ha subido el proyecto corregido en el trámite:<br><br>"
                . "Codigo: <b>$tram->Codigo</b><br> "
                . "Título de Proyecto : <b>$titul</b> <br><br>  "
                . "A partir de la fecha tiene un plazo de 15 días calendarios "
                . " para realizar la <b>Dictaminación del Proyecto."  ;

            $msgtesista = "<br>Ud. ha subido el proyecto corregido en el trámite:<br><br>"
                . "Codigo: <b>$tram->Codigo</b><br> "
                . "Título de Proyecto : <b>$titul</b> <br><br>  "
                . "A partir de la fecha tiene un plazo de 15 días calendarios "
                . " se realizará la <b>Dictaminación del Jurado Evaluador."  ;

        }
        $this->dbPilar->Update( 'tesTramites', array(
                'Estado'    => $estado,
                'FechModif' => mlCurrentDate()
            ), $tram->Id );


        $this->dbPilar->Insert( 'tesTramsDet', array(
            'Iteracion' => $iteracion,
            'IdTramite' => $tram->Id,
            'Archivo'   => $archi,
            'Titulo'    => $titul,
            'Fecha'     => mlCurrentDate()
        ));

        // para mineria de datos
        $this->dbPilar->Insert( 'tesTramDoc', array(
            'Tipo'      => 1,
            'IdTramite' => $tram->Id,
            'Title'     => $titul,
           // 'Abstract'  => $resum,
            'Conclus'   => "*",
            //'Keywords'  => $clave
        ));



        if($tram->Estado==4)//agregado unuv1.0
        {
            $pos1=0;$pos2=0;$pos3=0;
            $det = $this->dbPilar->inTramDetIter($tram->Id, 1);
            if($det->vb1==2 ){$pos1=2;}
            if($det->vb2==2 ){ $pos2=2;}
            if($det->vb3==2 ){ $pos3=2;}

            $dets = $this->dbPilar->inTramDetIter( $tram->Id,2);
            $this->dbPilar->Update( "tesTramsDet", array(
                            'vb1'    => $pos1,
                            'vb2'    => $pos2,
                            'vb3'    => $pos3
                        ), $dets->Id);
        }
        if($tram->Estado==5)
        { //cuando el docente ya hara realizo aprobacion, asi va seguir el docente
            $pos1=0;$pos2=0;$pos3=0;
            $det = $this->dbPilar->inTramDetIter($tram->Id, 2);
            if($det->vb1==2 ){$pos1=2;}
            if($det->vb2==2 ){ $pos2=2;}
            if($det->vb3==2 ){ $pos3=2;}

            $dets = $this->dbPilar->inTramDetIter( $tram->Id,3);
            $this->dbPilar->Update( "tesTramsDet", array(
                            'vb1'    => $pos1,
                            'vb2'    => $pos2,
                            'vb3'    => $pos3
                        ), $dets->Id);
        }

         if($tram->Estado==6)
        { //cuando el docente ya realizo aprobacion asi va seguir
            $pos1=0;$pos2=0;$pos3=0;
            $det = $this->dbPilar->inTramDetIter($tram->Id, 3);
            if($det->vb1==2 ){$pos1=2;}
            if($det->vb2==2 ){ $pos2=2;}
            if($det->vb3==2 ){ $pos3=2;}

            $dets = $this->dbPilar->inTramDetIter( $tram->Id,4);
            $this->dbPilar->Update( "tesTramsDet", array(
                            'vb1'    => $pos1,
                            'vb2'    => $pos2,
                            'vb3'    => $pos3
                        ), $dets->Id);
        }


       /* $msg = "<br>El tesista ha subido Correcciones en el trámite:<br><br>"
             . "Codigo: <b>$tram->Codigo</b><br> "
             . "Título de Proyecto : <b>$titul</b> <br><br>  "
             . "A partir de la fecha en un plazo de 5 días hábiles (sin feriados) "
             . "se realizará la <b>Dictaminación del Jurado Evaluador</b>. "
             . "Se procede con el registro y envio de las notificaciones."  ; //comenatdo unuv1.0*/
        

        // agregar tramite
        $this->logTramites( $sess->userId, $tram->Id, "Subida de Corrección", $msg );

        // grabar y enviamos mail en LOG correos
       // $this->logCorreo( $tram->Id, $sess->userMail, "Subida de Corrección", $msg ); comentado unuv1.0

        //------------------Correo a los tesistas -------------
        if($tram->IdTesista2 != 0)
          {
            $mail = $this->dbPilar->inCorreo( $tram->IdTesista1);
            $mail2 = $this->dbPilar->inCorreo( $tram->IdTesista2);
            $this->logCorreo( $tram->IdTesista1, $mail, $titulomen, $msgtesista );
            $this->logCorreo( $tram->IdTesista2,$mail2, $titulomen, $msgtesista );
          }
        else
          {
            $mail = $this->dbPilar->inCorreo($tram->IdTesista1);
            $this->logCorreo($tram->IdTesista1, $mail, $titulomen, $msgtesista );
          }
        //---------------------FIN----------------------------       

		// enviar correos a profesores OJO
		/// $this->correoProfes($tram);        
		$corr1 = $this->dbRepo->inCorreo( $tram->IdJurado1 );
		$corr2 = $this->dbRepo->inCorreo( $tram->IdJurado2 );
		$corr3 = $this->dbRepo->inCorreo( $tram->IdJurado3 );
		$corr4 = $this->dbRepo->inCorreo( $tram->IdJurado4 );

		if($pos1!=2){
            $this->logCorreo( $tram->Id, $corr1, $titulomen, $msg );
        }
        
        if($pos2!=2){
            $this->logCorreo( $tram->Id, $corr2, $titulomen, $msg );
        }

        if($pos3!=2){
            $this->logCorreo( $tram->Id, $corr3, $titulomen, $msg );
        }        
        
      /*  $this->logCorreo( $tram->Id, $corr1, $titulomen, $msg );
		$this->logCorreo( $tram->Id, $corr2, $titulomen, $msg );
		$this->logCorreo( $tram->Id, $corr3, $titulomen, $msg );
		$this->logCorreo( $tram->Id, $corr4, $titulomen, $msg );*/

        // finalmente
        echo $msg . "<br><b>hecho !</b>";
    }

    // Correcciones Borrador
    //Subida de borrador de tesis 1
    public function execInCorrBorr()
    {
        $this->gensession->IsLoggedAccess();

        $sess  = $this->gensession->GetData();
        $tram = $this->dbPilar->inTramByTesista($sess->userId);
        $dets = $this->dbPilar->inLastTramDet( $tram->Id );

        $archi='';
        // si falla al subir Borrador termina
        if($tram->Estado==15)
        {
            $archi = $this->subirArchevo( 4 );
        }else
        {
            $archi = $this->subirArchevo( 2 );
        }
        if( ! $archi ) return;

        //$archi = mlSecurePost( "nomarch" );  siempre es NULL arriba lo asignaremos
        $resum = mlSecurePost( "resumen" ); 
        $clave = mlSecurePost( "pclaves" ); 
        $concl = mlSecurePost( "conclus" ); 
        $titul = mb_strtoupper( mlSecurePost( "nomproy" ) );

        // 1. verificar previo
        // 2. insertar Tramite
        // 3. insertar detTramite
        // 4. insertar TramiteDoc  mining
        // 5. log de tramites
        // 6. enviar correo...
        // 7. log de correos
        $pos1 =$pos2=$pos3=0;
        // $det = $this->dbPilar->inTramDetIter($tram->Id, 1);
            if($dets->vb1==2 ){$pos1=2;}
            if($dets->vb2==2 ){ $pos2=2;}
            if($dets->vb3==2 ){ $pos3=2;}
       


        // check if we had a prevoius activation
        if( $tram->Tipo != 2 && $tram->Estado >= 14 ) return;//ver aquii
        $estado='12';
        $iteracion=6;

        if($tram->Estado==12){
            $estado='13';
            $iteracion=7;
        }
        if($tram->Estado==13){
            $estado='14';
            $iteracion=8;
        }
        if($tram->Estado==15){
            $estado='16';
             $iteracion=9;
        }


        $this->dbPilar->Update( 'tesTramites', array(
                'Estado'    => $estado,
                'FechModif' => mlCurrentDate()
            ), $tram->Id );


        $this->dbPilar->Insert( 'tesTramsDet', array(
            'Iteracion' => $iteracion,
            'IdTramite' => $tram->Id,
            'Archivo'   => $archi,
            'Coti'      => $dets->Coti,
            'Anexo'      => $dets->Anexo,
            'Titulo'    => $titul,
           'Fecha'     => mlCurrentDate()
         ));

        // para mineria de datos
        $this->dbPilar->Insert( 'tesTramDoc', array(
            'Tipo'      => 2,
            'IdTramite' => $tram->Id,
            'Title'     => $titul,
            'Abstract'  => $resum,
            'Conclus'   => $concl,
            'Keywords'  => $clave
        ));


       $dets = $this->dbPilar->inLastTramDet( $tram->Id );
            $this->dbPilar->Update( "tesTramsDet", array(
                            'vb1'    => $pos1,
                            'vb2'    => $pos2,
                            'vb3'    => $pos3
                        ), $dets->Id);


        $estado='14';
        
        if($tram->Estado<13)
        {

                   //--------notificaciones Tesista ------------
         $msg = "<br>Estimado(a) Tesita se ha subido su Borrador de Tesis corregido en el trámite:<br><br>"
             . "Codigo: <b>$tram->Codigo</b><br> "
             . "Título de Proyecto : <b>$titul</b> <br><br>  "
             . "A partir de la fecha los miembros de sus jurados tiene un plazo de 15 días calendarios "
             . " para que verifiquen las correciones de su Borrador de Tesis."  ;      

         $msgcel =" UNU -PILAR \nEstimado(a) Tesista se ha subido su borrador de tesis corregido en el codigo de tramite <b>$tram->Codigo</b>,<br>" 
            . " A partir de la fecha los miembros de sus jurados tiene un plazo de 15 días calendarios para que verifiquen las correciones de su Borrador de Tesis"
            ."\n".date("d-m-Y")." \nVRI - Plataforma PILAR UNU " 
            ."\n*Este es un mensaje automático, no responda por favor.";

        $this->logCorreo( $sess->userId, $sess->userMail, "Subida de Borrador de Tesis Corregido", $msg );
        $cel= $this->dbPilar->inCelTesista($sess->userId);
        $resCel= $this->NotificacionCelular($cel,$msgcel);
       
        if($tram->IdTesista2!=0)
          {
            $cel= $this->dbPilar->inCelTesista($tram->IdTesista2);
            $resCel= $this->NotificacionCelular($cel,$msgcel);
            $mail = $this->dbPilar->inCorreo( $tram->IdTesista2);
            $this->logCorreo( $tram->IdTesista2, $mail, "Subida de Borrador de Tesis Corregido", $msg );
          }  

        $this->logTramites( $sess->userId, $tram->Id, "Subida de Borrador de Tesis Corregido", $msg );

        //-------------------------

        //--------Estimado Asesors ------------
        $msg = "<br>Estimado(a) Asesor, el tesista ha subido su Borrador de Tesis Corregido en el codigo de trámite:<br><br>"
             . "Codigo: <b>$tram->Codigo</b><br> "
             . "Título de Proyecto : <b>$titul</b> <br><br>  "
             . "A partir de la fecha los miembros del jurado tienen un plazo de 15 días calendarios "
             . " para verificar las correciones y/o observalas." 
            ." <br><br> *Este es un mensaje automático, no responda por favor.";
         $msgcel =" UNU -PILAR \nEstimado(a) Asesor, el tesista ha subido su Borrador de Tesis corregido en el codigo de trámite <b>$tram->Codigo</b>,<br>" 
            . " A partir de la fecha los miembros del jurado tienen un plazo de 15 días calendarios para verificar las correciones y/o observalas."
            ."\n".date("d-m-Y")." \nVRI - Plataforma PILAR UNU " 
            ."\n*Este es un mensaje automático, no responda por favor.";
           
        $corr4 = $this->dbRepo->inCorreo( $tram->IdJurado4 );
        $this->logCorreoDoce( $tram->IdJurado4,0, $corr4, "Subida de Borrador de Tesis Corregido", $msg );
        $cel = $this->dbRepo->inCelu( $tram->IdJurado4 );
             $resCel= $this->NotificacionCelular($cel,$msgcel);
        //----------------------------------------

        //-----------jurados-------------------------
        $msg = "<br>Estimado(a) Docente, el tesista ha subido su Borrador de Tesis Corregido en el trámite:<br><br>"
             . "Codigo: <b>$tram->Codigo</b><br> "
             . "Título de Proyecto : <b>$titul</b> <br><br>  "
             . "A partir de la fecha tiene un plazo de 15 días calendarios "
             . " para verificar las correciones y/o observalas." 
            ." <br><br> *Este es un mensaje automático, no responda por favor.";            

        $msgcel = "Estimado(a) Docente.\n"
            ."el tesista ha subido su Borrador de Tesis Corregido en el trámite <b>$tram->Codigo</b>, A partir de la fecha tiene un plazo de 15 días calendarios para verificar las correciones y/o observalas. \n                     
            \n".date("d-m-Y")." \nVRI - Plataforma PILAR UNU.  
            \n*Este es un mensaje automático, no responda por favor.";
        $corr1 = $this->dbRepo->inCorreo( $tram->IdJurado1 );
        $corr2 = $this->dbRepo->inCorreo( $tram->IdJurado2 );
        $corr3 = $this->dbRepo->inCorreo( $tram->IdJurado3 );
        if($pos1!=2){
            $this->logCorreoDoce( $tram->IdJurado1,0, $corr1, "Borrador de Tesis Corregido ", $msg );
        }
        
        if($pos2!=2){
             $this->logCorreoDoce( $tram->IdJurado2,0, $corr2, "Borrador de Tesis Corregido", $msg );
        }

        if($pos3!=2){
            $this->logCorreoDoce( $tram->IdJurado3,0, $corr3, "Borrador de Tesis Corregido", $msg );
        } 
        
        $celu1 = $this->dbRepo->inCelu(  $tram->IdJurado1);
        $celu2 = $this->dbRepo->inCelu(  $tram->IdJurado2 );
        $celu3 = $this->dbRepo->inCelu(  $tram->IdJurado3 );
       $resCel1= $this->NotificacionCelular( $celu1,$msgcel);
        $resCel2= $this->NotificacionCelular( $celu2,$msgcel);
        $resCel3= $this->NotificacionCelular( $celu3,$msgcel);
        //----------------------------------------------
       
         $msg= "<div class='panel panel-info'>"
                        ."<div class='panel-heading'>
                                <h2 class='panel-title'> <b></b>Borrador de Tesis Corregido </h2>
                          </div>"
                        ."<div class='panel-body' id='plops'>"
                            ." Estimado(a) Tesita se ha subido su Borrador de Tesis corregido en el trámite:<br><br>"
                            . "Codigo: <b>$tram->Codigo</b><br> "
                            . "Título de Proyecto : <b>$titul</b> <br><br>  "
                            . "A partir de la fecha los miembros de sus jurados tiene un plazo de 15 días calendarios "
                            . " para que verifiquen las correciones de su Borrador de Tesis."
                        ."</div></div>";
        echo $msg ;

        }
        else if($tram->Estado==15){            

              $msg= "<div class='panel panel-info'>"
                        ."<div class='panel-heading'>
                                <h2 class='panel-title'> <b></b>Borrador de Tesis Final </h2>
                          </div>"
                        ."<div class='panel-body' id='plops'>"
                            ." Estimado(a) Tesita se ha subido su Borrador de Tesis Final en el trámite : <br><br>"
                            . "Codigo: <b>$tram->Codigo</b><br> "
                            . "Título de Proyecto : <b>$titul</b> <br><br>  "
                        ."</div></div>";  
        echo $msg ;

        } else
        {
             $msg = "<br>Estimado(a) Tesita se ha subido su Borrador de Tesis corregido en el trámite:<br><br>"
             . "Codigo: <b>$tram->Codigo</b><br> "
             . "Título de Proyecto : <b>$titul</b> <br><br>  "
             . "Su borradorde Tesis ahora se encuentra en proceso de Dictamen, la Comisión de Grados y Titulos de su Facultad tiene 7 dias calendarios para notificar a sus miembros de jurado para una Revision Presencial."  ;
              $msgcel =" UNU -PILAR \nEstimado(a)Tesista se ha subido su borrador de tesis corregido en el codigo de tramite <b>$tram->Codigo</b>,<br>" 
            . " Su borradorde Tesis ahora se encuentra en proceso de Dictamen, la Comisión de Grados y Titulos de su Facultad tiene 7 dias calendarios para notificar a sus miembros de jurado para una Revision Presencial"
            ."\n".date("d-m-Y")." \nVRI - Plataforma PILAR UNU " 
            ."\n*Este es un mensaje automático, no responda por favor.";

         $this->logCorreo( $sess->userId, $sess->userMail, "Dictamen de Borrador de Tesis", $msg );
        $cel= $this->dbPilar->inCelTesista($sess->userId);
        $resCel= $this->NotificacionCelular($cel,$msgcel);
       
        if($tram->IdTesista2!=0)
          {
            $cel= $this->dbPilar->inCelTesista($tram->IdTesista2);
            $resCel= $this->NotificacionCelular($cel,$msgcel);
            $mail = $this->dbPilar->inCorreo( $tram->IdTesista2);
            $this->logCorreo( $tram->IdTesista2, $mail,"Dictamen de Borrador de Tesis", $msg );
          }  

        $this->logTramites( $sess->userId, $tram->Id, "Dictamen de Borrador de Tesis", $msg );

         $msg= "<div class='panel panel-info'>"
                        ."<div class='panel-heading'>
                                <h2 class='panel-title'> <b></b>Borrador de Tesis para Dictamen </h2>
                          </div>"
                        ."<div class='panel-body' id='plops'>"
                            ." Estimado(a) Tesita se ha subido su Borrador de Tesis para dictamen en el trámite:<br><br>"
                            . "Codigo: <b>$tram->Codigo</b><br> "
                            . "Título de Proyecto : <b>$titul</b> <br><br>  "
                            . "Su borradorde Tesis ahora se encuentra en proceso de Dictamen, la Comisión de Grados y Titulos de su Facultad tiene 7 dias calendarios para notificar a sus miembros de jurado para una Revision Presencial."
                        ."</div></div>";
        echo $msg ;      
        }


    }


	function correoProfes( $tram )
	{
		//
		// $tram = $this->dbPilar->inTramByTesista(1);
		//
		//$this->gensession->IsLoggedAccess();

		$correo1 = $this->dbRepo->inCorreo( $tram->IdJurado1 );
		$correo2 = $this->dbRepo->inCorreo( $tram->IdJurado2 );
		$correo3 = $this->dbRepo->inCorreo( $tram->IdJurado3 );
		$correo4 = $this->dbRepo->inCorreo( $tram->IdJurado4 );

        $msg = "<br>Se ha actualizado el trámite: <b>$tram->Codigo</b><br><br> "
             . "Título de Proyecto de Tesis: <b>$titul</b> <br><br> "
             . "A partir de la fecha en un plazo de 15 días hábiles (sin feriados) "
             . "Ud. podrá realizar la Dictaminación del <b>Proyecto de Tesis</b>. "
             . "Una vez que se ha procedido con realizar el envio de las notificaciones.";


        // grabar y enviamos mail en LOG correos
		//
        $this->logCorreo( $tram->Id, $correo1, "Subida de Corrección", $msg );
		$this->logCorreo( $tram->Id, $correo2, "Subida de Corrección", $msg );
		$this->logCorreo( $tram->Id, $correo3, "Subida de Corrección", $msg );
		$this->logCorreo( $tram->Id, $correo4, "Subida de Corrección", $msg );
	}



    //
    // argumentos por URL : Combo Lineas Docentes
    ////modificado unuv1 --(3.8.4)
    public function loadLinCbo( $tipjur, $linea )
    {
        $this->gensession->IsLoggedAccess();
        $sess = $this->gensession->GetData();

        // nombre BD estricto
        $dbrep = "desarrollo_absmain"; // modificar testing-produccion
        $dbpil = "desarrollo_pilar3"; // modificar testing-produccion

        // first item
        echo "<option value='' disabled selected> seleccione </option>";

        // aliementamos los nombrados
        if( $tipjur == 4 )
        {
            // ojo tiene que ser vwJurados no repositorio
            // autoridades elegibles
            //  
			/*
            $table = $this->dbPilar->getQuery (
                "SELECT  * FROM  $dbpil.vxDocInLin
                  WHERE  IdCategoria <= '9'
                    AND  Activo >= 5
                    AND  IdLinea='$linea' ORDER BY DatosPers" );
			*/

            $table = $this->dbPilar->getSnapView(
						  "vxDocInLin",
						  "Activo>=5 AND IdLinea='$linea' AND TipoDoc='N' AND LinEstado = 2",
						  "ORDER BY DatosPers" );

            //
            // Rectores, Decanos NO pero si Asesores
            //
            foreach( $table->result() as $row ) {
                echo "<option value=$row->IdDocente> $row->DatosPers </option>";
            }
        }

        if( $tipjur == 3 )
        {
			// PRONTO A QUITAR TBLAUTORIDADES

            // ojo tiene que ser vwJurados no repositorio
            //

			/*
            $table = $this->dbPilar->getQuery(
              "SELECT  * FROM  $dbpil.vxDocInLin
                WHERE  IdDocente NOT IN(SELECT IdDocente FROM $dbrep.tblAutoridades)
                  AND  Activo = 6
                  AND  IdLinea = $linea ORDER BY DatosPers" );
		  	*/
            $table = $this->dbPilar->getSnapView(
						  "vxDocInLin",
						  "Activo=6 AND IdLinea='$linea'",
						  "ORDER BY DatosPers" );


            // Rectores, Decanos NO pero si Asesores
            //
            foreach( $table->result() as $row ){
                echo "<option value=$row->IdDocente> $row->DatosPers </option>";
            }
        }
    }


    //------------------------------------
    // external function area AJAX
    //------------------------------------
    //Modificado unuv1.0 -- (2.1.1)
    public function ComprobarCorreo()
    {
        $data = mlGetGlobalVar( "proRec" );
        if( !$data ){
            echo "Sin acceso autorizado.";
            return;
        }
        $data = json_decode( json_encode ($data) );
        $mail = mlSecurePost("mail");
        if( $this->dbPilar->getSnaprow( "tblTesistas", "correo='$mail'" ) ) 
        {
            echo 'true';
        }
        else{

        echo 'false';
          }
    }   

    //
    // grabar nuevo tesista verificado con OTI
    //
    //Modificado unuv1.0 -- (2.2)
    public function execInNew()
    {
        // validacion de datos interna
        $data = mlGetGlobalVar( "proRec" );
        if( !$data ){
            echo "Sin acceso autorizado.";
            return;
        }

        // procedemos:  array : { } : json
        $data = json_decode( json_encode ($data) );


        //
        // Super Importante : registrar evitando duplicados
        //
        if( ! $this->dbPilar->getSnaprow( "tblTesistas", "Codigo='$data->Codigo'" ) ) {

            $pass = mlSecurePost("pass1");
            $mail = mlSecurePost("mail");

            // mb_strtoupper
            $myId = $this->dbPilar->Insert( 'tblTesistas', array(
                'Activo'     => 2, // desde ya
                'DNI'        => $data->DNI,
                'Codigo'     => $data->Codigo,
                'IdFacultad' => $data->IdFacu,
                'IdCarrera'  => $data->IdCarr,
                'IdEspec'    => $data->IdEspec,
                'SemReg'     => $data->SemReg,
                'FechaReg'   => mlCurrentDate(),
                'NroCelular' => mlSecurePost("celu"),
                'Direccion'  => mlSecurePost("dire"),
                'Correo'     => mlSecurePost("mail"),
                'Nombres'    => $data->Nombres,
                'Apellidos'  => $data->Apellis,
                'Clave'      => sqlPassword($pass)
            ));



            $msg = "<h3>Bienvenido</h3>"
                 . "Estimado(a): <b>$data->Nombres $data->Apellis</b>.<br>"
                 . "Ud. ha concluido satisfactoriamente su inscripción en la  "
                 . "Plataforma PILAR para el trámite virtual de su "
                 . "proyecto y borrador de tesis, en calidad de "
                 . "egresado de la <b>UNU</b>."
                 . "<br><br><b>Datos de su Cuenta:</b><br>"
                 . "  * usuario: $mail<br>"
                 . "  * contraseña: $pass<br>"
                 . "<br><br>Gracias."
                 ;

            // grabar en LOG de correos y enviamos mail
            $this->logCorreo( $myId, $mail, "Inscripción", $msg );
        //    $this->notiCelu(mlSecurePost("celu"),1);

            echo "Registro completo, revise su <b>e-mail</b> y <b>celular</b.";

        } else {
            echo "Se guardo previamente";
        }

        //print_r( $data );
        mlSetGlobalVar( "proRec", null );
    }
 //Agregado unuv1.0
public function notiCelu($cel,$tip)
{

    $this->load->library('apismss'); 
    $number   = "0051$cel";
    if($tip==1){ // Tesista : mensaje rechazo del proyecto
       $mensaje  = "UNU -PILAR \nEstimado(a) Tesista Ud. ha concluido satisfactoriamente su inscripción en la Plataforma PILAR para el tramite virtual de su proyecto y borrador de tesis, para mayor informacion de su cuenta creada revise su correo electronico.  \n\n".date("d-m-Y")."\nPlataforma PILAR.";
    }
    else
    {
        $mensaje  = "UNU -PILAR \nEstimado(a) Tesista Ud. ha registrado su proyecto satisfactoriamente, la comisión de GyT de su facultad tendra 2 dias calendarios para revisar el formato y otros criterios, antes de enviar su proyecto a su Asesor,para mayor informacion de su proyecto registrado revise su correo electronico.  \n\n".date("d-m-Y")."\nPlataforma PILAR.";
    }

$result   = $this->apismss->sendMessageToNumber2($number,$mensaje);

    if ($result) {
       return "Mensaje Enviado al $number";
    }else{
       return  "Error al enviar mensaje : $number";
    }
}
//------- agregado unuv2.0-------------
public function NotificacionCelular($cel,$mensaje)
{
    $this->load->library('apismss'); 
    $number   = "0051$cel";
    $result   = $this->apismss->sendMessageToNumber2($number,$mensaje);
    if ($result) {
       return " <br>Mensaje Enviado al $cel";
    }else{
       return  "<b>Error al enviar mensaje : $cel </b>";
    }
}
//------- fin agregado unuv2.0-------------



    //
    // verificacion con OTI, dni, semestre, carrera, session
    // Modificado unuv1.0 -- (1.2)
    public function jsBusqTes()
    {
        mlSetGlobalVar( "proRec", array() );

        // no logueado
        $codigo = mlSecurePost("cod");
        $numdni = mlSecurePost("dni");
        if( ! $codigo ) return;

        if( $row = $this->dbPilar->getSnapRow("tblTesistas","Codigo='$codigo'") ) {
            echo "<b>$row->Nombres</b> Ha sido registrado el <b>$row->FechaReg</b>";
            return;
        }
        $alumno = $this->dbRepo->getSnapRow("tblcandidatostesistas","Codigo='$codigo'");
        //$alumno = otiGetData($codigo);
        if( $alumno == null ) {
            echo "<b> No se encontro informacion con los datos ingresados, comunicarse al correo soporte_pilar@unu.edu.pe </b>";
            return;
        }

        /*if( $alumno->success == false )
        {
            echo "<b> Datos incompletos </b>";
            return;
        } //Modificado v.1.0 */

        // copiar datos y verificacion de DNI
        $data = $alumno;
        if( $data->documento_numero != $numdni ){
            echo "<b> Los datos no coinciden, comunicarse al correo soporte_pilar@unu.edu.pe </b>";
            return;
        }


        // solo los semestres
        $arrSemes = array(
                   "OCTAVO", "NOVENO", "DECIMO",
                   "DECIMO PRIMERO", "DECIMO SEGUNDO",
                   "DECIMO TERCERO", "DECIMO CUARTO","EGRESADO"
             );

        if( !in_array($data->matricula_semestre, $arrSemes) ) {
            echo "<b>Solo estudiantes de 2 últimos semestres</b> <br><small>"
               . "Ud. está en: " .$data->matricula_semestre. "</small>" ;
            return;
        }

        // codigo 15 no, 14 agosto no
        // if( $codigo >= 142000 && $codigo <= 700000 ){
        //     echo "Ley 30220-SUNEDU, Reglamento en desarrollo apersonese al VRI";
        //     return;
        // }

        // revisar carreras permitidas
        $carres = $this->dbRepo->getSnapRow( "dicCarreras", "Nombre = '$data->escuela'" );
        if( $carres == null ) {
            echo "Error.05 : Carrera no indexada";
            return;
        }

        // 20 Secundaria
        // 11 Arte
        // 29 fismat
        // 16 biologia

        $idEspec = 0;
        /*if( $carres->Id==20 or $carres->Id==11 or $carres->Id==29 or $carres->Id==16) {

            // buscar la especialidad y grabarla
            //---------------------------------------------------------
            $arrEsp = array(
                ""                                              => 0,
                "CARRERA PURA"                                  => 0,
                //------------------------------------------------------
                "CIENCIAS SOCIALES"                             => 1,
                "BIOLOGIA, FISICA, QUIMICA Y LABORATORIOS"      => 2,
                "BIOLOGIA, FISICA, QUIMICA Y LABORATORIO"       => 2,
                "LENGUA, LITERATURA, PSICOLOGIA Y FILOSOFIA"    => 3,
                "MATEMATICA E INFORMATICA"                      => 4,
                "MATEMATICA, COMPUTACION E INFORMATICA"         => 4,
                "MATEMATICA FISICA COMPUTACION E INFORMATICA"   => 4,
                //------------------------------------------------------
                "ARTES PLASTICAS"                               => 11,
                "MUSICA"                                        => 12,
                "DANZA"                                         => 13,
                "TEATRO"                                        => 14,
                //------------------------------------------------------
                "PESQUERIA"                           => 21,
                "ECOLOGIA"                            => 22,
                "MICROBIOLOGIA Y LABORATORIO CLINICO" => 23,
                //------------------------------------------------------
                "MENCION MATEMATICA"                  => 31,
                "MENCION FISICA"                      => 32

            );

            $idEspec = $arrEsp[ $data->matricula->especialidad ];
        } //comentado unuv1.0 */


        // mlSetGlobalVar( "proRec", Facultad, Carrera, DNI txt, Codigo, Datos )
        mlSetGlobalVar( "proRec", array(
                'IdCarr'  => $carres->Id,
                'IdFacu'  => $carres->IdFacultad,
                'IdEspec' => $idEspec,
                'SemReg'  => $data->matricula_anio."-".$data->matricula_periodo." (".$data->matricula_semestre.")",
                'DNI'     => $data->documento_numero,
                'Apellis' => $data->apellidos,
                'Nombres' => $data->nombres,
                'Codigo'  => $data->codigo
            ) );


        // finalmente a mostrar los datos
        $this->load->view( "pilar/tes/regNvoTes", array(
                'data' => $data  // Json in array
            ) );
    }
    //------agregado unuv2.0---------
    public function jsRestablecer()
    {
        $email= mlSecurePost("mail");
        if($row = $this->dbPilar->getSnapRow("tblTesistas","Correo='$email'"))
        {
            $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $claveNueva ='UNU'.substr(str_shuffle($permitted_chars), 0, 6);
           
            $this->dbPilar->Update( 'tbltesistas', array(
                'Clave'    => sqlPassword($claveNueva),
                'Clavealeatorio'    => sqlPassword($claveNueva)
            ), $row->Id);
            $msg = "Estimado(a) $row->Nombres.<br><br>"
              ."Solicitó un restablecimiento de contraseña para el ingreso a la Plataforma PILAR <br>"
             . "Contraseña : <b>$claveNueva</b><br><br>"
             . "Nota : La plataforma PILAR solicitara el cambio de contraseña para mayor seguridad. 
                <br><br> *Este es un mensaje automático, no responda por favor."  ;
            $msgcel = "Estimado(a) $row->Nombres.\n"
            ."Solicitó un restablecimiento de contraseña para el ingreso a la Plataforma PILAR. \n"
           . "Contraseña : $claveNueva                      
            \n".date("d-m-Y")." \nVRI - Plataforma PILAR UNU.  
            \n*Este es un mensaje automático, no responda por favor.";
            $this->logLogin( $row->Id, "Restablecer Contraseña PILAR");            
            
            $cel= $this->dbPilar->inCelTesista( $row->Id);
            //$this->NotificacionCelular($cel,$msgcel); //activar los mensajes de textos
            $this->logCorreo($row->Id, $row->Correo, "Restablecer Contraseña PILAR", $msg);
            echo "La nueva contraseña ha sido enviado a tu correo electronico y numero de celular registrado.";

        }
        else
        {
            echo "";
            return;
        }
    }
    //------ fin agregado unuv2.0---------

    function listarEsp( $esp=1 )
    {
        echo "<style> body{ padding: 50px; font-family: Arial; font-size: 14px } </style>";
        echo "<table cellPadding=5 cellSpacing=0 border=1 style='font-size: 12px' width=800px>";

        $carr = $this->dbRepo->getSnapRow( "vwLstCarreras", "IdEspec=$esp" );
        echo "E.P.: $carr->Carrera : $carr->Especialidad";

        $nro = 1;
        $tes = $this->dbPilar->getSnapView( "vxDatTesistas", "IdCarrera=20 AND IdEspec=$esp ORDER BY DatosPers" );
        foreach( $tes->result() as $row ) {
            echo "<tr>";
            echo "<td> $nro </td>";
            echo "<td> $row->Codigo </td>";
            echo "<td> $row->DatosPers </td>";
            echo "<td> $row->SemReg </td>";
            echo "<td> " .mlFechaNorm($row->FechaReg). " </td>";
            echo "</tr>";
            $nro++;
        }

        echo "</table>";
    }

    public function mails(){

        $this->gensession->IsLoggedAccess();

        $sess = $this->gensession->GetData();
        $tram = $this->dbPilar->inTramByTesista( $sess->userId ); // Tipo > 0

        // no hay tramite disponble nuevo tramite
        if( $sess ) {
            $mail=$this->dbPilar->getOneField('tblTesistas',"Correo","Id=$sess->userId");
            $prev = $this->dbPilar->getTable( "logCorreos", "IdTesista='$sess->userId' ORDER BY Id DESC" );
            $this->load->view( "pilar/tes/proc/0_mails", ['prev'=>$prev] );

            //echo "<h3> Se  esta preparando un módulo de acuerdo al nuevo reglamento en debate el 20-03-18 en Consejo Universitario.</h3>";
            // echo "Desde este punto si los proyectos no cumplen el nuevo formato serán rechazados. Los que cumplen seguirán su tramite hasta concluir el semestre.";
            return;
        }

    }

    // Módulo complementario para realizar las sustentaciones en PILAR en el Periodo del COVID-19
    //Mostrar Formulario para subir archivo de bachiller - unuv2.0
    public function uploadBachiller(){
        $sess = $this->gensession->GetData();
        $tram = 
        $this->load->view('pilar/tes/proc/10_subbach',array('sess'=>$sess));
    }

    //Subida de bachiller - unuv2.0
    public function execInBachi(){
        $sess  = $this->gensession->GetSessionData();

        // si falla al subir termina
        $archi = $this->subirArchevo( 3 );
        if( ! $archi ) return;

        $rrec  = mlSecurePost( "rrec" );
        $dater = mlSecurePost( "dater" );
        $anio = mlSecurePost( "anio" );
        $tram=$this->dbPilar->inTramByTesista($sess->userId);


        $this->dbPilar->Insert( 'tesTramsBach', array(
            'Estado' => 1,//(1)Subido (2)Aprobado
            'IdTramite' => $tram->Id,
            'IdTesista' => $sess->userId,
            'IdCarrera' => $tram->IdCarrera,
            'NroRes'    => $rrec,
            'AnioRes'    => $anio,
            'DateRes'   => $dater,
            'File'   => $archi,
            'Obs'   => '-',            
        ));

        $msg = "Estimado(a) Tesista Ud. ha cargado su grado de bachiller en el Codigo de tramite <b>$tram->Codigo </b>, este proceso tiene caracter de declaración jurada bajo la responsabilidad del usuario de esta cuenta. Se habilitará la opción de cargar el borrador de tesis.<br>";

        $msgcel =" UNU -PILAR \nEstimado(a) Tesista Ud. ha cargado su grado de bachiller en el Codigo de tramite <b>$tram->Codigo </b>, este proceso tiene caracter de declaración jurada bajo la responsabilidad del usuario de esta cuenta. Se habilitará la opción de cargar el borrador de tesis.<br>";

        if ($tram->IdTesista2==0) { //Si la tesis es de una persona entra
            $this->dbPilar->Update( 'tesTramites', array(
                'Tipo'    => 2,
                'Estado'    => 9,
                'FechModif' => mlCurrentDate(), // fecha de modificacion de archivo (subida de archivo nuevo)
                'FechActBorr'=> mlCurrentDate() // activacion de borrador, para que el tesista pueda subir su borrador
            ), $tram->Id );
        }else{
            $querytes1=$this->dbPilar->getOneField('tesTramsBach',"Id","IdTesista=$tram->IdTesista1");
            $querytes2=$this->dbPilar->getOneField('tesTramsBach',"Id","IdTesista=$tram->IdTesista2");
            if ($querytes1 && $querytes2) {
                $this->dbPilar->Update( 'tesTramites', array(
                    'Tipo'    => 2,
                    'Estado'    => 9,
                    'FechModif' => mlCurrentDate(),
                    'FechActBorr'=> mlCurrentDate()
                ), $tram->Id );
            }else{
                $msg= $msg."<br><b>Nota : </b>Para completar la habilitación ambos tesistas deberán cargar su grado  de bachiller en PILAR.";
            }
        }
        $resCel='';
        $cel= $this->dbPilar->inCelTesista( $tram->Id);
        $resCel= $this->NotificacionCelular($cel,$msgcel);

       

        // agregar tramite
        $this->logTramites( $sess->userId, $tram->Id, "Subida de Bachiller", $msg.$resCel );

        // grabar y enviamos mail en LOG correos
        $this->logCorreo( $sess->userId, $sess->userMail, "Subida de Bachiller", $msg );

        echo $msg;
    }

    // Módulo de carga final de Borrador de tesis
    public function vwSolictaSust(){
        $this->gensession->IsLoggedAccess();

        $sess = $this->gensession->GetData();
        $tram = $this->dbPilar->inTramByTesista( $sess->userId );

        // no hay tramite disponble nuevo tramite
        if( $tram == null ) 
        {
            echo "<br><br><center><h3>  ¡Lo sentimos!  <br> Usted  aún no ha iniciado su tramite para su Tesis. </h3></center>";
            return;
        }
        $det = $this->dbPilar->inTramDetIter($tram->Id, 5);

        $solic=$this->dbPilar->getSnapRow("tesSustensSolic","IdTramite=$tram->Id");
        if( $solic ) {
            if ($solic->Estado==1) {
                # code...
                echo "<center><img class='img-responsive' style='height:70px;' src='".base_url('vriadds/pilar/imag/pilar-tes.png')."'/> </center>";
                echo "<center><h2 class='text'>Solicitud de Enviada </h2>";
            }

            // Si aún no se publicó 
            elseif ($solic->Estado==3) {
            
            $link = base_url("pilar/tesistas/actaDeliberacion/$tram->Id");

            echo "<div class='text-center'>";
            echo "<center><img class='img-responsive' style='height:70px;' src='".base_url('vriadds/pilar/imag/pilar-tes.png')."'/> </center>";
            echo "<h1>¡Felicitaciones! </h1>";
            echo "<h4>Su Trabajo de Investigación de Tesis ha sido <b class='text-success'>Aprobado</b></h4> Puede descargar su Acta de aprobación de Proyecto de Tesis. </h4>";
            echo "<hr> <a href='$link' target=_blank class='btn btn-info'> Ver/Descargar Acta </a>";
            echo "</div>";

            }
            elseif ($solic->Estado==2) {

                echo "<h4> Esperando el Dictamen del Jurado Evaluador .... </center> "; 
                echo "<br>Presidente      : <b> " .($det->vb1!=0? "Ok":"En Dictamen . . ."). "</b>";
                echo "<br>Primer Miembro  : <b> " .($det->vb2!=0? "Ok":"En Dictamen . . ."). "</b>";
                echo "<br>Segundo Miembro : <b> " .($det->vb3!=0? "Ok":"En Dictamen . . ."). "</b>";
                echo "<br>Asesor : <b> " .($det->vb4!=0? "Ok":"En Dictamen . . ."). "</b>";

            }else{
                echo "<h4> Esperando la Verificación y Publicación de la Sustentación ";
            }
        }
        elseif ($tram->Tipo == 2 && $tram->Estado == 17 ) {
            $this->load->view('pilar/tes/proc/13_sustvirtual',array('sess'=>$sess));
        }
        else{
            echo "<br><br><center><h3>  ¡Lo sentimos!  <br> Usted aún no cumple los requisitos para este proceso ( Sustentacion Virtual ). </h3></center>";
        }
    }

    public function execSolSusten(){
        $sess  = $this->gensession->GetSessionData();

        // si falla al subir termina
        $archi = $this->subirArchevo( 5 );
        if( ! $archi ) return;

        $dated=mlSecurePost("dated");
        $dates=mlSecurePost("dates");
        $enlarepo=mlSecurePost("enlarepo");

        $tram=$this->dbPilar->inTramByTesista($sess->userId);

        $this->dbPilar->Insert( 'tesSustensSolic', array(
            'Estado' => 1,
            'IdTramite' => $tram->Id,
            'IdTesista' => $sess->userId,
            'IdCarrera' => $tram->IdCarrera,
            'UrlRepo'    => $enlarepo,
            'FechDic'    => $dated,
            'FechSusten'   => $dates,
            'FileDiapo'   => $archi,
            'DateSolic'   => mlCurrentDate(),
            'Obs'   => '-',            
        ));


        $msg = "Se ha registrado la solicitud de exposición y defensa no presencial con el trámite:<br>"
             . "Codigo: <b>$tram->Codigo </b><br> "
             . "Se notificará a la Unidad de Investigación, para verificación de la información y programación de la sustentación en el panel de PILAR.<br>"
             . "Se procede con el registro y envio de las notificaciones."  ;

        // Agregar log del Trámite
        $this->logTramites( $sess->userId, $tram->Id, "Solicitud No Presencial", $msg );

        // Grabar y enviamos mail en LOG correos
        $this->logCorreo( $sess->userId, $sess->userMail, "Solicitud No Presencial", $msg );
        

        echo $msg."<br> Hecho!";

    }



    public function actaDeliberacion( $idTram=0 )
    {
                
        if( !$idTram ) return;

        $tram = $this->dbPilar->inProyTram($idTram);
        if( !$tram ){ echo "Inexistente"; return;}
        if( $tram->Estado < 13 ){ echo "No Aprobado"; return;}

        $dets = $this->dbPilar->inTramDetIter($idTram, 5);
        if( !$dets ) return;
        // iteración 4 presenta borrador
        // iteración 5 sustenta
               
        $acta = $this->dbPilar->getSnapRow("tesSustenAct","IdTramite=$idTram");
        if( !$acta ) { echo "No hay Acta "; return;}

        $pdf = new GenSexPdf();

        //$pdf->AddPage();
        $pdf->AddPageEx( 'P', '', 2 );
        $pdf->SetMargins( 18, 40, 20 );

        $pdf->Ln( 25 );
        //$pdf->SetFont( "Times", 'B', 15 );


        //$pdf->Cell( 28, 9, $tram->Codigo, 1, 0, 'C' ); el código ya no va acá
        $pdf->BarCode39( 150, 34, $tram->Codigo );
        mlQrRotulo( $pdf, 19, 240, $tram->Codigo );


        $txtFacultadPerse=toUTF($this->dbRepo->inFacultad($tram->IdCarrera));
        $txtEscuelaPerse=toUTF($this->dbRepo->inCarrera($tram->IdCarrera));

        $txtFacultad="FACULTAD DE ".$txtFacultadPerse;
        $txtEscuela="ESCUELA PROFESIONAL DE".$txtEscuelaPerse;
        $pdf->Ln( 10 );
        $pdf->SetFont( "Arial", 'B', 11 );
        $pdf->Cell( 174, 5, toUTF(strtoupper($txtFacultad)), 0, 1, 'C' );
        $pdf->Cell( 174, 5, toUTF(strtoupper($txtEscuela)), 0, 1, 'C' );
        $pdf->Ln(5);


        // agregar ruta en la BD de la imagen

        // $codCarrera= $tram->IdCarrera;
        // $rutaEscudo=$this->dbRepo->getOneField("dicCarreras","RutaEscudo","Id=".$codCarrera); 

        // $pdf->Cell(70,40, "",0);
        // if ($rutaEscudo) {
        //     $pdf->Cell(46,40, $pdf->Image($rutaEscudo, $pdf->GetX(), $pdf->GetY(),30),0, 0,'R');
        // }
        $pdf->Cell(58,40, "",0);
        $pdf->Ln(5);
        
        


        $cadTitulo="ACTA DE EVALUACIÓN DE TESIS Nº ";
        // $acta="001"; // agregar función que lleve la cuenta de actas por escuela
        $pdf->Ln( 20 );
        $pdf->SetFont( "Arial", 'B', 14 );
        $pdf->Cell( 174, 5, toUTF($cadTitulo).$acta->Num, 0, 1, 'C' );


        $dia = (int) substr( $dets->Fecha, 8, 2 );
        $mes = mlNombreMes( substr($dets->Fecha,5,2) );
        $ano = (int) substr( $dets->Fecha, 0, 4 );
        $hor = substr( $dets->Fecha, 11, 8 );


        


        $str = "El jurado revisor ha calificado el trabajo de tesis titulado:";

        $pdf->Ln( 7 );
        $pdf->SetFont( "Arial", '', 10 );
        $pdf->MultiCell( 174, 5, toUTF($str) );


        $pdf->Ln( 4 );
        $pdf->SetFont( "Arial", 'B', 10 );
        $pdf->MultiCell( 174, 6, toUTF($dets->Titulo), 0, 'C' );

        $strBachiller = "Presentado por el(la) Bachiller:";
        $tes = $this->dbPilar->inTesista($tram->IdTesista1, true);
        if( $tram->IdTesista2 != 0 ){
            $strBachiller= "Presentado por los Bachilleres:";
            $tes = $tes ."\n". $this->dbPilar->inTesista($tram->IdTesista2, true);
        }

        $pdf->Ln( 4 );
        $pdf->SetFont( "Arial", '', 10 );
        $pdf->MultiCell( 174, 5, toUTF($strBachiller),0 );

        $pdf->Ln( 4 );
        $pdf->SetFont( "Arial", 'B', 10 );
        $pdf->MultiCell( 174, 6, toUTF($tes), 0, 'C' );

        
        
        $strCod=$this->dbPilar->getOneField("tblTesistas","Codigo","Id=".$tram->IdTesista1);
        $strCod1=$this->dbPilar->getOneField("tblTesistas","Codigo","Id=".$tram->IdTesista2);

        $strCodPY= $tram->Codigo;
        $strTexto = "Con código de matrícula ".$strCod;
        $strTextoCodPY = " y código de proyecto : ".$strCodPY;
        if( $tram->IdTesista2 != 0 ){
            $strTexto= $strTexto." y $strCod1 ";
        }

        $strCodPY= $tram->Codigo;
        $strTexto = "Con código de matrícula ".$strCod;
        $strTextoCodPY = " y código de proyecto : ".$strCodPY;
        //obtener código de matrícula
        $pdf->Ln( 4 );
        $pdf->SetFont( "Arial", '', 10 );
        $pdf->Cell( 174, 5, toUTF($strTexto.$strTextoCodPY),0);

        // carrera
        $txtCarrera = $this->dbRepo->inCarrera($tram->IdCarrera);
        $pdf->Ln(6);
        $pdf->SetFont( "Arial", "", 10 );
        $pdf->Cell( 50, 5, toUTF("de la Escuela Profesional de"),0, 'L' );
        $pdf->SetFont( "Arial", "B", 10 );
        $pdf->Cell( 40, 5, toUTF(": ".$txtCarrera), 0, '' );



        $asesor = $this->dbRepo->inDocenteEx( $tram->IdJurado4 );

        $pdf->Ln(6);
        $pdf->SetFont( "Arial", "", 10 );
        $pdf->Cell( 50, 5, "Asesor ", 0, 0, "L" );
        $pdf->Cell( 100, 5, ": " .toUTF($asesor), 0, 0, "L" );

                
        $resEvaluacion= "$acta->Obs"; // función de resultado de evaluación
       
        $codCarrera= $tram->IdCarrera;
        $denominacion=$this->dbRepo->getOneField("dicCarreras","Titulo","Id=".$codCarrera); 

        $pdf->Ln(6);
        $pdf->Cell( 60, 5, toUTF("Siendo el resultado de la evaluación"), 0, 0, "L" );
        $pdf->Cell( 80, 5, ": " .toUTF($resEvaluacion), 0, 0, "L" );


        $pdf->Ln(10);
        $pdf->Cell( 50, 5, "Por lo expuesto, el(la) bachiller ", 0, 0, "L" );
        $pdf->Cell( 100, 5, ": " .toUTF($tes), 0, 0, "L" );

        $pdf->Ln(6);
        $pdf->Cell( 83, 5, toUTF("queda expedito para recibir el Título Profesional de: "), 0, 0, "L" );
        $pdf->SetFont( "Arial", 'B', 10 );
        $pdf->Cell( 70, 5, toUTF($denominacion), 0, 0, "L" ); 
        
               

        $cadenaFe = "Para dar fe de ello, queda asentada la presente acta ";

        $pdf->Ln(10);
        $pdf->SetFont( "Arial", "", 10 );
        $pdf->Cell( 174, 5, toUTF($cadenaFe), 0, 'J' );

                 
        $pdf->Ln( 4 );
        $pdf->SetFont( "Arial", '', 10 );
                

        $jurado1 = $this->dbRepo->inDocenteEx( $tram->IdJurado1 );
        $jurado2 = $this->dbRepo->inDocenteEx( $tram->IdJurado2 );
        $jurado3 = $this->dbRepo->inDocenteEx( $tram->IdJurado3 );
        $jurado4 = $this->dbRepo->inDocenteEx( $tram->IdJurado4 );

        $pdf->Ln(4);
        $pdf->Cell( 50, 5, "Presidente", 0, 0, "L" );
        $pdf->Cell( 100, 5, ": " .toUTF($jurado1), 0, 1, "L" );

        $pdf->Cell( 50, 5, "Primer Miembro", 0, 0, "L" );
        $pdf->Cell( 100, 5, ": " .toUTF($jurado2), 0, 1, "L" );

        $pdf->Cell( 50, 5, "Segundo Miembro", 0, 0, "L" );
        $pdf->Cell( 100, 5, ": " .toUTF($jurado3), 0, 1, "L" );

        $pdf->Cell( 50, 5, "Asesor", 0, 0, "L" );
        $pdf->Cell( 100, 5, ": " .toUTF($jurado4), 0, 1, "L" );
       
        
        $pdf->Ln(8);
        $pdf->SetFont( "Arial", "B", 11 );
        $pdf->MultiCell( 174, 5.5, toUTF("Puno, $mes de $ano"), 0, 'R' );


        $pdf->Output();
    }

    public function coo()
    {
        /*
        $config = array(
            'protocol' => 'smtp',
            'smtp_host' => 'smtp.1and1.com',
            'smtp_port' => 25,
            'smtp_user' => 'certificacion@gmail.com',
            'smtp_pass' => 'mapa4violeta',
            'charset' => 'utf-8',
            'priority' => 1
        );

        $this->load->helper('url');
        $this->load->library('email' );

        $this->email->initialize($config);
        $this->email->set_mailtype("html");
        $this->email->set_newline("\r\n");

        //Email content
        $htmlContent = '<h1>Sending email via SMTP server</h1>';
        $htmlContent .= '<p>This email has sent via SMTP server from CodeIgniter application.</p>';

        $this->email->to('rplm.mx@gmail.com');
        $this->email->from('certificacion@gmail.com','My email');
        $this->email->subject('How to send email via SMTP server in CodeIgniter');
        $this->email->message($htmlContent);

        //Send email
        $this->email->send();
        */



        /*
        //echo CI_VERSION;
        //exit;

        //$CI = & get_instance();

        $this->load->helper('url');
        $this->load->library('session');

        $this->config->item('base_url');
        $this->load->library('email' );

        //$this->email->initialize($config);
        /*
        $config['protocol'] = 'sendmail';
        $config['mailpath'] = '/usr/sbin/sendmail';
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE;
        $this->email->initialize($config);
        */

        /*
        $subject = 'Bienvenido a mi app';

        $msg = 'Mensaje de prueba xxx';

        $this->email
            ->from('certificacion@gmail.com')
            ->to($email)
            ->subject($subject)
            ->message($msg)
            ->send();
        */
    }    


    /*
    //  Mi tesis en UN PosTER
    public function vwInsqPoster()
    {
        $sess = $this->gensession->GetSessionData();
        $this->load->view("pilar/tes/poster/inscripcion",array('sess'=>$sess));
        // $this->load->view("pilar/tes/poster/fin");
    }
    
    public function execPostulaPoster()
    {
        $sess = $this->gensession->GetSessionData();

        $resum = mlSecurePost( "resumen" );
        $titulo = mlSecurePost( "titulo" );

        $tram=$this->dbPilar->inTramByTesista($sess->userId);
        $ord=$this->dbPilar->getOneField("2posTer","Ord","Id>0 ORDER BY Id DESC")+1;

        $codigo=sprintf("POS%03s", $ord );

        if(!$this->dbPilar->getSnaprow("2posTer","IdProyecto=$tram->Id"))
        {
            $this->dbPilar->Insert("2posTer", array(
                'IdProyecto'=>$tram->Id,
                'IdCarrera'=>$sess->IdCarrera,
                'Ord'=> $ord,
                'Codigo'=>$codigo,
                'Titulo'=>$titulo,
                'Resumen'=>$resum,
                // 'Poster'=>"$codigo.pptx",
                'Fecha'=>mlCurrentDate(),
            ));

            $msg= "<center><img  width='250px'src='http://vriunap.pe/vriadds/vri/web/logo_footer.png'></img></center><b>Postulación Aceptada</b><br><br>Estimado(a), Tesista Bienvenido al concurso MI PROYECTO DE TESIS EN UN POSTER.<br><br>Recuerde que en la
                presentación oral usted deberá presentar un poster teniendo en cuenta las pautas de la capacitación la cual se publicará en la página web del vicerrectorado de investigación. Usted puede verificar su inscripción en la web de la convocatoria : <a href='http://vriunap.pe/poster'><i> Ver Inscritos</i></a> ";
            $this->logCorreo( $tram->Id, $sess->userMail, "Inscripcion MI TESIS EN UN POSTER ", $msg );
            // $this->logCorreo( $tram->Id, "torresfrd@gmail.com", "Inscripcion TESIS EN UN POSTER ", $msg );

            echo "<div class='alert alert-success text-center'>
                  <h2><strong>Inscripción Finalizada</strong></h2> <h5>Estimado tesista tu postulación ha sido registrada con éxito.</h5>.
                </div>";
        }else{
                echo "<div class='alert alert-danger text-center'>ERROR :<br> Tienes Inconvenientes para la inscripción Intenta Nuevamente</h3> </div>";
        }

    }

    //  Tesis 3 Minutos
    public function vwInsq3mt()
    {
        $sess = $this->gensession->GetSessionData();
        echo "Inscripción Finalizada";
        // $this->load->view("pilar/tes/3mt/inscripcion",array('sess'=>$sess));
    }

    public function execPostula3MT()
    {
        $sess  = $this->gensession->GetSessionData();
        $resum  = mlSecurePost( "resumen" );
        $titulo = mlSecurePost( "titulo" );

        $tram = $this->dbPilar->inTramByTesista($sess->userId);
        $ord  = $this->dbPilar->getOneField("3mtPostul","Ord","Id>0 ORDER BY Id DESC")+1;

        $codigo  = sprintf("T%03s", $ord );
        $codigo1 = sprintf("LP%03s", $ord );

        $ppita = $this->uploaddf($codigo);

        // $potito=$this->uploadfoto($codigo1);
        if($ppita!=null)
        {
            if(!$this->dbPilar->getSnaprow("3mtPostul","IdTesista=$sess->userId"))
            {
                $this->dbPilar->Insert("3mtPostul", array(
                        'IdTesista'=>$sess->userId,
                        'IdCarrera'=>$sess->IdCarrera,
                        'Ord'=> $ord,
                        'Codigo'=>$codigo,
                        'Titulo'=>$titulo,
                        'Resumen'=>$resum,
                        'Archivo'=>"$codigo.pptx",
                        'Fecha'=>mlCurrentDate(),
                    ));
            }

            $msg= "<center><img  width='250px'src='http://vriunap.pe/vriadds/vri/web/convocatorias/curso1-3mt.jpg'></img></center><b>Postulación Aceptada</b><br><br>Señor Tesista Bienvenido a Tesis en Tres Minutos UNA-Puno (3MT®).<br><br>Recuerde que en la
                    presentación oral usted deberá explicar de forma convincente, concisa y clara su investigación. Usted puede verificar su inscripción en la web de la convocatoria : <a href='http://vriunap.pe/tesis3minutos'><i> Ver Inscritos</i></a> ";
            $this->logCorreo( $tram->Id, $sess->userMail, "Inscripcion 3MT ", $msg );
                // $this->logCorreo( $tram->Id, "torresfrd@gmail.com", "Inscripcion 3MT ", $msg );

            echo "<div class='alert alert-success text-center'>
                  <h2><strong>Inscripción Finalizada</strong></h2> <h5>Estimado tesista tu postulación ha sido registrada con éxito.</h5>.
                 </div>";
        }else{
                echo "<div class='alert alert-danger text-center'>ERROR :<br> Tienes Inconvenientes para la inscripción Intenta Nuevamente</h3> </div>";
        }


    }

    public function uploaddf($nombre)
    {
        $sess = $this->gensession->GetData();
        $config['upload_path']   = './repositor/tesis3m/';
        $config['allowed_types'] = 'ppt|pptx';  // ext
        $config['max_size']      = '6144';         // KB
        $config['overwrite']     = TRUE;
        $config['file_name']     = "$nombre";

        // finalmente subir archivo
        $this->load->library('upload', $config);
        if ( !$this->upload->do_upload("nomarch") ) { // input field

            $data['uploadError'] = $this->upload->display_errors();
            //echo "<div class='alert alert-danger text-center'><h2>Error:</h2> " . $this->upload->display_errors()."<h3><br> Tienes Inconvenientes para la inscripción Intenta Nuevamente</h3> </div>";
            return null;

        } else {
            $file_info = $this->upload->data();
            echo "Archivo Subido <br>";
            return $file_info;

        }

        // devolvemos el nombre del archivo
        return  $config['file_name'];
    }

    public function uploadfotoS($nombre)
    {
        $sess = $this->gensession->GetData();
        $config2['upload_path']   = './repositor/tesis3m/';
        $config2['max_size']      = '9144';         // KB
        $config2['allowed_types'] = 'png';  // ext
        $config2['overwrite']     = TRUE;
        $config2['max_width'] = '1024';
        $config2['max_height'] = '768';
        $config2['file_name']     = "$nombre.png";

        // finalmente subir archivo
        $this->load->library('upload', $config2);
        if ( !$this->upload->do_upload("nomphot") ) { // input field

            $data['uploadError'] = $this->upload->display_errors();
            // echo "<div class='alert alert-danger text-center'><h2>Error:</h2> " . $this->upload->display_errors()."<h3><br> Tienes Inconvenientes para la inscripción Intenta Nuevamente</h3> </div>";
            return null;

        } else {
            $this->upload->data();
            echo "Archivo Subido <br>";
            return $file_info;
        }

        // devolvemos el nombre del archivo
        // return  $config['file_name'];
    }
    */
}

//- EOF

