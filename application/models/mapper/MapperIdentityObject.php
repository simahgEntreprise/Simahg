<?php

class MapperIdentityObject {

    protected $currentfield = null;
    protected $fields = array();
    protected $table = null;
    private   $and = null;
    private   $enforce = array();
    protected $mapper = null;

//CHAPTER 13 ■ DATABASE PATTERNS 308// an identity object can start off empty, or with a field
    function __construct($field=null, array $enforce=null) {
        if (!is_null($enforce)) {
            $this->enforce = $enforce;
        }
        if (!is_null($field)) {
            $this->field($field);
        }
    }
    function loadMapper($path,$className){
        require_once MAPPER_PATH.$path.'.php';
        $this->mapper = new $className.'()';
    }

// field names to which this is constrained
    function getObjectFields() {
        return $this->enforce;
    }

// kick off a new field.
// will throw an error if a current field is not complete
// (ie age rather than age > 40)
// this method returns a reference to the current object
// allowing for fluent syntax
    function field($fieldname) {
        if (!$this->isVoid() && $this->currentfield->isIncomplete()) {
            throw new Exception("Incomplete field");
        }
        $this->enforceField($fieldname);
        if (isset($this->fields[$fieldname])) {
            $this->currentfield = $this->fields[$fieldname];
        } else {
            $this->currentfield = new woo_Mapper_Field($fieldname);
            $this->fields[$fieldname] = $this->currentfield;
        }
        return $this;
    }

// does the identity object have any fields yet
    function isVoid() {
        return empty($this->fields);
    }

// is the given fieldname legal?
    function enforceField($fieldname) {
        if (!in_array($fieldname, $this->enforce) &&
                !empty($this->enforce)) {
            $forcelist = implode(', ', $this->enforce);
            throw new Exception("{$fieldname} not a legal field ($forcelist)");
        }
    }

//CHAPTER 13 ■ DATABASE PATTERNS 309// add an equality operator to the current field
// ie 'age' becomes age=40
// returns a reference to the current object (via operator())
    function eq($value) {
        return $this->operator("=", $value);
    }

// less than
    function lt($value) {
        return $this->operator("<", $value);
    }

// greater than
    function gt($value) {
        return $this->operator(">", $value);
    }

// does the work for the operator methods
// gets the current field and adds the operator and test value
// to it
    private function operator($symbol, $value) {
        if ($this->isVoid()) {
            throw new Exception("no object field defined");
        }
        $this->currentfield->addTest($symbol, $value);
        return $this;
    }

// return all comparisons built up so far in an associative array
    function getComps() {
        $ret = array();
        foreach ($this->fields as $key => $field) {
            $ret = array_merge($ret, $field->getComps());
        }
        return $ret;
    }    
    //
    function exec(){
       $results = array();
       $this->db->select($this->mapper->fields);
       $this->db->from($this->table);
       $this->setConstraints();
       $res = $this->db->get();
       if($res->num_rows() > 0){
           foreach($res->result_array() as $row){
               $row = array_change_key_case($row, CASE_LOWER);
               $results[] = $this->mapper->doCreateObject($row);
           }
       }
       return $results;
    }
    //
    private function setConstraints(){
        foreach ($this->fields as $key => $field) {
            //$ret = array_merge($ret, $field->getComps());
            foreach($field->getCompos() as $test){
                switch($test['operator']){
                    case ($test['operator'] == '=' || $test['operator'] == '!='):
                        $this->db->where($field.$test['operator'],$test['value']);
                        break;
                    case 'like':
                        $this->db->like($field,$test['value']);
                        break;                    
                }                
            }
        }
    }

}
?>