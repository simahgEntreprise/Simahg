<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * This class allow the creation of an ajax message to be returned in case we need to
 * send an error from the server, the answer its returned in JSON.
 *
 * This class doesnt use the MY_ convention because its loaded using the PHP5
 * autoload feature , the code its appended to application config.php as
 * function __autoload($class)
 * {
 *    if(strstr($class,'Lib_') && strstr($class,'_Answer'))
 *    {
 *       include_once(APPPATH . 'libraries/' . $class . EXT);
 *    }
 * }
 *
 * NOTES : Only for PHP5
 *
 * @author Carlos Arana Reategui
 * @version 1.00 , 15 JUL 2009
 *
 */
class Lib_Ajax_Answer {

    private $_result = array();

    /**
     * Constructor
     * Preloa the text heper
     */
    function  __construct() {
        $CI =& get_instance();
        $CI->load->helper('text');
    }

    /**
     * Set if the answer it successful or not.
     *
     * @access public
     * @param $success , a boolean TRUE if it is successful.
     * @return the class instance. (to chaining call)
     */
    public function setSuccess($success) {
        $this->_result['success'] = is_bool($success)  ? $success : FALSE;
        return $this;
    }

    /**
     * Set the message to display on the client side.
     *
     * @access public
     * @param $msg , a string with the message.
     * @return the class instance. (to chaining call)
     */
    public function setMessage($msg) {
        $this->_result['msg']['txt'] = ascii_to_entities($msg);
        return $this;

    }

    /**
     * Set the error code to be used by the the client side.
     *
     * @access public
     * @param $code , a integer value.
     * @return the class instance. (to chaining call)
     */
    public function setCode($code) {
        $this->_result['msg']['code'] = $code;
        return $this;
    }

    /**
     * Add an error field to the list of field errors.
     *
     * @access public
     * @param $field , the field name , normall will be equal fo the form field
     * on the client side.
     * @param $fldmsg , the localized field mesage to display.
     * @return TRUE if its added otherwise FALSE.
     */
    public function addFieldError($field,$fldmsg) {
    // only if field and message are defned
        if (isset ($field) && strlen($field) > 0) {
            if (isset ($fldmsg) && strlen($fldmsg) > 0) {
                if (!isset ($this->_result['errors']) OR count($this->_result['errors']) == 0)
                    $this->setCode(1);
                $this->_result['errors'][$field] =ascii_to_entities($fldmsg);
                return TRUE;
            }
        }
        return FALSE;
    }

    /**
     * This method save any extra data to be pocessed later and that is required
     * by the client side as part of the answer.
     *
     * @access public
     * @param $extdata , a mixed value with the extra data
     * @return the class instance. (to chaining call)
     */
    public function setExtraData($extdata) {
        if (isset ($extdata)) {
            $this->_result['extdata'] = $extdata;
        }
        return $this;
    }

    /**
     * This method save any extra data to be pocessed later and that is required
     * by the client side as part of the answer.
     *
     * @access public
     * @param $extdata , a mixed value with the extra data
     * @return the class instance. (to chaining call)
     */
    public function setExtraValue($name,$value) {
        if (isset ($name) and isset($value)) {
            $this->_result['msg'][$name] = $value;
        }
        return $this;
    }
    /**
     * Return a reference to the results array , basically will be used
     * by the encoders.
     *
     * @return The results array (reference)
     */
    public function &getResult() {
        return $this->_result;
    }

    
    function getAnswer($obj = null,$total = 0){
        $results = array();

        if(is_object($obj)){
            if($obj > 0){
                if(count($obj) > 0){

                    foreach($obj as $key => $row){
                        $results[] = $row;
                    }// end for
                    $data = array('success'=>true,'total'=>$total,'results'=>$results);
                    echo json_encode($data);
                }else{

                   $data = array('success'=>true,'total'=>$total,'results'=>array());
                   echo json_encode($data);
                }
            }else{
                 $data = array('success'=>true,'total'=>$total,'results'=>array());
                 echo json_encode($data);
             }
        }elseif(is_array($obj)){
            foreach($obj as $key => $row){
                $results[] = (array)$row;
            }// end for
            //print_r($results);exit();
            $data = array('success'=>true,'total'=>$total,'results'=>$results);
            echo json_encode($data);
        }

        



    }

    function getAnswerError(array $msg){
        $data = array('success'=>false,'msg'=>array('txt'=>'---','code'=>1),'errors'=>($msg) );
        echo json_encode($data);
    }
    function getAnswerMsg($msg,$success = false){
        $data = array('success'=>$success,'msg'=>array('txt'=>$msg,'code'=>-1) );
        echo json_encode($data);
    }

}

/* End of file Lib_Ajax_Answer.php */
/* Location: ./system/application/libraries/Lib_Ajax_Answer.php */
