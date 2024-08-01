<?php
require_once RESPONSE_PATH.'BaseResponse.php';
class rspPerfil extends BaseResponse{
    static function  parseToArray($anyValue) {
        return parent::handler(new rspPerfil(), $anyValue);
    }
    public function  parse($anyValue) {
        if($anyValue == null){return null;}
        parent::check('dmnPerfil', $anyValue);
        $fields['id']                   = $anyValue->getId();
        $fields['nombre']               = $anyValue->getNombre();                
        $fields['flgAccesoMovil']               = $anyValue->getFlgAccesoMovil();                
        $fields['accion']                   = $anyValue->getId();
        return $fields;
    }
}


