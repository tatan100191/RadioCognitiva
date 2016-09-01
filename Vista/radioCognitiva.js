/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


$(document).ready(function(){
    main();
});


function main(){
    $(modalGrafica).dialog({ 
         autoOpen: false,  
         width: 950, 
         height: 650, 
         title: 'Graficar',
         open: function(event, ui) { $(".ui-dialog-titlebar-close").show();},
     });
     $(cargando).html("<img src='js/cargando.gif'> Cargando....")
     $(cargando).dialog({ 
         closeOnEscape: false,
         open: function(event, ui) { $(".ui-dialog-titlebar-close").hide();},
         autoOpen: false,  
         width: 250, 
         height: 120, 
         title: 'Cargando',
         resizable: false,
         
     });
    
    $(consultar).on("click", function(){
        console.log($(enlacesPrimarios).val());
        consultarDatos();
    });
    
    $(grafica).hide();
    $(grafica).on("click", function(){
       $(modalGrafica).dialog("open");
       $("input[name='grafica']").prop('checked', false); 
       $("#divCanvas").html('<canvas id="canvasGrafica" height="400" width="800%"/>');
    });
    $("input[name='grafica']").on("click", function(){
        $("#divCanvas").html('<canvas id="canvasGrafica" height="400" width="800%"/>');
        graficar($(this).val());
    });
}

function consultarDatos(){
     $(cargando).dialog("open");
    $.ajax({
                data:  {"accion": 1, "enlacesPrimarios" : $(enlacesPrimarios).val(), "enlacesSecundarios" : $(enlacesSecundarios).val()},
                url:   'cargarDatos.php',
                type:  'post',
                success:  function (response) {     
                        console.log(response);
//                        var enlaces = JSON.parse(response);
              
//                        var cadenaHtml = '<table id="tablaEnlaces">';
//                                
//                        cadenaHtml = cadenaHtml + "<thead>"+
//                                            "<tr>"+
//                                            "<td>Tipo Enlace</td>" +
//                                            "<td>Canal</td>" +
//                                            "<td>Coordenada X</td>" +
//                                            "<td>Coordenada Y</td>" +
//                                            "<td>Distancia Antena</td>" +
//                                            "<td>Potencia</td>" +
//                                            "<td>Sinr</td>" +
//                                            "<td>Tiempo</td>" +
//                                          "</tr>"+
//                                          "</thead>";
//                         cadenaHtml = cadenaHtml + "<tbody>";
//                        $.each(enlaces, function(i, item){
//                                        if(item.canal == 0){
//                                            cadenaHtml = cadenaHtml + "<tr style='color:red;' >";
//                                        }
//                                        else{
//                                            cadenaHtml = cadenaHtml + "<tr>" ; 
//                                        }
//                            cadenaHtml = cadenaHtml + 
//                                            "<td>" +item.tipoEnlace+"</td>" +
//                                            "<td>" +item.canal+"</td>" +
//                                            "<td>" +item.cordenadaX+"</td>" +
//                                            "<td>" +item.cordenadaY+"</td>" +
//                                            "<td>" +item.distanciaAntena+"</td>" +
//                                            "<td>" +item.potencia+"</td>" ;
//                                    console.log(cadenaHtml);
//                                            if (item.sinr == null){
//                                                cadenaHtml = cadenaHtml + "<td> </td>";
//                                            }
//                                            else {
//                                                cadenaHtml = cadenaHtml + "<td>" +item.sinr+"</td>" ;
//                                            }
//                                            cadenaHtml = cadenaHtml+"<td>" +item.tiempo+"</td>" +
//                                          "</tr>";
//                        });
//                         cadenaHtml = cadenaHtml + "</tbody></table>";
//                        $(divTablaEnlaces).html(cadenaHtml);
//                        configurarTabla();
                        $(grafica).show();
                        $(cargando).dialog("close");
                }
               });
}

function graficar(tipoGrafica){     
    $.ajax({
                data:  {"accion": 2,"tipoGrafica" : tipoGrafica},
                url:   'cargarDatos.php',
                type:  'post',
                success:  function (response) {     
                    var grafica = JSON.parse(response);
                    $("#titulo").html(grafica.titulo);
                    $("#labelY").text(grafica.labelY);
                    $("#labelX").text(grafica.labelX);
                    var canvas = $(canvasGrafica).get(0);
                    var ctx = canvas.getContext('2d');
                    var data = {
                        labels: [],
                        datasets: [
                            {
                                label: grafica.titulo,
                                fillColor: "rgba(220,220,220,0.2)",
                                strokeColor: "rgba(220,220,220,1)",
                                pointColoar: "rgba(220,220,220,1)",
                                pointStrokeColor: "#fff",
                                pointHighlightFill: "#fff",
                                pointHighlightStroke: "rgba(220,220,220,1)",
                            }
                            ]
                        };
                        var myNewChart = new Chart(ctx).Line(data, {
                            scaleOverride:true,
                            scaleSteps:grafica.pasos,
                            scaleStartValue:grafica.inicia,
                            scaleStepWidth: grafica.escala,
                              bezierCurve : true,
                        });
                        console.log(grafica);
                    $.each(grafica, function(i, item){
                        if (item.x != undefined){
                            myNewChart.addData([item.y], item.x);
                        }
                    });
                    
                }
    });
}



function configurarTabla(){
    $(tablaEnlaces).DataTable();
}