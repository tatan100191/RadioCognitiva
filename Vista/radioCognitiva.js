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
         width: 850, 
         height: 550, 
         title: 'Graficar'
     });
    
    $(consultar).on("click", function(){
        console.log($(enlacesPrimarios).val());
        consultarDatos();
    });
    
    $(grafica).hide();
    $(grafica).on("click", function(){
       $(modalGrafica).dialog("open");
       $("input[name='grafica']").prop('checked', false); 
       $("#divCanvas").html('<canvas id="canvasGrafica" height="400" width="700%"/>');
    });
    $("input[name='grafica']").on("click", function(){
        graficar($(this).val());
    });
}

function consultarDatos(){
    $.ajax({
                data:  {"accion": 1, "enlacesPrimarios" : $(enlacesPrimarios).val(), "enlacesSecundarios" : $(enlacesSecundarios).val()},
                url:   'cargarDatos.php',
                type:  'post',
                success:  function (response) {     
                        var enlaces = JSON.parse(response);
              
                        var cadenaHtml = '<table id="tablaEnlaces">';
                                
                        cadenaHtml = cadenaHtml + "<thead>"+
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
                         cadenaHtml = cadenaHtml + "</tbody></table>";
                        $(divTablaEnlaces).html(cadenaHtml);
                        configurarTabla();
                        $(grafica).show();
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
                    var canvas = $(canvasGrafica).get(0);
                    var ctx = canvas.getContext('2d');
                    var data = {
                        labels: [],
                        datasets: [
                            {
                                label: "Usuarios por canal",
                                fillColor: "rgba(220,220,220,0.2)",
                                strokeColor: "rgba(220,220,220,1)",
                                pointColor: "rgba(220,220,220,1)",
                                pointStrokeColor: "#fff",
                                pointHighlightFill: "#fff",
                                pointHighlightStroke: "rgba(220,220,220,1)",
                            }
                            ]
                        };
                        var myNewChart = new Chart(ctx).Line(data, {
                                    bezierCurve : true,
                        });
                    $.each(grafica, function(i, item){
                        myNewChart.addData([item.y], item.x);
                    });
                    
                }
    });
}



function configurarTabla(){
    $(tablaEnlaces).DataTable();
}