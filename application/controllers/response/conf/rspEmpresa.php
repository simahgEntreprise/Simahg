<?php
require_once RESPONSE_PATH.'BaseResponse.php';
class rspEmpresa extends BaseResponse{    
    
    static function  parseToArray($anyValue) {
        return parent::handler(new rspEmpresa(), $anyValue);
    }
    public function  parse($anyValue) {
        if($anyValue == null){return null;}
        parent::check('dmnEmpresa', $anyValue);
        $fields['id']                   = $anyValue->getId();
        $fields['nombre']               = $anyValue->getNombre();                
        $fields['ruc']               = $anyValue->getRuc();                
        $fields['representante']               = $anyValue->getRepresentante();                
        $fields['accion']                   = $anyValue->getId();
        return $fields;
    }
}


