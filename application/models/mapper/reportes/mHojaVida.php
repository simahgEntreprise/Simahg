<?php
require_once APPPATH.'models/BaseModel.php';
require_once DOMAIN_PATH.'reportes/dmnHojaVida.php';
class mHojaVida extends BaseModel{
    protected  $fields = "t_mantenimiento.id,t_mantenimiento.fecIngreso,t_tipoMant.nombre,t_mantenimiento.observacion,t_mantenimiento.responsable, t_mantenimiento.idtipo,t_mantenimiento.lugar";
    public function  __construct() {
        parent::__construct();
    }

    public function finder(array $constraints){
        $this->load->database();
        $this->db->select($this->fields);
        $this->db->join('t_item ','t_mantenimiento.idItem = t_item.id');
        $this->db->join('t_tipoMant ','t_mantenimiento.idtipo = t_tipoMant.id');
                       
        if(isset($constraints['id']) && strlen(trim($constraints['id'])) > 0){
            $this->db->where('t_item.id',(int)$constraints['id']);
        }        
        $this->db->order_by('t_mantenimiento.fecingreso','DSC');
        $res = $this->db->get('t_mantenimiento');
//        print_r($this->db->last_query());exit;
        if($res == null){
            throw new Exception('Error en MapperItem'.$this->db->last_query(),-1);
        }
        return $this->paginate($res);
    }

    public function doCreateObject(array $fields){        
        $dmnHojaVida = new dmnHojaVida($fields['id']);
        $dmnHojaVida->setFecha($fields['fecingreso']);
        $dmnHojaVida->setEquipo($fields['nombre']);
        $dmnHojaVida->setObservacion($fields['observacion']);
        $dmnHojaVida->setResponsable($fields['responsable']);
        $dmnHojaVida->setLugar($fields['lugar']);
        $dmnHojaVida->setTipo(new dmnTipoMant($fields['idtipo']));
        return $dmnHojaVida;
    }
    
}
