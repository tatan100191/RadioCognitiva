<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
        <script type="text/javascript"  src="Concurrent.Thread.js"></script>
        <link rel="stylesheet" href="css/bootstrap-3.3.6-dist/css/bootstrap.min.css" type="text/css">
        <title></title>
        <script TYPE="text/javascript"language="JavaScript">

            function insertarLlamada() {
                try {
                    alert("llamando.....");
                    var tipoUsuario = document.getElementById("tipUsuario").value;
                    var tiempoLlamada = 0;
                    tiempoLlamada = document.getElementById("tllamada").value;

                    alert("tiempoLlamada...." + tiempoLlamada);
                    alert("tipoUsuario...." + tipUsuario);
                    var _operacion = "insLlamada";
                    $.ajax({
                        data: {"_operacion": _operacion, "tLlamada": tiempoLlamada, "tipUsu": tipoUsuario},
                        url: 'ConsultasAjax.php',
                        type: 'post',
                        success: function (response) {
//                    location.reload();
                            $("#div1").html(response);
                            console.log(response.variable);
                        },
                        error: function (xhr) {
                            alert('ocurrio un error: ' + xhr.status + ' ' + xhr.statusText);
                        }
                    });
                } catch (e) {
                    console.log(e instanceof ReferenceError);
                    console.log(e.message);
                    console.log(e.name);
                }
            }
        </script>
    </head>
    <body >
        <!--tipoEnlace, cordenadaX, cordenadaY, tiempo, canal, distanciaAntena, potencia-->
        <div id="formulario">
            <form role="form" action="cargarDatos.php" method="POST" id="contacto" title="Nombre">
                <label for="enlacesPrimarios">Numero Enlaces Primarios</label>
                <input name="enlacesPrimarios" type="text" required="required" id="enlacesPrimarios" placeholder="enlacesPrimarios" tabindex="1" title="enlacesPrimarios">
                <br>
                <label for="enlacesSecundarios">Numero de Enlaces Secundarios</label>
                <input name="enlacesSecundarios" type="text" required="required" id="enlacesSecundarios" placeholder="enlacesSecundarios" tabindex="2" title="enlacesSecundarios">
                <br>
                <input type="submit" name="enviar" tabindex="7" value="Enviar"><input type="reset" tabindex="8" value="Borrar">
                <input type="hidden" name="estado" value="1">
            </form>
        </div>
    </body>
</html>
