<div class='section section-small'>              
          <div class='section-header'><h5><?php echo $idioma["sindicatos"]; ?></h5></div>
          <? if($_SESSION["adm_gestor_sindicato"] == "S"){ ?>
	          <div style=" padding: 8px; background-color:#FFFFCC;background-image:none;"><?php echo $idioma["todas_sindicatos"]; ?></div>
          <? 			  
		  } else {
		  ?>
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



<div class='section section-small'>
					<div class='section-header'>
						<h5><?=$idioma["numeros"];?></h5>
					</div>
					<div class='section-body'>
						<div class='row-fluid'>
							<table class="" border="0" cellpadding="5" cellspacing="0">
								<tbody>
                                <? foreach($situacaoArray as $ind => $situacao) { ?>
                                    <tr>
                                      <td width="30">
                                      <span id="span_disponivel" class="btn" style="color: #<?= $situacao["cor_nome"]; ?>; border-color: #<?= $situacao["cor_bg"]; ?>; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); background: #<?= $situacao["cor_bg"]; ?>; "><?= number_format($situacao["matriculas"], 0, ',', '.'); ?></span></td>
                                      <td><?= $situacao["nome"]; ?></td>
                                    </tr>
                                <? } ?>    
								</tbody>
							</table>               


						</div>
					</div>
				</div>