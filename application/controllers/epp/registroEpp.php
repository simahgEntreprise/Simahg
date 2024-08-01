<?php
require_once APPPATH.'controllers/BaseController.php';
require_once MAPPER_PATH.'proceso/mMantenimiento.php';
class registroEpp extends BaseController{
    public function __construct() {
	parent::__construct();	
        if($this->session->userdata('is_logued_in') != TRUE) {
        redirect('login');
        }
    }
    public function index(){
        $this->load->view('epp/vRegistroEpp');
    }
    
    public function getList(){
        $this->load->model('mapper/epp/mEpp','mprEpp');
        $contr = array();
        $contr['nombre'] = $this->input->post('nombre');        
        $contr['id'] = $this->input->post('id');
        $dmnResponse = $this->mprEpp->finder($contr);
        foreach ($dmnResponse->getResults() as $dmnEpp) {
            $dmnEpp->mapper()->getRiesgo();
            $dmnEpp->mapper()->getTipo();
        }
        $data['data'] = $dmnResponse;
        $this->load->view('answer/answerEpp',$data);
    }
    
    public function create(){
        $this->load->model('mapper/epp/mEpp','mprEpp');
        $this->load->model('mapper/epp/mEppCargo','mprEppCargo');
        $dmnEpp = new dmnEpp();
        try {
            $tipo=$this->input->post('tipo');            
            $succ=true;            
            if (count($_FILES)>0){
                $name="";
                for($i=0; $i<count($_FILES); $i++){
                    $upload_folder="images/epp";
                    $nombre_archivo = $_FILES['archivo'.$i]['name'];

//                    print_r($nombre_archivo);exit();
                    $archivador = $upload_folder . '/' . $nombre_archivo;
                    $tmp_archivo = $_FILES['archivo'.$i]['tmp_name'];                               
                    if (!move_uploaded_file($tmp_archivo, $archivador)) {
                        $return = Array('success' => FALSE, 'msg' => 'Ocurrio un error al subir el archivo. No pudo guardarse.');
                        $succ=false;
                    }
                    $name=$name.$upload_folder . '/' . $nombre_archivo;
                }
                $dmnEpp->setImagen(base_url().$name);                
            }
            
            if ($succ) {                                
                $dmnEpp->setRiesgo(new dmnRiesgo($this->input->post('riesgo')));
                $dmnEpp->setTipo(new dmnTipoEpp($this->input->post('tipoEpp')));           
                $dmnEpp->setUso($this->input->post('uso'));           
                $dmnEpp->setEspecificacion($this->input->post('especificacion'));
                $dmnEpp->setVidaUtil($this->input->post('vida'));
                $dmnEpp->setTiempoVidaUtil($this->input->post('cant'));
                $dmnEpp->setNorma($this->input->post('norma'));
                $dmnEpp->setEstado($this->input->post('est'));
                
                if($tipo=="C"){
                    $this->mprEpp->create($dmnEpp);                    
                }else{
                    $dmnEpp->setId($this->input->post('id'));
                    $this->mprEpp->update($dmnEpp);                    
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



