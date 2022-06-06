<div class="row-fluid m-box">
    <div class="span12">
        <div class="row-fluid">
            <div class="span12">
                <i class="closed-x" data-dismiss="modal"> <strong><?php echo $idioma['fechar']; ?></strong></i>
                <h1><?php echo $idioma['criar_topico']; ?></h1>
                <form id="formTopico" name="formTopico" method="post" enctype="multipart/form-data" onsubmit="return validateFields(this, regras)">
                    <input name="acao" id="acao" type="hidden" value="salvar_topico" />
                    <input name="idforum" id="idforum" type="hidden" value="<?php echo $url[6]; ?>" />
                    <div class="span12 box-gray extra-align no-margin" id="divScroll">
                        <label for="nome"><strong><?php echo $idioma['titulo_formulario']; ?></strong></label>
                        <input type="text" id="nome" name="nome" placeholder="<?php echo $idioma['informe_titulo']; ?>" class="span12" maxlength="100" />
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
    regras.push("required,nome,<?php echo $idioma['titulo_vazio']; ?>");
    regras.push("required,mensagem,<?php echo $idioma['mensagem_vazio']; ?>");
    regras.push("formato_arquivo,arquivo,jpg|jpeg|gif|png|bmp|zip|rar|tar|gz|doc|docx|xls|xlsx|ppt|pptx|pps|ppsx|txt|pdf,'',<?php echo $idioma['formato_arquivo']; ?>");
</script> 