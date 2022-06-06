<div class="row-fluid m-box">
    <div class="span12">
        <div class="row-fluid">
            <div class="span12">
                <i class="closed-x" data-dismiss="modal"> <strong><?php echo $idioma['fechar']; ?></strong></i>
                <h1><?php echo $idioma['titulo']; ?></h1>
                <form action="<?php echo '/'.$url[0].'/'.$url[1].'/'.$url[2].'/'.$matricula['idmatricula'].'/'.$url[4].'/mensagens';?>" id="form_iniciar_conversa" name="form_iniciar_conversa" method="post" enctype="multipart/form-data" onsubmit="return validateFields(this, regras);" target="_blank">
                    <input name="acao" id="acao" type="hidden" value="iniciar_chat" />
                    <input name="participantes[]" id="participantes" type="hidden" value="<?php echo 'ALUNO|'.$url[6]; ?>" />
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
	regras.push("required,participantes,<?php echo $idioma['participantes_vazio']; ?>");
	regras.push("required,mensagem,<?php echo $idioma['mensagem_vazio']; ?>");
	regras.push("formato_arquivo,arquivo,jpg|jpeg|gif|png|bmp|pdf|doc|docx,,<?php echo $idioma['arquivo_nao_permitido']; ?>)");
</script> 