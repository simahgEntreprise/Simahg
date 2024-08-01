<?php
require_once APPPATH.'/libraries/Lib_Domain_VO.php';
class dmnPerfilAcceso extends Lib_Domain_VO{
    protected $id;
    protected $idperfil;
    protected $idAcceso;
    
    function __construct($id=null) {
        $this->id = $id;
    }
    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getIdperfil() {
        return $this->idperfil;
    }

    public function setIdperfil($idperfil) {
        $this->idperfil = $idperfil;
    }

    public function getIdAcceso() {
        return $this->idAcceso;
    }

    public function setIdAcceso($idAcceso) {
        $this->idAcceso = $idAcceso;
    }    
}
?>
