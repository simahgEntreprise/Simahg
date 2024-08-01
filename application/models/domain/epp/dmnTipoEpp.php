<?php
require_once APPPATH.'/libraries/Lib_Domain_VO.php';
class dmnTipoEpp extends Lib_Domain_VO{
    protected $id;
    protected $nombre;    
    
    function __construct($id= null) {
        $this->id = $id;
    }

    function getId() {
        return $this->id;
    }

    function getNombre() {
        return $this->nombre;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }


    }
