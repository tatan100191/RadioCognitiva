<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Enlace
 *
 * @author FABIAN
 */
class Enlace {
    var $tipoEnlace;
    var $coordenadaX;
    var $coordenadaY;
    var $potencia;
    function __construct() {
    }
    function getTipoEnlace() {
        return $this->tipoEnlace;
    }

    function getCoordenadaX() {
        return $this->coordenadaX;
    }

    function getCoordenadaY() {
        return $this->coordenadaY;
    }

    function getPotencia() {
        return $this->potencia;
    }

    function setTipoEnlace($tipoEnlace) {
        $this->tipoEnlace = $tipoEnlace;
    }

    function setCoordenadaX($coordenadaX) {
        $this->coordenadaX = $coordenadaX;
    }

    function setCoordenadaY($coordenadaY) {
        $this->coordenadaY = $coordenadaY;
    }

    function setPotencia($potencia) {
        $this->potencia = $potencia;
    }
}
