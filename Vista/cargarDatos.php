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
$calculos = new Calculos();
$numPrimarios = $_POST['enlacesPrimarios'];
$numSecundarios = $_POST['enlacesSecundarios'];
 echo json_encode($calculos->cargarDatosEnlaces($numSecundarios, $numPrimarios));


?>
