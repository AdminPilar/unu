<div id='reg'>
<small><p style="color: black; line-height: 1.23; font-size: 11px">
        <?php
            echo "$data->apellidos, $data->nombres";
            echo "<br>( $data->escuela )";
        ?>
</p></small>

<input name="mail" type="email" placeholder="Mi Correo personal" required/>
<input name="celu" type="number" placeholder="Número celular" required maxlength="9" oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"/>
<input name="dire" type="text" placeholder="Dirección" required/>
<input id="pass1" name="pass1" type="password" placeholder="contraseña" required/>
<input id="pass2" name="pass2" type="password" placeholder="repite la contraseña" required/>
<button type=submit class="login-btn-tesista"> Registrame </button>
</div>
