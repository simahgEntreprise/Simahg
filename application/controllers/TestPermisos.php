<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TestPermisos extends CI_Controller {
    
    public function index() {
        // Test de escritura de archivos
        echo "<h1>🧪 Test de Permisos de Escritura</h1>";

        $session_dir = APPPATH . 'sessions/';
        $test_file = $session_dir . 'test_write.txt';

        echo "<p><strong>Directorio de sesiones:</strong> " . $session_dir . "</p>";
        echo "<p><strong>Permisos del directorio:</strong> " . substr(sprintf('%o', fileperms($session_dir)), -4) . "</p>";
        echo "<p><strong>Propietario:</strong> " . fileowner($session_dir) . "</p>";

        // Intentar escribir un archivo de prueba
        if (is_writable($session_dir)) {
            echo "<p>✅ Directorio es escribible</p>";
            
            if (file_put_contents($test_file, "Test " . date('Y-m-d H:i:s'))) {
                echo "<p>✅ Archivo de prueba creado exitosamente</p>";
                
                // Limpiar el archivo de prueba
                unlink($test_file);
                echo "<p>✅ Archivo de prueba eliminado</p>";
                
            } else {
                echo "<p>❌ No se pudo escribir el archivo de prueba</p>";
            }
            
        } else {
            echo "<p>❌ Directorio NO es escribible</p>";
            echo "<p>Usuario PHP actual: " . get_current_user() . "</p>";
        }

        echo "<hr>";
        echo "<p><a href='" . base_url('login/simple') . "'>🔑 Probar Login</a></p>";
    }
}
?>
