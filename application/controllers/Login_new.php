<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->database();
    }
    
    public function index() {
        // Si ya está logueado, redirigir al home
        if ($this->session->userdata('is_logued_in')) {
            redirect('home');
        }
        $this->load->view('vLogin');
    }
    
    public function getLogin() {
        // Establecer tipo de respuesta JSON
        $this->output->set_content_type('application/json');
        
        try {
            // Obtener datos del POST
            $usuario = $this->input->post('log', TRUE);
            $password = $this->input->post('pass', TRUE);
            
            // Validaciones básicas
            if (empty($usuario) || empty($password)) {
                $response = array(
                    'authenticated' => false,
                    'message' => 'Usuario y contraseña son requeridos'
                );
                echo json_encode($response);
                return;
            }
            
            // Encriptar la contraseña para comparar
            $password_encrypted = sha1(strtoupper($password));
            
            // Consultar usuario en la base de datos
            $this->db->select('u.*, p.nombre as perfil_nombre');
            $this->db->from('usuarios u');
            $this->db->join('perfiles p', 'u.id_perfil = p.id');
            $this->db->where('u.usuario', $usuario);
            $this->db->where('u.password', $password_encrypted);
            $this->db->where('u.estado', 1);
            $query = $this->db->get();
            
            if ($query->num_rows() == 1) {
                $user = $query->row();
                
                // Actualizar último acceso
                $this->db->where('id', $user->id);
                $this->db->update('usuarios', array('ultimo_acceso' => date('Y-m-d H:i:s')));
                
                // Crear datos de sesión
                $session_data = array(
                    'is_logued_in' => TRUE,
                    'user_id' => $user->id,
                    'username' => $user->nombre . ' ' . $user->apellidos,
                    'email' => $user->email,
                    'usuario' => $user->usuario,
                    'perfil_id' => $user->id_perfil,
                    'perfil_nombre' => $user->perfil_nombre,
                    'login_time' => time()
                );
                
                $this->session->set_userdata($session_data);
                
                $response = array(
                    'authenticated' => true,
                    'message' => '¡Bienvenido al sistema!',
                    'user' => array(
                        'nombre' => $user->nombre . ' ' . $user->apellidos,
                        'perfil' => $user->perfil_nombre
                    )
                );
            } else {
                $response = array(
                    'authenticated' => false,
                    'message' => 'Usuario y/o contraseña incorrectos'
                );
            }
            
        } catch (Exception $e) {
            $response = array(
                'authenticated' => false,
                'message' => 'Error interno del sistema'
            );
            log_message('error', 'Error en login: ' . $e->getMessage());
        }
        
        echo json_encode($response);
    }
    
    public function logout() {
        // Destruir todos los datos de sesión
        $this->session->unset_userdata(array(
            'is_logued_in',
            'user_id',
            'username',
            'email',
            'usuario',
            'perfil_id',
            'perfil_nombre',
            'login_time'
        ));
        
        $this->session->sess_destroy();
        
        // Limpiar caché
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
        $this->output->set_header("Pragma: no-cache");
        
        redirect('login');
    }
    
    /**
     * Verificar si el usuario está autenticado
     */
    public function check_auth() {
        if (!$this->session->userdata('is_logued_in')) {
            $response = array(
                'authenticated' => false,
                'message' => 'Sesión expirada'
            );
            echo json_encode($response);
            return;
        }
        
        $response = array(
            'authenticated' => true,
            'user' => array(
                'username' => $this->session->userdata('username'),
                'perfil' => $this->session->userdata('perfil_nombre')
            )
        );
        echo json_encode($response);
    }
    
    /**
     * Cambiar contraseña
     */
    public function change_password() {
        $this->output->set_content_type('application/json');
        
        if (!$this->session->userdata('is_logued_in')) {
            echo json_encode(array('success' => false, 'message' => 'No autorizado'));
            return;
        }
        
        $current_password = $this->input->post('current_password');
        $new_password = $this->input->post('new_password');
        $confirm_password = $this->input->post('confirm_password');
        
        if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
            echo json_encode(array('success' => false, 'message' => 'Todos los campos son requeridos'));
            return;
        }
        
        if ($new_password !== $confirm_password) {
            echo json_encode(array('success' => false, 'message' => 'Las contraseñas nuevas no coinciden'));
            return;
        }
        
        if (strlen($new_password) < 6) {
            echo json_encode(array('success' => false, 'message' => 'La contraseña debe tener al menos 6 caracteres'));
            return;
        }
        
        // Verificar contraseña actual
        $user_id = $this->session->userdata('user_id');
        $current_encrypted = sha1(strtoupper($current_password));
        
        $this->db->where('id', $user_id);
        $this->db->where('password', $current_encrypted);
        $query = $this->db->get('usuarios');
        
        if ($query->num_rows() == 0) {
            echo json_encode(array('success' => false, 'message' => 'Contraseña actual incorrecta'));
            return;
        }
        
        // Actualizar contraseña
        $new_encrypted = sha1(strtoupper($new_password));
        $this->db->where('id', $user_id);
        $this->db->update('usuarios', array('password' => $new_encrypted));
        
        echo json_encode(array('success' => true, 'message' => 'Contraseña actualizada exitosamente'));
    }
}
