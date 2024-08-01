<?php
require_once 'Lib_Base_VO.php';
class Lib_Domain_VO extends Lib_Base_VO{
    protected $useMapper = FALSE;
    public function switchMapper($parameter){
        $this->useMapper = $parameter;
    }
    public function getMode(){
        return $this->useMapper;
    }
    public function Mapper(){
        $this->useMapper = TRUE;
        return $this;
    }
}
?>
