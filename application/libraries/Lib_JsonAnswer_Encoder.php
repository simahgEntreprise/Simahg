<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * This class encode a Lib_Ajax_Answer to JSON.
 *
 *
 * @author Carlos Arana Reategui
 * @version 1.00 , 15 JUL 2009
 *
 */
require_once APPPATH.'libraries/Lib_IAjaxAnswer_Encoder.php';

class  Lib_JsonAnswer_Encoder implements Lib_IAjaxAnswer_Encoder {

/**
 * Basically try to convert the object properties to an array,
 * if the object is nstance of Lib_Base_VO (value object) call
 * the getAsArrayMethod() othwrwise take other default actions to try
 * to convert to array.
 *
 * @param a reference to the extradara contained on the aswer.
 * @access protected
 */
    protected function _processExtraData(&$p_extdata) {
        if (isset ($p_extdata)) {
            $extdata = $p_extdata;
            // IF not an array
            if (!is_array($p_extdata)) {
            // if is an object
                if (is_object($p_extdata)) {
                    if ($p_extdata instanceof Lib_Base_VO) {
                        $p_extdata = $extdata->getAsArray();
                    } else {
                        $p_extdata = (array)$extdata;
                    }
                }
            }
        }
    }

    /**
     * This method generate the ajax answer , the format of the answer in this
     * implementation is in JSON format.
     *
     * The answer will have the format :
     * {success: boolean,
     *  msg: { txt: 'a message string',
     *        code: a numeric identifier of the message (1 means the errors are field errors)
     *  },
     *  errors : [{'field_name': 'flderror'}],
     *  extradata :{no specific specification by default return as array otherwise
     *              override _processExtraData()}
     * }
     *
     * @access public
     * @return An string with the JSON encoded answer.
     *
     *
     */
    public function encode(Lib_Ajax_Answer &$answer) {
        $result = &$answer->getResult();

        if (!isset ($result['errors']) OR count($result['errors']) == 0) {
            $result['errors']['null'] =NULL;
        }
        // Process extra data
        if (isset($result['extdata'])) {
            $this->_processExtraData($result['extdata']);
        }

        // encode in JSON
        $ret =  json_encode($result);

        return $ret;
    }
}
?>