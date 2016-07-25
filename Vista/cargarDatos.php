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
    if ($tipoGrafica == "usuariosXCanal"){
        echo json_encode($utilitario->usuariosXcanal());
    }
}


?>
