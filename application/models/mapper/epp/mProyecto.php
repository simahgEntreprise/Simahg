<?php
require_once APPPATH.'models/BaseModel.php';
require_once DOMAIN_PATH.'epp/dmnProyecto.php';
require_once DOMAIN_PATH.'epp/dmnTrabajador.php';
class mProyecto extends BaseModel{
    protected  $fields = 'id,nombre,responsable,idlider,estado';
    protected $table ='t_proyecto';
    public function  __construct() {
        parent::__construct();
    }
    public function find(array $constraints){
        $this->db->select($this->fields);
        if(isset($constraints['id'])){
            $this->db->where('id',$constraints['id']);        
        }  else {
            $this->session->set_flashdata('proyecto','Los datos introducidos son incorrectos');
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
        $dmnProyecto = new dmnProyecto($fields['id']);
        $dmnProyecto->setLider(new dmnTrabajador($fields['idlider']));                
        $dmnProyecto->setNombre($fields['nombre']);
        $dmnProyecto->setResponsable($fields['responsable']);        
        $dmnProyecto->setEstado($fields['estado']);
        
        return $dmnProyecto;
    }

    public function create(dmnProyecto $dmnProyecto){
        try{
            $this->db->trans_begin();
            $this->doCreate($dmnProyecto);
            $this->db->trans_commit();
        }catch(Exception $e){
            $this->db->trans_rollback();
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    private function doCreate(dmnProyecto $dmnProyecto){
        
        $fields['idlider'] = $dmnProyecto->getLider()->getId();        
        $fields['nombre'] = $dmnProyecto->getNombre();        
        $fields['responsable'] = $dmnProyecto->getResponsable();        
        $fields['estado'] = $dmnProyecto->getEstado();
        $this->db->set($fields);
        $this->db->insert($this->table);
        $dmnProyecto->setId($this->db->insert_id());
        if($this->db->trans_status() === false){
            throw new Exception("Hubo un problema al Registrar Area, revisar los datos Ingresados: ". $this->db->last_query(), -1);
        }
    }

    public function update(dmnProyecto $dmnProyecto){
        try{
            $this->db->trans_begin();
            $this->doUpdate($dmnProyecto);
            $this->db->trans_commit();
        }catch(Exception $e){
            $this->db->trans_rollback();
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    private function doUpdate(dmnProyecto $dmnProyecto){
        $fields['idlider'] = $dmnProyecto->getLider()->getId();        
        $fields['nombre'] = $dmnProyecto->getNombre();        
        $fields['responsable'] = $dmnProyecto->getResponsable();        
        $fields['estado'] = $dmnProyecto->getEstado();
        
        $this->db->set($fields);
        $this->db->where(array('ID' => $dmnProyecto->getId()));
        $this->db->update($this->table);

        if($this->db->trans_status() === false){
            throw new Exception("Hubo un problema al Modificar Usuario, revisar los datos Ingresados: ". $this->db->last_query(), -1);
        }
    }

    public function delete(dmnProyecto $dmnProyecto){
        try{
            $this->db->trans_begin();
            $this->doDelete($dmnProyecto);
            $this->db->trans_commit();
        }catch(Exception $e){
            $this->db->trans_rollback();
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    private function doDelete(dmnProyecto $dmnProyecto){
        $this->db->where(array('id' => $dmnProyecto->getId()));
        $this->db->delete($this->table);

        if($this->db->affected_rows()==0){
            throw new Exception("Hubo un problema al Eliminar area, revisar los datos Ingresados: ". $this->db->last_query(), -1);
        }
        if($this->db->trans_status() === false){
            throw new Exception("Hubo un problema al Eliminar Usuario, revisar los datos Ingresados: ". $this->db->last_query(), -1);
        }
    }
    
}
