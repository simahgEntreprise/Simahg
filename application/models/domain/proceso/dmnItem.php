<?php
require_once APPPATH.'/libraries/Lib_Domain_VO.php';
require_once DOMAIN_PATH.'configuracion/dmnArea.php';
require_once DOMAIN_PATH.'configuracion/dmnGrupo.php';
require_once DOMAIN_PATH.'configuracion/dmnTiempoMantenimiento.php';
require_once MAPPER_PATH.'configuracion/mTiempoMant.php';
require_once MAPPER_PATH.'configuracion/mArea.php';
require_once MAPPER_PATH.'configuracion/mGrupo.php';
class dmnItem extends Lib_Domain_VO{
    protected $id;
    protected $codigo;
    protected $nombre;
    protected $numserie;
    protected $imagen;
    protected $idarea;
    protected $responsable;
    protected $fecIngreso;
    protected $fecUsoItem;
    protected $idtiempoMant;
    protected $fecregistro;
    protected $fecmodificacion;
    protected $estado;
    protected $cantidad;
    protected $numserie2;
    protected $modelo1;
    protected $modelo2;
    protected $motor;
    protected $tecnico;
    protected $areaTec;
    protected $grupo;
    protected $lugar;
    protected $nroFactura;
            
    function __construct($id=NULL) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getCodigo() {
        return $this->codigo;
    }

    public function setCodigo($codigo) {
        $this->codigo = $codigo;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function getNumserie() {
        return $this->numserie;
    }

    public function setNumserie($numserie) {
        $this->numserie = $numserie;
    }

    public function getImagen() {
        return $this->imagen;
    }

    public function setImagen($imagen) {
        $this->imagen = $imagen;
    }

    public function getIdarea() {
        if ($this->useMapper == TRUE){            
            $mprArea = new mArea();
            $this->idarea = $mprArea->find(array('id' => $this->idarea->getId()));
            $this->useMapper = FALSE;
        }
        return $this->idarea;
    }

    public function setIdarea($idarea) {
        $this->idarea = $idarea;
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

    public function getFecUsoItem() {
        return $this->fecUsoItem;
    }

    public function setFecUsoItem($fecUsoItem) {
        $this->fecUsoItem = $fecUsoItem;
    }

    public function getIdtiempoMant() {
        if ($this->useMapper == TRUE){            
            $mprTiempoMant = new mTiempoMant();
            $this->idtiempoMant = $mprTiempoMant->find(array('id' => $this->idtiempoMant->getId()));
            $this->useMapper = FALSE;
        }
        return $this->idtiempoMant;
    }

    public function setIdtiempoMant($idtiempoMant) {
        $this->idtiempoMant = $idtiempoMant;
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
    function getEstado() {
        return $this->estado;
    }

    function setEstado($estado) {
        $this->estado = $estado;
    }
    
    function getCantidad() {
        return $this->cantidad;
    }

    function setCantidad($cantidad) {
        $this->cantidad = $cantidad;
    }
    function getNumserie2() {
        return $this->numserie2;
    }

    function getModelo1() {
        return $this->modelo1;
    }

    function getModelo2() {
        return $this->modelo2;
    }

    function getMotor() {
        return $this->motor;
    }

    function getTecnico() {
        return $this->tecnico;
    }

    function getAreaTec() {
        if ($this->useMapper == TRUE){            
            $mprArea = new mArea();
            $this->areaTec = $mprArea->find(array('id' => $this->areaTec->getId()));
            $this->useMapper = FALSE;
        }
        return $this->areaTec;
    }

    function getGrupo() {
        if ($this->useMapper == TRUE){            
            $mprArea = new mGrupo();
            $this->grupo = $mprArea->find(array('id' => $this->grupo->getId()));
            $this->useMapper = FALSE;
        }
        return $this->grupo;
    }

    function setNumserie2($numserie2) {
        $this->numserie2 = $numserie2;
    }

    function setModelo1($modelo1) {
        $this->modelo1 = $modelo1;
    }

    function setModelo2($modelo2) {
        $this->modelo2 = $modelo2;
    }

    function setMotor($motor) {
        $this->motor = $motor;
    }

    function setTecnico($tecnico) {
        $this->tecnico = $tecnico;
    }

    function setAreaTec($areaTec) {
        if ($this->useMapper == TRUE){            
            $mprArea = new mArea();
            $this->areaTec  = $mprArea->find(array('id' => $this->areaTec ->getId()));
            $this->useMapper = FALSE;
        }        
        return $this->areaTec = $areaTec;
    }

    function setGrupo($grupo) {
        if ($this->useMapper == TRUE){            
            $mprGrupo = new mGrupo();
            $this->grupo  = $mprGrupo->find(array('id' => $this->grupo->getId()));
            $this->useMapper = FALSE;
        }        
        return $this->grupo = $grupo;
        
    }
    function getLugar() {
        return $this->lugar;
    }

    function setLugar($lugar) {
        $this->lugar = $lugar;
    }

    function getNroFactura() {
        return $this->nroFactura;
    }

    function setNroFactura($nroFactura) {
        $this->nroFactura = $nroFactura;
    }




}
