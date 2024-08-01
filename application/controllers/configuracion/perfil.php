<?php
require_once MAPPER_PATH.'configuracion/mAcceso.php';
require_once APPPATH.'controllers/BaseController.php';
class perfil extends BaseController{
    public function __construct() {
	parent::__construct();	
        if($this->session->userdata('is_logued_in') != TRUE) {
        redirect('login');
        }
    }
    public function index(){
        $this->load->view("configuracion/vPerfil");
    }
    public function getList(){
        $this->load->model('mapper/configuracion/mPerfil','mprPerfil');
        $contr = array();
        $contr['nombre'] = $this->input->post('nombre');        
        $contr['id'] = $this->input->post('id');
        $dmnResponse = $this->mprPerfil->finder($contr);
        $data['data'] = $dmnResponse;
        $this->load->view('answer/answerPerfil',$data);
    }
    
    public function create(){
        $this->load->model('mapper/configuracion/mPerfil','mprPerfil');
        $this->load->model('mapper/configuracion/mPerfilAcceso','mprPerfilAcceso');
        
        $dmnPerfil = new dmnPerfil();
        
        try {
            $tipo=$this->input->post('tipo');            
            
                $dmnPerfil->setnombre($this->input->post('nombre'));
                $dmnPerfil->setFlgAccesoMovil($this->input->post('flgAccMov'));
                
                if($tipo=="C"){
                    $dmnPerfil = $this->mprPerfil->create($dmnPerfil);                                                            
                    $this->__creaAccesos($this->db->insert_id());
                }else{                                      
                    $dmnPerfil->setId($this->input->post('id'));
                    $this->mprPerfil->update($dmnPerfil);                    
                    $this->mprPerfilAcceso->delete($dmnPerfil->getId());
                    $this->__creaAccesos($dmnPerfil->getId());
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
    
    public function __creaAccesos($idPerfil){
        try {            
            $dmnAcceso = new dmnPerfilAcceso();
            $dmnAcceso->setIdperfil($idPerfil);            
            if ($this->input->post('aperfil')=="true"){
                $mprAcceso = new mAcceso();
                $dmnAcc = $mprAcceso->find(array('app' =>"1", 'opc' => "2"));                
                $dmnAcceso->setIdAcceso($dmnAcc->getId());
                $this->mprPerfilAcceso->create($dmnAcceso);
            }
            if ($this->input->post('aarea') =="true"){
                $mprAcceso = new mAcceso();
                $dmnAcc = $mprAcceso->find(array('app' =>"1", 'opc' => "3"));
                $dmnAcceso->setIdAcceso($dmnAcc->getId());
                $this->mprPerfilAcceso->create($dmnAcceso);
            }
            if ($this->input->post('acargo')=="true"){
                $mprAcceso = new mAcceso();
                $dmnAcc = $mprAcceso->find(array('app' =>"1", 'opc' => "7"));
                $dmnAcceso->setIdAcceso($dmnAcc->getId());
                $this->mprPerfilAcceso->create($dmnAcceso);
            }
            if ($this->input->post('atipo')=="true"){
                $mprAcceso = new mAcceso();
                $dmnAcc = $mprAcceso->find(array('app' =>"1", 'opc' => "5"));
                $dmnAcceso->setIdAcceso($dmnAcc->getId());
                $this->mprPerfilAcceso->create($dmnAcceso);
            }
            if ($this->input->post('auser')=="true"){
                $mprAcceso = new mAcceso();
                $dmnAcc = $mprAcceso->find(array('app' =>"1", 'opc' => "1"));
                $dmnAcceso->setIdAcceso($dmnAcc->getId());
                $this->mprPerfilAcceso->create($dmnAcceso);
            }
            if ($this->input->post('agrup')=="true"){
                $mprAcceso = new mAcceso();
                $dmnAcc = $mprAcceso->find(array('app' =>"1", 'opc' => "8"));
                $dmnAcceso->setIdAcceso($dmnAcc->getId());
                $this->mprPerfilAcceso->create($dmnAcceso);
            }
            if ($this->input->post('atmant')=="true"){
                $mprAcceso = new mAcceso();
                $dmnAcc = $mprAcceso->find(array('app' =>"1", 'opc' => "4"));
                $dmnAcceso->setIdAcceso($dmnAcc->getId());
                $this->mprPerfilAcceso->create($dmnAcceso);
            }if ($this->input->post('aemp')=="true"){
                $mprAcceso = new mAcceso();
                $dmnAcc = $mprAcceso->find(array('app' =>"1", 'opc' => "6"));
                $dmnAcceso->setIdAcceso($dmnAcc->getId());
                $this->mprPerfilAcceso->create($dmnAcceso);
            }
            if ($this->input->post('aitem')=="true"){
                $mprAcceso = new mAcceso();
                $dmnAcc = $mprAcceso->find(array('app' =>"2", 'opc' => "1"));
                $dmnAcceso->setIdAcceso($dmnAcc->getId());
                $this->mprPerfilAcceso->create($dmnAcceso);
            }
            if ($this->input->post('amnt')=="true"){
                $mprAcceso = new mAcceso();
                $dmnAcc = $mprAcceso->find(array('app' =>"2", 'opc' => "2"));
                $dmnAcceso->setIdAcceso($dmnAcc->getId());
                $this->mprPerfilAcceso->create($dmnAcceso);
            }
            if ($this->input->post('arge')=="true"){
                $mprAcceso = new mAcceso();
                $dmnAcc = $mprAcceso->find(array('app' =>"2", 'opc' => "3"));
                $dmnAcceso->setIdAcceso($dmnAcc->getId());
                $this->mprPerfilAcceso->create($dmnAcceso);
            }            
            if ($this->input->post('ahoja')=="true"){
                $mprAcceso = new mAcceso();
                $dmnAcc = $mprAcceso->find(array('app' =>"3", 'opc' => "1"));
                $dmnAcceso->setIdAcceso($dmnAcc->getId());
                $this->mprPerfilAcceso->create($dmnAcceso);
            } 
            if ($this->input->post('ahoja')=="true"){
                $mprAcceso = new mAcceso();
                $dmnAcc = $mprAcceso->find(array('app' =>"3", 'opc' => "0"));
                $dmnAcceso->setIdAcceso($dmnAcc->getId());
                $this->mprPerfilAcceso->create($dmnAcceso);
            } 
            if ($this->input->post('aregepp') =='true'){
                $mprAcceso = new mAcceso();
                $dmnAcc = $mprAcceso->find(array('app' =>"4", 'opc' => "1"));
                $dmnAcceso->setIdAcceso($dmnAcc->getId());
                $this->mprPerfilAcceso->create($dmnAcceso);
            }
            
            if ($this->input->post('arepepp') =='true'){
                $mprAcceso = new mAcceso();
                $dmnAcc = $mprAcceso->find(array('app' =>"4", 'opc' => "2"));
                $dmnAcceso->setIdAcceso($dmnAcc->getId());
                $this->mprPerfilAcceso->create($dmnAcceso);
            }
            
            if ($this->input->post('aenlepp') =='true'){
                $mprAcceso = new mAcceso();
                $dmnAcc = $mprAcceso->find(array('app' =>"4", 'opc' => "3"));
                $dmnAcceso->setIdAcceso($dmnAcc->getId());
                $this->mprPerfilAcceso->create($dmnAcceso);
            }
            
            if ($this->input->post('aregtra') =='true'){
                $mprAcceso = new mAcceso();
                $dmnAcc = $mprAcceso->find(array('app' =>"4", 'opc' => "4"));
                $dmnAcceso->setIdAcceso($dmnAcc->getId());
                $this->mprPerfilAcceso->create($dmnAcceso);
            }

            if ($this->input->post('aregpro') =='true'){
                $mprAcceso = new mAcceso();
                $dmnAcc = $mprAcceso->find(array('app' =>"4", 'opc' => "5"));
                $dmnAcceso->setIdAcceso($dmnAcc->getId());
                $this->mprPerfilAcceso->create($dmnAcceso);
            }

            
            if ($this->input->post('aregepp') =='true' || $this->input->post('aregepp') =='true'){
                $mprAcceso = new mAcceso();
                $dmnAcc = $mprAcceso->find(array('app' =>"4", 'opc' => "0"));
                $dmnAcceso->setIdAcceso($dmnAcc->getId());
                $this->mprPerfilAcceso->create($dmnAcceso);
            }
                
            if ($this->input->post('aitem') =="true" || $this->input->post('amnt')=="true" || $this->input->post('arge')=="true" ){
                $mprAcceso = new mAcceso();
                $dmnAcc = $mprAcceso->find(array('app' =>"2", 'opc' => "0"));
                $dmnAcceso->setIdAcceso($dmnAcc->getId());
                $this->mprPerfilAcceso->create($dmnAcceso);
            }
            if ($this->input->post('aperfil')=="true" || $this->input->post('aarea')=="true" || 
                    $this->input->post('acargo')=="true" || $this->input->post('atipo')=="true" ||
                    $this->input->post('auser')=="true" || $this->input->post('atmant')=="true" ||
                    $this->input->post('aemp')=="true" || $this->input->post('agrup')=="true"){
                $mprAcceso = new mAcceso();
                $dmnAcc = $mprAcceso->find(array('app' =>"1", 'opc' => "0"));
                $dmnAcceso->setIdAcceso($dmnAcc->getId());
                $this->mprPerfilAcceso->create($dmnAcceso);
            }
            
            
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        }
    
    public function delete(){
        $this->load->model('mapper/configuracion/mPerfil','mprPerfil');
        $this->load->model('mapper/configuracion/mPerfilAcceso','mprPerfilAcceso');
        try {
            $dmnPerfil = new dmnPerfil($this->input->post('id'));            
            $this->mprPerfil->delete($dmnPerfil);
            $this->mprPerfilAcceso->delete($this->input->post('id'));
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
