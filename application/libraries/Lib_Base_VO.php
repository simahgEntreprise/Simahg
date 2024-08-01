<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * This is the base Value Object class , it allow that the sub classes can be created
 * as a pure object oriented way with setters and/or getters and also be created as
 * a normal class but with his attributes not directly accessed
 * (using __get and __set magic methods),
 *
 * For Example :
 *
 * <code>
 * class test extends Lib_Base_VO {
 *      protected $_var1;
 *      protected $_var2;
 *
 *      public function setVar1($var1) {
 *          $this->_var1 = $var1;
 *      }
 *
 *      public function getVar2() {
 *          return $this->_var2;
 *      }
 * }
 *
 * $clstest = new test();
 * $clstest->var1 = 'test'; // calls the method setVar1()
 * $clstest->var2 = 'test'; // do a direct assign using magic method __set
 * echo  $clstest->var1; // // returns directly the value of $_var1  using the magic methosd __get
 * echo  $clstest->var2; // // call the method getVar2()
 *
 * </code>
 *
 *  Of course you can use directly the getters or setters if they are defined.
 *
 * @author Carlos Arana Reategui
 * @version 1.00 , 14 JUL 2009
 * @since 1.00
 *
 */
class Lib_Base_VO {

/**
 * Constructor
 * If an array with attr names and values are sended its used
 * for initialize the class attributes.
 *
 * IMPORTANT : each attr name need to be declared protected in the subclass
 * and need to have an underscore prepended , like $_myvar
 *
 * For example if the array is ('field1'=>'val1','field2'=>'val2') , the subclas
 * need to have the protected #_field1 and $_field2 members.
 *
 * @access public
 * @return void
 *
 */
	protected $RNUM;
	
    public function __construct(array $options = null) {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }

    /**
     * Override of the magic method __get
     *
     * First try to call a method based in the param 'name' , if the method doesnt
     * exist try to set the attribute identified by the param 'name' directly.
     *
     * IMPORTANT : The setter methods if they are created in the subclass need to be
     * with camel case , for example $this->attr = 'test' will try to call a setter
     * setAttr.
     * If the method doesnt exist will try to access the attribute _attr. , the attributes
     * need to be protected.
     *
     *
     * @access public
     * @param name string with the attribute name (without the prepended underscore)..
     * @param value mixed , the value to set in the attribute
     * @return void
     *
     */
    public function __set($name, $value) {

        $method = 'set' . ucfirst($name);
        log_message('info', 'The method __set called for '.$method);
        if (!method_exists($this, $method)) {
            $attr = "_$name";
            $this->$attr=$value;
        } else {
            $this->$method($value);
        }
    }

    /**
     * Override of the magic method __set
     *
     * First try to call a method based in the param 'name' , if the method doesnt
     * exist try to get the attribute identified by the param 'name' directly.
     *
     * IMPORTANT : The getters methods if they are created in the subclass need to be
     * with camel case , for example echo $this->attr will try to call a getter
     * getAttr.
     * If the method doesnt exist will try to access the attribute _attr. , the attributes
     * need to be protected.
     *
     * @access public
     * @param name string with the attribute name (without the prepended underscore)..
     * @param value mixed , the value to set in the attribute
     * @return void
     *
     */
    public function __get($name) {
        $method = 'get' . ucfirst($name);
        log_message('info', 'The method __get called for '.$method);
        if (!method_exists($this, $method)) {
            $attr = "_$name";
            return $this->$attr;
        }
        return $this->$method();
    }

    /**
     * This method allows to setup the attributes from an array.
     *
     * First for each element in the array try to call a method based in the param 'name' ,
     * if the method doesnt exist try to set the attribute identified by the param 'name'
     * directly.
     *
     * IMPORTANT : The setter methods if they are created in the subclass need to be
     * with camel case , for example $this->attr = 'test' will try to call a setter
     * setAttr.
     * If the method doesnt exist will try to access the attribute _attr. , the attributes
     * need to be protected.
     *
     * @access public
     * @param options an array with elements containig the attr->value pair.
     * @return Instance of the class
     *
     */
    public function setOptions(array $options) {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            } else {
                //$attr = "_$key"; //Modificado por MAQC
                $attr = "$key";
                $this->$attr=$value;
            }
        }
        return $this;
    }

    /**
     * This method returns all the properties as an array
     * Can be overriden for specific uses.
     *
     * @return an array with the properties.
     */
  public function getAsArray() {
        $serial = serialize( $this ) ;
        $serial = preg_replace( '/O:\d+:".+?"/' ,'a' , $serial ) ;
        if( preg_match_all( '/s:\d+:"\\0.+?\\0(.+?)"/' , $serial, $ms, PREG_SET_ORDER )) {
            foreach( $ms as $m ) {
                $serial = str_replace( $m[0], 's:'. strlen( $m[1] ) . ':"'.$m[1] . '"', $serial ) ;
            }
        }
        $array = @unserialize( $serial ) ;
        unset($array['RNUM']);

        //*----agregadoo
        if(count($array) == 1){
             foreach ($array as $data){
                 $array = $data;
             }
        }
        //------------end agregado---
        return  $array;
    }

}
/* End of file Lib_Base_VO.php */
/* Location: ./system/application/libraries/Lib_Base_VO.php */