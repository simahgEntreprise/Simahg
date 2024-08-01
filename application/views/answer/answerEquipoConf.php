<?php
require_once RESPONSE_PATH.'conf/rspEquipoConf.php';
$dmnEquipo = $data;
$response = array(                                
                "recordsTotal" => $dmnEquipo->getTotal(),
                "recordsFiltered" => $dmnEquipo->getTotal(),
                'data' => rspEquipoConf::parseToArray($dmnEquipo->getResults())        
);
echo json_encode($response);
