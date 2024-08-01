<?php
require_once APPPATH.'models/domain/configuracion/dmnUsuario.php';
require_once APPPATH.'models/domain/configuracion/dmnPerfil.php';
require_once APPPATH.'models/domain/configuracion/dmnAcceso.php';
require_once APPPATH.'models/domain/configuracion/dmnPerfilAcceso.php';
//require_once APPPATH.'core/control_sesion.php';
//session_start();
/**
 * PHP Template.
 */
class BaseController extends CI_Controller
{
   /**
 * Constructor
 * Load the library session
 */
    function __construct() {
        parent::__construct();

        // Load the session library
        $this->load->library('session');
    }

    function getSessionInfo(){
        $answer 		= new Lib_Ajax_Answer();
        $data 			= array();
        $results 		= array();

        if($this->isUnderSession()== false)
        {
            $this->load->lang('common');
            $answer->setSuccess(FALSE)->setMessage($this->lang->line('msg_session_expired_err'))->setCode(0);
            $data['data'] = $answer;
            $this->load->view('json_answer_view',$data);
        }else{
            $this->load->model('mapper/configuracion/mUsuario','MapperUsuario');
            try
            {
            	$dmnUsuario = $this->MapperUsuario->find(array('nombre' => strtoupper($this->session->userdata('user'))));
                if($dmnUsuario == NULL){
                    throw new Exception('No se encontro el usuario con nombre: '.$this->session->userdata('user'),-1);
                }
                $dmnSession = new DomainSession();
                $dmnSession->setUsuario($dmnUsuario);
//                //Seteando al Objeto Localidad
//                $dmnLocalidad = new DomainLocalidad($this->session->userdata('LocalidadId'));
//                //Seteando al Objeto Compania
//                $dmnCompania = new DomainCompania($this->session->userdata('CompaniaId'));
//                $dmnCompania->setNombreCorto($this->session->userdata('NombreCompania'));
//                $dmnLocalidad->setCompania($dmnCompania);
//                $dmnLocalidad->setNombre($this->session->userdata('NombreLocalidad'));
//                //Completando los datos de la session
//                $dmnSession->setLocalidad($dmnLocalidad);
                $data['data'] = $dmnSession;
                $this->load->view('answers/session/AnswerSessionInformation',$data);
            }catch(Exception $e){
                $answer->setSuccess(FALSE)->setMessage($e->getMessage())->setCode($e->getCode());
                $data['data'] = $answer;
                $this->load->view('json_answer_view',$data);
            }
        }
        $data = null;
    }

    /**
     * @return unknown_type
     */
    function session_check()
    {
        $nologin=array("login_c","companias_c","localidades_c");
        if(!$this->isUnderSession()  and !in_array(get_class($this),$nologin)){
          $this->session->sess_destroy();
          header("Location: ".base_url());
          exit;
        }
    }
    /**
     * Check if the original session created after login is
     * loaded, for do that after the login the session need
     * to contain he key 'sess' with true.
     *
     * @access protected
     * @return boolean , TRUE if the session is loaded
     *
     */
    protected function isUnderSession()
    {
        if ($this->session->userdata('sess') == false) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /**
     * Setup the required data for all the views , like
     * the user selected language and the current theme.
     *
     * @access protected
     * @param reference to the array that will hold the setup
     * data.
     * @return void
     *
     */
    protected function setupPageData(array &$data) {
        $data['lang'] = $this->session->userdata('lang');
        $data['theme'] = $this->session->userdata('theme');
    }

    /**
     * Setup the default setup  data for if not already selected
     * by the user (doesnt exist the session)
     *
     * @access protected
     * @param reference to the array that will hold the setup
     * data.
     * @return void
     *
     */
    protected function setupDefaultPageData(array &$data) {
        $data['lang'] = 'es';
        $data['theme'] = 'sunny';
    }
    /**
     * Return the user selected language.
     *
     * @access protected
     * @return string , with the user selected language like 'en','es',etc.
     *
     */
    function getSelectedLanguage() {

        return $this->session->userdata('lang');
    }

    /**
     * Because codeigniter allow only one language in the file config
     * this methods allow on the fly change the config option for the language
     * using the selected user language. Can be called previously to any
     * language output stuff for use the user selected language.
     *
     * @access protected
     * @return void.
     *
     */
    protected function setSelectedLanguageInConfig() {
    // Get the suported languages from the config and set the config language
    // using the user desired language from the session.
        $supported_langs = $this->config->item('lang_availables');
        if($this->getSelectedLanguage()!=""){
            $this->config->set_item('language',$supported_langs[$this->getSelectedLanguage()]);
        }else{
            $this->config->set_item('language',$supported_langs["es"]);
        }
        $supported_langs = NULL;
    }

    function createFieldErrorAnswer(Lib_Ajax_Answer $answer,$msg,array $flderrs) {

        $answer->setSuccess(FALSE)->setMessage($msg)->setCode(0);
        // On validation error returns the error messages with the fields invloved and his error messages
        $count = count($flderrs);
        $errs = 0;
        for($i=0; $i < $count; $i++) {
        // get  the field  name
            $field = $flderrs[$i]['field'];
            // Get the error message for the field
            if ($answer->addFieldError($field,$this->form_validation->error($field))) {
                if ($errs == 0) {
                    $answer->setCode(1);
                }
                ++$errs;
            }
            $field = NULL;
        }
        $flderrs = NULL;
    }

    /**
     * @return unknown_type
     */
    function getConfigUsuario()
    {
    	$answer 		= new Lib_Ajax_Answer();
        $data 			= array();
        $results 		= array();

        if($this->isUnderSession()== false)
        {
            $this->load->lang('common');
            $answer->setSuccess(FALSE)->setMessage($this->lang->line('msg_session_expired_err'))->setCode(0);
            $data['data'] = $answer;
            $this->load->view('json_answer_view',$data);
        }else{
            $this->load->model('usuarios_dao_m','UsuariosDAO',TRUE);
            try
            {
            	$constraints['UsuarioId'] = $this->session->userdata('userid');
                $results = $this->UsuariosDAO->getUsuarios($constraints);
                //print_r($results);
                $Usuario = $results[0];
                return $Usuario;
            }

            catch(Lib_Db_Exception $e)
            {
                $answer->setsucces(FALSE)->setMessage($e->getMessage())->setCode($e->getCode());
                $data['data'] = $answer;
                $this->load->view('json_answer_view',$data);
            }
        }
        $data = null;
    }
	/**
	 * @return unknown_type
	 */
    function getUserSession()
    {
//        print_r($this->session);
    	$data = array();
       	$data['Nombre']             = $this->session->userdata('user');
       	$data['UsuarioId'] 		    = $this->session->userdata('userid');
       	$data['CompaniaId']		    = $this->session->userdata('CompaniaId');
       	$data['LocalidadId']		= $this->session->userdata('LocalidadId');
       	$data['NombreCompania']		= $this->session->userdata('NombreCompania');
       	$data['NombreLocalidad']	= $this->session->userdata('NombreLocalidad');

        //----------------Cesar-----------------
        $_SESSION['NombreUsuario']  = $this->session->userdata('user');
        $_SESSION['UsuarioId']		= $this->session->userdata('userid');
        $_SESSION['NombreCompania'] = $this->session->userdata('NombreCompania');
        $_SESSION['NombreLocalidad']= $this->session->userdata('NombreLocalidad');
        //-----------------Cesar----------------

       	return $data;
    }
    /**
     * @return unknown_type
     */
    function contrlr_validation($Archivo,$Seccion,$type)
    {
    	$answer = new Lib_Ajax_Answer();
    	$this->load->library('form_validation');
        $this->load->config('validation/'.$Archivo);        
        $rules = $this->config->item($Seccion);        
        $this->form_validation->set_error_delimiters('','');
        $this->form_validation->set_rules($rules[$type]);
        try
        {
	        if ($this->form_validation->run() == false)
	        {
		    	$this->createFieldErrorAnswer($answer,'---',$rules[($type)]);
				$data['data'] = $answer;
		        $this->load->view('json_answer_view',$data);
		        $rules = null;
		        $data = null;
		        return false;
	        }
	        return true;
        }catch(Lib_Db_Exception $ex)
        {
        	$answer->setSuccess(FALSE)->setMessage($ex->getMessage())->setCode($ex->getCode());
        	$data['data'] = $answer;
        	$this->load->view('json_answer_view',$data);
        	$data = null;
        }
    }


    /*
     * FUNCION PARA VALIDAR LOS FORMULARIOS
     * @param: $NombreArchivo: Es el Nombre del Archivo donde se encuentra la validacion.
     * @param: $IndexTipo: Indice el tipo a validad ejemplo('eppend','update').
     */
    function validar($NombreArchivo,$type)
    {
        $answer = new Lib_Ajax_Answer();
    	$this->load->library('form_validation');
        $this->load->config('validation/'.($NombreArchivo).'_Validation');
        $rules = $this->config->item(strtolower($NombreArchivo));
        $this->form_validation->set_error_delimiters('','');
        $this->form_validation->set_rules($rules[$type]);
        //Ejecutamos el Validador
    	if ($this->form_validation->run() == false)
    	{   //print_r($rules);exit();
    		$this->createFieldErrorAnswer($answer,'---',$rules[($type)]);
			$data['data'] = $answer;
            echo json_encode($data['data']->getResult());
            exit();
    	}
    	return true;

    }


    /*FUNCTION PARA TRANSFORMAR LA FECHA QUE VIENE DEL CLIENTE*/
    /*@fecha: format:Y/m/d */
    function dateServer($fecha ){
        if($fecha == null){
            return null;
        }
        if($fecha == 'null'){
            return null;
        }
        if($fecha == 'undefined'){
            return null;
        }
        if($fecha == ''){
            return null;
        }
        if(empty($fecha)){
            return null;
        }

        if(!empty($fecha)){
            $f = strtotime($fecha);
            return date(FORMATDATE,$f);
        }else{
            return null;
        }

    }

    /*function que convierte la fecha del server al formato d/m/Y, la fecha entra d-m-Y */
    function dateCliente($fecha){
        /*si la fecha viene con (-)*/
        $fecha = explode(' ',$fecha);
        if(count($fecha) > 0){
         $fecha = $fecha[0];
        }
        //print_r($fecha);exit();
        if(!empty($fecha)){
            $f = strtotime($fecha);
            return date('d/m/Y',$f);
        }else{
            return null;
        }

    }

    public  function dateYMD($DMY){

        if($DMY == null){
            return null;
        }
        if($DMY == 'null'){
            return null;
        }
        if($DMY == 'undefined'){
            return null;
        }
        if($DMY == ''){
            return null;
        }
        if(empty($DMY)){
            return null;
        }

        $tabla = explode('/',$DMY);
        $fech = $tabla[2].'/'.$tabla[1].'/'.$tabla[0];
        return $fech;
        //return date(FORMATDATE,strtotime($fech));
    }

    /*
     * @FUNCION ENCARGADA DE VALIDAR QUE NO SE REPITA LOS DATOS EN UNA GRILLA
     */
    /*
     * @param: $row-> row de la matriz.
     * @param: $arg-> campos a concatenar y realizar la comparacion.
     */
    protected function cargar($row,$arg){
        $cad = '';
        foreach($arg as $fila){
            $cad .= strtolower($row[$fila]);
        }
        return $cad;
    }

    function NotRepeatDataGrid($Matriz,array $fieldsConcatenar,$Mensaje){
    	$id_serv = array();
    	$cadena	= '';
    	$claves = array();

    	foreach($Matriz as $row){
    		$id_serv[] = $this->Cargar($row,$fieldsConcatenar);
    	}
    	foreach($id_serv as $key => $row){
    		$claves[$row][] = $key+1;
    	}
    	foreach ($claves as $key => $row){
            if (count($row) > 1){
                    $lineas = json_encode($row);
                    $cadena = $cadena.$Mensaje.$lineas."\n";
            }
    	}
    	if($cadena != null){
            $cadena = nl2br($cadena);
        }
        return $cadena;
    }

    /*
     * funcion para convertir las hora en numerico(solo para 15,30,45 minutos).
     */
   function getHoraToNumeric($time){
       // 1 Hora -> 60 Min.
       //print_r($time);exit();
       $answer = new Lib_Ajax_Answer();
       if($time <= 0){
           $answer->getAnswerError(array('msg'=>'La Cantidad Ingresada(Tiempo) Debe ser Mayor que Cero.'),false);
           exit();
       }
       $minuto  = explode('.',$time); //print_r($minuto);exit();
       if(count($minuto) > 1){
        $min = $minuto[1];
       }else{
         $min = 0;
       }

       if($min != 0){

           if($min >= 60){
               $answer->getAnswerError(array('msg'=>'El Minuto Ingresado Debe ser Menor a 60 min.'),false);
               exit();
           }
    //       print_r($min);
           // validamos que solo ingrese 15,30,45 min.
           if($min == 3){
               $min = 30;
           }
           if( $min % 15 != 0 ){
               $answer->getAnswerError(array('msg'=>'Solo se Debe Ingresar Los Minutos de la Siguiente Manera(15,30,45). '),false);
               exit();
           }
           //print_r($min);exit();
           if(strlen($min) > 0 ){
               $min = (60/$min);
               $min = round(100/$min,0);
               return $minuto[0].'.'.$min;

           }else{
               //print_r('aca');exit();
               return $minuto[0].'.'.$time;
           }
       }else{
           return  $minuto[0];//.'.'.$time;;
       }

   }
   function load_exception_list(Lib_Ajax_Answer $Answer, Exception $e){
       $Answer->setSuccess(false)->setMessage($e->getMessage())->setCode($e->getCode());
        $data['data'] = $Answer;
        $this->load->view('json_answer_view',$data);
        $data = null;
   }
   function generateAnswer($results){
       $data['data'] = array(
                                'success' => true,
                                'total' => $results['count'],
                                'results' => $results['results']
        );

       $this->load->view('jsonAnswer',$data);
   }

    function loadView($view, Response $dmnResponse = null){
       $data = array();
       if($dmnResponse != null){
            $data['data'] = $dmnResponse;
            $this->load->view($view,$data);
       }
       if($dmnResponse == NULL){
           $dmnResponse = new Response();
           $data['data'] = $dmnResponse;
           $this->load->view($view,$data);
       }
   }


}
?>