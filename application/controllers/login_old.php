<?php
require_once APPPATH.'core/control_sesion.php';
require_once APPPATH.'controllers/configuracion/usuario.php';
class login extends control_sesion{
    protected $arrLetras = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','Ñ','O','P','Q','R','S','T','U','V','W','X','Y','Z');
    protected $arrNumeros =array('A' => '1','B' => '2','C' =>'3','D' =>'4','E' =>'5','F' =>'6','G' =>'7','H' =>'8','J' =>'9');
    public function __construct() {
	parent::__construct();	
	$this->removeCache();
    }
    public function index(){
        $this->load->view('vlogin');
    }
    
    public function getLogin(){
        $this->load->driver('session');
//        if($this->input->post('token') && $this->input->post('token') == $this->session->userdata('token')){        
        $this->load->model('mapper/configuracion/mUsuario','mapperUsuario');
        
        $contr = array();
        $contr['log'] = $this->input->post('log');     
        $contr['pass'] = $this->encriptar(strtoupper($this->input->post('pass')));

       // echo $this->encriptar("123456");




//        $contr['pass'] = sha1($this->input->post('pass'));        
//        $check_user = new dmnUsuario();
        
        $check_user = $this->mapperUsuario->find($contr);    
       // var_dump($check_user);
       // echo '<br>';
                   
        $auth = false;
        if($check_user  == TRUE){
       // echo 'entro';
            $auth = true;
            $data = array(
	                'is_logued_in' 	=> 		TRUE,
	                'id_usuario' 	=> 	$check_user->id,
	                'perfil'	=>	$check_user->idperfil->id,
	                'username' 	=> 	$check_user->nombre
            );		
            $this->session->set_userdata($data);

             //var_dump($data);
        }
        $data = array(
            'success' => true,
            'authenticated' => $auth
        );
        echo json_encode($data);        
    }    

    
    public function token()
	{
		$token = md5(uniqid(rand(),true));
		$this->session->set_userdata('token',$token);
		return $token;
	}
	
	public function logout_ci()
	{
            $this->load->driver('session');
		$this->session->sess_destroy();
                $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');		
		$this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);		
                $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
                $this->output->set_header("Pragma: no-cache");	
//                $this->load->view('vlogin');
                redirect('login');
	}


public function encriptar($valor){

        $arrVal = str_split( $valor);
        $clave ='';
        $totClave ='';
        for ($i =0; $i<count($arrVal); $i++){
            if (in_array($arrVal[$i], $this->arrLetras)){
                $clave = array_search($arrVal[$i], $this->arrLetras);
            }elseif (in_array($arrVal[$i], $this->arrNumeros)) {
                $clave = array_search($arrVal[$i], $this->arrNumeros);
            }else{
                $clave = $arrVal[$i];
            }
            $totClave .= $clave.".";
        }
        $totClave = substr($totClave, 0, -1);                
        return $totClave;
    }
    public function desencriptar($valor){
        $arrVal = explode(".", strtoupper($valor)) ;
        
        
        $clave ='';
        $totClave ='';
        foreach ($arrVal as  $value) {           
            if (array_key_exists($value, $this->arrLetras)){
                $clave = $this->arrLetras[$value];
            }elseif (array_key_exists($value, $this->arrNumeros)){
                $clave = $this->arrNumeros[$value];
            }else{
                $clave = $value;
            }
            $totClave.=$clave;
        }                        
                    
        return $totClave;
    }
    
}