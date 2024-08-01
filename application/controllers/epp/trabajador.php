<?php
require_once APPPATH.'controllers/BaseController.php';
class trabajador extends BaseController{
    public function __construct() {
	parent::__construct();	
        if($this->session->userdata('is_logued_in') != TRUE) {
        redirect('login');
        }
    }
    public function index(){
        $this->load->view('epp/vTrabajador');
    }

    public function getList(){
        $this->load->model('mapper/epp/mTrabajador','mprDatos');
        $contr = array();
        $contr['nombre'] = $this->input->post('nombre');        
        $contr['id'] = $this->input->post('id');        
        $dmnResponse = $this->mprDatos->finder($contr);
        foreach ($dmnResponse->getResults() as $dmn) {            
            $dmn->mapper()->getIdcargo();            
            $dmn->mapper()->getIdArea();            
            $dmn->mapper()->getIdProy();                                   
        }
        $data['data'] = $dmnResponse;
        $this->load->view('answer/answerTrabajador',$data);
    }
    
    public function getListPro(){
        $this->load->model('mapper/epp/mTrabajador','mprDatos');
        $contr = array();
        $contr['nombre'] = $this->input->post('nombre');        
        $contr['id'] = $this->input->post('id');        
        $dmnResponse = $this->mprDatos->finder($contr);
        foreach ($dmnResponse->getResults() as $dmn) {            
            $dmn->mapper()->getIdcargo();            
            $dmn->mapper()->getIdArea();            
            $dmn->mapper()->getIdProy();            
            if ($_GET['idPro']!=null || $_GET['idPro']!=''){
                
                if ($dmn->getIdproy()->getId() == $_GET['idPro']){
                    $dmn->setChecked('1');
                }
            }
            
        }
        $data['data'] = $dmnResponse;
        $this->load->view('answer/answerTrabajador',$data);
    }
    
    
    public function create(){
        $this->load->model('mapper/epp/mTrabajador','mprDatos');        
        $dmnDatos = new dmnTrabajador();
        try {
            $tipo=$this->input->post('tipo');            
            
            
                $dmnDatos->setIdcargo(new dmnTrabajador($this->input->post('cargo')));
                $dmnDatos->setIdarea(new dmnTrabajador($this->input->post('area')));
//                $dmnDatos->setIdproy(new dmnTrabajador($this->input->post('proy')));
                $dmnDatos->setNombre($this->input->post('nombre'));           
                $dmnDatos->setApellido($this->input->post('apellidos'));                           
                $dmnDatos->setDni($this->input->post('dni'));                                           
                
                if($tipo=="C"){
                    $this->mprDatos->create($dmnDatos);                    
                }else{
                    $dmnDatos->setId($this->input->post('id'));
                    $this->mprDatos->update($dmnDatos);                    
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
    
    public function deleteItem(){
        $this->load->model('mapper/proceso/mItem','mprItem');
        try {
            $dmnItem = new dmnItem($this->input->post('idItem'));
            $this->mprItem->delete($dmnItem);
            $data = array(
                'success' => true,
                'msg'=>'Registro eliminado correctamente'
            );
            echo json_encode($data);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }    
}



