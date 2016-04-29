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
        <div class="columns medium-12 medium-centered">
          <a href="<?php echo base_url('painel');?>"><h1>Painel ADM</h1></a>
            <div class="text-right">
                Logado como <strong><?php echo $this->session->userdata('user_nome')?></strong> <a href="<?php echo base_url('usuarios/deslogar');?>">sair</a>
            </div>
        </div>

      </div>
  <?php endif ?>
  <div class="row paineladm">
  {conteudo}
  </div>
  <script scr="js/foundation.min.js"></script>
  <div class="row rodape">
    <div ="columns medium-6 medium-centered">
      {rodape}
    </div>
  </div>
  {footerinc}
  </body>
</html>