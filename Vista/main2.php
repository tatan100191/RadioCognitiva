<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        
        <script type="text/javascript"  src="Concurrent.Thread.js"></script>
        <script type="text/javascript"  src="js/jquery-3.js"></script>
        <link rel="stylesheet" type="text/css" href="js/DataTables-1.10.12/media/css/datatables.jqueryui.css">
        <script type="text/javascript" charset="utf8" src="js/DataTables-1.10.12/media/js/jquery.datatables.js"></script>
        <script type="text/javascript"  src="radioCognitiva.js"></script>
        <link rel="stylesheet" href="css/bootstrap-3.3.6-dist/css/bootstrap.min.css" type="text/css">
        <title>Radio Cognitiva</title>
    </head>
    <body >
        <!--tipoEnlace, cordenadaX, cordenadaY, tiempo, canal, distanciaAntena, potencia-->
        <div id="contenedor" style="height: 100%; width: 70%; text-align: center; margin: auto">
        <div id="formulario" style="text-align: left; margin: auto">
            <table>
                <tr>
                <td><label for="enlacesPrimarios">Numero Enlaces Primarios</label></td>
                <td><input name="enlacesPrimarios" type="text" required="required" id="enlacesPrimarios" placeholder="enlacesPrimarios" tabindex="1" title="enlacesPrimarios"></td>
                </tr>
                <tr>
                 <td><label for="enlacesSecundarios">Numero de Enlaces Secundarios &nbsp;</label></td>
                 <td><input name="enlacesSecundarios" type="text" required="required" id="enlacesSecundarios" placeholder="enlacesSecundarios" tabindex="2" title="enlacesSecundarios"></td>
                </tr>
            </table>
            <input type="submit" id="consultar" name="enviar" tabindex="7" value="Enviar">
        </div>
            <br/>
        <div>
            <table id="tablaEnlaces">
                
            </table>
        </div>
            <button id="grafica" >
                Grafica
            </button>
        </div>
    </body>
</html>
