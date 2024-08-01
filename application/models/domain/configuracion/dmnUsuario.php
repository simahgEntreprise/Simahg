<?php
require_once APPPATH.'/libraries/Lib_Domain_VO.php';
require_once DOMAIN_PATH.'configuracion/dmnPerfil.php';
require_once DOMAIN_PATH.'configuracion/dmnCargo.php';
require_once MAPPER_PATH.'configuracion/mPerfil.php';
require_once MAPPER_PATH.'configuracion/mCargo.php';
class dmnUsuario extends Lib_Domain_VO{
    protected $id;
    protected $usuario;
    protected $contrasena;
    protected $idperfil;
    protected $nombre;
    protected $apellidos;
    protected $idCargo;
    protected $fecIngreso;
    protected $fecModificacion;
    protected $estado;

public function  __construct($id = null) {
        $this->id = $id;
}

public function getId() {
    return $this->id;
}

public function setId($id) {
    $this->id = $id;
}

public function getUsuario() {
    return $this->usuario;
}

public function setUsuario($usuario) {
    $this->usuario = $usuario;
}

public function getContrasena() {
    return $this->contrasena;
}

public function setContrasena($contrasena) {
    $this->contrasena = $contrasena;
}

public function getIdperfil() {
    if ($this->useMapper == TRUE){
        $mprPerfil = new mPerfil();        
            $this->idperfil = $mprPerfil->find(array('id' => $this->ìdperfil->getId()));
            $this->useMapper = FALSE;
    }        
    return $this->idperfil;
}

public function setIdperfil($idperfil) {
    $this->idperfil = $idperfil;
}

public function getNombre() {
    return $this->nombre;
}

public function setNombre($nombre) {
    $this->nombre = $nombre;
}

public function getApellido() {
    return $this->apellidos;
}

public function setApellido($apellidos) {
    $this->apellidos = $apellidos;
}

public function getIdCargo() {
    if ($this->useMapper == TRUE){
        $mprCargp = new mCargo();
            $this->idcargo = $mprCargp->find(array('id' => $this->idcargo->getId()));
            $this->useMapper = FALSE;
    }
    return $this->idCargo;
}

public function setIdCargo($idCargo) {
    $this->idCargo = $idCargo;
}

public function getFecIngreso() {
    return $this->fecIngreso;
}

public function setFecIngreso($fecIngreso) {
    $this->fecIngreso = $fecIngreso;
}

public function getFecModificacion() {
    return $this->fecModificacion;
}

public function setFecModificacion($fecModificacion) {
    $this->fecModificacion = $fecModificacion;
}

function getEstado() {
    return $this->estado;
}

function setEstado($estado) {
    $this->estado = $estado;
}


}

