<?php
require_once("core/mapper/Collections.php");
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class core_domain_HelperFactory {
    static function getCollection($type){
        $type = preg_replace( "/^.*_/", "", $type );
        $collection = "core_mapper_{$type}Collection";
        if ( class_exists( $collection ) ) {
            return new $collection();
        }
//        throw new woo_base_AppException( "Unknown: $collection" );
    }
}

?>
