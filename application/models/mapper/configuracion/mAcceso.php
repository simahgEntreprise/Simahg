<?php
require_once APPPATH.'models/BaseModel.php';
class mAcceso extends BaseModel{
    protected  $fields = 't_acceso.id,nombre,idapp,idopc,ruta';
    protected $table ='t_acceso';
    public function  __construct() {
        parent::__construct();
    }
    public function find(array $constraints){
        $this->db->select($this->fields);
        if(isset($constraints['app']) && isset($constraints['opc']) ){
            $this->db->where('idapp',$constraints['app']);        
            $this->db->where('idopc',$constraints['opc']);        
        }elseif (isset ($constraints['id'])){
            $this->db->where('id',$constraints['id']);
        }else {
            $this->session->set_flashdata('usuario_incorrecto','Los datos introducidos son incorrectos');
        }
        $res = $this->db->get($this->table);        
        return $this->createObject($res);
    }
    public function doCreateObject(array $fields){
        $dmnAcceso = new dmnAcceso($fields['id']);
        $dmnAcceso->setNombre($fields['nombre']);
        $dmnAcceso->setIdApp($fields['idapp']);
        $dmnAcceso->setIdopc($fields['idopc']);
	$dmnAcceso->setRuta($fields['ruta']);        
        return $dmnAcceso;
    }
}
