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
    function cargarDatosEnlacesPrimarios($prEnlace){
        $enlace = new Enlace();
        $enlace = $prEnlace;
        $conexion = new Conexion();
        
        $sql = "SELECT valor FROM pargenerales where codparametro = 'numcanales'";
        $result = $conexion->consultar($sql);
        $numCanales = 0;
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $numCanales = $row['valor'];
        } 
        $canalesConPrimarios = array();
        
        for ($i = 1; $i <= $numCanales; $i++) {
            $canalesConPrimarios[$i]=0;
        }
                
        $sql = "SELECT tipoEnlace, cordenadaX, cordenadaY, tiempo, canal, distanciaAntena, potencia FROM enlace";
        $result = $conexion->consultar($sql);
        if ($result->num_rows > 0) {
            $array = array();
            $primarios = array();
            while ($row = $result->fetch_assoc()) {
                $enlace = new Enlace();
                $enlace->setTipoEnlace($row['tipoEnlace']);
                $enlace->setCoordenadaX($row['cordenadaX']);
                $enlace->setCoordenadaY($row['cordenadaY']);
                $enlace->setTiempo($row['tiempo']);
                $enlace->setCanal($row['canal']);
                $enlace->setDistanciaAntena($row['distanciaAntena']);
                $enlace->setPotencia($row['potencia']);
                $tamaño = count($array);
                $array[$tamaño] = $enlace;
            }
            foreach ($array as $key => $val){
                if ($val->getTipoEnlace()== "P"){
                    $canalesConPrimarios[$val->getCanal()] = 1;        
                }
            }
            $cuenta=1;
            foreach ($canalesConPrimarios as $key => $val){
                if($val = 0){
                    $enlace->setCanal($cuenta);
                    break;
                }
                $cuenta++;
            }
            $this->insertarEnlaces($enlace);
        }else {
            echo 'alert(\"<h1>0 results</h1>\")';
            return "<h1>0 results</h1>";
        }
    }
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
        $respuesta = $conexion->insertar($sql);
        if($respuesta){
            echo 'Se inserto Correctamente';
        }else{
            echo 'No se inserto';
        }
    }
    
}
