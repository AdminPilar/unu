<div class=row>
    <div class="col-md-3">
        <a href="/siraa/web/trabajos" class="btn btn-block btn-info"> Inicio </a>
        <a href="/siraa/web/postula" class="btn btn-block btn-success"> Enviar mi Trabajo </a>
        <a href="/siraa/web/estado" class="btn btn-block btn-success"> Ver mi estado </a>
    </div>

    <div class="col-md-1" style="background: #E0F0E0"></div>

    <div class="col-md-8">
        <h4> Estado de postulaciones: trabajos de investigación </h4>
        <form action="/siraa/web/grabPost" method="post" enctype="multipart/form-data">


            <!-- input area -->
            <div class="form-group row">
                <label class="col-sm-4 col-form-label"> <b>Documento de identidad</b> </label>
                <div class="col-sm-8">
                    <input name="ldni" type="text" class="form-control" placeholder="número  de  D.N.I.  ó  Pasaporte" required="" readonly>
                </div>
            </div>


            <!-- input area -->
            <div class="form-group row">
                <label class="col-sm-4 col-form-label"> <b>Correo electrónico</b> </label>
                <div class="col-sm-8">
                    <input name="mail" type="email" class="form-control" placeholder="@" required="" readonly>
                </div>
            </div>

            <hr>
            <div class="form-group row">
                <div class="col-sm-7">
                </div>
                <div class="col-sm-5">
                    <input type="submit" class="btn-success form-control" value="Consultar" disabled>
                </div>
            </div>
        </form>
    </div>
</div>
