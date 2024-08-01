<?php
require_once APPPATH.'/libraries/Lib_Domain_VO.php';
require_once MAPPER_PATH.'configuracion/mEquipo.php';
class dmnSolicitudEquipo extends Lib_Domain_VO{
    protected $id;
    protected $equipo;
    protected $responsable;
    protected $asignado;
    protected $fecSolicitud;
    protected $fecEntrega;
    protected $fecregistro;
    protected $fecmodificacion;
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

    public function getEquipo() {
        if ($this->useMapper == TRUE){            
            $mprDatos= new mEquipo();
            $this->equipo = $mprDatos->find(array('id' => $this->equipo->getId()));
            $this->useMapper = FALSE;
        }        
        return $this->equipo;
    }

    public function setEquipo($idItem) {
        $this->equipo = $idItem;
    }

    public function getResponsable() {
        return $this->responsable;
    }

    public function setResponsable($responsable) {
        $this->responsable = $responsable;
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
    function getFecSolicitud() {
        return $this->fecSolicitud;
    }

    function getFecEntrega() {
        return $this->fecEntrega;
    }

    function setFecSolicitud($fecSolicitud) {
        $this->fecSolicitud = $fecSolicitud;
    }

    function setFecEntrega($fecEntrega) {
        $this->fecEntrega = $fecEntrega;
    }
    function getTipo() {
        return $this->tipo;
    }

    function setTipo($tipo) {
        $this->tipo = $tipo;
    }
    
    function getAsignado() {
        return $this->asignado;
    }

    function setAsignado($asignado) {
        $this->asignado = $asignado;
    }




}
?>
