<?php
require_once APPPATH.'controllers/BaseController.php';
class perfilAcceso extends BaseController{
    public function __construct() {
	parent::__construct();	
        if($this->session->userdata('is_logued_in') != TRUE) {
        redirect('login');
        }
    }
    public function getList(){
        $this->load->model('mapper/configuracion/mAccesoPerfil','mprAccesoPerfil');
        $contr = array();    
        $contr['idPerfil'] = $this->input->post('idPerfil');        
        $dmnResponse = $this->mprAccesoPerfil->finder($contr);        
        $data['data'] = $dmnResponse;
        $this->load->view('answer/answerAccesoPerfil',$data);            
    }
}