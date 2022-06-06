<div class='section section-small'>
    <div class='section-header'>
        <h5><?=$idioma["numeros"];?></h5>
    </div>
    <div class='section-body'>
        <div class='row-fluid'>
            <table class="" border="0" cellpadding="5" cellspacing="0" style="width:100%">
                <tbody>
					<?php 
                    foreach ($situacaoArray as $ind => $situacao) { 
                        
                        if ($situacao['fim'] <> 'S' && $situacao['cancelada'] <> 'S') {
                            $alunos_ativos += $situacao["matriculas"];
                        } ?>
                        <tr>
                            <td width="30">
								<a target="_blank" href="/<?= $url[0]; ?>/academico/matriculas?q[1|m.idsituacao]=<?php echo $situacao['idsituacao']; ?>">
									<span id="span_disponivel" class="btn" style="color: #<?= $situacao["cor_nome"]; ?>; border-color: #<?= $situacao["cor_bg"]; ?>; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); background: #<?= $situacao["cor_bg"]; ?>; ">
										<?= number_format($situacao["matriculas"], 0, ',', '.'); ?>
									</span>
								</a>
							</td>
                            <td><?= $situacao["nome"]; ?></td>
                        </tr>
					<?php } ?> 
                        <tr>
                            <td width="30" bgcolor="#F4F4F4" style="border-top:2px #999 solid"><span id="span_disponivel" class="btn" style="color: #000; border-color: #CCC; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); background: #CCC; "><?= number_format($alunos_ativos, 0, ',', '.'); ?></span></td>
                            <td bgcolor="#F4F4F4" style="border-top:2px #999 solid">Alunos ativos</td>
                        </tr>                       
                </tbody>
            </table>               
        </div>
    </div>
</div>