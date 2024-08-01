<?php
require_once APPPATH.'models/BaseModel.php';
require_once DOMAIN_PATH.'epp/dmnEpp.php';
require_once DOMAIN_PATH.'epp/dmnRiesgo.php';
require_once DOMAIN_PATH.'epp/dmnTipoEpp.php';
class mEpp extends BaseModel{
    protected  $fields = 'id,idRiesgo,tipo,uso,imagen,vidautil,especificaciones,norma,tiempovidautil,estado';
    protected $table ='t_epp';
    public function  __construct() {
        parent::__construct();
    }
    public function find(array $constraints){
        $this->db->select($this->fields);
        if(isset($constraints['id'])){
            $this->db->where('id',$constraints['id']);        
        }  else {
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
        $dmnEpp = new dmnEpp($fields['id']);
        $dmnEpp->setRiesgo(new dmnRiesgo($fields['idriesgo']));        
        $dmnEpp->setTipo( new dmnTipoEpp($fields['tipo']));        
        $dmnEpp->setUso($fields['uso']);
        $dmnEpp->setImagen($fields['imagen']);
        $dmnEpp->setNorma($fields['norma']);
        $dmnEpp->setEspecificacion($fields['especificaciones']);
        $dmnEpp->setVidaUtil($fields['vidautil']);
        $dmnEpp->setTiempoVidaUtil($fields['tiempovidautil']);
        $dmnEpp->setEstado($fields['estado']);
        
        return $dmnEpp;
    }

    public function create(dmnEpp $dmnEpp){
        try{
            $this->db->trans_begin();
            $this->doCreate($dmnEpp);
            $this->db->trans_commit();
        }catch(Exception $e){
            $this->db->trans_rollback();
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    private function doCreate(dmnEpp $dmnEpp){
        
        $fields['idriesgo'] = $dmnEpp->getRiesgo()->getId();
        $fields['tipo'] = $dmnEpp->getTipo()->getId();        
        $fields['uso'] = $dmnEpp->getUso();        
        $fields['imagen'] = $dmnEpp->getImagen();
        $fields['vidautil'] = $dmnEpp->getVidaUtil();        
        $fields['especificaciones'] = $dmnEpp->getEspecificacion();
        $fields['norma'] = $dmnEpp->getNorma();
        $fields['tiempovidautil'] = $dmnEpp->getTiempoVidaUtil();
        $fields['estado'] = $dmnEpp->getEstado();
        $this->db->set($fields);
        $this->db->insert($this->table);

        if($this->db->trans_status() === false){
            throw new Exception("Hubo un problema al Registrar Area, revisar los datos Ingresados: ". $this->db->last_query(), -1);
        }
    }

    public function update(dmnEpp $dmnEpp){
        try{
            $this->db->trans_begin();
            $this->doUpdate($dmnEpp);
            $this->db->trans_commit();
        }catch(Exception $e){
            $this->db->trans_rollback();
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    private function doUpdate(dmnEpp $dmnEpp){
        $fields['idriesgo'] = $dmnEpp->getRiesgo()->getId();
        $fields['tipo'] = $dmnEpp->getTipo()->getId();        
        $fields['uso'] = $dmnEpp->getUso();        
        $fields['imagen'] = $dmnEpp->getImagen();
        $fields['vidautil'] = $dmnEpp->getVidaUtil();        
        $fields['especificaciones'] = $dmnEpp->getEspecificacion();
        $fields['norma'] = $dmnEpp->getNorma();
        $fields['tiempovidautil'] = $dmnEpp->getTiempoVidaUtil();
        $fields['estado'] = $dmnEpp->getEstado();
        
        $this->db->set($fields);
        $this->db->where(array('ID' => $dmnEpp->getId()));
        $this->db->update($this->table);

        if($this->db->trans_status() === false){
            throw new Exception("Hubo un problema al Modificar Usuario, revisar los datos Ingresados: ". $this->db->last_query(), -1);
        }
    }

    public function delete(dmnEpp $dmnEpp){
        try{
            $this->db->trans_begin();
            $this->doDelete($dmnEpp);
            $this->db->trans_commit();
        }catch(Exception $e){
            $this->db->trans_rollback();
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    private function doDelete(dmnEpp $dmnEpp){
        $this->db->where(array('id' => $dmnEpp->getId()));
        $this->db->delete($this->table);

        if($this->db->affected_rows()==0){
            throw new Exception("Hubo un problema al Eliminar area, revisar los datos Ingresados: ". $this->db->last_query(), -1);
        }
        if($this->db->trans_status() === false){
            throw new Exception("Hubo un problema al Eliminar Usuario, revisar los datos Ingresados: ". $this->db->last_query(), -1);
        }
    }
    
}
