<?php
require_once RESPONSE_PATH.'BaseResponse.php';
class rspAccesoPerfil extends BaseResponse{
    static function  parseToArray($anyValue) {
        return parent::handler(new rspAccesoPerfil(), $anyValue);
    }
    public function  parse($anyValue) {
        if($anyValue == null){return null;}
        parent::check('dmnAcceso', $anyValue);
        $fields['id']                   = $anyValue->getId();
        $fields['nombre']   =$anyValue->getNombre();
        $fields['idapp']   =$anyValue->getIdApp();
        $fields['idopc']   =$anyValue->getIdopc();
	$fields['ruta']   = $anyValue->getRuta();        
        return $fields;
    }
}