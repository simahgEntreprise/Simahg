<?php
require_once RESPONSE_PATH.'BaseResponse.php';
class rspTipoEpp extends BaseResponse{
    static function  parseToArray($anyValue) {
        return parent::handler(new rspTipoEpp(), $anyValue);
    }
    public function  parse($anyValue) {
        if($anyValue == null){return null;}
        parent::check('dmnTipoEpp', $anyValue);
        $fields['id']                   = $anyValue->getId();
        $fields['nombre']               = $anyValue->getNombre();        
        $fields['accion']               = $anyValue->getId();
        return $fields;
    }
}
