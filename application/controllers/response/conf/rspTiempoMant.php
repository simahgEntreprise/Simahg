<?php
require_once RESPONSE_PATH.'BaseResponse.php';
class rspTiempoMant extends BaseResponse{
    static function  parseToArray($anyValue) {
        return parent::handler(new rspTiempoMant(), $anyValue);
    }
    public function  parse($anyValue) {
        if($anyValue == null){return null;}
        parent::check('dmnTiempoMantenimiento', $anyValue);
        $fields['id']                   = $anyValue->getId();
        $fields['nombre']               = $anyValue->getNombre();        
        $fields['tipo']               = $anyValue->getTipo();        
        $fields['accion']                   = $anyValue->getId();
        return $fields;
    }
}