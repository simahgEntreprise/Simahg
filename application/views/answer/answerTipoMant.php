<?php
require_once RESPONSE_PATH.'conf/rspTipoMant.php';
$dmnDatos = $data;
$response = array(                                
                "recordsTotal" => $dmnDatos->getTotal(),
                "recordsFiltered" => $dmnDatos->getTotal(),
                'data' => rspTipoMant::parseToArray($dmnDatos->getResults())        
);
echo json_encode($response);
