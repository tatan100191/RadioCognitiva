<?php
/**
 * Description of Calculos
 *
 * @author FABIAN
 */
include_once 'Enlace.php';
class Calculos {
   
// lds(u) representa la distancia que existe entre
// el transmisor y receptor en el enlace
// secundario u que se desea analizar.
    
// Cada enlace debe tener como atributo la distancia con su antena(celda)
    public function calLds($u) {
          $quer = "select distanciaAntena from Enlaces where enlace = \'s\'";
          return $quer;
    }
// ldp(v) representa la distancia que existe entre
// el transmisor y receptor en el enlace
// primario v que se desea analizar.
// 
// Cada enlace debe tener como atributo la distancia con su antena(celda)
    public function calLdp($v) {
//        select distanciaCelda from tablaEnlaces where enlace = '$v';
        return 4;
    }
//dss(k,u) es la distancia entre el transmisor
//interferente del enlace secundario k al
//receptor del enlace secundario u.
/**
 * 
 * @param type $k arreglo de dos posiciones, coordenadas (x,y) del enlace secundario k
 * @param type $u arreglo de dos posiciones, coordenadas (x,y) del enlace secundario u
 * @return type $c, distancia entre los dos enlaces secundarios
 */
    
    protected function calDss($k,$u) {
        $x1 = $k[0];$x2 = $u[0];
        $y1 = $k[1];$y2 = $u[1];
        $a2 = pow(($x2-$x1),2);
        $b2 = pow(($y2-$y1),2);
        $c = pow(($a2+$b2),0,5);
        return $c;
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
        $x1 = $k[0];$x2 = $v[0];
        $y1 = $k[1];$y2 = $v[1];
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
       
    protected function calcularSINRU($u, $v, $arregloK, $arregloP, $phi, $n) {
        $pu= $u->getPotencia();
        $pv= $v->getPotencia();
        $numerador = $pu/pow($this->calLds($u), $n);
        $den2= $pv / pow($this->calDps($v, $u), $n);
        $den1 = 0;
        //se refiere al índice de sólo aquellos transmisores secundarios que tienen asignado un mismo canal.
        $arregloK = array();
        $limI = 0;
        $limS = count($arregloK);
        $k=0;
        for ($i=$limI; $i<=$limS;$i++){
            $k=$arregloK[$i];
            $potenciaK = $arregloP[$k]->getPotencia();
            $den1 = $den1 + ($potenciaK/(pow($this->calDss($arregloP[$k], $u),$n)));
        }
        $resultado = $numerador/($den1+$den2);
        return $resultado;
    }
    protected function calcularSINRV($u, $v, $arregloK, $arregloP, $phi, $n) {
        $pu= $u->getPotencia();
        $pv= $v->getPotencia();
        $pk;
        $numerador = $pv/pow($this->calLdp($v), $n);
        $den1 = 0;
        //se refiere al índice de sólo aquellos transmisores secundarios que tienen asignado un mismo canal.
        $arregloK = array();
        $limI = 0;
        $limS = count($arregloK);
        for ($i=$limI; $i<=$limS;$i++){
            $k=$arregloK[$i];
            $potenciaK = $arregloP[$k]->getPotencia();
            $den1 = $den1 + ($potenciaK/(pow($this->calDps($arregloP[$k], $v),$n)));
        }
        $resultado = $numerador/($den1);
        return $resultado;
    }
    protected function calcularCu($B, $sinru) {
        $arg = 1 + $sinru;
        return B * log($arg, 2);
    }
    protected function calcularCv($B, $sinrv) {
        $arg = 1 + $sinrv;
        return B * log($arg, 2);
    }
    /**
     * 
     * @param type $x
     * @param type $si
     * @param type $pi
     * @return type
     * 
     */
    protected function fo($x, $si, $pi) {
        $arreglox = array();
        $arreglox = $x;
        $parte1=0;
        $parte21=0;
        $parte22=0;
        for ($i=1; $i<=$si;$i++){
            $parte1 = $parte1 + $arreglox[$i];
        }
        for ($i=1; $i<=$si;$i++){
            $sinru = $this->calcularSINRU($u, $v, $arregloK, $arregloP, $phi, $n);
            $parte21 = $parte21 + ($this->calcularCu($B, $sinru) * $arreglox[$i]);
        }
        for ($i=1; $i<=$pi;$i++){
            $parte22 = $parte22 + ($this->calcularCv($B, $sinrv));
        }
        $result = $parte1 + ($parte21+$parte22);
        return $result;
    }
    protected function main() {
        $arreglo = array();
        $si = 100;
        for ($i=1; $i<=$si;$i++){
            $enlace = new Enlace();
            $enlace->setCoordenadaX(rand(5, 15));
            $enlace->setCoordenadaY(rand(5, 15));
            $enlace->setPotencia(rand(10, 90));
            if(rand(0, 1) == 1){
                $enlace->setTipoEnlace("U");
            }else{
                $enlace->setTipoEnlace("V");
            }
            $arreglox[$i] = $enlace;
        }
        
    }
}