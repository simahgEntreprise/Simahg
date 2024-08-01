<?php
require_once RESPONSE_PATH.'BaseResponse.php';
class rspRiesgo extends BaseResponse{
    static function  parseToArray($anyValue) {
        return parent::handler(new rspRiesgo(), $anyValue);
    }
    public function  parse($anyValue) {
        if($anyValue == null){return null;}
        parent::check('dmnRiesgo', $anyValue);
        $fields['id']                   = $anyValue->getId();
        $fields['factor']               = $anyValue->getFactor();        
        $fields['descripcion']               = $anyValue->getDescripcion();        
        $fields['accion']               = $anyValue->getId();
        return $fields;
    }
}
