    <body class="bg-1">
      <!--se aumento oncontext y onkeydown para no mostrarc odigo fuente oncontextmenu="return false-->
      <!------- Modal comunicado al inicio -->
      <div class="modal fade" id="msgPosterX" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content modal-pilar">
            <div class="modal-header modal-pilar-title" style="background-color:rgba(0, 138, 49)">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">COMUNICADO</h4>
            </div>
            <div class="modal-body" style="padding: 0px">
              <CENTER>
                <img class="img-responsive" src="<?php echo base_url("vriadds/pilar/imag/Comunicados/Aviso.jpg"); ?>">
              </CENTER>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal"> Cerrar </button>
            </div>
          </div>
        </div>
      </div>
      <!-------- fin Modal comunicado - descomentado en unuv2.0-->
      <!----------Menu vertical -------------->
      <div class="navbar navbar-default" style="background-color: #046c04">
        <div class="container-fluid">
          <div class="navbar-header">
            <button button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
              <span class="sr-only">Menu</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" rel="home" href="<?= base_url("pilar") ?>" title="Universidad Nacional de Ucayali | Vicerrectorado de Investigación">
              <img class="img-responsive" style="max-width:160px; margin-top: -25px; margin-left: 20px;" src="<?= base_url("vriadds/pilar/imag/logos-u-v-p.png"); ?>">
            </a>
          </div>
          <div id="navbar" class="collapse navbar-collapse navbar-responsive-collapse">
            <ul class="nav navbar-nav navbar-right">
              <li><a href="<?= base_url("pilar") ?>">Inicio</a></li>
              <!--<li><a href="<?= base_url("pilar/docentes") ?>">Docentes</a></li>
                    <li><a href="<?= base_url("pilar/tesistas") ?>">Tesistas</a></li>
                    <li><a href="<?= base_url("pilar/cordinads") ?>">Coordinadores</a></li>
                    <li><a href="<?= base_url("pilar/sustentas") ?>">Sustentaciones</a></li> //comentado unuv1.0-->
            </ul>
          </div>
        </div>
      </div>
      <!----------fin Menu vertical -------------->
      <!----------Pantalla principal   -------------->
      <div class="container info-pilar2" style="background: url('<?= base_url("vriadds/pilar/imag/baner.jpg"); ?>');background-repeat:no-repeat;">
        <img class="img-responsive logo-pilar3" src="<?= base_url("vriadds/pilar/imag/pilar-n.png"); ?>">
        <h3 id="name-pilar">Plataforma de Investigación Universitaria <br>Integrada a la Labor Académica con Responsabilidad </h3>
        <h4><i>Universidad Nacional de Ucayali</i>
      </div>
      <!----------fin pantalla principal   -------------->
      <!----------Menu principal  -------------->
      <div class="container">
        <div class="col-md-12 contenido1">
          <div class="col-md-9 bg-white margin">
            <div class="titulo">Presentación</div>
            <p class="description">
              La Universidad Nacional de Ucayali mediante RESOLUCIÓN Nº377-2020-UNU-CU-R <b>“Convenio Específico de Cooperación Interinstitucional entre la Universidad Nacional de Ucayali y la Universidad Nacional del Altiplano de Puno Nº007-2020/UNU-UNA”</b> pone a disposición la Plataforma <i>PILAR</i>
              para Docentes, Tesistas y Comisión GyT; contando con la información disponible para realizar la
              subida, calificacion, revisión y posterior dictaminación de proyectos de investigación de pregrado
              conducentes a la obtención del título profesional.
              <!-- // Modificado version 1.0 -->
            </p>
            <div class="row">
              <div class="col-xs-12 col-md-4 btn-acces-pilar">
                <a id="1tes" onclick="openNav(this.id)" class="btn btn-default btn-user bg-teal"><span class="glyphicon glyphicon-ok-circle"></span> <br />Tesista</a>
              </div>
              <div class="col-xs-12 col-md-4 btn-acces-pilar">
                <a id="2doc" onclick="openNav(this.id)" class="btn btn-default btn-user bg-green" role="button"><span class="glyphicon glyphicon-list-alt"></span> <br />Docente</a>
              </div>
              <div class="col-xs-12 col-md-4 btn-acces-pilar">
                <a id="3coord" onclick="openNav(this.id)" class="btn btn-default btn-user bg-red-ligth" role="button"><span class="glyphicon glyphicon-question-sign"></span> <br />Comisión GyT</a>
              </div>
            </div>
            <br>
            <!--  <div class="row">
                  <div class="col-xs-12 col-md-12 btn-acces-pilar">
                    <a class="btn btn-preg bg-teal"><span class="glyphicon glyphicon-cog"></span> <br/>Consultas en Linea ( En Construcción ) </a>
                  </div>
            </div> // modificado unuv1.0 -->
          </div>
          <div class="col-md-3 bg-white margin-bd" style=" padding: 5px;">
            <div class="titulo">Reglamentos y Manuales</div>
            <div class="list-group">
              <ul class="nav nav-pills bderecha">
                <a target="_blank" href="<?php echo base_url("vriadds/pilar/doc/Reglamento Pilar - Proyecto de Tesis.pdf"); ?>" class="list-group-item blink"><span class="glyphicon glyphicon-book"></span> Reglamento Proyectos </a>
                <!--<a target="_blank" href="<?php echo base_url("vriadds/pilar/doc/resReglaBorrador.pdf"); ?>" class="list-group-item blink"><span class="glyphicon glyphicon-book"></span> Reglamento de Borrador</a>
              <a target="_blank" href="<?php echo base_url("web/etica"); ?>" class="list-group-item blink"><span class="glyphicon glyphicon-book"></span> Procedimientos Ética en Investigación  <span class="label label-warning"> Nuevo </span></a> // comentado unuv1.0 -->
                <a target="_blank" href="<?php echo base_url("vriadds/pilar/doc/formatos/General.docx"); ?>" class="list-group-item blink"><span class="glyphicon glyphicon-bookmark"></span> Formato de Proyecto</a>
                <!--<a target="_blank" href="<?php echo base_url("vriadds/pilar/doc/Formato-Borrador-Tesis-2017.docx"); ?>"  class="list-group-item blink"><span class="glyphicon glyphicon-bookmark"></span> Formato de Borrador</a>
              <hr>
              <a href="#" class="list-group-item blink"><span class="glyphicon glyphicon-th-list"></span> Manual para Docentes</a> // comentado unuv1.0 -->
                <hr>
                <a target="_blank" href="<?php echo base_url("vriadds/pilar/doc/Manual de docente_v1.pdf"); ?>" class="list-group-item blink"><span class="glyphicon glyphicon-th-list"></span> Manual para Docentes </a>
                <a target="_blank" href="<?php echo base_url("vriadds/pilar/doc/Manual de tesista_v1.pdf"); ?>" class="list-group-item blink"><span class="glyphicon glyphicon-th-list"></span> Manual para Tesistas </a>
                <!-- <a href="#" class="list-group-item blink"><span class="glyphicon glyphicon-th-list"></span> Manual para Coordinadores</a> comentado unuv1.0-->
                <a target="_blank" href="<?php echo base_url("/pilar/web/preguntas"); ?>" class="list-group-item blink"><span class="glyphicon glyphicon-th-list"></span> Preguntas frecuentes</a>
              </ul>
            </div>
          </div>
          <div class="col-md-3 bg-white margin-bd" style=" padding: 5px;">
            <div class="titulo">Contactenos</div>
            <div class="list-group">
              <ul class="nav nav-pills bderecha">
                <a class="list-group-item blink"><span class="glyphicon glyphicon-envelope"></span> soporte_pilar@unu.edu.pe </a>
              </ul>
            </div>
          </div>
          <!--<div class="col-md-12 bg-white">
          <div class="titulo">Herramientas del Investigador</div>
        </div> comentado unuv1.0-->
          <div class="col-md-12 bg-vino footer" style="background-color: #046c04">
            Universidad Nacional de Ucayali<br>
            Rectorado<br>
            <b> Contactenos : soporte_pilar@unu.edu.pe </b>
            <!--Dirección General de Investigación<br>
          &copy; Plataforma de Investigación y Desarrollo //comentado unuv1.0-->
          </div>
        </div>
      </div>
      <!---------- fin Menu principal   -------------->
      <!----- Modal Inicio Login Tesista-->
      <div id="Tesistas" class="overlay">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <div class="overlay-content">
          <div class="login-page">
            <div class="form">
              <img class="img-responsive login-logo" src="<?php echo base_url("vriadds/pilar/imag/pilar-tes.png"); ?>">
              <h4 class="login-title-tes" id='area'>Área de Tesistas</h4>
              <div id="pmsg" class="alert alert-danger"><span class="glyphicon glyphicon-exclamation-sign"></span>
              </div>
              <form class="login-form" name="logtes" onsubmit="callLoginTes(); return false" method="post">

                <input type="email" name="mail" id="maillogin" placeholder="Correo" required="" />
                <input type="password" id='passlogin' name="pass" placeholder="contraseña" required="" />
                <button type="submit" class="login-btn-tesista"> Ingresar</button>
                <a onclick="olvcontra()" href="javascript:void(0)" align="right">
                  <p><small><u>¿Olvidaste tu contraseña? Clic aquí</u></small></p>
                </a>
                <p class="message"><a rel="nofollow" onclick="valites()" href="javascript:void(0)">¿Usted es Tesista Nuevo? Registrate </a></p>
              </form>
              <!----------Restablecer contarseña - agregado unuv2.0 ----->
              <form class="register-form" name="frmoti1" onsubmit="CallEnvio(); return false">
              
                <div id="pdta1" style="color: black">
                  <input type="email" id="mailRecuperacion" name="mail" placeholder="Correo" required="" />
                  <button onclick="CallEnvio()" class="login-btn-tesista" type="button"> Restablecer contraseña </button>
                </div>
                <div id="msgok" style="color: black ; Display:none;">                  
                </div>
                <p class="message"><a href="javascript:void(0)" onclick="rgrprincipal()">Ingresar</a></p>
              </form>
              <!-------Fin Restablecer contarseña ----->
              <!----------Nuevo Tesista ----->
              <form class="register-form" name="frmoti" onsubmit="callSave(); return false">
                <div id="pdta" style="color: black">
                  <input id="cod" name="cod" type="number" placeholder="Codigo de Estudiante" required="" maxlength="10" oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" />
                  <input id="dni" name="dni" type="number" placeholder="Número de D.N.I." required="" maxlength="8" oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" />
                  <button onclick="callOTI()" class="login-btn-tesista" type="button"> Verificar mis Datos </button>
                </div>
                <div id="msgoktes" style="color: black ; Display:none;">                  
                </div>
                <p class="message"><a href="javascript:void(0)" onclick="rgrprincipal()">Ingresar</a></p>
              </form>
              <!----------Fin Nuevo Tesista ----->
              <a class="text-center" onclick="closeNav()"><span class="glyphicon glyphicon-remove-circle gi-1x"></span></a>
            </div>
          </div>
        </div>
      </div>
      <!--------- Fin Login Tesista------->
      <!------ Modal Inicio Login Docente-->
      <div id="Docentes" class="overlay">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <div class="overlay-content">
          <div class="login-page">
            <div class="form">
              <img class="img-responsive login-logo" src="<?php echo base_url("vriadds/pilar/imag/pilar-doc.png"); ?>">
              <h4 class="login-title-doc">Área de Docente</h4>
              <div id="qmsg" class="alert alert-danger"><span class="glyphicon glyphicon-exclamation-sign"></span> </div>              
              <form name="logdoc" class="login-form" onsubmit="callLoginDoc(); return false" method="post">
                <input name="mail" id='maillogin2' type="text" placeholder="Correo institucional" required="" />
                <input name="pass" id ='passlogin2' type="password" placeholder="contraseña" required="" />
                <button class="login-btn-docente">Ingresar</button>
                <a onclick="olvcontradoc()" href="javascript:void(0)" align="right">
                  <p><small><u>¿Olvidaste tu contraseña? Clic aquí </u></small></p>
                </a> 
              </form>
              <!----------Restablecer contarseña - agregado unuv2.0 ----->
              <form class="register-form" name="frmresta" onsubmit="CallEnviodoc(); return false">
              
                <div id="pdta3" style="color: black">
                  <input type="email" id="mailRecuperaciondoc" name="mailRecuperaciondoc" placeholder="Correo Institucional" required="" />
                  <button onclick="CallEnviodoc()" class="login-btn-docente" type="button"> Restablecer contraseña </button>
                </div>
                <div id="msgok2" style="color: black ; Display:none;">                  
                </div>
                <p class="message"><a href="javascript:void(0)" onclick="rgrprincipaldoc()">Ingresar</a></p>
              </form>
              <!-------Fin Restablecer contarseña ----->
              <a class="text-center" onclick="closeNav()"><span class="glyphicon glyphicon-remove-circle gi-1x"></span></a>
            </div>
          </div>
        </div>
      </div>
      <!--------- Fin Login Docente------->
      <!----- Modal Inicio Login Coordinador----->
      <div id="Coordinadores" class="overlay">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <div class="overlay-content">
          <div class="login-page">
            <div class="form">
              <img class="img-responsive login-logo" src="<?php echo base_url("vriadds/pilar/imag/pilar-cord.png"); ?>">
              <h4 class="login-title-cord">Área de Comisión GyT</h4>
              <div id="cmsg" class="alert alert-danger"><span class="glyphicon glyphicon-exclamation-sign"></span> </div>
              <form class="register-form">
                <input type="email" placeholder="Correo Institucional" required="" />
                <input type="password" placeholder="contraseña" required="" />
                <input type="text" placeholder="email address" required="" />
                <button class="login-btn-coord">Crear</button>
                <p class="message">'¿Ya estas Registrado? <a rel="nofollow" onclick="register()" rel="noreferrer">Ingresar</a></p>
              </form>

              <form name="logcor" class="login-form" onsubmit="callLoginCor(); return false" method="post">
                <input name="user" type="text" placeholder="Correo Institucional" required="" />
                <input name="pass" type="password" placeholder="contraseña" required="" />
                <button class="login-btn-coord">Ingresar</button>

              </form>
              <a class="text-center" onclick="closeNav()"><span class="glyphicon glyphicon-remove-circle gi-1x"></span></a>
            </div>
          </div>
        </div>
      </div>
      <!--------- Fin Login Coordinador------->
      <!--
    <div class="modal fade bs-example-modal-lg" tabindex="-4" role="dialog" id="tutorial">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
            
            <div class="carousel-inner">
              <div class="item active">
                <img class="img-responsive" src="<?= base_url("vriadds/pilar/imag/img3mt/paso0.jpg"); ?>" alt="...">
              </div>
              <div class="item">
                <img class="img-responsive" src="<?= base_url("vriadds/pilar/imag/img3mt/paso1.jpg"); ?>" alt="...">
              </div>
              <div class="item">
                <img class="img-responsive" src="<?= base_url("vriadds/pilar/imag/img3mt/paso2.jpg"); ?>" alt="...">
              </div>
              <div class="item">
                <img class="img-responsive" src="<?= base_url("vriadds/pilar/imag/img3mt/paso3.jpg"); ?>" alt="...">
              </div>
              <div class="item">
                <img class="img-responsive" src="<?= base_url("vriadds/pilar/imag/img3mt/paso4.jpg"); ?>" alt="...">
              </div>
            </div>
            <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
              <span class="glyphicon glyphicon-chevron-left"></span>
            </a>
            <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
              <span class="glyphicon glyphicon-chevron-right"></span>
            </a>
          </div>
        </div>
      </div>
    </div>  comentado unuv2.0 -->
    </body>
    <script>
      
      var frmoti1 = document.frmoti1;
      var frmtes = document.logtes;
      var frmoti = document.frmoti;  
      var frmres=  document.frmresta;  
      var logdoc =document.logdoc; 
      
        function valites()
        {
          frmtes.style.display = "none";
          frmoti1.style.display = "none";
          frmoti.style.display = "block";
        }

        function olvcontra()
        {
          $("#msgok").hide();
          $("#pdta1").show();
          document.getElementById('mailRecuperacion').value = '';
          frmtes.style.display = "none";
          frmoti1.style.display = "block";
          frmoti.style.display = "none";
        }
        function olvcontradoc()
        {
          $("#msgok2").hide();
          $("#pdta3").show();
          document.getElementById('mailRecuperaciondoc').value = '';
          logdoc.style.display = "none";
          frmres.style.display = "block";
        }

        function rgrprincipal()
        {
          document.getElementById('maillogin').value = '';
          document.getElementById('passlogin').value = '';
          $("#pmsg").hide();
          frmoti1.style.display = "none";
          frmoti.style.display = "none";
          frmtes.style.display = "block"; 
        }

        function rgrprincipaldoc()
        {
          document.getElementById('maillogin2').value = '';
          document.getElementById('passlogin2').value = '';
          $("#qmsg").hide();
          logdoc.style.display = "block";
          frmres.style.display = "none";
        }

        function cerrar() {
          m_href = "tesistas/destruircession";
          location.href = m_href;

        }
    </script>


    </html>