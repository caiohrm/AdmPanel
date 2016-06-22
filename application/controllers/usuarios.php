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

    public function gerenciar(){
        esta_logado();
        set_tema('footerinc',load_js(array('datatables.min','table')),FALSE);
        set_tema('titulo','Listagem de usuários');
        set_tema('conteudo',load_modulo('usuarios','gerenciar'));
        load_template();

    }

    public function alterar_senha()
    {
        esta_logado();
        $this->form_validation->set_rules('senha','SENHA','trim|required|min_length[4]|strtolower');
        $this->form_validation->set_rules('senha2','REPITA SENHA','trim|required|min_length[4]|strtolower|matches[senha]');
        if($this->form_validation->run()) {
            $dados['senha'] = md5($this->input->post('senha'));
            $this->usuarios->do_update($dados, array('id' => $this->input->post('idusuario')));
        }
        set_tema('titulo','Alteração de senha');
        set_tema('conteudo',load_modulo('usuarios','alterar_senha'));
        load_template();

    }

    public function editar()
    {
        esta_logado();
        $this->form_validation->set_rules('nome','NOME','trim|required|min_length[4]|ucwords');
        if($this->form_validation->run()){
            $dados['nome']=$this->input->post('nome');
            $dados['ativo']=($this->input->post('ativo')==1 ? 1: 0);
            if(!is_admin() && $this->input->post('adm')=='1')
            {
                set_msg('msgerro','Apenas Administradores podem cadastrar novos Administradores','erro');
                set_tema('titulo','Cadastro de usuários');
                set_tema('conteudo',load_modulo('usuarios','cadastrar'));
                load_template();
                return false;
            }
            $this->usuarios->do_update($dados,array('id'=>$this->input->post('idusuario')));
        }
        set_tema('titulo','Alteração de usuários');
        set_tema('conteudo',load_modulo('usuarios','editar'));
        load_template();

    }

    public function excluir()
    {
        esta_logado();
        if(is_admin(TRUE)){
            $iduser = $this->uri->segment(3);
            echo $iduser;
            if($iduser != null){
                $query=$this->usuarios->get_byid($iduser);
                if($query->num_rows()==1){
                    $query = $query->row();
                    if($query->id !=1){
                        $this->usuarios->do_delete(array('id'=>$query->id),false);
                    }else{
                        set_msg('msgerro','Este usuário não pode ser excluido','erro');
                    }
                }else{
                    set_msg('msgerro','Usuário não encontrado para exclusão','erro');
                }
            }else{
                set_msg('msgerro','Escolha um usuário para excluir','erro');
            }
        }
        redirect('usuarios/gerenciar');

    }

}
