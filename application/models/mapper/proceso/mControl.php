<?php
class mControl extends BaseModel{
    protected $fields ="A.ID, B.TIPO, A.CODIGO,A.nombre,A.numserie,A.responsable,A.fecIngreso,A.fecUsoItem";
    protected $table = "t_item";
    public function finder($contr){
        $this->load->database();
        $this->db->select($this->fields);
        $this->db->join('t_tiempoMant B','A.idtiempoMant = B.id');                
        $res = $this->db->get($this->table);

        if($res == null){
            throw new Exception('Error en mControl '.$this->db->last_query(),-1);
        }
        return $this->paginate($res);
    }
    
    public function doCreateObject(array $fields){
        $dmnItem = new dmnControl($fields['id']);
        $dmnItem->setCodigo($fields['codigo']);
        $dmnItem->setNombre($fields['nombre']);
        $dmnItem->setNumserie($fields['numserie']);
        $dmnItem->setImagen($fields['imagen']);
        $dmnItem->setIdarea(new dmnArea($fields['idarea']));
        $dmnItem->setResponsable($fields['responsable']);
        $dmnItem->setFecIngreso($fields['fecingreso']);
        $dmnItem->setFecUsoItem($fields['fecusoitem']);
        $dmnItem->setIdtiempoMant($fields['idtiempomant']);
        $dmnItem->setFecregistro($fields['fecregistro']);
        $dmnItem->setFecmodificacion($fields['fecmodificacion']);
        return $dmnItem;
    }
    
}