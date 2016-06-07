<?php
/**
 * Created by PhpStorm.
 * User: caio
 * Date: 27/04/2016
 * Time: 17:21
 */

//carrega um modulo do sistema devolvendo a tela solicitada
function load_modulo($modulo=NULL,$tela=NULL,$diretorio='painel'){
    $CI =& get_instance();
    if($modulo != null):
        return $CI->load->view("$diretorio/$modulo",array('tela'=> $tela),true);
     else:
         return false;
     endif;
}

//seta valores ao array $tema da classe sistema
function set_tema($prop,$valor,$replace=TRUE){
    $CI =& get_instance();
    $CI->load->library('sistema');
    if($replace):
        $CI->sistema->tema[$prop] = $valor;
    else:
        if(!isset($CI->sistema->tema[$prop]))$CI->sistema->tema[$prop] = '' ;
        $CI->sistema->tema[$prop] .= $valor;
    endif;
}

//retorna os valores do array $tema da classe sistema
function get_tema(){
    $CI =& get_instance();
    $CI->load->library('sistema');
    return $CI->sistema->tema;

}

//inicializa o painel adm carregando os recursos necessarios

function init_painel(){
    $CI =& get_instance();
    $CI->load->library(array('sistema','session','form_validation'));
    $CI->load->helper(array('form','url','array','text'));
    $CI->load->model('usuarios_model','usuarios');

    //carregamento dos models
    set_tema('titulo_padrao','Painel Adm');
    
    set_tema('rodape','');
    set_tema('template','painel_view');
    set_tema('headerinc',load_css(array('foundation.min',
                                        'app',)),FALSE);
    set_tema('footerinc',load_js(array(
        'jquery-2.2.4',
        'foundation.min',
        'app',
    )),FALSE);
}

//carrega um template passando o array $tema como parametro
function load_template()
{
    $CI =& get_instance();
    $CI->load->library('sistema');
    $CI->parser->parse($CI->sistema->tema['template'],get_tema());
}

//carrega um ou varios arquivos css de uma pasta

function load_css($arquivo=NULL,$pasta='css',$midia='all'){
    if($arquivo!= null):
        $CI =& get_instance();
        $CI->load->helper('url');
        $retorno ='';
        if(is_array($arquivo)):
            foreach ($arquivo as $css):
                $retorno .= '<link rel="stylesheet" type="text/css" href="'.base_url("$pasta/$css.css").'"media="'.$midia.'"/>';
            endforeach;
        else:
            $retorno = '<link rel="stylesheet" type="text/css" href="'.base_url("$pasta/$arquivo.css").'"media="'.$midia.'"/>';
        endif;
    endif;
    return $retorno;

}

//carrega um ou varios arquivos .js de uma pasta ou servidor remoto
function load_js($arquivo=NULL,$pasta='js',$remoto=FALSE){
    if($arquivo!= null):
        $CI =& get_instance();
        $CI->load->helper('url');
        $retorno ='';
        if(is_array($arquivo)):
            foreach ($arquivo as $js):
                $retorno .= set_js($remoto,$js,$pasta);
            endforeach;
        else:
            $retorno = set_js($remoto,$arquivo,$pasta);
        endif;
    endif;
    return $retorno;
}

function set_js($remoto=FALSE,$arquivo=NULL,$pasta){
    if($remoto):
        return '<script type="text/javascript" src="'.$arquivo.'"></script';
    else:
        return '<script type="text/javascript" src="'.base_url("$pasta/$arquivo.js").'"></script>';
    endif;

}

//mostra erros de validação em forms
function erros_validacao(){
    if(validation_errors()) echo '<div class="alert callout">'.validation_errors('<p>','</p>').'</div>';
}

//verifica se o usuario está logado no sistema
function esta_logado($redir=true){
    $CI =& get_instance();
    $CI->load->library('session');
    $user_status = $CI->session->userdata('user_logado');
    if(!isset($user_status) || $user_status !=TRUE):
       // $CI->session->sess_destroy();
        if($redir):
            redirect('usuarios/login');
        else:
          return  FALSE;
        endif;
    else:
        return TRUE;
    endif;
}

function logout(){
    $CI =& get_instance();
    $CI->load->library('session');
    $user_status = $CI->session->userdata('user_logado');
    if(isset($user_status) || $user_status ==TRUE):
        $CI->session->sess_destroy();
    endif;
}