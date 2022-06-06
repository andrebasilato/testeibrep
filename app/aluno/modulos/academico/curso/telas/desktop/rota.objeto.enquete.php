<div class="row-fluid box-item">
    <div class="span12">                
        <div class="abox extra-align">
            <?php /*if($exercicio['acao'] == 'retornar') { ?>
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
            <?php }*/ ?>
            <?php if(!$enquete['votou']) { ?>
            <form name="form_enquete" id="form_enquete" method="post">
                <input type="hidden" name="acao" id="acao" value="votar_enquete" />
			<?php } ?>
            <div class="row-fluid b-margin">
                <div class="span12 extra-align border-box">
                    <div>        
                        <p><strong><?php echo $objeto['objeto']['pergunta']; ?></strong></p>
                        <?php foreach($enquete['opcoes'] as $opcao) { ?>  
                            <p>
                                <div style="float:left;width:90%;">
                                    <label class="p-cursor"><input type="radio" name="idopcao" id="idopcao" value="<?php echo $opcao['idopcao']; ?>" <?php if($opcao['idopcao'] == $enquete['votou']) { ?>checked="checked"<?php } ?><?php if($enquete['votou']) { ?>disabled="disabled"<?php } ?>><?php echo $opcao['opcao']; ?></label>
                                </div>
                                <?php if($enquete['votou']) { 
                                    $porcentagem = 0;
                                    if($enquete['total_votos'] > 0) 
                                        $porcentagem = ($opcao['votos'] * 100) / $enquete['total_votos'];
                                        ?>
                                    <div style="float:right;width:10%;"><?php echo number_format($porcentagem, 2, ',', '.'); ?>%</div>
                                <?php } ?>
                            </p>
                        <?php } ?>
                    </div>                      
                </div>
            </div>
            <?php if(!$enquete['votou']) { ?>
                <div class="row-fluid">
                    <div class="span12">
                        <div id="btn-stop" class="btn btn-azul btn-mob" onClick="votar();">VOTAR</div>
                    </div>
                </div>
            </form>
            <?php } ?>
        </div>
    </div>
</div>
<script type="text/javascript">
	function votar() {
		var confirma = confirm('Deseja realmente votar?');
		if(confirma) {
			document.getElementById('form_enquete').submit();
		} else {
			return false;
		}
	}
</script>