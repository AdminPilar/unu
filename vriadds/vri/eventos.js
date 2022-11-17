/*
 *  Vicerrectorado de Investigacion - WebPage
 *  -----------------------------------------
 *
 *  Plataforma integrada VRI  - [ codename : florcita ]
 *
 *  Team Developer:
 *    M.Sc. Ramiro Pedro Laura Murillo
 *    Ing. Fred Torres Cruz
 *    Ing. Julio Cesar Tisnado Puma
 */



$(document).ready(function() {
    $('.boxes').cycle( {fx: 'fade', timeout: 8000} );
});


function vriLogin()
{
    jVRI("#pnlmsg").html( "Submit" );
    jVRI.ajax({
        url  : "vri/web/jsQryLogin",
        data : new FormData(loginvri),
        success: function( arg )
        {
            res = eval( arg );

            $('#pnlmsg').html("<b>"+res[0].msg+"</b>");
            $('#pnlmsg').attr('class', "alert alert-" +
                ( (res[0].error)? "danger":"success") );

            jVRI("#edt1").val("");
            jVRI("#edt2").val("");
            jVRI("#edt1").focus();

            if( !res[0].error )
                location.href = "";
        }
    });
}


function consulta(){
    alert("Consulta");
    jVRI.ajax({
        url     :"pci/web/nuevaConsulta/",
        data    :new FormData(qryDNI),
        success : function (arg){
            alert(arg);
        } 
    });
}
function vriPopLogin()
{
    jVRI.ajax({
        url : "vri/web/jsDlgLogin",
        success : function( arg ) {
            jVRI("#idPops").html( arg );
            $("#vriLogin").modal();
        }
    });
}

function drawBars(canvDest, strlabel, arrLabels, arrFrecs)
{
    //// var ctx = document.getElementById("myChart").getContext('2d');
    var ctx = canvDest.getContext("2d");
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: arrLabels,    //// ["Red", "Blue", "Yellow", "Green", "Purple", "Orange"],
            datasets: [{
                label: strlabel,  //// 'Frecuencia de acceso por Sistema Operativo',
                data: arrFrecs,   //// [12, 19, 3, 5, 2, 3],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(255, 99, 132, 0.2)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(255,99,132,1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero:true
                    }
                }]
            }
        }
    });
}

function loadEvLoc(id,ctrl){
    jVRI("#"+id).load(ctrl);
}

