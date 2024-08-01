<?php
require_once APPPATH.'models/BaseModel.php';
require_once DOMAIN_PATH.'configuracion/dmnCargo.php';
class mCargo extends BaseModel{
    protected  $fields = 'id,nombre';
    protected $table ='t_cargo';
    public function  __construct() {
        parent::__construct();
    }
    public function find(array $constraints){
        $this->db->select($this->fields);
        if(isset($constraints['id'])){
            $this->db->where('id',$constraints['id']);
        }elseif(isset ($constraints['nombre'])) {
            $this->db->where('nombre',$constraints['nombre']);            
        }  else {
            $this->session->set_flashdata('cargo_incorrecto','Los datos introducidos son incorrectos');
        }
        $res = $this->db->get($this->table);        
        return $this->createObject($res);
    }

    public function finder(array $constraints, $begin = 0, $end = 0){
        $this->load->database();
        $this->db->select($this->fields);

        if(isset($constraints['nombre']) && strlen(trim($constraints['nombre'])) > 0){
            $this->db->like('nombre',$constraints['nombre']);
        }
        
        $res = $this->db->get($this->table);
        if($res == null){
            throw new Exception('Error en MapperCArgo'.$this->db->last_query(),-1);
        }
        return $this->paginate($res);
    }

    public function doCreateObject(array $fields){
        $dmnPerfil = new dmnCargo($fields['id']);
        $dmnPerfil->setNombre($fields['nombre']);
        
        return $dmnPerfil;
    }

    public function create(dmnCargo $dmnCargo){
        try{
            $this->db->trans_begin();
            $this->doCreate($dmnCargo);
            $this->db->trans_commit();
        }catch(Exception $e){
            $this->db->trans_rollback();
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    private function doCreate(dmnCargo $dmnCargo){
        
//        $fields['id'] = $dmnPerfil->getId();
        $fields['nombre'] = $dmnCargo->getNombre();
        
        $this->db->set($fields);
        $this->db->insert($this->table);

        if($this->db->trans_status() === false){
            throw new Exception("Hubo un problema al Registrar perfil, revisar los datos Ingresados: ". $this->db->last_query(), -1);
        }
    }

    public function update(dmnCargo $dmnCargo){
        try{
            $this->db->trans_begin();
            $this->doUpdate($dmnCargo);
            $this->db->trans_commit();
        }catch(Exception $e){
            $this->db->trans_rollback();
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    private function doUpdate(dmnCargo $dmnCargo){

//        $fields['id'] = $dmnUsuario->getId();
        $fields['nombre'] = $dmnCargo->getNombre();
        

        $this->db->set($fields);
        $this->db->where(array('ID' => $dmnCargo->getId()));
        $this->db->update($this->table);

        if($this->db->trans_status() === false){
            throw new Exception("Hubo un problema al Modificar Cargo, revisar los datos Ingresados: ". $this->db->last_query(), -1);
        }
    }

    public function delete(dmnCargo $dmnCargo){
        try{
            $this->db->trans_begin();
            $this->doDelete($dmnCargo);
            $this->db->trans_commit();
        }catch(Exception $e){
            $this->db->trans_rollback();
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    private function doDelete(dmnCargo $dmnCargo){
        $this->db->where(array('id' => $dmnCargo->getId()));
        $this->db->delete($this->table);

        if($this->db->affected_rows()==0){
            throw new Exception("Hubo un problema al Eliminar cargo, revisar los datos Ingresados: ". $this->db->last_query(), -1);
        }
        if($this->db->trans_status() === false){
            throw new Exception("Hubo un problema al Eliminar cargo, revisar los datos Ingresados: ". $this->db->last_query(), -1);
        }
    }
}


