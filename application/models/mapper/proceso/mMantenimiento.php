<?php
require_once APPPATH.'models/BaseModel.php';
require_once DOMAIN_PATH.'proceso/dmnMantenimiento.php';
class mMantenimiento extends BaseModel{
    protected  $fields = 'id,iditem,horastrab,idEmpresa,idtipo,responsable,fecingreso,fecsalida,observacion,estado,fecregistro,fecmodificacion,lugar';
    protected $table ='t_mantenimiento';
    public function  __construct() {
        parent::__construct();
    }
    public function find(array $constraints){
        $this->db->select($this->fields);
        if(isset($constraints['id'])){
            $this->db->where('id',$constraints['id']);        
        }  else {
            $this->session->set_flashdata('area','Los datos introducidos son incorrectos');
        }
        $res = $this->db->get($this->table);        
        return $this->createObject($res);
    }

    public function finder(array $constraints){
        $this->load->database();
        $this->db->select($this->fields);

        if(isset($constraints['iditem']) && strlen(trim($constraints['iditem'])) > 0){
            $this->db->where('iditem',$constraints['iditem']);
        }
        if(isset($constraints['estado']) && strlen(trim($constraints['estado']))> 0){
            $this->db->where('estado',$constraints['estado']);
        }
        if(isset($constraints['id']) && strlen(trim($constraints['id'])) > 0){
            $this->db->like('id',$constraints['id']);
        }
        $res = $this->db->get($this->table);
        if($res == null){
            throw new Exception('Error en MapperMantenimiento'.$this->db->last_query(),-1);
        }
        return $this->paginate($res);
    }

    public function doCreateObject(array $fields){
        $dmnMantenimiento = new dmnMantenimiento($fields['id']);
        $dmnMantenimiento->setIdItem(new dmnItem($fields['iditem']));
        $dmnMantenimiento->setEstado($fields['estado']);
        $dmnMantenimiento->setFecIngreso($fields['fecingreso']);
        $dmnMantenimiento->setFecSalida($fields['fecsalida']);
        $dmnMantenimiento->setFecmodificacion($fields['fecmodificacion']);
        $dmnMantenimiento->setFecregistro($fields['fecregistro']);
        $dmnMantenimiento->setHorastrab($fields['horastrab']);                
        $dmnMantenimiento->setIdEmpresa(new dmnEmpresa($fields['idempresa']));
        $dmnMantenimiento->setIdtipo(new dmnTipoMant($fields['idtipo']));
        $dmnMantenimiento->setObservacion($fields['observacion']);
        $dmnMantenimiento->setResponsable($fields['responsable']);                
        $dmnMantenimiento->setLugar($fields['lugar']);                
        
        return $dmnMantenimiento;
    }

    public function create(dmnMantenimiento $dmnMantenimiento){
        try{
            $this->db->trans_begin();
            $this->doCreate($dmnMantenimiento);
            $this->db->trans_commit();
        }catch(Exception $e){
            $this->db->trans_rollback();
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    private function doCreate(dmnMantenimiento $dmnMantenimiento){
        
//        $fields['id'] = $dmnMantenimiento->getId();
        $fields['iditem'] = ($dmnMantenimiento->getIdItem() != null ?  $dmnMantenimiento->getIdItem()->getId() : '' );
        $fields['estado'] = $dmnMantenimiento->getEstado();
        $fields['fecingreso'] = $dmnMantenimiento->getFecIngreso();
        $fields['fecsalida'] = $dmnMantenimiento->getFecSalida();
        $fields['fecmodificacion'] = $dmnMantenimiento->getFecmodificacion();
        $fields['fecregistro'] = $dmnMantenimiento->getFecregistro();
        $fields['horastrab'] = $dmnMantenimiento->getHorastrab();
        $fields['idempresa'] = ($dmnMantenimiento->getIdEmpresa() != null ? $dmnMantenimiento->getIdEmpresa()->getId() : '');
        $fields['idtipo'] = ($dmnMantenimiento->getIdtipo() != null ? $dmnMantenimiento->getIdtipo()->getId() : '');
        $fields['observacion'] = $dmnMantenimiento->getObservacion();
        $fields['responsable'] = $dmnMantenimiento->getResponsable();
        $fields['lugar'] = $dmnMantenimiento->getLugar();                
        
        $this->db->set($fields);
        $this->db->insert($this->table);

        if($this->db->trans_status() === false){
            throw new Exception("Hubo un problema al Registrar empresa, revisar los datos Ingresados: ". $this->db->last_query(), -1);
        }
    }

    public function update(dmnMantenimiento $dmnMantenimiento){
        try{
            $this->db->trans_begin();
            $this->doUpdate($dmnMantenimiento);
            $this->db->trans_commit();
        }catch(Exception $e){
            $this->db->trans_rollback();
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    private function doUpdate(dmnMantenimiento $dmnMantenimiento){

//        $fields['id'] = $dmnMantenimiento->getId();
        $fields['iditem'] = ($dmnMantenimiento->getIdItem() != null ?  $dmnMantenimiento->getIdItem()->getId() : '' );
        $fields['estado'] = $dmnMantenimiento->getEstado();
        $fields['fecingreso'] = $dmnMantenimiento->getFecIngreso();
        $fields['fecsalida'] = $dmnMantenimiento->getFecSalida();
        $fields['fecmodificacion'] = $dmnMantenimiento->getFecmodificacion();
        $fields['fecregistro'] = $dmnMantenimiento->getFecregistro();
        $fields['horastrab'] = $dmnMantenimiento->getHorastrab();
        $fields['idempresa'] = ($dmnMantenimiento->getIdEmpresa() != null ? $dmnMantenimiento->getIdEmpresa()->getId() : '');
        $fields['idtipo'] = ($dmnMantenimiento->getIdtipo() != null ? $dmnMantenimiento->getIdtipo()->getId() : '');
        $fields['observacion'] = $dmnMantenimiento->getObservacion();
        $fields['responsable'] = $dmnMantenimiento->getResponsable();
        $fields['lugar'] = $dmnMantenimiento->getLugar();                
        
        $this->db->set($fields);
        $this->db->where(array('ID' => $dmnMantenimiento->getId()));
        $this->db->update($this->table);

        if($this->db->trans_status() === false){
            throw new Exception("Hubo un problema al Modificar empresa, revisar los datos Ingresados: ". $this->db->last_query(), -1);
        }
    }

    public function delete(dmnMantenimiento $dmnMantenimiento){
        try{
            $this->db->trans_begin();
            $this->doDelete($dmnMantenimiento);
            $this->db->trans_commit();
        }catch(Exception $e){
            $this->db->trans_rollback();
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    private function doDelete(dmnMantenimiento $dmnMantenimiento){
        $this->db->where(array('id' => $dmnMantenimiento->getId()));
        $this->db->delete($this->table);

        if($this->db->affected_rows()==0){
            throw new Exception("Hubo un problema al Eliminar empresa, revisar los datos Ingresados: ". $this->db->last_query(), -1);
        }
        if($this->db->trans_status() === false){
            throw new Exception("Hubo un problema al Eliminar empresa, revisar los datos Ingresados: ". $this->db->last_query(), -1);
        }
    }
    
    public function obtainLastStatus($idItem){
        $this->db->select('estado ');        
        $this->db->where('idItem',$idItem);                
        $this->db->limit(1);
        $this->db->order_by('id','desc');
        $res = $this->db->get($this->table);                        
        $estado = null;
        If ($res->num_rows() > 0 ){
            $estado = $res->row();
        }        
        return $estado;        
    }
    
}

