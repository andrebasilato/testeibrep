<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--><html class="no-js"><!--<![endif]-->
<head>
<?php incluirLib("head_forum",$config,$usuario); ?>
<link rel="stylesheet" href="<?= $config["urlSistema"]; ?>/assets/aluno/css/forum.css">
<style type="text/css">
  .bts-forum-criar{
	color:#FFF;
  }
  .forum-acoes{
	padding:15px;
	width:100%;
	background-color:#f2f2f2;
	
  }
  .forum-acoes a{
	float:left;
	width:100%;
	padding:5px;
	background-color:#949494;
	color:#FFF;
	margin-bottom:3px;
	text-transform:uppercase;
	text-align:center;
	transition:all .2s;
	
  }
  .forum-acoes a:hover{
	background-color:#797979;
  }
  .forum-acoes a img{
	margin-top:-7px;
	margin-left:3px;
  }
  .forum-interna-titulo{
	background-color:#f7f7f7;
	position:relative;
	float:left;
	padding:10px;
	width:100%;
  }
  .forum-interna-titulo .forum-listagem-status{
	width:150px;
  }
  .forum-aviso{
	width:100%;
	padding:20px;
	color:#FFF;
	text-align:center;
	text-transform:uppercase;
	margin-bottom:10px;
  }
  .corbgVermelho{
	background-color:#C30;
  }
  .forum-interna-listagem{
	float:left;
  }
  .forum-interna-listagem li{
	border-bottom:1px dotted #ccc;
	position:relative;
  }
  .forum-interna-avatar{
	width: 45px;
	height: 45px;
	border-radius: 50%;
	position: absolute;
	top: 15px;
	overflow: hidden;
	left: 10px;  
  }
  .forum-interna-botoes{
  }
  .forum-interna-conteudo{
	width:100%;
	padding:10px;
	padding-left:66px;
  }
  .forum-citacao{
	padding:15px;
	background-color:#fffdc3;
	margin-bottom:10px;
  }
  .forum-interna-botoes{  
	width:100%;
	margin-bottom:30px;
  }
  .forum-interna-botoes a{
	float:left;
	margin:2px;
	padding:5px 10px 2px 10px;
	text-transform:uppercase;
	color:#FFF;
  }
  .corbg-btlaranja{
	background-color:#f18d2b;
  }
  .forum-interna-botoes a img{
	margin-top: -7px;
	margin-left: 3px;
  }
  .corbgcinza{
	background-color:#4b4b4b;
  }
  .forum-tag{
	padding: 10px;
	text-transform: uppercase;
	position: absolute;
	right: -11px;
	top: -12px;
	color: #FFF;
	letter-spacing: -1px;
	font-size:13px;
  }
  .forum-interna-conteudo h3{
	position:relative;
  }
</style>
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
    <?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|44", false)) { ?>
      <form action="" method="post" id="form_ocultar_topico">
        <input name="acao" id="acao_ocultar_topico" type="hidden" value="" />
      </form>
    <?php } ?>
    <?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|49", false)) { ?>
      <form action="" method="post" id="form_ocultar_mensagem">
        <input name="acao" id="acao_ocultar_mensagem" type="hidden" value="" />
        <input name="idmensagem" id="ocultar_mensagem_idmensagem" type="hidden" value="" />
      </form>
    <?php } ?>
    <?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|45", false)) { ?>
      <form action="" method="post" id="form_bloquear_topico">
        <input name="acao" id="acao_bloquear_topico" type="hidden" value="" />
      </form>
	<?php } ?>
    <?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|46", false)) { ?>
      <form action="" method="post" id="form_assinar_topico">
        <input name="acao" id="acao_assinar_topico" type="hidden" value="" />
        <input name="idassinatura" id="desassinar_topico_idassinatura" type="hidden" value="" />
      </form>
    <?php } ?>
    <div class="coluna-dados" id="coluna-conteudo">
      <div class="area area-conteudo">
        <div class="principal-area">
          <div class="principal-titulo">
            <h2><?php echo $forum["nome"]; ?></h2>
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
                <a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $url[3]; ?>/<?php echo $url[4]; ?>/acessar"><?php echo $idioma["foruns"]; ?></a> >
                <a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $url[3]; ?>/<?php echo $url[4]; ?>/<?php echo $url[5]; ?>/<?php echo $url[6]; ?>"><?php echo $forum["nome"]; ?></a> >
                <a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $url[3]; ?>/<?php echo $url[4]; ?>/<?php echo $url[5]; ?>/<?php echo $url[6]; ?>"><?php echo $idioma["topicos"]; ?></a> >
                <a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $url[3]; ?>/<?php echo $url[4]; ?>/<?php echo $url[5]; ?>/<?php echo $url[6]; ?>/<?php echo $url[7]; ?>/<?php echo $url[8]; ?>"><?php echo $topico["nome"]; ?></a> >
                <a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $url[3]; ?>/<?php echo $url[4]; ?>/<?php echo $url[5]; ?>/<?php echo $url[6]; ?>/<?php echo $url[7]; ?>/<?php echo $url[8]; ?>"><?php echo $idioma["mensagens"]; ?></a>
              </div>
              <?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|45", false) && $topico["bloqueado"] == "desbloqueado") { ?>
                <a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $url[3]; ?>/<?php echo $url[4]; ?>/<?php echo $url[5]; ?>/<?php echo $url[6]; ?>/<?php echo $url[7]; ?>/<?php echo $url[8]; ?>/cadastrar" class="corbgVerde-escuro bts-forum-criar" rel="facebox"><?php echo $idioma["responder_topico"]; ?></a>
              <?php } ?>
            </div>
            <div class="row-fluid">
              <div class="span4" style="min-height:500px;">
                <div class="forum-coluna">
                <div class="forum-base-titulo corbgVerde-escuro"><?php echo $idioma["acoes_topico"]; ?></div>
                  <div class="forum-acoes">
                    <?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|46", false)) { ?>
                      <a href="#" onclick="<?php if($assinatura) { ?>desassinarTopico(<?php echo $assinatura; ?>)<?php } else { ?>assinarTopico()<?php } ?>"><?php if($assinatura) { echo $idioma["desassinar_topico"]; } else { echo $idioma["assinar_topico"]; }?><img src="/assets/aluno/img/forum/ico_adicionar.png" height="17" width="20"/></a>
                    <?php } ?>
					<?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|45", false)) { ?>
                      <a href="#" onclick="<?php if($topico["bloqueado"] != "desbloqueado") { ?>desbloquearTopico()<?php } else { ?>bloquearTopico()<?php } ?>"><?php if($topico["bloqueado"] != "desbloqueado") { echo $idioma["desbloquear_topico"]; } else { echo $idioma["bloquear_topico"]; } ?><img src="/assets/aluno/img/forum/ico_bloquear.png" height="17" width="20"/></a>
                    <?php } ?>
					<?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|44", false)) { ?>
                      <a href="#" onclick="<?php if($topico["oculto"] == "N") { ?>ocultarTopico()<?php } else { ?>desocultarTopico()<?php } ?>"><?php if($topico["oculto"] == "N") { echo $idioma["ocultar_topico"]; } else { echo $idioma["desocultar_topico"]; } ?><img src="/assets/aluno/img/forum/ico_ocultar.png" height="17" width="20"/></a>
                    <?php } ?>
                    <p class="texto-apoio marge30-up">
					  <?php 
					  echo $idioma["descricao_assinar"]."<br />";
					  echo $idioma["descricao_bloquear"]."<br />";
                      echo $idioma["descricao_ocultar"]; 
					  ?>
                    </p>                                        
                  </div>
                </div>
                <?php /*?><div class="forum-coluna">
                  <div class="forum-base-titulo corbgVerde-escuro"><?php echo $idioma["topicos_populares"]; ?></div>
                  <ul class="forum-lista-topicos">
                    <?php 
                    if(count($populares) > 0) {
                      foreach($populares as $topico) { ?>
                        <li><a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $url[3]; ?>/<?php echo $url[4]; ?>/<?php echo $topico["idforum"]; ?>/topicos/<?php echo $topico["idtopico"]; ?>/mensagens"><p><?php echo $topico["nome"]; ?></p></a><div class="forum-lista-topicos-view"><?php echo $topico["respostas"]; ?></div></li>
                      <?php 
                      } 
                    } else { ?>
                      <li><p><?php echo $idioma["nenhum_topico"]; ?></p></li>
                    <?php } ?>
                  </ul>
                </div><?php */?>
                <div class="marge30-up">
                  <div class="forum-base-titulo corbgVerde-claro"><?php echo $idioma["alunos_participantes"]; ?></div>
                  <ul class="forum-lista-alunos">
                    <?php 
                    if(count($participantes) > 0) {
                      foreach($participantes as $aluno) { ?>
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
                <?php 
				$topicoModeradoOcultadoBloqueado = false;
				if($topico["moderado"] == "S") {
				  $topicoModeradoOcultadoBloqueado = 1;
				  if($topico["oculto"] == "S") {
					$topicoModeradoOcultadoBloqueado = 4;
					if($topico["bloqueado"] != "desbloqueado") {
					  $topicoModeradoOcultadoBloqueado = 5;
					}
				  } elseif($topico["bloqueado"] != "desbloqueado") {
					$topicoModeradoOcultadoBloqueado = 6;
				  }
				} elseif($topico["oculto"] == "S") {
				  $topicoModeradoOcultadoBloqueado = 2;
				  if($topico["bloqueado"] != "desbloqueado") {
					$topicoModeradoOcultadoBloqueado = 7;
				  }
				} elseif($topico["bloqueado"] != "desbloqueado") {
				  $topicoModeradoOcultadoBloqueado = 3;
				}
				if($topicoModeradoOcultadoBloqueado) { ?><div class="forum-aviso corbgVermelho"><?php echo $idioma["topico_bloqueado"][$topicoModeradoOcultadoBloqueado]; ?></div><?php } ?>
                <div class="forum-interna-titulo">
                  <div class="forum-listagem-avatar"><img src="/api/get/imagens/<?php echo $pastas_avatar[$topico["criado_por"]["tipo"]]; ?>/45/45/<?php echo $topico["criado_por"]["avatar"]; ?>" width="45" height="45"></div>
                  <div class="forum-listagem-titulo">
                    <p><?php echo $idioma["topico_criado_por"].$topico["criado_por"]["nome"]; ?></p>
                    <h2 class="corpadrao"><?php echo $topico["nome"]; ?></h2>
                  </div>
                  <div class="forum-listagem-status">
                    <div class="forum-listagem-coluna">
                      <div class="corpadrao"><?php echo $topico["total_mensagens"]; ?></div>
                      <small><?php echo $idioma["respostas"]; ?></small>
                    </div>
                    <div class="forum-listagem-coluna">
                      <div class="corpadrao"><?php echo $topico["visualizacoes"]; ?></div>
                      <small><?php echo $idioma["visitas"]; ?></small>
                    </div>
                  </div>
                </div>
                <ul class="forum-interna-listagem">
                  <li>
                    <div class="forum-interna-conteudo">
                      <?php if($topico["moderado"] == "S") { ?>
                        <div class="forum-citacao">
                          <h3><?php echo $idioma["mensagem_original"]; ?></h3>
                          <p><?php echo nl2br($topico["mensagem"]); ?></p>
                        </div>
					  <?php } ?>
                      <p>
						<?php 
						if($topico["moderado"] == "S") {
						  echo nl2br($topico["moderado_mensagem"]);
						} else {
						  echo nl2br($topico["mensagem"]);
						}
						?>
                      </p>
                      <?php if($topico["arquivo_servidor"]) { ?>
                          <p>
                            <a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $url[3]; ?>/<?php echo $url[4]; ?>/<?php echo $url[5]; ?>/<?php echo $url[6]; ?>/<?php echo $url[7]; ?>/download"><?php echo $idioma['arquivo'].': '.$topico["arquivo_nome"].' ('.tamanhoArquivo($topico["arquivo_tamanho"]).')'; ?></a>
                          </p>
                      <?php } ?>
                      <div class="forum-interna-botoes">
                        <?php //if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|44", false)) { ?>
                          <a href="#" class="corbgpadrao btfade votar" id="<?= $topico["idtopico"]; ?>" name="curtir_topico"><img src="/assets/aluno/img/forum/ico_like.png" height="17" width="20" id="img_curtir_topico<?php echo $topico["idtopico"]; ?>" /><?php echo $topico["total_curtiu"]; ?></a>
                          <a href="#" class="corbgpadrao btfade votar" id="<?= $topico["idtopico"]; ?>" name="nao_curtir_topico"><img src="/assets/aluno/img/forum/ico_deslike.png" height="17" width="20" id="img_nao_curtir_topico<?php echo $topico["idtopico"]; ?>" /><?php echo $topico["total_nao_curtiu"]; ?></a>
                        <?php //} ?>
						<?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|43", false)) { ?>
                          <a href="#moderartopico" class="<?php if($topico["moderado"] == "S") { ?>corbgcinza<?php } else {  ?>corbg-btlaranja<?php } ?> btfade" rel="facebox"><?php if($topico["moderado"] == "S") { echo $idioma["moderado_mensagem"]; } else { echo $idioma["moderar_mensagem"]; } ?><img src="/assets/aluno/img/forum/ico_editar.png" height="17" width="20"/></a>
                          <div id="moderartopico" style="display:none;">
                            <iframe id="iframe_moderartopico" src="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $url[3]; ?>/<?php echo $url[4]; ?>/<?php echo $url[5]; ?>/<?php echo $url[6]; ?>/<?php echo $url[7]; ?>/moderar" width="900" height="650" frameborder="0"></iframe>
                          </div>
						<?php } ?>
                        <?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|42", false) && $topico["bloqueado"] == "desbloqueado") { ?>
                          <a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $url[3]; ?>/<?php echo $url[4]; ?>/<?php echo $url[5]; ?>/<?php echo $url[6]; ?>/<?php echo $url[7]; ?>/<?php echo $url[8]; ?>/cadastrar" class="corbg-btlaranja btfade" rel="facebox"><?php echo $idioma["responder_mensagem"]; ?><img src="/assets/aluno/img/forum/ico_responder.png" height="17" width="20"/></a>
						<?php } ?>
                      </div>
                    </div>
                  </li>
                  <?php 
				  if(count($respostas) > 0) {
					foreach($respostas as $resposta) { 
					  //if($resposta["oculto"] == "N" || ($resposta["oculto"] == "S" && $linhaObj->verificaPermissao($forum["permissoes"], $url[0]."|mensagens|2", false))) {
						$mensagemModeradaOcultada = false;
						if($resposta["moderado"] == "S") {
						  $mensagemModeradaOcultada = 1;
						  if($resposta["oculto"] == "S") {
							$mensagemModeradaOcultada = 3;
						  }
						} elseif($resposta["oculto"] == "S") {
						  $mensagemModeradaOcultada = 2;
						}
						?>
						<li>
						  <div class="forum-interna-avatar"><img src="/api/get/imagens/<?php echo $pastas_avatar[$resposta["criado_por"]["tipo"]]; ?>/45/45/<?php echo $resposta["criado_por"]["avatar"]; ?>" width="45" height="45"></div>
						  <div class="forum-interna-conteudo">
							<h3>[<?php echo formataData($resposta["data_cad"],"pt",1); ?>] <?php echo $resposta["criado_por"]["nome"].$idioma["diz"]; ?> <?php if($mensagemModeradaOcultada) { ?><div class="corbgcinza forum-tag"><?php echo $idioma["resposta_moderada_ocultada"][$mensagemModeradaOcultada]; ?></div><?php } ?></h3>
							<?php if($resposta["idmensagem_associada"]) { ?>
							  <div class="forum-citacao">
								<h3><?php echo $resposta["associada"]["criado_por"]["nome"].$idioma["diz"]; ?></h3>
								<p><?php echo nl2br($resposta["associada"]["mensagem"]); ?></p>
							  </div>
							<?php } 
							if($resposta["moderado"] == "S") { ?>
							  <div class="forum-citacao">
								<h3><?php echo $idioma["mensagem_original"]; ?></h3>
								<p><?php echo nl2br($resposta["mensagem"]); ?></p>
							  </div>
							<?php } ?>
							<p>
							  <?php 
							  if($resposta["moderado"] == "S") {
								echo nl2br($resposta["moderado_mensagem"]);
							  } else {
								echo nl2br($resposta["mensagem"]);
							  }
							  ?>
							</p>
                            <?php if($resposta["arquivo_servidor"]) { ?>
                                <p>
                                    <a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $url[3]; ?>/<?php echo $url[4]; ?>/<?php echo $url[5]; ?>/<?php echo $url[6]; ?>/<?php echo $url[7]; ?>/<?php echo $url[8]; ?>/<?php echo $resposta["idmensagem"]; ?>/download"><?php echo $idioma['arquivo'].': '.$resposta["arquivo_nome"].' ('.tamanhoArquivo($resposta["arquivo_tamanho"]).')'; ?></a>
                                </p>
							<?php } ?>
							<div class="forum-interna-botoes">
							  <?php //if($linhaObj->verificaPermissao($forum["permissoes"], $url[0]."|topicos|6", false)) { ?>
                                <a href="#" class="corbgpadrao btfade votar" id="<?= $resposta["idmensagem"]; ?>" name="curtir_mensagem"><img src="/assets/aluno/img/forum/ico_like.png" height="17" width="20" id="img_curtir_mensagem<?php echo $resposta["idmensagem"]; ?>" /><?php echo $resposta["total_curtiu"]; ?></a>
                                <a href="#" class="corbgpadrao btfade votar" id="<?= $resposta["idmensagem"]; ?>" name="nao_curtir_mensagem"><img src="/assets/aluno/img/forum/ico_deslike.png" height="17" width="20" id="img_nao_curtir_mensagem<?php echo $resposta["idmensagem"]; ?>" /><?php echo $resposta["total_nao_curtiu"]; ?></a>
							  <?php //} ?>
							  <?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|49", false)) { ?>
								<a href="#" onclick="<?php if($resposta["oculto"] == "S") { ?>desocultarMensagem(<?= $resposta["idmensagem"]; ?>)<?php } else { ?>ocultarMensagem(<?= $resposta["idmensagem"]; ?>)<?php } ?>" class="<?php if($resposta["oculto"] == "S") { ?>corbgcinza<?php } else { ?>corbg-btlaranja<?php } ?> btfade"><?php if($resposta["oculto"] == "S") { echo $idioma["desocultar_mensagem"]; } else { echo $idioma["ocultar_mensagem"]; } ?><img src="/assets/aluno/img/forum/ico_ocultar.png" height="17" width="20"/></a>
							  <?php } ?>
							  <?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|48", false)) { ?>
                                <a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $url[3]; ?>/<?php echo $url[4]; ?>/<?php echo $url[5]; ?>/<?php echo $url[6]; ?>/<?php echo $url[7]; ?>/<?php echo $url[8]; ?>/<?php echo $resposta["idmensagem"]; ?>/moderar" class="<?php if($resposta["moderado"] == "S") { ?>corbgcinza<?php } else {  ?>corbg-btlaranja<?php } ?> btfade" rel="facebox"><?php if($resposta["moderado"] == "S") { echo $idioma["moderado_mensagem"]; } else { echo $idioma["moderar_mensagem"]; } ?><img src="/assets/aluno/img/forum/ico_editar.png" height="17" width="20"/></a>
							  <?php } ?>
							  <?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|42", false) && $topico["bloqueado"] == "desbloqueado") { ?>
                                <a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $url[3]; ?>/<?php echo $url[4]; ?>/<?php echo $url[5]; ?>/<?php echo $url[6]; ?>/<?php echo $url[7]; ?>/<?php echo $url[8]; ?>/<?php echo $resposta["idmensagem"]; ?>/responder" class="corbg-btlaranja btfade" rel="facebox"><?php echo $idioma["responder_mensagem"]; ?><img src="/assets/aluno/img/forum/ico_responder.png" height="17" width="20"/></a>
                              <?php } ?>
							</div>
						  </div>
						</li>
					  <?php 
					  //}
					} 
				  } else { ?>
					<li>
					  <div class="forum-interna-conteudo">
                        <div class="forum-aviso corbgcinza"><?php echo $idioma["nenhum_resposta"]; ?></div>
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
  
  <?php //if($linhaObj->verificaPermissao($forum["permissoes"], $url[0]."|topicos|6", false)) { ?>
	$(function() {
	  $(".votar").click(function() {
		var id = $(this).attr("id");
		var name = $(this).attr("name");
		var dataString = 'id='+id+'&tipo='+name;
		var parent = $(this);
		var htmlAntigo = $(this).html();
		var imagem = '<img src="/assets/aluno/img/forum/ico_like.png" height="17" width="20" />';
		  
		$(this).fadeIn(200).html('<img src="/assets/img/forum/local_vote.gif" width="16" height="16" />');
		$.ajax({
		  type: "POST",
		  url: "/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/<?= $url[6]; ?>/<?= $url[7]; ?>/<?= $url[8]; ?>/json/curtir",
		  data: dataString,
		  cache: false,
		  success: function(html){
			var jsonObj = jQuery.parseJSON(html);
			if(jsonObj.mensagem == "ja_votou") {
			  parent.html(htmlAntigo);
			  alert('<?= $idioma["ja_votou"]; ?>');
			} else {
			  parent.html(imagem+jsonObj.contador);
			  alert('<?= $idioma["voto_computado_sucesso"]; ?>');
			}			
		  }
		});
		
		return false;
	  });
	});
  <?php //} ?>
  
  <?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|44", false)) { ?>
	function ocultarTopico() {
	  var confirma = confirm('<?=$idioma["confirma_ocultar_topico"];?>');
	  if(confirma) {
		document.getElementById("acao_ocultar_topico").value = "ocultar_topico";
		document.getElementById("form_ocultar_topico").submit();
	  } else {
		return false;	
	  }
	}
	
	function desocultarTopico() {
	  var confirma = confirm('<?=$idioma["confirma_desocultar_topico"];?>');
	  if(confirma) {
		document.getElementById("acao_ocultar_topico").value = "desocultar_topico";
		document.getElementById("form_ocultar_topico").submit();
	  } else {
		return false;	
	  }
	}
  <?php } ?>
  
  <?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|49", false)) { ?>
	function ocultarMensagem(idmensagem) {
	  var confirma = confirm('<?=$idioma["confirma_ocultar_mensagem"];?>');
	  if(confirma) {
		document.getElementById("acao_ocultar_mensagem").value = "ocultar_mensagem";
		document.getElementById("ocultar_mensagem_idmensagem").value = idmensagem;
		document.getElementById("form_ocultar_mensagem").submit();
	  } else {
		return false;	
	  }
	}
	
	function desocultarMensagem(idmensagem) {
	  var confirma = confirm('<?=$idioma["confirma_desocultar_mensagem"];?>');
	  if(confirma) {
		document.getElementById("acao_ocultar_mensagem").value = "desocultar_mensagem";
		document.getElementById("ocultar_mensagem_idmensagem").value = idmensagem;
		document.getElementById("form_ocultar_mensagem").submit();
	  } else {
		return false;	
	  }
	}
  <?php } ?>
  
  <?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|45", false)) { ?>
	function bloquearTopico() {
	  var confirma = confirm('<?=$idioma["confirma_bloquear_topico"];?>');
	  if(confirma) {
		document.getElementById("acao_bloquear_topico").value = "bloquear_topico";
		document.getElementById("form_bloquear_topico").submit();
	  } else {
		return false;	
	  }
	}
  
	function desbloquearTopico() {
	  var confirma = confirm('<?=$idioma["confirma_desbloquear_topico"];?>');
	  if(confirma) {
		document.getElementById("acao_bloquear_topico").value = "desbloquear_topico";
		document.getElementById("form_bloquear_topico").submit();
	  } else {
		return false;	
	  }
	}
  <?php } ?>
  <?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|46", false)) { ?>
	function assinarTopico() {
	  var confirma = confirm('<?=$idioma["confirma_assinar_topico"];?>');
	  if(confirma) {
		document.getElementById("acao_assinar_topico").value = "assinar_topico";
		document.getElementById("form_assinar_topico").submit();
	  } else {
		return false;	
	  }
	}
	
	function desassinarTopico(idassinatura) {
	  var confirma = confirm('<?=$idioma["confirma_desassinar_topico"];?>');
	  if(confirma) {
		document.getElementById("acao_assinar_topico").value = "desassinar_topico";
		document.getElementById("desassinar_topico_idassinatura").value = idassinatura;
		document.getElementById("form_assinar_topico").submit();
	  } else {
		return false;	
	  }
	}
  <?php } ?>
</script>
</body>
</html>