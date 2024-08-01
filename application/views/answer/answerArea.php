<?php
require_once RESPONSE_PATH.'conf/rspArea.php';
$dmnArea = $data;
$response = array(                                
                "recordsTotal" => $dmnArea->getTotal(),
                "recordsFiltered" => $dmnArea->getTotal(),
                'data' => rspArea::parseToArray($dmnArea->getResults())        
);
echo json_encode($response);
