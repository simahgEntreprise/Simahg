<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Testdb extends CI_Controller {

    public function index()
    {
        // Intentar conectarse
        $this->load->database();

        if ($this->db->conn_id) {
            echo "✔ Conexión exitosa a SQL Server";
        } else {
            echo "❌ Error al conectar a SQL Server";
        }
    }
    
}
