<?php
/**
 * Created by PhpStorm.
 * User: caio
 * Date: 27/04/2016
 * Time: 16:53
 */

class Painel extends  CI_Controller{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('sistema');
        init_painel();
    }

    public function index(){
        $this->inicio();
    }

    public function inicio(){
        if(esta_logado(false)):
            set_tema('titulo','Inicio');
            set_tema('rodape','<p>&copy; 2016 | todos os direitos reservados para Caio Martins</p>');
            set_tema('conteudo','<div class="columns medium-8 medium-centered"><p>Escolha um menu para iniciar</p></div>');
            load_template();
        else:
        set_msg('errologin','Acesso restrito faça login antes de prosseguir','erro');
        redirect('usuarios/login');
        endif;
    }
}