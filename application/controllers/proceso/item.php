<?php
require_once APPPATH.'controllers/BaseController.php';
require_once MAPPER_PATH.'proceso/mMantenimiento.php';
class item extends BaseController{
    public function __construct() {
	parent::__construct();	
        if($this->session->userdata('is_logued_in') != TRUE) {
        redirect('login');
        }
    }
    public function index(){
        $this->load->view('proceso/vItem');
    }
    
    public function getListItem(){
        $this->load->model('mapper/proceso/mItem','mprItem');
        $contr = array();
        $contr['nombre'] = $this->input->post('nombre');        
        $contr['id'] = $this->input->post('idItem');
        $dmnResponse = $this->mprItem->finder($contr);
        foreach ($dmnResponse->getResults() as $dmnItem) {
            $fec = date_create($dmnItem->getFecUsoItem());             
            $dmnItem->setFecUsoItem($fec->format('Y-m-d\TH:i:s'));
            $mprMante = new mMantenimiento();
            $rsp = $mprMante->finder(array("iditem" => $dmnItem->getId(), "estado" => "P"));
            
            if ($rsp->getTotal() > 0){
                foreach ($rsp->getResults() as $dmnMant) {
                    $dmnItem->setLugar($dmnMant->getLugar());
                }
            }
            
        }
        $data['data'] = $dmnResponse;
        $this->load->view('answer/answerItem',$data);
    }
    
    public function ctrItem(){
        $this->load->model('mapper/proceso/mItem','mprItem');
        $dmnItem = new dmnItem();
        try {
            $tipo=$this->input->post('tipo');            
            $succ=true;            
            if (count($_FILES)>0){
                $name="";
                for($i=0; $i<count($_FILES); $i++){
                    $upload_folder="images/items";
                    $nombre_archivo = $_FILES['archivo'.$i]['name'];

//                    print_r($nombre_archivo);exit();
                    $archivador = $upload_folder . '/' . $nombre_archivo;
                    $tmp_archivo = $_FILES['archivo'.$i]['tmp_name'];                               
                    if (!move_uploaded_file($tmp_archivo, $archivador)) {
                        $return = Array('success' => FALSE, 'msg' => 'Ocurrio un error al subir el archivo. No pudo guardarse.');
                        $succ=false;
                    }
                    $name=$name.$upload_folder . '/' . $nombre_archivo."]";
                    
                    
                }
                $dmnItem->setImagen($name);
            }
            
            if ($succ) {                                
                $dmnItem->setCodigo($this->input->post('cod'));
                $dmnItem->setNumserie($this->input->post('numserie1'));
                $dmnItem->setNombre($contr[''] = $this->input->post('nom'));
                $dmnItem->setResponsable($this->input->post('resp'));
                $dmnItem->setFecIngreso($this->input->post('fecing'));
                $date = date_create($this->input->post('fecuso'));            
                $dmnItem->setFecUsoItem($date->format('Y-m-d\TH:i:s'));
                $dmnItem->setIdarea(new dmnArea($this->input->post('area')));
                $dmnItem->setIdtiempoMant(new dmnTiempoMantenimiento($this->input->post('tmpmant')));
                $dmnItem->setFecregistro(date("Y-m-d"));
                $dmnItem->setFecmodificacion("");
                $dmnItem->setCantidad($this->input->post('cant'));
                $dmnItem->setNumserie2($this->input->post('numserie2'));
                $dmnItem->setModelo1($this->input->post('modelo1'));
                $dmnItem->setModelo2($this->input->post('modelo2'));
                $dmnItem->setTecnico($this->input->post('tec'));
                $dmnItem->setAreaTec(new dmnArea($this->input->post('area2')));
                $dmnItem->setMotor($this->input->post('motor'));
                $dmnItem->setGrupo(new dmnGrupo($this->input->post('grupo')));
                $dmnItem->setNroFactura($this->input->post('nroFac'));
                $dmnItem->setEstado($this->input->post('est'));
                
                if($tipo=="C"){
                    $this->mprItem->create($dmnItem);
                }else{
                    $dmnItem->setId($this->input->post('id'));
                    $dmnItem->setFecmodificacion(date("Y-m-d"));
                    $this->mprItem->update($dmnItem);
                }
                
                $return = array(
                    'success' => true,
                    'msg'=>'Registro guardado correctamente'                
                );
            }          
            
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

