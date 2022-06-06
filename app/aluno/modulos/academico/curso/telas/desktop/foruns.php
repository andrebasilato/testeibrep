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
                <h1><?php echo $idioma['titulo']; ?></h1>
                <i class="icon-keyboard"></i>            
            </div>
            <h2 class="ball-icon">&bull;</h2> 
            <!-- /Rota Topo -->        
            <div class="clear"></div>
            <div class="row-fluid box-item">
                <div class="span12">                
                    <div class="abox extra-align">
                        <div class="row-fluid">
                            <div class="span4 m-box">
                                <div class="top-box box-amarelo">                        
                                    <ul class="nav nav-tabs no-border no-margin">
                                        <li class="active"><a class="toggle-title active-tab tab-first" href="#tab1" data-toggle="tab"><?php echo $idioma['topicos_populares']; ?></a></li>
                                        <li><a class="toggle-title" href="#tab2" data-toggle="tab"><?php echo $idioma['alunos_ativos']; ?></a></li>
                                    </ul>
                                </div>
                                <div class="tabbable">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="tab1">
                                            <div class="box-gray extra-align">
                                                <?php 
												foreach($populares as $popular) { 
													$pastaAvatar = 'pessoas_avatar';
													if($popular['criado_por']['tipo'] == 'professor') {
														$pastaAvatar = 'professores_avatar';
													} elseif($popular['criado_por']['tipo'] == 'gestor') {
														$pastaAvatar = 'usuariosadm_avatar';
													}
													?>
                                                    <div class="row-fluid m-box">
                                                        <div class="span12">
                                                            <div class="contact-avatar l-align">
                                                                <img src="/api/get/imagens/<?php echo $pastaAvatar; ?>/56/56/<?php echo $popular['criado_por']['avatar']; ?>" alt="Avatar">
                                                            </div>
                                                            <div class="contact-forum">
                                                                <a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $matricula['idmatricula']; ?>/<?php echo $url[4]; ?>/<?php echo $url[5]; ?>/<?php echo $popular['idforum']; ?>/topicos/<?php echo $popular['idtopico']; ?>/mensagens"><p><i><?php echo $popular['nome']; ?></i></p></a>
                                                                <div class="cont-number"><?php echo $popular['respostas']; ?></div>
                                                            </div>
                                                        </div>
                                                    </div> 
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="tab2">
                                            <div class="box-gray extra-align">
                                                <?php foreach($alunosAtivos as $alunoAtivo) { ?>
                                                    <div class="row-fluid m-box">
                                                        <div class="span12">
                                                            <div class="contact-avatar l-align">
                                                                <img src="/api/get/imagens/pessoas_avatar/56/56/<?php echo $alunoAtivo['avatar_servidor']; ?>" alt="Avatar">
                                                            </div>
                                                            <div class="contact-forum">
                                                                <p><i><?php echo $alunoAtivo['nome']; ?></i></p>                            
                                                                <div class="cont-number"><?php echo $alunoAtivo['mensagens']; ?></div>
                                                            </div>
                                                        </div>
                                                    </div> 
												<?php } ?>                                             
                                            </div>
                                        </div>
                                    </div>
                                </div>                    
                            </div>
                            <div class="span8">
                                <?php foreach($foruns as $forum) { ?>
                                    <div class="row-fluid m-box">
                                        <div class="span12 box-gray">
                                            <div class="top-box box-verde">
                                                <h1>
													<?php echo $forum['nome']; ?>
                                                    <?php if ($forum["disciplina"]) { ?> | <span><?php echo $idioma['disciplina'].' '.$forum["disciplina"]; ?></span><?php } ?>
                                                </h1>
                                                <a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $matricula['idmatricula']; ?>/<?php echo $url[4]; ?>/<?php echo $url[5]; ?>/<?php echo $forum['idforum']; ?>/topicos"><i class="icon-plus"><strong><?php echo $idioma['ver_todos']; ?></strong></i></a>
                                            </div>
                                            <div class="clear"></div>
                                            <?php 
											foreach($forum['topicos'] as $topico) { 
												$pastaAvatar = 'pessoas_avatar';
												if($topico['criado_por']['tipo'] == 'professor') {
													$pastaAvatar = 'professores_avatar';
												} elseif($topico['criado_por']['tipo'] == 'gestor') {
													$pastaAvatar = 'usuariosadm_avatar';
												}
												?>
                                                <div class="m-box extra-align">
                                                    <div class="row-fluid">
                                                        <div class="span12">
                                                            <div>
                                                                <div class="row-fluid">
                                                                    <div class="span6 resume-forum">
                                                                        <div class="contact-avatar l-align">
                                                                            <img src="/api/get/imagens/<?php echo $pastaAvatar; ?>/56/56/<?php echo $topico['criado_por']['avatar']; ?>" alt="Avatar">
                                                                        </div>
                                                                        <div class="details-resume">
                                                                            <p><i><?php echo $idioma['topico_criado']; ?> <?php echo $topico['criado_por']['nome']; ?></i></p>
                                                                            <a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $matricula['idmatricula']; ?>/<?php echo $url[4]; ?>/<?php echo $url[5]; ?>/<?php echo $topico['idforum']; ?>/topicos/<?php echo $topico['idtopico']; ?>/mensagens"><p><strong><?php echo $topico['nome']; ?></strong></p></a>
                                                                            <?php if($topico['ultima_mensagem_data']) { ?>
                                                                                <p><i><?php echo $idioma['ultima_resposta']; ?> - <?php echo formataData($topico['ultima_mensagem_data'],'br',1); ?></i></p>
																			<?php } ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="span6">                                  
                                                                        <div class="social-forum">
                                                                            <i class="icon-thumbs-up-alt"></i>
                                                                            <p><?php echo $topico['total_curtiu']; ?> <?php echo $idioma['curtidas']; ?></p>
                                                                        </div>
                                                                        <div class="social-forum">
                                                                            <i class="icon-exclamation"></i>
                                                                            <p><?php echo $topico['total_mensagens']; ?> <?php echo $idioma['respostas']; ?></p>
                                                                        </div>
                                                                        <div class="social-forum">
                                                                            <i class="icon-smile"></i>
                                                                            <p><?php echo $topico['visualizacoes']; ?> <?php echo $idioma['visitas']; ?></p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div></div>
                                                </div>


											<?php } 

                                                $forum['participantes'] = explode(",", $forum['participantes']);
                                            ?>
                                                
                                                <!-- Adicionando alunos participantes do fórum -->
                                                <div class="m-box extra-align">
                                                    <div class="row-fluid">
                                                        <div class="span12">
                                                            <div>
                                                                <div class="row-fluid">
                                                                    <div class="span12">
                                                                        <div>
                                                                            <p><strong>Participantes do(s) tópico(s):</strong></p>
                                                                        </div>
                                                                        <div>
                                                                            <?php foreach ($forum['participantes'] as $key => $participante) { ?>
                                                                            
                                                                            <p><?php echo $participante; ?></p> 
                                                                                
                                                                            <?php }?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- fim de alunos participantes -->
                                        </div>
                                    </div>
                                <?php } ?>            
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Box -->
    </div>
</div>
<!-- /Conteudo -->
<?php incluirLib("rodape", $config, $usuario); ?>
</body>
</html>