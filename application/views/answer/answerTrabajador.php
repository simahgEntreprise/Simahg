<?php
require_once RESPONSE_PATH.'epp/rspTrabajador.php';
$dmn = $data;
$response = array(                                
                "recordsTotal" => $dmn->getTotal(),
                "recordsFiltered" => $dmn->getTotal(),
                'data' => rspTrabajador::parseToArray($dmn->getResults())        
);
echo json_encode($response);
