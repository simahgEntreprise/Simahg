<?php
require_once APPPATH.'controllers/BaseController.php';
class riesgo extends BaseController{
    public function __construct() {
	parent::__construct();	
        if($this->session->userdata('is_logued_in') != TRUE) {
        redirect('login');
        }
    }
    public function index(){
        $this->load->view('epp/vRiesgo');
    }
    
    public function getList(){
        $this->load->model('mapper/epp/mRiesgo','mprRiesgo');
        $contr = array();    
        $contr['id'] = $this->input->post('id');
        $dmnResponse = $this->mprRiesgo->finder($contr);
        
        $data['data'] = $dmnResponse;
        $this->load->view('answer/answerRiesgo',$data);            
    }
    
    public function create(){
        $this->load->model('mapper/epp/mRiesgo','mprDatos');
        $dmnDatos = new dmnRiesgo();
        try {
            $tipo=$this->input->post('tipo');            
            
                $dmnDatos->setFactor($this->input->post('factor'));
                $dmnDatos->setDescripcion($this->input->post('descripcion'));
                               
                
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
        $this->load->model('mapper/epp/mRiesgo','mprDatos');
        try {
            $dmnDatos = new dmnArea($this->input->post('id'));
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