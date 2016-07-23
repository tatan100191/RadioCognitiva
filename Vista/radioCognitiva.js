/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


$(document).ready(function(){
    main();
});


function main(){
    $("#consultar").on("click", function(){
        consultarDatos();
    });
    $("#grafica").hide();
}

function consultarDatos(){
    
    $.ajax({
                data:  {"enlacesPrimarios" : $(enlacesPrimarios).val(), "enlacesSecundarios" : $(enlacesSecundarios).val()},
                url:   'cargarDatos.php',
                type:  'post',
                success:  function (response) {     
                        var enlaces = JSON.parse(response);
                        console.log(enlaces);
                        var cadenaHtml = "<thead>"+
                                            "<tr>"+
                                            "<td>Tipo Enlace</td>" +
                                            "<td>Canal</td>" +
                                            "<td>Coordenada X</td>" +
                                            "<td>Coordenada Y</td>" +
                                            "<td>Distancia Antena</td>" +
                                            "<td>Potencia</td>" +
                                            "<td>Tiempo</td>" +
                                          "</tr>"+
                                          "</thead>";
                         cadenaHtml = cadenaHtml + "<tbody>";
                        $.each(enlaces, function(i, item){
                            console.log(item);
                            cadenaHtml = cadenaHtml +  
                                        "<tr>"+
                                            "<td>" +item.tipoEnlace+"</td>" +
                                            "<td>" +item.canal+"</td>" +
                                            "<td>" +item.cordenadaX+"</td>" +
                                            "<td>" +item.cordenadaY+"</td>" +
                                            "<td>" +item.distanciaAntena+"</td>" +
                                            "<td>" +item.potencia+"</td>" +
                                            "<td>" +item.tiempo+"</td>" +
                                          "</tr>";
                        });
                         cadenaHtml = cadenaHtml + "</tbody>";
                        $("#tablaEnlaces").html(cadenaHtml);
                        configurarTabla();
                        $("#grafica").show();
                }
                
               });
}


function configurarTabla(){
    $("#tablaEnlaces").DataTable();
}