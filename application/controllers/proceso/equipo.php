<?php
require_once APPPATH.'controllers/BaseController.php';
require_once DOMAIN_PATH.'proceso/dmnSolicitudEquipo.php';
class equipo extends BaseController{
    public function __construct() {
	parent::__construct();	
        if($this->session->userdata('is_logued_in') != TRUE) {
        redirect('login');
        }
    }
    public function index(){
        $this->load->view('proceso/vRegistroEquipo');
    }
    
    public function getList(){
        $this->load->model('mapper/proceso/mRegistroEquipo','mprEquipo');
        $contr = array();
        $contr['nombre'] = $this->input->post('nombre');        
        $contr['id'] = $this->input->post('id');
        $dmnResponse = $this->mprEquipo->finder($contr);
        foreach ($dmnResponse->getResults() as $dmnItem) {
            $dmnItem->mapper()->getEquipo();
        }
        
        $data['data'] = $dmnResponse;
        $this->load->view('answer/answerEquipo',$data);
    }
    
    public function create(){
        $this->load->model('mapper/proceso/mRegistroEquipo','mprEquipo');
        $dmnSolicitudEquipo = new dmnSolicitudEquipo();
        try {
            $tipo=$this->input->post('tipo');            
            
                $dmnSolicitudEquipo->setEquipo(new dmnEquipo($this->input->post('equipo')));
                $dmnSolicitudEquipo->setResponsable($this->input->post('respon'));
                $dmnSolicitudEquipo->setAsignado($this->input->post('asign'));
                $dmnSolicitudEquipo->setFecSolicitud($contr[''] = $this->input->post('fecSol'));
                $dmnSolicitudEquipo->setFecEntrega($this->input->post('fecEnt'));
                $dmnSolicitudEquipo->setFecregistro(date("Y-m-d"));                
                $dmnSolicitudEquipo->setFecmodificacion("");
                
                if($tipo=="C"){
                    $this->mprEquipo->create($dmnSolicitudEquipo);
                }else{
                    $dmnSolicitudEquipo->setId($this->input->post('id'));
                    $dmnSolicitudEquipo->setFecmodificacion(date("Y-m-d"));
                    $this->mprEquipo->update($dmnSolicitudEquipo);
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
        $this->load->model('mapper/proceso/mItem','mprItem');
        try {
            $dmnSolicitudEquipo = new dmnSolicitudEquipo($this->input->post('id'));
            $this->mprItem->delete($dmnSolicitudEquipo);
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