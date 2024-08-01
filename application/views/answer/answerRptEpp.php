<?php
require_once RESPONSE_PATH.'epp/rspReporteEpp.php';
$dmnArea = $data;
$response = array(                                
                "recordsTotal" => $dmnArea->getTotal(),
                "recordsFiltered" => $dmnArea->getTotal(),
                'data' => rspReporteEpp::parseToArray($dmnArea->getResults())        
);
echo json_encode($response);