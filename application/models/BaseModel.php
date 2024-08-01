<?php
require_once SYSDIR.'/libraries/Model.php';
require_once APPPATH.'models/Response.php';
//require_once APPPATH.'models/process/date_model.php';
//require_once APPPATH.'controllers/process/date_convert.php';
//
class BaseModel extends Model
{

    protected $fields;
    private $query = array();
    private  $limits = array();
    protected $dateModel;
    
    function  __construct() {
        parent::Model();
//        $this->connectSource();
//        $this->dateModel = new date_model($this->db);
    }
    function addFields($fields){
        if(strlen($fields) > 0){
            $this->fields.= ','.$fields;
        }
    }

    function verifyConstraints(array $toVerify,$constraints){
        foreach($toVerify as $key => $row){
            if(in_array($key, $constraints)){
                return true;
            }
        }
        return false;
    }

    


    public function setLimits(array $limits = null){        
        $this->limits = $limits;
    }
    protected function initialice(){
        $this->connectSource();
        $this->query = array();
        $this->dateModel = new date_model($this->db);
    }
    protected function getQuery(){
        return $this->query;
    }

    function connectSource(){
        $this->load->database();
        if(!is_resource($this->db->conn_id) && !is_object($this->db->conn_id)){
                throw new Exception('?',DB_ERR_SERVERNOTFOUND);
        }
    }
    protected function select($fields){
        $this->query['select'][] = $fields;
        $this->db->select($fields);
    }
    protected function from($table){
        $this->query['from'] = $table;
        $this->db->from($table);
    }
    //
    protected function setConstraints(array $constraints = null){
//        print_r($constraints);
        if(isset($constraints['equal'])){
            foreach($constraints['equal'] as $condition){                
                $this->where($condition);
            }
        }
        if(isset($constraints['equal_in'])){
            foreach($constraints['equal_in'] as $condition){
                $this->where_in($condition);
            }
        }
        if(isset($constraints['like'])){
            foreach($constraints['like'] as $conditions){
                $this->like($conditions);
            }
        }
    }
    protected function like($conditions){
        $this->query['constraints']['like'][] = $conditions;
        $this->_like($conditions);
    }
    protected function _like($conditions){
        foreach($conditions as $field => $value){
            $this->db->like($field,$value);
        }
    }
    protected function _setConstraints(array $constraints = null){
        
        if(isset($constraints['where'])){
            foreach($constraints['where'] as $condition){
                $this->_where($condition);
            }
        }
        if(isset($constraints['where_in'])){
            foreach($constraints['where_in'] as $condition){
                $this->_where_in($condition);
            }
        }
        if(isset($constraints['like'])){
            foreach($constraints['like'] as $conditions){
                $this->_like($conditions);
            }
        }
    }
    public function limits(){
        if(count($this->limits)> 0){
            $this->db->limit($this->limits['limit'] ,$this->limits['start']);
        }
    }
    //
    public function where(array $conditions = null){
        $this->query['constraints']['where'][] = $conditions;
        $this->_where($conditions);
    }
    public function where_in(array $conditions = null){
        $this->query['constraints']['where_in'][] = $conditions;
        $this->_where_in($conditions);
    }
    public function _where_in(array $conditions = null){
        foreach($conditions as $field => $values){
            $this->db->where_in($field,$values);
        }
    }
    //
    public function _where(array $conditions = null){
        foreach($conditions as $field => $value){
            $dateValue = date_parse($value);            
            if($dateValue == FALSE || $dateValue['error_count'] > 0 || checkdate($dateValue['month'],$dateValue['day'],$dateValue['year']) == FALSE ){
                $this->db->where($field,$value);
            }else{                
                $this->db->where($field,$this->dateModel->date_server(date_convert::converttoserver($value)));
            }
        }
    }   
    //
    
    //
    protected function join($table,$condition,$type = ''){
        $this->query['join'][] = array('table' => $table,'condition' => $condition,'type' => $type);
        if($type != ''){            
            $this->db->join($table,$condition,$type);
        }else{
            $this->db->join($table,$condition);
        }        
    }
    protected function _join(array $join = null){
        foreach($join as $row){
            if ($row['type'] != ''){
                $this->db->join($row['table'],$row['condition'],$row['type']);
            }else{
                $this->db->join($row['table'],$row['condition']);
            }
             
        }
    }
    protected function group_by($fields){
        $this->query['group_by'][] = $fields;
        $this->db->group_by($fields);
    }    
    //Funcion que retorna la cantidad del ultimo select sin considerar los querys de limit
    protected function getCountQuery(){
        $quantity = 0;        
        unset($this->query['select']);        
        $this->db->select('count(1) as "rowcount"');
        
        //Setting the from 
        $this->db->from($this->query['from']);
        if(isset($this->query['join'])){
            $this->_join($this->query['join']);
        }        
        $this->_setConstraints($this->query['constraints']);
        //Setting the group by
        if(isset($this->query['group_by'])){
            $this->db->group_by($this->query['group_by']);
        }
        $res = $this->db->get();
        

        if(isset($this->query['group_by'])){
            $quantity = $res->num_rows();
        }else{
            $quantity = $res->row()->rowcount;
        }        
        return $quantity;
    }
    //
    public function get(){        
        $results = array();
        $results['results'] = array();
        $response = $this->db->get();        
        $results['results'] = $response;
        $results['count'] = $this->getCountQuery();        
        return $results;                
    }
    protected function genResponse($response,array $results){
        $resObject = array();
        $resObject['count'] = $response['count'];
        $resObject['results'] = $results;
        return $resObject;
    }

    //
    public function getClassInformation(){
        $classInformation = new ReflectionClass(get_class($this));
    }
    //
    public function createObject($res){
        if($res->num_rows() == 0){return null;}
        if($res->num_rows() == 1){            
            return $this->doCreateObject(array_change_key_case($res->row_array(), CASE_LOWER));
        }
    }
    public function selDato($tabla,$field,$where = null){
        $this->db->select($field.'  as  "record"');
        if($where != null){
            if(is_object($where)){
                $this->db->where($where->getAsArray());
            }else{
                $this->db->where($where);
            }
        }
        $sql = $this->db->get($tabla);
//        print_r($sql);exit();
        if($sql->num_rows() > 0){
            return $sql->row()->record;
        }else{
            return 0;
        }

    }
    public function paginate($res){        
        $response = new Response();        
        $results = array();        
        $limit = $res->num_rows();
        for($i = 0;$i<$limit;$i++){            
            $row = array_change_key_case($res->row_array($i),CASE_LOWER);
            if($row == NULL){
                $response->setResults($results);
                $response->setTotal($res->num_rows());
                return $response;
            }
            $results[] = $this->doCreateObject($row);
        }
        
        $response->setResults($results);
        $response->setTotal($res->num_rows());

        //print_r($results);exit;
        return $response;
    }
    function  __destruct() {
//        $this->db->close();
    }
}
?>