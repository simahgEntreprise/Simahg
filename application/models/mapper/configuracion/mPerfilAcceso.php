<?php
require_once APPPATH.'models/BaseModel.php';
require_once DOMAIN_PATH.'configuracion/dmnAcceso.php';
require_once DOMAIN_PATH.'configuracion/dmnPerfil.php';
require_once DOMAIN_PATH.'configuracion/dmnPerfilAcceso.php';
class mPerfilAcceso extends BaseModel{
    protected  $fields = 't_perfil_acceso.id,idperfil,idacceso';
    protected $table ='t_perfil_acceso';
    public function  __construct() {
        parent::__construct();
    }
    
    public function finder (array $constraints){
        $this->load->database();
        $this->db->select($this->fields);
        $this->db->join("t_acceso","t_perfil_acceso.idacceso= t_acceso.id");
        if(isset($constraints['idPerfil']) && strlen(trim($constraints['idPerfil'])) > 0){
            $this->db->where('idperfil',$constraints['idPerfil']);
        }        
        $this->db->order_by("t_acceso.idapp,t_acceso.idopc","ASC");
        $res = $this->db->get($this->table);
        return $res->result_array();
        
        if($res == null){
            throw new Exception('Error en MapperPerfilAcceso '.$this->db->last_query(),-1);
        }
        
    }
    
    public function doCreateObject(array $fields){
        $dmnPrfAcc = new dmnPerfilAcceso($fields['id']);
        $dmnPrfAcc->setIdperfil(new dmnPerfil($fields['idperfil']));
        $dmnPrfAcc->setIdAcceso(new dmnAcceso($fields['idacceso']));        
        return $dmnPrfAcc;
    }
    public function create(dmnPerfilAcceso $dmnAcceso){
        try{
            $this->db->trans_begin();
            $this->doCreate($dmnAcceso);
            $this->db->trans_commit();
        }catch(Exception $e){
            $this->db->trans_rollback();
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    private function doCreate(dmnPerfilAcceso $dmnAcceso){
        
//        $fields['id'] = $dmnArea->getId();
        $fields['idperfil'] = $dmnAcceso->getIdperfil();        
        $fields['idacceso'] = $dmnAcceso->getIdAcceso();
        
        $this->db->set($fields);
        $this->db->insert($this->table);

        if($this->db->trans_status() === false){
            throw new Exception("Hubo un problema al Registrar perfil acceso, revisar los datos Ingresados: ". $this->db->last_query(), -1);
        }
    }
    public function delete ($idPerfil){
        try{
            $this->db->trans_begin();
            $this->load->database();
            $this->db->where(array('idperfil' => $idPerfil));
            $this->db->delete($this->table);
            $this->db->trans_commit();
        }catch(Exception $e){
            $this->db->trans_rollback();
            throw new Exception($e->getMessage(), $e->getCode());
        }
        
    }
}
