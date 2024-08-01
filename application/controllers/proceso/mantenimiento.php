<?php
require_once APPPATH.'controllers/BaseController.php';
require_once DOMAIN_PATH.'proceso/dmnItem.php';
require_once DOMAIN_PATH.'configuracion/dmnEmpresa.php';
require_once DOMAIN_PATH.'configuracion/dmnTipoMant.php';
require_once MAPPER_PATH.'proceso/mItem.php';
class mantenimiento extends BaseController{
    public function __construct() {
	parent::__construct();	
        if($this->session->userdata('is_logued_in') != TRUE) {
        redirect('login');
        }
    }
    public function index(){
        $this->load->view('proceso/vRegistroMantenimiento');
    }
    
    function getList(){
        $this->load->model('mapper/proceso/mMantenimiento','mprMante');
        $contr = array();    
        $contr['id'] = $this->input->post('id');
        $dmnResponse = $this->mprMante->finder($contr);
        foreach ($dmnResponse->getResults() as $dmnMant) {
            $dmnMant->mapper()->getIdItem();
            $dmnMant->mapper()->getIdTipo();
        }
        
        $data['data'] = $dmnResponse;
        $this->load->view('answer/answerMantenimiento',$data);            
    }
    
    public function create(){
        $this->load->model('mapper/proceso/mMantenimiento','mprDatos');
        $dmnDatos = new dmnMantenimiento();
        try {
            $tipo=$this->input->post('tipo');            
            
                $dmnDatos->setIdItem(new dmnItem($this->input->post('idItem')));
                $dmnDatos->setEstado($this->input->post('estado'));
                $dmnDatos->setFecIngreso($this->input->post('fecIng'));
                $dmnDatos->setFecSalida($this->input->post('fecSal'));
                $dmnDatos->setFecmodificacion("");
                $dmnDatos->setFecregistro(date("Y-m-d"));
                $dmnDatos->setHorastrab($this->input->post('horas'));
                $dmnDatos->setId($this->input->post('id'));
                $dmnDatos->setIdEmpresa(new dmnEmpresa(($this->input->post('idEmp') == '0' ? null : $this->input->post('idEmp'))));
                $dmnDatos->setIdtipo(new dmnTipoMant($this->input->post('idTipo')));
                $dmnDatos->setObservacion($this->input->post('observacion'));
                $dmnDatos->setResponsable($this->input->post('responsable'));                
                $dmnDatos->setLugar($this->input->post('lugar'));                                                              
                if($tipo=="C"){
                    $this->mprDatos->create($dmnDatos);
                }else{                    
                    $dmnDatos->setFecmodificacion(date("Y-m-d"));
                    $this->mprDatos->update($dmnDatos);
                }
                
                $this->updateItemDateMant($dmnDatos);
                $return = array(
                    'success' => true,
                    'msg'=>'Registro guardado correctamente'                
                );           
            echo json_encode($return);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }        
    }
    
    private function updateITemDateMant(dmnMantenimiento $dmn){
        If ($dmn->getEstado() == "T"){            
            $mprItem = new mItem();            
            $dmnItem = $mprItem->find(array('id' => $dmn->getIdItem()->getId()));
            $fec = date_create($dmn->getFecSalida());
            $dmnItem->setFecUsoItem($fec->format('Y-m-d\TH:i:s'));            
            $this->load->model('configuracion/mItem','mprItem');
            $this->mprItem->update($dmnItem);
        }
    }
    public function delete(){
        $this->load->model('mapper/proceso/mMantenimiento','mprDatos');
        try {
            $dmnDatos = new dmnMantenimiento($this->input->post('id'));
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

