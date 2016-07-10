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
                try{
                alert("llamando.....");
                var tipoUsuario = document.getElementById("tipUsuario").value;
                var tiempoLlamada = 0;
                tiempoLlamada = document.getElementById("tllamada").value;
                                
                alert("tiempoLlamada...." + tiempoLlamada);
                alert("tipoUsuario...." + tipUsuario);
                var _operacion="insLlamada";
        $.ajax({
                data:  {"_operacion" : _operacion, "tLlamada" : tiempoLlamada, "tipUsu" : tipoUsuario},
                url:   'ConsultasAjax.php',
                type:  'post',
                success:  function (response) {
//                    location.reload();
                    $("#div1").html(response);
                    console.log(response.variable);
                }, 
                error: function (xhr) {
                    alert('ocurrio un error: ' + xhr.status + ' ' + xhr.statusText);
                }
        });
    }catch(e)  {
           console.log(e instanceof ReferenceError);
           console.log(e.message);                   
           console.log(e.name);  
    }
            }
        </script>
    </head>
    <body >
        <?php
        include_once ("../Modelo/Facade.php"); 
        $facade = new Facade();
        $tiempo = 0;
        ?>
        <div class='container'>
        <div class="col-md-6 ">
            <div class='panel panel-default'>
            <?php 
//          aqui se generan los radio para la banda
            $facade->getBandas(); 
            ?>
             <div id="resultado">
             </div>
                <canvas id="miCanvas" height="800" width="534">
                <span>Tu explorador es anciano, renovalo si queres ver la magia</span>
            </canvas>
            </div>
        </div>
            <div class="col-md-6 ">
                <div class='panel panel-default'>
                    <ul class='list-group'>
                        <li class='list-group-item' >   
                            <div class='row'>
                                <div class='col-md-4'>
                                    <select id="tipUsuario" class="form-control">
                                        <option value="p">Primario</option>
                                        <option value="s">Secundario</option>
                                    </select>
                                </div>
                                <div class='col-md-4'>
                                    <label  for='tllamada'>Tiempo Llamada</label><input class='form-control' type="text" name='tllamada' id='tllamada'>
                                </div>
                                <div class='col-md-4 col-centered'>
                                    <input class='btn btn-primary' type='button' value='Llamar' onclick="insertarLlamada()">
                                </div>            
                            </div>
                        </li>
                        <li class='list-group-item' >   
                            <div class='row'>
                                <div class='col-md-4'>                        
                                    <div id="div1"></div>
                                </div>            
                            </div>
                        </li>

                        <li class='list-group-item' >   
                            <div class='row'>
                                <?php
                                $result =$facade->llenadoLlamadas();
                                ?>
                                <div class="llamadas">
                                    <table class="table table-striped">
                                        <tr>
                                            <th>Id Llamada</th>
                                            <th>Tipo usuario</th>
                                            <th>Canal</th>
                                            <th>Secuencia</th>
                                            <th>Colgar</th>
                                        </tr>
                                        <?php
                                            echo $result;
                                        ?>
                                    </table>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class ="list-group">
                    <div class='panel-150 panel panel-default col-md-6'>
                        <p>Indicadores</p>
                        <p>% ocuapacion de la banda</p>
                        <p>% Pu</p>
                        <p>% Su</p>
                        <p>% Total Usuarios</p>
                    </div>
                    <div class='panel-150 panel panel-default col-md-6'>
                        <p>Indice QoS</p>
                        <p>% Spectrum Handoff</p>
                        <p>% Spectrum Dropping</p>
                        <p>% Usuarios Bloqueados</p>
                        <p>ss</p>

                    </div>
                </div>
            </div>

        </div>

        <script>
            var tiempo = 0;
        function cargarBanda(banda){
             $.ajax({
                data:  {"banda" : banda, "operacion": "banda"},
                url:   'cargarDatos.php',
                type:  'post',
                success:  function (response) {          
                        var json = JSON.parse(response);
                        var canvas = document.getElementById('miCanvas');
                        canvas.height = ((parseInt(json.banda.banda.limitesuperior) +
                                11 + Object.keys(json.canal.canal).length) 
                                - parseInt(json.banda.banda.limiteinferior));
                        var contexto = canvas.getContext('2d');
                        contexto.clearRect(10, 10, 514, canvas.height);
                        contexto.fillStyle = '#000000';
                        // dibujamos un cuadrado con el color de llenado
                        contexto.fillRect(10, 10, 514, (parseInt(json.banda.banda.limitesuperior) +
                                11 + Object.keys(json.canal.canal).length) 
                                - parseInt(json.banda.banda.limiteinferior));
                        
                        contexto.fillStyle = '#d3d3d3';
                        var num = 1;
                        $.each(json.canal.canal, function(i, item) {
                            contexto.fillRect(11, (parseInt(item.limiteinferior) 
                                    - parseInt(json.banda.banda.limiteinferior)
                                    +10+ num), 512, parseInt(item.limitesuperior)-
                                    parseInt(item.limiteinferior));
                            num = num + 1;
                        });
                        
            }});         
     }
     
        function cargarLlamadas(banda){
             cargarBanda(banda);
             $.ajax({
                data:  {"banda" : banda, "operacion": "llamadas"},
                url:   'cargarDatos.php',
                type:  'post',
                success:  function (response) {   
                        var json = JSON.parse(response);
                        var canvas = document.getElementById('miCanvas'); 
                        var contexto = canvas.getContext('2d');
                        $.each(json, function(i, item) {
                            if(item.tipusuario == "p"){
                                contexto.fillStyle = '#2E9AFE';
                            }
                            else{
                                contexto.fillStyle = '#FF4000';
                            }
                            contexto.fillRect(11,  item.frecuencia, 512, 1);
                        });
                }});
            
        }
        
        function thread (){
            var resultado="ninguno";
            var banda=document.getElementsByName("banda");
            var band = true;
            var count = 0;
            while(band){
                // Recorremos todos los valores del radio button para encontrar el
                // seleccionado
                if (count >= 22000){
                    for(var i=0;i<banda.length;i++)
                    {
                        if(banda[i].checked)
                            resultado=banda[i].value;
                    }
                    
                    if (resultado != "ninguno"){
                        tiempo = tiempo +1;
                        document.getElementById("tiempo").innerHTML= "Tiempo: " + tiempo;
                        cargarLlamadas(resultado);
                        count = 0;
                        
                    }
                }
                count = count +1;
            }
        }
            
            Concurrent.Thread.create(thread); 
                
     
     

        </script>
        
        
    </body>
</html>
