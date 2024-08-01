<?php
require_once RESPONSE_PATH.'conf/rspEmpresa.php';
$dmnDatos = $data;
$response = array(                                
                "recordsTotal" => $dmnDatos->getTotal(),
                "recordsFiltered" => $dmnDatos->getTotal(),
                'data' => rspEmpresa::parseToArray($dmnDatos->getResults())        
);
echo json_encode($response);
