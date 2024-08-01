<?php
require_once RESPONSE_PATH.'BaseResponse.php';
require_once RESPONSE_PATH.'proceso/rspItem.php';
require_once RESPONSE_PATH.'conf/rspTipoMant.php';
require_once RESPONSE_PATH.'conf/rspEmpresa.php';
class rspMantenimiento extends BaseResponse{
    static function  parseToArray($anyValue) {
        return parent::handler(new rspMantenimiento(), $anyValue);
    }
    public  function  parse($anyValue) {
        if($anyValue == null){return null;}
        parent::check('dmnMantenimiento', $anyValue);
        $fields['id']                   = $anyValue->getId();
        $fields['item']                 = rspItem::parseToArray($anyValue->getidItem());
        $fields['estado']               =$anyValue->getEstado();
        $fields['fecIngreso']           =$anyValue->getFecIngreso();
        $fields['fecSalida']            =$anyValue->getFecSalida();        
        $fields['horaTrab']             =$anyValue->getHorastrab();
        $fields['id']                   =$anyValue->getId();
        $fields['empresa']              =  rspEmpresa::parseToArray($anyValue->getIdEmpresa());
        $fields['tipoMant']             =  rspTipoMant::parseToArray($anyValue->getIdtipo());
        $fields['observacion']          =$anyValue->getObservacion();
        $fields['responsable']          =$anyValue->getResponsable();                
        $fields['lugar']                =$anyValue->getLugar();                
        $fields['accion']                   = $anyValue->getId();
        return $fields;
    }
}