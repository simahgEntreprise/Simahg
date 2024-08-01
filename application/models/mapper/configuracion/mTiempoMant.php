<?php
require_once APPPATH.'models/BaseModel.php';
require_once DOMAIN_PATH.'configuracion/dmnTiempoMantenimiento.php';
class mTiempoMant extends BaseModel{
    protected  $fields = 'id,nombre, tipo';
    protected $table ='t_tiempoMant';
    public function  __construct() {
        parent::__construct();
    }
    public function find(array $constraints){
        $this->db->select($this->fields);
        if(isset($constraints['id'])){
            $this->db->where('id',$constraints['id']);        
        }  else {
            $this->session->set_flashdata('tiempo mant','Los datos introducidos son incorrectos');
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
        if(isset($constraints['id'])){
            $this->db->where('id',$constraints['id']);        
        }
        
        $res = $this->db->get($this->table);
        if($res == null){
            throw new Exception('Error en MapperTiempoMant'.$this->db->last_query(),-1);
        }
        return $this->paginate($res);
    }

    public function doCreateObject(array $fields){
        $dmnTiempoMant = new dmnTiempoMantenimiento($fields['id']);
        $dmnTiempoMant->setNombre($fields['nombre']);
        $dmnTiempoMant->setTipo($fields['tipo']);
        
        return $dmnTiempoMant;
    }

    public function create(dmnTiempoMantenimiento $dmnTiempoMant){
        try{
            $this->db->trans_begin();
            $this->doCreate($dmnTiempoMant);
            $this->db->trans_commit();
        }catch(Exception $e){
            $this->db->trans_rollback();
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    private function doCreate(dmnTiempoMantenimiento $dmnTiempoMant){
        
//        $fields['id'] = $dmnTiempoMant->getId();
        $fields['nombre'] = $dmnTiempoMant->getNombre();
        $fields['tipo'] = $dmnTiempoMant->getTipo();
        
        $this->db->set($fields);
        $this->db->insert($this->table);

        if($this->db->trans_status() === false){
            throw new Exception("Hubo un problema al Registrar Usuario, revisar los datos Ingresados: ". $this->db->last_query(), -1);
        }
    }

    public function update(dmnTiempoMantenimiento $dmnUsuario){
        try{
            $this->db->trans_begin();
            $this->doUpdate($dmnUsuario);
            $this->db->trans_commit();
        }catch(Exception $e){
            $this->db->trans_rollback();
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    private function doUpdate(dmnTiempoMantenimiento $dmnTiempoMant){

//        $fields['id'] = $dmnTiempoMant->getId();
        $fields['nombre'] = $dmnTiempoMant->getNombre();
        $fields['tipo'] = $dmnTiempoMant->getTipo();
        
        $this->db->set($fields);
        $this->db->where(array('ID' => $dmnTiempoMant->getId()));
        $this->db->update($this->table);

        if($this->db->trans_status() === false){
            throw new Exception("Hubo un problema al Modificar Usuario, revisar los datos Ingresados: ". $this->db->last_query(), -1);
        }
    }

    public function delete(dmnTiempoMantenimiento $dmnTiempoMant){
        try{
            $this->db->trans_begin();
            $this->doDelete($dmnTiempoMant);
            $this->db->trans_commit();
        }catch(Exception $e){
            $this->db->trans_rollback();
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    private function doDelete(dmnTiempoMantenimiento $dmnTiempoMant){
        $this->db->where(array('id' => $dmnTiempoMant->getId()));
        $this->db->delete($this->table);

        if($this->db->affected_rows()==0){
            throw new Exception("Hubo un problema al Eliminar Usuario, revisar los datos Ingresados: ". $this->db->last_query(), -1);
        }
        if($this->db->trans_status() === false){
            throw new Exception("Hubo un problema al Eliminar Usuario, revisar los datos Ingresados: ". $this->db->last_query(), -1);
        }
    }
    
}