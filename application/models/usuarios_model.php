<?php
/**
 * Created by PhpStorm.
 * User: caio
 * Date: 29/04/2016
 * Time: 10:45
 */
class Usuarios_model extends CI_Model{

public function do_login($usuario=NULL,$senha=NULL){

    if($usuario != null && $senha!=null):
        $this->db->where('login',$usuario);
        $this->db->where('senha',$senha);
        $this->db->where('ativo',1);
        $query = $this->db->get('usuarios');
        return $query->num_rows()==1;
    else:
       return false;
    endif;
}

    public function get_bylogin($login=NULL){
        if($login != null):
            $this->db->where('login',$login);
            $this->db->limit(1);
            return $this->db->get('usuarios');
        else:
            return false;
         endif;
    }


}
