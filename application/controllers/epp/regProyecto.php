<?php
require_once APPPATH.'controllers/BaseController.php';
class regProyecto extends BaseController{
    public function __construct() {
	parent::__construct();	
        if($this->session->userdata('is_logued_in') != TRUE) {
        redirect('login');
        }
    }
    public function index(){
        $this->load->view('epp/vRegProyecto');
    }

    public function getList(){
        $this->load->model('mapper/epp/mProyecto','mprDatos');
        $contr = array();
        $contr['nombre'] = $this->input->post('nombre');        
        $contr['id'] = $this->input->post('id');
        $dmnResponse = $this->mprDatos->finder($contr);
        foreach ($dmnResponse->getResults() as $dmnEpp) {
            $dmnEpp->mapper()->getLider();            
        }
        $data['data'] = $dmnResponse;
        $this->load->view('answer/answerProyecto',$data);
    }
    
    public function create(){
        $this->load->model('mapper/epp/mProyecto','mprDatos');        
        $this->load->model('mapper/epp/mtrabajador','mprTrab');        
        $dmnDatos = new dmnProyecto();
        try {
            $tipo=$this->input->post('tipo');            
            
            
                $dmnDatos->setLider(new dmnTrabajador($this->input->post('lider')));
                $dmnDatos->setNombre($this->input->post('nombre'));           
                $dmnDatos->setResponsable($this->input->post('resp'));                           
                $dmnDatos->setEstado($this->input->post('est'));
                $arrData = json_decode($this->input->post('trab'));
                
                if($tipo=="C"){
                    $this->mprDatos->create($dmnDatos);                                        
                }else{
                    $dmnDatos->setId($this->input->post('id'));
                    $this->mprDatos->update($dmnDatos);                    
                }
                
                foreach ($arrData as $row){                    
                    $dmnTrab = new dmnTrabajador();
                    $dmnTrab->setIdproy($dmnDatos);                    
                    $dmnTrab->setId($row->idTrab);
                    $this->mprTrab->update($dmnTrab);
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
    
    public function deleteItem(){
        $this->load->model('mapper/proceso/mItem','mprItem');
        try {
            $dmnItem = new dmnItem($this->input->post('idItem'));
            $this->mprItem->delete($dmnItem);
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



