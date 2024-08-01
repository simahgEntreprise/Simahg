<?php
require_once RESPONSE_PATH.'conf/rspArea.php';
require_once RESPONSE_PATH.'conf/rspTiempoMant.php'; 
require_once RESPONSE_PATH.'proceso/rspItem.php';

$dmnItem = $data;
$response = array(                                
                "recordsTotal" => $dmnItem->getTotal(),
                "recordsFiltered" => $dmnItem->getTotal(),
                'data' => rspItem::parseToArray($dmnItem->getResults())
        
);
echo json_encode($response);
