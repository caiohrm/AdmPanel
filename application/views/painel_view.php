<!doctype html>
<html class="no-js" lang="pt-br" dir="ltr">
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php if(isset($titulo)): ?> {titulo} |  <?php endif;?>{titulo_padrao}</title>
  {headerinc}
</head>
<body>
<?php
if(esta_logado(false)):;?>
  <div class="row">
    <div class="columns medium-6 text-left">
      <a href="<?php echo base_url('painel');?>"><h1>Painel ADM</h1></a>
    </div>
    <div class="columns medium-6 text-right">
      Logado como <strong><?php echo $this->session->userdata('user_nome')?></strong></br>
      <?php echo anchor('usuarios/alterar_senha/'.$this->session->userdata('user_id'),'Alterar Senha','class="tiny button"')?>
      <?php echo anchor('usuarios/deslogar','Sair','class="tiny alert button"')?>
    </div>
  </div>
  <div class="title-bar" data-responsive-toggle="example-menu" data-hide-for="medium">
    <button class="menu-icon" type="button" data-toggle></button>
    <div class="title-bar-title">Menu</div>
  </div>

  <div class="top-bar" id="example-menu">
    <div class="top-bar-left">
      <ul class="dropdown menu" data-dropdown-menu>
        <li><?php echo anchor('painel','Inicio');?></li>
        <li>
          <?php echo anchor('usuarios/cadastrar','Usuários');?>
          <ul class="menu vertical">
            <li><?php echo anchor('usuarios/cadastrar','Cadastrar');?></li>
            <li><?php echo anchor('usuarios/gerenciar','Gerenciar');?></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
<?php endif ?>
<div class="row paineladm">
  {conteudo}
</div>
{footerinc}
<div class="row rodape">
  <div ="columns medium-6 medium-centered">
  {rodape}
</div>
</body>

</html>