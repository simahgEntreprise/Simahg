<?php
require_once RESPONSE_PATH.'conf/rspGrupo.php';
$dmnGrupo = $data;
$response = array(                                
                "recordsTotal" => $dmnGrupo->getTotal(),
                "recordsFiltered" => $dmnGrupo->getTotal(),
                'data' => rspGrupo::parseToArray($dmnGrupo->getResults())        
);
echo json_encode($response);
