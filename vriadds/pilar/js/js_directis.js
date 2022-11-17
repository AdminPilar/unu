// #
// # VRI - Universidad Nacional de Ucayali - PUCALLPA 2020
// # Ing. Ramiro Pedro, Ing. Fred Torres, Ing. Julio Tisnado
// #

function jsMdlSorteo(url,num){
	jVRI("#mdlContCord").html(" ");
	$("#cordModal").modal("show");
	jVRI("#mdlContCord").load(url+"/"+num);
    // jVRI("#cntSor").load(url+"/"+num);
}

function popSaveSort(id){
    dita = new FormData(sorT);
    
    jVRI.ajax({
        url  : "cordinads/inDoSorteo/"+id,
        data :  dita,
        success: function( arg )
        {
          $("#sortis").html( arg );
          $("#panelCord").load("cordinads/vwProy2018");
        }
    });
}

function enableSave() {
   if( $("#linC").is(":checked")&& $("#directC").is(":checked")&& $("#cumpleC").is(":checked")&& $("#aceptoC").is(":checked")){
        $("#modal-btn-si").removeAttr('disabled');
    }
        else{
        $("#modal-btn-si").attr("disabled","disabled");
      }
}