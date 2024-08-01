<?php
require_once APPPATH.'/libraries/Lib_Domain_VO.php';
class dmnEmpresa extends Lib_Domain_VO{
    protected $id;
    protected $nombre;
    protected $ruc;
    protected $representante;
    
    function __construct($id = null) {
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
    
    function getRuc() {
        return $this->ruc;
    }

    function getRepresentante() {
        return $this->representante;
    }

    function setRuc($ruc) {
        $this->ruc = $ruc;
    }

    function setRepresentante($representante) {
        $this->representante = $representante;
    }


}
?>
