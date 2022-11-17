// #
// # VRI - Universidad Nacional de Ucayali - PUCALLPA 2020
// # Ing. Ramiro Pedro, Ing. Fred Torres, Ing. Julio Tisnado
// #

$(document).ready(function(){
//       $('#myModal').modal("show");
       $('#cmsg').hide();
       $('#pmsg').hide();
       $('#qmsg').hide();
});

function openNav($id) {
    if($id=='1tes'){
      document.getElementById("Tesistas").style.height = "100%";
      $('#Tesistas').addClass('tesista');
    }
    if($id=='2doc'){
      document.getElementById("Docentes").style.height = "100%";
      $('#Docentes').addClass('docente');
    }
    if($id=='3coord'){
      document.getElementById("Coordinadores").style.height = "100%";
      $('#Coordinadores').addClass('coord');
    }
}

function closeNav() {
    document.getElementById("Tesistas").style.height = "0%";
    document.getElementById("Docentes").style.height = "0%";
    document.getElementById("Coordinadores").style.height = "0%";
}

function register() {
  // $('.message a').click(function(){
    $('form').animate({height: "toggle", opacity: "toggle"}, "slow");
    $('#cmsg').hide();
    $('#pmsg').hide();
    $('#qmsg').hide();
  // });
}

