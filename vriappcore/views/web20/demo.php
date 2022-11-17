<!--
--  Vicerrectorado de Investigación
--  Universidad Nacional de Ucayali
--  ----------------------------------
--
--  Sitio Web Setiembre 2020 - version SSL/TLS
--  Recodificado por: Ramiro Pedro Laura Murillo
--  * Modulos: SSL, Firmas digitales, Progressive App WPA
--             Smarthpone integration WPA
-->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Sitio web oficial del Vicerrectorado de Investigación, UNA Puno - Perú">
    <meta name="author" content="OPID - DGI - VRI">

    <!--
    <link rel="manifest" href="/vri/manifest.json">
    -->
    <link href="/absmain/imgs/vri.png" rel="icon" sizes="32x32">
    <meta name="msapplication-TileColor" content="#11579c">
    <meta name="msapplication-TileImage" content="/absmain/imgs/vri.png">
    <meta name="theme-color" content="#11579c">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" rel="stylesheet">
    <link href="/vriadds/web20/css/estilo0.css" rel="stylesheet">

    <!-- js/jqLightForm.js -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js"></script>
    <script src="/sumaq/public/js/LightAjax.b.js"></script>

    <title> Vicerrectorado de Investigación - UNAP <?=date("Y")?> </title>
</head>


<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary bg-gradient text-white fixed-top">
        <div class="container">

            <div class="collapse navbar-collapse" id="navbarResponsive">

                <img class='navbar-brand mr-0 mr-md-2 img-fluid obscur' width='32'src="" alt="">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="/"> Inicio
                            <span class="sr-only">(current)</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/pilar"> PILAR </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/fedu"> FEDU </a>
                    </li>
                    <!--
                    <li class="nav-item">
                        <a class="nav-link" href="/epgunap"> <b>PILAR EPG</b> </a>
                    </li>
                    -->
                </ul>
            </div>
            <a class="navbar-brand font-weight-bold" href="/"> VRI - UNAP </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>


    <div class="container p-4 mt-5" style="background: linear-gradient(143deg, rgba(36,40,42,1) 2%, rgba(13,85,131,1) 55%);">
        <div class="row">
            <div class="col-md-3 offset-md-9">
                <img src="/vriadds/vri/web/logo-vri2.png" class="img-fluid">
            </div>
        </div>
    </div>

    <div class="container mt-0 bg-primary bg-gradient text-white"> <!-- never padding here -->
        <div class="p-3 pl-0 pr-0">
            <div class="row">
                <div class="col-md-2 col-sm-6">
                    <a href="/pilar" class="btnMnx"> <i class="fa fa-fax"></i><br> PILAR </a>
                </div>
                <div class="col-md-2 col-sm-6">
                    <a href="/fedu" class="btnMnx"> <i class="fa fa-archive"></i><br> FEDU </a>
                </div>
                <div class="col-md-2 col-sm-6">
                    <a href="/epgunap" class="btnMnx"> <i class="fa fa-fax"></i><br> PILAR EPG </a>
                </div>
                <div class="col-md-2 col-sm-6">
                    <a href="/cursos" class="btnMnx"> <i class="fa fa-bell"></i><br> Cursos & Eventos </a>
                </div>
                <div class="col-md-2 col-sm-6">
                    <a href="/pilar/sustentas" class="btnMnx"> <i class="fa fa-fax"></i><br> Sustentaciones </a>
                </div>
                <div class="col-md-2 col-sm-6">
                    <a href="repositorio.unap.edu.pe" class="btnMnx"> <i class="fa fa-book"></i><br> Repositorio </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Identidad -->
    <!--
    <div class="container pt-2 pb-2 pr-0 pl-0">

        <div class="row">
            <div class="col-lg-6 col-md-6 mb-2">
                <div class="text-center">
                    Uno, dos... ultraviolento !
                </div>
            </div>
            <div class="col-lg-6 col-md-6 mb-2 mt-3">
                <div class="text-center">
                    <img class='img-fluid' width='60%'src="/absmain/imgs/unap.png" alt="">
                </div>
            </div>
        </div>
    </div>
    -->


    <!-- inicio: cuerpo para Layout -->
        <!-- inicio: cuerpo para Layout -->
    <div class="container"> <!-- pt-2 pr-0 pl-0"> -->

      <div class="pt-3" style="">  <!-- div padding -->
        <div class="row">
            <div class="col-md-7">

                <!-- card mesa de partes -->
                <div class="card mx-auto mt-0 shadow">
                    <div class="card-header bg-primary bg-gradient text-white"> <center> <b>Unidades del VRI</b> </center> </div>
                    <div class="card-body" id="dvMesa">
                    <!-- inicio -->
                        <div class="row">
                            <div class="col-md-6" style="padding-bottom: 15px">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title" style="font-size: 1.4rem">
                                            Dirección General de Investigación </h5>

                                        <p class="card-text" style="font-size: 0.9rem" align="justify">
                                            La DGI entre sus funciones figura la de apoyar a los docentes y estudiantes en la formulación de proyectos que van a ser desarrollados al interior de la PUCP; proyectos de I+D, así como proyectos que son subvencionados íntegramente por la universidad en sus diversas modalidades de fondos concursables.
                                        </p>

                                    </div>
                                    <div class="card-footer">
                                        <a onclick="curRegis(4)" href="javascript:void(0)" class="btn btn-sm btn-info"> Seguir leyendo &gt;&gt; </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" style="padding-bottom: 15px">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title" style="font-size: 1.4rem">
                                            Dirección de Emprendimiento </h5>
                                        <p class="card-text" style="font-size: 0.9rem" align="justify">
                                            La Dirección de Emprendimiento Empresarial es el ente encargado la formación de Cultura Empresarial entre los estudiantes y la comunidad Universitaria, para ello esta constante coordinación con distintas universidades para promover eventos de capacitación asi como de asesoramiento.
                                        </p>

                                    </div>
                                    <div class="card-footer">
                                        <a onclick="curRegis(4)" href="javascript:void(0)" class="btn btn-sm btn-info"> Seguir leyendo &gt;&gt; </a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    <!-- fin -->
                    </div>
                </div>
                <!-- card mesa de partes -->

                <!-- card Cursos -->
                <div class="card mx-auto mt-0 shadow">
                    <div class="card-header bg-primary bg-gradient text-white"> <center> <b>Cursos & Eventos</b> </center> </div>
                    <div class="card-body" id="dvMesa">
                        <div class="row">
                            <div class="col-md-4" style="padding-bottom: 15px">
                                <div class="card">
                                    <img class="card-img-top" src="/vriadds/web20/img/cursos.jpg" style="opacity: 0.4">
                                    <div class="card-body">
                                        <h5 class="card-title" style="font-size: 1.0rem; color: gray">
                                               Inducción al Emprendimiento: Aprende a emprender </h5>
                                        <p class="card-text" style="font-size: 0.9rem" align="justify">
                                            <b> Fecha:</b> del 09 al 11 de setiembre 2020 </p>

                                    </div>
                                    <div class="card-footer">
                                        <a class="btn btn-sm btn btn-outline-primary" disabled> Finalizado </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4" style="padding-bottom: 15px">
                                <div class="card">
                                    <img class="card-img-top" src="/vriadds/web20/img/cursos.jpg" style="opacity: 0.4">
                                    <div class="card-body">
                                        <h5 class="card-title" style="font-size: 1.0rem; color: gray">
                                            DISCUSIONES EPISTEMOLÓGICAS DE CONSTRUCCIÓN DISCIPLINARIA  </h5>
                                        <p class="card-text" style="font-size: 0.9rem" align="justify">
                                            <b>Fecha:</b> del 17 al 20 de diciembre de 2019      </p>

                                    </div>
                                    <div class="card-footer">
                                        <a class="btn btn-sm btn btn-outline-primary" disabled> Finalizado </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4" style="padding-bottom: 15px">
                                <div class="card">
                                    <img class="card-img-top" src="/vriadds/web20/img/cursos.jpg" style="opacity: 0.4">
                                    <div class="card-body">
                                        <h5 class="card-title" style="font-size: 1.0rem; color: gray">
                                            Taller de capacitación sobre uso de software legal CASE y redacción de Escritos Procesales </h5>
                                        <p class="card-text" style="font-size: 0.9rem" align="justify">
                                            <b>Fecha:</b> 19 de setiembre 2019 </p>

                                    </div>
                                    <div class="card-footer">
                                        <a class="btn btn-sm btn btn-outline-primary" disabled> Finalizado </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- fin card: Cursos -->

                <!-- card: Cifras -->
                <div class="card mx-auto mt-0 shadow">
                    <div class="card-header bg-primary bg-gradient text-white"> <center> <b>Indicadores</b> </center> </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 col-sm-6">
                                <a href="" class="btnMnx"> <i class="fa fa-fax"></i><br> Sustentaciones 2018 </a>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <a href="" class="btnMnx"> <i class="fa fa-fax"></i><br> Sustentaciones 2019 </a>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <a href="" class="btnMnx"> <i class="fa fa-fax"></i><br> Sustentaciones 2020 </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- card: Cifras -->

            </div>

            <!-- AREA DERECHA FB + Anuncion -->
            <div class="col-md-5 pull-right">
                <div class="card mx-auto mt-0 shadow">
                    <!--
                    <div class="card-header bg-gray"> <center> <b>Accesos</b> </center> </div>
                    <div class="card-body">
                        <div class="list-group">
                            <a id="mnuCami" href="" class="list-group-item list-group-item-action"> Buscar Trámites / Seguimientos </a>
                            <br>
                            <a id="mnuHelp" href="" class="list-group-item list-group-item-action"> <b>Ayuda / Manual de uso</b> </a>
                        </div>
                    </div>
                    <hr>
                    -->
                    <!-- face -->
                    <div class="card-header bg-primary bg-gradient text-white"> <center> <b>Nuestro facebook</b> </center> </div>
                    <div class="card-body">
                        <iframe src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fweb.facebook.com%2Fvriunap%2F%3Fmodal%3Dadmin_todo_tour&amp;tabs=timeline&amp;width=900&amp;height=520&amp;small_header=true&amp;adapt_container_width=false&amp;hide_cover=true&amp;show_facepile=true&amp;appId"
                                width="100%" height="520" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowtransparency="true" allow="encrypted-media"></iframe>
                    </div>

                    <hr>
                    <div class="card-header bg-primary bg-gradient text-white"> <center> <b>Avisos</b> </center> </div>
                    <div class="card-body">

                        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                            <ol class="carousel-indicators">
                                <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                                <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                            </ol>
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="/vriadds/web20/curs/poster20.jpg" class="img-fluid">
                                </div>
                                <div class="carousel-item">
                                    <img src="/vriadds/web20/img/sustonline.jpeg" class="img-fluid">
                                </div>
                                <div class="carousel-item">
                                    <img src="/vriadds/web20/curs/orcidweb.jpg" class="d-block w-100" alt="...">
                                </div>
                            </div>
                            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
      </div> <!-- div padding -->
    </div>



    <div class="bg-dark mt-3 text-white" style="font-size: 12px">

        <div class="container">
        <div class="row">

            <div class="col-sm-12 col-md-3 p-3">
                <div class="footer-item">
                    <a href="" id="footer-logo" class="site-logo"> <img src="/vriadds/vri/web/logo_footer.png"> </a>
                    <p id="footer-slogan">Universidad Nacional de Ucayali</p>
                    Mail - 1: viceinvestigacion@unap.edu.pe <br>
                    Mail - 2: dginvestigacion@unap.edu.pe
                    <div class="social-btn fondo-bottom">
                        <a class="icon-youtube" href="https://www.youtube.com/channel/UCuqHXK8NlDMN3uK2S6aHFew" target="_blank"></a>
                        <a class="icon-twitter-bird" href="https://www.twitter.com/vriunap" target="_blank"></a>
                        <a class="icon-facebook-rect" href="https://www.facebook.com/vriunap" target="_blank"></a>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-3 footer-info">
                <div class="p-3">
                    <h5>Contáctos</h5>
                    <p>Av. Floral Nº 1153, Ciudad de Puno - Perú</p>
                    <p class="fondo-bottom">
                        Teléfono: +51 51 365054 <br />
                    </p>
                </div>
            </div>

            <div class="col-sm-12 col-md-3 p-3">
                <h5> Enlaces referenciales </h5>
            </div>

            <div class="col-sm-12 col-md-3 p-3">
                <div id="fuckDis" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <a href="https://www.scielo.org/php/index.php?lang=es" target="_blank">
                                <img src="/vriadds/vri/web/interes/Recurso1.png" class="d-block w-100">
                            </a>
                        </div>
                        <div class="carousel-item">
                            <a href="https://www.wiley.com/WileyCDA/" target="_blank">
                                <img src="/vriadds/vri/web/interes/Recurso2.png" class="d-block w-100">
                            </a>
                        </div>
                        <div class="carousel-item">
                            <a href="https://www.scopus.com/" target="_blank">
                                <img src="vriadds/vri/web/interes/Recurso3.png" class="d-block w-100">
                            </a>
                        </div>
                        <div class="carousel-item">
                            <a href="https://www.comunicacionunap.com/index.php/rev" target="_blank">
                                <img src="/vriadds/vri/web/interes/Recurso4.png" class="d-block w-100">
                            </a>
                        </div>
                        <div class="carousel-item">
                            <a href="https://rpu.edu.pe/" target="_blank">
                                <img src="/vriadds/vri/web/interes/Recurso5.png" class="d-block w-100">
                            </a>
                        </div>
                        <div class="carousel-item">
                            <a href="https://endnote.com/" target="_blank">
                                <img src="/vriadds/vri/web/interes/Recurso6.png" class="d-block w-100">
                            </a>
                        </div>
                        <div class="carousel-item">
                            <a href="https://www.sciencedirect.com/" target="_blank">
                                <img src="/vriadds/vri/web/interes/Recurso7.png" class="d-block w-100">
                            </a>
                        </div>
                        <div class="carousel-item">
                            <a href="https://dina.concytec.gob.pe/appDirectorioCTI/" target="_blank">
                                <img src="/vriadds/vri/web/interes/Recurso8.png" class="d-block w-100">
                            </a>
                        </div>
                        <div class="carousel-item">
                            <a href="https://huajsapata.unap.edu.pe/ria/index.php/ria" target="_blank">
                                <img src="/vriadds/vri/web/interes/Recurso9.png" class="d-block w-100">
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        </div>
    </div>



    <!-- Footer -->
    <footer class="py-4 bg-dark bg-gradient text-white" style="font-size: 12px">
        <div class="container text-center">
            <small> <b>Oficina de Plataforma de Investgación y Desarrollo</b> </small> <br>
            <small> Vicerrectorado de Investigación </small> <br>
            <small> Universidad Nacional de Ucayali </small>
            <p class="m-0 text-light">UNAP &copy; VRI <?=date("Y")?> </p>
        </div>
    </footer>

</body>

</html>
