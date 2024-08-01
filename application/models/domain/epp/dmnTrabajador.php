<?php
require_once APPPATH.'/libraries/Lib_Domain_VO.php';
require_once MAPPER_PATH.'configuracion/mCargo.php';
require_once MAPPER_PATH.'configuracion/mArea.php';
class dmnTrabajador extends Lib_Domain_VO{
    protected $id;
    protected $nombre;
    protected $apellidos;
    protected $dni;
    protected $idcargo;
    protected $idarea;
    protected $idproy;
    protected $checked;
            
    function __construct($id = NULL) {
        $this->id = $id;
    }

    
    function getId() {
        return $this->id;
    }

    function getNombre() {
        return $this->nombre;
    }

    function getApellido() {
        return $this->apellidos;
    }

    function getDni() {
        return $this->dni;
    }

    function getIdarea() {
        if ($this->useMapper == TRUE){            
            $mpr = new mArea();
            $this->idarea= $mpr->find(array('id' => $this->idarea->getId()));
            $this->useMapper = FALSE;
        }        
        return $this->idarea;
    }

    function getIdproy() {
        return $this->idproy;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    function setApellido($apellidos) {
        $this->apellidos = $apellidos;
    }

    function setDni($dni) {
        $this->dni = $dni;
    }

    function setIdarea($idarea) {
        $this->idarea = $idarea;
    }

    function setIdproy($idproy) {
        $this->idproy = $idproy;
    }

    function getIdcargo() {
        if ($this->useMapper == TRUE){            
            $mprCargo = new mCargo();
            $this->idcargo= $mprCargo->find(array('id' => $this->idcargo->getId()));
            $this->useMapper = FALSE;
        }
        return $this->idcargo;
    }

    function setIdcargo($idcargo) {
        $this->idcargo = $idcargo;
    }    
    
    function getChecked() {
        return $this->checked;
    }

    function setChecked($checked) {
        $this->checked = $checked;
    }


}

