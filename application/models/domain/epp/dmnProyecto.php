<?php
require_once APPPATH.'/libraries/Lib_Domain_VO.php';
require_once MAPPER_PATH.'epp/mTrabajador.php';
class dmnProyecto extends Lib_Domain_VO{
    protected $id;
    protected $nombre;
    protected $responsable;
    protected $lider;
    protected $estado;
    
    function __construct($id=null) {
        $this->id = $id;
    }

    function getId() {
        return $this->id;
    }

    function getNombre() {
        return $this->nombre;
    }

    function getResponsable() {
        return $this->responsable;
    }

    function getLider() {
        if ($this->useMapper == TRUE){            
            $mpr = new mTrabajador();
            $this->lider= $mpr->find(array('id' => $this->lider->getId()));
            $this->useMapper = FALSE;
        }
        return $this->lider;
    }

    function getEstado() {
        return $this->estado;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    function setResponsable($responsable) {
        $this->responsable = $responsable;
    }

    function setLider($lider) {
        $this->lider = $lider;
    }

    function setEstado($estado) {
        $this->estado = $estado;
    }

    
}