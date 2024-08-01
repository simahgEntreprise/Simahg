<?php
require_once RESPONSE_PATH.'BaseResponse.php';
class rspCargo extends BaseResponse{
    static function  parseToArray($anyValue) {
        return parent::handler(new rspCargo(), $anyValue);
    }
    public function  parse($anyValue) {
        if($anyValue == null){return null;}
        parent::check('dmnCargo', $anyValue);
        $fields['id']                   = $anyValue->getId();
        $fields['nombre']               = $anyValue->getNombre();                
        $fields['accion']                   = $anyValue->getId();
        return $fields;
    }
}

