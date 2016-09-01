<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of newPHPClass
 *
 * @author Tatan
 */
require_once ('../Modelo/Calculos.php');
require_once ('../Modelo/Utilitario.php');
$calculos = new Calculos();
$utilitario = new Utilitario();
if($_POST['accion']== 1){
    $numPrimarios = $_POST['enlacesPrimarios'];
    $numSecundarios = $_POST['enlacesSecundarios'];
    echo json_encode($calculos->cargarDatosEnlaces($numSecundarios, $numPrimarios));
}
else if($_POST['accion']== 2){
    $tipoGrafica = $_POST['tipoGrafica'];
    if ($tipoGrafica == "eficienciaEspectral"){
        echo json_encode($utilitario->analisisEficiencia());
    }
    if ($tipoGrafica == "usuarioSecundarios"){
        echo json_encode($utilitario->analisisUsuarios());
    }
    if ($tipoGrafica == "analisisLambda02"){
        echo json_encode($utilitario->graficaLambdaCeroDos());
    }
    if ($tipoGrafica == "analisisLambda05"){
        echo json_encode($utilitario->graficaLambdaCeroCinco());
    }
    if ($tipoGrafica == "analisisLambda08"){
        echo json_encode($utilitario->graficaLambdaCeroOcho());
    }
    
}


?>
