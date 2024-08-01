<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
abstract class BaseResponse {
    public static function handler($instance,$anyValue){                
        if(is_array($anyValue)){
            $results = array();
            foreach ($anyValue as $row){
                //call_u                
                $results[] = call_user_func(array($instance,'parse'),$row );
//                $results[] = call_user_func($instance .'::parse',$row);
                //::parse($row);
            }
            return $results;
        }
        if(is_object($anyValue)){
            return call_user_func(array($instance,'parse'),$anyValue );
        }
    }
    public static function check($domain,$anyValue){
        if(!($anyValue instanceof  $domain)){
            throw new Exception('No se ah Ingresado una instancia de la clase: '.$domain);
        }
    }
    public abstract function parse($anyValue);
//    protected  static function parse($anyValue);
}
