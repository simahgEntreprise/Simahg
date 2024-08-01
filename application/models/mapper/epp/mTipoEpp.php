<?php
require_once APPPATH.'models/BaseModel.php';
require_once DOMAIN_PATH.'epp/dmnTipoEpp.php';
class mTipoEpp extends BaseModel{
    protected  $fields = 'id,nombre';
    protected $table ='t_tipoepp';
    public function  __construct() {
        parent::__construct();
    }
    public function find(array $constraints){
        $this->db->select($this->fields);
        if(isset($constraints['id'])){
            $this->db->where('id',$constraints['id']);        
        }  else {
            $this->session->set_flashdata('tipoepp','Los datos introducidos son incorrectos');
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
            throw new Exception('Error en MapperTipoEpp'.$this->db->last_query(),-1);
        }
        return $this->paginate($res);
    }

    public function doCreateObject(array $fields){
        $dmnTipoEpp = new dmnTipoEpp($fields['id']);          
        $dmnTipoEpp->setNombre($fields['nombre']);                
        return $dmnTipoEpp;
    }

    public function create(dmnTipoEpp $dmnTipoEpp){
        try{
            $this->db->trans_begin();
            $this->doCreate($dmnTipoEpp);
            $this->db->trans_commit();
        }catch(Exception $e){
            $this->db->trans_rollback();
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    private function doCreate(dmnTipoEpp $dmnTipoEpp){
       
        $fields['nombre'] = $dmnTipoEpp->getNombre();        
        
        $this->db->set($fields);
        $this->db->insert($this->table);

        if($this->db->trans_status() === false){
            throw new Exception("Hubo un problema al Registrar Area, revisar los datos Ingresados: ". $this->db->last_query(), -1);
        }
    }

    public function update(dmnTipoEpp $dmnTipoEpp){
        try{
            $this->db->trans_begin();
            $this->doUpdate($dmnTipoEpp);
            $this->db->trans_commit();
        }catch(Exception $e){
            $this->db->trans_rollback();
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    private function doUpdate(dmnTipoEpp $dmnTipoEpp){        
        $fields['nombre'] = $dmnTipoEpp->getNombre();
               
        $this->db->set($fields);
        $this->db->where(array('ID' => $dmnTipoEpp->getId()));
        $this->db->update($this->table);

        if($this->db->trans_status() === false){
            throw new Exception("Hubo un problema al Modificar Usuario, revisar los datos Ingresados: ". $this->db->last_query(), -1);
        }
    }

    public function delete(dmnTipoEpp $dmnTipoEpp){
        try{
            $this->db->trans_begin();
            $this->doDelete($dmnTipoEpp);
            $this->db->trans_commit();
        }catch(Exception $e){
            $this->db->trans_rollback();
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    private function doDelete(dmnTipoEpp $dmnTipoEpp){
        $this->db->where(array('id' => $dmnTipoEpp->getId()));
        $this->db->delete($this->table);

        if($this->db->affected_rows()==0){
            throw new Exception("Hubo un problema al Eliminar area, revisar los datos Ingresados: ". $this->db->last_query(), -1);
        }
        if($this->db->trans_status() === false){
            throw new Exception("Hubo un problema al Eliminar Usuario, revisar los datos Ingresados: ". $this->db->last_query(), -1);
        }
    }
    
}
