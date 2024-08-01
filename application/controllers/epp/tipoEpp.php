<?php
require_once APPPATH.'controllers/BaseController.php';
class tipoEpp extends BaseController{
    public function __construct() {
	parent::__construct();	
        if($this->session->userdata('is_logued_in') != TRUE) {
        redirect('login');
        }
    }
    public function index(){
        $this->load->view('epp/vTipoEpp');
    }
    
    public function getList(){
        $this->load->model('mapper/epp/mTipoEpp','mprDatos');
        $contr = array();    
        $contr['id'] = $this->input->post('id');
        $dmnResponse = $this->mprDatos->finder($contr);
        
        $data['data'] = $dmnResponse;
        $this->load->view('answer/answerTipoEpp',$data);            
    }
    
    public function create(){
        $this->load->model('mapper/epp/mTipoEpp','mprDatos');
        $dmnDatos = new dmnTipoEpp();
        try {
            $tipo=$this->input->post('tipo');            
            
                $dmnDatos->setNombre($this->input->post('nombre'));
                               
                
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
    
    public function delete(){
        $this->load->model('mapper/epp/mTipoEpp','mprDatos');
        try {
            $dmnDatos = new dmnTipoEpp($this->input->post('id'));
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

