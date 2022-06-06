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
<!-- Conteudo -->
<div class="content">
    <p class="texto-index"><?= $idioma['selecione_curso']; ?></p>
</div>
<div class="content">
    <div class="box-bg">
        <div class="top-box box-azul">
            <h1><?= $idioma['meus_cursos']; ?></h1>
            <i class="icon-book"></i>
        </div>
        <h2 class="ball-icon">&bull;</h2>
        <?php
        if (count($banners) > 0) {
            foreach ($banners as $banner) { ?>
                <div style="text-align:center; background-color:<?= $banner['cor_background']; ?>">
                    <?php if ($banner['link']){ ?><a href="<?= $banner['link'] ?>" target="_blank"><?php } ?>
                        <img src="/api/get/imagens/bannersavaaluno_imagem/x/x/<?= $banner["imagem_servidor"]; ?>" />
                    <?php if ($banner['link']) { ?></a><?php } ?>
                </div>
            <?php } ?>
        <?php } ?>
        <div class="clear"></div>
        <!-- Curso -->
        <?php

        foreach($matriculas as $matricula) {
            $matricula['escola'] = $matriculaObj->set('matricula', ['idescola' => $matricula['idescola']])
                ->retornarEscola();
            $forum = $matriculaObj->RetornarAlerta('forum', $matricula["idmatricula"]);
            $agendamento = $matriculaObj->RetornarAlerta('agendamento', $matricula["idmatricula"]);
            $documentos = $matriculaObj->RetornarAlerta('documentospedagogicos', $matricula["idmatricula"]);
            $tiraduvidas = $matriculaObj->RetornarAlerta('tiraduvidas', $matricula["idmatricula"]);
            $documentosObrigatorios = $matriculaObj->retornarDocumentosPendentes($matricula["idmatricula"], $matricula["idsindicato"],$matricula["idcurso"] , true);

            $matriculaObj->set('id', $matricula['idmatricula']);
            $matriculaObj->set('matricula', $matricula);
            $visualizacoesSituacao = $matriculaObj->retornarVisualizacoesSituacao($matricula['idsituacao']);

            $curriculo = $matriculaObj->retornaCurriculo();

            $existeContratoParaAssinar = $matriculaObj->existeContratoParaAssinar();

            $acessoAva['pode_acessar_ava'] = false;
            $avaliacoesPendentes['total'] = 0;

            $matricula['porcentagem'] = $matriculaObj->porcentagemCursoAtual((int)$matricula['idmatricula']);
            //Não poderá acessar o AVA se estiver na situação inicio(Pré-Matrícula)
            if ($visualizacoesSituacao[27] && $matricula['idsituacao'] != $situacaoInicial['idsituacao']) {
                $acessoAva = $matriculaObj->retornarAcessoAva();
                $avaliacoesPendentes = $matriculaObj->retornarAvaliacoesPendentes();
            }

            $podeSolicitar = false;
            if($visualizacoesSituacao[28]) {
                $podeSolicitar = $matriculaObj->retornarPodeSolicitarProva();
                /*if($podeSolicitar) {
                    $qtdeSolicitacoesProvas = $matriculaObj->retornarQtdeSolicitacoesProvas();
                }*/
            }

            $inicioCurso = $matriculaObj->retornaDataEmCurso($matricula['idmatricula']);

            $cursoSindicato = $matriculaObj->RetornarCursoSindicato();
            $podeGerarDiploma = false;
            $situacaoDiplomaExpedido = $matriculaObj->retornarSituacaoDiplomaExpedido();
            $situacaoConcluido = $matriculaObj->retornarSituacaoConcluido();

            $matricula['oferta_curso'] = $matriculaObj->retornaDadosOfertaCurso($matricula['idoferta'], $matricula['idcurso']);
            if ($matricula['oferta_curso']['idfolha'] &&
                $cursoSindicato['certificado_ava'] == 'S'
               )
            {
                $matricula["alunoAprovadoNotas"] = $matriculaObj->verificaMatriculaAprovadaNotas($matricula['oferta_curso']['porcentagem_minima_disciplinas']);
                $matricula["alunoAprovadoNotasDias"] = $matriculaObj->verificaMatriculaAprovadaNotasDias($matricula['idmatricula'],$matricula['idoferta'], $matricula['idcurso']);
            }

            if ((
                    $situacaoDiplomaExpedido['idsituacao'] == $matricula['idsituacao'] ||
                    $situacaoConcluido['idsituacao'] == $matricula['idsituacao']
                ) &&
                $cursoSindicato['certificado_ava'] == 'S' &&
                (
                    $cursoSindicato['renach_obrigatorio'] == 'N' ||
                    (
                        $cursoSindicato['renach_obrigatorio'] == 'S' &&
                        $matricula['renach']
                    )
                )
            ) {
                $podeGerarDiploma = $matriculaObj->temDiploma($matricula["idmatricula"]);
            }
            ?>
            <div class="row-fluid box-item">
                <div class="extra-align">
                    <div class="span3">
                        <div class="imagem-item">
                            <img src="/api/get/imagens/cursos_imagem_exibicao/268/181/<?= $matricula["imagem_exibicao_servidor"]; ?>" alt="Curso" />
                        </div>
                    </div>
                    <div class="span9">
                        <div class="row-fluid show-grid">
                            <div class="span12 description-item">
                                <div class="span8">
                                    <h1><?= $matricula['curso']; ?></h1>
                                    <p><?= sprintf($idioma['oferta'], $curriculo['oferta']); ?></p>
                                    <p><?= sprintf($idioma['carga_horaria'], $matricula['carga_horaria_total']); ?></p>
                                    <p><?= $idioma['matricula']; ?> <strong><?= $matricula['idmatricula']; ?></strong></p>
                                    <p><?php if ($inicioCurso['data_cad']) {echo sprintf($idioma['inicio_curso'], formataData($inicioCurso['data_cad'], 'br', 0));} ?></p>
                                    <p>
                                        <?php
                                        if($matricula['porcentagem'] > 0) {
                                            echo sprintf($idioma['prcentagem'], number_format($matricula['porcentagem'],2,',','.'));
                                        } else {
                                            echo $idioma['prcentagem_inicio'];
                                        }
                                        if($acessoAva['pode_acessar_ava']) {
                                            if($acessoAva['data_limite_acesso_ava']) {
                                                echo sprintf($idioma['acesso_ava_data'], formataData($acessoAva['data_limite_acesso_ava'], 'br', 0));
                                            } else {
                                                echo $idioma['acesso_ava_ilimitado'];
                                            }
                                        }
                                        ?>
                                    </p>
                                </div>
                                <div class="span4 extra-progress">
                                    <div class="progress progress-striped active">
                                        <?php
                                        $porcentagem_ava = 100;
                                        if($curriculo['porcentagem_ava'])
                                            $porcentagem_ava = $curriculo['porcentagem_ava'];
                                        ?>
                                        <div class="bar" style="width: <?= $matricula['porcentagem']; ?>%;<?php if($matricula['porcentagem'] >= $porcentagem_ava) { ?>background-color:#228B22;<?php } else { ?>background-color:#ff8f5d;<?php } ?>">
                                        </div>
                                    </div>
                                    <p><?= sprintf($idioma['andamento'], number_format($matricula['porcentagem'],2,',','.')); ?></p>

                                   <div class="span12">
                                       <?php if($tiraduvidas["total"]){  ?>
                                    <div class="row-fluid">
                                        <div class="span12">
                                            <div class="box-alerts">
                                                <!-- <a target="_blank" href="/aluno/academico/curso/<?=$matricula['idmatricula'];?>/<?=$tiraduvidas['idava'];?>/mensagens">
                                                    <span id="span_disponivel" class="btn" style="color: white; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); background: #ffcc00;padding: 0;padding-left: 6px;padding-right: 9px;">
                                                        <?= $tiraduvidas["total"] ?>
                                                    </span>
                                                </a>
                                                <span><?= " - Tira-Dúvidas" ?></span> -->
                                            </div>
                                        </div>
                                    </div>
                                    <?php }
                                       if($forum["total"]){ ?>
                                     <div class="row-fluid">
                                        <div class="span12">
                                            <div class="box-alerts">
                                                <!-- <a target="_blank" href="/aluno/academico/curso/<?=$matricula['idmatricula'];?>/<?=$forum['idava'];?>/foruns">
                                                    <span id="span_disponivel" class="btn" style="color: white; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); background: #ff0000; padding: 0;padding-left: 6px;padding-right: 9px;">
                                                        <?= $forum["total"] ?>
                                                    </span>
                                                </a>
                                                <span><?= " - Fórum" ?></span> -->
                                            </div>
                                        </div>
                                    </div>
                                    <?php }
                                     if($agendamento["total"]){ ?>
                                     <div class="row-fluid">
                                        <div class="span12">
                                            <div class="box-alerts">
                                                <!-- <a target="_blank" href="/aluno/secretaria/meuscursos/<?=$matricula['idmatricula'];?>/provaspresenciais">
                                                    <span id="span_disponivel" class="btn" style="color: white; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); background: #33cc33; padding: 0;padding-left: 6px;padding-right: 9px;">
                                                        <?= $agendamento["total"] ?>
                                                    </span>
                                                </a>
                                                <span><?= " - Agendamento" ?></span> -->
                                            </div>
                                        </div>
                                    </div>
                                     <?php }
                                       if($documentos["total"]){ ?>
                                     <div class="row-fluid">
                                        <div class="span12">
                                            <div class="box-alerts">
                                                <!-- <a target="_blank" href="/aluno/secretaria/documentospedagogicos">
                                                    <span id="span_disponivel" class="btn" style="color: white; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); background: #FF6600; padding: 0 6px 0 9px;">
                                                        <?= $documentos["total"] ?>
                                                    </span>
                                                </a>
                                                <span><?= " - Documentos" ?></span> -->
                                            </div>
                                        </div>
                                    </div>
                                       <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="row-fluid show-grid">
                            <div class="span12">
                                </br>
                                <?php
                                if(!empty($acessoCursoNaoSimultaneo) && ($acessoCursoNaoSimultaneo['idmatricula'] != $matricula['idmatricula']) && $matricula['acesso_simultaneo'] === 'N' && $matricula['idsituacao'] == $situacaoAtiva['idsituacao'])
                                { ?>
                                   <div class="btn btn-cinza btn-mob" data-toggle="tooltip" data-placement="top" title="Este curso só pode ser acessado após concluir o curso <?= $acessoCursoNaoSimultaneo['nome'] ?>" style="cursor:default;"><?= $idioma['sem_acesso_ambiente']; ?></div>
                                <? } else {
                                if($config['alerta_requisitos_alunos']){ //Alertas de Requisitos Pendentes
                                    if (($avaliacoesPendentes['total'] > 0) || ($matricula['porcentagem'] < 100) || ($documentosObrigatorios))
                                    {  ?>
                                        <a href="/<?= $url[0] ?>/academico/curso/<?= $matricula["idmatricula"]; ?>"><div class="alert alert-danger text-center"> <?= $idioma['requisitos_pendentes']; ?></div></a>
                                    <?php
                                    }
                                }
                                if ($acessoAva['pode_acessar_ava']) {
                                    if ($matricula['acesso_ava'] == 'N') {
                                        ?>
                                        <div class="btn btn-cinza btn-mob" style="cursor:default;">
                                            <?= $idioma['sem_acesso_ava_sindicato']; ?>
                                        </div>
                                        <?php
                                    } elseif ($matricula['detran_situacao'] != 'LI' && ! empty($matricula['escola']['detran_codigo'])) {
                                        ?>
                                        <div class="btn btn-cinza btn-mob" style="cursor:default;">
                                            <?= sprintf($idioma['sem_acesso_detran'], $situacaoDetran[$config['idioma_padrao']][$matricula['detran_situacao']]) ?>
                                        </div>
                                        <?php
                                    } else {
                                        if($matricula['escola']['idestado'] == 26 && empty($matricula['renach'])){
                                            ?>
                                            <a onclick="verificaSergipe(<?= $matricula["idmatricula"]; ?>)">
                                                <div class="btn btn-verde btn-mob">
                                                    <?php
                                                    if($matricula['porcentagem'] > 0) {
                                                        echo $idioma['acessar_ambiente'];
                                                    } else {
                                                        echo $idioma['acessar_ambiente_inicio'];
                                                    }
                                                    ?>
                                                </div>
                                            </a>
                                            <?php
                                        } else {
                                            ?>
                                            <a href="/<?= $url[0] ?>/academico/curso/<?= $matricula["idmatricula"]; ?>">
                                                <div class="btn btn-verde btn-mob">
                                                    <?php
                                                    if($matricula['porcentagem'] > 0) {
                                                        echo $idioma['acessar_ambiente'];
                                                    } else {
                                                        echo $idioma['acessar_ambiente_inicio'];
                                                    }
                                                    ?>
                                                </div>
                                            </a>
                                            <?php
                                        }
                                    }
                                } else {
                                    ?>
                                    <div class="btn btn-cinza btn-mob" style="cursor:default;"><?= $idioma['sem_acesso_ambiente']; ?></div>
                                    <?php
                                }

                                // if ($acessoAva['pode_acessar_ava'] && $matricula['acesso_ava'] == 'S') {
                                //     ?>
                                    <!-- <a href="/<?= $url[0] ?>/<?= $url[1] ?>/<?= $url[2] ?>/<?= $matricula["idmatricula"]; ?>/boletim" target="_blank"><div class="btn btn-laranja btn-mob"><?= $idioma['boletim']; ?></div></a> -->
                                    <?php
                                // }

                                if ($avaliacoesPendentes['total'] > 0) {
                                    ?>
                                    <div class="alert alert-danger text-center"><?= $idioma['avaliacoes_pendentes']; ?></div>
                                    <?php
                                }

                                if ($matricula['idsituacao'] == $situacaoConcluido['idsituacao']) {?>
                                    <div class="alert alert-success text-center"><?= $idioma['curso_concluido']; ?></div>
                                    <?php
                                }
                                if (($podeGerarDiploma['total'] > 0 || $matricula['alunoAprovadoNotas']) && $matricula['alunoAprovadoNotasDias'] && $visualizacoesSituacao[74]) {
                                    if ($matricula['alunoAprovadoNotas'] && empty($podeGerarDiploma['idfolha'])) {
                                        $idFolha = $matricula['oferta_curso']['idfolha'];
                                    } else {
                                        $idFolha = $podeGerarDiploma['idfolha'];
                                    } ?>
                                    <?php if ($matricula['escola']['idestado'] == 10 && $matricula['codigo_curso'] == 'REC' && $matricula['detran_certificado'] == 'N') { ?>
                                        <a onclick="verificaMaranhao(<?= $matricula["idmatricula"]; ?>)">
                                            <div class="btn btn-amarelo btn-mob"><?= $idioma['diploma']; ?></div>
                                        </a>
                                    <?php } else { ?>
                                        <a href="/<?= $url[0] ?>/<?= $url[1] ?>/<?= $url[2] ?>/<?= $matricula['idmatricula']; ?>/diploma/<?= $idFolha; ?>" target="_blank">
                                            <div class="btn btn-amarelo btn-mob"><?= $idioma['diploma']; ?></div>
                                        </a>
                                    <?php } ?>
                                    <?php if ($matriculaObj->se_aluno_historico($matricula['idsindicato'], $matricula['idcurso'])) { ?>
                                        <a href="/<?= $url[0] ?>/<?= $url[1] ?>/<?= $url[2] ?>/<?= $matricula['idmatricula']; ?>/historico/<?= $idFolha; ?>" target="_blank"><div class="btn btn-azul btn-mob"><?= $idioma['historico']; ?></div></a>
                                        <?php
                                    } ?>
                                    <?php
                                } }?>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
            </div>
		<?php } ?>

    </div>
</div>

<div class="modal hide fade text-side-two extra-align in" id="detran_se" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="row-fluid m-box">
        <div class="span12">
            <div class="row-fluid">
                <div class="span12" style="font-size: 12px;">
                    <i class="closed-x" data-dismiss="modal"> <strong>Fechar</strong></i>
                    <h1>Aviso Detran Sergipe</h1>

                    VOCÊ JÁ ENTREGOU SUA CNH NO DETRAN E FEZ SEU CADASTRO NO SITE?<br>
                    <a href="https://seguro.detran.se.gov.br/portal/?pg=cnh_reciclagem_condutores">https://seguro.detran.se.gov.br/portal/?pg=cnh_reciclagem_condutores</a><br>
                    <br>
                    Para seu certificado de conclusão do Curso ser válido  é preciso primeiro se cadastrar no link acima<br>
                    <br>
                    Qualquer dúvida entre em contato conosco. Whatsapp (48) 98811-1125.
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal hide fade text-side-two extra-align in" id="detran_ma" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="row-fluid m-box">
        <div class="span12">
            <div class="row-fluid">
                <div class="span12" style="font-size: 12px;">
                    <i class="closed-x" data-dismiss="modal"> <strong>Fechar</strong></i>
                    <h1><?= $idioma['aviso_detran_ma']; ?></h1>
                    <?= $idioma['mensagem_detran_ma']; ?>
                    <br>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Conteudo -->
<?php incluirLib("rodape", $config, $usuario); ?>
<?php
if( $_SESSION['cliente_alerta'] == true ){
    $idModal = "modal-login";
    echo getModalOraculo($idModal,"Aviso",$idioma['aviso_text:carga_horaria_maxima']);
}
?>
<script type="text/javascript">
    cont = 0;
    function verificaSergipe(idMatricula){
        if(cont == 0){
            $('#detran_se').modal()
            .on('shown',
                function() {
                    descerScroll();
                }
            ).on("hidden",
                function() {
                    cont++;
                    $(this).remove(function (e){

                    });
                }
            );
        } else {
            window.location.href = "/<?= $url[0] ?>/academico/curso/" + idMatricula;
        }
    };

    function verificaMaranhao(idMatricula){
        $('#detran_ma').modal()
        .on('shown',
            function() {
                descerScroll();
            }
        ).on("hidden",
            function() {
                $(this).remove(function (e){
                });
            }
        );
    };
    <?php if( $_SESSION['cliente_alerta'] == true ){ ?>
    $('#<?php echo $idModal; ?>').modal('show')
    <?php } unset($_SESSION['cliente_alerta']); ?>
</script>
</body>
</html>
