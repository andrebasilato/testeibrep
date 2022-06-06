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
            <div class="top-box box-amarelo">
                <h1><?php echo $idioma['titulo']; ?></h1>
                <i class="icon-comment"></i>            
            </div>
            <h2 class="ball-icon">&bull;</h2> 
            <div class="clear"></div>
            <div class="row-fluid box-item">
                <div class="span12">                
                    <div class="abox">
                        <div class="span12">
                            <div class="row-fluid m-box">
                                <?php /*?><div class="span12">
                                    <div class="top-box box-gray">
                                        <h1><?php echo $idioma['lista']; ?></h1>
                                        <div class="r-align">
                                            <a href=""><div class="btn btn-verde btn-new-chat"><?php echo $idioma['cadastrar']; ?></div></a>
                                        </div>                   
                                    </div>
                                </div><?php */?>   
                                <?php 
                                $contadorChats = 0;
                                $dataHoje = date('Y-m-d');
                                $hoje = strtotime(date('Y-m-d H:i'));
                                foreach($chats as $chat) { 
                                    $contadorChats++;
                                    if($contadorChats == 1) {
                                        ?>
                                        <!-- Linha -->
                                        <div class="row-fluid">
                                            <div class="span12 extra-align">
                                                <div class="row-fluid">
                                                    <div class="span12">
									<?php }
													$inicioEntradaAluno = strtotime($chat['inicio_entrada_aluno']);
                                                    $fimEntradaAluno = strtotime($chat['fim_entrada_aluno']);

                                                    $inicioEntrada = explode(' ', $chat['inicio_entrada_aluno']);
                                                    $dataInicioEntrada = $inicioEntrada[0];

                                                    $link = 'href="#myModalChats" data-toggle="modal"';
                                                    $liberado = 'off';

                                                    if($dataHoje == $dataInicioEntrada && $hoje <= $inicioEntradaAluno) {
                                                        $corBotao = 'amarelo';
                                                        $textoBotao = 'chat_dia';
                                                        
                                                        $fimEntrada = explode(' ', $chat['fim_entrada_aluno']);
                                                        $idioma['horario_chat_hoje'] = sprintf($idioma['horario_chat_hoje'], $inicioEntrada[1], $fimEntrada[1]);
                                                        $textoModal = 'horario_chat_hoje';
                                                    } elseif(($hoje >= $inicioEntradaAluno || !$inicioEntradaAluno) && ($hoje <= $fimEntradaAluno || !$fimEntradaAluno)) {
                                                        $corBotao = 'azul';
                                                        $textoBotao = 'abrir';
                                                        
                                                        $link = 'href="/'.$url[0].'/'.$url[1].'/'.$url[2].'/'.$matricula['idmatricula'].'/'.$url[4].'/chats/'.$chat['idchat'].'" target="_blank"';
                                                        $liberado = 'on';
                                                    } elseif(($hoje >= $inicioEntradaAluno || !$inicioEntradaAluno) && ($hoje > $fimEntradaAluno)) {
                                                        $corBotao = 'vermelho';
                                                        $textoBotao = 'ler_chat';
                                                        
                                                        $link = 'href="/'.$url[0].'/'.$url[1].'/'.$url[2].'/'.$matricula['idmatricula'].'/'.$url[4].'/chats/'.$chat['idchat'].'" target="_blank"';
                                                    } elseif($hoje < $inicioEntradaAluno) {
                                                        $corBotao = 'verde';
                                                        $textoBotao = 'chat_proximo';
                                                        $textoModal = 'chat_fechado';
                                                    }
                                                    ?>
                                                    <!-- Chat -->
                                                    <div class="span4 extra-align box-gray m-box">
                                                        <div class="contact-avatar l-align">
                                                            <img src="/api/get/imagens/avas_chats_imagem/56/56/<?php echo $chat["imagem_servidor"]; ?>" alt="Avatar">
                                                            <div class="status <?php echo $liberado; ?>"></div>
                                                        </div>
                                                        <div class="text-side-two details-resume m-box">
                                                            <h1><?php echo $chat['nome']; ?></h1>                                  
                                                            <p><i>
																<?php if($chat['inicio_entrada_aluno'] && $chat['inicio_entrada_aluno'] != '0000-00-00 00:00:00') { echo formataData($chat['inicio_entrada_aluno'], 'br',1); } else { echo '--'; } ?>
                                                                 | 
																<?php if($chat['fim_entrada_aluno'] && $chat['fim_entrada_aluno'] != '0000-00-00 00:00:00') { echo formataData($chat['fim_entrada_aluno'], 'br',1); } else { echo '--'; } ?></i></p>                                                                   
                                                        </div>  
                                                        <a <?php echo $link; ?>><div class="btn btn-<?php echo $corBotao; ?> btn-responsive"><?php echo $idioma[$textoBotao]; ?></div></a>
                                                    </div>                  
                                                <!-- /Chat -->
									<?php 
									if($contadorChats == 3) { 
										$contadorChats = 0;
									?>    
                                                        </div> 
                                                    </div> 
                                                </div>                                
                                            </div>
                                            <!-- /Linha -->
                                    <?php } ?> 
								<?php } 
								if($contadorChats > 0) {
								?>   
                                                        </div> 
                                                    </div> 
                                                </div>                                
                                            </div>                                            
                                            <!-- /Linha -->    
								<?php } ?> 
                                <!-- Modal chats -->
                                <div class="mbox-side-one">
                                    <div id="myModalChats" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-side-one">
                                            <i class="icon-quote-right" data-dismiss="modal"></i>
                                            <p><div class="alert alert-danger text-center box-size-90"><strong><?php echo $idioma[$textoModal]; ?></strong></div></p>
                                            <i class="icon-remove m-box" data-dismiss="modal"> <strong><?php echo $idioma['fechar']; ?></strong></i>
                                        </div>                    
                                    </div>
                                </div>
                                <!-- /Modal chats -->
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