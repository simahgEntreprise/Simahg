<?php
require_once APPPATH.'/libraries/Lib_Domain_VO.php';
class dmnRiesgo extends Lib_Domain_VO{
    protected $id;
    protected $factor;
    protected $descripcion;
    
    function __construct($id= null) {
        $this->id = $id;
    }

    
    function getId() {
        return $this->id;
    }

    function getFactor() {
        return $this->factor;
    }

    function getDescripcion() {
        return $this->descripcion;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setFactor($factor) {
        $this->factor = $factor;
    }

    function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }


}
