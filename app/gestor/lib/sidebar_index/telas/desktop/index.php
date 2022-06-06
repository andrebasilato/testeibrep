<div class='section section-small'>              
    <div class='section-header'><h5><?php echo $idioma["sindicatos"]; ?></h5></div>
	<? if($_SESSION["adm_gestor_sindicato"] == "S"){ ?>
        <div style=" padding: 8px; background-color:#FFFFCC;background-image:none;"><?php echo $idioma["todas_sindicatos"]; ?></div>
	<? } else { ?>
        <div style="overflow:auto;max-height:200px;">
			<?php 
            if(count($sindicatosArray) > 0) {
                foreach($sindicatosArray as $inst) { ?>
                    <div style="padding: 8px; background-color:#FFFFFF;background-image:none; border-bottom:1px #E4E4E4 solid; height:30px">
                        <?php echo $inst["idmantenedora"]; ?>.<?php echo $inst["idsindicato"]; ?> - <?php echo $inst["sindicato"]; ?> 
                    </div>
                <?php 
                }
            } else { ?>
                <div style="padding: 8px; background-color:#FFFFFF;background-image:none;"><?php echo $idioma["nenhuma_sindicato"]; ?></div>
            <?php } ?>
        </div>
	<? } ?>
</div>

<? if($documentosPendentes) { ?>
	
		<div class='section section-small'>              
			<div class='section-header'><h5><?php echo 'Alertas'; ?></h5></div>
			
			<?php if ($atendimentos) { ?>
				<table class="" border="0" cellpadding="5" cellspacing="0" style="width:100%">
					<tr>
						<td width="30">
							<a target="_blank" href="/gestor/relacionamento/atendimentos?q[1|ate.idsituacao]=1">
								<span id="span_disponivel" class="btn" style="color: #<?php echo $atend_novo["cor_nome"]; ?>; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); background: #<?php echo $atend_novo["cor_bg"]; ?>; ">
									<?= $atendimentos; ?>
								</span>
							</a>
						</td>
						<td>
							Atendimentos - <?php echo $atend_novo["nome"]; ?>
						</td>
					</tr> 
				</table>
			<?php } ?>
			
			<? if($declaracoes[0]['total']) { ?>
				<table class="" border="0" cellpadding="5" cellspacing="0" style="width:100%">
					<tr>
						<td width="30">
							<a target="_blank" href="/gestor/academico/solicitacoesdeclaracoes?q[2|sd.situacao]=E">
								<span id="span_disponivel" class="btn" style="color: #FFF; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); background: #FF6600; ">
									<?= $declaracoes[0]['total']; ?>
								</span>
							</a>
						</td>
						<td>
							<?php echo $idioma["declaracoes_pendentes"]; ?>
						</td>
					</tr> 
				</table>
			<? } ?>

			<?php
			if($documentosPendentes[0]['total']) {
				?>
				<table class="" border="0" cellpadding="5" cellspacing="0" style="width:100%">
					<tr>
						<td width="30">
							<a target="_blank" href="/gestor/academico/documentosmatriculas">
								<span id="span_disponivel" class="btn" style="color: #FFF; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); background: #FF0000; ">
									<?= $documentosPendentes[0]['total']; ?>
								</span>
							</a>
						</td>
						<td>
							<?= $idioma["documentos_pendentes"]; ?>
						</td>
					</tr> 
				</table>
				<?php
			}
			?>
			
		</div>
	
<? } ?>

<div class='section section-small'>
    <div class='section-header'>
        <h5><?=$idioma["numeros"];?></h5>
    </div>
    <div class='section-body'>
        <div class='row-fluid'>
            <table class="" border="0" cellpadding="5" cellspacing="0" style="width:100%">
                <tbody>
					<? 
                    foreach($situacaoArray as $ind => $situacao) { 
                        
                        if($situacao['fim'] <> 'S' && $situacao['cancelada'] <> 'S'){
                            $alunos_ativos += $situacao["matriculas"];
                        }
                        
                    ?>
                        <tr>
                            <td width="30">
								<a target="_blank" href="/gestor/academico/matriculas?q[1|m.idsituacao]=<?php echo $situacao['idsituacao']; ?>">
									<span id="span_disponivel" class="btn" style="color: #<?= $situacao["cor_nome"]; ?>; border-color: #<?= $situacao["cor_bg"]; ?>; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); background: #<?= $situacao["cor_bg"]; ?>; ">
										<?= number_format($situacao["matriculas"], 0, ',', '.'); ?>
									</span>
								</a>
							</td>
                            <td><?= $situacao["nome"]; ?></td>
                        </tr>
					<? } ?> 
                        <tr>
                            <td width="30" bgcolor="#F4F4F4" style="border-top:2px #999 solid"><span id="span_disponivel" class="btn" style="color: #000; border-color: #CCC; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); background: #CCC; "><?= number_format($alunos_ativos, 0, ',', '.'); ?></span></td>
                            <td bgcolor="#F4F4F4" style="border-top:2px #999 solid">Alunos ativos</td>
                  </tr>                       
              </tbody>
            </table>               
        </div>
    </div>
</div>