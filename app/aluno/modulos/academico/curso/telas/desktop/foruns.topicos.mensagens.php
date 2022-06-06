<? header('Content-Type: text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<?php incluirLib("head", $config, $usuario); ?>
</head>
<body>

<!-- Topo -->
<?php incluirLib("topo", $config, $usuario); ?>
<!-- /Topo -->
<!-- Topo curso -->
<?php incluirLib("topo_curso", $config, $informacoesTopoCurso); ?>
<!-- /Topo curso -->
<!-- Conteudo -->
<div class="content" style="position: relative;">
    <div class="row container-fixed">
        <!-- Menu Fixo -->
        <?php incluirLib("menu", $config, $usuario); ?>
        <!-- /Menu Fixo -->   
        <!-- Box -->
        <div class="box-side box-bg">
            <div class="top-box box-azul">
                <h1><?php echo $idioma["titulo"]; ?></h1>
                <?php if($forumObj->verificaPermissao($forum['permissoes'], /*$url[0].*/'aluno|topicos|1', false)) { ?>
                    <a href="<?php echo '/'.$url[0].'/'.$url[1].'/'.$url[2].'/'.$url[3].'/'.$url[4].'/'.$url[5].'/'.$url[6].'/'.$url[7].'/cadastrar'; ?>" class="create-forum abrirModal"><i class="icon-plus"><strong><?php echo $idioma["criar_topico"]; ?></strong></i></a>
                <?php } ?>
            </div>
            <h2 class="ball-icon">&#8227;</h2> 
            <!-- /Rota Topo -->        
            <div class="clear"></div>
            <div class="row-fluid box-item">
                <div class="span12">                
                    <div class="abox extra-align">
                        <div class="row-fluid">
                            <div class="span8 m-box">
                                <ul class="breadcrumb">
                                    <li>
                                        <a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $url[3]; ?>/<?php echo $url[4]; ?>/foruns"><?php echo $idioma["foruns"]; ?></a>
                                        <span class="divider">/</span>
                                    </li>
                                    <li>
                                        <a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $url[3]; ?>/<?php echo $url[4]; ?>/foruns/<?php echo $topico["idforum"]; ?>/topicos"><?php echo $idioma["topicos"]; ?></a>
                                        <span class="divider">/</span>
                                    </li>
                                    <li class="active"><?php echo $idioma["mensagens"]; ?></li>
                                </ul>                       
                            </div>
                            <?php if($forumObj->verificaPermissao($forum["permissoes"], /*$url[0].*/"aluno|topicos|2", false) && $topico["bloqueado"] == "desbloqueado") { ?>
                                <div class="span4 m-box">
                                    <div class="r-align">
                                        <a href="<?php echo '/'.$url[0].'/'.$url[1].'/'.$url[2].'/'.$url[3].'/'.$url[4].'/'.$url[5].'/'.$url[6].'/'.$url[7].'/'.$url[8].'/'.$url[9].'/cadastrar'; ?>" class="btn btn-verde abrirModal"><i class="icon-edit"></i> <?php echo $idioma["responder_topico"]; ?></a>
                                    </div>                        
                                </div>
                            <?php } ?> 
                        </div>
                        <?php if($_POST["msg"]) { ?>
                            <div class="alert alert-success fade in">
                                <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                                <strong><?php echo $idioma[$_POST["msg"]]; ?></strong>
                            </div>
                        <?php } ?> 
                        
                        <!-- visualização mobile -->
                        <div class="row-fluid visible-phone visible-tablet">
                            
                            <div class="span12 abox">
                                <div class="row-fluid">
                                    <div class="span12 rel-box">
                                        <div class="top-forum" id="top-t-<?php echo $topico['idtopico']; ?>">
                                            <div class="row-fluid">
                                                <div class="span6 resume-forum">
                                                    <div class="contact-avatar l-align">
                                                        <?php
                                                        $pastaAvatar = 'pessoas_avatar';
                                                        if($topico['criado_por']['tipo'] == 'professor') {
                                                            $pastaAvatar = 'professores_avatar';
                                                        } elseif($topico['criado_por']['tipo'] == 'gestor') {
                                                            $pastaAvatar = 'usuariosadm_avatar';
                                                        }
                                                        ?>
                                                        <img src="/api/get/imagens/<?php echo $pastaAvatar; ?>/56/56/<?php echo $topico['criado_por']['avatar']; ?>" alt="Avatar">
                                                    </div>
                                                    <div class="details-resume">
                                                        <p><i><?php echo $idioma["topico_criado_por"]; ?> <?php echo $topico['criado_por']['nome']; ?></i></p>
                                                        <p><?php echo $topico['nome']; ?></p>
                                                    </div>
                                                </div>
                                                <div class="span6">
                                                    <div class="social-forum">
                                                        <i class="icon-thumbs-up-alt"></i>
                                                        <p><?php echo $topico['total_curtiu']; ?> <?php echo $idioma["curtidas"]; ?></p>
                                                    </div>                               
                                                    <div class="social-forum">
                                                        <i class="icon-exclamation"></i>
                                                        <p><?php echo $topico['total_mensagens']; ?> <?php echo $idioma["respostas"]; ?></p>
                                                    </div>
                                                    <div class="social-forum">
                                                        <i class="icon-smile"></i>
                                                        <p><?php echo $topico['visualizacoes']; ?> <?php echo $idioma["visitas"]; ?></p>
                                                    </div>
                                                </div>
                                            </div>          
                                        </div>
                                        <div class="box-gray extra-align">
                                            <p>
											<?php 
											if($topico["moderado"] == "S") {
												echo nl2br($topico["moderado_mensagem"]);
											} else {
												echo nl2br($topico["mensagem"]);
											}
											if($topico["arquivo_servidor"]) { ?>
                                                <p><a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $url[3]; ?>/<?php echo $url[4]; ?>/<?php echo $url[5]; ?>/<?php echo $url[6]; ?>/<?php echo $url[7]; ?>/<?php echo $url[8]; ?>/download"><?php echo $idioma['arquivo'].' '.$topico["arquivo_nome"].' ('.tamanhoArquivo($topico["arquivo_tamanho"]).')'; ?></a></p>
                                            <?php } ?>
                                            <hr />
                                            <?php 
                                            if($forumObj->verificaPermissao($forum["permissoes"], /*$url[0].*/"aluno|topicos|6", false)) { 
                                                $curtiuTopico = $forumObj->VerificaQualTipoVotoTopicoMensagem('curtir_topico', $topico['idtopico']);
                                                //$forumObj->verificaPermissao($forum["permissoes"]
                                                ?>
                                                <div class="btn btn-azul votar" id="<?php echo $topico['idtopico']; ?>" name="curtir_topico" style="<?php if($curtiuTopico['tipo'] == 'curtiu') { ?>background:#638db4;<?php } ?>"><i class="icon-thumbs-up-alt"></i> <?php echo $topico['total_curtiu']; ?></div> 
                                                <div class="btn btn-azul votar" id="<?php echo $topico['idtopico']; ?>" name="nao_curtir_topico" style="<?php if($curtiuTopico['tipo'] == 'nao_curtiu') { ?>background:#638db4;<?php } ?>"><i class="icon-thumbs-down-alt"></i> <?php echo $topico['total_nao_curtiu']; ?></div> 
                                            <?php } ?>
                                            <?php if($forumObj->verificaPermissao($forum["permissoes"], /*$url[0].*/"aluno|topicos|2", false) && $topico["bloqueado"] == "desbloqueado") { ?>
                                                <a href="<?php echo '/'.$url[0].'/'.$url[1].'/'.$url[2].'/'.$url[3].'/'.$url[4].'/'.$url[5].'/'.$url[6].'/'.$url[7].'/'.$url[8].'/'.$url[9].'/cadastrar'; ?>" class="btn btn-verde abrirModal"><i class="icon-edit"></i> <?php echo $idioma["responder_topico"]; ?></a>
                                            <?php } ?>
                                        </div>
                                        <div class="clear"></div>
                                        <?php /*?><div class="row-fluid  mini-box">
                                            <div id="resp-t-<?php echo $topico['idtopico']; ?>" class="span12 respond-forum" style="display:none">
                                                <div class="">
                                                    <form method="post" id="form_responder" name="form_responder" enctype="multipart/form-data">
                                                        <input name="acao" id="acao" type="hidden" value="responder_topico" />
                                                        <textarea id="mensagem" name="mensagem" placeholder="<?php echo $idioma["mensagem_responder"]; ?>"></textarea>
                                                        <i id="t-<?php echo $topico['idtopico']; ?>" class="icon-remove m-box close-textarea" data-dismiss="modal"></i>                                                        
                                                        <div class="row-fluid action-respond">
                                                            <div class="span9">
                                                                <p class="no-margin"><input id="form_arquivo" name="arquivo" type="file" /></p>
                                                            </div>
                                                            <div class="span3">
                                                            <input type="submit" class="btn btn-azul btn-send r-align btn-mob" value="<?php echo $idioma["enviar"]; ?>" >
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div><?php */?>
                                        <?php 
                                        foreach($respostas as $resposta) { 
											if($resposta["oculto"] == "N" || ($resposta["oculto"] == "S" && $forumObj->verificaPermissao($forum["permissoes"], /*$url[0].*/"aluno|mensagens|2", false))) {
												/*$mensagemModeradaOcultada = false;
												if($resposta["moderado"] == "S") {
													$mensagemModeradaOcultada = 1;
													if($resposta["oculto"] == "S") {
														$mensagemModeradaOcultada = 3;
													}
												} elseif($resposta["oculto"] == "S") {
													$mensagemModeradaOcultada = 2;
												}*/
												
												$pastaAvatar = 'pessoas_avatar';
												if($resposta['criado_por']['tipo'] == 'professor') {
													$pastaAvatar = 'professores_avatar';
												} elseif($resposta['criado_por']['tipo'] == 'gestor') {
													$pastaAvatar = 'usuariosadm_avatar';
												}
												?>
                                                <div class="top-forum t-box" id="top-m-<?php echo $resposta['idmensagem']; ?>">
                                                    <div class="row-fluid">
                                                        <div class="span12">
                                                            <div class="contact-avatar l-align">
                                                                <img src="/api/get/imagens/<?php echo $pastaAvatar; ?>/56/56/<?php echo $resposta['criado_por']['avatar']; ?>" alt="Avatar">
                                                            </div>
                                                            <div class="details-resume">
                                                                <p><i><?php echo $idioma["resposta_de"]; ?> <?php echo $resposta['criado_por']['nome']; ?></i></p>
                                                                <p><?php echo formataData($resposta['data_cad'],'br',1); ?></p>
                                                            </div>
                                                        </div>
                                                    </div>          
                                                </div>
                                                <div class="box-gray extra-align">
                                                    <?php if($resposta["idmensagem_associada"]) { ?>
                                                        <div class="box-white extra-align m-box">
                                                            <i class="icon-quote-right r-align"></i>
                                                            <h3 class="m-box"><?php echo $resposta["associada"]["criado_por"]["nome"]; ?></h3>                              
                                                            <p>
																<?php 
                                                                if($resposta["associada"]["moderado"] == "S") {
                                                                    echo nl2br($resposta["associada"]["moderado_mensagem"]);
                                                                } else {
                                                                    echo nl2br($resposta["associada"]["mensagem"]);
                                                                }
                                                                ?>
                                                            </p>
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
                                                        <p><a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $url[3]; ?>/<?php echo $url[4]; ?>/<?php echo $url[5]; ?>/<?php echo $url[6]; ?>/<?php echo $url[7]; ?>/<?php echo $url[8]; ?>/<?php echo $url[9]; ?>/<?php echo $resposta['idmensagem']; ?>/download"><?php echo $idioma['arquivo'].$resposta["arquivo_nome"].' ('.tamanhoArquivo($resposta["arquivo_tamanho"]).')'; ?></a></p>
                                                    <?php } ?>
                                                    <hr />
                                                    <?php if($forumObj->verificaPermissao($forum["permissoes"], /*$url[0].*/"aluno|topicos|6", false)) { ?>
                                                        <div class="btn btn-azul votar" id="<?php echo $resposta['idmensagem']; ?>" name="curtir_mensagem"><i class="icon-thumbs-up-alt"></i> <?php echo $resposta['total_curtiu']; ?></div> 
                                                        <div class="btn btn-azul votar" id="<?php echo $resposta['idmensagem']; ?>" name="nao_curtir_mensagem"><i class="icon-thumbs-down-alt"></i> <?php echo $resposta['total_nao_curtiu']; ?></div> 
                                                    <?php } ?>
                                                    <?php 
                                                    if($forumObj->verificaPermissao($forum["permissoes"], /*$url[0].*/"aluno|topicos|2", false) && $topico["bloqueado"] == "desbloqueado") { 
                                                        $primeiroNome = explode(' ', $resposta['criado_por']['nome']);
                                                        ?>
                                                        <a href="<?php echo '/'.$url[0].'/'.$url[1].'/'.$url[2].'/'.$url[3].'/'.$url[4].'/'.$url[5].'/'.$url[6].'/'.$url[7].'/'.$url[8].'/'.$url[9].'/cadastrar'; ?>" class="btn btn-verde abrirModal"><i class="icon-edit"></i> <?php echo $idioma["responder_topico"]; ?></a>
                                                        <a href="<?php echo '/'.$url[0].'/'.$url[1].'/'.$url[2].'/'.$url[3].'/'.$url[4].'/'.$url[5].'/'.$url[6].'/'.$url[7].'/'.$url[8].'/'.$url[9].'/'.$resposta['idmensagem'].'/responder'; ?>" class="btn btn-laranja abrirModal"><i class="icon-edit"></i> <?php echo $idioma["responder"].' '.$primeiroNome[0]; ?></a>
													<?php } ?>
                                                </div>
                                                <div class="clear"></div>
                                                <?php /*?><div class="row-fluid  mini-box">
                                                    <div id="resp-m-<?php echo $resposta['idmensagem']; ?>" class="span12 respond-forum" style="display:none">
                                                        <div class="">
                                                            <form method="post" id="form_responder" name="form_responder" enctype="multipart/form-data">
                                                                <input name="acao" id="acao" type="hidden" value="responder_topico" />
                                                                <input name="idmensagem_associada" id="idmensagem_associada" type="hidden" value="<?php echo $resposta['idmensagem']; ?>" />
                                                                <textarea id="mensagem" name="mensagem" placeholder="<?php echo $idioma["mensagem_responder"]; ?>"></textarea>
                                                                <i id="m-<?php echo $resposta['idmensagem']; ?>" class="icon-remove m-box close-textarea" data-dismiss="modal"></i>                                                            
                                                                <div class="row-fluid action-respond">
                                                                    <div class="span9">
                                                                        <p class="no-margin"><input id="form_arquivo" name="arquivo" type="file" /></p>
                                                                    </div>
                                                                    <div class="span3">
                                                                    <input type="submit" class="btn btn-azul btn-send r-align btn-mob" value="<?php echo $idioma["enviar"]; ?>" >
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div><?php */?>
                                            <?php }
										} ?> 
                                    </div>
                                </div>           
                            </div>

                            <!-- Participantes -->
                            <div class="span12 m-box" style="margin-left: 0;">
                                <?php /*?><div class="row-fluid m-box">
                                    <div class="span12">
                                        <?php if(!$assinatura) { ?>
                                            <div class="btn btn-azul btn-large btn-sign">
                                                <?php echo $idioma["assinar_topico"]; ?>
                                                <p><?php echo $idioma["assinar_topico_descricao"]; ?></p>
                                            </div>
                                        <?php } else { ?>
                                            <div class="btn btn-vermelho btn-large btn-unsubscribe">
                                                <?php echo $idioma["desassinar_topico"]; ?>
                                                <p><?php echo $idioma["desassinar_topico_descricao"]; ?></p>
                                            </div>
                                        <?php } ?>
                                    </div>  
                                </div><?php */?>  
                                <div class="row-fluid">
                                    <div class="span12">
                                        <div class="top-box box-amarelo">                        
                                            <ul class="nav nav-tabs no-border no-margin">
                                                <li class="active"><div class="toggle-title active-tab tab-first" style="color:#000000;"><?php echo $idioma["alunos_participantes"]; ?></div></li>
                                                <li><div class="toggle-title"><div class="title-cont-number"><?php echo count($participantes); ?></div></div></li>
                                            </ul>
                                        </div>
                                        <div class="tabbable">
                                            <div class="tab-content">
                                                <div class="tab-pane active" id="tab1">
                                                    <div class="box-gray extra-align">
                                                        <?php foreach($participantes as $participante) { ?>
                                                            <div class="row-fluid m-box">
                                                                <div class="span12">
                                                                    <div class="contact-avatar l-align">
                                                                        <img src="/api/get/imagens/pessoas_avatar/56/56/<?php echo $participante['avatar_servidor']; ?>" alt="Avatar">
                                                                    </div>
                                                                    <div class="contact-forum">
                                                                        <p><i><?php echo $participante['nome']; ?></i></p>
                                                                        <div class="cont-number"><?php echo $participante['mensagens']; ?></div>
                                                                    </div>
                                                                </div>
                                                            </div> 
                                                        <?php } ?>                            
                                                    </div>
                                                </div>
                                            </div>
                                        </div>                    
                                    </div>
                                </div>      
                            </div>
                            <!-- fim participantes -->
                        </div>
                        <!-- fim visualização mobile -->

                        <!-- visualização comum -->
                        <div class="row-fluid no-mobile">
                            
                            <!-- Participantes -->
                            <div class="span4 m-box" style="margin-left: 0;">
                                <?php /*?><div class="row-fluid m-box">
                                    <div class="span12">
                                        <?php if(!$assinatura) { ?>
                                            <div class="btn btn-azul btn-large btn-sign">
                                                <?php echo $idioma["assinar_topico"]; ?>
                                                <p><?php echo $idioma["assinar_topico_descricao"]; ?></p>
                                            </div>
                                        <?php } else { ?>
                                            <div class="btn btn-vermelho btn-large btn-unsubscribe">
                                                <?php echo $idioma["desassinar_topico"]; ?>
                                                <p><?php echo $idioma["desassinar_topico_descricao"]; ?></p>
                                            </div>
                                        <?php } ?>
                                    </div>  
                                </div><?php */?>  
                                <div class="row-fluid">
                                    <div class="span12">
                                        <div class="top-box box-amarelo">                        
                                            <ul class="nav nav-tabs no-border no-margin">
                                                <li class="active"><div class="toggle-title active-tab tab-first" style="color:#000000;"><?php echo $idioma["alunos_participantes"]; ?></div></li>
                                                <li><div class="toggle-title"><div class="title-cont-number"><?php echo count($participantes); ?></div></div></li>
                                            </ul>
                                        </div>
                                        <div class="tabbable">
                                            <div class="tab-content">
                                                <div class="tab-pane active" id="tab1">
                                                    <div class="box-gray extra-align">
                                                        <?php foreach($participantes as $participante) { ?>
                                                            <div class="row-fluid m-box">
                                                                <div class="span12">
                                                                    <div class="contact-avatar l-align">
                                                                        <img src="/api/get/imagens/pessoas_avatar/56/56/<?php echo $participante['avatar_servidor']; ?>" alt="Avatar">
                                                                    </div>
                                                                    <div class="contact-forum">
                                                                        <p><i><?php echo $participante['nome']; ?></i></p>
                                                                        <div class="cont-number"><?php echo $participante['mensagens']; ?></div>
                                                                    </div>
                                                                </div>
                                                            </div> 
                                                        <?php } ?>                            
                                                    </div>
                                                </div>
                                            </div>
                                        </div>                    
                                    </div>
                                </div>      
                            </div>
                            <!-- fim participantes -->

                            <div class="span8 abox">
                                <div class="row-fluid">
                                    <div class="span12 rel-box">
                                        <div class="top-forum" id="top-t-<?php echo $topico['idtopico']; ?>">
                                            <div class="row-fluid">
                                                <div class="span6 resume-forum">
                                                    <div class="contact-avatar l-align">
                                                        <?php
                                                        $pastaAvatar = 'pessoas_avatar';
                                                        if($topico['criado_por']['tipo'] == 'professor') {
                                                            $pastaAvatar = 'professores_avatar';
                                                        } elseif($topico['criado_por']['tipo'] == 'gestor') {
                                                            $pastaAvatar = 'usuariosadm_avatar';
                                                        }
                                                        ?>
                                                        <img src="/api/get/imagens/<?php echo $pastaAvatar; ?>/56/56/<?php echo $topico['criado_por']['avatar']; ?>" alt="Avatar">
                                                    </div>
                                                    <div class="details-resume">
                                                        <p><i><?php echo $idioma["topico_criado_por"]; ?> <?php echo $topico['criado_por']['nome']; ?></i></p>
                                                        <p><?php echo $topico['nome']; ?></p>
                                                    </div>
                                                </div>
                                                <div class="span6">
                                                    <div class="social-forum">
                                                        <i class="icon-thumbs-up-alt"></i>
                                                        <p><?php echo $topico['total_curtiu']; ?> <?php echo $idioma["curtidas"]; ?></p>
                                                    </div>                               
                                                    <div class="social-forum">
                                                        <i class="icon-exclamation"></i>
                                                        <p><?php echo $topico['total_mensagens']; ?> <?php echo $idioma["respostas"]; ?></p>
                                                    </div>
                                                    <div class="social-forum">
                                                        <i class="icon-smile"></i>
                                                        <p><?php echo $topico['visualizacoes']; ?> <?php echo $idioma["visitas"]; ?></p>
                                                    </div>
                                                </div>
                                            </div>          
                                        </div>
                                        <div class="box-gray extra-align">
                                            <p>
                                            <?php 
                                            if($topico["moderado"] == "S") {
                                                echo nl2br($topico["moderado_mensagem"]);
                                            } else {
                                                echo nl2br($topico["mensagem"]);
                                            }
                                            if($topico["arquivo_servidor"]) { ?>
                                                <p><a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $url[3]; ?>/<?php echo $url[4]; ?>/<?php echo $url[5]; ?>/<?php echo $url[6]; ?>/<?php echo $url[7]; ?>/<?php echo $url[8]; ?>/download"><?php echo $idioma['arquivo'].' '.$topico["arquivo_nome"].' ('.tamanhoArquivo($topico["arquivo_tamanho"]).')'; ?></a></p>
                                            <?php } ?>
                                            <hr />
                                            <?php 
                                            if($forumObj->verificaPermissao($forum["permissoes"], /*$url[0].*/"aluno|topicos|6", false)) { 
                                                $curtiuTopico = $forumObj->VerificaQualTipoVotoTopicoMensagem('curtir_topico', $topico['idtopico']);
                                                //$forumObj->verificaPermissao($forum["permissoes"]
                                                ?>
                                                <div class="btn btn-azul votar" id="<?php echo $topico['idtopico']; ?>" name="curtir_topico" style="<?php if($curtiuTopico['tipo'] == 'curtiu') { ?>background:#638db4;<?php } ?>"><i class="icon-thumbs-up-alt"></i> <?php echo $topico['total_curtiu']; ?></div> 
                                                <div class="btn btn-azul votar" id="<?php echo $topico['idtopico']; ?>" name="nao_curtir_topico" style="<?php if($curtiuTopico['tipo'] == 'nao_curtiu') { ?>background:#638db4;<?php } ?>"><i class="icon-thumbs-down-alt"></i> <?php echo $topico['total_nao_curtiu']; ?></div> 
                                            <?php } ?>
                                            <?php if($forumObj->verificaPermissao($forum["permissoes"], /*$url[0].*/"aluno|topicos|2", false) && $topico["bloqueado"] == "desbloqueado") { ?>
                                                <a href="<?php echo '/'.$url[0].'/'.$url[1].'/'.$url[2].'/'.$url[3].'/'.$url[4].'/'.$url[5].'/'.$url[6].'/'.$url[7].'/'.$url[8].'/'.$url[9].'/cadastrar'; ?>" class="btn btn-verde abrirModal"><i class="icon-edit"></i> <?php echo $idioma["responder_topico"]; ?></a>
                                            <?php } ?>
                                        </div>
                                        <div class="clear"></div>
                                        <?php /*?><div class="row-fluid  mini-box">
                                            <div id="resp-t-<?php echo $topico['idtopico']; ?>" class="span12 respond-forum" style="display:none">
                                                <div class="">
                                                    <form method="post" id="form_responder" name="form_responder" enctype="multipart/form-data">
                                                        <input name="acao" id="acao" type="hidden" value="responder_topico" />
                                                        <textarea id="mensagem" name="mensagem" placeholder="<?php echo $idioma["mensagem_responder"]; ?>"></textarea>
                                                        <i id="t-<?php echo $topico['idtopico']; ?>" class="icon-remove m-box close-textarea" data-dismiss="modal"></i>                                                        
                                                        <div class="row-fluid action-respond">
                                                            <div class="span9">
                                                                <p class="no-margin"><input id="form_arquivo" name="arquivo" type="file" /></p>
                                                            </div>
                                                            <div class="span3">
                                                            <input type="submit" class="btn btn-azul btn-send r-align btn-mob" value="<?php echo $idioma["enviar"]; ?>" >
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div><?php */?>
                                        <?php 
                                        foreach($respostas as $resposta) { 
                                            if($resposta["oculto"] == "N" || ($resposta["oculto"] == "S" && $forumObj->verificaPermissao($forum["permissoes"], /*$url[0].*/"aluno|mensagens|2", false))) {
                                                /*$mensagemModeradaOcultada = false;
                                                if($resposta["moderado"] == "S") {
                                                    $mensagemModeradaOcultada = 1;
                                                    if($resposta["oculto"] == "S") {
                                                        $mensagemModeradaOcultada = 3;
                                                    }
                                                } elseif($resposta["oculto"] == "S") {
                                                    $mensagemModeradaOcultada = 2;
                                                }*/
                                                
                                                $pastaAvatar = 'pessoas_avatar';
                                                if($resposta['criado_por']['tipo'] == 'professor') {
                                                    $pastaAvatar = 'professores_avatar';
                                                } elseif($resposta['criado_por']['tipo'] == 'gestor') {
                                                    $pastaAvatar = 'usuariosadm_avatar';
                                                }
                                                ?>
                                                <div class="top-forum t-box" id="top-m-<?php echo $resposta['idmensagem']; ?>">
                                                    <div class="row-fluid">
                                                        <div class="span12">
                                                            <div class="contact-avatar l-align">
                                                                <img src="/api/get/imagens/<?php echo $pastaAvatar; ?>/56/56/<?php echo $resposta['criado_por']['avatar']; ?>" alt="Avatar">
                                                            </div>
                                                            <div class="details-resume">
                                                                <p><i><?php echo $idioma["resposta_de"]; ?> <?php echo $resposta['criado_por']['nome']; ?></i></p>
                                                                <p><?php echo formataData($resposta['data_cad'],'br',1); ?></p>
                                                            </div>
                                                        </div>
                                                    </div>          
                                                </div>
                                                <div class="box-gray extra-align">
                                                    <?php if($resposta["idmensagem_associada"]) { ?>
                                                        <div class="box-white extra-align m-box">
                                                            <i class="icon-quote-right r-align"></i>
                                                            <h3 class="m-box"><?php echo $resposta["associada"]["criado_por"]["nome"]; ?></h3>                              
                                                            <p>
                                                                <?php 
                                                                if($resposta["associada"]["moderado"] == "S") {
                                                                    echo nl2br($resposta["associada"]["moderado_mensagem"]);
                                                                } else {
                                                                    echo nl2br($resposta["associada"]["mensagem"]);
                                                                }
                                                                ?>
                                                            </p>
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
                                                        <p><a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $url[3]; ?>/<?php echo $url[4]; ?>/<?php echo $url[5]; ?>/<?php echo $url[6]; ?>/<?php echo $url[7]; ?>/<?php echo $url[8]; ?>/<?php echo $url[9]; ?>/<?php echo $resposta['idmensagem']; ?>/download"><?php echo $idioma['arquivo'].$resposta["arquivo_nome"].' ('.tamanhoArquivo($resposta["arquivo_tamanho"]).')'; ?></a></p>
                                                    <?php } ?>
                                                    <hr />
                                                    <?php if($forumObj->verificaPermissao($forum["permissoes"], /*$url[0].*/"aluno|topicos|6", false)) { ?>
                                                        <div class="btn btn-azul votar" id="<?php echo $resposta['idmensagem']; ?>" name="curtir_mensagem"><i class="icon-thumbs-up-alt"></i> <?php echo $resposta['total_curtiu']; ?></div> 
                                                        <div class="btn btn-azul votar" id="<?php echo $resposta['idmensagem']; ?>" name="nao_curtir_mensagem"><i class="icon-thumbs-down-alt"></i> <?php echo $resposta['total_nao_curtiu']; ?></div> 
                                                    <?php } ?>
                                                    <?php 
                                                    if($forumObj->verificaPermissao($forum["permissoes"], /*$url[0].*/"aluno|topicos|2", false) && $topico["bloqueado"] == "desbloqueado") { 
                                                        $primeiroNome = explode(' ', $resposta['criado_por']['nome']);
                                                        ?>
                                                        <a href="<?php echo '/'.$url[0].'/'.$url[1].'/'.$url[2].'/'.$url[3].'/'.$url[4].'/'.$url[5].'/'.$url[6].'/'.$url[7].'/'.$url[8].'/'.$url[9].'/cadastrar'; ?>" class="btn btn-verde abrirModal"><i class="icon-edit"></i> <?php echo $idioma["responder_topico"]; ?></a>
                                                        <a href="<?php echo '/'.$url[0].'/'.$url[1].'/'.$url[2].'/'.$url[3].'/'.$url[4].'/'.$url[5].'/'.$url[6].'/'.$url[7].'/'.$url[8].'/'.$url[9].'/'.$resposta['idmensagem'].'/responder'; ?>" class="btn btn-laranja abrirModal"><i class="icon-edit"></i> <?php echo $idioma["responder"].' '.$primeiroNome[0]; ?></a>
                                                    <?php } ?>
                                                </div>
                                                <div class="clear"></div>
                                                <?php /*?><div class="row-fluid  mini-box">
                                                    <div id="resp-m-<?php echo $resposta['idmensagem']; ?>" class="span12 respond-forum" style="display:none">
                                                        <div class="">
                                                            <form method="post" id="form_responder" name="form_responder" enctype="multipart/form-data">
                                                                <input name="acao" id="acao" type="hidden" value="responder_topico" />
                                                                <input name="idmensagem_associada" id="idmensagem_associada" type="hidden" value="<?php echo $resposta['idmensagem']; ?>" />
                                                                <textarea id="mensagem" name="mensagem" placeholder="<?php echo $idioma["mensagem_responder"]; ?>"></textarea>
                                                                <i id="m-<?php echo $resposta['idmensagem']; ?>" class="icon-remove m-box close-textarea" data-dismiss="modal"></i>                                                            
                                                                <div class="row-fluid action-respond">
                                                                    <div class="span9">
                                                                        <p class="no-margin"><input id="form_arquivo" name="arquivo" type="file" /></p>
                                                                    </div>
                                                                    <div class="span3">
                                                                    <input type="submit" class="btn btn-azul btn-send r-align btn-mob" value="<?php echo $idioma["enviar"]; ?>" >
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div><?php */?>
                                            <?php }
                                        } ?> 
                                    </div>
                                </div>           
                            </div>
                        </div>
                        <!-- fim visualização comum -->
                    </div>
                </div>
            </div>
        </div>
        <!-- /Box -->
    </div>
</div>
<!-- /Conteudo -->
<?php incluirLib("rodape", $config, $usuario); ?>
<script src="/assets/js/validation.js"></script>
<script type="text/javascript">
$( document ).ready(function() {
	$('.abrirModal').click(function(e) {
		e.preventDefault();
		var url = $(this).attr('href');
		if (url.indexOf('#') == 0) {
			$(url).modal('open').on('shown', function () { }).on("hidden", function () { $(this).remove(); });
		} else {
			$.get(url, function(data) {
				$('<div class="modal hide fade text-side-two extra-align" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">'+data+'</div>').modal().on('shown', function () { }).on("hidden", function () { $(this).remove(); });
			}).success(function() { 
				$('input:text:visible:first').focus();
			});
		}
	});
	
	<?php /*?>$(".close-textarea, .btn-responde").click(function() {
		var id = $(this).attr('id');
		$("#resp-"+id).toggle();
		
		$('html, body').animate({
			scrollTop: $("#top-"+id).offset().top
		}, 2000);
	});<?php */?>
	
	<?php if($forumObj->verificaPermissao($forum["permissoes"], /*$url[0].*/'aluno|topicos|6', false)) { ?>
	$(".votar").click(function() {
		var id = $(this).attr("id");
		var name = $(this).attr("name");
		var dataString = 'id='+id+'&tipo='+name;
		var parent = $(this);
		var htmlAntigo = $(this).html();
		
		$(this).fadeIn(200).html('<img src="/assets/img/forum/local_vote.gif" width="16" height="16" />');
		$.ajax({
			type: "POST",
			url: "/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $url[3]; ?>/<?php echo $url[4]; ?>/foruns/<?php echo $topico["idforum"]; ?>/topicos/<?php echo $topico["idtopico"]; ?>/mensagens/json/curtir",
			data: dataString,
			cache: false,
			success: function(html){
				var jsonObj = jQuery.parseJSON(html);
				if(jsonObj.mensagem == "ja_votou") {
					parent.html(htmlAntigo);
					alert('<?= $idioma["ja_votou"]; ?>');
				} else if(jsonObj.erro_json == "sem_permissao") {
					parent.html(htmlAntigo);
                    alert('<?= $idioma["sem_permissao"]; ?>');
				} else {
					parent.html('<i class="'+jsonObj.icone+'"></i> '+jsonObj.contador);
                    parent.css('background-color',jsonObj.background);
					if(jsonObj.mensagem == 'voto_cancelado_sucesso') {
                        alert('<?= $idioma["voto_cancelado_sucesso"]; ?>');
                    } else {
                        alert('<?= $idioma["voto_computado_sucesso"]; ?>');
                    }
                    
				}		
			}
		});
		
		return false;
	});
	<?php } ?>
	
});
</script>
</body>
</html>