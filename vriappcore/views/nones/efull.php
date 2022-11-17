<!DOCTYPE html>
<html lang="es">
<head>
    <title> VRI Web Base </title>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--template css-->
    <link rel="icon" href="http://vriunap.pe/pilar/includefile/imgs/vri.png" sizes="32x32" />
    <link rel="icon" href="http://vriunap.pe/pilar/includefile/imgs/vri.png" sizes="192x192" />
    <link href="<?=base_url("vriadds/vri/bootstrap.min.css")?>" rel="stylesheet">
    <link href="<?=base_url("vriadds/vri/style.css") ?>" rel="stylesheet">
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="<?=base_url("vriadds/vri/jquery.js")?>"></script>
    <script src="<?=base_url("vriadds/vri/bootstrap.js")?>"></script>
    <script src="<?=base_url("vriadds/lightajax.js")?>"></script>
    <script src="<?=base_url("vriadds/vri/events.js")?>"></script>
</head>

<body>

<div class="row" style="background: black; height: 54px;">
    <div class="container">
        <nav class="navbar navbar-inverse">
        <div class="container-fluid">
        <div class="navbar-header">
        <a class="navbar-brand" href="#"> Pilar3 </a>
        </div>
        <ul class="nav navbar-nav">
        <li class="active"><a href="#">Home</a></li>
        <li><a href="#">Page 1</a></li>
        <li><a href="#">Page 2</a></li>
        <li><a href="#">Page 3</a></li>
        </ul>
        </div>
        </nav>
    </div>
</div>
<br>

<div class="container">
    <div class="container-fluid">


        <div class="row">
        <div class="col-md-6">
            <!-- <img src="./Float-Admin_files/pilar3.200.jpg" height="150"> -->
        </div>
        <div class="col-md-6" style="background: #606060; height: 150px">
        </div>
        </div>
    </div>
    <hr>
    <input name="celula" type="number" placeholder="Ej. 999001122" class="form-control input-md" required="" autofocus="">    
</div>


<div class="container">

      <!-- Static navbar -->
      <nav class="navbar navbar-default">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Project name</a>
          </div>
          <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
              <li class="active"><a href="#">Home</a></li>
              <li><a href="#">About</a></li>
              <li><a href="#">Contact</a></li>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
                <ul class="dropdown-menu">
                  <li><a href="#">Action</a></li>
                  <li><a href="#">Another action</a></li>
                  <li><a href="#">Something else here</a></li>
                  <li role="separator" class="divider"></li>
                  <li class="dropdown-header">Nav header</li>
                  <li><a href="#">Separated link</a></li>
                  <li><a href="#">One more separated link</a></li>
                </ul>
              </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
              <li class="active"><a href="./">Default <span class="sr-only">(current)</span></a></li>
              <li><a href="../navbar-static-top/">Static top</a></li>
              <li><a href="../navbar-fixed-top/">Fixed top</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </nav>

      <!-- Main component for a primary marketing message or call to action -->
      <div class="jumbotron">
        <h1>Navbar example</h1>
        <p>This example is a quick exercise to illustrate how the default, static navbar and fixed to top navbar work. It includes the responsive CSS and HTML, so it also adapts to your viewport and device.</p>
        <p>
          <a onclick="vriLogin()" class="btn btn-lg btn-primary" href="#" role="button">View navbar docs »</a>
        </p>
      </div>

    </div>

<div class="container">
    <div class="row"> 
        <button type="button" class="btn">Basic</button>
        <button type="button" class="btn btn-default">Default</button>
        <button type="button" class="btn btn-primary">Primary</button>
        <button type="button" class="btn btn-success">Success</button>
        <button type="button" class="btn btn-info">Info</button>
        <button type="button" class="btn btn-warning">Warning</button>
        <button type="button" class="btn btn-danger">Danger</button>
        <button type="button" class="btn btn-link">Link</button>
    </div>

    <div class="row">
        <div id="dvDisplay" class="alert alert-custom"><b>Son candidatos estudiantes egresados</b> <small><br>Ud. está en: </small><div class="clsLine"> Datos OTI de Alumno </div><br>
<form class="form-horizontal" name="frmregtes" method="post" action="http://vriunap.pe/pilar/es/repositorio/execInNew">
  <fieldset>
      <!-- Text input-->
      <div class="form-group">
          <div>
            <input name="codigo" type="hidden" value="990100" readonly="">
          </div>
          <label class="col-md-4 control-label"> FACULTAD </label>
          <div class="col-md-7">
              <input name="idfacu" type="hidden" value="8" readonly="">
              <input name="facultad" type="text" class="form-control input-md" value="INGENIERÍA DE MINAS" readonly="">
          </div>
      </div>
      <!-- Text input-->
      <div class="form-group">
          <label class="col-md-4 control-label"> Escuela Profesional </label>
          <div class="col-md-7">
              <input name="idcarr" type="hidden" value="15" readonly="">
              <input name="escuela" type="text" class="form-control input-md" value="INGENIERIA DE MINAS" readonly="">
          </div>
      </div>

      <!-- Text input-->
      <div class="form-group">
          <label class="col-md-4 control-label"> Nombres Completos </label>
          <div class="col-md-7">
              <input type="text" class="form-control input-md" value="MIGUEL ANGEL MAMANI QUISPE">
              <input name="nombes" type="hidden" value="MIGUEL ANGEL">
              <input name="apells" type="hidden" value="MAMANI QUISPE">
          </div>
      </div>

      <!-- Textarea input: long names -->
      <div class="form-group">
          <label class="col-md-4 control-label"> Listados Multi-tesistas: </label>
          <div class="col-md-7">
            <textarea name="multis" class="form-control" rows="4" id="comment" placeholder="Causa aca pones los 2 o tres APELLIDOS y NOMBRES en MAYUSCULAS y borras el anterior, o te azoto !"></textarea>
          </div>
      </div>

      <!-- Text input-->
      <div class="form-group">
          <label class="col-md-4 control-label"> D.N.I. </label>
          <div class="col-md-7">
              <input name="numdni" type="text" class="form-control input-md" value="48035">
          </div>
      </div>

      <!-- Text input-->
      <div class="form-group">
          <label class="col-md-4 control-label"> Último Semestre reg. </label>
          <div class="col-md-7">
              <input name="ultsem" type="text" class="form-control input-md" value="- ()">
          </div>
      </div>
      <!-- Text input-->
      <div class="form-group">
          <label class="col-md-4 control-label"> Numero de Telef. Celular </label>
          <div class="col-md-7">
              <input name="celula" type="number" placeholder="Ej. 999001122" class="form-control input-md" required="" autofocus="">
          </div>
      </div>
      <!-- Text input-->
      <div class="form-group">
          <label class="col-md-4 control-label"> Correo Personal </label>
          <div class="col-md-7">
              <input name="correo" type="email" placeholder="e-mail" class="form-control input-md">
          </div>
      </div>

      <!-- Text input-->
      <div class="form-group">
          <label class="col-md-4 control-label"> Enlace 1 </label>
          <div class="col-md-7">
              <input name="link1" type="text" placeholder="http://" class="form-control input-md">
          </div>
      </div>

      <!-- Text input-->
      <div class="form-group">
          <label class="col-md-4 control-label"> Enlace 2 </label>
          <div class="col-md-7">
              <input name="link2" type="text" placeholder="http://" class="form-control input-md">
          </div>
      </div>


      <!-- Text input-->
      <div class="form-group">
          <label class="col-md-4 control-label"> Fecha de Sustentación </label>
          <div class="col-md-7">
              <input name="fechasus" type="date" class="form-control input-md">
          </div>
      </div>

      <!-- Checks input-->
      <div class="form-group">
          <label class="col-md-3 control-label"> Elementos Adjuntos </label>
          <div class="col-md-2">
              <div class="checkbox">
                  <label><input type="checkbox" value="">Empastado</label>
              </div>
          </div>
          <div class="col-md-2">
              <div class="checkbox">
                  <label><input type="checkbox" value="">CD-ROM</label>
              </div>
          </div>
          <div class="col-md-2">
              <div class="checkbox">
                  <label><input type="checkbox" value="">Constancia</label>
              </div>
          </div>
          <div class="col-md-2">
              <div class="checkbox">
                  <label><input type="checkbox" value="">Material Extra</label>
              </div>
          </div>
      </div>
        <hr>
      <!-- Button (Double) -->
      <div class="form-group">
          <div class="col-md-7"> </div>
          <div class="col-md-4">
              <input type="submit" class="btn btn-success col-xs-12" value="Registrar en Repositorio">
          </div>
      </div>
  </fieldset>
</form>
</div>
    </div>
</div>

</body>

</html>


<!-- === -->
<div id="vriLogin" class="modal fade" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-md" role="document">
    <br>
    <div class="modal-content">
      <div class="modal-header" style="background: #212321; color:white !important; text-align: center;font-size: 30px;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" style="color: white">&times;</span></button>
        <h4 class="modal-title" id="myLabel"> Inicio de Sesión VRI </h4>
      </div>

      <div class="gradTipo2" style="height: 70px; padding: 10px; margin-bottom: 10px">
          <center> <img height=50 src="<?=base_url("/absmain/imgs/vri_top.png")?>"> </center>
      </div>

      <div class="modal-body">
        <form name="loginvri" class="form-horizontal" method="post">
        <fieldset>
            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-4 control-label"> Su Correo </label>
                <div class="col-md-7">
                    <input id="edt1" name="user" type="text" class="form-control input-md" placeholder="Correo registrado">
                </div>
                <div class="col-md-1"></div>
            </div>
            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-4 control-label"> Contraseña </label>
                <div class="col-md-7">
                    <input id="edt2" name="pass" type="password" class="form-control input-md" placeholder="***">
                </div>
                <div class="col-md-1"></div>
            </div>
            <!-- alert bar -->
            <div class="form-group">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    <div class="alert alert-success" id="pnlmsg">
                        <b>La cuenta de correo que registró en el VRI.</b>
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>
        </fieldset>
        </form>
      </div>

      <div class="modal-footer">
        <button class="btn btn-primary" onclick="vriLogin()" > Acceder a mi Cuenta </button>
        <button class="btn btn-danger" data-dismiss="modal"> Cerrar ventana </button>
      </div>
    </div>
  </div>
</div>


<script>
  $('#vriLogin').modal( {keyboard:false} );
</script>