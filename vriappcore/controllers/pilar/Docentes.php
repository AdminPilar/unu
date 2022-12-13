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
date_default_timezone_set('America/Lima'); //Agregado unuv1.0


class Docentes extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->model('dbPilar');
        $this->load->model('dbRepo');

        $this->load->library("GenSession");
        $this->load->library("GenMailer");
		$this->load->library("GenSexPdf");
        $this->load->library("GenApi");
    }

    //Modificado unuv1.0 -- (4.1.2)
    public function login()
    {
		//echo '[{"error":true, "msg":"Servidor en actualización"}]';
		//return;

        $mail = mlSecurePost("mail");
        $pass = mlSecurePost("pass");
        $ldni = $mail;
        if( !$mail ) return;


        // Area de ingreso por DNI
        if( is_numeric($ldni) ) {

            /*
            $row = $this->dbPilar->getSnapRow( "vxDatDocentes", "DNI='$ldni'" );
            if( $pass != "SanTutix" ) {
                echo '[{"error":true, "msg":"Ingreso no concedido"}]';
                return;
            }*/

            $row = $this->dbPilar->loginByDNI( "vxDatDocentes", $ldni, sqlPassword($pass) );
            if( ! $row ) {
                echo '[{"error":true, "msg":"Datos incorrectos"}]';
                return;
            }



        } else {


            // verificar existencia de correo
            if( ! $this->dbPilar->getSnapRow( "vxDatDocentes", "Correo='$mail'" ) ) {
                echo '[{"error":true, "msg":"Este Correo no está registrado"}]';
                return;
            }

            // ahora si comprobar cuenta
            $row = $this->dbPilar->loginByMail( "vxDatDocentes", $mail, sqlPassword($pass) );
            if( ! $row ) {

                $IdDocente = $this->dbPilar->getOneField( "vxDatDocentes", "IdDocente", "Correo='$mail'"  );
                $this->logLogin( $IdDocente, "Clave incorrecta" );
                echo '[{"error":true, "msg":"Su clave es incorrecta"}]';
                return;
            }



            // 5 - jefaturas :: 6 - Ordinarios
            if( $row->Activo <= 4 ) {
                $estado = $this->dbRepo->getOneField( "dicEstadosDoc", "Nombre", "Id=$row->Activo" );
                $this->logLogin( $row->IdDocente, "$estado" );
                echo '[{"error":true, "msg":"Sin Acceso: '.$estado.'"}]';
                return;
            }
        }

        //----------------------------------------------------------------
        // como todo esta correcto creamos la sesion usuario general
        //----------------------------------------------------------------
        $this->gensession->SetUserLogin(
            'docentes',
            $row->IdDocente,
            $row->DatosPers,
            $row->Correo,
            $row->DNI,
            $row->Codigo,
            $row->IdCarrera
        );
        $this->logLogin( $row->IdDocente, "Ingreso" );

        echo '[{"error":false, "msg":"OK, Estamos redireccionando..."}]';
    }

    // Salir de Docentes
    public function logout() {

        $this->gensession->SessionDestroy();
        redirect( base_url("pilar"), 'refresh');
    }

    /*
    public function ver()
    {
        $this->gensession->IsLoggedAccess();

        $sess = $this->gensession->GetSessionData();
        echo "$sess->userDesc :: $sess->userName :: $sess->IdCarrera";
    }*/


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
                'Tipo'    => 'D',
                'IdUser'  => $idUser,
                'Accion'  => $obs,
                'Browser' => $agent,
                'IP'      => mlClientIP(),
                'OS'      => $this->agent->platform(),
                'Fecha'   => mlCurrentDate()
            ) );
    }

    private function logCorreo( $idUser, $correo, $titulo, $mensaje )
    {
        $this->genmailer->mailPilar( $correo, $titulo, $mensaje );

        $this->dbPilar->Insert( 'logCorreos', array(
                'IdDocente' => $idUser,
                'IdTesista' => 0,
                'Fecha'   => mlCurrentDate(),
                'Correo'  => $correo,
                'Titulo'  => $titulo,
                'Mensaje' => $mensaje
            ) );
    }
    private function logCorreotesista( $idUser, $correo, $titulo, $mensaje )
    {
        $this->genmailer->mailPilar( $correo, $titulo, $mensaje );

        $this->dbPilar->Insert( 'logCorreos', array(
                'IdDocente' => 0,
                'IdTesista' => $idUser,
                'Fecha'   => mlCurrentDate(),
                'Correo'  => $correo,
                'Titulo'  => $titulo,
                'Mensaje' => $mensaje
            ) );
    }

	// obvio que el Usuario actual
    private function logTramites( $tram, $accion, $detall )
    {
		$this->gensession->IsLoggedAccess();
        $sess = $this->gensession->GetData();

        $this->dbPilar->Insert(
            'logTramites', array(
                'Tipo'      => 'D',      // T D C A
                'IdUser'    => $sess->userId, // $idUser,
                'IdTramite' => $tram,
                'Quien'     => 'Pilar',
                'Accion'    => $accion,
                'Detalle'   => $detall,
                'Fecha'     => mlCurrentDate()
        ) );
    }

    private function logTramitespilar( $tram, $accion, $detall )
    {
        $this->gensession->IsLoggedAccess();
        $sess = $this->gensession->GetData();

        $this->dbPilar->Insert(
            'logTramites', array(
                'Tipo'      => 'P',      // T D C A
                'IdUser'    => $sess->userId, // $idUser,
                'IdTramite' => $tram,
                'Quien'     => 'Pilar',
                'Accion'    => $accion,
                'Detalle'   => $detall,
                'Fecha'     => mlCurrentDate()
        ) );
    }


    //----------------------------------------------------------------------
    //----------------------------------------------------------------------

    //Modificado unuv1.0 -- (4.1.3)
    public function index()
    {
        if( mlPoorURL() )
            redirect( mlCorrectURL() );

        // peek session Gral Usuario activado
        // en caso de admin crear nueva session admin por App
        //
        $sess = $this->gensession->GetData();

        if( !$sess ){
            redirect( base_url("pilar"), 'refresh');
            return;
        }

        // otra que no sea sesion de docente X
        if( $sess->userDesc != "docentes" ) {

            // cerrar session incorrecta
            $this->logout();
            return;
        }
        //agregado unuv1..0 - recuperacion de contraseña tesista
        $row = $this->dbRepo->getSnapRow("tbldocentes","Id='$sess->userId'"); //busca datos del tesista
    if($row->Clave == $row->Clavealeatorio) //modificado 24/02/2021 - modificado unuv2.0
        {
            $this->load->view('pilar/doc/header');
            $this->load->view("pilar/doc/RecuperarPass");
         }
        else
        {
            $this->load->view('pilar/doc/header');
            $this->load->view('pilar/doc/menu', array('sess'=>$sess) );
            $this->load->view('pilar/doc/panel');
        }  


       
        //print_r( $_SESSION );
    }

    //------agregado unuv2.0---------
    public function jsRestablecerdoc()
    {
        $email= mlSecurePost("mailRecuperaciondoc");
        if($row = $this->dbRepo->getSnapRow("tbldocentes","Correo='$email'"))
        {
            $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $claveNueva ='UNU'.substr(str_shuffle($permitted_chars), 0, 6);
           
            $this->dbRepo->Update( 'tbldocentes', array(
                'Clave'    => sqlPassword($claveNueva),
                'Clavealeatorio'    => sqlPassword($claveNueva)
            ), $row->Id);
            $msg = "Estimado(a) Docente $row->Nombres.<br><br>"
              ."Solicitó un restablecimiento de contraseña para el ingreso a la Plataforma PILAR <br>"
             . "Contraseña : <b>$claveNueva</b><br><br>"
             . "Nota : La plataforma PILAR solicitara el cambio de contraseña para mayor seguridad. 
                <br><br> *Este es un mensaje automático, no responda por favor."  ;
            $msgcel = "Estimado(a) Docente $row->Nombres.\n"
            ."Solicitó un restablecimiento de contraseña para el ingreso a la Plataforma PILAR. \n"
           . "Contraseña : $claveNueva                      
            \n".date("d-m-Y")." \nVRI - Plataforma PILAR UNU.  
            \n*Este es un mensaje automático, no responda por favor.";
            $this->logLogin( $row->Id, "Restablecer Contraseña PILAR");            
            $resCel='';
            $cel= $this->dbRepo->inCelu($row->Id);
            $resCel= $this->NotificacionCelular($cel,$msgcel);
            $this->logCorreo($row->Id, $row->Correo, "Restablecer Contraseña PILAR", $msg.$resCel);
            echo "La nueva contraseña ha sido enviado a tu correo electronico y numero de celular registrado.";

        }
        else
        {
            echo "";
            return;
        }
    }
    //------ fin agregado unuv2.0---------
    //------- agregado unuv2.0-------------
    public function NotificacionCelular($cel,$mensaje)
    {
        $this->load->library('apismss'); 
        $number   = "0051$cel";
        $result   = $this->apismss->sendMessageToNumber2($number,$mensaje);
        if ($result) {
        return "Mensaje Enviado al $number";
        }else{
        return  "Error al enviar mensaje : $number";
        }
    }
    //-------fin agregado unuv2.0-------------

    //Agregado unuv1.0 - recuperacion de contraseña docente - modificacion unuv2.0
    public function CambiarPass()
    {
        $this->gensession->IsLoggedAccess();
        $sess = $this->gensession->GetData();
        if(!$sess){ echo "Por favor Logearse para poder realizar la modificación ";}        
        $passCambio = mlSecurePost( "passCambio" );
        $passCambio2 = mlSecurePost( "passCambio2" );       
        if($passCambio == $passCambio2)
        {            
            if($row = $this->dbRepo->getSnapRow("tbldocentes","Id='$sess->userId'"))
            {                              
                    $this->dbRepo->Update( "tbldocentes", array(
                        'Clave'    =>sqlPassword($passCambio),
                        'Clavealeatorio' => NULL
                    ), $row->Id);

                    $msg=  "Estimado(a) Docente .$row->Nombres.\n"
                    .'Se realizo exitosamente el cambio de contraseña. :   <br><b> Su nueva contraseña es :'.$passCambio.'</b>'          
                    ."<br><br> *Este es un mensaje automático, no responda por favor." ;

                    $msgcel = "Estimado(a) Docente " .$row->Nombres."\n"
                    ."Se realizo exitosamente el cambio de contraseña. \n
                    \n".date("d-m-Y")." \nVRI - Plataforma PILAR UNU.  
                    \n*Este es un mensaje automático, no responda por favor.";
                    $resCel='';
                    $cel= $this->dbRepo->inCelu($row->Id);
                    //$resCel= $this->NotificacionCelular($cel,$msgcel);
                    $this->logCorreo( $row->Id, $row->Correo, "Cambio de contraseña", $msg.$resCel);
                    $this->logLogin( $row->Id, "Cambio de contraseña" );
                    echo "";
                    return;                
            }
        }
        echo "La nuevas contraseñas no coinciden ";
    }


    // Información del Docente , Update Docente , Update Lineas de Investigación
    //Modificacion unuv1.0 --(4.2.1)
    public function infoDocente()
    {
		$this->gensession->IsLoggedAccess();
        $sess = $this->gensession->GetData();

        $carre = $sess->IdCarrera;
		if( $sess->IdCarrera == 19 )
			$carre = 18;

        $perDoc = $this->dbRepo->getSnapRow("vwDocentes","Id=$sess->userId");
		$lineas = $this->dbPilar->getTable("vxLineas","IdCarrera=$carre");
        $linDoc = $this->dbPilar->getTable("docLineas","IdDocente=$sess->userId");
        $graDoc = $this->dbPilar->getTable("docEstudios","IdDocente=$sess->userId");
        $idxDoc = $this->dbRepo->getSnapRow("dicDocIndex","IdDocente=$sess->userId");

        $this->load->view("pilar/doc/infoDoc", array(
            'datDoc' => $perDoc ,
            'linDoc' => $linDoc,
            'lineas' => $lineas,
            'grad'   => $graDoc,
            'idxs'   => $idxDoc
        ) );
		// $this->load->view("pilar/doc/infoDoc", array('datDoc'=>$perDoc,'lineas'=>$lineas) );
    }

    public function infoTrams( $tipo=0 )
    {
		$this->gensession->IsLoggedAccess();
        $sess = $this->gensession->GetData();

        if( $tipo<=0 && $tipo>=4 ) return;


        $tipoName = array(1 =>'Proyectos' , 2=>'Borradores',3=>'Sustentaciones' );
        // Nota: FechModif sera importante para la bandeja se mostrar el
        // tramite ultimo modificado, controlado claro.
        //  al subir, enviar a Asesor, al sortear y revisar, al subir corr
        //  al aprobar sacar de bandeja y poner en actas


        // E: 1, 2, 3, no mostrar a todos

        $tproys = $this->dbPilar->getTable( 'tesTramites',
			"(Estado>=2) AND " .
            "( Tipo = $tipo ) AND (         " .
            "IdJurado1='$sess->userId'  OR  " .
            "IdJurado2='$sess->userId'  OR  " .
            "IdJurado3='$sess->userId'  OR  " .
            "IdJurado4='$sess->userId'  )  ORDER BY FechModif DESC" );


        $this->load->view( "pilar/doc/infoTramite", array(
                'tproys' => $tproys,
                'sess'   => $sess,
                'tipo'  => $tipoName[$tipo]
            ) );
    }


	//----------------------------------------------------------------------------------------
	// funciones AJAX
	//----------------------------------------------------------------------------------------
    //modificacion unuv1.0 - Estado aceptacion y/o rechazo proyecto
    //modificacion unuv1.0 - Estado revision 1
    //modificacion unuv1.0 - Estado revision 2
    //modificacion unuv1.0 - Estado revision 3
    //modificacion unuv1.0 - Estado dictamen
	public function corrProys( $idtram=0, $height )
	{
		$this->gensession->IsLoggedAccess();
		$sess = $this->gensession->GetData();

		if( !$idtram ) return;

		$tram = $this->dbPilar->inProyTram( $idtram );
		if( ! $tram ) {
			echo "Sin Tramite";
			return;
		}

		/////$dets = $this->dbPilar->inTramDetIter( $idtram, 1 ); // 1ra
		$dets = $this->dbPilar->inLastTramDet( $idtram ); // N last

		if( ! $dets ) {
			echo "Sin Detalle Tram (1)";
			return;
		}

		$linkPdf = base_url( "repositor/docs/$dets->Archivo" );

		echo "<div class='col-md-9'>";
		echo " <iframe id='frmpdf' name='frmpdf' src='$linkPdf' frameborder=0 width='100%' height='$height px'></iframe>";
		echo "</div>";

		echo "<div id='lisPan' class='col-md-3' style='padding-left: 0px; padding-right: 22px'>";

		if( $tram->Estado == 2 ) { //unuv1.0 Estado aceptcaion y/o rechazo proyecto

			echo '<h4 class="titulo"> Proyecto para Asesor </h4>';
            echo "<b>Codigo Proyecto :</b> " .  $tram->Codigo . "<br>";
			//echo "<b>Tesista(s) :</b> " . $this->dbPilar->inTesistas( $idtram ) . "<br>"; comentado unuv1.0
			echo "<b>Linea de Inv.:</b> " . $this->dbRepo->inLineaInv( $tram->IdLinea ) . "<br>";
			echo "<b>Escuela Profesional :</b> " . $this->dbRepo->inCarrera( $tram->IdCarrera ) . "<br>";
			echo "<br>Estimado Docente. ¿Ud. desea aceptar la Asesoria del presente proyecto de Tesis? ";
            echo "<br> <p class='text-danger'><b>Nota :</b> Asegúrese que le proyecto de tesis no supere el 10% en el Sistema Antiplagio.</p><br><br>";
			echo "<button onclick='grabEvent($tram->Id,21)' type='button' class='btn btn-success'> SI, Acepto </button> | ";
			echo "<button onclick='grabEvent($tram->Id,10)' type='submit' class='btn btn-warning'> No acepto </button> | ";
			echo "<button type='button' class='btn btn-danger' data-dismiss='modal'> [x] Salir </button>";
		}

		if( $tram->Estado == 4 || $tram->Estado == 5 || $tram->Estado == 6 ) {     //modificacion unuv1.0 - Estado revision 1,2

            $pos = $this->dbPilar->inPosJurado( $tram, $sess->userId );
            $chk = ($pos==1)? $dets->vb1 : (($pos==2)? $dets->vb2 : (($pos==3)? $dets->vb3:0));
            // Revisar que funcione para 4
            // $chk = ($pos==1)? $dets->vb1 : (($pos==2)? $dets->vb2 : (($pos==3)? $dets->vb3:(($pos==4)? $dets->vb4:0)));


            $eve = "onclick='if(confirm(\"Esta acción cerrará el proceso de observaciones y las envia al tesista.\")) grabEvent($tram->Id,41)'";

			// aqui evento grabar correcciones
			//
            if( $chk == 0 ) {
                 echo  "<form method=post id='frmkrs' name='frmkrs' onsubmit='grabCorrs(); return false'>";
                 echo    "<input type=hidden id='kidt' name='kidt' value='$tram->Id'>";
                 echo    "<input type=hidden name='idoc' value='$sess->userId'>";
                 echo    "<textarea id='korec' name='korec' type='text' class='form-control' rows=4 placeholder='Ingrese una corrección, no mas de 5 lineas' required></textarea>";
                 echo    "<br>";
                 echo    " <p class='text-success'> <b>Nota 1:</b> En caso tenga observacione o cuando termine de realizar las observaciones no se olvide de finalizar </p>";
                 echo    " <p class='text-danger'> <b>Nota 2:</b> En caso no tenga observaciones el proyecto, dar en finalizar y se considera aprobado por Ud.</p> <br>";
                 echo    "<center>";
                 echo    "<button type='submit' class='btn btn-success'> Añadir Observacion </button> | ";
                 echo    "<button $eve type='button' class='btn btn-warning'> Finalizar Observacion </button> | ";
                 echo    "<button type='button' class='btn btn-danger' data-dismiss='modal'> Cerrar Ventana </button>";
                 echo    "</center>";
                 echo  "</form>";
                //echo "<br><img class='img-responsive' src='http://vriunap.pe/vriadds/vri/web/convocatorias/comunicadoenero.png'</h4>";
            }
            else if($chk==2) //agregado unuv1.0 - para el docente que aprobo la tesis
            {
                echo "<h4> Ud. Ya aprobo el proyecto de tesis </h4>";
                echo  "<button type='button' class='btn btn-danger' data-dismiss='modal'> [X] Salir  </button>";

            }
            else {
                echo "<h4> Ud. ha finalizado las observaciones </h4>";
                echo "<button type='button' class='btn btn-danger' data-dismiss='modal'> [x] Cerrar Ventana </button>";
            }

			$hgt = ($height - 174) . "px";
			echo  "<div id='lisCorr' style='overflow-y:auto; height:$hgt; margin-top: 10px; padding-top: 5px; border-top: 4px solid orange'>";

			// mostrar las puñeteras correcciones joder !
            $this->listarCorrecs( $tram->Id, $sess->userId, 3); //agregado unuv1.0 
            $this->listarCorrecs( $tram->Id, $sess->userId, 2 );//agregado unuv1.0 
			$this->listarCorrecs( $tram->Id, $sess->userId, 1 );
			echo  "</div>";
		}


		if( $tram->Estado == 7 ) {

			// etapa de dictaminacion del proyecto
			//
			echo  '<h4 class="titulo"> Dictaminación de proyecto de tesis </h4>';
			echo  "<form method=post id='frmkrs' name='frmkrs' onsubmit='grabTCorrs(); return false'>";
			// echo    "<input type=hidden name='kidt' value='$tram->Id'>";
			// echo    "<textarea id='korec' name='korec' type='text' class='form-control' rows=4 placeholder='Ingrese una corrección, no mas de 5 lineas' required></textarea>";
            // ***DE AQUI HASTA ALLI PARA BLOQUEAR DICTAMINACIONES
			echo  "Estimado Docente. ¿Ud. <b>aprueba</b> o <b>desaprueba</b> el presente proyecto de Tesis? <br><br>";
			echo  "<button onclick='grabEvent($tram->Id,51)' type='button' class='btn btn-success'> Aprobar </button> | ";
			echo  "<button onclick='grabEvent($tram->Id,50)' type='button' class='btn btn-warning'> Desaprobar </button> | ";
			echo  "<button type='button' class='btn btn-danger' data-dismiss='modal'> [X] Salir  </button>";
			echo  "</form>";
            //*** AQUI ES ALLI
            // echo "<br><img class='img-responsive' src='http://vriunap.pe/vriadds/vri/web/convocatorias/comunicadoenero.png'</h4>";
			$hgt = ($height - 164) . "px";
			echo  "<div id='lisCorr' style='overflow-y:auto; height:$hgt; margin-top: 10px; padding-top: 5px; border-top: 4px solid orange'>";

			// mostrar las puñeteras correcciones joder !
			$this->listarCorrecs( $tram->Id, $sess->userId, 3 );//agregado unuv1.0
            $this->listarCorrecs( $tram->Id, $sess->userId, 2 );//agregado unuv1.0
            $this->listarCorrecs( $tram->Id, $sess->userId, 1 );//agregado unuv1.0
			echo  "</div>";
		}

        if( $tram->Estado == 14 ) {

            // DICTAMEN DE SUSTENTACIÓN
            echo  '<h4 class="titulo"> Dictamen de Sustentación </h4>';
            echo  "<form method=post id='frmkrs' name='frmkrs' onsubmit='grabTCorrs(); return false'>";
            // echo    "<input type=hidden name='kidt' value='$tram->Id'>";
            // echo    "<textarea id='korec' name='korec' type='text' class='form-control' rows=4 placeholder='Ingrese una corrección, no mas de 5 lineas' required></textarea>";
            // ***DE AQUI HASTA ALLI PARA BLOQUEAR DICTAMINACIONES
            echo  "Estimado Docente. ¿Cual es la calificación que Ud. asignaría ( <b>Aprobado con Distinción , Aprobado </b> o <b>Desaprobado</b>) a la presente exposición y defensa de Tesis? <br><br>";
            echo  "<center><button onclick='grabEvent($tram->Id,132)' type='button' class='btn btn-info'> Aprobar con Distinción </button>|";
            echo  "<button onclick='grabEvent($tram->Id,131)' type='button' class='btn btn-info'> Aprobar </button>| ";
            echo  "<button onclick='grabEvent($tram->Id,130)' type='button' class='btn btn-info'> Desaprobar </button> <hr> ";
            echo  "<button type='button' class='btn btn-danger' data-dismiss='modal'> [X] Salir  </button> </center>";
            echo  "</form>";
            //*** AQUI ES ALLI
            // echo "<br><img class='img-responsive' src='http://vriunap.pe/vriadds/vri/web/convocatorias/comunicadoenero.png'</h4>";
            $hgt = ($height - 164) . "px";
            echo  "<div id='lisCorr' style='overflow-y:auto; height:$hgt; margin-top: 10px; padding-top: 5px; border-top: 4px solid orange'>";

            // mostrar las puñeteras correcciones joder !
            $this->listarCorrecs( $tram->Id, $sess->userId, 1 );
            echo  "</div>";
        }

		echo "</div>";
	}

    //modificacion unuv1.0 - Estado aceptacion y/o rechazo proyecto
     //modificacion unuv1.0 - Revision 1
     //modificacion unuv1.0 - Revision 2
      //modificacion unuv1.0 - Revision 3
    //modificacion unuv1.0 - estado dictaminacion
    public function aprobarodesaprobar($idtram)
    {
         $this->gensession->IsLoggedAccess();
        $sess = $this->gensession->GetData();
         $dets = $this->dbPilar->inLastTramDet( $idtram);
         $tram = $this->dbPilar->inProyTram( $idtram );


         if($dets->vb1!=0 && $dets->vb2!=0  && $dets->vb3!=0)
         {

         

         if($dets->vb1==2 && $dets->vb2==2  && $dets->vb3==2)
         {
            $this->dbPilar->Update( 'tesTramites', array(
                'Estado'    => 8,
                'FechModif' => mlCurrentDate()
            ) , $tram->Id );
          $titulo='Aprobación de Proyecto';

          $msg = "<h4> Felicitaciones </h4><br>"
             . "Su proyecto <b>$tram->Codigo</b>, ha sido aprobado ya puede visualizarlo"
             . " y descargarlo desde su cuenta de la <b>Plataforma PILAR</b>.";
          $msg1 = "<h4> Proyecto Aprobado</h4><br>"
             . "El proyecto <b>$tram->Codigo</b>, ha sido aprobado. ";
          $men=7;
          $this->logTramitespilar( $sess->userId, $tram->Id, $titulo, $msg );
           if($tram->IdTesista2 !=0)
            {
              $mail = $this->dbPilar->inCorreo( $tram->IdTesista1);
              $mail2 = $this->dbPilar->inCorreo( $tram->IdTesista2);
              $this->logCorreotesista( $tram->IdTesista1,$mail, $titulo, $msg );
              $this->logCorreotesista( $tram->IdTesista2,$mail2, $titulo, $msg );
               $cel= $this->dbPilar->inCelTesista( $tram->IdTesista1);
               $cel2= $this->dbPilar->inCelTesista( $tram->IdTesista2);
              /* $a=$this->notiCelu($cel, $men);
               $a=$a." - ".$this->notiCelu($cel2, $men);*/

            }
          else
            {
              $mail = $this->dbPilar->inCorreo( $tram->IdTesista1);
              $cel= $this->dbPilar->inCelTesista( $tram->IdTesista1);
              $this->logCorreotesista( $tram->IdTesista1, $mail,$titulo, $msg );
             //  $a=$this->notiCelu($cel, $men);
            }
        }
        else{

            if( $tram->Estado==7)
            {    
               

                  $fechPy = mlFechaNorm( $tram->FechRegProy );
                  $titulo='Desaprobación de Proyecto';
                  $this->dbPilar->Update( "tesTramites", array('Tipo'=>0), $tram->Id );
                   $msg = "El Proyecto de Tesis con código <b>$tram->Codigo</b> <br>\n"
                     . "presentado el <b>$fechPy</b> con </b><br>titulado: <b>".$row->Titulo."</b>.<br><br>\n\n"
                     . "Ha sido desaprobado, por lo que se procedera a archivar el presente trámite.<br><br>"
                     . "Por la presente le comunicamos que queda habilitada la cuenta para realizar un nuevo trámite.\n"
                     ; 
                   $msg1= $msg; 
                   $men=8;    
                   $this->logTramitespilar( $sess->userId, $tram->Id, $titulo, $msg );
                    if($tram->IdTesista2 !=0)
            {
                      $mail = $this->dbPilar->inCorreo( $tram->IdTesista1);
                      $mail2 = $this->dbPilar->inCorreo( $tram->IdTesista2);
                      $this->logCorreotesista( $tram->IdTesista1, $mail, $titulo, $msg );
                      $this->logCorreotesista( $tram->IdTesista2, $mail2, $titulo, $msg );
                       $cel= $this->dbPilar->inCelTesista( $tram->IdTesista1);
                       $cel2= $this->dbPilar->inCelTesista( $tram->IdTesista2);
                      /* $a=$this->notiCelu($cel, $men);
                       $a=$a." - ".$this->notiCelu($cel2, $men);*/

                    }
                  else
                    {
                      $mail = $this->dbPilar->inCorreo( $tram->IdTesista1);
                      $cel= $this->dbPilar->inCelTesista( $tram->IdTesista1);
                      $this->logCorreotesista( $tram->IdTesista1, $mail,$titulo, $msg );
                     //  $a=$this->notiCelu($cel, $men);
                    }           

           }


        }       

        }     
         //------------------Correo a los tesistas -------------
       
         




         
    }
	public function grabEvent( $idtram, $event )
	{
		$this->gensession->IsLoggedAccess();
		$sess = $this->gensession->GetData();

		$tram = $this->dbPilar->inProyTram( $idtram );
		if( !$tram ){ echo "No tram"; return; }

		//---------------------------------------------------------------- -------
		// aprobar o deaprobar proyecto
		//---------------------------------------------------------------- -------
		if( $event == 50 or $event == 51 ) { //unuv1.0 - estado dictaminacion

			$calif = ($event==50? -1 : 2);
			$posis = $this->dbPilar->inPosJurado( $tram, $sess->userId );
			$dets = $this->dbPilar->inLastTramDet( $idtram); //

			$msg = "<br>Procesando Proyecto : <b>$tram->Codigo</b>"
			     . "<br>Orden de Jurado : $posis"
			     . "<br>Iteracion : $dets->Iteracion";

			echo "$msg <hr>";

			// realizar la acción
			$this->dbPilar->Update( "tesTramsDet", array("vb$posis" => $calif), $dets->Id );
			$this->logTramites( $tram->Id, "Dictaminación de Jurado $posis", $msg );

            $this->aprobarodesaprobar($tram->Id);
           

			// sumar si es 3 aprobar inmediatamente pero no por ahora
		}
        if( $event == 130 or $event == 131 or $event == 132 ) {

            $calif = ($event==130? 0 : ($event==131? 1 : ($event==132?2 : -1)));
            $posis = $this->dbPilar->inPosJurado( $tram, $sess->userId );
            $dets = $this->dbPilar->inTramDetIter( $idtram, 5 );

            $msg = "<br>Procesando Proyecto : <b>$tram->Codigo</b> con <b>$calif</b>"
                 . "<br>Orden de Jurado : $posis"
                 . "<br>Iteracion : $dets->Iteracion";

            echo "$msg <hr>";

            // realizar la acción
            $this->dbPilar->Update( "tesTramsDet", array("vb$posis" => $calif), $dets->Id );
            $this->logTramites( $tram->Id, "Dictaminación de Jurado $posis : $calif", $msg );


            $dets = $this->dbPilar->inTramDetIter( $idtram, 5 );
            $total = $dets->vb1 + $dets->vb2 +$dets->vb3+$dets->vb4;
            if($total >= 3){
                $ptj=($total==8?"Aprobado con Distinción":($total>=3?"Aprobado":"Desaprobado"));
                $dict=($total==8?"2":($total>=3?"1":"0"));
                $sust=$this->dbPilar->getSnaprow('tesSustensSolic',"IdTramite=$tram->Id");
                $this->dbPilar->Update("tesSustensSolic",array('Estado'=>3),$sust->Id);
                $this->dbPilar->Update("tesTramites",array('Tipo'=>3),$sust->Id);
                $value=$this->dbPilar->getOneField('tesSustenAct',"Num"," IdTramite>0 ORDER BY Num DESC");
                $num=($value?$value:0);
                // 
                $sustentado=$this->dbPilar->getSnapRow("tesSustenAct","IdTramite=$tram->Id");
                if($sustentado){
                    echo "Acta registrada";
                    return;              
                }
                // 
                $this->dbPilar->Insert( "tesSustenAct", array(
                    'IdTramite' => $tram->Id,  ///// 1, 4,
                    'IdCarrera' => $tram->IdCarrera,
                    'Dictamen'  => $dict,
                    'Fecha' => mlCurrentDate(),
                    'Num'   => $num+1,
                    'Obs' =>$ptj
                ) );
            }
        }

        if( $event == 40 or $event == 41 ) { //Estado revision 1,2
            $iteracion=1; //agregado unuv1.0
			$posis = $this->dbPilar->inPosJurado( $tram, $sess->userId );
            if($tram->Estado ==5) //agregado revision 2
            {
                $iteracion=2;
            }
            if($tram->Estado ==6)
            {
                $iteracion=3;
            }

			$dets = $this->dbPilar->inTramDetIter( $idtram, $iteracion);


			$msg = "<br>Procesando Proyecto : <b>$tram->Codigo</b>"
			     //. "<br>Orden de Jurado : $posis"
			     . "<br>Iteracion : $dets->Iteracion";

			echo "$msg ";

			// agregado unuv1.0 - 
            $corr = $this->dbPilar->inNCorrecs( $idtram, $sess->userId, $iteracion);//verificar si el docente realizo alguna observacion

            if($corr == 0)
            {                
                $this->dbPilar->Update( "tesTramsDet", array("vb$posis" => 2), $dets->Id );  
                echo "<br> <b>Ud. Aprobo el proyecto </b>"; 

            }
            else
            {   
                $this->dbPilar->Update( "tesTramsDet", array("vb$posis" => 1), $dets->Id );
            }
			//$this->dbPilar->Update( "tesTramsDet", array("vb$posis" => 1), $dets->Id );
			$this->logTramites( $tram->Id, "Fin de Observaciones Jurado $posis", $msg );
             $this->aprobarodesaprobar($tram->Id);
		}
        if( $event == 69) {

            $posis = $this->dbPilar->inPosJurado( $tram, $sess->userId );
            $dets = $this->dbPilar->inLastTramDet( $idtram, 5 );
            $aproba='';
            if($tram->Estado ==11) //agregado revision 2
            {
                $iteracion=5;
            }
            if($tram->Estado ==12)
            {
                $iteracion=6;
            }
            if($tram->Estado ==13)
            {
                $iteracion=7;
            }

            if($tram->Estado<=8){
                 $msg = "<br>Procesando Proyecto : <b>$tram->Codigo</b>"
                 . "<br>Revision : $dets->Iteracion";
                 $aproba ="<br> <b>Ud. Aprobo el Proyecto de Tesis </b>"; 

            }else
            {
                 $msg = "<br> Borrador de Tesis procesado : <b>$tram->Codigo</b>"
                 . "<br>Revision Borrador :<b>".($dets->Iteracion-4)."</b>";
                 $aproba ="<br> <b>Ud. Aprobo el Borrador de Tesis </b>"; 

            }

            $corr = $this->dbPilar->inNCorrecs( $idtram, $sess->userId, $iteracion);//verificar si el docente realizo alguna observacion

            if($corr == 0)
            {                
                $this->dbPilar->Update( "tesTramsDet", array("vb$posis" => 2), $dets->Id );  
                echo  $aproba;

            }
            else
            {   
                $this->dbPilar->Update( "tesTramsDet", array("vb$posis" => 1), $dets->Id );
                echo "<br>Usted ha finalizado las observaciones";
            }
            
            $this->logTramites( $tram->Id, "Fin de Correcciones Borrador Jurado $posis", $msg );

           
            echo "$msg ";
            // agregado unuv1.0 - 
            
        }


		//---------------------------------------------------------------- -------
		// Modificacion unuv1.0 - Estado aceptacion de proyecto
		if( $event == 21 ) { 

			$this->dbPilar->Update( "tesTramites", array(
				'Estado'    => 3,
				'FechModif' => mlCurrentDate()
			), $idtram );
            $msg  = "<b>Saludos</b><br><br>El Asesor que Ud. eligió, ha aceptado su proyecto con codigo <b>".$tram->Codigo
              ."</b> y en un máximo de 2 dias calendarios serán sorteados sus jurados. ";		

			 //------------------Correo a los tesistas ------------- modificacion unuv1.0
             if($tram->IdTesista2 !=0)
             {
               $mail = $this->dbPilar->inCorreo( $tram->IdTesista1);
               $mail2 = $this->dbPilar->inCorreo( $tram->IdTesista2);
               $cel= $this->dbPilar->inCelTesista( $tram->IdTesista1);
               $cel2= $this->dbPilar->inCelTesista( $tram->IdTesista2);
               $this->logCorreo( $sess->userId, $mail, "Aceptación de Asesor", $msg );
               $this->logCorreo( $sess->userId, $mail2, "Aceptación de Asesor", $msg );
            //   $a=$this->notiCelu($cel,2);
            //   $a=$a." - ".$this->notiCelu($cel2,2);
             }
           else
             {
               $mail = $this->dbPilar->inCorreo( $tram->IdTesista1);
                $cel= $this->dbPilar->inCelTesista( $tram->IdTesista1);
               $this->logCorreo( $sess->userId, $mail, "Aceptación de Asesor", $msg );
            //   $a=$this->notiCelu($cel,2);
             }
             $a='';
             $msg=$msg.$a;
           //........................................
			$this->logTramites( $tram->Id, "Aceptación del Asesor", $msg );

			// mensaje y salida con actualizacion de ventana
			echo "<hr><p>El proyecto <b>$tram->Codigo</b> Ha sido enviado para sorteo. Gracias por su tiempo.</p><br>";
		}

        if( $event == 10 ) { 

            echo "<form  method='post' id='cora' name='cora' onsubmit='rechazarProyecto()' >
            <div class='form-group col-md-12'>                   
                <input type=hidden id='idtra' name='idtra' value='$tram->Id'>
                <label for='comment'>Mensaje a enviar:<small class='help-block'>Indique el motivos por lo que el proyecto es rechazado.</small></label> <br> 
                <textarea class='col-md-12 input-sm' name='msg' id='msg'rows='9' placeholder='Ingrese Motivos' required ></textarea>
            </div>
            <div class='form-group col-md-12'>
             <button type='submit' class='btn btn-success'> Enviar </button>   
            <button onclick='closeDlg(\"docentes/infoTrams/1\")' type='button' class='btn btn-danger'> [x] Salir </button> 
            </div>
        </form>";        
        }
        // mensaje general con refrezcado de ventana
        //
        echo "<hr>";
        if($event!=10){
            echo " <button onclick='closeDlg(\"docentes/infoTrams/$tram->Tipo\")' type='button' class='btn btn-danger'> [x] Salir </button>";
        }
            
	}


    public function notiCelu($cel,$tip)
    {
     $this->load->library('apismss'); 
     $number   = "0051$cel";
     if($tip==1){ // Tesista : mensaje rechazo del proyecto
       $mensaje  = "UNU -PILAR \nEstimado(a) Tesista su proyecto fue rechazado por su Asesor, revisar su correo para mas detalles del rechazo. \nDebera corregir y coordinar personalmente para realizar nuevamente su tramite en la plataforma PILAR  en http://pilar.unu.edu.pe/unu/pilar  \n\n".date("d-m-Y")."\nPlataforma PILAR.";
    }
    else if($tip==2){
       $mensaje  = "UNU PILAR \nEstimado(a) Tesista el Asesor que Ud. eligió, aceptó su proyecto y en un máximo de 2 dias calendarios serán sorteados sus jurados.  \n\n".date("d-m-Y")."\nPILAR.";
    }   
    else{
       $mensaje  = "UNU PILAR \nEstimado(a) Docente se le recuerda revisar, la plataforma PILAR en http://pilar.unu.edu.pe/unu/pilar y verificar los proyectos y borradores pendientes.\n\n".date("d-m-Y")."\nPILAR.";
    }
    $result   = $this->apismss->sendMessageToNumber2($number,$mensaje);

        if ($result) {
           return "Mensaje Enviado al $number";
        }else{
           return  "Error al enviar mensaje : $number";
        }
    }


     //Agregado unuv1.0 - Rechazo del proyecto 
     public function rechazarProyecto()    
     {
         $this->gensession->IsLoggedAccess();
         $sess = $this->gensession->GetData();
          $idtram = mlSecurePost( "idtra" );
          $msg = mlSecurePost( "msg" );
         if( !$idtram ) return;
 
         $tram = $this->dbPilar->inProyTram( $idtram );
         if( !$tram ){ echo "No tram"; return; }
         
         $this->dbPilar->Update( "tesTramites", array(
                 'Estado'    => 0,
                 'Tipo'      => 0,
                 'FechModif' => mlCurrentDate()
             ), $idtram );
         
         //------------------Correo a los tesistas -------------
         $msgEnviar  = "El Asesor ha rechazado su proyecto de tesis <b>".$tram->Codigo
               ."</b> por los siguientes motivos : <br>      ".$msg." <br><br> Deberá corregir y coordinar personalmente para realizar nuevamente su tramite en la plataforma PILAR. ";
         if($tram->IdTesista2 !=0)
           {
             $mail = $this->dbPilar->inCorreo( $tram->IdTesista1);
             $mail2 = $this->dbPilar->inCorreo( $tram->IdTesista2);
             $cel= $this->dbPilar->inCelTesista( $tram->IdTesista1);
             $cel2= $this->dbPilar->inCelTesista( $tram->IdTesista2);
             $this->logCorreo( $sess->userId, $mail, "Rechazo del Asesor", $msgEnviar );
             $this->logCorreo( $sess->userId, $mail2, "Rechazo del Asesor", $msgEnviar );
             //$a=$this->notiCelu($cel,1);
              // $a=$a." - ".$this->notiCelu($cel2,1);
           }
         else
           {
             $mail = $this->dbPilar->inCorreo( $tram->IdTesista1);
             $cel= $this->dbPilar->inCelTesista( $tram->IdTesista1);
             $this->logCorreo( $sess->userId, $mail, "Rechazo del Asesor", $msgEnviar );
             //$a=$this->notiCelu($cel,1);
           }
        $a='';
         //---------------------FIN----------------------------
        $msg=$msg.$a;
         $this->logTramites( $tram->Id, "Rechazo del Asesor", $msgEnviar );
         echo "<br><b> Proyecto Rechazado </b><br>";
         echo "Se procede con notificar al tesista(s) para que subsane las observación o realice el cambio de asesor.";
         echo "<br><br>";    
 
         echo "<button onclick='closeDlg(\"docentes/infoTrams/1\")' type='button' class='btn btn-danger'> [x] Cerrar Ventana </button>"; 
     }
 
       
	public function corrBorras( $idtram=0, $height )
	{
		$this->gensession->IsLoggedAccess();
		$sess = $this->gensession->GetData();

		if( !$idtram ) return;
		/*
			parent.document.getElementById('frmpdf').height = window.innerHeight - 120;
			$('#frmpdf').attr('src', "../repositor/docs/d00002945-Proy.pdf");
    		$('#myModal').modal({backdrop: 'static', keyboard: false});
		*/

		$tram = $this->dbPilar->inProyTram( $idtram );
		if( ! $tram ) {
			echo "Sin Tramite";
			return;
		}

        //-------------------------------------------------------------------------------------------
		//$dets = $this->dbPilar->inTramDetIter( $idtram, 5); // 4ta
        $dets = $this->dbPilar->inLastTramDet( $idtram );

        if( ! $dets ) {
            echo "Sin Detalle Tram (1)";
            return;
        }

        //-------------------------------------------------------------------------------------------
        $pos = $this->dbPilar->inPosJurado( $tram, $sess->userId );
        // $chk = ($pos==1)? $dets->vb1 : (($pos==2)? $dets->vb2 : (($pos==3)? $dets->vb3: ($pos==4)? $dets->vb4: 0  ));
        //-------------------------------------------------------------------------------------------
        // $chk = ($pos==1)? $dets->vb1 : (($pos==2)? $dets->vb2 : (($pos==3)? $dets->vb3:0));
        // $chk = ($chk==0)?$dets->vb4:0; 
        $chk1 = ($pos==1)?$dets->vb1 :0;
        $chk2 = ($pos==2)?$dets->vb2 :0;
        $chk3 = ($pos==3)?$dets->vb3 :0;
        $chk4 = ($pos==4)?$dets->vb4 :0;
		///$dets = $this->dbPilar->inLastTramDet( $idtram );
        // echo "  $chk1 - $chk2-$chk3 - $chk4   ";
		

        //echo "xxxxxxxxxxxxxxxx:  $dets->vb1 / $dets->vb2 / $dets->vb3 / $dets->vb4";
        //echo "<hr>";
        // validar el OK y quitar controles

        $linkPdf = base_url( "repositor/docs/$dets->Archivo" );

		echo "<div class='col-md-9'>";
		echo " <iframe id='frmpdf' name='frmpdf' src='$linkPdf' frameborder=0 width='100%' height='$height px'></iframe>";
		echo "</div>";

		echo "<div id='lisPan' class='col-md-3' style='padding-left: 0px; padding-right: 22px'>";
        if( $tram->Estado == 11 || $tram->Estado == 12 || $tram->Estado == 13 ) { 

        $pos = $this->dbPilar->inPosJurado( $tram, $sess->userId );
        $chk = ($pos==1)? $dets->vb1 : (($pos==2)? $dets->vb2 : (($pos==3)? $dets->vb3:0) );

        if($pos==4){
            $chk=$dets->vb4;
        }
		// aqui evento grabar correcciones
		$eve = "onclick='if(confirm(\"Esta acción cerrará el proceso de correcciones y las envia al tesista.\")) grabEvent($tram->Id,69)'";

        if( $chk == 0 ) {
           echo  "<form method=post id='frmkrs' name='frmkrs' onsubmit='grabCorrs(); return false'>";
                 echo    "<input type=hidden id='kidt' name='kidt' value='$tram->Id'>";
                 echo    "<input type=hidden name='idoc' value='$sess->userId'>";
                 echo    "<textarea id='korec' name='korec' type='text' class='form-control' rows=4 placeholder='Indique las observaciones (max 1000 carácteres)' maxlength='1000' required></textarea>";
                 echo    "<br>";
                 echo    " <p class='text-success'> <b>Nota 1:</b> En caso tenga observacione o cuando termine de realizar las observaciones no se olvide hacer clic en el boton finalizar </p>";
                 echo    " <p class='text-danger'> <b>Nota 2:</b> En caso no tenga observaciones el borrador de tesis, dar en clic en el boton finalizar y se considera aprobado por Ud.</p> <br>";
                 echo    "<center>";
                 echo    "<button type='submit' class='btn btn-success'> Añadir Observacion </button> | ";
                 echo    "<button $eve type='button' class='btn btn-warning'> Finalizar Observacion </button> |  ";
                 echo    "<button type='button' class='btn btn-danger' data-dismiss='modal'> Cerrar Ventana buu</button>";
                 echo    "</center>";
                 echo  "</form>";
        } else if($chk==2) //agregado unuv1.0 - para el docente que aprobo la tesis
            {
                echo "<h4> Ud. Ya aprobo el Borrador  de tesis </h4>";
                echo  "<button type='button' class='btn btn-danger' data-dismiss='modal'> [X] Salir  </button>";

            }
            else {
                echo "<h4> Ud. ha finalizado las observaciones al Borrador de Tesis </h4>";
                echo "<button type='button' class='btn btn-danger' data-dismiss='modal'> [x] Cerrar Ventana </button>";
            }

    /*    if( $chk1+$chk2+$chk3+$chk4 < 1) {
             echo  "<form method=post id='frmkrs' name='frmkrs' onsubmit='grabCorrs(); return false'>";
             echo    "<input type=hidden name='kidt' value='$tram->Id'>";
             echo    "<input type=hidden name='idoc' value='$sess->userId'>";
             echo    "<textarea id='korec' name='korec' type='text' class='form-control' rows=4 placeholder='Ingrese una corrección, no mas de 5 lineas' required></textarea>";
             echo    "<br>";
             echo    "<button type='submit' class='btn btn-success'> Grabar Corrección bo </button> | ";
             echo    "<button type='submit' $eve  class='btn btn-warning'> Finalizar Correcciones</button> | ";
             echo    "<button type='button' class='btn btn-danger' data-dismiss='modal'> Cerrar Ventana </button>";
             echo  "</form>";

           // echo "<br><img class='img-responsive' src='vriadds/vri/web/convocatorias/comunicadoenero.png'</h4>";

        } else {
            echo "<h4> Ud. ha finalizado las observaciones </h4>";
            echo "<button type='button' class='btn btn-danger' data-dismiss='modal'> [x] Cerrar Ventana </button>";
        } */


		$hgt = ($height - 174) . "px";
		echo  "<div id='lisCorr' style='overflow-y:auto; height:$hgt; margin-top: 10px; padding-top: 5px; border-top: 4px solid orange'>";

		// mostrar las puñeteras correcciones joder !

            $this->listarCorrecs( $tram->Id, $sess->userId, 7);
            $this->listarCorrecs( $tram->Id, $sess->userId, 6);
            $this->listarCorrecs( $tram->Id, $sess->userId, 5);
           
        ///echo "$tram->Id :: $sess->userId";

		echo  "</div>";
         }

		echo "</div>";
	}

    //modificacion unuv1.0 - Estado revision 1
    //modificacion unuv1.0 - Estado revision 2
      //modificacion unuv1.0 - Estado revision 3
	public function corrGraba( )
	{
		$this->gensession->IsLoggedAccess();
		$sess = $this->gensession->GetData();

		$idtram = mlSecurePost( "kidt" );
		$iddoce = mlSecurePost( "idoc" );
        $mesage = mlSecurePost( "korec" );


		$tram = $this->dbPilar->inProyTram( $idtram );
		if( !$tram ){ echo "No tram"; return; }

		// iteracion de acuerdo al tramite hack
		//$iteracion = ($tram->Estado >= 6)? 4 : 1;
        $iteracion =1;
        if($tram->Estado == 5) //agregado unuv1.0
        {
            $iteracion=2;
        }
        if ($tram->Estado == 6){ //agregado unuv1.0
            $iteracion=3;
        }
        if($tram->Estado>=7)
        {
            $iteracion =4;
        }
        if($tram->Estado ==11)
        {
            $iteracion =5;
        }
        if($tram->Estado ==12)
        {
            $iteracion =6;
        }
        if($tram->Estado ==13)
        {
            $iteracion =7;
        }
         if($tram->Estado ==14)
        {
            $iteracion =8;
        }

		$this->dbPilar->Insert( "tblCorrects", array(
				'Iteracion' => $iteracion,  ///// 1, 4,
				'IdTramite' => $tram->Id,
                'IdDocente' => $iddoce,
				//'IdDocente' => $sess->userId,
				'Mensaje'   => $mesage,
				'Fecha' => mlCurrentDate()
			) );
        if($tram->Tipo==2)
        {
            $this->listarCorrecs( $tram->Id, $sess->userId, 7);
            $this->listarCorrecs( $tram->Id, $sess->userId, 6);
            $this->listarCorrecs( $tram->Id, $sess->userId, 5);
            return;
        }

        $this->listarCorrecs( $tram->Id, $sess->userId, 3);//agregado unuv1.0
        $this->listarCorrecs( $tram->Id, $sess->userId, 2);  //agregado unuv1.0
        $this->listarCorrecs( $tram->Id, $sess->userId, 1 ); 

        
	}


	// se invoca en Estados (4) (5) (12)
	//
    //modificacion unuv1.0 - Estado revision 2
	private function listarCorrecs( $idTram, $userId, $iter )
	{
		$corr = $this->dbPilar->getSnapView( "tblCorrects",
						"Iteracion=$iter AND IdTramite=$idTram AND IdDocente=$userId",
						"ORDER BY Id DESC"
				   );
        $alerta ="info"; //agregado unuv1.0
        if($iter==2)
        {
            $alerta="warning";
        }
        if($iter==3)
        {
            $alerta="success";
        }
        if($iter==6)
        {
            $alerta="warning";
        }
        if($iter==7)
        {
            $alerta="success";
        }
		foreach( $corr->result() as $row ) {
			echo '<div class="alert alert-'.$alerta.'" style="margin-bottom: 8px; padding: 6px">';
			echo "<strong>".mlFechaNorm($row->Fecha)."</strong>:<br>" . secureString($row->Mensaje);
			echo "</div>";
		}
	}


	//------------------------------------------------------------------------
	// lineas de investigacion Docente
	//------------------------------------------------------------------------
	public function saveLin( $nlin=0 )
	{
		if( ! $nlin ) return;

		$this->gensession->IsLoggedAccess();
		$sess = $this->gensession->GetData();

		// si hay tres chao...
		$tlin = $this->dbPilar->getTable("docLineas","IdDocente=$sess->userId" );
		if( $tlin->num_rows() >= 5 ) return;

		// verificar e insertar nueva linea
		//
		if( ! $this->dbPilar->getSnapRow("docLineas","IdDocente=$sess->userId AND IdLinea=$nlin") ) {

			$this->dbPilar->Insert( "docLineas", array(
					'Tipo'      => 1,
					'IdLinea'   => $nlin,
					'IdDocente' => $sess->userId,
                    'Estado' => 1,
					//'Fecha' => mlCurrentDate()
				) );
		}
	}

	public function borrLinea()
	{
		;
	}

	public function cargaLineas()
	{
		$this->gensession->IsLoggedAccess();
		$sess = $this->gensession->GetData();

		$linDoc = $this->dbPilar->getTable("docLineas","IdDocente=$sess->userId");
		if( !$linDoc ) return;

		$nro = 1;
		foreach( $linDoc->result() as $row )
		{
			$opciones = "<button onclick='borrLinea($row->Id)' class='btn btn-danger btn-xs'><span class='glyphicon glyphicon-erase'></span> Borrar</button>";

			echo "<tr>";
			echo "<td class='col-md-1' style='text-align:center'> <b> $nro </b> </td>";
			echo "<td class='col-md-1'> <center><b>" .(($row->Tipo==1)?"P":"A"). "</b></center> </td>";
			echo "<td class='col-md-9'> " .$this->dbRepo->inLineaInv($row->IdLinea). "</td>" ;
			echo "<td class='col-md-1'> $opciones </td>";
			echo "</tr>"; $nro++;
		}
	}



	//------------------------------------------------------------------------
	// area de Constancia de Jurados
	//------------------------------------------------------------------------
	public function constJurado( $idtram=0, $height=0 )
	{
		$this->gensession->IsLoggedAccess();
		$sess = $this->gensession->GetData();

		if( !$idtram ) return;

		$tram = $this->dbPilar->inProyTram( $idtram );
		if( ! $tram ) {
			echo "Sin Tramite";
			return;
		}

		$linkPdf = "docentes/contanSust/$idtram";
		$hgt = ($height - 174) . "px";

		echo "<div class='col-md-9'>";
		echo " <iframe id='frmpdf' name='frmpdf' src='$linkPdf' frameborder=0 width='100%' height='$height px'></iframe>";
		echo "</div>";

		echo "<div id='lisPan' class='col-md-3' style='padding-left: 0px; padding-right: 22px'>";

		if( $tram->Tipo == 3 ) {

			echo '<h4 class="titulo"> Constancia de Jurado </h4>';
			echo "<button type='button' class='btn btn-success' onclick='frmpdf.print()'> Deseo Imprimir </button> | ";
			echo "<button type='button' class='btn btn-danger' data-dismiss='modal'> [x] Cerrar Ventana </button>";
		}

	}

    public function solicitaUrkund( $idtram=null )
    {
        $this->load->model("dbWeb");
        $idtram = $this->dbWeb->getSnaprow('UsersUrkund',"Id=$idtram");
        if( !$idtram ){
            echo " Solicite acceso en <a href='".base_url("urkund")."'>URKUND UNAP</a>";
            return;
        } 

        //-------------------------------------------------------------------------------------------------

        $doc=$this->dbRepo->inDocente("$idtram->IdDocente");
        $esc=$this->dbRepo->inCarrera($idtram->IdCarrera);
        $idx=$this->dbRepo->getSnapRow('dicDocIndex',"IdDocente=$idtram->IdDocente");
        ///$pdf = new FPDF();
        $pdf = new GenSexPdf();

        //$pdf->SetMargins(25, 35, 25);
        $pdf->SetMargins(20, 10, 25);
        $pdf->AddPage();


        $pdf->SetDrawColor( 150, 150, 150 );
        $pdf->SetFont( "Times", "B", 14 );
    

        $pdf->Ln(6);
        $pdf->SetFont( "Arial", "B", 17 );
        $pdf->Cell( 170, 10, toUTF("SOLICITUD DE ASIGNACIÓN DE CUENTA"), 0, 1, 'C' );
        $pdf->Ln(5);


        $str = "SEÑOR VICERRECTOR DE INVESTIGACIÓN DE LA Universidad Nacional de Ucayali - PUCALLPA.";

        $pdf->SetFont( "Arial", "", 12 );
        $pdf->MultiCell( 165, 7, toUTF($str), 0, 'L' );

        $str = "Yo $doc , identificado con DNI N° $idtram->DNI , docente de la Escuela Profesional de $esc"
                ." mediante el presente solicito el acceso al Software de Detección de Similitud y Plagio "
                ."para lo cual he registrado la información solicitada como sigue a continuación:"
             ;

        $pdf->Ln(5);
        $pdf->SetFont( "Arial", "", 11 );
        $pdf->MultiCell( 165, 7, toUTF($str), 0, 'J' );


        $pdf->Ln(7);
        $pdf->SetFont( "Arial", "B", 11 );
        $pdf->Cell(  20, 7, "" ); // espacio
        $pdf->Cell(  45, 7, toUTF("DINA"), 0, 0 );
        $pdf->Cell( 100, 7, toUTF(": $idx->Dina"), 0, 1 );

         $pdf->Ln(4);
        $pdf->SetFont( "Arial", "B", 11 );
        $pdf->Cell(  20, 7, "" ); // espacio
        $pdf->Cell(  45, 7, toUTF("ORCID"), 0, 0 );
        $pdf->Cell( 100, 7, toUTF(": $idx->Orcid"), 0, 1 );

        $pdf->Ln(4);
        $pdf->SetFont( "Arial", "B", 11 );
        $pdf->Cell(  20, 7, "" ); // espacio
        $pdf->Cell(  45, 7, toUTF("CORREO "), 0, 0 );
        $pdf->Cell( 100, 7, toUTF(": $idtram->Mail"), 0, 1 );

        $pdf->Ln(5);
        $pdf->SetFont( "Arial", "", 11 );
        $pdf->MultiCell( 165, 7, toUTF("Cumpliendo con lo solicitado, esta cuenta será utilizada únicamente para trabajos académicos o de investigación de la Universidad Nacional de Ucayali, por lo que acepto los reglamentos , condiciones* y políticas de uso de software, de lo contrario me someto a las auditorías y sanciones correspondientes. "), 0, 'J' );

        $pdf->Ln(7);
        $pdf->SetFont( "Arial", "", 11 );
        $pdf->MultiCell( 165, 7, toUTF("Atentamente."), 0, 'J' );


        $pdf->Ln(8);
        $pdf->SetFont( "Arial", "B", 11 );


        $fecha = mlCurrentDate();

        $dia = (int) substr( $fecha, 8, 2 );
        $mes = mlNombreMes( substr($fecha,5,2) );
        $ano = (int) substr( $fecha, 0, 4 );

        $pdf->MultiCell( 164, 6, toUTF("Puno, $dia de $mes del $ano"), 0, 'R' );

        //$pdf->Image( 'includefile/imgs/aprofirma.jpg', 75, 245, 80 );
        //$this->qrImage( $pdf, 23, 230, $row->Codigo );

        $pdf->Ln(15);
        $pdf->SetFont( "Arial", "B", 11 );
        $pdf->MultiCell( 165, 5, toUTF("_______________________________________ "), 0, 'C' );
        $pdf->MultiCell( 165, 5, toUTF("$doc "), 0, 'C' );
        $pdf->MultiCell( 165, 5, toUTF(" DOCENTE"), 0, 'C' );



        $pdf->Ln(12);

        $pdf->SetFont( "Arial", "B", 8 );
        $pdf->MultiCell( 165, 5, toUTF("* NOTA :"), 0, 'J' );
        $pdf->SetFont( "Arial", "", 7.5 );
        $pdf->MultiCell( 165, 4, toUTF("(1)Esta solicitud será procesada en un plazo no mayor a 24 Horas, una vez entregada a la Oficina de Plataforma de Investigación. (2) Los documentos cargados no podrán ser eliminados bajo ningun motivo. (3) La carga de documentos es a partir de la fecha de asignación y estos serán exclusivos de la UNU."), 0, 'J' );
        $pdf->Output();
    }

    public function contanSust( $idtram=null )
    {
		$idtram = secureString($idtram);
		if( !$idtram ) return;

		$this->gensession->IsLoggedAccess();
		$sess = $this->gensession->GetData();

		$tram = $this->dbPilar->getSnapRow( "tesTramites", "Tipo=3 AND Id=$idtram" );
		if( ! $tram ){ echo "No Tramite"; return; }

		$sus = $this->dbPilar->getSnapRow( "tesSustens", "IdTramite=$idtram" );
        if( ! $sus ){ echo "No Sustentación"; return; }

		$det = $this->dbPilar->inLastTramDet( $idtram );
		if( ! $det ){ echo "No Details"; return; }

        $autors = $this->dbPilar->inTesistas( $idtram );

        $indx = 0;
        if( $tram->IdJurado1 == $sess->userId ) $indx = 1;
        if( $tram->IdJurado2 == $sess->userId ) $indx = 2;
        if( $tram->IdJurado3 == $sess->userId ) $indx = 3;
        if( $tram->IdJurado4 == $sess->userId ) $indx = 4;
        //-------------------------------------------------------------------------------------------------

        ///$pdf = new FPDF();
		$pdf = new GenSexPdf();

        //$pdf->SetMargins(25, 35, 25);
		$pdf->SetMargins(20, 10, 25);
        $pdf->AddPage();

        //$pdf->Image( 'includefile/imgs/logoFrmt.png', 7, 10 );
		mlQrRotulo( $pdf, 19, 220, $tram->Codigo );
		$pdf->Image( 'vriadds/pilar/imag/aprofirma.jpg', 75, 230, 80 );

        $pdf->SetDrawColor( 150, 150, 150 );
        $pdf->SetFont( "Times", "B", 14 );
        $pdf->Cell( 30, 8, toUTF($tram->Codigo), 1, 1, 'C' );

        $pdf->Ln(6);
        $pdf->SetFont( "Arial", "B", 17 );
        $pdf->Cell( 170, 10, toUTF("CONSTANCIA"), 0, 1, 'C' );
        $pdf->Ln(5);


        $str = "EL VICERRECTORADO DE INVESTIGACIÓN DE LA Universidad Nacional de Ucayali - PUCALLPA.";

        $pdf->SetFont( "Arial", "", 12 );
        $pdf->MultiCell( 165, 7, toUTF($str), 0, 'J' );

        $pdf->Ln(5);
        $pdf->SetFont( "Arial", "B", 12 );
        $pdf->MultiCell( 165, 7, "HACE CONSTAR:", 0, 'J' );


		$fechaM = mlFechaNorm( $sus->Fecha );

        $str = "Que de conformidad con la evaluación del jurado calificador del informe de Tesis titulado: $det->Titulo "
             . "Presentada por el(los) Bachiller(es): $autors. Mediante la Plataforma de Investigación Universitaria Integrada "
             . "a la Labor Académica con Responsabilidad - PILAR. realizado en: $sus->Lugar con fecha: $fechaM, como consta "
             . "en la Plataforma PILAR."
             ;

        $pdf->Ln(5);
        $pdf->SetFont( "Arial", "", 11 );
        $pdf->MultiCell( 165, 7, toUTF($str), 0, 'J' );



        $str = "Se establece en el Art. 149 del Reglamento de Investigación de Grados y Títulos de la UNA Puno que, "
             . "aprobado y sustentado el mencionado Borrador de Tesis conformado por el jurado:"
             ;
        $pdf->Ln(7);


        $jur = array( "", "Presidente", "Primer Miembro", "Segundo Miembro", "Asesor" );
        $jur = $jur[$indx];
        $str = $this->dbRepo->inDocente( $sess->userId );


        $pdf->Ln(7);
        $pdf->SetFont( "Arial", "B", 11 );
        $pdf->Cell(  20, 7, "" ); // espacio
        $pdf->Cell(  45, 7, toUTF($jur), 0, 0 );
        $pdf->Cell( 100, 7, toUTF($str), 0, 1 );


        $pdf->Ln(7);
        $pdf->SetFont( "Arial", "", 11 );
        $pdf->MultiCell( 165, 7, toUTF("Se expide la presente para los fines que estime conveniente."), 0, 'J' );


        $pdf->Ln(8);
        $pdf->SetFont( "Arial", "B", 11 );


		$fecha = mlCurrentDate();

        $dia = (int) substr( $fecha, 8, 2 );
        $mes = mlNombreMes( substr($fecha,5,2) );
        $ano = (int) substr( $fecha, 0, 4 );

		$pdf->MultiCell( 164, 6, toUTF("Puno, $dia de $mes del $ano"), 0, 'R' );

        //$pdf->Image( 'includefile/imgs/aprofirma.jpg', 75, 245, 80 );
        //$this->qrImage( $pdf, 23, 230, $row->Codigo );

        $pdf->Output();
    }

    public function contanciaSus( $idtram=null, $idprof=null )
    {
		$idtram = secureString($idtram);
		if( !$idtram ) return;

		//$this->gensession->IsLoggedAccess();
		//$sess = $this->gensession->GetData();

		$tram = $this->dbPilar->getSnapRow( "tesTramites", "Tipo=3 AND Id=$idtram" );
		if( ! $tram ){ echo "No Tramite"; return; }

		$sus = $this->dbPilar->getSnapRow( "tesSustens", "IdTramite=$idtram" );
        if( ! $sus ){ echo "No Sustentación"; return; }

		$det = $this->dbPilar->inLastTramDet( $idtram );
		if( ! $det ){ echo "No Details"; return; }

        $autors = $this->dbPilar->inTesistas( $idtram );

        $indx = 0;
        if( $tram->IdJurado1 == $idprof ) $indx = 1;
        if( $tram->IdJurado2 == $idprof ) $indx = 2;
        if( $tram->IdJurado3 == $idprof ) $indx = 3;
        if( $tram->IdJurado4 == $idprof ) $indx = 4;
        //-------------------------------------------------------------------------------------------------

        ///$pdf = new FPDF();
		$pdf = new GenSexPdf();

        //$pdf->SetMargins(25, 35, 25);
		$pdf->SetMargins(20, 10, 25);
        $pdf->AddPage();

        //$pdf->Image( 'includefile/imgs/logoFrmt.png', 7, 10 );
		mlQrRotulo( $pdf, 19, 220, $tram->Codigo );
		$pdf->Image( 'vriadds/pilar/imag/aprofirma.jpg', 75, 230, 80 );

        $pdf->SetDrawColor( 150, 150, 150 );
        $pdf->SetFont( "Times", "B", 14 );
        $pdf->Cell( 30, 8, toUTF($tram->Codigo), 1, 1, 'C' );

        $pdf->Ln(6);
        $pdf->SetFont( "Arial", "B", 17 );
        $pdf->Cell( 170, 10, toUTF("CONSTANCIA"), 0, 1, 'C' );
        $pdf->Ln(5);


        $str = "EL VICERRECTORADO DE INVESTIGACIÓN DE LA Universidad Nacional de Ucayali - PUCALLPA.";

        $pdf->SetFont( "Arial", "", 12 );
        $pdf->MultiCell( 165, 7, toUTF($str), 0, 'J' );

        $pdf->Ln(5);
        $pdf->SetFont( "Arial", "B", 12 );
        $pdf->MultiCell( 165, 7, "HACE CONSTAR:", 0, 'J' );


		$fechaM = mlFechaNorm( $sus->Fecha );

        $str = "Que de conformidad con la evaluación del jurado calificador del informe de Tesis titulado: $det->Titulo "
             . "Presentada por el(los) Bachiller(es): $autors. Mediante la Plataforma de Investigación Universitaria Integrada "
             . "a la Labor Académica con Responsabilidad - PILAR. realizado en: $sus->Lugar con fecha: $fechaM, como consta "
             . "en la Plataforma PILAR."
             ;

        $pdf->Ln(5);
        $pdf->SetFont( "Arial", "", 11 );
        $pdf->MultiCell( 165, 7, toUTF($str), 0, 'J' );



        $str = "Se establece en el Art. 149 del Reglamento de Investigación de Grados y Títulos de la UNA Puno que, "
             . "aprobado y sustentado el mencionado Borrador de Tesis conformado por el jurado:"
             ;
        $pdf->Ln(7);


        $jur = array( "", "Presidente", "Primer Miembro", "Segundo Miembro", "Asesor" );
        $jur = $jur[$indx];
        $str = $this->dbRepo->inDocente( $idprof );


        $pdf->Ln(7);
        $pdf->SetFont( "Arial", "B", 11 );
        $pdf->Cell(  20, 7, "" ); // espacio
        $pdf->Cell(  45, 7, toUTF($jur), 0, 0 );
        $pdf->Cell( 100, 7, toUTF($str), 0, 1 );


        $pdf->Ln(7);
        $pdf->SetFont( "Arial", "", 11 );
        $pdf->MultiCell( 165, 7, toUTF("Se expide la presente para los fines que estime conveniente."), 0, 'J' );


        $pdf->Ln(8);
        $pdf->SetFont( "Arial", "B", 11 );


		$fecha = mlCurrentDate();

        $dia = (int) substr( $fecha, 8, 2 );
        $mes = mlNombreMes( substr($fecha,5,2) );
        $ano = (int) substr( $fecha, 0, 4 );

		$pdf->MultiCell( 164, 6, toUTF("Puno, $dia de $mes del $ano"), 0, 'R' );

        //$pdf->Image( 'includefile/imgs/aprofirma.jpg', 75, 245, 80 );
        //$this->qrImage( $pdf, 23, 230, $row->Codigo );

        $pdf->Output();
    }

    public function programaLaspau()
    {
        $this->gensession->IsLoggedAccess();
        $sess = $this->gensession->GetData();

        $dat     = $this->dbRepo->getSnapRow("vwDocentes","Id=$sess->userId");
        $cuantos = $this->dbPilar->getSnapView("_laspau");
        $coho    = $this->dbPilar->getOneField("_laspau","Coho","IdDoc=$sess->userId");
        $fechita = ($coho == 1 ? '1,2,3,5 de octubre y 15 Noviembre del 2018' : "15,16,17,19 de octubre , y 16 de Noviembre del 2018");

        if($cuantos->num_rows()<236){
            $this->load->view("pilar/doc/laspau", array('sess'=>$sess , 'dat'=>$dat,'cuantos'=>$cuantos->num_rows()));
        }else{
            if ($this->dbPilar->getOneField("_laspau","Confirm","IdDoc=$sess->userId")==0){
            echo "
            <div class='panel panel-default'>
              <div class='panel-body'><h2>CONFIRMACIÓN DE PARTICIPACIÓN</h2></div>
              <div class='panel-footer'><p style='font-size:20px'>Señor profesor(a) $sess->userName, sirvase confirmar confirmar su participación en el curso LASPAU Segun el cronograma para ambos cohortes:
                    Fechas: $fechita.<br>

                    Se sugiere que se puedan avanzar los cursos de su carga académica, debido a que el curso laspau 2018 , es de tiempo completo y se respetará el horario establecido:
                    Horario : <ul style='font-size:20px'>
                        <li>08:00 a 12:00 Hrs</li>
                        <li>12:00 a 13:30 Hrs - (Break)</li>
                        <li>13:30 a 16:30 Hrs</li>
                    </ul>

                    <p style='font-size:20px'>El vicerrectorado de investigación, - Dirección General de Investigación, solicita la confirmación de participación en el curso para tramitar a  las instancias correspondientes los permisos para que la firma de los partes de asistencia se realicen en las Instalaciónes del Curso : Edificio de Educación Continua.

                    Nota : Fecha límite de confirmación de participación Jueves 27 de Septiembre 2018 23:59:00 Hrs.</p></div>

                    <center><a href='http://vriunap.pe/pilar/docentes/ConfirmPas/$sess->userId' onclick=\"return confirm('¿Está seguro de confirmar su participación,y cumplir con el cronograma establecido?')\" class='btn btn-lg btn-success'> Confirmar Participación</a></center>
            </div>

            </div>

            ";
            }if($this->dbPilar->getOneField("_laspau","Confirm","IdDoc=$sess->userId")==1){
                echo"<div class='panel panel-default'>
              <div class='panel-body'><h2>CONFIRMACIÓN DE PARTICIPACIÓN</h2></div>
              <div class='panel-footer'>
                    <center><h2 class='text-success'> POSTULACIÓN CONFIRMADA</h2></center>

              <p style='font-size:20px'>Señor profesor(a) $sess->userName, sirvase confirmar confirmar su participación en el curso LASPAU Segun el cronograma para ambos cohortes:
                    Fechas: $fechita<br>

                    Se sugiere que se puedan avanzar los cursos de su carga académica, debido a que el curso laspau 2018 , es de tiempo completo y se respetará el horario establecido:
                    Horario : <ul style='font-size:20px'>
                        <li>08:00 a 12:00 Hrs</li>
                        <li>12:00 a 13:30 Hrs - (Break)</li>
                        <li>13:30 a 16:30 Hrs</li>
                    </ul>

                   <p style='font-size:20px'> El vicerrectorado de investigación, - Dirección General de Investigación, solicita la confirmación de participación en el curso para tramitar a  las instancias correspondientes los permisos para que la firma de los partes de asistencia se realicen en las Instalaciónes del Curso : Edificio de Educación Continua.
            </div>
            </div>";

            }else{
                echo "No tiene postulación registrada";
            }
        }
        // $this->load->view("pilar/doc/laspau", array('sess'=>$sess , 'dat'=>) );
        // $this->load->view("pilar/doc/laspau");
    }

    public function ConfirmPas($id){
       if($this->dbPilar->updateEx('_laspau',array('Confirm'=>1,"FechConfirm"=>mlCurrentDate()),"IdDoc=$id")){

        // $this->apismss->notiCelu2($this->dbRepo->getOneField("tblDocentes","NroCelular","Id=$id"));
        $this->load->view("pilar/head");
        echo "<div class='col-md-3'> </div>"; 
        echo "<div class='col-md-6'> ";
        echo "<img class='img-responsive' src='".base_url("vriadds/pilar/imag/pilar-head.jpg")."'></img>";
        echo "<h1 class='text-center'><small>CONFIRMACIÓN DE PARTICIPACIÓN</small> </I></h1>";
        echo "<h5 class='text-right'>".date("d/m/Y")."</h5>";
        echo "
        <center>            <h3>Señor docente su confirmación, fue remitido satisfactoriamente.</h3>
            <a href='http://vriunap.pe/pilar/docentes/' class='btn btn-lg btn-success'>Regresar</a>
        </center>";
        echo "</div>";
        echo "</div>";
    }else{
        echo "Error, intente nuevamente";
    }
    }

    public function notiCelu2($cel)
    {
        $this->load->library('apismss');
        $result = $this->apismss->sendMsj($cel,4);
        // print_r($result);
    }

    public function execPostulaLaspau()
    {
        $sess = $this->gensession->GetSessionData();
        $ord=$this->dbPilar->getOneField("_laspau","Ord","Id>0 ORDER BY Id DESC")+1;
        $sem1=mlSecurePost("sem1");
        $cur1=mlSecurePost("cur1");
        $sem2=mlSecurePost("sem2");
        $cur2=mlSecurePost("cur2");
        $sem3=mlSecurePost("sem3");
        $cur3=mlSecurePost("cur3");
        $sem4=mlSecurePost("sem4");
        $cur4=mlSecurePost("cur4");
        $titulod=mlSecurePost("titulod");
        $grad=mlSecurePost("grad");
        $resumen=mlSecurePost("resumen");
        $nomarch=mlSecurePost("nomarch");
        $codigo=sprintf("LP%03s", $ord );
        $tram=$this->dbPilar->inTramByTesista($sess->userId);

        $ppita=$this->uploacarta($codigo);

        if($ppita!=null){
                if(!$this->dbPilar->getSnaprow("_laspau","IdDoc=$sess->userId")){
                    $this->dbPilar->Insert("_laspau", array(
                            'Cod'       => $codigo,
                            'IdDoc'     => $sess->userId,
                            'Curso1'    => "$sem1-$cur1",
                            'Curso2'    => "$sem2-$cur2",
                            'Curso3'    => "$sem3-$cur3",
                            'Curso4'    => "$sem4-$cur4",
                            'Titulo'    => $titulod,
                            'Grado'     => $grad,
                            'Resumen'   => $resumen,
                            'Archivo'   => "$codigo.pdf",
                            'Fecha'   => mlCurrentDate(),
                            'Obs'       => "-",
                            'Ord'       => $ord,
                    ));
                }
                $msg= "<center><img  width='250px'src='http://vriunap.pe/vriadds/vri/web/laspau.png'></img></center><b>POSTULACIÓN ENVIADA</b><br><br>Señor(a) docente bienvenido(a) al CONCURSO para participar en el <b> Programa de enseñanza y aprendizaje para la innovación y la investigación en la UNA Puno.</b>.<br><br <br><br> Su código de postulación es :<b> $codigo </b><br><br><br>
                    Vicerrectorado de Investigación

                    ";
                $this->logCorreo( $sess->userId, $sess->userMail, "Inscripcion CONCURSO PROGRAMA LASPAU", $msg );
                 // $this->logCorreo( $tram->Id, "torresfrd@gmail.com", "Inscripcion 3MT ", $msg );

                echo "<div class='alert alert-success text-center'>
                      <h2><strong>Inscripción Enviada</strong></h2> <h5>La postulacción fue enviada exitosamente, así mismo se notificó al correo electrónico: $sess->userMail.</h5>.
                    </div>";
        }else{
                echo "<div class='alert alert-danger text-center'>ERROR :<br> Usted tiene inconvenientes para la inscripción Intente Nuevamente</h3> </div>";
        }
    }





    public function uploacarta($nombre)
    {
        $sess = $this->gensession->GetData();
        $config['upload_path']   = './repositor/laspau/';
        $config['allowed_types'] = 'pdf';  // ext
        $config['max_size']      = '204800';         // KB
        $config['overwrite']     = TRUE;
        $config['file_name']     = "$nombre.pdf";
        // finalmente subir archivo
        $this->load->library('upload', $config);
        if ( !$this->upload->do_upload("nomarch") ) { // input field

            $data['uploadError'] = $this->upload->display_errors();
            // echo "<div class='alert alert-danger text-center'><h2>Error:</h2> " . $this->upload->display_errors()."<h3><br> Tienes Inconvenientes para la inscripción Intenta Nuevamente</h3> </div>";
            // return null;

        } else {
            $file_info = $this->upload->data();
            // echo "Archivo Subido <br>";
            return $file_info;

        }

        // devolvemos el nombre del archivo
        return  $config['file_name'];
    }

    public function invitaDocente()
    {
        // $query=$this->dbRepo->getSnapView("tblDocentes","IdCategoria<10");
        // foreach ($query->result() as $rrow) {
        //     if($rrow->Correo){
                
        //       $msj = "<p>Estimado(a) Profesor(a),
        //             <br>Mediante el presente se le invita a participar del <b style='color:#172272;'> CICLO DE WEBINARS DE INVESTIGACIÓN </b>, el cual está dirigido a docentes universitarios para registrarse puede hacer click en el siguiente enlace:<br><br> <center><a style=' background-color: white;   color: #4CAF50; padding:5px; border: 2px solid #4CAF50; text-decoration: none; padding:8px;' href='http://www.minedu.gob.pe/conectados/webinars-investigacion.php'> REGISTRARME </a></center></p> </p> <br><br><hr style='border:1px dotted #C0C0C0'> ";

        //         $this->genmailer->sendMail($rrow->Correo, 'VRI UNAP: INVITACIÓN WEBINAR', $msj ); 
        //         echo "$rrow->Correo : Enviado <br>";
        //     }
        // }

        $this->genmailer->sendMail("torresfrd@gmail.com", 'VRI UNAP: INVITACIÓN WEBINAR', "$msj" ); 
        echo "Envio Completado";
    }


    public function grabIndexDoc()
    {
        $this->gensession->IsLoggedAccess();
        $sess = $this->gensession->GetData();

        $orcid  = mlSecurePost("orcid");
        $scopus = mlSecurePost("scopus");
        $dina   = mlSecurePost("dina");
        $regina = mlSecurePost("regina");


        if( $row=$this->dbRepo->getSnapRow("dicDocIndex","IdDocente=$sess->userId") ){

            $this->dbRepo->Update( "dicDocIndex", array(
                "Orcid"  => $orcid,
                "Dina"   => $dina,
                "Scopus" => $scopus,
                "Regina" => $regina,
                "Fecha"  => mlCurrentDate()
            ), $row->Id );

            echo "Datos editados correctamente";
            return;
        }

        $id = $this->dbRepo->Insert( "dicDocIndex", array(
            "IdDocente" => $sess->userId,
            "DNI" => $sess->userDNI,
            "Orcid"  => $orcid,
            "Dina"   => $dina,
            "Scopus" => $scopus,
            "Regina" => $regina,
            "Fecha"  => mlCurrentDate()
        ) );

        echo "Id de Registro: $id";
    }




    //----------------------------------------------------------------------------------------
    // public function li_index()
    // {
    //     $idUser = 461; // villano
    //     $idUser = 451; // nyr


    //     $this->inListProys( 1, $idUser );
    //     $this->inListProys( 2, $idUser );
    //     $this->inListProys( 3, $idUser );
    //     $this->inListProys( 4, $idUser );

    //     //$this->load->view( "pilar/base", array( 'table'=>$table ) );
    // }


    // private function docenteFull( $id )
    // {
    //     $grado = $this->dbPilar->inGradoDoc($id);
    //     $nombe = $this->dbRepo->inDocente($id);

    //     return (strlen($grado)? "$grado $nombe" : $nombe);
    // }

    // private function decoraProys( $table )
    // {
    //     foreach( $table->result() as $row ){

    //         $proy = $this->dbPilar->inProyDetail( $row->Id );
    //         $tes = $this->dbPilar->inTesista( $row->IdTesista1 );
    //         $dat = $this->dbRepo->inDocente( $row->IdJurado1 );

    //         echo "$row->Codigo : $row->Estado :: :: $proy->Iteracion -- $proy->Archivo :: $proy->Titulo :: $tes :: $dat <br>";

    //         echo "<br>" . $this->docenteFull( $row->IdJurado1 ) ;
    //         echo "<br>" . $this->docenteFull( $row->IdJurado2 ) ;
    //         echo "<br>" . $this->docenteFull( $row->IdJurado3 ) ;
    //         echo "<br>" . $this->docenteFull( $row->IdJurado4 ) ;

    //         $doc = $this->dbrepo->inDocenteRow( $row->IdJurado1 );
    //         $doc = $this->dbrepo->inDocenteRow( $row->IdJurado2 );
    //         $doc = $this->dbrepo->inDocenteRow( $row->IdJurado3 );
    //         $doc = $this->dbrepo->inDocenteRow( $row->IdJurado4 );
    //         echo $doc->Categoria;
    //     }
    //     echo "<hr>";
    // }

    // private function inListProys( $ordJur, $idJur )
    // {
    //     $table = $this->dbPilar->getTable( 'tesTramites', "IdJurado$ordJur='$idJur' ORDER BY Id DESC" );
    //     $this->decoraProys( $table );
    // }

}

//- EOF

