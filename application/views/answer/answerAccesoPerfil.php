<?php
require_once RESPONSE_PATH.'conf/rspAccesoPerfil.php';
$dmnDatos = $data;
$response = array(                                
                "recordsTotal" => $dmnDatos->getTotal(),
                "recordsFiltered" => $dmnDatos->getTotal(),
                'data' => rspAccesoPerfil::parseToArray($dmnDatos->getResults())        
);
echo json_encode($response);
