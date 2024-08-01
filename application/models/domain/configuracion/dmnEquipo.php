<?php
require_once APPPATH.'/libraries/Lib_Domain_VO.php';
class dmnEquipo extends Lib_Domain_VO{
    protected $id;
    protected $nombre;
    protected $responsable;
    
    function __construct($id=NULL) {
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

    function setId($id) {
        $this->id = $id;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    function setResponsable($responsable) {
        $this->responsable = $responsable;
    }


}
