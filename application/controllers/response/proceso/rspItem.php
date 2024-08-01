<?php
require_once RESPONSE_PATH.'BaseResponse.php';
require_once RESPONSE_PATH.'conf/rspArea.php';
require_once RESPONSE_PATH.'conf/rspGrupo.php';
require_once RESPONSE_PATH.'conf/rspTiempoMant.php';
class rspItem extends BaseResponse{
    static function  parseToArray($anyValue) {
        return parent::handler(new rspItem(), $anyValue);
    }
    public  function  parse($anyValue) {
        if($anyValue == null){return null;}
        parent::check('dmnItem', $anyValue);
        $fields['id']                   = $anyValue->getId();
        $fields['codigo']               = $anyValue->getCodigo();        
        $fields['nombre']               = $anyValue->getNombre();        
        $fields['numserie']               = $anyValue->getNumSerie();        
        $fields['imagen']               = $anyValue->getImagen();        
        $fields['area']               = rspArea::parseToArray($anyValue->getidArea());
        $fields['responsable']               = $anyValue->getResponsable();        
        $fields['fecingreso']               = $anyValue->getFecIngreso();        
        $fields['fecusoitem']               = $anyValue->getFecUsoItem();        
        $fields['cantmant']               = $anyValue->getCantidad();        
        $fields['tiempmant']               = rspTiempoMant::parseToArray($anyValue->getidTiempoMant());
        $fields['fecregistro']               = $anyValue->getFecRegistro();        
        $fields['fecmodifica']               = $anyValue->getFecModificacion();        
        $fields['estado']               = $anyValue->getEstado();        
        $fields['numseriealt'] = $anyValue->getNumserie2();
        $fields['modelo']      = $anyValue->getModelo1();
        $fields['modeloalt']   = $anyValue->getModelo2();
        $fields['tecnico']     = $anyValue->getTecnico();
        $fields['areatec']   = rspArea::parseToArray($anyValue->getAreaTec());
        $fields['motor']       = $anyValue->getMotor();
        $fields['grupo']     = rspGrupo::parseToArray($anyValue->getGrupo());
        $fields['accion']                   = $anyValue->getId();
        $fields['accion2']                   = $anyValue->getId()."-".$anyValue->getEstado();
        $fields['lugar']                   = $anyValue->getLugar();
        $fields['nroFactura']                   = $anyValue->getNroFactura();
        return $fields;
    }
}