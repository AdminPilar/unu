<div class="modal fade" id="vriLogin" role="dialog">

  <form name="loginvri" class="form-horizontal" method="post" onsubmit="vriLogin(); return false">

    <div class="modal-dialog">
    <br><br>
    <div class="modal-content">
      <div class="modal-header" style="background: #212321; color:white !important; text-align: center;font-size: 30px;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" style="color: white">&times;</span></button>
        <h4 class="modal-title" id="myLabel"> Inicio de Sesión VRI </h4>
      </div>

      <div class="gradTipo2" style="height: 70px; padding: 10px; margin-bottom: 10px">
          <center> <img height=50 src="<?=base_url("/absmain/imgs/vri_top.png")?>"> </center>
      </div>

      <div class="modal-body">
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
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-primary"> Acceder a mi Cuenta </button>
        <button class="btn btn-danger" data-dismiss="modal"> Cerrar ventana </button>
      </div>
    </div>

    </div>
  </form>
</div>



<script>
//  $('#vriLogin').modal( {keyboard:false} );
</script>
