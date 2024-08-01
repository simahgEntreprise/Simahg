<?php
require_once RESPONSE_PATH.'proceso/rspEquipo.php';
$dmnDatos = $data;
$response = array(                                
                "recordsTotal" => $dmnDatos->getTotal(),
                "recordsFiltered" => $dmnDatos->getTotal(),
                'data' => rspEquipo::parseToArray($dmnDatos->getResults())        
);
echo json_encode($response);
