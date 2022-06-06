<link rel="stylesheet" href="/assets/plugins/fcbkcomplete/style.css" type="text/css" media="screen" charset="utf-8" />
<div class="row-fluid m-box">
    <div class="span12">
        <div class="row-fluid">
            <div class="span12">
                <i class="closed-x" data-dismiss="modal"> <strong><?php echo $idioma['fechar']; ?></strong></i>
                <h1><?php echo $idioma['titulo']; ?></h1>
                <form id="form_iniciar_conversa" name="form_iniciar_conversa" method="post" onsubmit="return validateFields(this, regras);">
                    <input name="acao" id="acao" type="hidden" value="adicionar_pessoa" />
                    <div class="span12 box-gray extra-align no-margin" id="divScroll">
                        <label for="participantes"><strong><?php echo $idioma['pessoas']; ?></strong></label>
                        <select id="participantes" name="participantes" class="span12"></select>
                    </div>
                    <input type="submit" class="btn btn-azul r-align" value="<?php echo $idioma['adicionar']; ?>" />
                </form>
            </div>
        </div>
    </div>
</div>
<script type="application/javascript">
var regras = new Array();
regras.push("required,participantes,<?php echo $idioma["participantes_vazio"]; ?>");
$( document ).ready(function() {
	$("#participantes").fcbkcomplete({
		json_url: "/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $url[3]; ?>/<?php echo $url[4]; ?>/json/participantes_mensagem/",
		addontab: true,
		height: 10,
		width: "100%",
		maxshownitems: 10,
		cache: true,
		maxitems: 20,
		filter_selected: true,
		firstselected: true,
		complete_text: "<?php echo $idioma['participante_select']; ?>",
		addoncomma: true
	});
});
</script> 