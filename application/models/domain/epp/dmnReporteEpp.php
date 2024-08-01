<?php
require_once APPPATH.'/libraries/Lib_Domain_VO.php';

class dmnReporteEpp extends Lib_Base_VO{
    protected $nombre;
    protected $apellidos;
    protected $factor;
    protected $descripcion;
    protected $tipo;
    protected $fecingreso;
    protected $fecfin;   
    protected $valor;
    protected $proyecto;
    protected $lider;
    protected $idlider;
    protected $idProyecto;
    protected $responsable;
    protected $cargoTrab;
    protected $dniTrab;
    
    function getNombre() {
        return $this->nombre;
    }

    function getApellido() {
        return $this->apellidos;
    }

    function getFactor() {
        return $this->factor;
    }

    function getDescripcion() {
        return $this->descripcion;
    }

    function getTipo() {
        return $this->tipo;
    }

    function getFecingreso() {
        return $this->fecingreso;
    }

    function getFecfin() {
        return $this->fecfin;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    function setApellido($apellidos) {
        $this->apellidos = $apellidos;
    }

    function setFactor($factor) {
        $this->factor = $factor;
    }

    function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    function setFecingreso($fecingreso) {
        $this->fecingreso = $fecingreso;
    }

    function setFecfin($fecfin) {
        $this->fecfin = $fecfin;
    }

    function getValor() {
        return $this->valor;
    }

    function setValor($valor) {
        $this->valor = $valor;
    }

    function getProyecto() {
        return $this->proyecto;
    }

    function getLider() {
        return $this->lider;
    }

    function getResponsable() {
        return $this->responsable;
    }

    function setProyecto($proyecto) {
        $this->proyecto = $proyecto;
    }

    function setLider($lider) {
        $this->lider = $lider;
    }

    function setResponsable($responsable) {
        $this->responsable = $responsable;
    }

    function getIdlider() {
        return $this->idlider;
    }

    function getIdProyecto() {
        return $this->idProyecto;
    }

    function setIdlider($idlider) {
        $this->idlider = $idlider;
    }

    function setIdProyecto($idProyecto) {
        $this->idProyecto = $idProyecto;
    }

    function getCargoTrab() {
        return $this->cargoTrab;
    }

    function getDniTrab() {
        return $this->dniTrab;
    }

    function setCargoTrab($cargoTrab) {
        $this->cargoTrab = $cargoTrab;
    }

    function setDniTrab($dniTrab) {
        $this->dniTrab = $dniTrab;
    }



}