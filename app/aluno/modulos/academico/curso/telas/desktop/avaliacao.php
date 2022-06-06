<? header('Content-Type: text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <?php incluirLib("head", $config, $usuario); ?>
    <script type="text/javascript">
        <?php if ($avaliacao['tempo_em_segundos']) { ?>
        alert('<?php echo sprintf($idioma['tempo_realização'], ($avaliacao['tempo_em_segundos'] / 60)) ?>');
        <?php } else { ?>
        //alert('<?= $idioma['tempo_realização_vazio'] ?>');
        <?php } ?>
    </script>
    <style>
        .contabilizador {
            width: 230px;
            min-height: 40px;
            padding: 5px;
            text-align: center;
            background-color: #f0f0f0;
            float: right;
            position: absolute;
            display: block;
            font-size: 14px;
            z-index: 2;
            box-shadow: 2px 2px 10px #aaa;
            color: #40547e;
            border-radius: 3px;
        }

        .posicionameno-fixo {
            position: fixed;
            top: 10px;
        }
    </style>

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
                <?php
                $martriculaliberada = 'false';
                if (isset($_GET['reconhecimento']) && $recon == $_GET['reconhecimento'] || $matriculaLiberada ||
                    $curso['usar_datavalid'] == 'N' || $dadosSindicato['usar_datavalid'] == 'N') { ?>
                    <div class="span12">
                        <div class="abox extra-align">
                            <div class="row-fluid m-box">
                                <div class="span3">
                                    <div class="imagem-item">
                                        <img src="/api/get/imagens/avas_avaliacoes_imagem_exibicao/249/138/<?php echo $avaliacao["imagem_exibicao_servidor"]; ?>" alt="Avaliação" />
                                    </div>
                                </div>
                                <div class="span9">
                                    <div class="row-fluid show-grid">
                                        <div class="span12 description-item">
                                            <p><strong><?php echo $avaliacao['nome']; ?></strong></p>
                                            <p><i id="horario"><?php echo date('d/m/Y H:i:s'); ?></i></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="contabilizador">
                                <?php echo $idioma['perguntas_respondidas']; ?>:
                                <strong class="perguntas-respondidas">0</strong>
                                <br>
                                <?php echo $idioma['perguntas_nao_respondidas']; ?>:
                                <strong class="perguntas-nao-respondidas">
                                    <?php echo count($prova['perguntas']); ?>
                                </strong>
                            </div>
                            <form class="ac-custom ac-radio ac-checkbox ac-fill ac-cross" id="formProva" name="formProva" autocomplete="off" method="post" enctype="multipart/form-data" >
                                <input name="acao" type="hidden" value="salvar_respostas_prova"/>
                                <input name="idprova" id="idprova" type="hidden" value="<?php echo $prova['idprova']; ?>"/>
                                <input name="tipo_correcao" type="hidden" value="<?php echo $avaliacao['avaliador']; ?>"/>
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
                                            <?php } ?>
                                            <?php
                                            if ($pergunta['tipo'] == 'O') {
                                                $type = 'radio';
                                                if($pergunta['multipla_escolha'] == 'S')
                                                    $type = 'checkbox';
                                                ?>
                                                <ul style="margin: 0;">
                                                    <?php
                                                    foreach ($pergunta['opcoes'] as $opcao) {
                                                        $id = 'pergunta['.$pergunta['id_prova_pergunta'].']['.$opcao['idopcao'].']';
                                                        $name = 'opcoes_unica['.$pergunta['id_prova_pergunta'].']';
                                                        if($pergunta['tipo'] == 'O' && $pergunta['multipla_escolha'] == 'S')
                                                            $name = 'opcoes_multipla['.$pergunta['id_prova_pergunta'].']['.$opcao['idopcao'].']';
                                                        ?>
                                                        <li><input id="<?php echo $id; ?>" name="<?php echo $name; ?>" type="<?php echo $type; ?>" value="<?php echo $opcao['idopcao']; ?>"><label for="<?php echo $id; ?>"><?php echo $opcao['ordem']; ?>) <?php echo $opcao['nome']; ?></label></li>
                                                    <?php } ?>
                                                </ul>
                                            <?php } else { ?>
                                                <textarea name="respostas_subjetivas[<?php echo $pergunta['id_prova_pergunta']; ?>]" id="respostas_subjetivas[<?php echo $pergunta['id_prova_pergunta']; ?>]" class="span12" style="height:120px;"></textarea>
                                            <?php } ?>
                                            <br />
                                            <?php if ($pergunta['permite_anexo_resposta'] == 'S') {?>
                                                <div>
                                                    <?php echo $idioma['anexar_arquivo']; ?>
                                                    <input type="file" name="arquivo[<?php echo $pergunta['id_prova_pergunta']; ?>]" id="arquivo[<?php echo $pergunta['id_prova_pergunta']; ?>]">
                                                </div>
                                            <?php }?>
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
                <?php 
                } else {
                    ?>
                    <div class="span12">
                        <div class="abox extra-align">
                            <div class="row-fluid m-box">
                                <div class="span3">
                                    <div class="imagem-item">
                                        <img src="/api/get/imagens/avas_avaliacoes_imagem_exibicao/249/138/<?php echo $avaliacao["imagem_exibicao_servidor"]; ?>" alt="Avaliação" />
                                    </div>
                                </div>
                                <div class="span9">
                                    <div class="row-fluid show-grid">
                                        <div class="span12 description-item">
                                            <div class="span8">
                                                <p><strong><?php echo $avaliacao['nome']; ?></strong></p>
                                                <p><i id="horario"><?php echo date('d/m/Y H:i:s'); ?></i></p>
                                            </div>
                                            <div class="span4">
                                                <?php if($avaliacao['tempo_em_segundos'] > 0) { ?>
                                                    <i id="timer" class="icon-time"> </i>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="contabilizador">
                                <?php echo $idioma['perguntas_respondidas']; ?>:
                                <strong class="perguntas-respondidas">0</strong>
                                <br>
                                <?php echo $idioma['perguntas_nao_respondidas']; ?>:
                                <strong class="perguntas-nao-respondidas">
                                    <?php echo count($prova['perguntas']); ?>
                                </strong>
                            </div>
                            <form class="ac-custom ac-radio ac-checkbox ac-fill ac-cross" id="formProva" name="formProva" autocomplete="off" method="post" enctype="multipart/form-data" >
                                <input name="acao" type="hidden" value="salvar_respostas_prova"/>
                                <input name="idprova" id="idprova" type="hidden" value="<?php echo $prova['idprova']; ?>"/>
                                <input name="tipo_correcao" type="hidden" value="<?php echo $avaliacao['avaliador']; ?>"/>
                                <?php
                                $ordem = 0;
                                foreach ($prova['perguntas'] as $pergunta) {
                                    $ordem++;
                                    ?>
                                    <div class="row-fluid">
                                        <div class="span12 extra-align border-box">
                                            <div><h6><?php echo $ordem; ?>) <?php echo $pergunta['nome']; ?></h6></div>
                                            <?php if ($pergunta['imagem_servidor']) { ?>
                                                <div>
                                                    <img src="/api/get/imagens/disciplinas_perguntas_imagens/x/300/<?php echo $pergunta["imagem_servidor"]; ?>">
                                                </div>
                                            <?php } ?>
                                            <?php
                                            if ($pergunta['tipo'] == 'O') {
                                                $type = 'radio';
                                                if($pergunta['multipla_escolha'] == 'S')
                                                    $type = 'checkbox';
                                                ?>
                                                <ul style="margin: 0;">
                                                    <?php
                                                    foreach ($pergunta['opcoes'] as $opcao) {
                                                        $id = 'pergunta['.$pergunta['id_prova_pergunta'].']['.$opcao['idopcao'].']';
                                                        $name = 'opcoes_unica['.$pergunta['id_prova_pergunta'].']';
                                                        if($pergunta['tipo'] == 'O' && $pergunta['multipla_escolha'] == 'S')
                                                            $name = 'opcoes_multipla['.$pergunta['id_prova_pergunta'].']['.$opcao['idopcao'].']';
                                                        ?>
                                                        <li><input id="<?php echo $id; ?>" name="<?php echo $name; ?>" type="<?php echo $type; ?>" value="<?php echo $opcao['idopcao']; ?>"><label for="<?php echo $id; ?>"><?php echo $opcao['ordem']; ?>) <?php echo $opcao['nome']; ?></label></li>
                                                    <?php } ?>
                                                </ul>
                                            <?php } else { ?>
                                                <textarea name="respostas_subjetivas[<?php echo $pergunta['id_prova_pergunta']; ?>]" id="respostas_subjetivas[<?php echo $pergunta['id_prova_pergunta']; ?>]" class="span12" style="height:120px;"></textarea>
                                            <?php } ?>
                                            <br />
                                            <?php if ($pergunta['permite_anexo_resposta'] == 'S') {?>
                                                <div>
                                                    <?php echo $idioma['anexar_arquivo']; ?>
                                                    <input type="file" name="arquivo[<?php echo $pergunta['id_prova_pergunta']; ?>]" id="arquivo[<?php echo $pergunta['id_prova_pergunta']; ?>]">
                                                </div>
                                            <?php }?>
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
                <?php  } ?>
            </div>
        </div>
        <!-- /Box -->
    </div>
</div>
<!-- /Conteudo -->
<?php incluirLib("rodape", $config, $usuario); ?>
<script type="text/javascript">

    <?php if(isset($GLOBALS['url'][4]) && $GLOBALS['url'][5] == 'avaliacoes') { ?>
        const createClock = setInterval(contabilizaAcesso, 60000 * 10); // 60 segundos
        function contabilizaAcesso() {
            // console.log('POG para evitar que a sessão expire no servidor por tempo de inatividade...');
            var xhttp = new XMLHttpRequest();
            xhttp.responseType = 'json';
            xhttp.open("GET", "/aluno/academico/curso/<?= $GLOBALS['url'][3]; ?>/<?= $GLOBALS['url'][4]; ?>/avaliacoes?&atualizaSessao=true", true);
            xhttp.send();
        }
    <?php } ?>

    var secs = <?php echo $avaliacao['tempo_em_segundos']; ?>;
    <?php if($avaliacao['tempo_em_segundos_alerta'] > 0) {
    echo "var secsAlert = {$avaliacao['tempo_em_segundos_alerta']};";
    ?>
    var currentSeconds = 0;
    var currentMinutes = 0;
    var secsPassed = 0;
    setTimeout('Decrement()',1000);

    function Decrement() {
        currentMinutes = Math.floor(secs / 60);
        currentSeconds = secs % 60;
        if(currentSeconds <= 9)
            currentSeconds = "0" + currentSeconds;
        secs--;
        secsPassed++;
        <?php if($avaliacao['tempo_em_segundos_alerta'] > 0) { ?>
        if(secsPassed === secsAlert) {
            alert('<?php echo $idioma['tempo_falta']; ?> ' + Math.floor(secs / 60) +
                ' <?php echo $idioma['tempo_falta1']; ?>');
            secsPassed = 0;
        }
        <?php } ?>
        if(secs === 600) {
            alert('<?php echo $idioma['tempo_falta_cinco_min']; ?>');
            secsPassed = 0;
        }
        if(secs !== -1)
            setTimeout('Decrement()',1000);
        else {
            alert('<?php echo $idioma['tempo_esgotado']; ?>');
            document.getElementById("formProva").submit();
        }
    }
    <?php } ?>
    $(document).ready(function(){
        $('.contabilizador').css('margin-left', ($('#formProva').width() - $('#contabilizador').width()) + 'px');
        $(window).scroll(function() {
            if($(this).scrollTop() >= 400) {
                $('.contabilizador').addClass('posicionameno-fixo');
            } else {
                $('.contabilizador').removeClass('posicionameno-fixo');
            }
        });
    });

    $('#formProva input[type="radio"]').click(function() {
        var perguntasRespondidas = 0;
        var perguntasNaoRespondidas = $('#formProva ul').length;
        $('#formProva ul').each(function() {
            $(this).children('li').children('input').each(function() {
                if ($(this).is(':checked')) {
                    perguntasRespondidas++;
                    perguntasNaoRespondidas--;
                }
            });
        });
        $('.perguntas-respondidas').html(perguntasRespondidas);
        $('.perguntas-nao-respondidas').html(perguntasNaoRespondidas);
    });

</script>
</body>
</html>