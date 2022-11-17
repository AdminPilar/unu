<!-- Login Admin -->
<div class="col-md-12 login-admin">
    <div class="container col-xs-12 col-sm-offset-2 col-sm-8 col-md-offset-3 col-md-6 col-lg-offset-5 col-lg-3">
        <div class="panel panel-default ">
            <div class="login-panel panel-heading ">
                <h1>Login</h1>
            </div>
            <form method="post" action="<?=base_url("pilar/admin/login")?>">
            <div class="panel-body">
                <div class="form-group">
                    <div class="input-group">
                    <span class="input-group-addon">
                    <i class="glyphicon glyphicon-user" ></i>
                    </span>
                    <input name="user" type="text" class="form-control" placeholder="Usuario" required="" autofocus autocomplete="off" />
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                    <span class="input-group-addon">
                    <i class="glyphicon glyphicon-lock" ></i>
                    </span>
                    <input name="pass" type="password" class="form-control" placeholder="Contraseña" required="" />
                    </div>
                </div>
                <button id="btnLogin"  class="btn btn-default" >
                    ENTRAR  <i class="glyphicon glyphicon-log-in"></i>
                </button>
            </div>
            </form>
        </div>
    </div>
</div>
<!-- /Login Admin -->