<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php incluirLib("head", $config, $usuario); ?>
    <link rel="stylesheet" href="/assets/css/etapas.css" media="all" type="text/css"/>
    <script src='/assets/plugins/rating_h/inc/rating.js' type="text/javascript" language="javascript"></script>
    <link href='/assets/plugins/rating_h/inc/rating.css' type="text/css" rel="stylesheet"/>
    <script language="javascript">
        function novo_arquivo() {
            var IE = document.all ? true : false
            var div_arquivos = document.getElementById("div_arquivos");
            if (!IE) {
                var length = div_arquivos.childNodes.length - 1;
            } else {
                var length = div_arquivos.childNodes.length + 1;
            }
            var input = document.createElement('INPUT');
            input.setAttribute("type", "file");
            id = "arquivos[" + length + "]";
            input.setAttribute("name", id);
            input.setAttribute("id", id);
            div_arquivos.appendChild(input);
            var br = document.createElement('br');
            div_arquivos.appendChild(br);
            regras.push("formato_arquivo," + id + ",jpg|jpeg|gif|png|bmp|zip|rar|tar|gz|doc|docx|xls|xlsx|ppt|pptx|pps|ppsx|txt|pdf,'',<?php echo $idioma['arquivo_invalido']; ?>");
        }

        function msg_ativa(id) {
            if (id == 'msg') {
                document.getElementById('msg').style.display = '';
                document.getElementById('msg_principal').style.display = 'none';
                document.getElementById('resposta_atendimento_auto').style.display = 'none';
                document.getElementById('resposta_atendimento').style.display = '';
            } else if (id == 'auto') {
                document.getElementById('msg_principal').style.display = 'none';
                document.getElementById('msg').style.display = '';
                document.getElementById('resposta_atendimento_auto').style.display = '';
                document.getElementById('resposta_atendimento').style.display = 'none';
            } else {
                document.getElementById('msg').style.display = 'none';
                document.getElementById('msg_principal').style.display = '';
            }
        }

        function validaMsg() {
            if (document.getElementById('resposta_atendimento_auto').style.display != 'none') {
                if (document.getElementById('resposta_atendimento_auto').value == "") {
                    alert('<?php echo $idioma["mensagem_vazio"]; ?>');
                    return false;
                }
            } else if (document.getElementById('resposta_atendimento').style.display != 'none') {
                if (document.getElementById('resposta_atendimento').value == "") {
                    alert('<?php echo $idioma["mensagem_vazio"]; ?>');
                    return false;
                }
            }
            return validateFields(document.getElementById('msg'), regras);
        }
    </script>
    <style>
        .tabela {
            border: #CCC solid 1px;
            width: 100%;
        }

        .linha {
            border-bottom: #CCC solid 1px;
        }

        .coluna {
            border-right: #CCC solid 1px;
        }

        .botao {
            background-color: #F9F9F9;
            color: #000;
            height: 350px;
            border: #CCC solid 1px;
            cursor: pointer;
        }

        .nav > .li {
            border: #CCC solid 1px;
            padding: 10px;
        }

        .nav > .li:hover {
            text-decoration: none;
            background-color: #eeeeee;
        }

        .botao_big {
            height: 100px;
            margin-top: 15px;
            padding-bottom: 0px;
        }

        .li {
            border: #CCC solid 1px;
            padding: 10px;
        }

        .li:hover {
            text-decoration: none;
            background-color: #eeeeee;
        }

        .status {
            cursor: pointer;
            color: #FFF;
            font-size: 9px;
            font-weight: bold;
            padding: 5px;
            text-transform: uppercase;
            white-space: nowrap;
            -webkit-border-radius: 3px;
            -moz-border-radius: 3px;
            border-radius: 3px;
            margin-right: 5px;
        }

        .ativo {
            font-size: 15px;
        }

        .inativo {
            background-color: #838383;
        }

        .divCentralizada {
            position: relative;
            width: 700px;
            height: 200px;
            left: 15%;
            top: 50%;
        }
    </style>
</head>
<body>
<? incluirLib("topo", $config, $usuario); ?>
<div class="container-fluid">
<section id="global">
    <div class="page-header">
        <h1><?= $idioma["pagina_titulo"]; ?> &nbsp;
            <small><?= $idioma["pagina_subtitulo"]; ?></small>
        </h1>
    </div>
    <ul class="breadcrumb">
        <li><a href="/<?= $url[0]; ?>"><?= $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>"><?= $idioma["nav_modulo"]; ?></a> <span class="divider">/</span>
        </li>
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"><?= $idioma["pagina_titulo"]; ?></a> <span
                class="divider">/</span></li>
        <li class="active">#<?php echo $linha['protocolo']; ?> </li>
        <span class="pull-right"
              style="padding-top:3px; color:#999;"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
    </ul>
</section>
<div class="row-fluid">
<div class="span9">
<div class="box-conteudo" style="margin:0px;">
<? if (count($salvar["erros"]) > 0) { ?>
    <div class="control-group">
        <div class="row alert alert-error fade in" style="margin:0px;">
            <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
            <strong><?= $idioma["form_erros"]; ?></strong>
            <? foreach ($salvar["erros"] as $ind => $val) { ?><br/><?php echo $idioma[$val]; ?><? } ?>
        </div>
    </div>
<? } ?>
<? if (count($linha["erros"]) > 0) { ?>
    <div class="control-group">
        <div class="row alert alert-error fade in" style="margin:0px;">
            <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
            <strong><?= $idioma["form_erros"]; ?></strong>
            <? foreach ($linha["erros"] as $ind => $val) { ?><br/><?php echo $idioma[$val] . $val; ?><? } ?>
        </div>
    </div>
<? } ?>
<? if ($_POST["msg"]) { ?>
    <div class="alert alert-success fade in">
        <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
        <strong><?= $idioma[$_POST["msg"]]; ?></strong>
    </div>
    <script>alert('<?= $idioma[$_POST["msg"]]; ?>');</script>
<? } ?>
<? if ($mensagem["erro"]) { ?>
    <div class="alert alert-error">
        <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
        <?= $idioma[$mensagem["erro"]]; ?>
    </div>
    <script>alert('<?= $idioma[$mensagem["erro"]]; ?>');</script>
<? } ?>
<div class="control-group">
<div class="page-header" style="border-bottom:0px;">
            <span class="pull-right" style="padding-top:3px; color:#999;">			

			  <?php if ($linha['cliente_visualiza'] == 'S') { ?>
                  <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/cliente_bloquear"
                     class="btn btn-primary" style="color:#FFFFFF;"> Visualizado pelo cliente </a> &nbsp;
              <?php } else { ?>
                  <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/cliente_liberar"
                     class="btn btn-danger" style="color:#FFFFFF;"> Não Visualizado pelo cliente </a> &nbsp;
              <?php } ?>
			  
            </span>

    <h3>
        <?php echo $idioma["atendimento"]; ?> #<?php echo $linha['protocolo']; ?>
        <small><?php if ($linha['idclone']) { ?>(<?php echo $idioma["atendimento_clone"]; ?> <a
                href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $clone['idatendimento']; ?>/visualiza">#<?php echo $clone['protocolo']; ?></a>)<?php } ?>
        </small>
    </h3>
    <small><?= $idioma["data_abertura"]; ?><?= formataData($linha["data_cad"], "br", 1); ?>
        (<?php echo diferencaDias($linha["data_cad"]); ?>)
    </small>
    <br/>
    <small><?= $idioma["tempo_medio"]; ?><?php if ($linha["tempo_resposta"]) {
            echo retornaTempo($linha["tempo_resposta"]);
        } else {
            echo "--";
        } ?></small>
    <br/>
    <small><?= $idioma["tempo_finalizar"]; ?><?php if ($linha["tempo_finalizado"]) {
            echo retornaTempo($linha["tempo_finalizado"]);
        } else {
            echo "--";
        } ?></small>
</div>
<br/>
<small><?= $idioma["titulo"]; ?> </small>
<h4><?= $linha["nome"]; ?></h4>
<br/>
<small><?php echo $idioma["prioridade"]; ?> </small>
<br/>

<div
    class="btn <?php if ($linha['prioridade'] == 'B') { ?> btn-success <?php } else if ($linha['prioridade'] == 'A') { ?> btn-danger <?php } else if ($linha['prioridade'] == 'N') { ?> btn-info <?php } ?> btn-small"
    style="cursor:default;"> <?php echo $prioridades[$config['idioma_padrao']][$linha['prioridade']]; ?> </div>
<br/>
<br/>
<small><?php echo $idioma["assunto"]; ?> </small>
<h4><?php echo $linha['assunto']; ?>  <? if (!empty($linha['subassunto'])) echo ' -> ' . $linha['subassunto']; ?></h4>
<br/>
<small><?php echo $idioma["curso"]; ?> </small>
<h4><?php echo $linha['curso']; ?></h4>
<br/>
<?php if ($linha["empreendimento"]) { ?>
    <h4><?php echo $linha["empreendimento"] . " -&gt; " . $linha["etapa"] . " -&gt; " . $linha["bloco"] . " -&gt; " . $unidadeDados["nome"]; ?> </h4>
    <br/>
<?php } ?>
<small><?php echo $idioma["situacao"]; ?> </small>
<br/>
<fieldset>
    <div id="divSituacoes" style="padding-top:15px; padding-bottom:25px;">
        <? foreach ($situacaoWorkflow as $ind => $val) { ?>
            <span
                id="sit_<?= $ind; ?>" <? ($ind == $linha['idsituacao']) ? print 'class="status ativo" style="background-color: #' . $val["cor_bg"] . ';color: #' . $val["cor_nome"] . '"' : print 'class="status inativo"'; ?>
                <? if (in_array($ind, $array_situacoes)) { ?>onclick="modificarSituacao('<?= $ind; ?>','<?= $val["nome"]; ?>');"<? } else { ?>data-original-title="<?= $idioma['indisponivel']; ?>" style="background-color:#CCC" rel="tooltip"<? } ?>>
				  <?= $val["nome"]; ?>
                </span>
        <? } ?>
    </div>
    <script type="text/javascript">
        function modificarSituacao(para, nome) {
            var de = "<?= $reserva["idsituacao"]; ?>";
            var msg = "<?=$idioma['confirma_altera_situacao_atendimento'];?>";
            msg = msg.replace("[[idreserva]]", "<?=$url[3];?>");
            msg = msg.replace("[[nome]]", nome);
            var confirma = confirm(msg);
            if (confirma) {
                document.getElementById('situacao_para').value = para;
                document.getElementById('form_situacao').submit();
            } else {
                return false;
            }
        }
    </script>
    <form method="post" action="#situacao" id="form_situacao">
        <input name="acao" type="hidden" value="alterarSituacao"/>
        <input name="situacao_para" id="situacao_para" type="hidden" value=""/>
    </form>
</fieldset>
<br/>
<br/>

<div class="nav li">
    <h4><? echo $linha["nome"]; ?></h4>
    <small><?= $idioma["dia"]; ?><?= formataData($linha["data_cad"], "br", 1); ?> <?= $idioma["por"]; ?> <?php echo ($linha["usuario"]) ? $linha["usuario"] : $linha["cliente"]; ?></small>
    <br/><br/>
    <?php echo $linha['descricao']; ?>

    <br/><br/>
    <?php if (count($arquivos)) { ?>
        <strong><?= $idioma["arquivo"]; ?></strong>
        <br/>
        <?php foreach ($arquivos as $arquivo) { ?>
            <div id="arquivo<?= $arquivo["idarquivo"]; ?>">
                <span class="icon-file"></span>
                <a href="<?php echo "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/download_ate/" . $arquivo["idarquivo"]; ?>"><?= $arquivo["nome"]; ?>
                    (<?= tamanhoArquivo($arquivo["tamanho"]); ?>)</a>
                <br/>
            </div>
        <?php } ?>
    <?php } ?>
    <br/>
</div>


<?php
if ($respostas) {
    $count = 0;
    foreach ($respostas as $resposta) {
        $count++;
        ?>
        <ul class="nav nav-tabs nav-stacked">
            <li class="li">
                <small>
                    #<strong><?= $resposta['idresposta'] ?></strong> &nbsp;
                    <strong><?= $idioma["dia"]; ?></strong><?= formataData($resposta["data_cad"], "br", 1); ?>
                    <strong><?= $idioma["por"]; ?></strong>
                    <?php
                    if ($resposta["cliente"])
                        echo $resposta["cliente"] . ' (Cliente)';
                    else
                        echo $resposta["usuario"] . ' (Gestor)';
                    ?>

                </small>
                        <span class="pull-right" style="padding-top:3px; color:#999;">
                            <?php if ($count == count($respostas) && $resposta["usuario"] && $situacao_atendimento['fim'] != 'S' && ($editar_mensagem_cliente == $resposta['idresposta'] || $resposta['publica'] == 'N')) { ?>
                                <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/editarmensagem/<?= $resposta["idresposta"]; ?>"
                                   rel="facebox"><span class="icon-edit" style="cursor:pointer;"
                                                       data-original-title="<?php echo $idioma["editar"]; ?>"
                                                       data-placement="left" rel="tooltip"></span></a>&nbsp;&nbsp;
                            <?php } ?>
                            <div
                                class="btn btn-small <?php if ($resposta['publica'] == 'S') echo 'dropdown-toggle'; else echo 'btn-danger'; ?>"
                                style="cursor:default;"> <?php if ($resposta['publica'] == 'S') echo $idioma["msg_publica"]; else echo $idioma["msg_privada"]; ?> </div>
                        </span>
                <br/>
                <br/>
                <?php if ($resposta['resposta']) echo nl2br($resposta['resposta']); else echo nl2br($resposta['automatica']); ?>
                <?php if (count($resposta["arquivos"])) { ?>
                    <br/>
                    <br/>
                    <strong><?= $idioma["arquivo"]; ?></strong>
                    <br/>
                    <?php foreach ($resposta["arquivos"] as $arquivos) { ?>
                        <div id="arquivo<?= $arquivos["idarquivo"]; ?>">
                            <span class="icon-file"></span>
                            <a href="<?php echo "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/download/" . $resposta["idresposta"] . "/" . $arquivos["idarquivo"]; ?>"><?= $arquivos["nome"]; ?>
                                (<?= tamanhoArquivo($arquivos["tamanho"]); ?>)</a>
                            <br/>
                        </div>
                    <?php } ?>
                <?php } ?>
            </li>
        </ul>
    <?php } ?>
<?php } else { ?>
    <ul class="nav nav-tabs nav-stacked">
        <li>
            <span> <?php echo $idioma["sem_mensagem"]; ?> </span>
        </li>
    </ul>
<?php } ?>

<?php if ($situacao_atendimento['fim'] != 'S') { ?>
    <br/>
    <strong><?php echo $idioma['responder']; ?></strong>
    <div style="border:#CCC solid 1px;" class="row-fluid" id="msg_principal">
        <br/><br/><br/>

        <div class="divCentralizada">
            <strong><?php echo $idioma['tipo_selecao']; ?></strong><br/><br/>

            <div style="float:left;">
                <a href="javascript:void(0);" class="span3 btn" id="respostaAuto" onclick="msg_ativa('auto');">
                    <?php echo $idioma['resposta_automatica']; ?>
                </a>
            </div>
            <div style="float:left;">
                <a href="javascript:void(0);" class="span3 btn" id="respostaManu" onclick="msg_ativa('msg');">
                    <?php echo $idioma['resposta_manual']; ?>
                </a>
            </div>
        </div>
    </div>

    <form name="resposta_manual" method="post" enctype="multipart/form-data" onsubmit="return validaMsg();" id="msg"
          style="display:none;">
        <div style="border:#CCC solid 1px; padding-bottom:10px;" class="row-fluid">
            <div style="width:90%; padding-left:15px;">
                <br/>
                <small><?php echo $idioma['mensagem']; ?></small>
                <br/>
                <select id="resposta_atendimento_auto" name="resposta_atendimento_auto" style="display:none;"
                        onchange="set_resposta();">
                    <option value=""><?php echo $idioma['selecione_mensagem']; ?></option>
                    <?php foreach ($respostas_automaticas as $respostas_automatica) { ?>
                        <option
                            value="<?php echo $respostas_automatica['idresposta']; ?>"><?php echo $respostas_automatica['nome']; ?></option>
                    <?php } ?>
                </select>
                <br/>
                <textarea name="resposta_atendimento" id="resposta_atendimento" rows="5" style="width:99%;"></textarea>
                <br/>
                <br/>

                <div style="float:left;" id="div_arquivos">
                    <small><?php echo $idioma['proxima_acao']; ?></small>
                    <input type="text" name="proxima_acao"/>
                    <br/>
                    <br/>
                    <input type="checkbox" name="publica" value="1"/>
                    <small><?php echo $idioma['publica']; ?></small>
                    <br/>
                    <?php if ($linha["idsituacao"] != $situacaoRespondidoGestor["idsituacao"]) { ?>
                        <input type="checkbox" name="marcar_respondido" value="1" checked="checked"/>
                        <small><?php echo $idioma['marcar_respondido']; ?>
                            <strong><?php echo $situacaoRespondidoGestor['nome']; ?></strong></small>
                        <br/>
                    <?php } ?>
                    <br/>
                    <?php echo $idioma['anexar_arquivo']; ?>
                    <input type="button" class="btn btn-primary btn-mini" onclick="novo_arquivo();" name="enviar"
                           value=" + "/>
                    <br/>
                    <input type="file" name="arquivos[1]" id="arquivos[1]"/><br/>
                </div>
                <input type="hidden" name="acao" value="responder_atendimento">

                <div style="float:right;"><input type="submit" class="btn btn-primary" name="enviar" value="Enviar"/>
                </div>
            </div>
            <div style="float:right; margin-right:3px;">
                <input type="button" class="btn" id="respostaManu" onclick="msg_ativa('principal');"
                       value="<?php echo $idioma['btn_cancelar']; ?>"/>
            </div>
        </div>
    </form>

<?php } ?>

</div>

</div>
</div>

<div class="span3">
    <div class="box-conteudo">
        <?php if ($situacao_atendimento['fim'] != 'S') { ?>
            <a class="btn" href="#pagEncaminhar" rel="facebox"><?php echo $idioma['encaminhar']; ?></a><br/><br/>
            <a class="btn" href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/informacoesgerenciais"
               rel="facebox"><?php echo $idioma['informacoes_gerenciais']; ?></a><br/><br/>
            <a class="btn" href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/checklist"
               rel="facebox"><?php echo $idioma['checklist']; ?></a><br/><br/>
            <div id="pagEncaminhar" style="display:none;">
                <iframe src="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/encaminhar" width="500"
                        height="300" frameborder="0"></iframe>
            </div>
            <form method="post" id="formClonar">
                <input name="acao" type="hidden" value="clonar"/>
                <input name="idatendimento" type="hidden" value="<?php echo $linha["idatendimento"]; ?>"/>
            </form>
            <input type="button" class="btn" value="<?php echo $idioma['clonar']; ?>" onclick="confirmaClonar();"/><br/>
            <br/>
        <?php } ?>
        <a class="btn" href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/quem_visualiza" rel="facebox"><?php echo $idioma['quem_visualiza']; ?></a><br/><br/>
        <?php if($linha["idmatricula"]) { ?>
            <a class="btn" href="/<?= $url[0]; ?>/academico/matriculas/<?= $linha["idmatricula"]; ?>/dossie" target="_blank"><?php echo $idioma['dossie']; ?></a><br /><br />
        <?php } ?>
        <div style="border:#CCC solid 1px;">
            <strong style="line-height:30px;">&nbsp; <?php echo $idioma['avaliacao']; ?></strong>
            <br/>
            <?php $nota = $linha['avaliacao']; ?>
            <input type="hidden" value="<?php echo $nota; ?>" id="avaliacao_antiga"/>

            <div style=" height:30px; margin-left:5px;" id="estrelas_avalicao">
                <div
                    id="star_1" <?php if (!$nota) { ?>  onmouseover="over(this)" onmouseout="out()" onmouseup="avaliar(this)" <?php } ?>
                    class="star-rating <?php if ($nota == '1') echo 'selected'; ?>"></div>
                <div
                    id="star_2" <?php if (!$nota) { ?>  onmouseover="over(this)" onmouseout="out()" onmouseup="avaliar(this)" <?php } ?>
                    class="star-rating <?php if ($nota == '2') echo 'selected'; ?>"></div>
                <div
                    id="star_3" <?php if (!$nota) { ?>  onmouseover="over(this)" onmouseout="out()" onmouseup="avaliar(this)" <?php } ?>
                    class="star-rating <?php if ($nota == '3') echo 'selected'; ?>"></div>
                <div
                    id="star_4" <?php if (!$nota) { ?>  onmouseover="over(this)" onmouseout="out()" onmouseup="avaliar(this)" <?php } ?>
                    class="star-rating <?php if ($nota == '4') echo 'selected'; ?>"></div>
                <div
                    id="star_5" <?php if (!$nota) { ?>  onmouseover="over(this)" onmouseout="out()" onmouseup="avaliar(this)" <?php } ?>
                    class="star-rating <?php if ($nota == '5') echo 'selected'; ?>"></div>
            </div>
        </div>
        <br/>


        <table class="tabela" cellpadding="4" cellspacing="0">
            <tr class="linha">
                <td class="coluna"><strong><?php echo $idioma['matricula_cliente']; ?></strong></td>
                <td><?php echo $linha["idmatricula"]; ?></td>
            </tr>
            <tr class="linha">
                <td class="coluna"><strong><?php echo $idioma['cod_cliente']; ?></strong></td>
                <td><?php echo $linha["idpessoa"]; ?></td>
            </tr>
            <tr class="linha">
                <td class="coluna"><strong><?php echo $idioma['nome_cliente']; ?></strong></td>
                <td><?php echo $linha["cliente"]; ?></td>
            </tr>
            <tr class="linha">
                <td class="coluna"><strong><?php echo $idioma['telefone_cliente']; ?></strong></td>
                <td><?php echo $pessoaDados['telefone']; ?></td>
            </tr>
            <tr class="linha">
                <td class="coluna"><strong><?php echo $idioma['telefone2_cliente']; ?></strong></td>
                <td><?php echo $pessoaDados['celular']; ?></td>
            </tr>
            <tr class="linha">
                <td class="coluna"><strong><?php echo $idioma['email_cliente']; ?></strong></td>
                <td><?php echo $pessoaDados['email']; ?></td>
            </tr>
        </table>

        <br/>

        <table class="tabela" cellpadding="4" cellspacing="0">
            <tr class="linha">
                <td class="coluna"><strong><?php echo $idioma['abertura_atendimento']; ?></strong></td>
                <td><?php echo formataData($linha["data_cad"], 'pt', 1); ?></td>
            </tr>
            <tr class="linha">
                <td class="coluna"><strong><?php echo $idioma['interacao_atendimento']; ?></strong></td>
                <td><?php if ($ultimaInteracao['data_cad']) echo formataData($ultimaInteracao['data_cad'], 'pt', 1); else echo '--'; ?></td>
            </tr>
            <tr class="linha">
                <td class="coluna"><strong><?php echo $idioma['atendente_atendimento']; ?></strong></td>
                <td><?php if ($ultimoAtendente['usuario']) echo $ultimoAtendente['usuario']; else echo '--'; ?></td>
            </tr>
            <tr class="linha">
                <td class="coluna"><strong><?php echo $idioma['assunto_atendimento']; ?></strong></td>
                <td><?php echo $linha["assunto"]; ?></td>
            </tr>
            <tr class="linha">
                <td class="coluna"><strong><?php echo $idioma['subassunto_atendimento']; ?></strong></td>
                <td><?php echo $linha["subassunto"]; ?></td>
            </tr>
        </table>
        <br/>

        <div style="width:100%; overflow:auto; height:300px; border:#CCC solid 1px;">
            <table class="" cellpadding="5" cellspacing="0" width="100%">
                <tr class="linha">
                    <td class="coluna_fim">
                        <h5><?php echo $idioma['titulo_historico']; ?></h5><br/>
                        <strong><?php echo $idioma['atendimento_aberto']; ?></strong><br/>
                        <?php echo formataData($linha["data_cad"], 'pt', 1); ?>,
                        <strong><?php echo $idioma['por']; ?></strong> <?php echo ($linha["usuario"]) ? $linha["usuario"] : $linha["cliente"]; ?>
                    </td>
                </tr>
                <?php foreach ($historicos as $historico) { ?>
                    <tr class="linha">
                        <td class="coluna_fim">
                            <strong>
                                <?php
                                echo $idioma["historico_" . $historico["tipo"]];
                                switch ($historico["tipo"]) {
                                    case "E":
                                        echo '<br /><br /><span class="status" style="background-color:#EEEEEE;color:#000000" >' . $historico["assunto_de"] . '</span> -&gt; ';
                                        echo '<span class="status" style="background-color:#EEEEEE;color:#000000" >' . $historico["assunto_para"] . '</span><br />';
                                        break;
                                    case "ES":
                                        if (empty($historico["subassunto_de"]))
                                            echo '<br /><br />' . $idioma['historico_vazio'] . ' -&gt; ';
                                        else
                                            echo '<br /><br /><span class="status" style="background-color:#EEEEEE;color:#000000" >' . $historico["subassunto_de"] . '</span> -&gt; ';
                                        if (empty($historico["subassunto_para"]))
                                            echo $idioma['historico_vazio'];
                                        else
                                            echo '<span class="status" style="background-color:#EEEEEE;color:#000000" >' . $historico["subassunto_para"] . '</span><br />';
                                        break;
                                    case "IP":
                                        echo '<br /><br /><span class="status" style="background-color:#EEEEEE;color:#000000" >' . $prioridades[$config["idioma_padrao"]][$historico["de"]] . '</span> -&gt; ';
                                        echo '<span class="status" style="background-color:#EEEEEE;color:#000000" >' . $prioridades[$config["idioma_padrao"]][$historico["para"]] . '</span><br />';
                                        break;
                                    case "IA":
                                        echo "<br />";
                                        if ($historico["de"]) {
                                            echo "De: " . formataData($historico["de"], 'pt', 0) . " -> ";
                                        }
                                        echo "Para: " . formataData($historico["para"], 'pt', 0);
                                        break;
                                    case "CU":
                                        echo $historico["usuario_convidado"];
                                    case "CI":
                                        if ($historico['idimobiliaria_convidada'])
                                            echo "Convidou imobiliária: <br />" . $historico["imobiliaria_convidada"];
                                        else
                                            echo "Alterou o atendimento para: \"não encaminhar para imobiliária\" <br />";
                                        break;
                                    case "CC":
                                        echo "Convidou corretor: <br />" . $historico["corretor_convidado"];
                                        break;
                                    case "CAO":
                                        echo $historico["protocolo_clone"];
                                        break;
                                    case "CAD":
                                        echo $historico["protocolo_clone"];
                                        break;
                                    case "S":
                                        echo "<br /><br />";
                                        if ($historico["status_de"]) {
                                            echo '<span class="status" style="background-color:#' . $historico["cor_de"] . '" >' . $historico["status_de"] . '</span> -&gt; ';
                                        }
                                        echo '<span class="status" style="background-color:#' . $historico["cor_para"] . '" >' . $historico["status_para"] . '</span><br />';
                                        break;
                                    case "A":
                                        echo "<br />";
                                        if ($historico["de"]) {
                                            echo "De: " . $historico["de"] . " -> ";
                                        }
                                        echo "Para: " . $historico["para"];
                                        break;
                                    case "UNI":
                                        if (empty($historico["unidade_de"]))
                                            echo '<br /><br />' . $idioma['historico_vazio'] . ' -&gt; ';
                                        else
                                            echo '<br /><br /><span class="status" style="background-color:#EEEEEE;color:#000000" >' . $historico["unidade_de"] . '</span> -&gt; ';
                                        if (empty($historico["unidade_para"]))
                                            echo $idioma['historico_vazio'];
                                        else
                                            echo '<span class="status" style="background-color:#EEEEEE;color:#000000" >' . $historico["unidade_para"] . '</span><br />';
                                        break;
                                    case "CL":
                                        echo "Liberou o atendimento para o cliente <br />";
                                        break;
                                    case "CB":
                                        echo "Bloqueou o atendimento para o cliente<br />";
                                        break;
                                    case "ROC":
                                        echo "Desmarcou a opção : <br />" . $historico["opcao"];
                                        break;
                                    case "AOC":
                                        echo "Marcou a opção : <br />" . $historico["opcao"];
                                        break;

                                }
                                ?>
                            </strong>
                            <br/>
                            <?php /*echo formataData($historico["data_cad"],'pt',1); ?><?php if($historico["usuario"] || $historico["cliente"]) { ?>, <strong><?php echo $idioma['por']; ?></strong><?php } ?> <?php if($historico["usuario"]) { echo $historico["usuario"]; } elseif($historico["cliente"]) { echo $historico["cliente"]; } */ ?>
                            <?php echo formataData($historico["data_cad"], 'pt', 1); ?>
                            <?php if ($historico["usuario"] || $historico["cliente"] || $historico["usuario_imobiliaria"] || $historico["corretor"]) { ?>,
                                <strong><?php echo $idioma['por']; ?></strong>
                            <?php } ?>
                            <?php
                            if ($historico["usuario"]) {
                                echo $historico["usuario"] . ' (Gestor)';
                            } elseif ($historico["cliente"]) {
                                echo $historico["cliente"] . ' (Cliente)';
                            } elseif ($historico["usuario_imobiliaria"]) {
                                echo $historico["usuario_imobiliaria"] . ' (Imobiliária)';
                            } elseif ($historico["corretor"]) {
                                echo $historico["corretor"] . ' (Corretor)';
                            } ?>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>

</div>
<? incluirLib("rodape", $config, $usuario); ?>
</div>

<?php 
if ($linha['idmatricula']) {
    incluirTela("cabecalho_info", $config, $matriculaDados); 
}    
?>

</body>

<script type="text/javascript">
    var regras = new Array();
    regras.push("formato_arquivo,arquivos[1],jpg|jpeg|gif|png|bmp|zip|rar|tar|gz|doc|docx|xls|xlsx|ppt|pptx|pps|ppsx|txt|pdf,'',<?php echo $idioma['arquivo_invalido']; ?>");
</script>

<script src="/assets/plugins/jquery-ui/jquery-ui.min.js"></script>

<script src="/assets/plugins/jquery-ui/ui/jquery.ui.datepicker.js"></script>
<script src="/assets/plugins/jquery-ui/ui/i18n/jquery.ui.datepicker-pt-BR.js"></script>
<script language="javascript" type="text/javascript">
    jQuery(document).ready(function ($) {
        $("input[name='proxima_acao']").datepicker($.datepicker.regional["pt-BR"]);
    });
</script>

<link media="screen" href="/assets/plugins/jquery.msg/jquery.msg.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/assets/plugins/jquery.msg/jquery.center.min.js"></script>
<script type="text/javascript" src="/assets/plugins/jquery.msg/jquery.msg.min.js"></script>
<script>
    function modificarStatus(situacao, cor) {
        confirma = confirm("<?php echo $idioma["confirma_modificar_situacao"]; ?>");
        if (!confirma)
            return false;
        $.msg({
            autoUnblock: false,
            clickUnblock: false,
            klass: 'white-on-black',
            content: 'Processando solicitação.',
            afterBlock: function () {
                var self = this;
                jQuery.ajax({
                    url: "/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/json/modificar_status",
                    dataType: "json",
                    type: "POST",
                    data: {modificar_status: situacao},
                    success: function (json) {
                        if (json.sucesso) {
                            altualizaBotoes(json.situacao, json.situacoes_json, cor);
                            self.unblock();
                        } else {
                            alert('<?php echo $idioma['erro_json']; ?>');
                            self.unblock();
                        }
                    }
                });
            }
        });
    }

    function altualizaBotoes(situacao, situacoes_json, cor) {

        cores_arr = new Array();
        <?php
        foreach($situacaoWorkflow as $ind => $val){
            ?>
        cores_arr['<?=$ind; ?>'] = '#<?=$val["cor_bg"]; ?>';
        <?php
           }
           ?>

        situacoes_array = Array();
        for (var a = 0; a < situacoes_json.length; a++)
            situacoes_array[a] = situacoes_json[a]['idsituacao_para'];

        var situacoes = document.getElementById('divSituacoes').getElementsByTagName('span');
        var tamanho = (situacoes.length);
        for (var i = 0; i < tamanho; i++) {
            var arr = situacoes[i].id.split('_');
            var novo_id = arr[1];
            if (novo_id != situacao) {
                var id = situacoes[i].id;
                document.getElementById(id).setAttribute("style", "background-color:#666;");
                $("#" + id).removeClass("ativo");
                $("#" + id).addClass("inativo");

                if (situacoes_array.indexOf(novo_id) == -1) {
                    document.getElementById(id).removeAttribute("onClick");
                    document.getElementById(id).setAttribute("rel", "tooltip");
                    document.getElementById(id).setAttribute("style", "background-color:#CCC;");
                    document.getElementById(id).setAttribute("data-original-title", "<?php echo $idioma['indisponivel']; ?>");
                    $("span[rel*=tooltip]").tooltip({ });
                } else {
                    document.getElementById(id).setAttribute("onClick", "modificarStatus('" + novo_id + "','" + cores_arr[novo_id] + "');");
                    document.getElementById(id).removeAttribute("rel");
                    document.getElementById(id).removeAttribute("data-original-title");
                }
            }
        }
        if (situacao != "") {
            var id_charp = "#sit_" + situacao;
            var id_sem = "sit_" + situacao;
            var background = "background-color:" + cor + ";";
            $(id_charp).addClass("ativo");
            document.getElementById(id_sem).setAttribute("style", background);
        }
    }

</script>

<script>
    function confirmaClonar() {
        confirma = confirm("<?php echo $idioma["confirma_clonar"]; ?>");
        if (confirma) {
            document.getElementById("formClonar").submit();
        } else {
            return false;
        }
    }
</script>

<script type="text/javascript" language="javascript">iniciar_rating();</script>
<script>
    function remove_avaliacao() {
        array_star = document.getElementById('estrelas_avalicao').getElementsByTagName('div');
        tam_star = array_star.length;
        for (var i = 0; i < tam_star; i++) {
            array_star[i].removeAttribute("onmouseover");
            array_star[i].removeAttribute("onmouseout");
            array_star[i].removeAttribute("onmouseup");
        }
        return true;
    }

    function avaliar(avaliacao) {
        array = avaliacao.id.split('_');
        valor_avaliacao = array[1];
        if (!confirm('<?php echo $idioma['confirmar_avaliacao'] ?>'))
            return false;

        $.msg({
            autoUnblock: false,
            clickUnblock: false,
            klass: 'white-on-black',
            content: 'Processando solicitação.',
            afterBlock: function () {
                var self = this;
                jQuery.ajax({
                    url: "/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/json/avaliar",
                    dataType: "json",
                    type: "POST",
                    data: {avaliar: valor_avaliacao},
                    success: function (json) {
                        if (json.sucesso) {
                            ;
                            remove_avaliacao()
                            selecionar(avaliacao);
                            alert('<?php echo $idioma["avaliar_sucesso"]; ?>');
                            self.unblock();
                        } else {
                            alert('<?= $idioma["erro_json"]; ?>');
                            self.unblock();
                        }
                    }
                });
            }
        });
    }
</script>

<script type="text/javascript" language="javascript">
    function set_resposta() {
        var id_sel = document.getElementById('resposta_atendimento_auto').options[document.getElementById('resposta_atendimento_auto').selectedIndex].value;

        $.msg({
            autoUnblock: false,
            clickUnblock: false,
            klass: 'white-on-black',
            content: 'Processando solicitação.',
            afterBlock: function () {
                var self = this;
                jQuery.ajax({
                    url: "/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/json/resposta_automatica",
                    dataType: "json",
                    type: "POST",
                    data: {id: id_sel},
                    success: function (json) {
                        if (json.sucesso) {
                            ;
                            document.getElementById('resposta_atendimento').style.display = '';
                            document.getElementById('resposta_atendimento').value = json.resposta;
                            self.unblock();
                        } else {
                            alert('<?= $idioma["erro_json"]; ?>');
                            self.unblock();
                        }
                    }
                });
            }
        });
    }
</script>

</html>