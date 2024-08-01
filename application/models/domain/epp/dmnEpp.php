<?php
require_once APPPATH.'/libraries/Lib_Domain_VO.php';
require_once MAPPER_PATH.'epp/mRiesgo.php';
require_once MAPPER_PATH.'epp/mTipoEpp.php';
class dmnEpp extends Lib_Domain_VO{
    protected $id;
    protected $riesgo;
    protected $tipo;
    protected $uso;
    protected $imagen;
    protected $vidaUtil;
    protected $tiempoVidaUtil;
    protected $especificacion;
    protected $norma;
    protected $estado;
    protected $checked;
    
    function __construct($id = null) {
        $this->id = $id;
    }

    function getId() {
        return $this->id;
    }

    function getRiesgo() {
        if ($this->useMapper == TRUE){            
            $mprRiesgo = new mRiesgo();
            $this->riesgo  = $mprRiesgo->find(array('id' => $this->riesgo->getId()));
            $this->useMapper = FALSE;
        } 
        return $this->riesgo;
    }

    function getTipo() {
        if ($this->useMapper == TRUE){            
            $mprTipoEpp = new mTipoEpp();
            $this->tipo  = $mprTipoEpp->find(array('id' => $this->tipo->getId()));
            $this->useMapper = FALSE;
        }
        return $this->tipo;
    }

    function getUso() {
        return $this->uso;
    }

    function getImagen() {
        return $this->imagen;
    }

    function getVidaUtil() {
        return $this->vidaUtil;
    }

    function getTiempoVidaUtil() {
        return $this->tiempoVidaUtil;
    }

    function getEspecificacion() {
        return $this->especificacion;
    }

    function getNorma() {
        return $this->norma;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setRiesgo($riesgo) {
        $this->riesgo = $riesgo;
    }

    function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    function setUso($uso) {
        $this->uso = $uso;
    }

    function setImagen($imagen) {
        $this->imagen = $imagen;
    }

    function setVidaUtil($vidaUtil) {
        $this->vidaUtil = $vidaUtil;
    }

    function setTiempoVidaUtil($tiempoVidaUtil) {
        $this->tiempoVidaUtil = $tiempoVidaUtil;
    }

    function setEspecificacion($especificacion) {
        $this->especificacion = $especificacion;
    }

    function setNorma($norma) {
        $this->norma = $norma;
    }

    function getEstado() {
        return $this->estado;
    }

    function setEstado($estado) {
        $this->estado = $estado;
    }

    function getChecked() {
        return $this->checked;
    }

    function setChecked($cheched) {
        $this->checked = $cheched;
    }




}
