<?php
require_once APPPATH.'models/BaseModel.php';
class mRegistroEquipo extends BaseModel{
    protected  $fields = 'id,equipo,responsable,fecsolicitud,fecEntrega,fecregistro,fecmodificacion,asignado';
    protected $table ='t_solicitudEquipo';
    public function  __construct() {
        parent::__construct();
    }
    public function find(array $constraints){
        $this->db->select($this->fields);
        if(isset($constraints['id'])){
            $this->db->where('id',$constraints['id']);        
        }  else {
            $this->session->set_flashdata('registroEquipo','Los datos introducidos son incorrectos');
        }
        $res = $this->db->get($this->table);        
        return $this->createObject($res);
    }

    public function finder(array $constraints){
        $this->load->database();
        $this->db->select($this->fields);

        if(isset($constraints['nombre']) && strlen(trim($constraints['nombre'])) > 0){
            $this->db->like('nombre',$constraints['nombre']);
        }
        if(isset($constraints['id']) && strlen(trim($constraints['id'])) > 0){
            $this->db->like('id',$constraints['id']);
        }
        $this->db->order_by('fecentrega','desc');
        $res = $this->db->get($this->table);
        if($res == null){
            throw new Exception('Error en MapperEquipo'.$this->db->last_query(),-1);
        }
        return $this->paginate($res);
    }

    public function doCreateObject(array $fields){
        $dmnSolicitudEquipo = new dmnSolicitudEquipo($fields['id']);
        $dmnSolicitudEquipo->setEquipo(new dmnEquipo($fields['equipo']));
        $dmnSolicitudEquipo->setResponsable($fields['responsable']);
        $dmnSolicitudEquipo->setAsignado($fields['asignado']);
        $dmnSolicitudEquipo->setFecSolicitud($fields['fecsolicitud']);        
        $dmnSolicitudEquipo->setFecEntrega($fields['fecentrega']);        
        $dmnSolicitudEquipo->setFecregistro($fields['fecregistro']);        
        $dmnSolicitudEquipo->setFecmodificacion($fields['fecmodificacion']);        
        
        return $dmnSolicitudEquipo;
    }

    public function create(dmnSolicitudEquipo $dmnSolicitudEquipo){
        try{
            $this->db->trans_begin();
            $this->doCreate($dmnSolicitudEquipo);
            $this->db->trans_commit();
        }catch(Exception $e){
            $this->db->trans_rollback();
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    private function doCreate(dmnSolicitudEquipo $dmnSolicitudEquipo){
        
//        $fields['id'] = $dmnSolicitudEquipo->getId();
        $fields['equipo'] = $dmnSolicitudEquipo->getEquipo()->getId() ;
        $fields['responsable'] = $dmnSolicitudEquipo->getResponsable();
        $fields['asignado'] = $dmnSolicitudEquipo->getAsignado();
        $fields['fecsolicitud'] = $dmnSolicitudEquipo->getFecSolicitud();
        $fields['fecentrega'] = $dmnSolicitudEquipo->getFecEntrega();
        $fields['fecregistro'] = $dmnSolicitudEquipo->getFecregistro();
        $fields['fecmodificacion'] = $dmnSolicitudEquipo->getFecmodificacion();
        
        $this->db->set($fields);
        $this->db->insert($this->table);

        if($this->db->trans_status() === false){
            throw new Exception("Hubo un problema al Registrar empresa, revisar los datos Ingresados: ". $this->db->last_query(), -1);
        }
    }

    public function update(dmnSolicitudEquipo $dmnSolicitudEquipo){
        try{
            $this->db->trans_begin();
            $this->doUpdate($dmnSolicitudEquipo);
            $this->db->trans_commit();
        }catch(Exception $e){
            $this->db->trans_rollback();
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    private function doUpdate(dmnSolicitudEquipo $dmnSolicitudEquipo){

//        $fields['id'] = $dmnSolicitudEquipo->getId();
        $fields['equipo'] = $dmnSolicitudEquipo->getEquipo()->getId() ;;
        $fields['responsable'] = $dmnSolicitudEquipo->getResponsable();
        $fields['asignado'] = $dmnSolicitudEquipo->getAsignado();
        $fields['fecsolicitud'] = $dmnSolicitudEquipo->getFecSolicitud();
        $fields['fecentrega'] = $dmnSolicitudEquipo->getFecEntrega();
        $fields['fecregistro'] = $dmnSolicitudEquipo->getFecregistro();
        $fields['fecmodificacion'] = $dmnSolicitudEquipo->getFecmodificacion();
        
        $this->db->set($fields);
        $this->db->where(array('ID' => $dmnSolicitudEquipo->getId()));
        $this->db->update($this->table);

        if($this->db->trans_status() === false){
            throw new Exception("Hubo un problema al Modificar empresa, revisar los datos Ingresados: ". $this->db->last_query(), -1);
        }
    }

    public function delete(dmnSolicitudEquipo $dmnSolicitudEquipo){
        try{
            $this->db->trans_begin();
            $this->doDelete($dmnSolicitudEquipo);
            $this->db->trans_commit();
        }catch(Exception $e){
            $this->db->trans_rollback();
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    private function doDelete(dmnSolicitudEquipo $dmnSolicitudEquipo){
        $this->db->where(array('id' => $dmnSolicitudEquipo->getId()));
        $this->db->delete($this->table);

        if($this->db->affected_rows()==0){
            throw new Exception("Hubo un problema al Eliminar empresa, revisar los datos Ingresados: ". $this->db->last_query(), -1);
        }
        if($this->db->trans_status() === false){
            throw new Exception("Hubo un problema al Eliminar empresa, revisar los datos Ingresados: ". $this->db->last_query(), -1);
        }
    }

}
