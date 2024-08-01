<?php
require_once APPPATH.'/libraries/Lib_Domain_VO.php';
class dmnTiempoMantenimiento extends Lib_Domain_VO{
    protected $id;
    protected $nombre;
    protected $tipo;
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

    public function getTipo() {
        return $this->tipo;
    }

    public function setTipo($tipo) {
        $this->tipo = $tipo;
    }    
}
?>
