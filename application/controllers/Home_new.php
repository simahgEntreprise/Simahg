<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->helper(['url', 'form']);
        $this->load->library('session');
        $this->load->database();
        
        // Verificar si el usuario está autenticado
        if (!$this->session->userdata('is_logued_in')) {
            redirect('login');
        }
    }
    
    public function index() {
        $data = array(
            'page_title' => 'Dashboard - SIMAHG',
            'user_name' => $this->session->userdata('username'),
            'perfil_nombre' => $this->session->userdata('perfil_nombre'),
            'stats' => $this->getDashboardStats(),
            'recent_activities' => $this->getRecentActivities(),
            'menu_items' => $this->getMenuItems()
        );
        
        $this->load->view('dashboard/vHome', $data);
    }
    
    /**
     * Obtener estadísticas del dashboard
     */
    private function getDashboardStats() {
        $stats = array();
        
        // Total de usuarios
        $this->db->where('estado', 1);
        $stats['total_usuarios'] = $this->db->count_all_results('usuarios');
        
        // Total de perfiles
        $this->db->where('estado', 1);
        $stats['total_perfiles'] = $this->db->count_all_results('perfiles');
        
        // Usuarios activos hoy
        $this->db->where('estado', 1);
        $this->db->where('DATE(ultimo_acceso)', date('Y-m-d'));
        $stats['usuarios_activos'] = $this->db->count_all_results('usuarios');
        
        // Total de módulos activos
        $this->db->where('estado', 1);
        $stats['total_modulos'] = $this->db->count_all_results('modulos');
        
        return $stats;
    }
    
    /**
     * Obtener actividades recientes
     */
    private function getRecentActivities() {
        $this->db->select('u.nombre, u.apellidos, u.ultimo_acceso, p.nombre as perfil');
        $this->db->from('usuarios u');
        $this->db->join('perfiles p', 'u.id_perfil = p.id');
        $this->db->where('u.estado', 1);
        $this->db->where('u.ultimo_acceso IS NOT NULL');
        $this->db->order_by('u.ultimo_acceso', 'DESC');
        $this->db->limit(10);
        $query = $this->db->get();
        
        return $query->result();
    }
    
    /**
     * Obtener elementos del menú según el perfil del usuario
     */
    private function getMenuItems() {
        $perfil_id = $this->session->userdata('perfil_id');
        
        $this->db->select('m.*, mod.nombre as modulo_nombre, mod.icono as modulo_icono');
        $this->db->from('menu m');
        $this->db->join('modulos mod', 'm.id_modulo = mod.id');
        $this->db->join('permisos p', 'm.id = p.id_menu');
        $this->db->where('p.id_perfil', $perfil_id);
        $this->db->where('p.leer', 1);
        $this->db->where('m.estado', 1);
        $this->db->where('mod.estado', 1);
        $this->db->order_by('mod.orden, m.orden');
        $query = $this->db->get();
        
        $menu_items = array();
        foreach ($query->result() as $item) {
            $menu_items[$item->modulo_nombre][] = $item;
        }
        
        return $menu_items;
    }
    
    /**
     * Obtener datos para gráficos
     */
    public function getChartData() {
        $this->output->set_content_type('application/json');
        
        try {
            // Usuarios por perfil
            $this->db->select('p.nombre, COUNT(u.id) as total');
            $this->db->from('usuarios u');
            $this->db->join('perfiles p', 'u.id_perfil = p.id');
            $this->db->where('u.estado', 1);
            $this->db->group_by('p.id, p.nombre');
            $users_by_profile = $this->db->get()->result();
            
            // Actividad por mes (últimos 6 meses)
            $monthly_activity = array();
            for ($i = 5; $i >= 0; $i--) {
                $date = date('Y-m', strtotime("-$i months"));
                $this->db->where('DATE_FORMAT(ultimo_acceso, "%Y-%m")', $date);
                $count = $this->db->count_all_results('usuarios');
                $monthly_activity[] = array(
                    'month' => date('M Y', strtotime($date)),
                    'count' => $count
                );
            }
            
            $data = array(
                'users_by_profile' => $users_by_profile,
                'monthly_activity' => $monthly_activity
            );
            
            echo json_encode($data);
            
        } catch (Exception $e) {
            echo json_encode(array('error' => 'Error al obtener datos'));
        }
    }
    
    /**
     * Obtener información del sistema
     */
    public function getSystemInfo() {
        $this->output->set_content_type('application/json');
        
        try {
            // Información del sistema
            $this->db->where_in('clave', ['sistema_nombre', 'sistema_version', 'empresa_nombre']);
            $config_query = $this->db->get('configuraciones');
            
            $config = array();
            foreach ($config_query->result() as $item) {
                $config[$item->clave] = $item->valor;
            }
            
            $system_info = array(
                'php_version' => PHP_VERSION,
                'codeigniter_version' => CI_VERSION,
                'mysql_version' => $this->db->version(),
                'server_time' => date('Y-m-d H:i:s'),
                'system_config' => $config
            );
            
            echo json_encode($system_info);
            
        } catch (Exception $e) {
            echo json_encode(array('error' => 'Error al obtener información del sistema'));
        }
    }
    
    /**
     * Actualizar perfil de usuario
     */
    public function updateProfile() {
        $this->output->set_content_type('application/json');
        
        if (!$this->session->userdata('is_logued_in')) {
            echo json_encode(array('success' => false, 'message' => 'No autorizado'));
            return;
        }
        
        $user_id = $this->session->userdata('user_id');
        $nombre = $this->input->post('nombre', TRUE);
        $apellidos = $this->input->post('apellidos', TRUE);
        $email = $this->input->post('email', TRUE);
        
        if (empty($nombre) || empty($apellidos) || empty($email)) {
            echo json_encode(array('success' => false, 'message' => 'Todos los campos son requeridos'));
            return;
        }
        
        // Verificar si el email ya existe (excepto el usuario actual)
        $this->db->where('email', $email);
        $this->db->where('id !=', $user_id);
        if ($this->db->count_all_results('usuarios') > 0) {
            echo json_encode(array('success' => false, 'message' => 'El email ya está en uso'));
            return;
        }
        
        // Actualizar datos
        $update_data = array(
            'nombre' => $nombre,
            'apellidos' => $apellidos,
            'email' => $email
        );
        
        $this->db->where('id', $user_id);
        if ($this->db->update('usuarios', $update_data)) {
            // Actualizar datos de sesión
            $this->session->set_userdata('username', $nombre . ' ' . $apellidos);
            $this->session->set_userdata('email', $email);
            
            echo json_encode(array('success' => true, 'message' => 'Perfil actualizado exitosamente'));
        } else {
            echo json_encode(array('success' => false, 'message' => 'Error al actualizar el perfil'));
        }
    }
}
