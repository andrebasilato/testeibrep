<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php incluirLib("head", $config, $usuario); ?>
    <style type="text/css">
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
            line-height: 30px;
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
            height: 150px;
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
        <? if ($url[3] == "cadastrar") { ?>
            <li class="active"><?= $idioma["nav_formulario"]; ?></li>
        <? } else { ?>
            <li class="active"><?php echo $linha["nome"]; ?></li>
        <? } ?>
        <span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
    </ul>
</section>
<div class="row-fluid">
<div class="span12">
<div class="box-conteudo">
<div class="pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"><i
            class="icon-share-alt"></i> <?= $idioma["btn_sair"]; ?></a></div>
<?php if ($url[3] != "cadastrar") { ?><h2 class="tituloEdicao"><?php echo $linha["nome"]; ?></h2><?php } ?>
<div class="tabbable tabs-left">
<?php if ($url[3] != "cadastrar") {
    incluirTela("inc_menu_edicao", $config, $linha);
} ?>
<div class="tab-content">
<div class="tab-pane active" id="tab_editar">
<h2 class="tituloOpcao"><?php if ($url[3] == "cadastrar") {
        echo $idioma["titulo_opcao_cadastar"];
    } else if ($url[5] == "quitar") {
        echo $idioma["titulo_opcao_quitar"];
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

<form method="post" onsubmit="return validateFields(this, regras)"
      enctype="multipart/form-data" class="form-horizontal">
    <? if ($url[4] == "editar") { ?>
        <input name="acao" type="hidden" value="salvar"/>
        <?php echo '<input type="hidden" name="' . $config["banco"]["primaria"] . '" id="' . $config["banco"]["primaria"] . '" value="' . $linha[$config["banco"]["primaria"]] . '" />';
        foreach ($config["banco"]["campos_unicos"] as $campoid => $campo) {
            ?>
            <input name="<?= $campo["campo_form"]; ?>_antigo" id="<?= $campo["campo_form"]; ?>_antigo" type="hidden"
                   value="<?= $linha[$campo["campo_banco"]]; ?>"/>
        <?
        }
        $linhaObj->GerarFormulario("formulario", $linha, $idioma);
    } else {
        ?> <input name="acao" type="hidden" value="salvar"/> <?php
        $linhaObj->GerarFormulario("formulario", $_POST, $idioma);
    }
    ?>
    <div class="form-actions">
        <input type="submit" class="btn btn-primary" value="<?= $idioma["btn_salvar"]; ?>">&nbsp;
        <input type="reset" class="btn"
               onclick="MM_goToURL('parent','/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>');"
               value="<?= $idioma["btn_cancelar"]; ?>"/>
    </div>
</form>
</div>
</div>
</div>
</div>
</div>
</div>
<? incluirLib("rodape", $config, $usuario); ?>
<script src="/assets/plugins/jquery-ui/ui/jquery.ui.core.js"></script>
<script src="/assets/plugins/jquery-ui/ui/jquery.ui.widget.js"></script>
<script src="/assets/plugins/jquery-ui/ui/jquery.ui.datepicker.js"></script>
<script src="/assets/plugins/jquery-ui/ui/i18n/jquery.ui.datepicker-pt-BR.js"></script>
<script src="/assets/plugins/password_force/password_strength_plugin.js"></script>
<link rel="stylesheet" href="/assets/plugins/password_force/style.css" type="text/css" media="screen" charset="utf-8"/>
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
<? }
}
}
}
}
?>

jQuery(document).ready(function ($) {
    <?
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
    <? }
    if($campo["datepicker"]) { ?>
    $("#<?= $campo["id"]; ?>").datepicker($.datepicker.regional["pt-BR"]);
    <? }
    if($campo["numerico"]) { ?>
    $("#<?= $campo["id"]; ?>").keypress(isNumber);
    $("#<?= $campo["id"]; ?>").blur(isNumberCopy);
    <? }
    if($campo["decimal"]) { ?>
    $("#<?= $campo["id"]; ?>").maskMoney({symbol: "R$", decimal: ",", thousands: "."});
    <? }
    if($campo["json"]){
        if (!$linha[$campo["valor"]] && $_POST[$campo["valor"]]) {
            $linha[$campo["valor"]] = $_POST[$campo["valor"]];
        }

        if (!$linha[$campo["json_idpai"]] && $_POST[$campo["json_idpai"]]) {
            $linha[$campo["json_idpai"]] = $_POST[$campo["json_idpai"]];
        }
    ?>
    $('#<?=$campo["json_idpai"];?>').change(function () {
        if ($(this).val()) {
            $.getJSON('<?=$campo["json_url"];?>', {
            <?=$campo["json_idpai"];?>:
            $(this).val(), ajax
        :
            'true'
        }
        ,
        function (json) {
            var options = '<option value="">– <?=$idioma[$campo["json_input_vazio"]]; ?> –</option>';
            for (var i = 0; i < json.length; i++) {
                var selected = '';
                if (json[i].<?=$campo["valor"];?> == <?=intval($linha[$campo["valor"]]);?>)
                    var selected = 'selected';
                options += '<option value="' + json[i].<?=$campo["valor"];?> + '" ' + selected + '>' + json[i].<?=$campo["json_campo_exibir"];?> + '</option>';
            }
            $('#<?=$campo["id"];?>').html(options);
        }

        )
        ;
    }
    else
    {
        $('#<?=$campo["id"];?>').html('<option value="">– <?=$idioma[$campo["json_input_pai_vazio"]]; ?> –</option>');
    }
});

$.getJSON('<?=$campo["json_url"];?><?=$linha[$campo["json_idpai"]];?>', {
<?=$campo["json_idpai"];?>:
'<?=$linha[$campo["json_idpai"]];?>', ajax
:
'true'
},
function (json) {
    var options = '<option value="">- <?=$idioma[$campo["json_input_vazio"]]; ?> -</option>';
    if (null != json) {
        for (var i = 0; i < json.length; i++) {
            var selected = '';
            if (json[i].<?=$campo["valor"];?> == <?=intval($linha[$campo["valor"]]);?>)
                var selected = 'selected';
            options += '<option value="' + json[i].<?=$campo["valor"];?> + '" ' + selected + '>' + json[i].<?=$campo["json_campo_exibir"];?> + '</option>';
        }
    }

    $('#<?=$campo["id"];?>').html(options);
}
)
;
<? }
if ($campo["botao_hide"]) {
    if ($campo['tipo'] == 'select') { ?>
var aux_d = $('#<?= $campo["id"]; ?>').attr('value');
var idCampo = "<?= $campo["id"]; ?>";
if (idCampo == 'form_tipo') {
    if (aux_d == 'despesa') {
        $('#<?= $campo["id"]; ?> option[value="despesa"]').attr('selected', 'selected');
        $('#div_<?= $campo["iddiv2"]; ?>').show();
        $('#div_<?= $campo["iddiv3"]; ?>').show();
    } else {
        if (aux_d == 'receita') {
            $('#<?= $campo["id"]; ?> option[value="receita"]').attr('selected', 'selected');
            $('#div_<?= $campo["iddiv"]; ?>').show();
        }
    }
}
<? }
}
}
}
?>
<? if ($url[3] <> "cadastrar") { ?>

$.getJSON('/gestor/financeiro/previsoes_gastos/cadastrar/ajax_categorias/', {
		'idsindicato':
		'<?=$linha['idsindicato'];?>', ajax
		:
		'true'
		},
		function (json) {
			var options = '<option value="">- Selecione uma Categoria -</option>';
			if (null != json) {
				for (var i = 0; i < json.length; i++) {
					var selected = '';
					if (json[i].idcategoria == <?=intval($linha['idcategoria']);?>)
						var selected = 'selected';
					options += '<option value="' + json[i].idcategoria + '" ' + selected + '>' + json[i].nome + '</option>';
				}
			}
		
			$('#idcategoria').html(options);
		}
);

$.getJSON('/gestor/financeiro/previsoes_gastos/cadastrar/ajax_subcategorias/', {
			'idcategoria':<?=$linha['idcategoria'];?>, 'idsindicato' : <?=$linha['idsindicato'];?>, ajax:'true'
			},
			function (json) {
				var options = '<option value="">– Selecione uma Subcategoria –</option>';
				for (var i = 0; i < json.length; i++) {
					var selected = '';
					if (json[i].idsubcategoria == <?=intval($linha['idsubcategoria']);?>)
						var selected = 'selected';
					options += '<option value="' + json[i].idsubcategoria + '" ' + selected + '>' + json[i].nome + '</option>';
				}
				$('#idsubcategoria').html(options);
			}
	
);
<? } ?>

<? if ($_POST['idsindicato']) { ?>

$.getJSON('/gestor/financeiro/previsoes_gastos/cadastrar/ajax_categorias/', {
		'idsindicato':
		'<?=$_POST['idsindicato'];?>', ajax
		:
		'true'
		},
		function (json) {
			var options = '<option value="">- Selecione uma Categoria -</option>';
			if (null != json) {
				for (var i = 0; i < json.length; i++) {
					var selected = '';
					if (json[i].idcategoria == <?=intval($_POST['idcategoria']);?>)
						var selected = 'selected';
					options += '<option value="' + json[i].idcategoria + '" ' + selected + '>' + json[i].nome + '</option>';
				}
			}
		
			$('#idcategoria').html(options);
		}
);

$.getJSON('/gestor/financeiro/previsoes_gastos/cadastrar/ajax_subcategorias/', {
			'idcategoria':<?=$_POST['idcategoria'];?>, 'idsindicato' : <?=$_POST['idsindicato'];?>, ajax:'true'
			},
			function (json) {
				var options = '<option value="">– Selecione uma Subcategoria –</option>';
				for (var i = 0; i < json.length; i++) {
					var selected = '';
					if (json[i].idsubcategoria == <?=intval($_POST['idsubcategoria']);?>)
						var selected = 'selected';
					options += '<option value="' + json[i].idsubcategoria + '" ' + selected + '>' + json[i].nome + '</option>';
				}
				$('#idsubcategoria').html(options);
			}
	
);
<? }  ?>

$('#idcategoria').change(function () {
        if ($(this).val()) {
			$.getJSON('/gestor/financeiro/previsoes_gastos/cadastrar/ajax_subcategorias/', {
			'idcategoria':$(this).val(), 'idsindicato' : $("#idsindicato").val(), ajax:'true'
			},
			function (json) {
				var options = '<option value="">– Selecione uma Subcategoria –</option>';
				for (var i = 0; i < json.length; i++) {
					var selected = '';
					options += '<option value="' + json[i].idsubcategoria + '" ' + selected + '>' + json[i].nome + '</option>';
				}
				$('#idsubcategoria').html(options);
			}
	
			)
			;
    }
    else
    {
        $('#idsubcategoria').html('<option value="">– Escolha uma Categoria –</option>');
    }
});



})
;

//FUNÇÃO PARA FORMATAR VALOR EM JAVASCRIPT
function formata_valor(id) {
    var val = document.getElementById(id).value;

    var c = 0;
    part = new Array();
    array = new Array();

    ar = new Array();
    ar = val.split(".");
    val = ar[0];
    var t = val.length;

    for (i = t - 1; i >= 0; i--) {
        part[c] = val[i];
        c++;
        if (c == 3) {
            c = 0;
            array[array.length] = part.reverse().join("");
            part = new Array();
        }
    }

    if (part.length > 0)
        array[array.length] = part.reverse().join("");

    if (!ar[1])
        document.getElementById(id).value = array.reverse().join(".") + ',00';
    else {
        if (ar[1].length == 1)
            ar[1] += '0';
        document.getElementById(id).value = array.reverse().join(".") + ',' + ar[1];
    }
}

$(".class_data").datepicker($.datepicker.regional["pt-BR"]);
$(".class_data").mask("99/99/9999");
$(".class_decimal").maskMoney({decimal: ",", thousands: ".", precision: 2, allowZero: true});

</script>
</div>
</body>
</html>