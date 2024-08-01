<?php
require_once APPPATH.'models/BaseModel.php';
require_once DOMAIN_PATH.'configuracion/dmnEquipo.php';
class mEquipo extends BaseModel{
    protected  $fields = 'id,nombre, responsable';
    protected $table ='t_equipo';
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

        if(isset($constraints['nombre']) && strlen(trim($constraints['nombre'])) > 0){
            $this->db->like('nombre',$constraints['nombre']);
        }
        if(isset($constraints['id']) && strlen(trim($constraints['id'])) > 0){
            $this->db->like('id',$constraints['id']);
        }
        $res = $this->db->get($this->table);
        if($res == null){
            throw new Exception('Error en MapperEquipo'.$this->db->last_query(),-1);
        }
        return $this->paginate($res);
    }

    public function doCreateObject(array $fields){
        $dmnEquipo = new dmnEquipo($fields['id']);
        $dmnEquipo->setNombre($fields['nombre']);        
        $dmnEquipo->setResponsable($fields['responsable']);
        return $dmnEquipo;
    }

    public function create(dmnEquipo $dmnEquipo){
        try{
            $this->db->trans_begin();
            $this->doCreate($dmnEquipo);
            $this->db->trans_commit();
        }catch(Exception $e){
            $this->db->trans_rollback();
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    private function doCreate(dmnEquipo $dmnEquipo){
        
//        $fields['id'] = $dmnEquipo->getId();
        $fields['nombre'] = $dmnEquipo->getNombre();        
        $fields['responsable'] = $dmnEquipo->getResponsable();        
        
        $this->db->set($fields);
        $this->db->insert($this->table);

        if($this->db->trans_status() === false){
            throw new Exception("Hubo un problema al Registrar Area, revisar los datos Ingresados: ". $this->db->last_query(), -1);
        }
    }

    public function update(dmnEquipo $dmnEquipo){
        try{
            $this->db->trans_begin();
            $this->doUpdate($dmnEquipo);
            $this->db->trans_commit();
        }catch(Exception $e){
            $this->db->trans_rollback();
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    private function doUpdate(dmnEquipo $dmnEquipo){

//        $fields['id'] = $dmnEquipo->getId();
        $fields['nombre'] = $dmnEquipo->getNombre();
        $fields['responsable'] = $dmnEquipo->getResponsable();        
        
        $this->db->set($fields);
        $this->db->where(array('ID' => $dmnEquipo->getId()));
        $this->db->update($this->table);

        if($this->db->trans_status() === false){
            throw new Exception("Hubo un problema al Modificar Usuario, revisar los datos Ingresados: ". $this->db->last_query(), -1);
        }
    }

    public function delete(dmnEquipo $dmnEquipo){
        try{
            $this->db->trans_begin();
            $this->doDelete($dmnEquipo);
            $this->db->trans_commit();
        }catch(Exception $e){
            $this->db->trans_rollback();
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    private function doDelete(dmnEquipo $dmnEquipo){
        $this->db->where(array('id' => $dmnEquipo->getId()));
        $this->db->delete($this->table);

        if($this->db->affected_rows()==0){
            throw new Exception("Hubo un problema al Eliminar area, revisar los datos Ingresados: ". $this->db->last_query(), -1);
        }
        if($this->db->trans_status() === false){
            throw new Exception("Hubo un problema al Eliminar Usuario, revisar los datos Ingresados: ". $this->db->last_query(), -1);
        }
    }
    
}

