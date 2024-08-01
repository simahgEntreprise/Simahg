<?php
require_once APPPATH.'controllers/BaseController.php';
class tipoMant extends BaseController{
    public function __construct() {
	parent::__construct();	
        if($this->session->userdata('is_logued_in') != TRUE) {
        redirect('login');
        }
    }
    function index(){
        $this->load->view('configuracion/vTipoMantenimiento');
    }
    
    function getList(){
        $this->load->model('mapper/configuracion/mTipoMant','mprTipoMant');
        $contr = array();        
        $contr['id'] = $this->input->post('id');
        $dmnResponse = $this->mprTipoMant->finder($contr);
        
        $data['data'] = $dmnResponse;
        $this->load->view('answer/answerTipoMant',$data);            
    }
    
    public function create(){
        $this->load->model('mapper/configuracion/mTipoMant','mprDatos');
        $dmnDatos = new dmnTipoMant();
        try {
            $tipo=$this->input->post('tipo');            
            
                $dmnDatos->setNombre($this->input->post('nombre'));
                $dmnDatos->setId($this->input->post('id'));
                               
                
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
        $this->load->model('mapper/configuracion/mTipoMant','mprDatos');
        try {
            $dmnDatos = new dmnTipoMant($this->input->post('id'));
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

