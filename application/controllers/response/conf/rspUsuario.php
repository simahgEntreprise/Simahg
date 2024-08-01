<?php
require_once RESPONSE_PATH.'BaseResponse.php';
class rspUsuario extends BaseResponse{
    static function  parseToArray($anyValue) {
        return parent::handler(new rspUsuario(), $anyValue);
    }
    public function  parse($anyValue) {
        if($anyValue == null){return null;}
        parent::check('dmnUsuario', $anyValue);
        $fields['id']                   = $anyValue->getId();
        $fields['usuario']               = $anyValue->getUsuario();        
        $fields['pasword']               = $anyValue->getContrasena();        
        $fields['nombre']               = $anyValue->getNombre();        
        $fields['apellidos']               = $anyValue->getApellido();        
        $fields['perfil']               = rspPerfil::parseToArray($anyValue->getidPerfil());
        $fields['cargo']               = rspCargo::parseToArray($anyValue->getidCargo());
        $fields['estado']               = $anyValue->getEstado();        
        $fields['accion']                   = $anyValue->getId();
        return $fields;
    }
}
