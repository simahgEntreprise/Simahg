<?php
require_once RESPONSE_PATH.'BaseResponse.php';
require_once RESPONSE_PATH.'epp/rspTrabajador.php';
class rspProyecto extends BaseResponse{
    static function  parseToArray($anyValue) {
        return parent::handler(new rspProyecto(), $anyValue);
    }
    public function  parse($anyValue) {
        if($anyValue == null){return null;}
        parent::check('dmnProyecto', $anyValue);
        $fields['id']                   = $anyValue->getId();
        $fields['lider']               = rspTrabajador::parseToArray($anyValue->getLider());
        $fields['nombre']              = $anyValue->getNombre();
        $fields['responsable']              = $anyValue->getResponsable();
        $fields['estado']                  = $anyValue->getEstado();        
        $fields['accion']               = $anyValue->getId();        
        return $fields;
    }
}
