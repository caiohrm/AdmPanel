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
        if($redir):
            set_msg('errologin','Acesso restrito faça login antes de prosseguir','erro');
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

//define uma mensagem para ser exibida na proxima tela
function set_msg($id='msgerro',$msg=NULL,$tipo='erro'){
    $CI =& get_instance();
    switch ($tipo){
        case 'erro':
            $CI->session->set_flashdata($id,'<div class="alert callout"><p>'.$msg.'</p></div>');
            break;
        case 'success':
            $CI->session->set_flashdata($id,'<div class="success callout">'.$msg.'</div>');
            break;
        default:
            $CI->session->set_flashdata($id,'<div class="alert-box"><p>'.$msg.'</p></div>');
            break;

    }
}

//verifica se existe uma mensagem para ser exibida na tela atual
function get_msg($id,$printar=TRUE){
    $CI =& get_instance();
    if($CI->session->flashdata($id)){

        if($printar){
            echo $CI->session->flashdata($id);
            return true;
        }else{
            return $CI->session->flashdata($id);
        }
    }
   // echo $_SESSION[$id];
    return false;
}

function is_admin($set_msg=FALSE){
    $CI =& get_instance();
    $user_admin = $CI->session->userdata('user_admin');
    if(!isset($user_admin) || $user_admin != true){
        if($set_msg)
            set_msg('msg_erro','Seu usuário não tem permissão para executar essa operação','erro');
        return false;
    }
    return true;

}