<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
            <?php
            include_once ("../Modelo/Manejador.php");
            include_once("../Modelo/Llamada.php");
//            $numero = count($_GET);
            $numero = count($_POST);
//            $tags = array_keys($_GET);// obtiene los nombres de las varibles
            $tags = array_keys($_POST);// obtiene los nombres de las varibles
//            $valores = array_values($_GET);
            $valores = array_values($_POST);
            for($i=0;$i<$numero;$i++){
                $$tags[$i]=$valores[$i];
            }
            //operacion para insertar una llamada nueva
            
            if($_operacion='insLlamada'){
                $manejador = new Manejador();
                $llamada = new Llamada();
                
                $llamada->setTipUsuario($tipUsu);
                $llamada->setTiempo($tLlamada);
                $llamada->setEstado(1);
                $variable = $manejador->spectrumSensing($llamada);
                echo json_encode($variable);
            }
        ?>