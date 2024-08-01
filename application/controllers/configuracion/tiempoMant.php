<?php
require_once APPPATH.'controllers/BaseController.php';
class tiempoMant extends BaseController{
    public function __construct() {
	parent::__construct();	
        if($this->session->userdata('is_logued_in') != TRUE) {
        redirect('login');
        }
    }
    function index(){
        $this->load->view('configuracion/vTiempoMantenimiento');
    }
    
    function getList(){
        $this->load->model('mapper/configuracion/mTiempoMant','mprTiempoMant');
        $contr = array();        
        $contr['id'] = $this->input->post('id');
        $dmnResponse = $this->mprTiempoMant->finder($contr);
        
        $data['data'] = $dmnResponse;
        $this->load->view('answer/answerTiempoMant',$data);            
    }      
    
    public function create(){
        $this->load->model('mapper/configuracion/mTiempoMant','mprDatos');
        $dmnDatos = new dmnTiempoMantenimiento($this->input->post('id'));
        try {
            $tipo=$this->input->post('tipo');            
            
                $dmnDatos->setNombre($this->input->post('nombre'));
                $dmnDatos->setTipo($this->input->post('tipMant'));
                               
                
                if($tipo=="C"){
                    $this->mprDatos->create($dmnDatos);
                }else{                  
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
    
    public function delete(){
        $this->load->model('mapper/configuracion/mTiempoMant','mprDatos');
        try {
            $dmnDatos = new dmnTiempoMantenimiento($this->input->post('id'));
            $this->mprDatos->delete($dmnDatos);
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