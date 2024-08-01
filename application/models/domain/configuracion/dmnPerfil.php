<?php
require_once APPPATH.'/libraries/Lib_Domain_VO.php';
class dmnPerfil extends Lib_Domain_VO{
    protected $id;
    protected $nombre;
    protected $flgAccesoMovil;
    
    function __construct($id=null) {
        $this->id = $id;
    }
    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }
    
    function getFlgAccesoMovil() {
        return $this->flgAccesoMovil;
    }

    function setFlgAccesoMovil($flgAccesoMovil) {
        $this->flgAccesoMovil = $flgAccesoMovil;
    }


}
