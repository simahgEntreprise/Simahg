<?php
require_once APPPATH.'models/BaseModel.php';
require_once DOMAIN_PATH.'configuracion/dmnUsuario.php';
require_once DOMAIN_PATH.'configuracion/dmnPerfil.php';
require_once DOMAIN_PATH.'configuracion/dmnCargo.php';
class mUsuario extends BaseModel{
    protected  $fields = 'id,usuario,contrasena,idperfil,nombre,apellidos,idcargo,fecIngreso,fecModificacion,estado';
    protected $table ='t_usuario';
    public function  __construct() {
        parent::__construct();
    }
    public function find(array $constraints){
        $this->db->protect_identifiers('', FALSE);


        $this->db->select($this->fields);
        if(isset($constraints['id'])){
            $this->db->where('id',$constraints['id']);
        }elseif(isset ($constraints['log']) && isset ($constraints['pass']) ) {
            //$this->db->where('UPPER(usuario)',strtoupper($constraints['log']));
            $this->db->where("usuario", $constraints['log']);
            $this->db->where("contrasena", $constraints['pass']);

        }  else {
             // echo "Entró en el else<br>";
            $this->session->set_flashdata('usuario_incorrecto','Los datos introducidos son incorrectos');
        }
        //echo "Password recibido (normal): [" . $constraints['pass'] . "]<br>";
        //echo "Password recibido (HEX): [";
        /*for ($i = 0; $i < strlen($constraints['pass']); $i++) {
            echo dechex(ord($constraints['pass'][$i])) . " ";
        }*/
        //echo "]<br>";




        $res = $this->db->get($this->table);    
        
        //echo "Consulta SQL: ".$this->db->last_query()."<br>";
        ///echo "Filas encontradas: ".$res->num_rows()."<br>";

        return $this->createObject($res);
    }

    public function finder(array $constraints, $begin = 0, $end = 0){
        $this->load->database();
        $this->db->select($this->fields);
        if(isset($constraints['id'])){
            $this->db->where('id',$constraints['id']);
        }elseif(isset($constraints['nombre']) && strlen(trim($constraints['nombre'])) > 0){
            $this->db->like('nombre',$constraints['nombre']);
        }
        
        $res = $this->db->get($this->table);
        if($res == null){
            throw new Exception('Error en MapperUsuario '.$this->db->last_query(),-1);
        }
        return $this->paginate($res);
    }

    public function doCreateObject(array $fields){
        $dmnUsuario = new dmnUsuario($fields['id']);
        $dmnUsuario->setUsuario($fields['usuario']);
        $dmnUsuario->setContrasena($fields['contrasena']);
        $dmnUsuario->setIdperfil(new dmnPerfil($fields['idperfil']));
	$dmnUsuario->setNombre($fields['nombre']);	
        $dmnUsuario->setApellido($fields['apellidos']);	
        $dmnUsuario->setIdCargo(new dmnCargo($fields['idcargo']));	
        $dmnUsuario->setFecIngreso($fields['fecingreso']);
        $dmnUsuario->setFecModificacion($fields['fecmodificacion']);
        $dmnUsuario->setEstado($fields['estado']);
        return $dmnUsuario;
    }

    public function create(dmnUsuario $dmnUsuario){
        try{
            $this->db->trans_begin();
            $this->doCreate($dmnUsuario);
            $this->db->trans_commit();
        }catch(Exception $e){
            $this->db->trans_rollback();
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    private function doCreate(dmnUsuario $dmnUsuario){
        
//        $fields['id'] = $dmnUsuario->getId();
        $fields['usuario'] = $dmnUsuario->getUsuario();
        $fields['contrasena'] = $dmnUsuario->getContrasena();
        $fields['idperfil'] = $dmnUsuario->getIdperfil()->getId();
        $fields['nombre'] = $dmnUsuario->getNombre();
        $fields['apellidos'] = $dmnUsuario->getApellido();
        $fields['idcargo'] = $dmnUsuario->getIdCargo()->getId();
        $fields['fecIngreso'] = $dmnUsuario->getFecIngreso();
        $fields['fecModificacion'] = $dmnUsuario->getFecModificacion();
        $fields['estado'] = $dmnUsuario->getEstado();

        $this->db->set($fields);
        $this->db->insert($this->table);

        if($this->db->trans_status() === false){
            throw new Exception("Hubo un problema al Registrar Usuario, revisar los datos Ingresados: ". $this->db->last_query(), -1);
        }
    }

    public function update(dmnUsuario $dmnUsuario){
        try{
            $this->db->trans_begin();
            $this->doUpdate($dmnUsuario);
            $this->db->trans_commit();
        }catch(Exception $e){
            $this->db->trans_rollback();
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    private function doUpdate(dmnUsuario $dmnUsuario){

//        $fields['id'] = $dmnUsuario->getId();
        $fields['usuario'] = $dmnUsuario->getUsuario();
        $fields['contrasena'] = $dmnUsuario->getContrasena();
        $fields['idperfil'] = $dmnUsuario->getIdperfil()->getId();
        $fields['nombre'] = $dmnUsuario->getNombre();
        $fields['apellidos'] = $dmnUsuario->getApellido();
        $fields['idcargo'] = $dmnUsuario->getIdCargo()->getId();
//        $fields   ['fecIngreso'] = $dmnUsuario->getFecIngreso();
        $fields['fecModificacion'] = $dmnUsuario->getFecModificacion();
        $fields['estado'] = $dmnUsuario->getEstado();

        $this->db->set($fields);
        $this->db->where(array('ID' => $dmnUsuario->getId()));
        $this->db->update($this->table);

        if($this->db->trans_status() === false){
            throw new Exception("Hubo un problema al Modificar Usuario, revisar los datos Ingresados: ". $this->db->last_query(), -1);
        }
    }

    public function delete(dmnUsuario $dmnUsuario){
        try{
            $this->db->trans_begin();
            $this->doDelete($dmnUsuario);
            $this->db->trans_commit();
        }catch(Exception $e){
            $this->db->trans_rollback();
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    private function doDelete(dmnUsuario $dmnUsuario){
        $this->db->where(array('id' => $dmnUsuario->getId()));
        $this->db->delete($this->table);

        if($this->db->affected_rows()==0){
            throw new Exception("Hubo un problema al Eliminar Usuario, revisar los datos Ingresados: ". $this->db->last_query(), -1);
        }
        if($this->db->trans_status() === false){
            throw new Exception("Hubo un problema al Eliminar Usuario, revisar los datos Ingresados: ". $this->db->last_query(), -1);
        }
    }
}
