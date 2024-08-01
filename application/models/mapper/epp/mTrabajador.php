<?php
require_once APPPATH.'models/BaseModel.php';
require_once DOMAIN_PATH.'epp/dmnTrabajador.php';
require_once DOMAIN_PATH.'configuracion/dmnArea.php';
require_once DOMAIN_PATH.'configuracion/dmnCargo.php';
require_once DOMAIN_PATH.'epp/dmnProyecto.php';
class mTrabajador extends BaseModel{
    protected  $fields = 'id,nombre,apellidos,dni,idarea,idcargo,idproyecto';
    protected $table ='t_trabajador';
    public function  __construct() {
        parent::__construct();
    }
    public function find(array $constraints){
        $this->db->select($this->fields);
        if(isset($constraints['id'])){
            $this->db->where('id',$constraints['id']);        
        }  else {
            $this->session->set_flashdata('trabajador','Los datos introducidos son incorrectos');
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
        $res = $this->db->get($this->table);
        if($res == null){
            throw new Exception('Error en MapperEpp'.$this->db->last_query(),-1);
        }
        return $this->paginate($res);
    }

    public function doCreateObject(array $fields){
        $dmnTrabajador = new dmnTrabajador($fields['id']);
        $dmnTrabajador->setIdcargo(new dmnCargo($fields['idcargo']));        
        $dmnTrabajador->setIdarea( new dmnArea($fields['idarea']));        
        $dmnTrabajador->setNombre($fields['nombre']);
        $dmnTrabajador->setApellido($fields['apellidos']);
        $dmnTrabajador->setDni($fields['dni']);
        $dmnTrabajador->setIdproy( new dmnProyecto($fields['idproyecto']));
        
        return $dmnTrabajador;
    }

    public function create(dmnTrabajador $dmnTrabajador){
        try{
            $this->db->trans_begin();
            $this->doCreate($dmnTrabajador);
            $this->db->trans_commit();
        }catch(Exception $e){
            $this->db->trans_rollback();
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    private function doCreate(dmnTrabajador $dmnTrabajador){
        
        $fields['idcargo'] = $dmnTrabajador->getIdcargo()->getId();
        $fields['idarea'] = $dmnTrabajador->getIdarea()->getId();        
        if ($dmnTrabajador->getIdproy()!=null){
        $fields['idproyecto'] = $dmnTrabajador->getIdproy()->getId();       
        }             
        $fields['nombre'] = $dmnTrabajador->getNombre();        
        $fields['apellidos'] = $dmnTrabajador->getApellido();
        $fields['dni'] = $dmnTrabajador->getDni();        
        
        $this->db->set($fields);
        $this->db->insert($this->table);

        if($this->db->trans_status() === false){
            throw new Exception("Hubo un problema al Registrar Area, revisar los datos Ingresados: ". $this->db->last_query(), -1);
        }
    }

    public function update(dmnTrabajador $dmnTrabajador){
        try{
            $this->db->trans_begin();
            $this->doUpdate($dmnTrabajador);
            $this->db->trans_commit();
        }catch(Exception $e){
            $this->db->trans_rollback();
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    private function doUpdate(dmnTrabajador $dmnTrabajador){
        if ($dmnTrabajador->getIdcargo() != null){
            $fields['idcargo'] = $dmnTrabajador->getIdcargo()->getId();
        }
        if ($dmnTrabajador->getIdarea() != null){
            $fields['idarea'] = $dmnTrabajador->getIdarea()->getId();        
        }
        if ($dmnTrabajador->getIdproy() != null){
            $fields['idproyecto'] = $dmnTrabajador->getIdproy()->getId();        
        }
        if ($dmnTrabajador->getNombre()!= null ||$dmnTrabajador->getNombre()!='' ){
            $fields['nombre'] = $dmnTrabajador->getNombre();        
        }
        if ($dmnTrabajador->getApellido() != null || $dmnTrabajador->getApellido()!=''){
            $fields['apellidos'] = $dmnTrabajador->getApellido();
        }
        if ($dmnTrabajador->getDni() !=null || $dmnTrabajador->getDni()!=''){
            $fields['dni'] = $dmnTrabajador->getDni();        
        }
        
        
        $this->db->set($fields);
        $this->db->where(array('ID' => $dmnTrabajador->getId()));
        $this->db->update($this->table);

        if($this->db->trans_status() === false){
            throw new Exception("Hubo un problema al Modificar Usuario, revisar los datos Ingresados: ". $this->db->last_query(), -1);
        }
    }

    public function delete(dmnTrabajador $dmnTrabajador){
        try{
            $this->db->trans_begin();
            $this->doDelete($dmnTrabajador);
            $this->db->trans_commit();
        }catch(Exception $e){
            $this->db->trans_rollback();
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    private function doDelete(dmnTrabajador $dmnTrabajador){
        $this->db->where(array('id' => $dmnTrabajador->getId()));
        $this->db->delete($this->table);

        if($this->db->affected_rows()==0){
            throw new Exception("Hubo un problema al Eliminar area, revisar los datos Ingresados: ". $this->db->last_query(), -1);
        }
        if($this->db->trans_status() === false){
            throw new Exception("Hubo un problema al Eliminar Usuario, revisar los datos Ingresados: ". $this->db->last_query(), -1);
        }
    }
    
}
