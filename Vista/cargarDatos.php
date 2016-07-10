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
require_once ('../Modelo/Facade.php');
$facade = new Facade();
if($_POST['operacion']=="banda"){
    $banda = $facade->getBanda($_POST['banda']);
    echo json_encode($banda, JSON_FORCE_OBJECT);
}   
if ($_POST['operacion']=="llamadas"){
    $llamadas = $facade->getLlamadas($_POST['banda']);
    echo json_encode($llamadas);
}
?>
