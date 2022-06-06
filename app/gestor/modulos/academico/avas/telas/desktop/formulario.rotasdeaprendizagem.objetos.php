<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php incluirLib("head",$config,$usuario); ?>
    <link href="/assets/css/menuVertical.css" rel="stylesheet" />
</head>
<body>
<?php incluirLib("topo",$config,$usuario); ?>
<div class="container-fluid">
    <section id="global">
        <div class="page-header"><h1><?= $idioma["pagina_titulo"]; ?> &nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1></div>
        <ul class="breadcrumb">
            <li><a href="/<?= $url[0]; ?>"><?= $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
            <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>"><?= $idioma["nav_modulo"]; ?></a> <span class="divider">/</span></li>
            <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"><?= $idioma["pagina_titulo"]; ?></a> <span class="divider">/</span></li>
            <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $linha["idava"]; ?>/editar"><? echo $linha["ava"]; ?></a> <span class="divider">/</span> </li>
            <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $linha["idava"]; ?>/rotasdeaprendizagem"><?= $idioma["pagina_titulo_interno"]; ?></a> <span class="divider">/</span></li>
            <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $linha["idava"]; ?>/rotasdeaprendizagem/<?= $linha["idrota_aprendizagem"]; ?>/editar"><?php echo $linha["nome"]; ?></a></li> <span class="divider">/</span></li>
            <li class="active"><?php echo $idioma["nav_formulario"]; ?></li>
            <span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
        </ul>
    </section>
    <div class="row-fluid">
        <div class="span12">
            <div class="box-conteudo box-ava">
                <div class="tabbable tabs-left">
                    <?php incluirTela("inc_submenu",$config,$linha); ?>
                    <div class="ava-conteudo">
                        <div class="pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"><i class="icon-share-alt"></i> <?= $idioma["btn_sair"]; ?></a></div>
                        <?php if($url[5] != "cadastrar") { ?><h2 class="tituloEdicao"><?php echo $linha["nome"]; ?></h2><?php } ?>
                        <?php include("inc_submenu_rotasdeaprendizagem.php"); ?>
                        <div class="tab-pane active" id="tab_editar">
                            <h2 class="tituloOpcao"><?php echo $idioma["titulo_opcao_objetos"]; ?></h2>
                            <? if($_POST["msg"]) { ?>
                                <div class="alert alert-success fade in">
                                    <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                                    <strong><?= $idioma[$_POST["msg"]]; ?></strong>
                                </div>
                            <? } ?>
                            <? if(count($salvar["erros"]) > 0){ ?>
                                <div class="alert alert-error fade in">
                                    <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                                    <strong><?= $idioma["form_erros"]; ?></strong>
                                    <? foreach($salvar["erros"] as $ind => $val) { ?>
                                        <br />
                                        <?php echo $idioma[$val]; ?>
                                    <? } ?>
                                </div>
                            <? } ?>
                            <form class="well wellDestaque form-inline" method="post">
                                <table>
                                    <tr>
                                        <td><?= $idioma["form_ordem"]; ?></td>
                                        <td><?= $idioma["form_objeto"]; ?></td>
                                        <td><?= $idioma["form_dias"]; ?></td>
                                        <td><?= $idioma["form_vencimento"]; ?></td>
                                        <td><?= $idioma["form_tempo"]; ?></td>
                                        <td><?= $idioma["form_porcentagem"]; ?></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" class="span1" name="ordem" id="ordem" maxlength="3" /></td>
                                        <td>
                                            <select class="span4" name="objeto" id="objeto">
                                                <option value=""></option>
                                                <?php if(count($audios) > 0) { ?>
                                                    <optgroup label="<?php echo $idioma["select_audios"]; ?>">
                                                        <?php foreach($audios as $audio) { ?>
                                                            <option value="audio|<?php echo $audio["idaudio"]; ?>"><?php echo $audio["nome"]; ?></option>
                                                        <?php } ?>
                                                    </optgroup>
                                                <?php } ?>
                                                <?php if(count($objetosdivisores) > 0) { ?>
                                                    <optgroup label="<?php echo $idioma["select_objetos_divisores"]; ?>">
                                                        <?php foreach($objetosdivisores as $objetodivisor) { ?>
                                                            <option id="objeto_divisor_<?= $objetodivisor["idobjeto_divisor"] ?>" value="objeto_divisor|<?php echo $objetodivisor["idobjeto_divisor"]; ?>">
                                                                <?php echo $objetodivisor["nome"]; ?>
                                                            </option>
                                                        <?php } ?>
                                                    </optgroup>
                                                <?php } ?>
                                                <?php if(count($conteudos) > 0) { ?>
                                                    <optgroup label="<?php echo $idioma["select_conteudos"]; ?>">
                                                        <?php foreach($conteudos as $conteudo) { ?>
                                                            <option value="conteudo|<?php echo $conteudo["idconteudo"]; ?>"><?php echo $conteudo["nome"]; ?></option>
                                                        <?php } ?>
                                                    </optgroup>
                                                <?php } ?>
                                                <?php if(count($downloads) > 0) { ?>
                                                    <optgroup label="<?php echo $idioma["select_downloads"]; ?>">
                                                        <?php foreach($downloads as $download) { ?>
                                                            <option value="download|<?php echo $download["iddownload"]; ?>"><?php echo $download["nome"]; ?></option>
                                                        <?php } ?>
                                                    </optgroup>
                                                <?php } ?>
                                                <?php if(count($links) > 0) { ?>
                                                    <optgroup label="<?php echo $idioma["select_links"]; ?>">
                                                        <?php foreach($links as $link) { ?>
                                                            <option value="link|<?php echo $link["idlink"]; ?>"><?php echo $link["nome"]; ?></option>
                                                        <?php } ?>
                                                    </optgroup>
                                                <?php } ?>
                                                <?php if(count($perguntas) > 0) { ?>
                                                    <optgroup label="<?php echo $idioma["select_perguntas"]; ?>">
                                                        <?php foreach($perguntas as $pergunta) { ?>
                                                            <option value="pergunta|<?php echo $pergunta["idpergunta"]; ?>"><?php echo $pergunta["nome"]; ?></option>
                                                        <?php } ?>
                                                    </optgroup>
                                                <?php } ?>
                                                <?php if(count($enquetes) > 0) { ?>
                                                    <optgroup label="<?php echo $idioma["select_enquetes"]; ?>">
                                                        <?php foreach($enquetes as $enquete) { ?>
                                                            <option value="enquete|<?php echo $enquete["idenquete"]; ?>"><?php echo $enquete["pergunta"]; ?></option>
                                                        <?php } ?>
                                                    </optgroup>
                                                <?php } ?>
                                                <?php if(count($videos) > 0) { ?>
                                                    <optgroup label="<?php echo $idioma["select_videos"]; ?>">
                                                        <?php foreach($videos as $video) { ?>
                                                            <option value="video|<?php echo $video["idvideo"]; ?>"><?php echo $video["titulo"]; ?></option>
                                                        <?php } ?>
                                                    </optgroup>
                                                <?php } ?>
                                                <?php if(count($exercicios) > 0) { ?>
                                                    <optgroup label="<?php echo $idioma["select_exercicios"]; ?>">
                                                        <?php foreach($exercicios as $exercicio) { ?>
                                                            <option value="exercicio|<?php echo $exercicio["idexercicio"]; ?>"><?php echo $exercicio["nome"]; ?></option>
                                                        <?php } ?>
                                                    </optgroup>
                                                <?php } ?>
                                                <?php if(count($aulasonline) > 0) { ?>
                                                    <optgroup label="<?php echo $idioma["select_aula_online"]; ?>">
                                                        <?php foreach($aulasonline as $aulaonline) { ?>
                                                            <option value="aulaonline|<?php echo $aulaonline["idaula"]; ?>"><?php echo $aulaonline["nome"]; ?></option>
                                                        <?php } ?>
                                                    </optgroup>
                                                <?php } ?>
                                                <optgroup label="Reconhecimento">
                                                    <option value="reconhecimento|0">Reconhecimento</option>
                                                </optgroup>
                                                <?php /* if(count($simulados) > 0) { ?>
                      <optgroup label="<?php echo $idioma["select_simulados"]; ?>">
                          <?php foreach($simulados as $simulado) { ?>
                          <option value="simulado|<?php echo $simulado["idsimulado"]; ?>"><?php echo $simulado["nome"]; ?></option>
                          <?php } ?>
                      </optgroup>
                      <?php }*/ ?>
                                            </select>
                                        </td>
                                        <td><input type="text" class="span2" name="vencimento" id="vencimento" /></td>
                                        <td><input type="text" class="span2" name="tempo" id="tempo" /></td>
                                        <td><input type="text" class="span2" name="porcentagem" id="porcentagem" maxlength="6" /></td>
                                        <td>
                                            <input type="hidden" id="acao" name="acao" value="cadastrar_objeto">
                                            <input type="submit" class="btn" value="<?= $idioma["btn_adicionar"]; ?>" />
                                        </td>
                                    </tr>
                                </table>
                            </form>
                            <form method="post" id="remover_objeto" name="remover_objeto">
                                <input type="hidden" id="acao" name="acao" value="remover_objeto">
                                <input type="hidden" id="remover" name="remover" value="">
                            </form>
                            <form method="post" id="editar_objeto" name="editar_objeto">
                                <input type="hidden" id="acao" name="acao" value="editar_objeto">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th width="80"><?= $idioma["tabela_ordem"]; ?></th>
                                        <th width="100"><?= $idioma["tabela_tipo"]; ?></th>
                                        <th><?= $idioma["tabela_nome"]; ?></th>
                                        <th width="80"><?= $idioma["tabela_dias"]; ?></th>
                                        <th width="80"><?= $idioma["tabela_pre_requisito"]; ?></th>
                                        <th width="100"><?= $idioma["tabela_vencimento"]; ?></th>
                                        <th width="80"><?= $idioma["tabela_tempo"]; ?></th>
                                        <th width="80"><?= $idioma["tabela_porcentagem"]; ?></th>
                                        <th width="60"><?= $idioma["gerar_data_final"]; ?></th>
                                        <th width="60"><?= $idioma["tabela_opcoes"]; ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if(count($objetos) > 0) {
                                        $validacao = "";
                                        $preRequisitos = array();
                                        $totalPorcentagem = 0;
                                        foreach($objetos as $objeto) {
                                            $validacao .= '$("#ordem'.$objeto["idobjeto"].'").keypress(isNumber); $("#ordem'.$objeto["idobjeto"].'").blur(isNumberCopy); ';
                                            $validacao .= '$("#tempo'.$objeto["idobjeto"].'").mask("99:99"); ';
                                            $validacao .= '$("#porcentagem'.$objeto["idobjeto"].'").maskMoney({symbol:"",decimal:",",thousands:"."}); ';
                                            $validacao .= '$("#vencimento'.$objeto["idobjeto"].'").datepicker({ currentText: "Now" }); ';
                                            $validacao .= '$("#vencimento'.$objeto["idobjeto"].'").mask("99/99/9999"); ';

                                            if($objeto['tipo'] == 'reconhecimento'){
                                                $objeto['nome_reconhecimento'] = 'Reconhecimento';
                                            }

                                            $style = NULL;
                                            if($objeto["tipo"] == 'objeto_divisor') { $style = ' style="background-color:#6CF"'; }

                                            ?>
                                            <tr>
                                                <td<?= $style; ?>><input type="text" maxlength="3" class="span1" name="objetos[<?php echo $objeto["idobjeto"]; ?>][ordem]" id="ordem<?php echo $objeto["idobjeto"]; ?>" value="<?php echo $objeto["ordem"]; ?>" /></td>
                                                <td<?= $style; ?>>
                                                    <?php echo $GLOBALS["tipo_objetos_rota_apresizagem"][$GLOBALS["config"]["idioma_padrao"]][$objeto["tipo"]]; ?>
                                                </td>
                                                <td<?= $style; ?>>
                                                    <?php echo $objeto["nome_".$objeto["tipo"]]; ?>
                                                </td>
                                                <td<?= $style; ?>><input type="text" maxlength="3" class="span1" name="objetos[<?php echo $objeto["idobjeto"]; ?>][dias]" id="dias<?php echo $objeto["idobjeto"]; ?>" value="<?php echo $objeto["dias"]; ?>" /></td>
                                                <td<?= $style; ?>>
                                                    <select class="span2" name="objetos[<?= $objeto["idobjeto"]; ?>][pre_requisito]" id="pre_requisito<?= $objeto["idobjeto"]; ?>" <?php if(count($preRequisitos) <= 0) { ?>disabled="disabled"<?php } ?>>
                                                        <option value=""></option>
                                                        <?php foreach($preRequisitos as $preRequisito) { ?>
                                                            <option value="<?php echo $preRequisito["idobjeto"]; ?>" <?php if($preRequisito['idobjeto'] == $objeto["idobjeto_pre_requisito"]) { ?>selected="selected"<?php } ?>><?php echo $preRequisito["nome_".$preRequisito["tipo"]]; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td<?= $style; ?>>
                                                    <input type="text" class="span2"
                                                           name="objetos[<?= $objeto["idobjeto"]; ?>][vencimento]"
                                                        <?php if ($objeto["tipo"] == 'objeto_divisor' || $objeto["tipo"] == 'exercicio') { ?>
                                                            disabled="disabled"
                                                        <?php } ?>
                                                           id="vencimento<?= $objeto["idobjeto"]; ?>"
                                                           value="<?php echo formataData($objeto["vencimento"], 'br', 0); ?>"  />
                                                </td>
                                                <td<?= $style; ?>>
                                                    <input type="text"
                                                           class="span1"
                                                           name="objetos[<?= $objeto["idobjeto"]; ?>][tempo]"
                                                        <?php if ($objeto["tipo"] == 'objeto_divisor' || $objeto["tipo"] == 'exercicio') { ?>
                                                            disabled="disabled"
                                                        <?php } ?>
                                                           id="tempo<?= $objeto["idobjeto"]; ?>"
                                                           value="<?php echo substr($objeto["tempo"], -5); ?>"  />
                                                </td>
                                                <td<?= $style; ?>>
                                                    <input type="text"
                                                           maxlength="6"
                                                           class="span1"
                                                        <?php if ($objeto["tipo"] == 'objeto_divisor' || $objeto["tipo"] == 'exercicio') { ?>
                                                            disabled="disabled"
                                                        <?php } ?>
                                                           name="objetos[<?= $objeto["idobjeto"]; ?>][porcentagem]"
                                                           id="porcentagem<?= $objeto["idobjeto"]; ?>"
                                                           value="<?php if($objeto["porcentagem"] || $objeto["porcentagem"] === "0") { echo number_format($objeto["porcentagem"], 2, ",", "."); } ?>"/>
                                                    <? $totalPorcentagem += $objeto["porcentagem"]; ?>
                                                </td>
                                                <td<?= $style; ?>>
                                                    <input type="radio" id="gerar_data_final" name="gerar_data_final" value="<?= $objeto["idobjeto"]; ?>" <?php if($objeto['gerar_data_final']) echo 'checked="checked"'; ?>>
                                                </td>
                                                <td<?= $style; ?>>
                                                    <a href="javascript:void(0);"
                                                       class="btn btn-mini"
                                                       data-original-title="<?= $idioma["btn_remover"]; ?>"
                                                       data-placement="left" rel="tooltip"
                                                       onclick="remover(<?php echo $objeto["idobjeto"]; ?>)">
                                                        <i class="icon-remove"></i></a>
                                                </td>

                                            </tr>
                                            <?php
                                            if($objeto["tipo"] == 'exercicio') $preRequisitos[] =  $objeto;
                                        } ?>
                                    <?php } else { ?>
                                        <tr>
                                            <td colspan="6"><?= $idioma["sem_informacao"]; ?></td>
                                        </tr>
                                    <?php } ?>


                                    <tr>
                                        <td colspan="6" align="right" style="text-align:right">Total: </td>
                                        <td><?= number_format($totalPorcentagem, 2, ",", "."); ?>%</td>
                                        <td>&nbsp;</td>
                                    </tr>



                                    </tbody>
                                </table>
                                <div class="form-actions">
                                    <input class="btn btn-primary" type="submit" value="Salvar">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <? incluirLib("rodape",$config,$usuario); ?>
    <script src="/assets/plugins/jquery-ui/ui/jquery.ui.core.js"></script>
    <script src="/assets/plugins/jquery-ui/ui/jquery.ui.widget.js"></script>
    <script src="/assets/plugins/jquery-ui/ui/jquery.ui.datepicker.js"></script>
    <script src="/assets/plugins/jquery-ui/ui/i18n/jquery.ui.datepicker-pt-BR.js"></script>
    <script type="text/javascript">
        $("#vencimento").mask("99/99/9999");
        $("#vencimento").datepicker({ currentText: "Now" });
        function remover(id) {
            confirma = confirm('<?= $idioma["confirmar_remocao"]; ?>');
            if(confirma) {
                document.getElementById("remover").value = id;
                document.getElementById("remover_objeto").submit();
            }
        }
        var regras = new Array();
        jQuery(document).ready(function($) {
            $("#tempo").mask("99:99");
            $("#porcentagem").maskMoney({symbol:"",decimal:",",thousands:"."});
            $("#ordem").keypress(isNumber);
            $("#ordem").blur(isNumberCopy);
            <?php echo $validacao; ?>

            $('#objeto').change(function() {
                var value = this.value ;
                var tipo_objeto = value.split('|') ;
                if (tipo_objeto[0] == 'objeto_divisor' || tipo_objeto[0] == 'exercicio') {

                    $('#vencimento').prop('value','');
                    $('#tempo').prop('value','');
                    $('#porcentagem').prop('value','');

                    $('#vencimento').prop('disabled',true);
                    $('#tempo').prop('disabled',true);
                    $('#porcentagem').prop('disabled',true);

                } else {
                    $('#vencimento').prop('disabled',false);
                    $('#tempo').prop('disabled',false);
                    $('#porcentagem').prop('disabled',false);
                }
            });

        });
    </script>
</div>
</body>
</html>