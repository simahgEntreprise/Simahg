<?php
# determina si el usuario esta logueado o no..
if ( ! function_exists('user_is_logged'))
{
   function user_is_logged(){   

        //instanciamos al objeto codeigniter
        $CI =& get_instance();
        
        // cargamos la base de datos
        $CI->load->database('default');
        
        // obtenemos el valor del item 'sess_use_database'
        $sess_use_database = $CI->config->item('sess_use_database');

        // cargamos la libreria de sesion
        $CI->load->driver('session');
        
        // obtenemos los datos de la sesion
        $arrSesion = $CI->session->userdata('ses_usuario');
        
        // si no esta definida la sesion, no esta logueado
        if (!isset($arrSesion['usuario'])){
            return false;// indicamos no is_logged

        }else{

            // obtenemos el id del usuario
            $session_id = $CI->session->userdata('session_id');
            
            // si se usa la session database, debemos asegurarnos
            // de que la sesion en el cliente, coincida con
            // la sesion de la base de datos
            if (!empty($session_id) and $sess_use_database){
                 
                // consultamos por id
                $CI->db->from('ci_sesion');
                $CI->db->where('session_id',$session_id);
                $query = $CI->db->get();
                
                // si coincide el session_id en algun registro
                // es porque el usuario tiene sesion abierta
                return ($query->num_rows()>0) ? true : false;

            }else{

                // 
                return (!empty($session_id)) ? true : false;
            }
        }
        $CI->db->close();
        unset($CI);
    }
}

# permite cerrar la sesion activa ..
if (! function_exists('cerrar_sesion')){
    
    function cerrar_sesion(){
        
        //instanciamos al objeto codeigniter
        $CI =& get_instance();
        
        // cargamos la base de datos
        $CI->load->database('default');
                
        // obtenemos el valor del item 'sess_use_database'
        $sess_use_database = $CI->config->item('sess_use_database');
        
        // si el usuario esta logueado, cerramos la sesion..
        if (user_is_logged()){
        
            // cargamos la libreria sesion
            $CI->load->driver('session');

            // si se esta usando la base de datos para las sesiones
            if($sess_use_database){
            
                //exit('entre, esta logueado y se usa db');
                
                // indicara si se elimino la seison de la db
                $delete = false;
                
                // obtenemos los datos de la sesion
                $arrSesion = $CI->session->userdata('ses_usuario');                
                
                // obtenemos los registros de sesion
                $CI->db->select('user_data,session_id');
                $CI->db->from('ci_sesion');
                $query    = $CI->db->get();
                $arrDatos = array();

                // recorremos la lista de usuarios con sesion en la db
                foreach($query->result() as $row){

                    // obtenemos el user_data de la fila 
                    $valor = $row->user_data;

                    // los datos estan serializados en la db
                    // asi que los deserializamos
                    $arrData = unserialize($valor);
                    
                    // verificamos si el usuario pasado por parametro 
                    // es el mismo q tiene la sesion abierta
                    if ($arrSesion['usuario']==$arrData['ses_usuario']['usuario']){
                                
                        // borramos la sesion de la db
                        $CI->db->delete('ci_sesion',array('session_id' => $row->session_id));
                        //echo $CI->db->last_query();exit;
                        $delete = true;
                        break;
                    }
                }
        
                if($delete){
                    // cerramos la sesion
                    $CI->session->sess_destroy();
                }
            }else{
                
                // cerramos la sesión
                $CI->session->sess_destroy();
            }
        }
        
        // adicionalmente verificamos las sesioens activas en la db 
        // y eliminamos las que tengan determinado tiempo de incactividad        
        $CI->db->where('last_activity <',(time() - 3600));//3600 - 1 hora
        $CI->db->delete('ci_sesion');
        $CI->db->close();
        #-----------------------------------------------------------------------
        
        unset($CI);
        
        // redireccionamos al controlador index
        redirect('index_c', 'location');
   }
}
