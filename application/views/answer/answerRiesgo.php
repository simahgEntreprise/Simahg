<?php
require_once RESPONSE_PATH.'epp/rspRiesgo.php';
$dmnRiesgo = $data;
$response = array(                                
                "recordsTotal" => $dmnRiesgo->getTotal(),
                "recordsFiltered" => $dmnRiesgo->getTotal(),
                'data' => rspRiesgo::parseToArray($dmnRiesgo->getResults())        
);
echo json_encode($response);
