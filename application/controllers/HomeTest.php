<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class HomeTest extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->helper(['url', 'form']);
        $this->load->library('session');
        $this->load->database();
    }
    
    public function index() {
        echo "<h1>🏠 Home Test - SIMAHG Dashboard</h1>";
        
        echo "<h2>Estado de la Sesión:</h2>";
        echo "<p>¿Usuario logueado?: " . ($this->session->userdata('is_logued_in') ? '✅ SÍ' : '❌ NO') . "</p>";
        
        if ($this->session->userdata('is_logued_in')) {
            echo "<div style='background: #d4edda; padding: 20px; border-radius: 5px; margin: 20px 0;'>";
            echo "<h3>✅ ¡Bienvenido al Dashboard!</h3>";
            echo "<p><strong>Usuario:</strong> " . $this->session->userdata('username') . "</p>";
            echo "<p><strong>Email:</strong> " . $this->session->userdata('email') . "</p>";
            echo "<p><strong>Perfil:</strong> " . $this->session->userdata('perfil_nombre') . "</p>";
            echo "<p><strong>Hora de login:</strong> " . date('Y-m-d H:i:s', $this->session->userdata('login_time')) . "</p>";
            echo "</div>";
            
            echo "<h3>Menú Principal:</h3>";
            echo "<ul>";
            echo "<li>📊 Reportes</li>";
            echo "<li>👥 Usuarios</li>";
            echo "<li>⚙️ Configuración</li>";
            echo "<li>📋 Gestión</li>";
            echo "</ul>";
            
        } else {
            echo "<div style='background: #f8d7da; padding: 20px; border-radius: 5px; margin: 20px 0;'>";
            echo "<h3>❌ No hay sesión activa</h3>";
            echo "<p>Por favor, inicie sesión para acceder al dashboard.</p>";
            echo "</div>";
        }
        
        echo "<hr>";
        echo "<h3>Datos Completos de Sesión:</h3>";
        echo "<pre>";
        print_r($this->session->all_userdata());
        echo "</pre>";
        
        echo "<p><a href='" . base_url('login/simple') . "'>🔑 Ir al Login</a></p>";
        echo "<p><a href='" . base_url('login/logout') . "'>🚪 Cerrar Sesión</a></p>";
    }
}
?>
