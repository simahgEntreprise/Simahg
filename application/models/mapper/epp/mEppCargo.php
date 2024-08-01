<?php
require_once APPPATH.'models/BaseModel.php';
require_once DOMAIN_PATH.'epp/dmnEppCargo.php';

class mEppCargo extends BaseModel{
    protected  $fields = 'idCargo,idEpp';
    protected $table ='t_epp_cargo';
    public function  __construct() {
        parent::__construct();
    }
    public function find(array $constraints){
        $this->db->select($this->fields);
        if(isset($constraints['cargo'])){
            $this->db->where('idcargo',$constraints['cargo']);        
        }
        if (isset($constraints['epp'])) {        
            $this->db->where('idepp',$constraints['epp']);                
        }else {
            $this->session->set_flashdata('riesgo','Los datos introducidos son incorrectos');
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
        $dmnEpp = new dmnEppCargo();
        $dmnEpp->setIdcargo(new dmnCargo($fields['idcargo']));        
        $dmnEpp->setIdepp( new dmnEpp($fields['idepp']));                
        
        return $dmnEpp;
    }

    public function create(dmnEppCargo $dmnEpp){
        try{
            $this->db->trans_begin();
            $this->doCreate($dmnEpp);
            $this->db->trans_commit();
        }catch(Exception $e){
            $this->db->trans_rollback();
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    private function doCreate(dmnEppCargo $dmnEpp){
        
        $fields['idcargo'] = $dmnEpp->getIdcargo()->getId();
        $fields['idepp'] = $dmnEpp->getIdepp()->getId();                
        $this->db->set($fields);
        $this->db->insert($this->table);

        if($this->db->trans_status() === false){
            throw new Exception("Hubo un problema al Registrar Area, revisar los datos Ingresados: ". $this->db->last_query(), -1);
        }
    }

    public function update(dmnEppCargo $dmnEpp){
        try{
            $this->db->trans_begin();
            $this->doUpdate($dmnEpp);
            $this->db->trans_commit();
        }catch(Exception $e){
            $this->db->trans_rollback();
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    private function doUpdate(dmnEppCargo $dmnEpp){
        $fields['idcargo'] = $dmnEpp->getIdcargo()->getId();
        $fields['idepp'] = $dmnEpp->getIdepp()->getId();                
        
        $this->db->set($fields);
        $this->db->where(array('ID' => $dmnEpp->getId()));
        $this->db->update($this->table);

        if($this->db->trans_status() === false){
            throw new Exception("Hubo un problema al Modificar Usuario, revisar los datos Ingresados: ". $this->db->last_query(), -1);
        }
    }

    public function delete(dmnEppCargo $dmnEpp){
        try{
            $this->db->trans_begin();
            $this->doDelete($dmnEpp);
            $this->db->trans_commit();
        }catch(Exception $e){
            $this->db->trans_rollback();
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    private function doDelete(dmnEppCargo $dmnEpp){
        $this->db->where(array('idcargo' => $dmnEpp->getIdcargo()->getId()));
        $this->db->delete($this->table);

        
        if($this->db->trans_status() === false){
            throw new Exception("Hubo un problema al Eliminar Usuario, revisar los datos Ingresados: ". $this->db->last_query(), -1);
        }
    }
    
}
