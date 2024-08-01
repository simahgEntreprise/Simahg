<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {
    
    public function index() {
        echo "<h1>¡CodeIgniter funciona!</h1>";
        echo "<p>Base URL: " . base_url() . "</p>";
        echo "<p>Current URI: " . uri_string() . "</p>";
        echo "<p>Database conectada: ";
        
        try {
            $this->load->database();
            echo "SÍ</p>";
            
            // Probar conexión
            $query = $this->db->query("SELECT COUNT(*) as total FROM usuarios");
            $result = $query->row();
            echo "<p>Usuarios en la base de datos: " . $result->total . "</p>";
            
        } catch (Exception $e) {
            echo "NO - " . $e->getMessage() . "</p>";
        }
        
        echo "<p><a href='" . base_url('login') . "'>Ir al Login</a></p>";
    }
}
?>
