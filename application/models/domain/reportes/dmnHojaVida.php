<?php
require_once APPPATH.'/libraries/Lib_Domain_VO.php';
require_once MAPPER_PATH.'configuracion/mTipoMant.php';
class dmnHojaVida extends Lib_Domain_VO{
    protected $id;  
    protected $fecha;
    protected $equipo;
    protected $tipo;
    protected $lugar;
    protected $observacion;
    protected $responsable;
    
    function __construct($id = NULL) {
        $this->id = $id;
    }

    function getId() {
        return $this->id;
    }

    function setId($id) {
        $this->id = $id;
    }

        function getFecha() {
        return $this->fecha;
    }

    function getEquipo() {
        return $this->equipo;
    }

    function getObservacion() {
        return $this->observacion;
    }

    function getResponsable() {
        return $this->responsable;
    }

    function setFecha($fecha) {
        $this->fecha = $fecha;
    }

    function setEquipo($equipo) {
        $this->equipo = $equipo;
    }

    function setObservacion($observacion) {
        $this->observacion = $observacion;
    }

    function setResponsable($responsable) {
        $this->responsable = $responsable;
    }
    function getTipo() {
        if ($this->useMapper == TRUE){            
            $mprDatos= new mTipoMant();
            $this->tipo = $mprDatos->find(array('id' => $this->tipo->getId()));
            $this->useMapper = FALSE;
        }
        return $this->tipo;
    }

    function getLugar() {
        return $this->lugar;
    }

    function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    function setLugar($lugar) {
        $this->lugar = $lugar;
    }



}