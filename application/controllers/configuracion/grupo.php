<?php
require_once APPPATH.'controllers/BaseController.php';
class grupo extends BaseController{
    public function __construct() {
	parent::__construct();	
        if($this->session->userdata('is_logued_in') != TRUE) {
        redirect('login');
        }
    }
    function index(){
        $this->load->view('configuracion/vGrupo');
    }
    
    function getList(){
        $this->load->model('mapper/configuracion/mGrupo','mprGrupo');
        $contr = array();    
        $contr['id'] = $this->input->post('id');
        $dmnResponse = $this->mprGrupo->finder($contr);
        
        $data['data'] = $dmnResponse;
        $this->load->view('answer/answerGrupo',$data);            
    }
    
    public function create(){
        $this->load->model('mapper/configuracion/mGrupo','mprDatos');
        $dmnDatos = new dmnGrupo();
        try {
            $tipo=$this->input->post('tipo');            
            
                $dmnDatos->setNombre($this->input->post('nombre'));
                               
                
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
        $this->load->model('mapper/configuracion/mArea','mprDatos');
        try {
            $dmnDatos = new dmnGrupo($this->input->post('id'));
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


