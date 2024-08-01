<?php
require_once APPPATH.'models/BaseModel.php';
require_once DOMAIN_PATH.'configuracion/dmnTipoMant.php';
class mTipoMant extends BaseModel{
    protected  $fields = 'id,nombre';
    protected $table ='t_tipoMant';
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
        if(isset($constraints['id'])){
            $this->db->where('id',$constraints['id']);        
        }
        if(isset($constraints['nombre']) && strlen(trim($constraints['nombre'])) > 0){
            $this->db->like('nombre',$constraints['nombre']);
        }
        
        $res = $this->db->get($this->table);
        if($res == null){
            throw new Exception('Error en MapperTipoMant'.$this->db->last_query(),-1);
        }
        return $this->paginate($res);
    }

    public function doCreateObject(array $fields){
        $dmnTipoMant = new dmnTipoMant($fields['id']);
        $dmnTipoMant->setNombre($fields['nombre']);        
        
        return $dmnTipoMant;
    }

    public function create(dmnTipoMant $dmnTipoMant){
        try{
            $this->db->trans_begin();
            $this->doCreate($dmnTipoMant);
            $this->db->trans_commit();
        }catch(Exception $e){
            $this->db->trans_rollback();
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    private function doCreate(dmnTipoMant $dmnTipoMant){
        
//        $fields['id'] = $dmnTipoMant->getId();
        $fields['nombre'] = $dmnTipoMant->getNombre();        
        
        $this->db->set($fields);
        $this->db->insert($this->table);

        if($this->db->trans_status() === false){
            throw new Exception("Hubo un problema al Registrar TipoMAnt, revisar los datos Ingresados: ". $this->db->last_query(), -1);
        }
    }

    public function update(dmnTipoMant $dmnTipoMant){
        try{
            $this->db->trans_begin();
            $this->doUpdate($dmnTipoMant);
            $this->db->trans_commit();
        }catch(Exception $e){
            $this->db->trans_rollback();
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    private function doUpdate(dmnTipoMant $dmnTipoMant){

//        $fields['id'] = $dmnTipoMant->getId();
        $fields['nombre'] = $dmnTipoMant->getNombre();
               
        $this->db->set($fields);
        $this->db->where(array('ID' => $dmnTipoMant->getId()));
        $this->db->update($this->table);

        if($this->db->trans_status() === false){
            throw new Exception("Hubo un problema al Modificar Usuario, revisar los datos Ingresados: ". $this->db->last_query(), -1);
        }
    }

    public function delete(dmnTipoMant $dmnTIpoMant){
        try{
            $this->db->trans_begin();
            $this->doDelete($dmnTIpoMant);
            $this->db->trans_commit();
        }catch(Exception $e){
            $this->db->trans_rollback();
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    private function doDelete(dmnTipoMant $dmnTIpoMant){
        $this->db->where(array('id' => $dmnTIpoMant->getId()));
        $this->db->delete($this->table);

        if($this->db->affected_rows()==0){
            throw new Exception("Hubo un problema al Eliminar area, revisar los datos Ingresados: ". $this->db->last_query(), -1);
        }
        if($this->db->trans_status() === false){
            throw new Exception("Hubo un problema al Eliminar Usuario, revisar los datos Ingresados: ". $this->db->last_query(), -1);
        }
    }
    
}

