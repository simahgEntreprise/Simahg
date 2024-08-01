<?php
require_once APPPATH.'/libraries/Lib_Domain_VO.php';
class dmnAcceso extends Lib_Domain_VO{
    protected $id;
    protected $nombre;
    protected $idApp;
    protected $idopc;
    protected $ruta;
            
    function __construct($id) {
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

    public function getIdApp() {
        return $this->idApp;
    }

    public function setIdApp($idApp) {
        $this->idApp = $idApp;
    }

    public function getIdopc() {
        return $this->idopc;
    }

    public function setIdopc($idopc) {
        $this->idopc = $idopc;
    }
    
    public function getRuta() {
        return $this->ruta;
    }

    public function setRuta($ruta) {
        $this->ruta = $ruta;
    }
}
?>
