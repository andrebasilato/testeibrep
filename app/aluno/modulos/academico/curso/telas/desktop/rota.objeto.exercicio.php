<div class="row-fluid box-item">
    <div class="span12">                
        <div class="abox extra-align">
            <?php if($exercicio['acao'] == 'retornar') { ?>
                <div class="row-fluid b-margin">
                    <div class="span12 center-align">
                        <div class="alert alert-success text-center"><i><?php echo $idioma['exercicio']; ?></i> <strong><?php echo $idioma['respondido']; ?></strong></div> 
                        <a href="#myModal2" data-toggle="modal"><div class="btn btn-verde btn-mob"><?php echo $idioma['resultado']; ?></div></a>
                        <a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $matricula['idmatricula']; ?>/<?php echo $ava['idava']; ?>/rota/<?php echo $url[6]; ?>/refazer"><div class="btn btn-azul btn-mob"><?php echo $idioma['refazer']; ?></div></a>
                        <div id="myModal2" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="extra-align">
                                <p><strong><?php echo $idioma['confira_resultado']; ?></strong></p>
                                <div class="alert alert-info text-center"><?php echo $idioma['nota']; ?> <?php echo number_format($exercicio['nota'],1,',',''); ?></div>
                                <div class="alert alert-success text-center"><?php echo $idioma['acertos']; ?> <?php echo $exercicio['corretas']; ?></div>
                                <div class="alert alert-danger text-center"><?php echo $idioma['erros']; ?> <?php echo $exercicio['erradas']; ?></div>
                                <i class="icon-remove m-box r-align" data-dismiss="modal"> <strong><?php echo $idioma['fechar']; ?></strong></i>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <?php if($exercicio['acao'] != 'retornar') { ?>
                <div class="row-fluid b-margin">
                    <div class="span12 center-align">
                        <div class="alert alert-danger text-center"><i>Para avançar os estudos, você deverá realizar agora</i> <strong><?php echo count($exercicio['perguntas']); ?></strong><i> questões</i></div> 
                    </div>
                </div>
            <form name="form_exercicio" id="form_exercicio" method="post">
                <input type="hidden" name="acao" id="acao" value="salvar_exercicio" />
                <input type="hidden" name="idmatricula_exercicio" id="idmatricula_exercicio" value="<?php echo $exercicio["idmatricula_exercicio"];?>" />
			<?php } ?>
            	<?php 
                foreach($exercicio['perguntas'] as $pergunta) { 
                    $type = 'radio';
                    
                    if ($pergunta['tipo'] == 'O' && $pergunta['multipla_escolha'] == 'S') 
                    $type = 'checkbox';
                    ?>
                    <div class="row-fluid b-margin">
                        <div class="span12 extra-align border-box">
                            <div>        
                                <p><strong><?php echo $pergunta['nome']; ?></strong></p>
                                <?php if($pergunta['imagem_servidor']) { ?>
                                    <div class="imagem-item">
                                        <img src="/api/get/imagens/disciplinas_perguntas_imagens/x/x/<?php echo $pergunta["imagem_servidor"]; ?>" alt="Imagem">
                                    </div>
                                <?php }
                                
                                if ($exercicio['acao'] == 'retornar' && $pergunta['critica']) {
                                    $criticaStyle = 'font-size: 13px; font-style: italic; text-decoration: none; border: solid 1px #eee; padding: 5px 5px; background-color: #f6f6f6;';
                                    echo '<div><p style="' . $criticaStyle . '"><strong>'. $idioma['critica'] . ': </strong>' . $pergunta['critica'] . '</p></div>';
                                }
                                
                                // var_dump($pergunta['opcoes']);
                                foreach($pergunta['opcoes'] as $opcao) { 
                                    $name = 'pergunta['.$pergunta['idmatricula_exercicio_pergunta'].'][opcao]';
                                    $correta = false;

                                    if($pergunta['tipo'] == 'O' && $pergunta['multipla_escolha'] == 'S') {
                                        if((($opcao['marcada'] == 'S' && $opcao['correta'] == 'S')) && $exercicio['acao'] == 'retornar') {
                                            $correta = true;
                                        }
                                        $name = 'pergunta['.$pergunta['idmatricula_exercicio_pergunta'].'][opcao]['.$opcao['idopcao'].']';
                                    } else {
                                        if($opcao['marcada'] == 'S' && $opcao['correta'] == 'S' && $exercicio['acao'] == 'retornar')
                                            $correta = true;
                                    }
                                    // var_dump($exercicio['acao']);
                                    ?>  
                                    <p>
                                        <label class="p-cursor">
                                            <input type="<?php echo $type; ?>"
                                                name="<?php echo $name; ?>"
                                                id="<?php echo $name; ?>"
                                                value="<?php echo $opcao['idopcao']; ?>"
                                                <?php if($opcao['marcada'] == 'S') { ?>checked="checked"<?php } ?>
                                                <?php if($exercicio['acao'] == 'retornar') { ?>disabled="disabled"<?php } ?>>
                                                <?php if($correta && $exercicio['acao'] == 'retornar') { ?>
                                                <i class="icon-ok"></i>
                                                <?php } ?><?php echo $opcao['ordem']; ?>) <?php echo $opcao['nome']; ?>
                                        </label>
                                    </p>
                                <?php } ?>
                            </div>                      
                        </div>
                    </div>
                <?php } ?>
             <?php if($exercicio['acao'] != 'retornar') { ?>
                <div class="row-fluid">
                    <div class="span12">
                        <div id="btn-stop" class="btn btn-azul btn-mob" onClick="finalizar();">FINALIZAR</div>
                    </div>
                </div>
            </form>
            <?php } ?>
        </div>
    </div>
</div>
<script type="text/javascript">
	function finalizar() {
		var confirma = confirm('Deseja realmente finalizar o exercício?');
		if(confirma) {
			document.getElementById('form_exercicio').submit();
		} else {
			return false;
		}
	}
</script>