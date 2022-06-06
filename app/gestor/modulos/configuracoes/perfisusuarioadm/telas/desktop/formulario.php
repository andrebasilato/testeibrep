<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php incluirLib("head", $config, $usuario); ?>
    <link rel="stylesheet" href="/assets/plugins/redactor770/redactor/css/redactor.css"/>
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
        <? if ($url[3] == "cadastrar") { ?>
            <li class="active"><?= $idioma["nav_formulario"]; ?></li>
        <? } else { ?>
            <li class="active"><?php echo $linha["nome"]; ?></li>
        <? } ?>
        <span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
    </ul>
</section>
<div class="row-fluid">
    <div class="span9">
        <div class="box-conteudo">
            <div class="pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"><i
                        class="icon-share-alt"></i> <?= $idioma["btn_sair"]; ?></a></div>
            <?php if ($url[3] != "cadastrar") { ?><h2 class="tituloEdicao"><?php echo $linha["nome"]; ?></h2><?php } ?>
            <!--<div class="tabbable tabs-left">-->
            <?php //if($url[3] != "cadastrar") { incluirTela("inc_menu_edicao",$config,$linha); } ?>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_editar">
                    <h2 class="tituloOpcao"><?php if ($url[3] == "cadastrar") {
                            echo $idioma["titulo_opcao_cadastar"];
                        } else {
                            echo $idioma["titulo_opcao_editar"];
                        } ?></h2>
                    <? if ($_POST["msg"]) { ?>
                        <div class="alert alert-success fade in">
                            <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                            <strong><?= $idioma[$_POST["msg"]]; ?></strong>
                        </div>
                    <? } ?>
                    <? if (count($salvar["erros"]) > 0) { ?>
                        <div class="alert alert-error fade in">
                            <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                            <strong><?= $idioma["form_erros"]; ?></strong>
                            <? foreach ($salvar["erros"] as $ind => $val) { ?>
                                <br/>
                                <?php echo $idioma[$val]; ?>
                            <? } ?>
                        </div>
                    <? } ?>
                    <form method="post" onsubmit="return validateFields(this, regras)" enctype="multipart/form-data"
                          class="form-horizontal">
                        <input name="acao" type="hidden" value="salvar"/>
                        <? if ($url[4] == "editar") {
                            echo '<input type="hidden" name="' . $config["banco"]["primaria"] . '" id="' . $config["banco"]["primaria"] . '" value="' . $linha[$config["banco"]["primaria"]] . '" />';
                            foreach ($config["banco"]["campos_unicos"] as $campoid => $campo) {
                                ?>
                                <input name="<?= $campo["campo_form"]; ?>_antigo"
                                       id="<?= $campo["campo_form"]; ?>_antigo" type="hidden"
                                       value="<?= $linha[$campo["campo_banco"]]; ?>"/>
                            <?
                            }
                            $linhaObj->GerarFormulario("formulario", $linha, $idioma);
                        } else {
                            $linhaObj->GerarFormulario("formulario", $_POST, $idioma);
                        }
                        ?>
                        <div class="control-group">
                            <label class="control-label"><?php echo $idioma["form_permissoes"]; ?></label>

                            <div class="controls">
                                <ul class="nav nav-tabs" id="tabPerfil">
                                    <?
                                    $i = 0;
                                    foreach ($acoes as $moduloind => $modulo) {
                                        $i++;
                                        ?>
                                        <li<? if ($i == 1) { ?> class="active"<? } ?>><a
                                                href="#<?= $moduloind; ?>"><?= $modulo["nome"]; ?></a></li>
                                    <? } ?>
                                </ul>
                                <div class="tab-content">
                                    <?
                                    $i = 0;
                                    foreach ($acoes as $moduloind => $modulo) {
                                        $i++;
                                        ?>
                                        <div class="tab-pane<? if ($i == 1) { ?> active<? } ?>" id="<?= $moduloind; ?>">
                                            <div class="control-group">
                                                <label class="control-label"
                                                       for="<?= $moduloind; ?>"><strong><?= $modulo["nome"]; ?></strong></label>

                                                <div class="controls">
                                                    <label class="checkbox">
                                                        <input name="marcar_todas_<?= $moduloind; ?>"
                                                               id="marcar_todas_<?= $moduloind; ?>"
                                                               onclick="selecionaTodos('<?= $moduloind; ?>')" value=""
                                                               type="checkbox">
                                                        <em><?= $idioma["marcar_todas"]; ?></em>
                                                    </label>
                                                </div>
                                            </div>
                                            <?
                                            //print_r2($modulo["funcionalidades"]);

                                            foreach ($modulo["funcionalidades"] as $funcionalidadeid => $funcionalidade) {
                                                ?>
                                                <div class="control-group">
                                                    <label class="control-label"
                                                           for="<?= $funcionalidadeid; ?>"><strong><?= $funcionalidade["nome"]; ?></strong></label>

                                                    <div class="controls" id="<?= $funcionalidadeid; ?>">
                                                        <?php if (count($funcionalidade["acoes"]) > 1) { ?>
                                                            <label class="checkbox">
                                                                <input name="marcar_todas_<?= $funcionalidadeid; ?>"
                                                                       id="marcar_todas_<?= $funcionalidadeid; ?>"
                                                                       onclick="selecionaTodos('<?= $funcionalidadeid; ?>')"
                                                                       value="" type="checkbox">
                                                                <em><?= $idioma["marcar_todas"]; ?></em>
                                                            </label>
                                                        <?php } ?>
                                                        <?
                                                        foreach ($funcionalidade["acoes"] as $acaoid => $acao) {
                                                            $permissaoid = $funcionalidadeid . "|" . $acaoid;
                                                            ?>
                                                            <label class="checkbox">
                                                                <input name="permissoes[<?= $permissaoid; ?>]" value="1"
                                                                       type="checkbox" <? if ($linha["permissoes"][$permissaoid]) {
                                                                    echo ' checked="checked"';
                                                                } ?>>
                                                                <?php if (empty($acao)) echo "<spam style='color:#FF0000'>Erro: Sem idioma.</span>"; else echo $acao; ?>
                                                            </label>
                                                        <? } ?>
                                                    </div>
                                                </div>
                                            <? } ?>
                                        </div>
                                    <? } ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <input type="submit" class="btn btn-primary" value="<?= $idioma["btn_salvar"]; ?>">&nbsp;
                            <input type="reset" class="btn"
                                   onclick="MM_goToURL('parent','/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>');"
                                   value="<?= $idioma["btn_cancelar"]; ?>"/>
                        </div>
                    </form>
                </div>
            </div>
            <!--</div>-->
        </div>
    </div>
    <div class="span3">
        <div class='section section-small'>
            <div class='section-header'><h5><?php echo $idioma["quem_perfil"]; ?></h5></div>
            <div style="overflow:auto;max-height:500px;">
                <?php
                if ($url[4] == "editar") {
                    if (count($usuariosPerfil) > 0) {
                        foreach ($usuariosPerfil as $usuario) {
                            ?>
                            <div class='section-header'
                                 style="background-color:#FFFFFF;background-image:none;text-transform:uppercase;">
                                <?php echo $usuario["nome"]; ?>
                                <br/>
                                <small class="muted"><?php echo $usuario["email"]; ?></small>
                            </div>
                        <?php
                        }
                    } else {
                        ?>
                        <div class='section-header'
                             style="background-color:#FFFFFF;background-image:none;"><?php echo $idioma["nenhum_informacao"]; ?></div>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
        <?php incluirLib("sidebar_" . $url[1], $config); ?>
    </div>
</div>
<? incluirLib("rodape", $config, $usuario); ?>
<script src="/assets/plugins/redactor770/redactor/langs/pt_br.js"></script>
<script src="/assets/plugins/redactor770/redactor/redactor.js"></script>
<script type="text/javascript">
    var regras = new Array();
    <?php
    foreach($config["formulario"] as $fieldsetid => $fieldset) {
      foreach($fieldset["campos"] as $campoid => $campo) {
        if(is_array($campo["validacao"])){
          foreach($campo["validacao"] as $tipo => $mensagem) {
            if($campo["tipo"] == "file"){ ?>
    regras.push("<?php echo $tipo; ?>,<?php echo $campo["id"]; ?>,<?php echo $campo["extensoes"]; ?>,<?php echo $campo["tamanho"]; ?>,<?php echo $idioma[$mensagem]; ?>");
    <? } else { ?>
    regras.push("<?php echo $tipo; ?>,<?php echo $campo["id"]; ?>,<?php echo $idioma[$mensagem]; ?>");
    <?
    }
  }
}
}
}
?>
    jQuery(document).ready(function ($) {
        <?php
        foreach($config["formulario"] as $fieldsetid => $fieldset) {
          foreach($fieldset["campos"] as $campoid => $campo) {
            if($campo["mascara"]){ ?>
        <?php if($campo["mascara"] == "99/99/9999") { ?>
        $("#<?= $campo["id"]; ?>").mask("<?= $campo["mascara"]; ?>");
        $('#<?= $campo["id"]; ?>').change(function () {
            if ($('#<?= $campo["id"]; ?>').val() != '') {
                valordata = $("#<?= $campo["id"]; ?>").val();
                date = valordata;
                ardt = new Array;
                ExpReg = new RegExp("(0[1-9]|[12][0-9]|3[01])/(0[1-9]|1[012])/[12][0-9]{3}");
                ardt = date.split("/");
                erro = false;
                if (date.search(ExpReg) == -1) {
                    erro = true;
                }
                else if (((ardt[1] == 4) || (ardt[1] == 6) || (ardt[1] == 9) || (ardt[1] == 11)) && (ardt[0] > 30))
                    erro = true;
                else if (ardt[1] == 2) {
                    if ((ardt[0] > 28) && ((ardt[2] % 4) != 0))
                        erro = true;
                    if ((ardt[0] > 29) && ((ardt[2] % 4) == 0))
                        erro = true;
                }
                if (erro) {
                    alert("\"" + valordata + "\" não é uma data válida!!!");
                    $('#<?= $campo["id"]; ?>').focus();
                    $("#<?= $campo["id"]; ?>").val('');
                    return false;
                }
                return true;
            }
        });
        <?php } elseif($campo["mascara"] == "(99) 9999-9999" || $campo["mascara"] == "(99) 9999-9999?9") { ?>
        $('#<?= $campo["id"]; ?>').focusout(function () {
            var phone, element;
            element = $(this);
            element.unmask();
            phone = element.val().replace(/\D/g, '');
            if (phone.length > 10) {
                element.mask("(99) 99999-999?9");
            } else {
                element.mask("(99) 9999-9999?9");
            }
        }).trigger('focusout');
        <?php } else { ?>
        $("#<?= $campo["id"]; ?>").mask("<?= $campo["mascara"]; ?>");
        <?php } ?>
        <?
        }
        if($campo["datepicker"]) {
        ?>
        $("#<?= $campo["id"]; ?>").datepicker($.datepicker.regional["pt-BR"]);
        <?
        }
        if($campo["numerico"]){
        ?>
        $("#<?= $campo["id"]; ?>").keypress(isNumber);
        $("#<?= $campo["id"]; ?>").blur(isNumberCopy);
        <?
        }
        if($campo["decimal"]){
        ?>
        $("#<?= $campo["id"]; ?>").maskMoney({symbol: "R$", decimal: ",", thousands: "."});
        <?
        }
        if($campo["editor"]){
        ?>
        $("#<?= $campo["id"]; ?>").height(300);
        $("#<?= $campo["id"]; ?>").redactor({lang: 'pt_br' });
        <?
        }
      }
    }
    ?>
        $('#tabPerfil a').click(function (e) {
            e.preventDefault();
            $(this).tab('show');
        })
    });

    function selecionaTodos(id) {
        var div = document.getElementById(id);
        var inputs = div.getElementsByTagName("input");
        var todos = document.getElementById("marcar_todas_" + id).checked;
        var marcar = false;
        if (todos) {
            marcar = true;
        }
        for (i = 0; i < inputs.length; i++) {
            inputs[i].checked = marcar;
        }
    }
</script>
</div>
</body>
</html>
