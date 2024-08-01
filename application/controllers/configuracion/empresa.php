<?php
require_once APPPATH.'controllers/BaseController.php';
class empresa extends BaseController{
    public function __construct() {
	parent::__construct();	
        if($this->session->userdata('is_logued_in') != TRUE) {
        redirect('login');
        }
    }
    
    function index(){
        $this->load->view('configuracion/vEmpresa');
    }
    
    function getList(){
        $this->load->model('mapper/configuracion/mEmpresa','mprDatos');
        $contr = array();    
        $contr['id'] = $this->input->post('id');
        $dmnResponse = $this->mprDatos->finder($contr);
        
        $data['data'] = $dmnResponse;
        $this->load->view('answer/answerEmpresa',$data);            
    }
    
    public function create(){
        $this->load->model('mapper/configuracion/mEmpresa','mprDatos');
        $dmnDatos = new dmnEmpresa();
        try {
            $tipo=$this->input->post('tipo');            
            
                $dmnDatos->setId($this->input->post('id'));
                $dmnDatos->setNombre($this->input->post('nombre'));
                $dmnDatos->setRuc($this->input->post('ruc'));
                $dmnDatos->setRepresentante($this->input->post('represent'));               
                               
                
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
        $this->load->model('mapper/configuracion/mEmpresa','mprDatos');
        try {
            $dmnDatos = new dmnEmpresa($this->input->post('id'));
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
