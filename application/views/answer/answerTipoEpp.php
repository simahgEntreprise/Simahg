<?php
require_once RESPONSE_PATH.'epp/rspTipoEpp.php';
$dmnTipoepp = $data;
$response = array(                                
                "recordsTotal" => $dmnTipoepp->getTotal(),
                "recordsFiltered" => $dmnTipoepp->getTotal(),
                'data' => rspTipoEpp::parseToArray($dmnTipoepp->getResults())        
);
echo json_encode($response);