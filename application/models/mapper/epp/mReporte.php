<?php
require_once APPPATH.'models/BaseModel.php';
require_once DOMAIN_PATH.'epp/dmnReporteEpp.php';
class mReporte extends BaseModel{
    protected  $fields = "b.nombre,b.apellidos, d.factor, d.descripcion,e.nombre as tipo,a.fechaingreso,a.fechafin, f.nombre as proyecto, g.nombre+' '+g.apellidos as lider, f.responsable, f.id as idProyecto, g.id as idLider, h.nombre as  cargotrab, b.dni as dnitrab";
    public function  __construct() {
        parent::__construct();
    }

    public function finder(array $constraints){
        $this->load->database();
        $this->db->select($this->fields);
        $this->db->join('t_trabajador b ',' a.idtrabajador = b.id');
        $this->db->join('t_cargo h ',' b.idCargo = h.id ');
        $this->db->join('t_epp c ','a.idepp = c.id');
        $this->db->join('t_riesgo d ','c.idRiesgo = d.id');
        $this->db->join('t_tipoepp e ','c.tipo = e.id');
        $this->db->join('t_proyecto f ','f.id = b.idproyecto', 'LEFT');
        $this->db->join('t_trabajador g ','g.id = f.idlider', 'LEFT');
                       
        if(isset($constraints['id']) && strlen(trim($constraints['id'])) > 0){
            $this->db->where('f.idlider',(int)$constraints['id']);
        }        
        $this->db->order_by('a.fechafin','ASC');
        $res = $this->db->get('t_asignacionepp a');
//        print_r($this->db->last_query());exit;
        if($res == null){
            throw new Exception('Error en MapperItem'.$this->db->last_query(),-1);
        }
        return $this->paginate($res);
    }

    public function doCreateObject(array $fields){        
        $dmnReporteEpp = new dmnReporteEpp();
        $dmnReporteEpp->setNombre($fields['nombre']);
        $dmnReporteEpp->setApellido($fields['apellidos']);
        $dmnReporteEpp->setFactor($fields['factor']);
        $dmnReporteEpp->setDescripcion($fields['descripcion']);
        $dmnReporteEpp->setTipo($fields['tipo']);
        $dmnReporteEpp->setFecingreso($fields['fechaingreso']);
        $dmnReporteEpp->setFecfin($fields['fechafin']);
        $dmnReporteEpp->setResponsable($fields['responsable']);
        $dmnReporteEpp->setLider($fields['lider']);
        $dmnReporteEpp->setProyecto($fields['proyecto']);
        $dmnReporteEpp->setIdProyecto($fields['idproyecto']);
        $dmnReporteEpp->setIdLider($fields['idlider']);
        $dmnReporteEpp->setCargoTrab($fields['cargotrab']);
        $dmnReporteEpp->setDniTrab($fields['dnitrab']);
        return $dmnReporteEpp;
    }
    
}


