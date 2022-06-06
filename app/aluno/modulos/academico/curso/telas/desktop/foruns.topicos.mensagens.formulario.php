<div class="row-fluid m-box">
    <div class="span12">
        <div class="row-fluid">
            <div class="span12">
                <i class="closed-x" data-dismiss="modal"> <strong><?php echo $idioma['fechar']; ?></strong></i>
                <h1><?php echo $idioma['responder_topico']; ?></h1>
                <form id="formResponder" name="formResponder" method="post" enctype="multipart/form-data" onsubmit="return validateFields(this, regras)">
                    <input name="acao" id="acao" type="hidden" value="responder_topico" />
                    <?php if($url[10] && $url[11] == "responder") { ?>
                        <input name="idmensagem_associada" id="idmensagem_associada" type="hidden" value="<?php echo $url[10]; ?>" />
                    <?php } ?>
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
    regras.push("required,mensagem,<?php echo $idioma['mensagem_vazio']; ?>");
    regras.push("formato_arquivo,arquivo,jpg|jpeg|gif|png|bmp|zip|rar|tar|gz|doc|docx|xls|xlsx|ppt|pptx|pps|ppsx|txt|pdf,'',<?php echo $idioma['formato_arquivo']; ?>");
</script> 