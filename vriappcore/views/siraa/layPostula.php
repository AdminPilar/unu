<div class=row>
    <div class="col-md-3">
        <a href="/siraa/web/trabajos" class="btn btn-block btn-info"> Inicio </a>
        <a href="/siraa/web/postula" class="btn btn-block btn-success"> Enviar mi Trabajo </a>
        <a href="/siraa/web/estado" class="btn btn-block btn-success"> Ver mi estado </a>
    </div>

    <div class="col-md-1" style="background: #E0F0E0"></div>

    <div class="col-md-8">

        <b>
            Pasos para la postulación<br>
        </b>
        1. Llenado de formulario. <br>
        2. Adjuntar artículo. <a target="_blank" href="http://huajsapata.unap.edu.pe/ria/index.php/ria/article/view/476/384">(en base al formato)</a> <br>
        3. Envio de trabajos. <br>
        2. Respuesta de aceptación/rechazo via email y listado local. <br>
        <hr>

        <h4> Complete la ficha de envio de trabajos de investigación </h4>
        <form action="/siraa/web/grabPost" method="post" enctype="multipart/form-data">
            <!-- select -->
            <div class="form-group row">
                <label class="col-sm-4 col-form-label"> <b>Tipo de Participante</b> </label>
                <div class="col-sm-8">
                    <select name="tipo" class="form-control" autofocus="" required="">
                        <option value="">( seleccione )</option>
                        <option value="1"> ALUMNO </option>
                        <option value="2"> PROFESIONAL </option>
                    </select>
                </div>
            </div>

            <!-- select -->
            <div class="form-group row">
                <label class="col-sm-4 col-form-label"> <b>Nacionalidad</b> </label>
                <div class="col-sm-8">
                    <select name="pais" class="form-control" autofocus="" required="">
                        <option value="">( seleccione )</option>
                        <option value="1"> PERUANO </option>
                        <option value="2"> EXTRANJERO </option>
                    </select>
                </div>
            </div>

            <!-- input area -->
            <div class="form-group row">
                <label class="col-sm-4 col-form-label"> <b>Documento de identidad</b> </label>
                <div class="col-sm-8">
                    <input name="ldni" type="text" class="form-control" placeholder="número  de  D.N.I.  ó  Pasaporte" required="">
                </div>
            </div>

            <!-- input area -->
            <div class="form-group row">
                <label class="col-sm-4 col-form-label"> <b>Nombres y Apellidos</b> </label>
                <div class="col-sm-8">
                    <input name="apes" type="text" class="form-control" placeholder="" required="">
                </div>
            </div>

            <!-- input area -->
            <div class="form-group row">
                <label class="col-sm-4 col-form-label"> <b>Institución de procedencia</b> </label>
                <div class="col-sm-8">
                    <input name="orig" type="text" class="form-control" placeholder="Ejm. Nombre de Universidad">
                </div>
            </div>

            <!-- input area -->
            <div class="form-group row">
                <label class="col-sm-4 col-form-label"> <b>Correo electrónico</b> </label>
                <div class="col-sm-8">
                    <input name="mail" type="email" class="form-control" placeholder="@" required="">
                </div>
            </div>

            <div class="custom-file">
                <input name="arch" type="file" class="custom-file-input" id="validatedCustomFile" required>
                <label class="custom-file-label" for="validatedCustomFile">Seleccione archivo en formato PDF</label>
                <div class="invalid-feedback">Formato de archivo no válido</div>
            </div>


            <hr>
            <div class="form-group row">
                <div class="col-sm-7">
                </div>
                <div class="col-sm-5">
                    <input type="submit" class="btn-success form-control" placeholder="Enviar trabajo">
                </div>
            </div>
        </form>
    </div>
</div>


<script>
$('.custom-file-input').on('change', function(event) {
    var inputFile = event.currentTarget;
    $(inputFile).parent()
        .find('.custom-file-label')
        .html(inputFile.files[0].name);
});
</script>