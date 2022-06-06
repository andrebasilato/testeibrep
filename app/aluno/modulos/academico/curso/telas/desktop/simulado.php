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
            <span class="top-box box-azul">
                <h1><?php echo $idioma['titulo']; ?></h1>
                <i class="icon-file-text-alt"></i>            
            </span>
            <h2 class="ball-icon">&bull;</h2> 
            <div class="clear"></div>
            <div class="row-fluid box-item">
                <div class="span12">                
                    <div class="abox extra-align">
                        <div class="row-fluid m-box">
                            <div class="span3">
                                <div class="imagem-item">
                                    <img src="/api/get/imagens/avas_simulados_imagem_exibicao/249/138/<?php echo $simulado["imagem_exibicao_servidor"]; ?>" alt="Simulado" />
                                </div>
                            </div>
                            <div class="span9">
                                <div class="row-fluid show-grid">
                                    <div class="span12 description-item">
                                        <div class="span8">
                                            <p><strong><?php echo $simulado['nome']; ?></strong></p>
                                            <p><i id="horario"><?php echo date('d/m/Y H:i:s'); ?></i></p>
                                        </div>
                                        <div class="span4">
                                            <?php if($simulado['tempo_em_segundos'] > 0) { ?>
                                                <i id="timer" class="icon-time"> </i> 
                                            <?php } ?>
                                        </div>                              
                                    </div>
                                </div>
                            </div> 
                        </div>
                        <form action="/<?php echo $url[0] ?>/<?php echo $url[1] ?>/<?php echo $url[2] ?>/<?php echo $matricula['idmatricula'] ?>/<?php echo $url[4] ?>/<?php echo $url[5] ?>/<?php echo $url[6] ?>/resultado" class="ac-custom ac-radio ac-checkbox ac-fill ac-cross" id="formProva" name="formProva" autocomplete="off" method="post" enctype="multipart/form-data" >
                            <input name="acao" type="hidden" value="salvar_respostas_prova"/>
                            <input name="idsimulado" id="idsimulado" type="hidden" value="<?php echo $prova['idsimulado']; ?>"/> 
                            <input name="idmatricula_simulado" id="idmatricula_simulado" type="hidden" value="<?php echo $prova['idmatricula_simulado']; ?>"/>
                            <?php 
							$ordem = 0;
                            foreach ($prova['perguntas'] as $pergunta) { 
								$ordem++;
								?>
                                <div class="row-fluid">
                                    <div class="span12 extra-align border-box">
                                        <div><h6 class="label-perguntas"><?php echo $ordem; ?>) <?php echo $pergunta['nome']; ?></h6></div> 
                                        <?php if ($pergunta['imagem_servidor']) { ?>
                                            <div>
                                                <img src="/api/get/imagens/disciplinas_perguntas_imagens/x/300/<?php echo $pergunta["imagem_servidor"]; ?>">
                                            </div>
										<?php 
                                        }
										
                                        $type = 'radio';
										if($pergunta['multipla_escolha'] == 'S') 
											$type = 'checkbox';
											?>
                                        <ul style="margin: 0;">
                                            <?php 
											foreach ($pergunta['opcoes'] as $opcao) { 
												$id = 'pergunta['.$pergunta['idmatricula_simulado_pergunta'].']['.$opcao['idopcao'].']';
												$name = 'opcoes_unica['.$pergunta['idmatricula_simulado_pergunta'].']';
												if($pergunta['tipo'] == 'O' && $pergunta['multipla_escolha'] == 'S') 
													$name = 'opcoes_multipla['.$pergunta['idmatricula_simulado_pergunta'].']['.$opcao['idopcao'].']';
													?>
                                                <li><input id="<?php echo $id; ?>" name="<?php echo $name; ?>" type="<?php echo $type; ?>" value="<?php echo $opcao['idopcao']; ?>"><label for="<?php echo $id; ?>"><?php echo $opcao['ordem']; ?>) <?php echo $opcao['nome']; ?></label></li>
                                            <?php } ?>
                                        </ul>
                                        <br />
                                    </div>
                                </div>
                                <div class="clear"></div>
                                <br />
                            <?php } ?>
                            <div class="row-fluid">
                                <div class="span12">
                                    <input type="submit" class="btn btn-azul btn-mob" value="<?php echo $idioma['finalizar']; ?>">
                                </div>
                            </div>
                        </form> 
                    </div>
                </div>
            </div>
        </div>
        <!-- /Box -->
    </div>
</div>
<!-- /Conteudo -->
<?php incluirLib("rodape", $config, $usuario); ?>
<script type="text/javascript">
	setInterval(function() {
		$.getJSON('/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $matricula['idmatricula']; ?>/<?php echo $url[4]; ?>/<?php echo $url[5]; ?>/<?php echo $url[6]; ?>/<?php echo $url[7]; ?>/atualizar', function(json){
			$('#horario').html(json.data);						
		});
	}, 60000);				
	<?php if($simulado['tempo_em_segundos'] > 0) { ?>
		var secs = <?php echo $simulado['tempo_em_segundos']; ?>;
		var currentSeconds = 0;
		var currentMinutes = 0;
		setTimeout('Decrement()',1000);
	
		function Decrement() {
			currentMinutes = Math.floor(secs / 60);
			currentSeconds = secs % 60;
			if(currentSeconds <= 9) 
				currentSeconds = "0" + currentSeconds;
	
			secs--;
			document.getElementById("timer").innerHTML = currentMinutes + ":" + currentSeconds; 
	
			if(secs == 600) 
				alert('<?php echo $idioma['tempo_falta_dez_min']; ?>');
			
			if(secs !== -1) 
				setTimeout('Decrement()',1000);
			else {
				alert('<?php echo $idioma['tempo_esgotado']; ?>');
				document.getElementById("formProva").submit();
			}
		}
	<?php } ?>
</script>
</body>
</html>