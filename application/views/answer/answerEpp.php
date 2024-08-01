<?php
require_once RESPONSE_PATH.'epp/rspEpp.php';
$dmnEpp = $data;
$response = array(                                
                "recordsTotal" => $dmnEpp->getTotal(),
                "recordsFiltered" => $dmnEpp->getTotal(),
                'data' => rspEpp::parseToArray($dmnEpp->getResults())        
);
echo json_encode($response);
