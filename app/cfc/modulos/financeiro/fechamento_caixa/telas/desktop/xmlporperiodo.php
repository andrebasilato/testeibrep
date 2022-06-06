<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <link rel="stylesheet" type="text/css" href="/assets/min/aplicacao.desktop.min.css">
    <style>
        .logo {
            display:block;
            margin-top:5px;
            margin-bottom:15px;
            margin-left: 30px;
            height:50px;
            width:135px;
            background:transparent url(<?php echo URL_LOGO_PEGUENA; ?>) 0 0 no-repeat;
            background-size: cover;
        }
    </style>
</head>
<body style="min-width:0px;padding-top:0px;background-image:none;">
<form class="well" method="get" action="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>"
      target="_blank">
    <label class="control-label" for="form_tipo_periodo"><?= $idioma["form_tipo_periodo"]; ?></label>
    <div class="controls">
        <select name="tipo_periodo" id="form_tipo_periodo" class="span3"
                onchange="verificaData(this, 'div_de', 'div_ate', 'periodo_inicio', 'periodo_final')">
            <option value="PER"><?= $idioma["periodo_PER"]; ?></option>
            <option value="HOJ"><?= $idioma["periodo_HOJ"]; ?></option>
            <option value="SET"><?= $idioma["periodo_SET"]; ?></option>
            <option value="MAT"><?= $idioma["periodo_MAT"]; ?></option>
            <option value="MPR"><?= $idioma["periodo_MPR"]; ?></option>
            <option value="MAN"><?= $idioma["periodo_MAN"]; ?></option>
        </select>
    </div>
    <div class="control-group" style="float:left; padding-right:25px;" id="div_de">
        <label class="control-label" for="periodo_inicio"><?= $idioma["form_de"]; ?></label>

        <div class="controls"><input class="span2" id="periodo_inicio" name="periodo_inicio" type="text" value=""/>
        </div>
    </div>
    <div class="control-group" id="div_ate">
        <label class="control-label" for="periodo_final"><?= $idioma["form_ate"]; ?></label>

        <div class="controls">
            <input class="span2" id="periodo_final" name="periodo_final" type="text" value=""/>
        </div>
    </div>
    <br/>
    <input type="submit" class="btn btn-primary" name="gerar" value="<?= $idioma["btn_gerar"]; ?>"/>
</form>
<script src="/assets/js/jquery.1.7.1.min.js"></script>
<script src="/assets/plugins/facebox/src/facebox.js"></script>
<script src="/assets/js/jquery.maskMoney.js"></script>
<script src="/assets/js/validation.js"></script>
<script src="/assets/js/jquery.maskedinput_1.3.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-transition.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-alert.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-dropdown.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-tab.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-tooltip.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-button.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-collapse.js"></script>
<script src="/assets/js/mousetrap.min.js"></script>
<script src="/assets/js/construtor.js"></script>
<script src="/assets/plugins/jquery-ui/ui/jquery.ui.core.js"></script>
<script src="/assets/plugins/jquery-ui/ui/jquery.ui.widget.js"></script>
<script src="/assets/plugins/jquery-ui/ui/jquery.ui.datepicker.js"></script>
<script src="/assets/plugins/jquery-ui/ui/i18n/jquery.ui.datepicker-pt-BR.js"></script>
<script type="text/javascript">
    $(function () {
        $("#periodo_inicio").datepicker({
            currentText: 'Now',
            dateFormat: 'dd/mm/yy',
            dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S'],
            dayNames: ['Domingo', 'Segunda-feira', 'Terca-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sabado'],
            monthNames: ['Janeiro', 'Fevereiro', 'Marco', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
            alignment: 'bottomLeft',
            buttonImageOnly: true,
            buttonImage: '/assets/img/calendar.png',
            showStatus: true
        });
        $("#periodo_final").datepicker({
            currentText: 'Now',
            dateFormat: 'dd/mm/yy',
            dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S'],
            dayNames: ['Domingo', 'Segunda-feira', 'Terca-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sabado'],
            monthNames: ['Janeiro', 'Fevereiro', 'Marco', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
            alignment: 'bottomLeft',
            buttonImageOnly: true,
            buttonImage: '/assets/img/calendar.png',
            showStatus: true
        });
    });

    function verificaData(obj, div_de, div_ate, periodo_inicio, periodo_final) {
        if (obj.value == 'PER') {
            document.getElementById(div_de).style.display = 'block';
            document.getElementById(div_ate).style.display = 'block';
        } else {
            document.getElementById(div_de).style.display = 'none';
            document.getElementById(div_ate).style.display = 'none';
            document.getElementById(periodo_inicio).value = '';
            document.getElementById(periodo_final).value = '';
        }
    }
</script>
</body>
</html>