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
                set_msg('errologin','Usuário/Senha incorreto','erro');
                redirect('usuarios/login');
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
            $this->session->sess_destroy();
        else:
            $this->logar();
        endif;
    }

    public function nova_senha()
    {
        $this->form_validation->set_rules('email','E-MAIL','trim|required|valid_email|strtolower');
        if($this->form_validation->run()) {
            $email = $this->input->post('email', true);
            $query = $this->usuarios->get_byemail($email);
            if($query->num_rows()==1)
            {
                $novasenha= substr(str_shuffle('qwertyuiopasdfghjklzxcvbnm1234567890'),0,6);
                $mensagem= "<p>Você solicitou uma nova de acesso ao painel de administração do site, a partir de agora use
 a seguinte senha para acesso <strong>$novasenha</strong></p><p>Troque esta senha para uma senha segura e de sua preferência
 o quanto antes.</p>";
                if($this->sistema->enviar_email($email,'Nova senha de acesso',$mensagem))
                {
                    $dados['senha']=md5($novasenha);
                    $this->usuarios->do_update($dados,array('email'=>$email),false);
                    set_msg('msgok','Uma nova senha foi enviada para seu e-mail','success');
                    redirect('usuarios/nova_senha');
                }
                else{
                    set_msg('msgerro','Erro ao enviar nova senha, contate o administrador','erro');
                    redirect('usuarios/nova_senha');
                }
            }else{
                set_msg('msgerro','Este e-mail não possui cadastro no sistema','erro');
                redirect('usuarios/nova_senha');
            }
        }
        set_tema('titulo','Recuperar Senha');
        set_tema('conteudo',load_modulo('usuarios','nova_senha'));
        load_template();

    }
    
    public function deslogar(){
        $this->session->set_userdata(array(
            'user_id'=> '',
            'user_nome'=>'',
            'user_adm'=>'',
            'user_logado'=>''
        ));
        set_msg('logoffok','Logoff efetuado com sucesso','success');
        $this->session->sess_destroy();
        //logout();
        redirect('usuarios/login');
    }

    public function cadastrar(){
        esta_logado();
        $this->form_validation->set_rules('nome','NOME','trim|required|min_length[4]|ucwords');
        $this->form_validation->set_rules('email','EMAIL','trim|required|valid_email|is_unique[usuarios.email]|strtolower');
        $this->form_validation->set_rules('login','LOGIN','trim|required|min_length[4]|is_unique[usuarios.login]|strtolower');
        $this->form_validation->set_rules('senha','SENHA','trim|required|min_length[4]|strtolower');
        $this->form_validation->set_rules('senha2','REPITA SENHA','trim|required|min_length[4]|strtolower|matches[senha]');

        if($this->form_validation->run()){
            $dados=elements(array('nome','email','login'),$this->input->post());
            $dados['senha']=md5($this->input->post('senha'));
            if(!is_admin() && $this->input->post('adm')=='1')
            {
                set_msg('msgerro','Apenas Administradores podem cadastrar novos Administradores','erro');
                set_tema('titulo','Cadastro de usuários');
                set_tema('conteudo',load_modulo('usuarios','cadastrar'));
                load_template();
                return false;
            }
            $this->usuarios->do_insert($dados);
        }
        set_tema('titulo','Cadastro de usuários');
        set_tema('conteudo',load_modulo('usuarios','cadastrar'));
        load_template();
    }
}
