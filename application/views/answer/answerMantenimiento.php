<?php
require_once RESPONSE_PATH.'proceso/rspMantenimiento.php'; 

$dmnDatos = $data;
$response = array(                                
                "recordsTotal" => $dmnDatos->getTotal(),
                "recordsFiltered" => $dmnDatos->getTotal(),
                'data' => rspMantenimiento::parseToArray($dmnDatos->getResults())
        
);
echo json_encode($response);

