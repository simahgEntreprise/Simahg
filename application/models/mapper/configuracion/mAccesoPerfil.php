<?php
require_once APPPATH.'models/BaseModel.php';
require_once DOMAIN_PATH.'configuracion/dmnAcceso.php';
require_once DOMAIN_PATH.'configuracion/dmnPerfil.php';
require_once DOMAIN_PATH.'configuracion/dmnPerfilAcceso.php';
require_once MAPPER_PATH.'configuracion/mAcceso.php';
class mAccesoPerfil extends mAcceso{
    public function  __construct() {
        parent::__construct();
    }
    
    public function finder (array $constraints){
        $this->load->database();
        $this->db->select($this->fields);
        $this->db->join("t_perfil_acceso","t_acceso.id = t_perfil_acceso.idacceso ");
        if(isset($constraints['idPerfil']) && strlen(trim($constraints['idPerfil'])) > 0){
            $this->db->where('t_perfil_acceso.idperfil',$constraints['idPerfil']);
        }        
        $res = $this->db->get($this->table);                
        if($res == null){
            throw new Exception('Error en MapperPerfilAcceso '.$this->db->last_query(),-1);
        }
        return $this->paginate($res);
    }
    
//    public function doCreateObject(array $fields){
//        $dmnPrfAcc = new dmnPerfilAcceso($fields['id']);
//        $dmnPrfAcc->setIdperfil(new dmnPerfil($fields['idperfil']));
//        $dmnPrfAcc->setIdAcceso(new dmnAcceso($fields['idacceso']));        
//        return $dmnPrfAcc;
//    }
    
    
}
