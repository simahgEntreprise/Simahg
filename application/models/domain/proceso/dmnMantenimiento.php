<?php
require_once APPPATH.'/libraries/Lib_Domain_VO.php';
require_once MAPPER_PATH.'proceso/mItem.php';
require_once MAPPER_PATH.'configuracion/mTipoMant.php';
require_once MAPPER_PATH.'configuracion/mEmpresa.php';
class dmnMantenimiento extends Lib_Domain_VO{
    protected $id;
    protected $idItem;
    protected $horastrab;
    protected $idEmpresa;
    protected $idtipo;
    protected $responsable;
    protected $fecIngreso;
    protected $fecSalida;
    protected $observacion;
    protected $estado;
    protected $fecregistro;
    protected $fecmodificacion;
    protected $lugar;
    
    function __construct($id= null) {
        $this->id = $id;
    }
    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getIdItem() {
        if ($this->useMapper == TRUE){            
            $mprDatos= new mItem();
            $this->idItem = $mprDatos->find(array('id' => $this->idItem->getId()));
            $this->useMapper = FALSE;
        }
        return $this->idItem;
    }

    public function setIdItem($idItem) {
        $this->idItem = $idItem;
    }

    public function getHorastrab() {
        return $this->horastrab;
    }

    public function setHorastrab($horastrab) {
        $this->horastrab = $horastrab;
    }

    public function getIdEmpresa() {
        if ($this->useMapper == TRUE){            
            $mprDatos= new mEmpresa();
            $this->idEmpresa = $mprDatos->find(array('id' => $this->idEmpresa->getId()));
            $this->useMapper = FALSE;
        }
        return $this->idEmpresa;
    }

    public function setIdEmpresa($idEmpresa) {
        $this->idEmpresa = $idEmpresa;
    }

    public function getIdtipo() {
        if ($this->useMapper == TRUE){            
            $mprDatos= new mTipoMant();
            $this->idtipo = $mprDatos->find(array('id' => $this->idtipo->getId()));
            $this->useMapper = FALSE;
        }
        return $this->idtipo;
    }

    public function setIdtipo($idtipo) {
        $this->idtipo = $idtipo;
    }

    public function getResponsable() {
        return $this->responsable;
    }

    public function setResponsable($responsable) {
        $this->responsable = $responsable;
    }

    public function getFecIngreso() {
        return $this->fecIngreso;
    }

    public function setFecIngreso($fecIngreso) {
        $this->fecIngreso = $fecIngreso;
    }

    public function getFecSalida() {
        return $this->fecSalida;
    }

    public function setFecSalida($fecSalida) {
        $this->fecSalida = $fecSalida;
    }

    public function getObservacion() {
        return $this->observacion;
    }

    public function setObservacion($observacion) {
        $this->observacion = $observacion;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }

    public function getFecregistro() {
        return $this->fecregistro;
    }

    public function setFecregistro($fecregistro) {
        $this->fecregistro = $fecregistro;
    }

    public function getFecmodificacion() {
        return $this->fecmodificacion;
    }

    public function setFecmodificacion($fecmodificacion) {
        $this->fecmodificacion = $fecmodificacion;
    }
    function getLugar() {
        return $this->lugar;
    }

    function setLugar($lugar) {
        $this->lugar = $lugar;
    }


}