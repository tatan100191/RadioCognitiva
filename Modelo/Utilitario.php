<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Utilitario
 *
 * @author FABIAN
 */
class Utilitario {
    //put your code here
    
    function insertarEnlaces($prEnlace){
        $enlace = new Enlace();
        $enlace = $prEnlace;
        $conexion = new Conexion();
        $canal = $enlace->getCanal();
        $coordenadaX = $enlace->getCoordenadaX();
        $coordenadaY = $enlace->getCoordenadaY();
        $distanciaAntena = $enlace->getDistanciaAntena();
        $potencia = $enlace->getPotencia();
        $tiempo = $enlace->getTiempo();
        $tipoEnlace = $enlace->getTipoEnlace();
        $sql = "insert into enlace (tipoEnlace, cordenadaX, cordenadaY, tiempo, canal, distanciaAntena, potencia) "
                . "values ('".$tipoEnlace."','".$coordenadaX."','".$coordenadaY."','".$tiempo."','".$canal."','".$distanciaAntena."','".$potencia."')";
        $conexion->insertar($sql);
    }
    
    function usuariosXcanal(){
        $conexion = new Conexion();
        $sql = "SELECT canal, count(*) as numeroUsuarios FROM enlace group by canal order by canal";
        $resultado = $conexion->consultar($sql);
        $grafica;
        while ($res = mysqli_fetch_array($resultado)) {
            $grafica[] = ["x" => $res['canal'], "y" => $res['numeroUsuarios']];
        }
        return $grafica;
    }
    
}
