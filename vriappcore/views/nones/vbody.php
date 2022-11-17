<?php
?>

<!-- =============== -->
<!-- begin: top-menu -->
<!-- =============== -->
<nav class="navbar navbar-fixed-top" role="navigation" style="background: #808080; color: black">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display-->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li>
                    <!-- <a onclick="location.href=''" href="javascript:void(0)"> <b> Inicio </b>  <span class="sr-only">(current)</span></a> -->
                </li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li>
                <?php
                    $data = $this->gensession->GetSessionData();
                    if( $data ) {
                ?>
                    <div style="padding: 12px; color: white">
                        <span class="glyphicon glyphicon-user"></span>
                        <a href="javascript:void(0)" style="color: white"> <?=$data->userName?> </a>
                    </div>
                <?php } else { ?>
                    <a href="javascript:void(0)"  xdata-toggle="modal" xdata-target="#vriLogin">
                        Panel de Control &nbsp; <span class="glyphicon glyphicon-log-in"></span>
                    </a>
                <?php } ?>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- =============== -->
<!-- end: top-menu   -->
<!-- =============== -->


<!-- =============== -->
<!-- begin: top-logo -->
<!-- =============== -->

<div class="container-fluid" stylest="padding-top:45px; padding-bottom:0px">
    <div class="container" style="background: linear-gradient(152deg, rgba(63,94,251,1) 0%, rgba(161,70,252,1) 65%);">
        <div class="row" style="padding: 0px;">
            <!-- -->
            <div class="col-md-12" style="margin-top:35px; padding: 20px">
                <center>
                    <h1>
                        <h2 style="color: white">
                            <img src="/vriadds/epiei/finesi_rotulo.png" width="50%">
                        </h2>
                    </h1>
                </center>
            </div>
            <div class="col-md-12" style="padding: 15px; background: rgba(225,225,225); text-align: center; font-size: 20px">
                    Control de Asistencia Docente
            </div>
            <!-- -->
        </div>
    </div>
</div>

<!-- =============== -->
<!-- end: top-logo   -->
<!-- =============== -->

<div class="container-body"> <!-- begin: content-fluid -->
    <!-- Begin: Body Type 1 -->
    <div class="container" style="background: #F5F5F5; padding-top: 25px">
        <div class="row">
            <div id="dvMain" class="col-md-12">

            <?php
                //if( !$sess ):

                //echo "DBG: ";
                //print_r( $sess );
            ?>

            <?php if( !$sess ): ?>
                <div class="col-md-4 col-md-offset-4">
                    <div class="panel panel-default">
                        <div class="panel-heading"> Validación de datos </div>
                        <div class="panel-body">
                            <form onsubmit="return doLogin(this)">
                                <div class="form-group">
                                    <label for=""> Codigo Docente </label>
                                    <input name="codigo" type="number" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for=""> Número de DNI </label>
                                    <input name="numdni" type="password" class="form-control" required>
                                </div>
                                <div id="dvAlert"></div>
                                <hr style="margin: 7px">
                                <button type="submit" class="form-control btn-warning"> <span class="glyphicon glyphicon-search"></span> Verificar </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php else: ?>


                <div class="col-md-3">
                    <div class="panel panel-default">
                        <div class="panel-heading"> Menú </div>
                        <div class="panel-body">
                            <div class="list-group">
                                <a class="list-group-item" onclick="location.href=''" href="javascript:void(0)"> <b>Inicio</b> </a>
                                <a class="list-group-item" onclick="LxPost('#dvBody','/asistencias/web/mnuAsist')" href="javascript:void(0)"> <b>Mis asistencias</b> </a>
                                <a class="list-group-item" onclick="LxPost('#dvBody','/asistencias/web/mnuAcadem')" href="javascript:void(0)"> <b>Avance académico</b> </a>
                                <br>
                                <a class="list-group-item" onclick="LxPost('#dvBody','/asistencias/web/mnuLogRep')" href="javascript:void(0)"> Admin </a>
                                <br>
                                <a class="list-group-item" href="/asistencias/web/logout"> Cerrar Sesión</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ========================================================================================================= -->
                <!-- ========================================================================================================= -->
                <div class="col-md-9">
                    <div class="panel panel-default">
                        <div class="panel-body" id="dvBody">

                            <?php
                                $docente = $this->dbRepo->getSnapRow( "vwDocentes", "Id=$sess->userId" );
                                $carrera = $this->dbRepo->inCarreDoc( $docente->Id );

                                $media = $this->genapi->getDataPer( $sess->userDNI );
                                $foto = $media? $media->foto : "";
                            ?>
                            <div class="table-responsive">
                            <table class="table table-striped">
                                <tr>
                                    <td> Datos Personales </td>
                                    <td> <?=$docente->DatosNom?> </td>
                                    <td rowspan=5> <center> <img src="<?=$foto?>" border="1"> </td>
                                </tr>
                                <tr>
                                    <td> Categoria </td>
                                    <td> <?=$docente->Categoria?> </td>
                                </tr>
                                <tr>
                                    <td> Carrera Profesional </td>
                                    <td> <?=$carrera." - <b>".$docente->FacuAbrev?> </td>
                                </tr>
                                                                <tr>
                                    <td> Codigo de Docente </td>
                                    <td> <?=$docente->Codigo?> </td>
                                </tr>
                                                                <tr>
                                    <td> Fecha de Ingreso </td>
                                    <td> <?=$docente->FechaIn?> </td>
                                </tr>
                            </table>
                            </div>

                            <div class="col-md-6 col-md-offset-3">
                                <div id="reloj" class="alert alert-info" style="font-size: 25px; font-weight: bold; text-align: center">
                                    --:--:--
                                </div>
                                <FORM onsubmit="return doSignIn()">
                                    <button id="btnsig" type="submit" class="form-control btn-warning"> <span class="glyphicon glyphicon-search"></span> MARCAR / FIRMAR </button>
                                </FORM>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- ========================================================================================================= -->
                <!-- ========================================================================================================= -->
            <?php endif; ?>

            </div>
            <!-- == Column One == -->
            <div class="col-md-3 col-sm-3 col-xs-12 clsAdaptWidth">
                <a href="javascript:void(0)"> <span class="glyphicon glyphicon-search"></span> </a> |
                <a href="javascript:void(0)"> <span class="glyphicon glyphicon-envelope"></span> </a> |
                <a href="javascript:void(0)" onclick="LxPost('#dvBody','/asistencias/web/mnuLogRep')"> <span class="glyphicon glyphicon-print"></span> </a>
            </div>
        </div>
    </div>
</div>
<br>

<!--
<center>
    <small>
    <b>Oficina de Plataforma, Investigación y Desarrollo</b> <br>
    Vicerrectorado de Investigación <br>
    Universidad Nacional de Ucayali - PUCALLPA
    </small>
</center>
-->


<script>
function doLogin( frm )
{
    LxPost( "#dvAlert", "/asistencias/web/login", new FormData(frm) );
    return false;
}

function doSignIn()
{
    if( confirm("La entrada se marcará con la hora en Pantalla, desea continuar?") ){

        LxPost( "#btnsig", "/asistencias/web/sign", null, ( res ) =>{

            // acciones post procesado
            console.log( res );
            $( "#btnsig" ).attr( "disabled", true );
            $( "#btnsig" ).removeClass( "btn-warning" );
            $( "#btnsig" ).addClass( "btn-default" );
        } );
    }

    return false;
}

function doAsis(tip)
{
    var a = document.createElement('a');
    a.target = "_blank";
    a.href   = "doList/"+tip+"/"+obs.value;
    a.click();
}

function doClock()
{
    var hoy = new Date();
    var h = hoy.getHours();
    var m = hoy.getMinutes();
    var s = hoy.getSeconds();

    var r = document.getElementById("reloj");
    if( r )
        r.innerHTML = h+":"+m+":"+s;
    ///jVRI("#reloj").html( h+":"+m+":"+s );
}

setInterval( doClock, 500 );


//-------------------------------------------------------------------------------------
// tomado prestado
//-------------------------------------------------------------------------------------
function LxPost( dest, urlx, args, hook )
{
    jVRI( dest ).html( "<small><b>Procesando..." );
    jVRI.ajax({
        url     : urlx,
        data    : args,
        success : function( res ){

            jVRI( dest ).html( res );
            if( hook ) hook( res );
        }
    });
    return false;
}
</script>

