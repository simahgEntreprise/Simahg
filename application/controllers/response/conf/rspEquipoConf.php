<?php
require_once RESPONSE_PATH.'BaseResponse.php';
class rspEquipoConf extends BaseResponse{
    static function  parseToArray($anyValue) {
        return parent::handler(new rspEquipoConf(), $anyValue);
    }
    public function  parse($anyValue) {
        if($anyValue == null){return null;}
        parent::check('dmnEquipo', $anyValue);
        $fields['id']                   = $anyValue->getId();
        $fields['nombre']               = $anyValue->getNombre();        
        $fields['responsable']          = $anyValue->getResponsable();
        return $fields;
    }
}
