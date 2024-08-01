<?php
require_once APPPATH.'core/control_sesion.php';
require_once MAPPER_PATH.'proceso/mMantenimiento.php';
class control extends  control_sesion{
    public function __construct() {
	parent::__construct();	
        if($this->session->userdata('is_logued_in') != TRUE) {
        redirect('login');
        }
    }
    public function index(){
        $this->load->view('vHome');
    }

    public function getControl(){
        $this->load->model('mapper/proceso/mItem','mprItem');
        $contr = array('estado' => 'A');
        
        $dmnResponse = $this->mprItem->finder($contr);        

        foreach($dmnResponse->getResults() as $dmnItem){
            
           if ($dmnItem->getIdtiempoMant()->getId() != null) {
                $dmnItem->Mapper()->getIdtiempoMant();                
                $dmnItem = $this->getControlDate($dmnItem);
           }           
           if ($dmnItem->getIdarea()->getId() != null){
                $dmnItem->Mapper()->getIdarea();                
           }
        }
        
        $data['data'] = $dmnResponse;
        $this->load->view('answer/answerItem',$data);            
    }
    
    public function getControlDate(dmnItem $dmnItem){        
        $fec = $dmnItem->getFecUsoItem();        
        $newFec='';
        switch ($dmnItem->getIdtiempoMant()->getTipo()) {            
            case "M":                                
                $cant = '+'.$dmnItem->getCantidad().' month';                
                $newFec = strtotime ((string)$cant, strtotime ($fec) );                                
                break;
            case "A":
                $cant = '+'.$dmnItem->getCantidad().' year';                
                $newFec = strtotime ((string)$cant, strtotime ($fec) );                                
                break;
            case "D":
                $cant = '+'.$dmnItem->getCantidad().' day';                
                $newFec = strtotime ((string)$cant, strtotime ($fec) );                                
                break;
            case "H":
                
                $cant = '+'.$dmnItem->getCantidad().' hour';                                
                
                $newFec = strtotime ((string)$cant, strtotime ($fec) );                                
                break;
            default:
                $newFec =  strtotime('now');
                break;            
        }
        
        $segundos=$newFec - strtotime('now');
        
        $diferencia_dias=intval($segundos/60/60/8);
//        If ($dmnItem->getEstado()=="T"){
//            $dmnItem->setEstado("3");
//        }else{
            if ($diferencia_dias<=5){
                        $dmnItem->setEstado("1");
            }elseif ($diferencia_dias<=15){
                        $dmnItem->setEstado("2");                    
            }else{
                        $dmnItem->setEstado("3");
            }
//        } 
        $mprMain = new mMantenimiento();
        $est = $mprMain->obtainLastStatus($dmnItem->getId());        
        IF ($est != null){
            if ($est->estado == 'S'){
                $dmnItem->setEstado("3");
            } 
        } 
        
        return $dmnItem;
    }
}

