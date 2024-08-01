<?php
require_once RESPONSE_PATH.'conf/rspCargo.php';
$dmnDatos = $data;
$response = array(                
                "recordsTotal" => $dmnDatos->getTotal(),
                "recordsFiltered" => $dmnDatos->getTotal(),
                'data' => rspCargo::parseToArray($dmnDatos->getResults())        
);
echo json_encode($response);
