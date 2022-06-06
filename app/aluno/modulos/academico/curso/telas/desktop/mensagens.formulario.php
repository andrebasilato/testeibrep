<link rel="stylesheet" href="/assets/plugins/fcbkcomplete/style.css" type="text/css" media="screen" charset="utf-8" />
<div class="row-fluid m-box">
    <div class="span12">
        <div class="row-fluid">
            <div class="span12">
                <i class="closed-x" data-dismiss="modal"> <strong><?php echo $idioma['fechar']; ?></strong></i>
                <h1><?php echo $idioma['titulo']; ?></h1>
                <form id="form_iniciar_conversa" name="form_iniciar_conversa" method="post" enctype="multipart/form-data" onsubmit="return validateFields(this, regras);" target="_blank">
                    <input name="acao" id="acao" type="hidden" value="iniciar_chat" />
                    <div class="span12 box-gray extra-align no-margin" id="divScroll">
                        <label for="participantes"><strong><?php echo $idioma['pessoas']; ?></strong></label>
                        <select id="participantes" name="participantes" class="span12"></select>
                    </div>
                    <textarea id="mensagem" name="mensagem" placeholder="<?php echo $idioma['informe_mensagem']; ?>" class="box-textarea"></textarea>
                    <input type="file" class="upload_put" id="arquivo" name="arquivo">
                    <input type="submit" class="btn btn-azul r-align" value="<?php echo $idioma['enviar']; ?>" />
                </form>
            </div>
        </div>
    </div>
</div>
<script type="application/javascript">
var regras = new Array();
regras.push("required,participantes,<?php echo $idioma["participantes_vazio"]; ?>");
regras.push("required,mensagem,<?php echo $idioma["mensagem_vazio"]; ?>");
regras.push("formato_arquivo,arquivo,jpg|jpeg|gif|png|bmp|pdf|doc|docx,,<?php echo $idioma["arquivo_nao_permitido"]; ?>)");
$( document ).ready(function() {
	$("#participantes").fcbkcomplete({
		json_url: "/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $matricula['idmatricula']; ?>/<?php echo $url[4]; ?>/json/participantes_mensagem/",
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