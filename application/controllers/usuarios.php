<?php
/**
 * Created by PhpStorm.
 * User: caio
 * Date: 27/04/2016
 * Time: 17:08
 */
class Usuarios extends  CI_Controller{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('sistema');
        init_painel();
    }

    public function index(){
        $this->load->view('');
    }

    //valida o login
    public function valida(){
        $this->form_validation->set_rules('usuario','USUÁRIO','trim|required|min_length[4]|strtolower');
        $this->form_validation->set_rules('senha','SENHA','trim|required|min_length[4]|strtolower');
        if($this->form_validation->run()):
            $usuario = $this->input->post('usuario',true);
            $senha  = md5($this->input->post('senha',true));
            if($this->usuarios->do_login($usuario,$senha)):
                $query = $this->usuarios->get_bylogin($usuario)->row();
                $dados = array(
                    'user_id'=> $query->id,
                    'user_nome'=>$query->nome,
                    'user_adm'=>$query->adm,
                    'user_logado'=>TRUE
                );
                $this->session->set_userdata($dados);
                redirect('painel');
            else:
                echo 'Login falhou';
            endif;
        endif;


    }

    public function logar(){
        set_tema('titulo','Login');
        set_tema('conteudo',load_modulo('usuarios','login'));
        load_template();
    }

    //carregar o modulo usuarios e mostrar a tela de login
    public function login(){
        if(esta_logado(false)):
            redirect('painel');
        else:
            $this->logar();
        endif;
    }
    
    public function deslogar(){
        logout();
        redirect('usuarios/login');
    }



}