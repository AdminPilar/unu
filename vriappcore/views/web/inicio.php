<!-- =================================================================================
  --
  -- Vicerretorado de Investigación - UNA Puno : (2017)
  -- --------------------------------------------------
  --
  -- Project          : VRI Integrated (codename : BoobieMovie)
  -- Developers Team  : Platform and Development Department.
  -- Project Begins   : Feb 14, 2017 - today.
  -- Joined Services  : Web, Pilar, Fedu, Cursos, +
  --
  -- ================================================================================= -->
  
<!DOCTYPE html>
<html lang="es">
<head>
    <title> PILAR - Plataforma de Investigación Integrada a La Labor Académica con Responsabilidad </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="http://vriunap.pe/absmain/imgs/vrifavico.ico" sizes="192x192" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?=base_url("vriadds/pilar/css/style_web.css") ?>">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="<?=base_url('vriadds/pilar/js/js_web.js');?>"></script>
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!--
    <script src="<?=base_url("vriadds/vri/jquery.js")?>"></script>
    <script src="<?=base_url("vriadds/vri/bootstrap.js")?>"></script>
    -->
    <script src="<?=base_url("vriadds/lightajax.js")?>"></script>
    <script src="<?=base_url("vriadds/pilar/general.js")?>"></script>
    <script src="<?=base_url("vriadds/pilar/manager.js")?>"></script>

    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-97516323-1', 'auto');
      ga('send', 'pageview');
    </script>


    <!-- <link rel="stylesheet" href="vriadds/vri/bootstrap.min.css"> -->
       <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="http://vriunap.pe/vriadds/vri/style.css" rel="stylesheet">

</head>
<body>

<div class="container">
  <div align="center" >
      <div class="radiohead">
          <img id="fadeout" src="http://vriunap.pe/vriadds/vri/img/vriPNGrojosmall.png"  >
          <img id="fadein" src="http://vriunap.pe/vriadds/vri/img/vriPNGazulsmall.png" >
      </div>
      <!--
            <img src="http://vriunap.pe/vriadds/vri/img/vriPNGazulsmall.png" class="img-responsive center-block" >
      -->
      <div class="text-radiohead">
            <p style="font-size: 150%">
             Vicerrectorado de Investigación
            </p>
            Universidad Nacional de Ucayali - PUCALLPA<br><br>

            <text-anuncio> La platafoma de investigación y desarrollo  se encuentra en actualización </textoanuncio> 
            </div>
            <br><br>
            <a href="http://vriunap.pe/fedu" style="color:#1aa77b;"><img class="img-responsive" style="max-width: 200px;" src="http://vriunap.pe/fedu/includefile/logofedu.png"><br><i> CLICK AQUÍ</i></a>
      </div>
</div>



  <!-- MODAL  -->
   <div class="modal" id="myModal" role="dialog">
    <div class="modal-dialog">
      <br><br><br>
      <!-- Modal content-->
      <div class="modal-content modal-pilar">
        <div class="modal-header modal-pilar-title">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">COMUNICADO</h4>
        </div>
        <div id="game" style="padding: 15px; text-align: center"> <!-- remove -->
            <div class="modal-body modal-pilar modal-pilar-content">
                <p style="text-align: left">Estimados docentes y estudiantes estamos actualizando nuestros módulos la Plataforma PILAR estará activa el :<b>24-04-2017</b> <br> </p>
            </div>
            <div class="modal-footer">
                <a  style="text-decoration: none; color: #d03e73;" onclick="loadGame()"> Me siento con suerte, haré click! </a> 
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
            </div>
        </div> <!-- remove -->
      </div>

    </div>
  </div>
  <!-- /MODAL  -->
</body>
</html>