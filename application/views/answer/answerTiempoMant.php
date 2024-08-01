<?php
require_once RESPONSE_PATH.'conf/rspTiempoMant.php';
$dmnTiempoMant = $data;
$response = array(                                
                "recordsTotal" => $dmnTiempoMant->getTotal(),
                "recordsFiltered" => $dmnTiempoMant->getTotal(),
                'data' => rspTiempoMant::parseToArray($dmnTiempoMant->getResults())        
);
echo json_encode($response);

