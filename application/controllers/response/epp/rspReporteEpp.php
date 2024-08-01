<?php
require_once RESPONSE_PATH.'BaseResponse.php';
class rspReporteEpp extends BaseResponse{
    static function  parseToArray($anyValue) {
        return parent::handler(new rspReporteEpp(), $anyValue);
    }
    public function  parse($anyValue) {
        if($anyValue == null){return null;}
        parent::check('dmnReporteEpp', $anyValue);
        $fields['nombre']                   = $anyValue->getNombre();
        $fields['apellidos']                   = $anyValue->getApellido();
        $fields['factor']               = $anyValue->getFactor();        
        $fields['descripcion']               = $anyValue->getDescripcion();        
        $fields['tipo']               = $anyValue->getTipo();
        $fields['fecing']               = $anyValue->getFecIngreso();
        $fields['fecfin']               = $anyValue->getFecfin();
        $fields['proyecto']               = $anyValue->getProyecto();
        $fields['lider']               = $anyValue->getLider();
        $fields['idLider']              = $anyValue->getidLider();
        $fields['responsable']               = $anyValue->getResponsable();
        $fields['accion']               = $anyValue->getValor();
        $fields['viewLider'] = $anyValue->getidLider()." - ".$anyValue->getLider();
        return $fields;
    }
}
