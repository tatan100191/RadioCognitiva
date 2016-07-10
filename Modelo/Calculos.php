<?php
/**
 * Description of Calculos
 *
 * @author FABIAN
 */
include_once 'Enlace.php';
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
        $x1 = $u[0].getCoordenadaX();
        $x2 = $v[0].getCoordenadaX();
        $y1 = $u[1].getCoordenadaY();
        $y2 = $v[1].getCoordenadaY();
        $a2 = pow(($x2-$x1),2);
        $b2 = pow(($y2-$y1),2);
        $c = pow(($a2+$b2),0,5);
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
       
    protected function calcularSINRU($u, $v, $arregloP, $n, $beta) {
        $pu= $u->getPotencia();
        $pv= $v->getPotencia();
        $numerador = $pu/pow(($u->getDistanciaAntena()), $n);
        $den2= $pv / pow($this->calDps($v, $u), $n);
        $den1 = 0;
        $limI = 0;
        $limS = count($arregloP);
        $k=0;
        for ($i=$limI; $i<=$limS;$i++){
            $potenciaK = $arregloP[$i]->getPotencia();
            $den1 = $den1 + ($potenciaK/(pow($this->calDps($arregloP[$i], $u),$n)));
        }
        $resultado = $numerador/($den1+$den2);
        if($resultado >= $beta){
            $u->setCanal($v->getCanal());
            $utilitario.insertarEnlaces($u);
            return true;
        }
        return false;
    }
    
    public function cargarDatosEnlaces($numEnlacesSecun, $numEnlacesPrima){
     while ($numEnlacesPrima >= count){
        $enlace = new Enlace();
        $enlace->setTipoEnlace("P");
        $enlace->setDistanciaAntena(rand(1, 100));
        $enlace->setCoordenadaX(rand(1, 200));
        $enlace->setCoordenadaY(rand(1, 200));
        $enlace->setTiempo(rand(1, 20));
        cargarDatosEnlacesPrimarios($enlace);
     }
     while($numEnlacesSecun){
        $enlace = new Enlace();
        $enlace->setTipoEnlace("S");
        $enlace->setDistanciaAntena(rand(1, 100));
        $enlace->setCoordenadaX(rand(1, 200));
        $enlace->setCoordenadaY(rand(1, 200));
        $enlace->setTiempo(rand(1, 20));
        cargarDatosEnlacesSecundarios($enlace);
     }
    }
    
    public function cargarDatosEnlacesSecundarios($enlace){
        $this->conexion->conectar();
        $count = 1;
        $sql = "Select valor from pargenerales where codparametro = \'numcanales\'";
        $resultado = $this->conexion->consultar($sql);
        while ($res = mysqli_fetch_array($resultado)) {
            $canales = $res['valor'];
        }
        $sql = "Select valor from pargenerales where codparametro = \'atenuacion\'";
        $resultado = $this->conexion->consultar($sql);
        while ($res = mysqli_fetch_array($resultado)) {
            $atenuacion = $res['valor'];
        }
        $sql = "Select valor from pargenerales where codparametro = \'beta\'";
        $resultado = $this->conexion->consultar($sql);
            while ($res = mysqli_fetch_array($resultado)) {
              $beta = $res['valor'];
        }
        while ($canales >= $count){
            $sql = "Select * from Enlaces where canal = \'".$count."\' and tipoEnlace = \'P'";
            $resultado = $this->conexion->consultar($sql);
            while ($res = mysqli_fetch_array($resultado)) {
                $enlacePrimario = new Enlace();
                $enlacePrimario->setTipoEnlace($res['tipoEnlace']);
                $enlacePrimario->setDistanciaAntena($res['distanciaAntena']);
                $enlacePrimario->setCoordenadaX($res['cordenadaX']);
                $enlacePrimario->setCoordenadaY($res['cordenadaY']);
                $enlacePrimario->setTiempo($res['tiempo']);
                $enlacePrimario->setPotencia($res['potencia']);
                $enlacePrimario->setCanal($res['canal']);
            }
            $enlacesSecundarios = array();
            $sql = "Select * from Enlaces where canal = \'".$count."\' and tipoEnlace = \'P'";
            $resultado = $this->conexion->consultar($sql);
            while ($res = mysqli_fetch_array($resultado)) {
                $enlaceSecundorio = new Enlace();
                $enlaceSecundorio->setTipoEnlace($res['tipoEnlace']);
                $enlaceSecundorio->setDistanciaAntena($res['distanciaAntena']);
                $enlaceSecundorio->setCoordenadaX($res['cordenadaX']);
                $enlaceSecundorio->setCoordenadaY($res['cordenadaY']);
                $enlaceSecundorio->setTiempo($res['tiempo']);
                $enlaceSecundorio->setPotencia($res['potencia']);
                $enlaceSecundorio->setCanal($res['canal']);
                $enlacesSecundarios[] = $enlaceSecundorio;                
            }
            if (calcularSINRU($enlace, $enlacePrimario,$enlacesSecundarios,$atenuacion, $beta))
                $count = $canales;   
            else {
                $count++;
            }
        }
    
}
      
 
    
}