<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--><html class="no-js"><!--<![endif]-->
<head>
<?php incluirLib("head_forum",$config,$usuario); ?>
<link rel="stylesheet" href="<?= $config["urlSistema"]; ?>/assets/aluno/css/forum.css">
<script>
$(document).ready(function(){
	$('#coluna-conteudo').css('left', 0).css('width', '100%');
});
</script>
<script type="text/javascript">
  <?php if($_POST["msg"]) { ?>
	alert("<?php echo $idioma[$_POST["msg"]]; ?>");
  <? } ?>
</script>
</head>
<body>
<div class="content">
  <div class="conteudo">
    <div class="coluna-dados" id="coluna-conteudo">
      <div class="area area-conteudo" >
        <div class="principal-area">
          <div class="principal-titulo">
            <h2><?php echo $forum["nome"]; ?></h2>
            <span style="position:relative;margin-left:90px;"><strong><?php if ($forum["disciplina"]) { echo 'Disciplina: '.$forum["disciplina"];}; ?></strong></span>
            <!--<div class="principal-filtros">
              <a href="javascript:favoritar()" class="  cor-cinza  ">Abrir Tudo</a>
              <a href="javascript:favoritar()" class="  cor-cinza  ">Fechar Tudo</a>
            </div>-->
          </div> <!-- principal - titulo  -->
          <div class="principal-texto">
            <?php /*?><div class="forum-faixa">
              <div class="forum-faixa-central">
                <div class="forum-busca">
                  <h2><?php echo $idioma["bem_vindo"]; ?></h2>
                  <p><?php echo $idioma["descricao"]; ?></p>
                  <div class="forum-busca-campo">
                    <input type="text" placeholder="O QUE PROCURA?">
                    <a><img src="/assets/aluno/img/forum/ico_lupa.png" height="20" width="23" /></a>
                  </div>
                </div>
                <div class="forum-faixa-ator"><img src="/assets/aluno/img/forum/ator.png" height="210" width="169"/></div>
              </div>
            </div><?php */?>
            <div class="forum-breadcrumbs">
              <div class="forum-breadcrumbs-bts corpadrao">
                <a href="#"><?php echo $idioma["foruns"]; ?></a> >
                <a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $url[3]; ?>/<?php echo $url[4]; ?>"><?php echo $forum["nome"]; ?></a> >
                <a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $url[3]; ?>/<?php echo $url[4]; ?>"><?php echo $idioma["topicos"]; ?></a>
              </div>
              <?php if($linhaObj->verificaPermissao($forum["permissoes"], $url[0]."|topicos|1", false)) { ?>
                <a href="#cadastrartopico" class="corbgVerde-escuro bts-forum-criar" rel="facebox"><?php echo $idioma["criar_topicos"]; ?></a>
                <div id="cadastrartopico" style="display:none;">
                    <iframe id="iframe_cadastrartopico" src="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $url[3]; ?>/<?php echo $url[4]; ?>/cadastrar" width="900" height="650" frameborder="0"></iframe>
                </div>
			  <?php } ?>
            </div>
            <div class="row-fluid">
              <div class="span4" style="min-height:500px;">
                <div class="forum-coluna">
                  <div class="forum-base-titulo corbgVerde-escuro"><?php echo $idioma["topicos_populares"]; ?></div>
                  <ul class="forum-lista-topicos">
                    <?php 
                    if(count($populares) > 0) {
                      foreach($populares as $topico) { ?>
                        <li><a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $topico["idforum"]; ?>/topicos/<?php echo $topico["idtopico"]; ?>/mensagens"><p><?php echo $topico["nome"]; ?></p></a><div class="forum-lista-topicos-view"><?php echo $topico["respostas"]; ?></div></li>
                      <?php 
                      } 
                    } else { ?>
                      <li><p><?php echo $idioma["nenhum_topico"]; ?></p></li>
                    <?php } ?>
                  </ul>
                </div>
                <div class="marge30-up">
                  <div class="forum-base-titulo corbgVerde-claro"><?php echo $idioma["alunos_ativos"]; ?></div>
                  <ul class="forum-lista-alunos">
                    <?php 
                    if(count($alunosAtivos) > 0) {
                      foreach($alunosAtivos as $aluno) { ?>
                        <li>
                          <div class="forum-lista-alunos-avatar"><img src="/api/get/imagens/pessoas_avatar/45/45/<?php echo $aluno["avatar_servidor"]?>" width="45" height="45"></div>
                          <div class="forum-lista-alunos-nome"><?php echo $aluno["nome"]; ?></div>
                          <div class="forum-lista-alunos-view"><?php echo $aluno["mensagens"]; ?></div>
                        </li>
                      <?php 
                      } 
                    } else { ?>
                      <li>
                        <div class="forum-lista-alunos-avatar"></div>
                        <div class="forum-lista-alunos-nome"><?php echo $idioma["nenhum_aluno"]; ?></div>
                      </li>
                    <?php } ?>
                  </ul>
                </div>
              </div> 
              <div class="span8">
                <div class="forum-base-titulo corbgpadrao"><?php echo $idioma["todos_topicos"]; ?></div>
                <ul class="forum-listagem">
                  <?php
                  if(count($topicos) > 0) {
                    foreach($topicos as $topico) { ?>
                      <li>
                        <div class="forum-listagem-avatar"><img src="/api/get/imagens/<?php echo $pastas_avatar[$topico["criado_por"]["tipo"]]; ?>/45/45/<?php echo $topico["criado_por"]["avatar"]; ?>" width="45" height="45"></div>
                        <div class="forum-listagem-titulo">
                          <p><?php echo $idioma["topico_criado_por"].$topico["criado_por"]["nome"]; ?></p>
                          <a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $topico["idforum"]; ?>/topicos/<?php echo $topico["idtopico"]; ?>/mensagens"><h2><?php echo $topico["nome"]; ?></h2></a>
                          <?php if($topico["ultima_mensagem_data"]) { ?><p><?php echo $idioma["ultima_resposta"].formataData($topico["ultima_mensagem_data"],"pt",1); ?></p><?php } ?>
                        </div>
                        <div class="forum-listagem-status">
                          <div class="forum-listagem-coluna">
                            <div class="corpadrao"><?php echo $topico["total_curtiu"]; ?></div>
                            <small><?php echo $idioma["curtidas"]; ?></small>
                          </div>
                          <div class="forum-listagem-coluna">
                            <div class="corpadrao"><?php echo $topico["total_mensagens"]; ?></div>
                            <small><?php echo $idioma["respostas"]; ?></small>
                          </div>
                          <div class="forum-listagem-coluna">
                            <div class="corpadrao"><?php echo $topico["visualizacoes"]; ?></div>
                            <small><?php echo $idioma["visitas"]; ?></small>
                          </div>
                        </div>
                      </li>
					<?php } ?>
                  <?php } else { ?>
                    <li>
                      <div class="forum-listagem-titulo">
                        <h2><?php echo $idioma["nenhum_topico"]; ?></h2>
                      </div>
                    </li>
				  <?php } ?>
                </ul>
              </div>
            </div>
          </div> <!-- principal area -->
        </div>
      </div> <!-- area-conteudo --> 	
    </div><!-- coluna dados -->
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function(){ 
	$('a[rel*=facebox]').facebox();
  });
</script>
</body>
</html>