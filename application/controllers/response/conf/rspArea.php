<?php
require_once RESPONSE_PATH.'BaseResponse.php';
class rspArea extends BaseResponse{
    static function  parseToArray($anyValue) {
        return parent::handler(new rspArea(), $anyValue);
    }
    public function  parse($anyValue) {
        if($anyValue == null){return null;}
        parent::check('dmnArea', $anyValue);
        $fields['id']                   = $anyValue->getId();
        $fields['nombre']               = $anyValue->getNombre();        
        $fields['accion']               = $anyValue->getId();
        return $fields;
    }
}
