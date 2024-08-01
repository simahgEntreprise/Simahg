<?php
require_once APPPATH.'controllers/BaseController.php';
class cargo extends BaseController{
    public function __construct() {
	parent::__construct();	
        if($this->session->userdata('is_logued_in') != TRUE) {
        redirect('login');
        }
    }
    public function index(){
        $this->load->view('configuracion/vCargo');
    }

    public function getList(){
        $this->load->model('mapper/configuracion/mCargo','mprCargo');
        $contr = array();
        $contr['id'] = $this->input->post('id');        
        $dmnResponse = $this->mprCargo->finder($contr);
        
        $data['data'] = $dmnResponse;
        $this->load->view('answer/answerCargo',$data);
    }
    
    public function create(){
        $this->load->model('mapper/configuracion/mCargo','mprCargo');
        $dmnCargo = new dmnCargo();
        try {
            $tipo=$this->input->post('tipo');            
            $succ=true;
            
            
                $dmnCargo->setnombre($this->input->post('nombre'));
                
                if($tipo=="C"){
                    $this->mprCargo->create($dmnCargo);
                }else{                                        
                    $this->mprCargo->update($dmnCargo);
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
        $this->load->model('mapper/configuracion/mCargo','mprCargo');
        try {
            $dmnItem = new dmnCargo($this->input->post('id'));
            $this->mprCargo->delete($dmnItem);
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
