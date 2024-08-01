<?php
require_once APPPATH.'controllers/BaseController.php';
class usuario extends BaseController{
    protected $arrLetras = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','Ñ','O','P','Q','R','S','T','U','V','W','X','Y','Z');
    protected $arrNumeros =array('A' => '1','B' => '2','C' =>'3','D' =>'4','E' =>'5','F' =>'6','G' =>'7','H' =>'8','J' =>'9');
    public function __construct() {
	parent::__construct();	
        if($this->session->userdata('is_logued_in') != TRUE) {
        redirect('login');
        }
    }
    public function index(){
        $this->load->view('configuracion/vUsuario');
    }
    
    public function getList(){
        $this->load->model('mapper/configuracion/mUsuario','mprUsuario');
        $contr = array();
        $contr['nombre'] = $this->input->post('nombre');        
        $contr['id'] = $this->input->post('id');
        $dmnResponse = $this->mprUsuario->finder($contr);
        foreach ($dmnResponse->getResults() as $dmnUsuario) {
            if ($dmnUsuario->getIdPerfil() != null){
//                $dmnUsuario->mapper()->getIdPerfil();
            }
        }
        $data['data'] = $dmnResponse;
        $this->load->view('answer/answerUsuario',$data);
    }
    
    public function create(){
        $this->load->model('mapper/configuracion/mUsuario','mprUsuario');
        $dmnUsuario = new dmnUsuario();        
        try {
            
            $tipo=$this->input->post('tipo');            
           
                $dmnUsuario->setUsuario($this->input->post('login'));
                $dmnUsuario->setContrasena($this->encriptar(strtoupper($this->input->post('pass'))));
                $dmnUsuario->setNombre($this->input->post('nombre'));
                $dmnUsuario->setApellido($this->input->post('ape'));
                $dmnUsuario->setIdperfil(new dmnPerfil($this->input->post('perfil')));
                $dmnUsuario->setIdCargo(new dmnCargo($this->input->post('cargo')));
                $dmnUsuario->setEstado($this->input->post('estado'));
                                
                
                if($tipo=="C"){
                 $dmnUsuario->setFecIngreso(date("Y-m-d"));   
                 $dmnUsuario->setFecModificacion("");   
                    $this->mprUsuario->create($dmnUsuario);
                }else{
                    $dmnUsuario->setId($this->input->post('id'));
                    $dmnUsuario->setFecmodificacion(date("Y-m-d"));
                    $this->mprUsuario->update($dmnUsuario);
                }
                
                $return = array(
                    'success' => true,
                    'msg'=>'Registro guardado correctamente'                
                );
           
            echo json_encode($return);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }        
    }
    
    public function delete(){
        $this->load->model('mapper/configuracion/mUsuario','mprUsuario');
        try {
            $dmnUsuario = new dmnUsuario($this->input->post('idItem'));
            $this->mprUsuario->delete($dmnUsuario);
            $data = array(
                'success' => true,
                'msg'=>'Registro eliminado correctamente'
            );
            echo json_encode($data);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    public function viewPass(){
        $tip = $this->input->post('tip');
        if ($tip=='S'){
            $val = $this->desencriptar($this->input->post('valor'));
        }else{
            $val = $this->encriptar($this->input->post('valor'));
        }
        
        $data = array(
                'success' => true,
                'msg'=>$val
            );
        echo json_encode($data);
    }
    
    public function encriptar($valor){
        $arrVal = str_split( $valor);
        $clave ='';
        $totClave ='';
        for ($i =0; $i<count($arrVal); $i++){
            if (in_array($arrVal[$i], $this->arrLetras)){
                $clave = array_search($arrVal[$i], $this->arrLetras);
            }elseif (in_array($arrVal[$i], $this->arrNumeros)) {
                $clave = array_search($arrVal[$i], $this->arrNumeros);
            }else{
                $clave = $arrVal[$i];
            }
            $totClave .= $clave.".";
        }
        $totClave = substr($totClave, 0, -1);                
        return $totClave;
    }
    
    public function desencriptar($valor){
        $arrVal = explode(".", strtoupper($valor)) ;
        
        
        $clave ='';
        $totClave ='';
        foreach ($arrVal as  $value) {           
            if (array_key_exists($value, $this->arrLetras)){
                $clave = $this->arrLetras[$value];
            }elseif (array_key_exists($value, $this->arrNumeros)){
                $clave = $this->arrNumeros[$value];
            }else{
                $clave = $value;
            }
            $totClave.=$clave;
        }                        
                    
        return $totClave;
    }
}