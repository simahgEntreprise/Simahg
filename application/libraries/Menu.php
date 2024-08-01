<?php
class Menu {
    private $ci;     
    function __construct()
    {
        $this->ci =& get_instance();    // get a reference to CodeIgniter.
    }
    public function getMenu($perfil){        
        
        $query = $this->ci->db->query("select * from t_perfil_Acceso a inner join t_acceso b on a.idacceso = b.id where idperfil =".$perfil." order by idapp, idopc");                
        $html = "<ul class='nav' id='side-menu'>";
        $prin='';        
        foreach ($query->result() as $row) {                   
            if($prin!=='' && $prin!==$row->idapp ){                
                        $html.='</ul></li>';
            }
            if ($row->idopc =='0'){
                $html.='<li><a href="#"><i class="fa fa-sitemap fa-fw"></i>'.$row->nombre.'<span class="fa arrow"></span></a>'.
                            '<ul class="nav nav-second-level">';
            }else{
                $html.='<li><a href="'.base_url()."index.php/". $row->ruta .'">'.$row->nombre.'</a></li>';
            }            
            $prin= $row->idapp;
        }
        $html.='</ul></li><li><a href="'.base_url()."index.php/login/logout_ci".'">CERRAR SESION</a></li></ul>';
        return $html;
    }
}