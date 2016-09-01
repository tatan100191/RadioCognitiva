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
        $sinr = $enlace->getSinr();
        $beta = $enlace->getBeta();
        $iteracion = $enlace->getIteracion();
        if($sinr != null){
            $sql = "insert into enlace (tipoEnlace, cordenadaX, cordenadaY, tiempo, canal, distanciaAntena, potencia, sinr,".
                "beta, iteracion)"
                . "values ('".$tipoEnlace."','".$coordenadaX."','".$coordenadaY."','".$tiempo.
                "','".$canal."','".$distanciaAntena."','".$potencia."',".$sinr. ", ". $beta.", ".$iteracion.
                    ")";
        }
        else{
        $sql = "insert into enlace (tipoEnlace, cordenadaX, cordenadaY, tiempo, canal, distanciaAntena, potencia,".
                "beta, iteracion)"
                . "values ('".$tipoEnlace."','".$coordenadaX."','".$coordenadaY."','".$tiempo.
                "','".$canal."','".$distanciaAntena."','".$potencia."', ". $beta.", ".$iteracion.
                    ")";
        }
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
    
    public function analisisEficiencia(){
        $conexion = new Conexion();
        $sql = "SELECT beta, max(suma) as maximo FROM `sumeficienciaiteracion` group by beta";
        $resultado = $conexion->consultar($sql);
        $grafica;
        while ($res = mysqli_fetch_array($resultado)) {
            $grafica[] = ["x" => $res['beta'], "y" => $res['maximo']];
        }   
        $grafica['labelY'] = 'Eficiencia Espectral';
        $grafica['labelX'] = 'Umbral';
        $grafica['titulo'] = 'Grafica Eficiencia Espectral';
        $grafica['pasos'] = 7;
        $grafica['escala'] = 1;
        $grafica['inicia'] = 12;
        return $grafica;
    }
    
    public function analisisUsuarios(){
        $conexion = new Conexion();
        $sql = "SELECT beta, max(suma) as maximo FROM `sumUsuariosIteracion` group by beta";
        $resultado = $conexion->consultar($sql);
        $grafica;
        while ($res = mysqli_fetch_array($resultado)) {
            $grafica[] = ["x" => $res['beta'], "y" => $res['maximo']];
        }   
        $grafica['labelY'] = 'Usuarios Secundarios';
        $grafica['labelX'] = 'Umbral';
        $grafica['titulo'] = 'Grafica Usuarios Secundarios';
        $grafica['pasos'] = 7;
        $grafica['escala'] = 1;
        $grafica['inicia'] = 12;
        return $grafica;
    }
    
     public function graficaLambdaCeroDos(){
        $conexion = new Conexion();
        $sql = "SELECT  `lambda` ,  `eficienciaXLambda` ,  `numUsuariosXunoMLambda`".
        "FROM  `analisis_datos2` where lambda = '0.2'".
        "GROUP BY  `lambda` ,  `eficienciaXLambda` ,  `numUsuariosXunoMLambda`";
        $resultado = $conexion->consultar($sql);
        $grafica;
        while ($res = mysqli_fetch_array($resultado)) {
            $grafica[] = ["x" => $res['numUsuariosXunoMLambda'], "y" => $res['eficienciaXLambda']];
        }
        $grafica['labelY'] = 'Eficiencia Espectral';
        $grafica['labelX'] = 'Numero Usuarios';
        $grafica['titulo'] = 'Grafica Lambda 0,2';
        $grafica['pasos'] = 7;
        $grafica['escala'] = 2;
        $grafica['inicia'] = 0;
        return $grafica;
    }
    
    public function graficaLambdaCeroCinco(){
        $conexion = new Conexion();
        $sql = "SELECT  `lambda` ,  `eficienciaXLambda` ,  `numUsuariosXunoMLambda`".
        "FROM  `analisis_datos2` where lambda = '0.5'".
        "GROUP BY  `lambda` ,  `eficienciaXLambda` ,  `numUsuariosXunoMLambda`";
        $resultado = $conexion->consultar($sql);
        $grafica;
        while ($res = mysqli_fetch_array($resultado)) {
            $grafica[] = ["x" => $res['numUsuariosXunoMLambda'], "y" => $res['eficienciaXLambda']];
        }
        $grafica['labelY'] = 'Eficiencia Espectral';
        $grafica['labelX'] = 'Numero Usuarios';
        $grafica['titulo'] = 'Grafica Lambda 0,5';
        $grafica['pasos'] = 7;
        $grafica['escala'] = 2;
        $grafica['inicia'] = 0;
        return $grafica;
    }
    public function graficaLambdaCeroOcho(){
        $conexion = new Conexion();
        $sql = "SELECT  `lambda` ,  `eficienciaXLambda` ,  `numUsuariosXunoMLambda`".
        "FROM  `analisis_datos2` where lambda = '0.8'".
        "GROUP BY  `lambda` ,  `eficienciaXLambda` ,  `numUsuariosXunoMLambda`";
        $resultado = $conexion->consultar($sql);
        $grafica;
        while ($res = mysqli_fetch_array($resultado)) {
            $grafica[] = ["x" => $res['numUsuariosXunoMLambda'], "y" => $res['eficienciaXLambda']];
        }   
        $grafica['labelY'] = 'Eficiencia Espectral';
        $grafica['labelX'] = 'Numero Usuarios';
        $grafica['titulo'] = 'Grafica Lambda 0,8';
        $grafica['pasos'] = 7;
        $grafica['escala'] = 2;
        $grafica['inicia'] = 0;
        return $grafica;
    }
}
