<?php

/**
 * Description of Calculos
 *
 * @author FABIAN
 */
include_once 'Enlace.php';
include_once 'Utilitario.php';
include_once '../Controlador/Conexion.php';

class Calculos {

    var $conexion;
    var $utilitario;

    function __construct() {
        $this->conexion = new Conexion();
        $this->utilitario = new Utilitario();
    }

//dps(v,u) es la distancia entre el transmisor
//primario interferente del enlace primario v al
//receptor del enlace secundario u.
    /**
     * 
     * @param type $v arreglo de dos posiciones, coordenadas (x,y) del enlace primario v
     * @param type $u arreglo de dos posiciones, coordenadas (x,y) del enlace secundario u
     * @return type
     */
    protected function calDps($v, $u) {
        $x1 = $u->getCoordenadaX();
        $x2 = $v->getCoordenadaX();
        $y1 = $u->getCoordenadaY();
        $y2 = $v->getCoordenadaY();
        $a2 = pow(($x2 - $x1), 2);
        $b2 = pow(($y2 - $y1), 2);
        $c = pow(($a2 + $b2), 0.5);
        return $c;
    }

    /**
     * 
     * @param type $pu: es la potencia de transmisión del transmisor del enlace secundario u.
     * @param type $pk, es la potencia de transmisión del transmisor interferente del enlace secundario k.
     * @param type $pv, es la potencia de transmisión del transmisor interferente del enlace primario v.
     * @param type $lds, lds(u) representa la distancia que existe entre el transmisor y receptor en el enlace
      secundario u que se desea analizar.
     * @param type $dss, dss(k,u) es la distancia entre el transmisor interferente del enlace secundario k al
      receptor del enlace secundario u.
     * @param type $dps, dps(v,u) es la distancia entre el transmisor
      primario interferente del enlace primario v al
      receptor del enlace secundario u.
     * @param type $arregloP Todas los enlaces transmisores con sus coordenadas, potencias y demas atributos  
     * @param type $phi, Φ es el conjunto de transmisores secundarios
      que utilizan el mismo canal primario.
     * @param type $n, n representa el factor de atenuación que sufre
      la señal en el enlace de comunicación que
      toma cualquier valor entre 2 y 4.
     * $lds, $dss, $dps,
     */

    protected function calcularSINRU($u, $v, $arregloP, $n, $beta, $canal, $numCanales) {
        $insertado = 0;
        if ($v != null){
            $pu= $u->getPotencia();
            $pv= $v->getPotencia();
            $numerador = $pu/pow(($u->getDistanciaAntena()), $n);
            $den2= $pv / pow($this->calDps($v, $u), $n);
            $den1 = 0;
            $limI = 0;
            $potenciaK = 0;
            $limS = count($arregloP);
            $enlaceAux = new Enlace();
            for ($i = $limI; $i < $limS; $i++) {
                $enlaceAux = $arregloP[$i];
                $potenciaK = $enlaceAux->getPotencia();
                $den1 = $den1 + ($potenciaK / (pow($this->calDps($arregloP[$i], $u), $n)));
            }
            $resultado = $numerador / ($den1 + $den2);
            if ($resultado >= $beta) {
                $u->setCanal($canal);
                $u->setSinr($resultado);
                $this->utilitario->insertarEnlaces($u);
                $insertado = 1;
                return true;
            }
        } else {
            $u->setCanal($canal);
            $this->utilitario->insertarEnlaces($u);
            return true;
        }
        if($insertado != 1 && $numCanales == $canal )
        {
            $u->setCanal(0);
            $this->utilitario->insertarEnlaces($u);
            return false;
        }
    }

    public function cargarDatosEnlaces($numEnlacesSecun, $numEnlacesPrima) {
        
        $sql = "delete from enlace";
        $conexion = new Conexion();
        $bandera = $this->conexion->actualizar($sql);
        for($beta = 4; $beta <= 20; $beta=$beta+3){
            $iteraciones = 1;
            while ($iteraciones <= 50){
            if ($bandera)
            $count = 1;
            while ($numEnlacesPrima >= $count) {
                $enlace = new Enlace();
                $enlace->setTipoEnlace("P");
                $enlace->setDistanciaAntena(rand(1, 50));
                $enlace->setCoordenadaX(rand(1, 100));
                $enlace->setCoordenadaY(rand(1, 100));
                $enlace->setTiempo(rand(1, 20));
                $enlace->setBeta($beta);
                $enlace->setIteracion($iteraciones);
                $this->cargarDatosEnlacesPrimarios($enlace);
                $count++;
            }
            $count = 1;
            while ($numEnlacesSecun >= $count) {
                $enlace = new Enlace();
                $enlace->setTipoEnlace("S");
                $enlace->setDistanciaAntena(rand(1, 50));
                $enlace->setCoordenadaX(rand(1, 100));
                $enlace->setCoordenadaY(rand(1, 100));
                $enlace->setTiempo(rand(1, 20));
                $enlace->setBeta($beta);
                $enlace->setIteracion($iteraciones);
                $this->cargarDatosEnlacesSecundarios($enlace);
                $count++;
            }
            $iteraciones++;
            }
        }
        $this->insertarAnalisisDatos();
        $this->insertarAnalisisDos();
        return "fin"; //$this->consultarEnlaces()
    }
    
    public function insertarAnalisisDos(){
        $sql = "delete from analisis_datos2";
        $bandera = $this->conexion->actualizar($sql);
        if($bandera){
            $sql= "insert into analisis_datos2 SELECT * FROM `analisisDos`";
            $this->conexion->insertar($sql);
        }
    }    
    

    function consultarEnlaces() {
        $sql = "Select * from enlace";
        $resultado = $this->conexion->consultar($sql);
        $llamadas = array() ;
        while ($res = mysqli_fetch_array($resultado)) {
        $llamadas[] = [ "tipoEnlace" => $res['tipoEnlace'],'cordenadaX' => $res['cordenadaX'], 
            'cordenadaY'=> $res['cordenadaY'], 'tiempo' => $res['tiempo'], 'canal' => $res['canal']
            ,'distanciaAntena' => $res['distanciaAntena'] ,'potencia' => $res['potencia'],'sinr' => $res['sinr']
                ,'id' => $res['id']];
        }
        return $llamadas;
    }
    
    function cargarDatosEnlacesPrimarios($prEnlace){
        $enlace1 = new Enlace();
        $enlace1 = $prEnlace;
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
            $canalesConPrimarios[$i] = 0;
        }


        $sql = "SELECT tipoEnlace, cordenadaX, cordenadaY, tiempo, canal, distanciaAntena, potencia "
                . "FROM enlace where beta =". $enlace1->getBeta() . " and iteracion = ". $enlace1->getIteracion();
        $result = $conexion->consultar($sql);
        if ($result->num_rows > 0) {
            $array = array();
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
            foreach ($array as $key => $val) {
                if ($val->getTipoEnlace() == "P") {
                    $canalesConPrimarios[$val->getCanal()] = 1;
                }
            }
            $cuenta = 1;



            foreach ($canalesConPrimarios as $key => $val){
                if($val == 0){
                    $enlace1->setCanal($cuenta);
                    break;
                }
                $cuenta++;
            }

        }else {
            $enlace1->setCanal(1);
        }
        $this->utilitario->insertarEnlaces($enlace1);
    }

    public function cargarDatosEnlacesSecundarios($enlace) {
        $count = 1;
        $canales = "";
        $sql = "Select valor from pargenerales where codparametro = 'numcanales'";
        $resultado = $this->conexion->consultar($sql);
        while ($res = mysqli_fetch_array($resultado)) {
            $canales = $res['valor'];
        }
        $atenuacion = "";
        $sql = "Select valor from pargenerales where codparametro = 'atenuacion'";
        $resultado = $this->conexion->consultar($sql);
        while ($res = mysqli_fetch_array($resultado)) {
            $atenuacion = $res['valor'];
        } 
        while ($canales >= $count) {
            $sql = "Select * from Enlace where canal = '" . $count . "' and tipoEnlace = 'P' and beta = ".
                            $enlace->getBeta(). " and iteracion = " . $enlace->getIteracion();
            $resultado = $this->conexion->consultar($sql);
            $enlacePrimario = new Enlace();
            while ($res = mysqli_fetch_array($resultado)) {
                $enlacePrimario->setTipoEnlace($res['tipoEnlace']);
                $enlacePrimario->setDistanciaAntena($res['distanciaAntena']);
                $enlacePrimario->setCoordenadaX($res['cordenadaX']);
                $enlacePrimario->setCoordenadaY($res['cordenadaY']);
                $enlacePrimario->setTiempo($res['tiempo']);
                $enlacePrimario->setPotencia($res['potencia']);
                $enlacePrimario->setCanal($res['canal']);
            }
            $enlacesSecundarios = array();
            $sql = "Select * from Enlace where canal = '" . $count . "' and tipoEnlace = 'S' and beta = ".
                            $enlace->getBeta(). " and iteracion = " . $enlace->getIteracion();
            $resultado = $this->conexion->consultar($sql);
            while ($res = mysqli_fetch_array($resultado)) {
                $enlaceSecundario = new Enlace();
                $enlaceSecundario->setTipoEnlace($res['tipoEnlace']);
                $enlaceSecundario->setDistanciaAntena($res['distanciaAntena']);
                $enlaceSecundario->setCoordenadaX($res['cordenadaX']);
                $enlaceSecundario->setCoordenadaY($res['cordenadaY']);
                $enlaceSecundario->setTiempo($res['tiempo']);
                $enlaceSecundario->setPotencia($res['potencia']);
                $enlaceSecundario->setCanal($res['canal']);
                $enlacesSecundarios[] = $enlaceSecundario;
            }


            if ($this->calcularSINRU($enlace, $enlacePrimario,$enlacesSecundarios,$atenuacion, $enlace->getBeta(), $count, $canales) == true)
                $count = $canales+1;   
            else {
                $count++;
            }
        }
    }

    public function usuariosSecundariosXCanal() {
        $sql = "SELECT `canal`, beta, iteracion, COUNT(*) as cuenta FROM `enlace` WHERE `tipoEnlace` = 'S' and canal <> 0 GROUP BY `canal`, beta, iteracion";
        $resultado = $this->conexion->consultar($sql);
        while ($res = mysqli_fetch_array($resultado)) {
            $cuentaCanales[$res['beta'] ."|". $res['iteracion']."|".$res['canal']] = $res['cuenta'];
        }
        return $cuentaCanales;
    }

    public function tamanoLlamada() {
        $sql = "SELECT `valor` FROM `pargenerales` WHERE `codparametro`='tamanollam'";
        $resultado = $this->conexion->consultar($sql);
        while ($res = mysqli_fetch_array($resultado)) {
            $tamanoLlamada = $res['valor'];
        }
        return $tamanoLlamada;
    }

    public function getAnchoBanda() {
        $sql = "SELECT `valor` FROM `pargenerales` WHERE `codparametro`='anchobanda'";
        $resultado = $this->conexion->consultar($sql);
        while ($res = mysqli_fetch_array($resultado)) {
            $anchoBanda = $res['valor'];
        }
        return $anchoBanda;
    }

    public function eficienciaEspectral() {
        $anchoBanda = $this->getAnchoBanda();
        $tamanoLlamada = $this->tamanoLlamada();
        $uSecundarios = $this->usuariosSecundariosXCanal();
        foreach ($uSecundarios as $key => $val) {
            $eficiencia[$key] = ($tamanoLlamada * $val) / $anchoBanda;
        }
        return $eficiencia;
    }
    
    public function insertarAnalisisDatos(){
        $sql = "delete from analisis_datos";
        $bandera = $this->conexion->actualizar($sql);
            if ($bandera){
            $eficienia = $this->eficienciaEspectral();
            $usuSecundarios = $this->usuariosSecundariosXCanal();
            foreach ($eficienia as $key => $val) {
                $aux = explode("|",$key);
                $sql = "insert into analisis_datos values (".$aux[0].",".$aux[1].",".$aux[2].
                        ",".$eficienia[$key].",".$usuSecundarios[$key].")";
                $this->conexion->insertar($sql);
            }
        }
    }
    
}
