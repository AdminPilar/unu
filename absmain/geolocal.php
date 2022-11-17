<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <title>Google Maps Geocodificación simple</title>

    <meta name="description" content="Google Maps Geocodificación simple">
    <script src="https://maps.googleapis.com/maps/api/js?sensor=true&language=es&key=AIzaSyAK86nb3RoksGVWqxFpe4BwIhD7R4FTUms"></script>

<style type="text/css">
html,body{margin-top:1%;padding:0;width:100%;height:500px;font-family:Trebuchet MS,verdana,arial;background-color:blue;}
#container{margin:0 auto;width:90%;height:100%;background-color:#ccc;padding:20px;}
#mapa{width:70%;height:100%;float:right}
#texto{width:25%;height:100%;float:left;vertical-align:middle;padding:2%}
#texto input.navi{font-size:18px;width:90%;height:30px;margin-bottom:10px}
</style>

<script type="text/javascript">
//<![CDATA[
var map, geocoder;
window.onload = function () {
  //<!--latitud y longitud que podemos cambiar una vez obtenidas-->
  //var latlng = new google.maps.LatLng(40.323892 , -3.852782 );
  //var latlng = new google.maps.LatLng(-15.8368537,-70.0233266);
  var latlng = new google.maps.LatLng(-15,864229, -69,997377);

  //<!--podemos aumentar o disminuir el zoom del mapa a mostrar (zoom: 15)-->
  var mapOptions = {
    zoom: 15,
    center: latlng,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  };
  map = new google.maps.Map(document.getElementById('mapa'), mapOptions);
  // llama a la funcion
  geocoder = new google.maps.Geocoder();
};

function codeAddress() {
  var address = document.getElementById('address').value;
  // Función completa de Geocoding
  geocoder.geocode({
    'address': address,
    //'LatLng': '-15.8368537,-70.0233266'
  }, function (results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
      document.getElementById('x').innerHTML = results[0].geometry.location.lat().toFixed(6);
      document.getElementById('y').innerHTML = results[0].geometry.location.lng().toFixed(6);
      map.setCenter(results[0].geometry.location);
      document.getElementById('direccion').innerHTML = results[0].formatted_address;
      var marker = new google.maps.Marker({
        map: map,
        position: results[0].geometry.location
      });
	  //podemos personalisar el toltip aqui
      infowindow = new google.maps.InfoWindow({
        content: '<h4>JK electricidad</h4>'+results[0].formatted_address + '<br> Latitud: ' + results[0].geometry.location.lat().toFixed(6) + '<br> Longitud: ' + results[0].geometry.location.lng().toFixed(6)
      });
      infowindow.open(map, marker)
    }
// Se detallan los diferentes tipos de error
else {
      alert('Geocode no tuvo éxito por la siguiente razón: ' + status)
    }
  })
};
//]]>
</script>
  </head>
  <body>
  <div id="container">
    <!--al introducir los datos obtendremos las coordenadas (LatLng )que nos permite cambiar  var latlng para personalizar nuestro mapa -->
<section id="texto">
  <h3>Dirección a localizar</h3>
  <!--se inserta la dirección completa que queremos mostrar en el mapa-->
  <p>Sobreescriba la dirección<br>puede indicar coordenadas lat,lng separadas por coma (,):</p>
      <input id="address" type="textbox" size="38" maxlength="80" value="" placeholder="Dirección o Lat, Lng" />
      <br>
	  <!--nos devuelve la latitud-->
      Latitud: <span style="color:red;" id="x"></span>
      <br>
	    <!--nos devuelve la longitud-->
      Longitud: <span style="color:red;" id="y"></span>
      <br>
	    <!--nos devuelve la direcion completa-->
      Dirección completa:<br><span style="color:blue;" id="direccion"></span>
      <br>
      <input type="button" class="navi" value="Localizar" onclick="codeAddress()">
    </section>
    <div id="mapa"></div>
	</div>
  </body>
</html>