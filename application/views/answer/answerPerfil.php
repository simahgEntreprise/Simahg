<?php
require_once RESPONSE_PATH.'conf/rspPerfil.php';
$dmnDatos = $data;
$response = array(                                
                "recordsTotal" => $dmnDatos->getTotal(),
                "recordsFiltered" => $dmnDatos->getTotal(),
                'data' => rspPerfil::parseToArray($dmnDatos->getResults())        
);
echo json_encode($response);
