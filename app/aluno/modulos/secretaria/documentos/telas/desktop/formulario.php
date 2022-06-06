<div class="row-fluid m-box">
    <div class="span12">
        <div class="row-fluid">
            <div class="span12">
                <i class="closed-x" data-dismiss="modal"> <strong><?= $idioma['fechar']; ?></strong></i>
                <h1><?= $idioma['adicionar']; ?></h1>
                <form id="formSolicitacao" name="formSolicitacao" method="post" enctype="multipart/form-data" onsubmit="return validateFields(this, regras)">
                    <input name="acao" type="hidden" value="adicionar_documento">
                    <div class="span12 box-gray extra-align no-margin" id="divScroll">
                        <label for="idmatricula"><strong><?= $idioma['matricula']; ?></strong></label>
                        <select id="idmatricula" name="idmatricula" class="span8">
                            <option value=""><?= $idioma['selecione_matricula']; ?></option>
                            <?php 
							foreach ($matriculas as $matricula) {
                                $matricula['situacao'] = $matriculaObj->RetornarSituacao($matricula['idsituacao']);

                                if ($matricula["situacao"]["visualizacoes"][7]) {
    								?>
                                        <option value="<?= $matricula['idmatricula']; ?>"><?= $matricula['idmatricula'].' - '.$matricula['curso']; ?></option>
    								<?php
                                }
							}
                            ?>
                        </select>
                        <label for="idtipo">
                            <strong><?= $idioma['tipo']; ?></strong>
                        </label>
                        <select name="idtipo" id="idtipo" style="width:300px;">
                            <option value=""><?= $idioma['selecione_tipo_documento']; ?></option>
                        </select>
                        <label for="documento">
                            <strong><?= $idioma['documento']; ?></strong>
                        </label>
                        <input type="file" id="documento" name="documento" />
                    </div>
                    <input type="submit" class="btn btn-azul r-align" value="<?= $idioma['enviar']; ?>" />
                </form>
            </div>
        </div>
    </div>
</div>
<script type="application/javascript">
var regras = new Array();
regras.push("required,idmatricula,<?= $idioma['matricula_vazio']; ?>");
regras.push("required,idtipo,<?= $idioma['idtipo_vazio']; ?>");
regras.push("required,documento,<?= $idioma['documento_vazio']; ?>");
regras.push("formato_arquivo,documento,jpg|jpeg|gif|png|bmp|pdf,2048,<?= $idioma['documento_invalido']; ?>");
regras.push("tamanho_arquivo,documento,jpg|jpeg|gif|png|bmp|pdf,2048,<?= $idioma['documento_tamanho_invalido']; ?>");

$('#idmatricula').change(function(){
    if($(this).val()){
        $.getJSON('<?= '/'.$url[0].'/'.$url[1].'/'.$url[2].'/json/tipos'; ?>',{idmatricula: $(this).val()}, function(json){
            var total = json.length;
            var options = '<option value=""><?= $idioma['selecione_tipo_documento']; ?></option>';
            for (var i = 0; i < total; i++) {
                documentoObrigatorio = '';
                //if (json[i].todos_cursos_obrigatorio == "S" || json[i].todas_instituicoes_obrigatorio == "S" || json[i].instituicao_obrigatorio == "S" || json[i].curso_obrigatorio == "S") {
                    //documentoObrigatorio = ' *<?= $idioma["obrigatorio"]; ?>*';
                //}
                options += '<option value="' + json[i].idtipo + '" >' + json[i].nome + documentoObrigatorio + '</option>';
            }
            $('#idtipo').html(options);
        });
    } else {
        $('#idtipo').html('<option value=""><?= $idioma['selecione_tipo_documento']; ?></option>');
    }
});
</script>