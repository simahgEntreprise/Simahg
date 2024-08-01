<?php
require_once RESPONSE_PATH.'BaseResponse.php';
require_once RESPONSE_PATH.'conf/rspEquipoConf.php';
class rspEquipo extends BaseResponse{
    static function  parseToArray($anyValue) {
        return parent::handler(new rspEquipo(), $anyValue);
    }
    public  function  parse($anyValue) {
        if($anyValue == null){return null;}
        parent::check('dmnSolicitudEquipo', $anyValue);
        $fields['id']                   = $anyValue->getId();
        $fields['responsable']               = $anyValue->getResponsable();        
        $fields['equipo']               = rspEquipoConf::parseToArray($anyValue->getEquipo());        
        $fields['fecsolicitud']               = $anyValue->getFecSolicitud();        
        $fields['fecentrega']               = $anyValue->getFecEntrega();                
        $fields['asignado']             = $anyValue->getAsignado();
        $fields['accion']                   = $anyValue->getId();
        return $fields;
    }
}