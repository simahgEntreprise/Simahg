<?php
require_once RESPONSE_PATH.'BaseResponse.php';
require_once RESPONSE_PATH.'epp/rspRiesgo.php';
require_once RESPONSE_PATH.'epp/rspTipoEpp.php';
class rspEpp extends BaseResponse{
    static function  parseToArray($anyValue) {
        return parent::handler(new rspEpp(), $anyValue);
    }
    public function  parse($anyValue) {
        if($anyValue == null){return null;}
        parent::check('dmnEpp', $anyValue);
        $fields['id']                   = $anyValue->getId();
        $fields['riesgo']               = rspRiesgo::parseToArray($anyValue->getRiesgo());
        $fields['tipoEpp']              = rspTipoEpp::parseToArray($anyValue->getTipo()); 
        $fields['uso']                  = $anyValue->getUso();
        $fields['imagen']                  = $anyValue->getImagen();
        $fields['vidaUtil']                  = $anyValue->getVidaUtil();
        $fields['tiempoVidaUtil']                  = $anyValue->getTiempoVidaUtil();
        $fields['especificacion']                  = $anyValue->getEspecificacion();
        $fields['norma']                  = $anyValue->getNorma();        
        $fields['estado']                  = $anyValue->getEstado();        
        $fields['accion']               = $anyValue->getId();
        $fields['checked']               = $anyValue->getChecked();
        return $fields;
    }
}
