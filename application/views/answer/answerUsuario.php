<?php
require_once RESPONSE_PATH.'conf/rspUsuario.php';
require_once RESPONSE_PATH.'conf/rspPerfil.php'; 
require_once RESPONSE_PATH.'conf/rspCargo.php';

$dmnDatos = $data;
$response = array(                                
                "recordsTotal" => $dmnDatos->getTotal(),
                "recordsFiltered" => $dmnDatos->getTotal(),
                'data' => rspUsuario::parseToArray($dmnDatos->getResults())
        
);
echo json_encode($response);
