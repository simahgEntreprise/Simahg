<?php
require_once RESPONSE_PATH.'epp/rspProyecto.php';
$dmn = $data;
$response = array(                                
                "recordsTotal" => $dmn->getTotal(),
                "recordsFiltered" => $dmn->getTotal(),
                'data' => rspProyecto::parseToArray($dmn->getResults())        
);
echo json_encode($response);
