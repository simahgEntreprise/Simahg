<?php
require_once RESPONSE_PATH.'BaseResponse.php';
class rspGrupo extends BaseResponse{
    static function  parseToArray($anyValue) {
        return parent::handler(new rspGrupo(), $anyValue);
    }
    public function  parse($anyValue) {
        if($anyValue == null){return null;}
        parent::check('dmnGrupo', $anyValue);
        $fields['id']                   = $anyValue->getId();
        $fields['nombre']               = $anyValue->getNombre();        
        $fields['accion']                   = $anyValue->getId();
        return $fields;
    }
}
