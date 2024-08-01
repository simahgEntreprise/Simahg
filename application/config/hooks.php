<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$hook['post_controller_constructor'] = array(
    'class'    => 'gestion_sesion', // clase que controla la sesion
    'function' => 'index', // metodo encargado de todo, dentro de la clase
    'filename' => 'gestion_sesion.php', // archivo a cargar
    'filepath' => 'hooks' // carpeta donde se encuentra la clase
);
