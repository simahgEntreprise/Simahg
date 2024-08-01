<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Response{
    protected $results;
    protected $total;
    public function getResults() {
        return $this->results;
    }

    public function setResults($results) {
        $this->results = $results;
    }

    public function getTotal() {
        return $this->total;
    }

    public function setTotal($total) {
        $this->total = $total;
    }
    


}
?>
