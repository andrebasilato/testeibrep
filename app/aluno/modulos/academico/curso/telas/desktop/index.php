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

<?php incluirLib("topo", $config, $usuario); ?>
<!-- /Topo -->
<!-- Topo curso -->
<?php incluirLib("topo_curso", $config, $informacoesTopoCurso); ?>
<!-- /Topo curso -->
<!-- Conteudo -->
<div class="content">
    <?php ?>
    <div class="row-fluid">
        <!-- Disciplinas -->
        <div class="span8">
            <div class="row-fluid show-grid box-bg">
                <div class="top-box box-azul">
                    <h1><?php echo $idioma['disciplinas']; ?></h1>
                    <i class="icon-book"></i>
                </div>
                <h2 class="ball-icon">&bull;</h2>
                <div class="clear"></div>
                <div class="row-fluid">
                    <div class="span12 m-box-table">
                        <?php
                        $matriculaObj->set('id', $matricula['idmatricula']);
                        $visualizacoesSituacao = $matriculaObj->retornarVisualizacoesSituacao($matricula['idsituacao']);
                        $documentosObrigatorios = $matriculaObj->retornarDocumentosPendentes($matricula["idmatricula"], $matricula["idsindicato"],$matricula["idcurso"] , true);

                        $idava = 0;
                        $contAva = 0;
                        $primeiroAva = getPrimeiroAva($disciplinasAvas);
						foreach($disciplinasAvas as $ava) {
                            $contAva++;
                            $contDisc = 0;
                            $qtdDisc = count($ava['disciplinas']);
                            $data['data_fim'][] = $ava['data_fim'];
                            $qtdDF = count($data['data_fim']);
                            if( $ava['idava'] != $primeiroAva ){
                                $dataAntFim = $data['data_fim'][$qtdDF-2];
                            }
                        ?>
                            <table width="100%" border="0" cellspacing="1" cellpadding="5">
                                <tbody>
									<?php foreach($ava['disciplinas'] as $disciplina) { $contDisc++; ?>
                                        <tr>
                                        <?php
                                        $dataFim = new DateTime($ava['data_fim']);
                                        $dataFim = $dataFim->format('Y-m-d H:i:s');
                                        $dataHoj = getCurrentDate();
                                        $boolDataFim = ( $dataFim > $dataHoj )?false:true;
                                        ?><td width="70%" <?php if( $ava['data_fim'] != "" && $boolDataFim ){ ?>style="background-color:#FCF3CF;"<?php } ?>>

                                                <b><?php echo $disciplina['disciplina']; ?></b>
                                                <?php $avaliacao = getProvaFinal($avaliacoes,$ava['idava']);?>
                                                <?php if( $contDisc == $qtdDisc && $ava['contabilizar_datas'] == 'S' ){ ?>
                                                    </br></br>
                                                    <?php if( $ava['data_ini'] != "" ){ ?>
                                                    <span style="font-weight:400;" title="1"><?php echo sprintf($idioma['dataini_ava'], formataData($ava['data_ini'],'br',1)); ?></span>
                                                    <?php } ?>&nbsp;&nbsp;
                                                    <?php if( $ava['data_fim'] != "" && $boolDataFim ){ ?>
                                                    <span style="font-weight:400;" title="1"><?php echo sprintf($idioma['datafim_ava'], formataData($ava['data_fim'],'br',1)); ?></span>
                                                    <?php } ?>
                                                    <?php if( $avaliacao['prova_corrigida'] == 'S' && $avaliacao['prova_corrigida'] != "" ){ ?>
                                                        </br></br>
                                                        <span style="font-weight:400;" title="1"><?php echo sprintf($idioma['data_avaliacao'], formataData($avaliacao['data_correcao'],'br',1)); ?></span>
                                                        <?php if( floatval($avaliacao['nota']) >= floatval($ava['nota_minima']) || $ava['nota_minima'] === null || floatval($ava['nota_minima']) == 0){ ?>
                                                                - <span style="font-weight:400;" title="1">(Aprovado(a).)</span>
                                                        <?php }else{ ?>
                                                                - <span style="font-weight:400;" title="1">(Não atingiu a nota mínima.)</span>
                                                        <?php } ?>
                                                    <?php } ?>
                                                <?php } ?>
                                                <?= ($config['alerta_requisitos_alunos'] && $disciplinasAvas[$ava['idava']]["avaliacao_pendente"]) ? "</br><span style='color: #ff0000;'>".$idioma['aviso_avaliacao']."</span>" : null; ?>
                                            </td>
											<?php
                                            if ($ava['idava'] != $idava) {
                                                $idava = $ava['idava'];
                                                if (
                                                    ($ava['idava'] == $primeiroAva
                                                        || !empty($dataAntFim))
                                                    && (empty($avaAnterior)
                                                        || !$disciplinasAvas[$avaAnterior]['avaliacao_pendente'])
                                                    && (empty($avaAnterior) || $disciplinasAvas[$avaAnterior]['porcentagem'] >= $matricula['oferta_curso']['porcentagem_minima_disciplinas'])
                                                ) {
                                                    $linkAva = "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $matricula['idmatricula'] . "/" . $ava['idava'];
                                                } else {
                                                    $linkAva = "javascript:alert('" . $idioma['impossibilitado_prosseguir'] . "')";
                                                    if (
                                                        !empty($dataAntFim)
                                                        && !empty($avaAnterior)
                                                        && $disciplinasAvas[$avaAnterior]['avaliacao_pendente']
                                                    ) {
                                                        $linkAva = "javascript:alert('" . $idioma['impossibilitado_prosseguir_avaliacao'] . "')";
                                                    }
                                                }
                                                if ($matricula['liberacao_temporaria_datavalid'] == 'S' && $contAva > 2){
                                                    $linkAva = "javascript:alert('" . sprintf($idioma['alert_liberacao_temporaria_datavalid'], ucwords(strtolower(explode(' ', $usuario['nome'])[0])))  . "')";
                                                }
                                                ?>
                                                <td width="30%" rowspan="<?php echo count($ava['disciplinas']); ?>" align="center" class="table-link">
                                                    <a href="<?php echo $linkAva; ?>"><p><?php echo $idioma['acessar_sala']; ?></p></a>
                                                    <span><?php echo sprintf($idioma['andamento_aluno'], number_format($ava['porcentagem'],2,',','.')); ?></span>
                                                    <span style="color: #ff0000;"><?= ($config['alerta_requisitos_alunos'] && $ava["porcentagem"] < $matricula['oferta_curso']['porcentagem_minima_disciplinas']) ?   sprintf($idioma['aviso_porcentagem'], number_format($matricula['oferta_curso']['porcentagem_minima_disciplinas'],2,',','.')) : null ;?></span>
                                                </td>
                                            <?php } ?>
                                        </tr>
									<?php } ?>
                                </tbody>
                            </table>
                        <?php
                            $avaAnterior = $ava['idava'];
						} ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Disciplinas -->
        <!-- Avisos -->
        <div class="span4 t-margin">
            <?php
            $totalQuadroDeAvisos = count($quadroDeAvisos);
			if($totalQuadroDeAvisos > 0) { ?>
                <div class="row-fluid show-grid box-bg m-box">
                    <span class="top-box box-amarelo">
                        <h1><?php echo $idioma['quadro_avisos']; ?></h1>
                        <i class="icon-quote-left"></i>
                    </span>
                    <h2 class="ball-icon b-box">&bull;</h2>
                    <div class="clear"></div>
                    <div>
                        <div class="mbox-side-one" style="margin-top:20px;color:#40547e;font-weight:bold;background-color:#fcf8e3;border:1px solid #c09853;padding:6px;">
                        <p><?php echo $idioma['aviso_text:carga_horaria_maxima']; ?></p>
                        </div>
                    </div>
                    <div>
                        <?php
                        if($config['alerta_requisitos_alunos'] && $documentosObrigatorios) {
                            echo '<div class="mbox-side-one alert" style="margin-top:20px;color:#b94a48;font-weight:bold;background-color:#fac0c0;border:1px solid #fac0c0;padding:6px;" >';
                             if ($documentosObrigatorios) {
                                 echo "Documento(s) pendente(s):";
                                 foreach ($documentosObrigatorios as $documentosObrigatorio) {
                                     echo "<li>" . $documentosObrigatorio["nome"];
                                 }
                             }
                             echo '</div>' ;
                        }
                        ?>
                    </div>
                    <div class="clear"></div>
                    <div class="limit-box">
                        <?php
						$contadorQuadroDeAvisos = 0;
						foreach($quadroDeAvisos as $quadroDeAviso) {
							$contadorQuadroDeAvisos++;
							?>
                            <div class="mbox-side-one <?php if($contadorQuadroDeAvisos < $totalQuadroDeAvisos) echo 'border-bottom'; ?>">
                                <h1><?php echo $quadroDeAviso['titulo']; ?></h1>
                                <p><strong><?php echo $quadroDeAviso['resumo']; ?></strong></p>
                                <a href="#myModal<?php echo $quadroDeAviso['idquadro']; ?>" data-toggle="modal"><?php echo $idioma['leia_mais']; ?></a>
                                <!-- Modal -->
                                <div id="myModal<?php echo $quadroDeAviso['idquadro']; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-side-one">
                                   		    <h1><?php echo $quadroDeAviso['titulo']; ?></h1>
                                            <i class="icon-quote-right" data-dismiss="modal"></i>
                                            <p><?php echo sprintf($idioma['data'], formataData($quadroDeAviso['data_cad'],'br',1)); ?></p>
                                            <div style="max-height:400px; overflow:auto; width:100%">
                                                 <p><?php echo $quadroDeAviso['descricao']; ?></p>
                                            </div>
                                        <i class="icon-remove m-box" data-dismiss="modal"> <strong><?php echo $idioma['fechar']; ?></strong></i>
                                    </div>
                                </div>
                                <!-- /Modal -->
                                <p><i><?php echo formataData($quadroDeAviso['data_cad'],'br',1); ?></i></p>
                            </div>
						<?php } ?>
                    </div>
                </div>
			<?php } ?>
            <?php
            $totalChats = count($chats);
			if($totalChats > 0) { ?>
                <div class="row-fluid">
                    <div class="span12 t-margin">
                        <div class="row-fluid show-grid box-bg">
                            <span class="top-box box-verde">
                                <h1><?php echo $idioma['proximos_chats']; ?></h1>
                                <i class="icon-comments"></i>
                            </span>
                            <h2 class="ball-icon b-box">&bull;</h2>
                            <div class="clear"></div>
                            <div class="limit-box">
								<?php
                                $contadorChats = 0;
								$hoje = strtotime(date('Y-m-d H:i'));
								foreach($chats as $chat) {
                                    $contadorChats++;

									$inicioEntradaAluno = strtotime($chat['inicio_entrada_aluno']);
									$fimEntradaAluno = strtotime($chat['fim_entrada_aluno']);
									$link = 'href="#myModalChats" data-toggle="modal"';
									$liberado = 'off';
									if($hoje >= $inicioEntradaAluno || !$inicioEntradaAluno) {
										$link = 'href="/'.$url[0].'/'.$url[1].'/'.$url[2].'/'.$url[3].'/'.$chat['idava'].'/chats/'.$chat['idchat'].'" target="_blank"';
										if($hoje <= $fimEntradaAluno || !$fimEntradaAluno)
											$liberado = 'on';
									}
									?>
                                    <div class="abox-side-two <?php if($contadorChats < $totalChats) echo 'border-bottom'; ?>">
                                        <div class="row-fluid">
                                            <div class="span12 extra-align">
                                                <div class="contact-avatar l-align">
                                                    <img src="/api/get/imagens/avas_chats_imagem/56/56/<?php echo $chat["imagem_servidor"]; ?>" alt="Avatar">
                                                    <div class="status <?php echo $liberado; ?>"></div>
                                                </div>

                                                <div class="text-side-one details-resume">
                                                    <a <?php echo $link; ?>><?php /*?><a href="conversa.php"><?php */?><h1><?php echo $chat['nome']; ?></h1></a>
                                                    <p><?php echo $chat['descricao']; ?></p>
                                                    <p><i><?php echo $chat['inicio_entrada_aluno'] ? formataData($chat['inicio_entrada_aluno'], 'br', 1) : '--'; ?> | <?php echo $chat['fim_entrada_aluno'] ? formataData($chat['fim_entrada_aluno'], 'br', 1) : '--'; ?></i></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <!-- Modal chats -->
                            <div class="mbox-side-one">
                                <div id="myModalChats" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-side-one">
                                        <i class="icon-quote-right" data-dismiss="modal"></i>
                                        <p><div class="alert alert-danger text-center box-size-90"><strong><?php echo $idioma['chat_fechado']; ?></strong></div></p>
                                        <i class="icon-remove m-box" data-dismiss="modal"> <strong><?php echo $idioma['fechar']; ?></strong></i>
                                    </div>
                                </div>
                            </div>
                            <!-- /Modal chats -->
                        </div>
                    </div>
                </div>
			<?php } ?>
        </div>
        <!-- /Avisos -->
    </div>
</div>

<!-- /Conteudo -->
<?php incluirLib("rodape", $config, $usuario); ?>
<script src="/assets/js/jquery.1.7.1.min.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-alert.js"></script>
<script src="/assets/js/validation.js"></script>
<link rel="stylesheet" href="/assets/plugins/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
<script type="text/javascript" src="/assets/plugins/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>

<link rel="stylesheet" href="/assets/plugins/facebox/src/facebox.css" type="text/css" media="screen" />
<script src="/assets/plugins/facebox/src/facebox.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-tooltip.js"></script>

</body>
</html>
