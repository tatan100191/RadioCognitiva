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
            <form role="form" action="php/contacto.php" method="POST" id="contacto" title="Nombre">
                <label for="nombre">Nombre</label>
                <input name="nombre" type="text" required="required" id="nombre" placeholder="nombre" tabindex="1" title="Nombre">
                <br>
                <label for="email">Email</label>
                <input name="email" type="email" required="required" id="email" placeholder="email" tabindex="2" title="Email">
                <br>
                <label for="telefono">Teléfono</label>
                <input name="telefono" type="text" id="telefono" placeholder="telefono" tabindex="3" title="Telefono">
                <br>
                <label for="ciudad">Ciudad</label>
                <input name="ciudad" type="text" id="ciudad" placeholder="ciudad" tabindex="4" title="ciudad">
                <br>
                <label for="pais">País</label>
                <input name="pais" type="pais" id="pais" placeholder="pais" tabindex="5" title="pais">
                <br>
                <label for="Mensaje">Mensaje</label>
                <textarea name="mensaje" rows="4" id="mensaje" placeholder="mensaje" tabindex="6"></textarea>
                <br>
                <input type="submit" name="enviar" tabindex="7" value="Enviar"><input type="reset" tabindex="8" value="Borrar">
                <input type="hidden" name="estado" value="1">
            </form>
        </div>
    </body>
</html>
