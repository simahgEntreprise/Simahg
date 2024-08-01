<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LoginTest extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->database();
    }
    
    public function index() {
        echo "<h1>Test de Login Manual</h1>";
        echo "<form method='post' action='" . base_url('logintest/verify') . "'>";
        echo "<p>Usuario: <input type='text' name='usuario' value='admin'></p>";
        echo "<p>Contraseña: <input type='password' name='password' value='123456'></p>";
        echo "<p><input type='submit' value='Probar Login'></p>";
        echo "</form>";
        
        echo "<h2>Debug Info:</h2>";
        echo "<p>Base URL: " . base_url() . "</p>";
        echo "<p>Session Data: ";
        var_dump($this->session->all_userdata());
        echo "</p>";
    }
    
    public function verify() {
        $usuario = $this->input->post('usuario');
        $password = $this->input->post('password');
        
        echo "<h1>Resultado del Test</h1>";
        echo "<p>Usuario: " . $usuario . "</p>";
        echo "<p>Password original: " . $password . "</p>";
        
        // Encriptar la contraseña
        $password_encrypted = sha1(strtoupper($password));
        echo "<p>Password encriptado: " . $password_encrypted . "</p>";
        
        // Buscar en la base de datos
        $this->db->select('u.*, p.nombre as perfil_nombre');
        $this->db->from('usuarios u');
        $this->db->join('perfiles p', 'u.id_perfil = p.id');
        $this->db->where('u.usuario', $usuario);
        $this->db->where('u.password', $password_encrypted);
        $this->db->where('u.estado', 1);
        $query = $this->db->get();
        
        echo "<p>SQL ejecutado: " . $this->db->last_query() . "</p>";
        echo "<p>Resultados encontrados: " . $query->num_rows() . "</p>";
        
        if ($query->num_rows() == 1) {
            $user = $query->row();
            echo "<h2>✅ Login Exitoso</h2>";
            echo "<p>Nombre: " . $user->nombre . " " . $user->apellidos . "</p>";
            echo "<p>Perfil: " . $user->perfil_nombre . "</p>";
            
            // Crear sesión
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
            
            echo "<p><a href='" . base_url('home') . "'>🏠 Ir al Home</a></p>";
            
        } else {
            echo "<h2>❌ Login Falló</h2>";
            
            // Verificar si el usuario existe
            $this->db->select('*');
            $this->db->from('usuarios');
            $this->db->where('usuario', $usuario);
            $check = $this->db->get();
            
            if ($check->num_rows() > 0) {
                $existing_user = $check->row();
                echo "<p>Usuario existe, pero password no coincide</p>";
                echo "<p>Password en BD: " . $existing_user->password . "</p>";
                echo "<p>Password enviado: " . $password_encrypted . "</p>";
            } else {
                echo "<p>Usuario no encontrado</p>";
            }
        }
        
        echo "<p><a href='" . base_url('logintest') . "'>⬅️ Volver</a></p>";
    }
}
?>
