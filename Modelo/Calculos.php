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
        $this->utilitario= new Utilitario();
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
        $a2 = pow(($x2-$x1),2);
        $b2 = pow(($y2-$y1),2);
        $c = pow(($a2+$b2),0.5);
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
       
    protected function calcularSINRU($u, $v, $arregloP, $n, $beta, $canal) {
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
            for ($i=$limI; $i<$limS;$i++){
                $enlaceAux = $arregloP[$i];
                $potenciaK = $enlaceAux->getPotencia();
                $den1 = $den1 + ($potenciaK/(pow($this->calDps($arregloP[$i], $u),$n)));
            }
            $resultado = $numerador/($den1+$den2);
            echo "Resultado: ".$resultado."<br>";
            if($resultado >= $beta){
                $u->setCanal($canal);
                $this->utilitario->insertarEnlaces($u);
                return true;
            }
        }
        else {
            $u->setCanal($canal);
            $this->utilitario->insertarEnlaces($u);
            return true;
        }
        return false;
    }
    
    public function cargarDatosEnlaces($numEnlacesSecun, $numEnlacesPrima){
     $count = 1;
     $sql="delete from enlace";
     $conexion = new Conexion();
     $bandera = $this->conexion->actualizar($sql);
     if($bandera) echo 'Se eliminaron los enlaces correctamente.. ';
     while ($numEnlacesPrima >= $count){
        $enlace = new Enlace();
        $enlace->setTipoEnlace("P");
        $enlace->setDistanciaAntena(rand(1, 100));
        $enlace->setCoordenadaX(rand(1, 200));
        $enlace->setCoordenadaY(rand(1, 200));
        $enlace->setTiempo(rand(1, 20));
        $this->cargarDatosEnlacesPrimarios($enlace);
        $count++;
     }
     $count = 1;
     while($numEnlacesSecun >= $count){
        $enlace = new Enlace();
        $enlace->setTipoEnlace("S");
        $enlace->setDistanciaAntena(rand(1, 100));
        $enlace->setCoordenadaX(rand(1, 200));
        $enlace->setCoordenadaY(rand(1, 200));
        $enlace->setTiempo(rand(1, 20));
        $this->cargarDatosEnlacesSecundarios($enlace);
        $count++;
     }
    }
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
                if($val == 0){
                    $enlace->setCanal($cuenta);
                    break;
                }
                $cuenta++;
            }
        }else {
            $enlace->setCanal(1);
        }
        $this->utilitario->insertarEnlaces($enlace);
    }
    
    public function cargarDatosEnlacesSecundarios($enlace){
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
        $beta = "";
        $sql = "Select valor from pargenerales where codparametro = 'beta'";
        $resultado = $this->conexion->consultar($sql);
            while ($res = mysqli_fetch_array($resultado)) {
              $beta = $res['valor'];
        }
        while ($canales >= $count){
            $sql = "Select * from Enlace where canal = '".$count."' and tipoEnlace = 'P'";
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
            $sql = "Select * from Enlace where canal = '".$count."' and tipoEnlace = 'S'";
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
            if ($this->calcularSINRU($enlace, $enlacePrimario,$enlacesSecundarios,$atenuacion, $beta, $count) == true)
                $count = $canales+1;   
            else {
                $count++;
            }
        }
    
}
      
 
    
}