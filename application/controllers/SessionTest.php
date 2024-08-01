<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SessionTest extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->database();
    }
    
    public function index() {
        echo "<h1>Test de Sesión</h1>";
        
        echo "<h2>Datos de Sesión Actuales:</h2>";
        echo "<pre>";
        print_r($this->session->all_userdata());
        echo "</pre>";
        
        echo "<h2>Verificaciones:</h2>";
        echo "<p>¿Usuario logueado?: " . ($this->session->userdata('is_logued_in') ? '✅ SÍ' : '❌ NO') . "</p>";
        echo "<p>User ID: " . ($this->session->userdata('user_id') ?: 'No definido') . "</p>";
        echo "<p>Username: " . ($this->session->userdata('username') ?: 'No definido') . "</p>";
        echo "<p>Usuario: " . ($this->session->userdata('usuario') ?: 'No definido') . "</p>";
        
        echo "<h2>Session ID:</h2>";
        echo "<p>" . $this->session->session_id . "</p>";
        
        echo "<hr>";
        echo "<p><a href='" . base_url('home') . "'>🏠 Intentar ir a Home</a></p>";
        echo "<p><a href='" . base_url('login/simple') . "'>🔑 Volver al Login</a></p>";
        echo "<p><a href='" . base_url('login/logout') . "'>🚪 Cerrar Sesión</a></p>";
    }
}
?>
