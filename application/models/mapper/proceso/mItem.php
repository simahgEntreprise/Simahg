<?php
require_once APPPATH.'models/BaseModel.php';
require_once DOMAIN_PATH.'proceso/dmnItem.php';
require_once DOMAIN_PATH.'configuracion/dmnArea.php';
require_once DOMAIN_PATH.'configuracion/dmnTiempoMantenimiento.php';
class mItem extends BaseModel{
    protected $fields = 'id,nombre,codigo,numserie,imagen,idarea,responsable,fecIngreso,fecUsoItem,idTiempoMant,fecRegistro,fecModificacion,cantTiempo,numseriealt,modelo ,modeloalt ,tecnico ,idareatec ,motor ,idgrupo, nrofactura,estado';
    protected $table = 't_item';

    public function  __construct() {
        parent::__construct();
    }

    public function find(array $constraints){
        $this->db->select($this->fields);
        if(isset($constraints['id'])){
            $this->db->where('id',(int)$constraints['id']);
        }else{
            return null;
        }
        $res = $this->db->get($this->table);
//        print_r($this->db->last_query());exit;
        return $this->createObject($res);
    }

    public function finder(array $constraints){
        $this->load->database();
        $this->db->select($this->fields);

        if(isset($constraints['nombre']) && strlen(trim($constraints['nombre'])) > 0){
            $this->db->like('nombre',$constraints['nombre']);
        }        
        if(isset($constraints['id']) && strlen(trim($constraints['id'])) > 0){
            $this->db->where('id',$constraints['id']);
        }        
        if(isset($constraints['estado']) && strlen(trim($constraints['estado'])) > 0){
            $this->db->where('estado',$constraints['estado']);
        }
        $this->db->order_by('fecUsoItem','DSC');
        $res = $this->db->get($this->table);
//        print_r($this->db->last_query());exit;
        if($res == null){
            throw new Exception('Error en MapperItem'.$this->db->last_query(),-1);
        }
        return $this->paginate($res);
    }

    public function doCreateObject(array $fields){
        $dmnItem = new dmnItem($fields['id']);
        $dmnItem->setCodigo($fields['codigo']);
        $dmnItem->setNombre($fields['nombre']);
        $dmnItem->setNumserie($fields['numserie']);
        $dmnItem->setImagen($fields['imagen']);
        $dmnItem->setIdarea(new dmnArea($fields['idarea']));
        $dmnItem->setResponsable($fields['responsable']);
        $dmnItem->setFecIngreso($fields['fecingreso']);
        $dmnItem->setFecUsoItem($fields['fecusoitem']);
        $dmnItem->setIdtiempoMant(new dmnTiempoMantenimiento($fields['idtiempomant']));
        $dmnItem->setFecregistro($fields['fecregistro']);
        $dmnItem->setFecmodificacion($fields['fecmodificacion']);
        $dmnItem->setCantidad($fields['canttiempo']);
        $dmnItem->setNumserie2($fields['numseriealt']);
        $dmnItem->setModelo1($fields['modelo']);
        $dmnItem->setModelo2($fields['modeloalt']);
        $dmnItem->setTecnico($fields['tecnico']);
        $dmnItem->setAreaTec(new dmnArea($fields['idareatec']));
        $dmnItem->setMotor($fields['motor']);
        $dmnItem->setGrupo(new dmnGrupo($fields['idgrupo']));
        $dmnItem->setNroFactura($fields['nrofactura']);
        $dmnItem->setEstado($fields['estado']);
        return $dmnItem;
    }

    public function create(dmnItem $dmnItem){
        try{
            $this->db->trans_begin();
            $this->doCreate($dmnItem);
            $this->db->trans_commit();
        }catch(Exception $e){
            $this->db->trans_rollback();
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    private function doCreate(dmnItem $dmnItem){
        $fields['CODIGO'] = $dmnItem->getCodigo();
        $fields['NOMBRE'] = $dmnItem->getNombre();
        $fields['NUMSERIE'] = $dmnItem->getNumserie();
        $fields['IMAGEN'] = $dmnItem->getImagen();            
        $fields['IDAREA'] = ($dmnItem->getIdarea()->getId() == '' ? null : $dmnItem->getIdarea()->getId());
        $fields['RESPONSABLE'] = $dmnItem->getResponsable();
        $fields['FECINGRESO'] = $dmnItem->getFecIngreso();
        $fields['FECUSOITEM'] = $dmnItem->getFecUsoItem();
        $fields['IDTIEMPOMANT'] = ($dmnItem->getIdtiempoMant()->getId() == '' ? null : $dmnItem->getIdtiempoMant()->getId());
        $fields['FECREGISTRO'] = $dmnItem->getFecregistro();
        $fields['FECMODIFICACION'] = $dmnItem->getFecmodificacion();
        $fields['CANTTIEMPO'] = $dmnItem->getCantidad();
        $fields['numseriealt'] = $dmnItem->getNumserie2();
        $fields['modeloalt']   = $dmnItem->getModelo2();
        $fields['tecnico']     = $dmnItem->getTecnico();
        $fields['modelo']      = $dmnItem->getModelo1();
        $fields['idareatec']   = ($dmnItem->getAreaTec()->getId()=='' ? null :$dmnItem->getAreaTec()->getId());
        $fields['motor']       = $dmnItem->getMotor();
        $fields['idgrupo']     = ($dmnItem->getGrupo()->getId()=='' ? null : $dmnItem->getGrupo()->getId());
        $fields['nrofactura'] = $dmnItem->getNroFactura();
        $fields['estado'] = $dmnItem->getEstado();

        $this->db->set($fields);
        $this->db->insert($this->table);
//        var_dump($this->db->trans_status());exit;
        if($this->db->trans_status() === false){
            throw new Exception("Hubo un problema al Registrar item, revisar los datos Ingresados: ". $this->db->last_query(), -1);
        }
    }

    public function update(dmnItem $dmnItem){
        try{
            $this->db->trans_begin();
            $this->doUpdate($dmnItem);
            $this->db->trans_commit();
        }catch(Exception $e){
            $this->db->trans_rollback();
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    private function doUpdate(dmnItem $dmnItem){        
        
        $fields['CODIGO'] = $dmnItem->getCodigo();
        $fields['NOMBRE'] = $dmnItem->getNombre();
        $fields['NUMSERIE'] = $dmnItem->getNumserie();
        if ($dmnItem->getImagen()<> ''){
            $fields['IMAGEN'] = $dmnItem->getImagen();
        }        
        $fields['IDAREA'] = ($dmnItem->getIdarea()->getId() == '' ? null : $dmnItem->getIdarea()->getId());        
        $fields['RESPONSABLE'] = $dmnItem->getResponsable();
        $fields['FECINGRESO'] = $dmnItem->getFecIngreso();
        $fields['FECUSOITEM'] = $dmnItem->getFecUsoItem();
        $fields['IDTIEMPOMANT'] = ($dmnItem->getIdtiempoMant()->getId() == '' ? null : $dmnItem->getIdtiempoMant()->getId());        
        $fields['FECREGISTRO'] = $dmnItem->getFecregistro();
        $fields['FECMODIFICACION'] = $dmnItem->getFecmodificacion();
        $fields['CANTTIEMPO'] = $dmnItem->getCantidad();                
        $fields['numseriealt'] = $dmnItem->getNumserie2();
        $fields['modeloalt']   = $dmnItem->getModelo2();
        $fields['tecnico']     = $dmnItem->getTecnico();
        $fields['modelo']      = $dmnItem->getModelo1();
        $fields['idareatec']   = ($dmnItem->getAreaTec()->getId() == '' ? null : $dmnItem->getAreaTec()->getId());
        $fields['motor']       = $dmnItem->getMotor();
        $fields['idgrupo']     = ($dmnItem->getGrupo()->getId() == '' ? null : $dmnItem->getGrupo()->getId());
        $fields['nrofactura'] = $dmnItem->getNroFactura();
        $fields['estado'] = $dmnItem->getEstado();
        $this->db->set($fields);
        $this->db->where(array('ID' => $dmnItem->getId()));
        $this->db->update($this->table);

        if($this->db->affected_rows()==0){
            throw new Exception("Hubo un problema al Modificar Item, revisar los datos Ingresados: ". $this->db->last_query(), -1);
        }
        if($this->db->trans_status() === false){
            throw new Exception("Hubo un problema al Modificar Item, revisar los datos Ingresados: ". $this->db->last_query(), -1);
        }
    }

    public function delete(dmnItem $dmnItem){
        try{
            $this->db->trans_begin();
            $this->doDelete($dmnItem);
            $this->db->trans_commit();
        }catch(Exception $e){
            $this->db->trans_rollback();
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    private function doDelete(dmnItem $dmnItem){
        $this->db->where(array('id' => $dmnItem->getId()));
        $this->db->delete($this->table);

        if($this->db->affected_rows()==0){
            throw new Exception("Hubo un problema al Eliminar Item, revisar los datos Ingresados: ". $this->db->last_query(), -1);
        }
        if($this->db->trans_status() === false){
            throw new Exception("Hubo un problema al Eliminar Item, revisar los datos Ingresados: ". $this->db->last_query(), -1);
        }
    }
}


