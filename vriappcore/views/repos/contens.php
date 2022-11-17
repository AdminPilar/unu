<body>

<div class="azul azulDeg">
	<img src="vriadds/repos/logo.png" width="86%">
</div>

<nav class="navbar navbar-inverse" role="navigation" style="border-radius:0px">
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
                <li class="active">
                    <a href="<?=base_url("repositorio")?>"> <b> Inicio </b>  <span class="sr-only">(current)</span></a>
                </li>
                <li>
                    <a href="http://repositorio.unap.edu.pe"> Repositorio de Tesis </a>
                </li>
                <li>
                    <a href="<?=base_url("repositorio")?>"> Revistas </a>
                </li>
                <li>
                    <a href="<?=base_url("repositorio")?>"> Contenidos </a>
                </li>
                <li>
                    <a href="<?=base_url("repositorio/admin")?>"> Admin </a>
                </li>
            </ul>
        </div>
    </div>
</nav>


<div class="container content"> <br>
    <div class="row">
        <div class="col-md-4"> <!-- bloque izq -->

            <div class="panel panel-info">
                <div class="panel-heading">
                    <h2 class="panel-title"> <span class="glyphicon glyphicon-ok-circle"></span> Generador de Tapa de Tesis </h2>
                </div>
                <div class="panel-body">
                    <form class="navbar-form" role="search" method="POST" onsubmit="return repoLoad('dvDisp','repo/web/genTapa/'+cod.value)">
                        <!-- Text input-->
                        <div class="input-group col-md-12">
                            <input id="cod" name="cod" type="text" class="form-control" placeholder="Código de Trámite" required="">
                            <div class="input-group-btn">
                                <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                            </div>
                        </div>
                    </form>
                    <p> Genere automaticamente su tapa si Ud. <b>sustentó bajo la Plataforma PILAR</b>, descarguelo e imprima. </p>
                    <img src="vriadds/repos/tapatesis.jpg" class="img-responsive">
                    <p> <br><small><b>Un servicio más de Plataforma y Desarrollo - VRI UNAP</b></small> </p>
                </div>
            </div>

        </div> <!-- bloque izq -->
        <div class="col-md-8">
            <div id="dvDisp">
                <iframe id="frmpdf" frameborder=0 width="100%" height=600></iframe>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-12 footer"> <br>
            <b>Universidad Nacional de Ucayali - PUCALLPA</b> <br>
            Repositorio Institucional - VRI UNAP <br>
            <b>Plataforma y Desarrollo - 2017</b>
        </div>
    </div>
</div>

</body>
