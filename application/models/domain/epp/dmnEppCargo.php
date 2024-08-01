<?php
require_once APPPATH.'/libraries/Lib_Domain_VO.php';
require_once MAPPER_PATH.'epp/mEpp.php';
require_once MAPPER_PATH.'configuracion/mCargo.php';
class dmnEppCargo extends Lib_Domain_VO{
    protected $idepp;
    protected $idcargo;
    
    function getIdepp() {
        if ($this->useMapper == TRUE){            
            $mprEpp = new mEpp();
            $this->idepp  = $mprEpp->find(array('id' => $this->idepp->getId()));
            $this->useMapper = FALSE;
        }
        return $this->idepp;
    }

    function getIdcargo() {
        if ($this->useMapper == TRUE){            
            $mprCargo = new mCargo();
            $this->idcargo= $mprCargo->find(array('id' => $this->idcargo->getId()));
            $this->useMapper = FALSE;
        }
        return $this->idcargo;
    }

    function setIdepp($idepp) {
        $this->idepp = $idepp;
    }

    function setIdcargo($idcargo) {
        $this->idcargo = $idcargo;
    }    
}