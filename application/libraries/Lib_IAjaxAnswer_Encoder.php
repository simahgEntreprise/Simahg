<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * This interface define the prototype for an Ajax answer encoder.
 *
 *
 * @author Carlos Arana Reategui
 * @version 1.00 , 15 JUL 2009
 *
 */
interface  Lib_IAjaxAnswer_Encoder {

    /**
     * This method need to be implemented to encode an answer to JSON,XML or
     * other formats necessary for return something that the client side undestand.
     *
     * @@param answer a reference to the Lib_Ajax_Answer object to encode.
     * @return Mixed with the encoded result.
     *
     */
    public function encode(Lib_Ajax_Answer &$answer);


}
