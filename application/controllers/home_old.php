<?php

require_once APPPATH.'controllers/BaseController.php';
class home extends BaseController{
    
    public function __construct() {
        parent::__construct();	
        if($this->session->userdata('is_logued_in') != TRUE) {
        redirect('login');
        }        
    }
    public function index(){
        $this->load->model('mapper/configuracion/mPerfil','mprPerfil');
        $dmnPerfil = $this->mprPerfil->find(array('id' => $this->session->userdata('perfil')));
        if ($dmnPerfil->getFlgAccesoMovil()== 1 ){
            $this->load->view('epp/vReporteEpp');
        }else{
            $this->load->view('vHome');        
        }
//        print_r($dmnPerfil);


    }
}