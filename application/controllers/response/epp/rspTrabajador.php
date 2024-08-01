<?php
require_once RESPONSE_PATH.'BaseResponse.php';
require_once RESPONSE_PATH.'conf/rspCargo.php';
require_once RESPONSE_PATH.'conf/rspArea.php';
require_once RESPONSE_PATH.'epp/rspProyecto.php';
class rspTrabajador extends BaseResponse{
    static function  parseToArray($anyValue) {
        return parent::handler(new rspTrabajador(), $anyValue);
    }
    public function  parse($anyValue) {
        if($anyValue == null){return null;}
        parent::check('dmnTrabajador', $anyValue);
        $fields['id']                   = $anyValue->getId();
        $fields['idarea']               = rspArea::parseToArray($anyValue->getidarea());
        $fields['idcargo']              = rspCargo::parseToArray($anyValue->getidcargo()); 
        $fields['idproy']              = rspProyecto::parseToArray($anyValue->getidProy()); 
        $fields['nombre']                  = $anyValue->getNombre();
        $fields['apellidos']                  = $anyValue->getApellido();
        $fields['dni']                  = $anyValue->getDni();                
        $fields['accion']               = $anyValue->getId();        
        $fields['checked']               = $anyValue->getChecked();        
        return $fields;
    }
}
