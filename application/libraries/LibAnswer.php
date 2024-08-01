<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
 Class BaseAnswer {
    private $data;
    function BaseAnswer($elements,$objects=null,$total=0){
        $this->data=null;
        if(is_array($elements)){
            if($object!=null){
                foreach($object as  $key => $row){
                    foreach($element as $function=>$variable){
                        $results[$key][$variable]=$row->{"get".$function}();
                    }
                }
                $this->data = array(
                                    'success'       => true,
                                    'total'         => $total,
                                    'results'       => $results
		);
             }      
        }
    
    }
    function prepare_answer()
    {
        return $this->data;
    }
 }
?>
