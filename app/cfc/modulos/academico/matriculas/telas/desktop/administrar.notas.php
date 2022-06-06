<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php incluirLib("head",$config,$usuario); ?>
    <link rel="stylesheet" href="/assets/plugins/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
    <!--<link rel="stylesheet" href="/assets/plugins/fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css" media="screen" />
    <link rel="stylesheet" href="/assets/plugins/fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen" />-->
    <style type="text/css">

        .tituloEdicao {
            font-size:45px;
        }
        legend {
            line-height:25px;
            margin-bottom: 5px;
            margin-top: 20px;
        }
        .botao {
            height:100px;
            margin-top: 15px;
            padding-bottom:0px;
            float:left;
            padding-top:40px;
            height:58px;
            text-transform:uppercase;
        }
        legend {
            background-color: #F4F4f4;
            -webkit-border-radius: 4px;
            -moz-border-radius: 4px;
            border-radius: 4px;
            padding: 5px 5px 5px 15px;
            width: 98%;
        }


        legend span {
            font-size: 9px;
            float: right;
            margin-right: 15px;
            color: #999;
        }

        .labelTipo {
            background-color:#E4E4E4;
            color:#999;
            -webkit-border-radius: 2px;
            -moz-border-radius: 2px;
            border-radius: 2px;
            font-size:9px;
        }

    </style>
</head>
<body>
<?php incluirLib("topo",$config,$usuario); ?>
<div class="container-fluid">
    <section id="global">
        <div class="page-header">
            <h1><?php echo $idioma["pagina_titulo"]; ?>&nbsp;<small><?php echo $idioma["pagina_subtitulo"]; ?></small></h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="/<?php echo $url[0]; ?>"><?php echo $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
            <li><a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>"><?php echo $idioma["nav_modulo"]; ?></a> <span class="divider">/</span></li>
            <li><a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/matriculas"><?php echo $idioma["pagina_titulo"]; ?></a> <span class="divider">/</span></li>
            <li><?php echo $idioma["nav_matricula"]; ?> #<?php echo $matricula["idmatricula"]; ?> <span class="divider">/</span></li>
            <li class="active"><?php echo $idioma["nav_administrar"]; ?></a></li>
            <span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
        </ul>
    </section>
    <div class="row-fluid">
        <div class="span12">
            <div class="box-conteudo" style="padding:20px">

                <div class=" pull-right">
                    <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"> <i class="icon-share-alt"></i> <?= $idioma["btn_sair"]; ?></a>
                </div>
                <table border="0" cellspacing="0" cellpadding="15">
                    <tr>
                        <td style="padding:0px;" valign="top"><img src="/api/get/imagens/pessoas_avatar/60/60/<?php echo $matricula["pessoa"]["avatar_servidor"]; ?>" class="img-circle"></td>
                        <td style="padding: 0px 0px 0px 8px;" valign="top">
                            <h2 class="tituloEdicao"><?= $idioma["matricula"]; ?> #<?= $matricula["idmatricula"]; ?>
                                <br />
                                <small style="text-transform:uppercase;">Aluno: <?= $matricula["pessoa"]["nome"]; ?></small>
                            </h2>
                        </td>
                    </tr>
                </table>
                <? incluirTela("administrar.menu",$config,$matricula); ?>
                <div class="row-fluid">
                    <div class="span12">
                        <?php if($mensagem["erro"]) { ?>
                            <div class="alert alert-error">
                                <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                                <?= $idioma[$mensagem["erro"]]; ?>
                            </div>
                            <script>alert('<?= str_ireplace(array("<br />", "<br/>", "<br>"), "\\n", $idioma[$mensagem["erro"]]); ?>');</script>
                        <? } ?>
                        <? if($_POST["msg"]) { ?>
                            <div class="alert alert-success fade in">
                                <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                                <strong><?= $idioma[$_POST["msg"]]; ?></strong>
                            </div>
                            <script>alert('<?= $idioma[$_POST["msg"]]; ?>');</script>
                        <? } ?>
                        <section id="notasmatricula">
                            <?
                            // Verificamos quantas colunas temos
                            $colunas = 0;
                            foreach($matricula["disciplinas"] as $ind => $disciplina) {
                                $notas = count($disciplina["notas"]);
                                if($notas > $colunas) $colunas = $notas;
                            }


                            if($matricula["situacao"]["visualizacoes"][62] ||
                                $matricula["situacao"]["visualizacoes"][63] ||
                                $matricula["situacao"]["visualizacoes"][64] ||
                                $matricula["situacao"]["visualizacoes"][71] ||
                                $matricula["situacao"]["visualizacoes"][72]) {
                                ?>
                                <div id="modificarNotas" style="display:none;">
                                    <form class="form-inline" method="post" onsubmit="return confirmaModificacaoNotas();">
                                        <input name="acao" type="hidden" value="modificar_notas" />
                                        <table cellpadding="5" cellspacing="0" class="table table-bordered table-condensed tabelaSemTamanho" <?php if($margin) { ?>style="margin-top:<?php echo $margin; ?>px;<?php } ?>">
                                            <tr>
                                                <td bgcolor="#F4F4F4" style="height:30px"><strong><?= $idioma["notas_disciplina"]; ?></strong></td>
                                                <? for($i=1;$i<=$colunas;$i++){ ?>
                                                    <td bgcolor="#F4F4F4">
                                                        <strong><?= $idioma["notas_nota"]; ?><?= $i; ?></strong>
                                                    </td>
                                                <? } ?>
                                            </tr>
                                            <? foreach($matricula["disciplinas"] as $ind => $disciplina) { ?>
                                                <tr>
                                                    <td align="right" style="text-align:right; height:30px"><strong><?=$disciplina["nome"];?></strong></td>
                                                    <? for($i=1;$i<=$colunas;$i++){
                                                        $nota = $disciplina["notas"][$i-1];
                                                        ?>
                                                        <td style="text-align:center;">
                                                            <?php if ($nota['aproveitamento_estudo'] == 'S') { ?>
                                                                <table width="100%" border="0" cellspacing="0" cellpadding="5" style="margin-bottom:0px">
                                                                    <tr>
                                                                        <td>AE</td>
                                                                    </tr>
                                                                </table>
                                                            <? } else if($nota &&
                                                                $matricula["situacao"]["visualizacoes"][64] || $nota &&
                                                                $matricula["situacao"]["visualizacoes"][72]){ ?>
                                                                <? if(!$nota["idprova"] && !$nota["id_solicitacao_prova"]){ ?>
                                                                    <table width="100%" border="0" cellspacing="0" cellpadding="5" style="margin-bottom:0px">
                                                                        <tr>
                                                                            <td><input type="text" class="notas_css" style="width:35px;" name="notas[<?= $nota["iddisciplina"]; ?>][<?= $nota["idmatricula_nota"]; ?>]" value="<?= number_format($nota["nota"], 2, ',', '.'); ?>" /></td>
                                                                        </tr>
                                                                    </table>
                                                                <? } else { ?>
                                                                    <span style="color:#999">
                                                                        <?= $nota["nota"]; ?>
                                                                        <? if($nota["idprova"]){ ?><sup>1</sup><? } ?>
                                                                        <? if($nota["id_solicitacao_prova"]){ ?><sup>2</sup><? } ?>
                                                                    </span>
                                                                <? } ?>
                                                            <? } ?>
                                                        </td>
                                                    <? } ?>
                                                </tr>
                                            <? } ?>
                                            <tr>
                                                <td colspan="<?= $colunas+1; ?>" style="text-align:center"><input type="submit" name="button" id="button" value="<?= $idioma["notas_btn_modificar"]; ?>" class="btn btn-mini " /></td>
                                            </tr>
                                            <tr>
                                                <td colspan="<?= $colunas+1; ?>" align="center" style="text-align:center"><?= $idioma["notas_legenda"]; ?></td>
                                            </tr>
                                        </table>
                                    </form>
                                    <script>
                                        function confirmaModificacaoNotas(){

                                            var confirma = confirm('Deseja realmente modificar as notas selecionadas?');
                                            if(confirma) {
                                                return true;
                                            } else {
                                                return false;
                                            }

                                        }
                                    </script>
                                </div>
                            <? }
                            if($matricula["situacao"]["visualizacoes"][63]) {?>
                                <div id="removerNotas" style="display:none;">
                                    <form class="form-inline" method="post" onsubmit="return confirmaRemocaoNotas();">
                                        <input name="acao" type="hidden" value="remover_notas" />
                                        <table cellpadding="5" cellspacing="0" class="table table-bordered table-condensed tabelaSemTamanho" <?php if($margin) { ?>style="margin-top:<?php echo $margin; ?>px;<?php } ?>">
                                            <tr>
                                                <td bgcolor="#F4F4F4" style="height:30px"><strong><?= $idioma["notas_disciplina"]; ?></strong></td>
                                                <? for($i=1;$i<=$colunas;$i++){ ?>
                                                    <td bgcolor="#F4F4F4"><strong><?= $idioma["notas_nota"]; ?>
                                                            <?= $i; ?>
                                                        </strong></td>
                                                <? } ?>
                                            </tr>
                                            <? foreach($matricula["disciplinas"] as $ind => $disciplina) { ?>
                                                <tr>
                                                    <td align="right" style="text-align:right; height:30px"><strong><?=$disciplina["nome"];?></strong></td>
                                                    <? for($i=1;$i<=$colunas;$i++){
                                                        $nota = $disciplina["notas"][$i-1];
                                                        ?>
                                                        <td style="text-align:center;">
                                                            <? if($nota){ ?>
                                                                <? if(!$nota["idprova"]){ ?>
                                                                    <table width="100%" border="0" cellspacing="0" cellpadding="5" style="margin-bottom:0px">
                                                                        <tr>
                                                                            <td><?= $nota["nota"]; ?></td>
                                                                            <td><input name="remover_nota[<?= $nota["iddisciplina"]; ?>][<?= $nota["idmatricula_nota"]; ?>]" class="check_remover_nota" type="checkbox" value="<?= $nota["idmatricula_nota"]; ?>" /></td>
                                                                        </tr>
                                                                    </table>
                                                                <? } else { ?>
                                                                    <span style="color:#999">
                                                                        <?= $nota["nota"]; ?>
                                                                        <? if($nota["idprova"]){ ?><sup>1</sup><? } ?>
                                                                        <? if($nota["id_solicitacao_prova"]){ ?><sup>2</sup><? } ?>
                                                                    </span>
                                                                <? } ?>
                                                            <? } ?>
                                                        </td>
                                                    <? } ?>
                                                </tr>
                                            <? } ?>
                                            <tr>
                                                <td colspan="<?= $colunas+1; ?>" style="text-align:center"><input type="submit" name="button" id="button" value="<?= $idioma["notas_btn_remover"]; ?>" class="btn btn-mini " /></td>
                                            </tr>
                                            <tr>
                                                <td colspan="<?= $colunas+1; ?>" align="center" style="text-align:center"><?= $idioma["notas_legenda"]; ?></td>
                                            </tr>
                                        </table>
                                    </form>

                                    <script>

                                        function confirmaRemocaoNotas(){

                                            var confirma = confirm('Deseja realmente remover as notas selecionadas?');
                                            if(confirma) {
                                                return true;
                                            } else {
                                                return false;
                                            }

                                        }

                                    </script>

                                </div>
                            <? } ?>

                            <legend><?= $idioma["label_notas_matricula"]; ?></legend>
                            <?php if($matricula["situacao"]["visualizacoes"][71]) { ?>
                            <?if(count($validacoesLancarNotas) > 0) {?>
                                <div class="alert alert-warning fade in" style="margin-right: 15px;">
                                    <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                                    <?php foreach ($validacoesLancarNotas as $validacao)
                                    { ?>
                                        <strong><?= $idioma[$validacao]; ?></strong>
                                        <br>
                                    <? } ?>
                                </div>
                            <? } else { ?>
                                <form method="post" action="" onsubmit="return validaNota();">
                                    <input name="acao" type="hidden" value="lancar_nota" />
                                    <table cellpadding="5" cellspacing="0" class="table table-bordered table-condensed tabelaSemTamanho">
                                        <tr>
                                            <td bgcolor="#F4F4F4"><strong><?=$idioma["notas_disciplina"];?></strong></td>
                                            <td bgcolor="#F4F4F4"><strong><?=$idioma["notas_aproveitamento"];?></strong></td>
                                            <td bgcolor="#F4F4F4"><strong><?=$idioma["notas_nota"];?></strong></td>
                                            <td bgcolor="#F4F4F4"><strong><?=$idioma["notas_tipo"];?></strong></td>
                                            <td bgcolor="#F4F4F4"><strong><?=$idioma["notas_modelo"];?></strong></td>
                                            <td bgcolor="#F4F4F4"><strong><?=$idioma["notas_agendamento"];?></strong></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <select name="iddisciplina" id="iddisciplina" class="span3" >
                                                    <option value=""><?= $idioma["selecione_disciplina"]; ?></option>
                                                    <?php foreach($matricula["disciplinas"] as $disciplina) { ?>
                                                        <?php if($disciplina['tipo'] !== 'EAD')
                                                         { ?>
                                                        <option value="<?= $disciplina['iddisciplina']; ?>"><?= $disciplina['nome']; ?></option>
                                                         <? } ?>
                                                    <? } ?>
                                                </select>
                                            </td>
                                            <td>
                                                <select name="aproveitamento_estudo" id="aproveitamento_estudo" class="span2">
                                                    <option value="N">Não</option>
                                                    <option value="S">Sim</option>
                                                </select>
                                            </td>
                                            <td><input name="nota" type="text" id="nota" maxlength="5" class="span1" /></td>
                                            <td>
                                                <select name="idtipo" id="idtipo" class="span2">
                                                    <option value=""><?= $idioma["selecione_tipo"]; ?></option>
                                                    <?php foreach ($tiposNotas as $tipo) { ?>
                                                        <option value="<?php echo $tipo['idtipo']; ?>"><?php echo $tipo['nome']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                            <td>
                                                <select name="idmodelo" id="idmodelo" class="span2">
                                                    <option value=""><?= $idioma["selecione_modelo"]; ?></option>
                                                    <?php foreach ($modelosProvas as $modelo) {
                                                        if($matricula["situacao"]["visualizacoes"][62] ||
                                                            $matricula["situacao"]["visualizacoes"][71]){?>
                                                        <option value="<?php echo $modelo['idmodelo']; ?>"><?php echo $modelo['nome']; ?></option>
                                                    <?php }} ?>
                                                </select>
                                            </td>
                                            <td>
                                                <select name="id_solicitacao_prova" id="id_solicitacao_prova" class="span3">
                                                    <option value=""><?= $idioma["selecione_solicitacao"]; ?></option>
                                                    <?php foreach ($solicitacoes as $solicitacao) { ?>
                                                        <option value="<?php echo $solicitacao['id_solicitacao_prova']; ?>"><?php echo formataData($solicitacao['data_realizacao'],'br',0).' DAS '.substr($solicitacao['hora_realizacao_de'],0,-3).' ÀS '.substr($solicitacao['hora_realizacao_ate'],0,-3); ?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                        </tr>
                                    </table>
                                    <input class="btn" type="submit" value="<?=$idioma["btn_adicionar"];?>" />
                                </form>
                                <?php } ?>
                            <?php } ?>
                            <br />
                            <table cellpadding="5" cellspacing="0" class="table table-bordered table-condensed tabelaSemTamanho" <?php if($margin) { ?>style="margin-top:<?php echo $margin; ?>px;<?php } ?>">
                                <tr>
                                    <td colspan="<?= $colunas+2; ?>" bgcolor="#F4F4F4">Curriculo academico: <strong><?= $matricula["curriculo"]["nome"]; ?> (<?= $matricula["curriculo"]["idcurriculo"]; ?>) </strong></td>
                                </tr>
                                <tr>
                                    <td bgcolor="#F4F4F4"><strong><?= $idioma["notas_disciplina"]; ?></strong></td>
                                    <? for($i = 1; $i <= $colunas; $i++){ ?>
                                        <td bgcolor="#F4F4F4"><strong><?= $idioma["notas_nota"].' '.$i; ?></strong></td>
                                    <? } ?>
                                    <td bgcolor="#F4F4F4"><strong><?= $idioma["notas_media"]; ?></strong></td>
                                    <td bgcolor="#F4F4F4"><strong><?= $idioma["notas_situacao"]; ?></strong></td>
                                </tr>
                                <? foreach($matricula["disciplinas"] as $ind => $disciplina) { ?>
                                    <tr>
                                        <td align="right" style="text-align:right"><strong><?=$disciplina["nome"];?></strong></td>
                                        <? for($i = 1; $i <= $colunas; $i++) {
                                            $nota = $disciplina["notas"][$i-1]; ?>
                                            <td style="text-align:center;">
                                                <? if($nota){
                                                    $tooltip_nota = '';
                                                    if ($nota['tipo'])
                                                        $tooltip_nota .= 'Tipo : ' . $nota['tipo'];
                                                    if ($nota['modelo']) {
                                                        $tooltip_nota .= '<br /> Modelo: ' . $nota['modelo'];
                                                    }
                                                    if ($nota['id_solicitacao_prova']) {
                                                        $tooltip_nota .= '<br /> Data: '.formataData($nota['data_realizacao'],'br',0).' das '.substr($nota['hora_realizacao_de'],0,-3).' às '.substr($nota['hora_realizacao_ate'],0,-3);
                                                    }
                                                    ?>
                                                    <a href="javascript:void(0)" style="text-decoration:none; " rel="tooltip" data-original-title="<?php echo $tooltip_nota; ?>"  >
                                                        <?= (substr($nota["nota"], 0, 2) == 'AE') ? 'AE' : number_format($nota["nota"],2,',',''); ?>
                                                    </a>
                                                    <? if($nota["idprova"]){ ?><sup>1</sup><? } ?>
                                                    <? if($nota["id_solicitacao_prova"]){ ?><sup>2</sup><? } ?><div class="labelTipo"><?= $nota['tipo_sigla']; ?></div>
                                                <? } ?>
                                            </td>
                                        <? } ?>
                                        <td>
                                            <?php
                                            if (substr($disciplina['situacao']['situacao'] , 0, 2) == 'AE')
                                                echo 'AE';
                                            else
                                                echo number_format((float)$disciplina['situacao']['valor'], 2, ',', '');

                                            ?>
                                        </td>
                                        <td><?php echo $disciplina['situacao']['situacao']; ?></td>
                                    </tr>
                                <? } ?>
                                <tr>
                                    <td colspan="<?= $colunas+3; ?>" align="center" style="text-align:center"><?= $idioma["notas_legenda"]; ?></td>
                                </tr>
                                <? if($matricula["situacao"]["visualizacoes"][64] ||
                                    $matricula["situacao"]["visualizacoes"][72]) { ?>
                                    <tr>
                                        <td colspan="<?= $colunas+6; ?>" align="center" style="text-align:center"><a href="#modificarNotas" rel="facebox"><?= $idioma["notas_link_modificar"]; ?></a></td>
                                    </tr>
                                <? } ?>
                                <? if($matricula["situacao"]["visualizacoes"][63]) { ?>
                                    <tr>
                                        <td colspan="<?= $colunas+6; ?>" align="center" style="text-align:center"><a href="#removerNotas" rel="facebox"><?= $idioma["notas_link_remover"]; ?></a></td>
                                    </tr>
                                <? } ?>
                            </table>
                        </section>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <? incluirLib("rodape",$config,$usuario); ?>
</div>
<script>
    $("#nota").maskMoney({decimal:",",thousands:".",precision: 2,allowZero: true});
    $("#nota").keypress(isNumberFloat);
    //$(".notasMatricula").blur(isNumberCopy);

    function validaNota() {
        disciplina = document.getElementById('iddisciplina').value;
        if(disciplina == null || disciplina == '') {
            alert('Informe a disciplina.');
            document.getElementById('iddisciplina').focus();
            return false;
        }

        aproveitamento = document.getElementById('aproveitamento_estudo').value;
        if(aproveitamento == null || aproveitamento == '') {
            alert('Informe o aproveitamento.');
            document.getElementById('aproveitamento_estudo').focus();
            return false;
        }
        if (aproveitamento == 'N') {
            nota = document.getElementById('nota').value;
            if(nota == null || nota == '') {
                alert('Informe a nota.');
                document.getElementById('nota').focus();
                return false;
            } else {
                nota = nota.replace(",",".");
                nota = parseFloat(nota);
                if(nota < 0 || nota > 10) {
                    alert('A nota '+nota+' não é válida, favor corrigir e tentar novamente.');
                    document.getElementById('nota').focus();
                    return false;
                }
            }
            tipo = document.getElementById('idtipo').value;
            if(tipo == null || tipo == '') {
                alert('Informe o modelo da prova.');
                document.getElementById('idtipo').focus();
                return false;
            }

            modelo = document.getElementById('idmodelo').value;
            if(modelo == null || modelo == '') {
                alert('Informe o modelo da prova.');
                document.getElementById('idmodelo').focus();
                return false;
            }

        }

        return true;

    }
    window.onload = function(){
        $('a[rel*=facebox]').on('click', function(){
            $(".notas_css").maskMoney({decimal:",",thousands:".",precision: 2,allowZero: true});
        });

        $('#aproveitamento_estudo').on('change', function(){
            if (this.value == 'S') {
                document.getElementById('nota').disabled = true;
                document.getElementById('idtipo').disabled = true;
                document.getElementById('idmodelo').disabled = true;
                document.getElementById('id_solicitacao_prova').disabled = true;
            } else {
                document.getElementById('nota').disabled = false;
                document.getElementById('idtipo').disabled = false;
                document.getElementById('idmodelo').disabled = false;
                document.getElementById('id_solicitacao_prova').disabled = false;
            }
        });
    };
</script>

<?php incluirTela("cabecalho_info", $config, $matricula); ?>

</body>
</html>
