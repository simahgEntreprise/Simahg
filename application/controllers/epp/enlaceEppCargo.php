<?php
require_once APPPATH.'controllers/BaseController.php';
class enlaceEppCargo extends BaseController{
    public function __construct() {
	parent::__construct();	
        if($this->session->userdata('is_logued_in') != TRUE) {
        redirect('login');
        }
    }
    public function index(){
        $this->load->view('epp/vEnlaceEppCargo');
    }
    
    public function getList(){
        $this->load->model('mapper/epp/mEpp','mprEpp');
        $this->load->model('mapper/epp/mEppCargo','mprEppCargo');
        $contr = array();        
        $contr['cargo'] = $_GET['cargo'];        
        $dmnResponse = $this->mprEpp->finder($contr);
        foreach ($dmnResponse->getResults() as $dmnEpp) {
            $contr['epp'] = $dmnEpp->getId();            
            $dmnEppCargo = $this->mprEppCargo->find($contr);                        
            
            if ($dmnEppCargo != null){
                $dmnEpp->setChecked('1');
            }            
            $dmnEpp->mapper()->getRiesgo();
            $dmnEpp->mapper()->getTipo();
        }
        $data['data'] = $dmnResponse;
        $this->load->view('answer/answerEpp',$data);
    }
    
    public function create(){        
        $this->load->model('mapper/epp/mEppCargo','mprEppCargo');
        $dmnEpp= new dmnEppCargo();
        try {
            $arrData = json_decode($this->input->post('data'));
            $cargo = $this->input->post('cargo');
            $dmnEpp->setIdcargo(new dmnCargo($cargo));
            $this->mprEppCargo->delete($dmnEpp);
            foreach ($arrData as $row){
                $dmnEppCargo = new dmnEppCargo();
                $dmnEppCargo->setIdcargo(new dmnCargo($row->idCargo));
                $dmnEppCargo->setIdepp(new dmnEpp($row->idEpp));
                $this->mprEppCargo->create($dmnEppCargo);
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
            $dmnItem = new dmn($this->input->post('cargo'));
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



