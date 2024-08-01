<?php
require_once APPPATH.'models/BaseModel.php';
require_once DOMAIN_PATH.'configuracion/dmnEmpresa.php';
class mEmpresa extends BaseModel{
    protected  $fields = 'id,nombre,ruc,representante';
    protected $table ='t_empresa';
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
            throw new Exception('Error en MapperEmpresa'.$this->db->last_query(),-1);
        }
        return $this->paginate($res);
    }

    public function doCreateObject(array $fields){
        $dmnEmpresa = new dmnEmpresa($fields['id']);
        $dmnEmpresa->setNombre($fields['nombre']);        
        $dmnEmpresa->setRuc($fields['ruc']);        
        $dmnEmpresa->setRepresentante($fields['representante']);        
        
        return $dmnEmpresa;
    }

    public function create(dmnEmpresa $dmnEmpresa){
        try{
            $this->db->trans_begin();
            $this->doCreate($dmnEmpresa);
            $this->db->trans_commit();
        }catch(Exception $e){
            $this->db->trans_rollback();
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    private function doCreate(dmnEmpresa $dmnEmpresa){
        
//        $fields['id'] = $dmnEmpresa->getId();
        $fields['nombre'] = $dmnEmpresa->getNombre();        
        $fields['ruc'] = $dmnEmpresa->getRuc();       
        $fields['representante'] = $dmnEmpresa->getRepresentante();       
        
        $this->db->set($fields);
        $this->db->insert($this->table);

        if($this->db->trans_status() === false){
            throw new Exception("Hubo un problema al Registrar empresa, revisar los datos Ingresados: ". $this->db->last_query(), -1);
        }
    }

    public function update(dmnEmpresa $dmnEmpresa){
        try{
            $this->db->trans_begin();
            $this->doUpdate($dmnEmpresa);
            $this->db->trans_commit();
        }catch(Exception $e){
            $this->db->trans_rollback();
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    private function doUpdate(dmnEmpresa $dmnEmpresa){

//        $fields['id'] = $dmnEmpresa->getId();
        $fields['nombre'] = $dmnEmpresa->getNombre();        
        $fields['ruc'] = $dmnEmpresa->getRuc();       
        $fields['representante'] = $dmnEmpresa->getRepresentante();       
        
        $this->db->set($fields);
        $this->db->where(array('ID' => $dmnEmpresa->getId()));
        $this->db->update($this->table);

        if($this->db->trans_status() === false){
            throw new Exception("Hubo un problema al Modificar empresa, revisar los datos Ingresados: ". $this->db->last_query(), -1);
        }
    }

    public function delete(dmnEmpresa $dmnEmpresa){
        try{
            $this->db->trans_begin();
            $this->doDelete($dmnEmpresa);
            $this->db->trans_commit();
        }catch(Exception $e){
            $this->db->trans_rollback();
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    private function doDelete(dmnEmpresa $dmnEmpresa){
        $this->db->where(array('id' => $dmnEmpresa->getId()));
        $this->db->delete($this->table);

        if($this->db->affected_rows()==0){
            throw new Exception("Hubo un problema al Eliminar empresa, revisar los datos Ingresados: ". $this->db->last_query(), -1);
        }
        if($this->db->trans_status() === false){
            throw new Exception("Hubo un problema al Eliminar empresa, revisar los datos Ingresados: ". $this->db->last_query(), -1);
        }
    }
    
}
