<div class="row-fluid m-box">
    <div class="span12">
        <div class="row-fluid">
            <div class="span12">
                <i class="closed-x" data-dismiss="modal"> <strong><?php echo $idioma['fechar']; ?></strong></i>
                <h1><?php echo $idioma['solicitar']; ?></h1>
                <form id="formSolicitacao" name="formSolicitacao" method="post" onsubmit="return validateFields(this, regras)">
                    <input name="acao" type="hidden" value="salvar_solicitacao">
                    <div class="span12 box-gray extra-align no-margin" id="divScroll">
                        <label for="idmatricula"><strong><?php echo $idioma['matricula']; ?></strong></label>
                        <select id="idmatricula" name="idmatricula" class="span8">
                            <option value=""><?php echo $idioma['selecione_matricula']; ?></option>
                            <?php
                            foreach($matriculas as $matricula) {
                                $matriculaObj->id = $matricula["idmatricula"];?>
                                <option value="<?php echo $matricula['idmatricula']; ?>"><?php echo $matricula['idmatricula'].' - '.$matricula['curso']; ?></option>
                            <?php } ?>
                        </select>
                        <label for="iddeclaracao"><strong><?php echo $idioma['declaracao']; ?></strong></label>
                        <select id="iddeclaracao" name="iddeclaracao" class="span8">
                            <option value=""><?php echo $idioma['selecione_matricula']; ?></option>
                        </select>
                    </div>
                    <input type="submit" class="btn btn-azul r-align" value="<?php echo $idioma['enviar']; ?>" />
                </form>
            </div>
        </div>
    </div>
</div>
<script type="application/javascript">
    var regras = new Array();
    regras.push("required,idmatricula,<?php echo $idioma['matricula_vazio']; ?>");
    regras.push("required,iddeclaracao,<?php echo $idioma['declaracao_vazio']; ?>");

    $('#idmatricula').change(function(){
        if($(this).val()){
            $.getJSON('<?php echo '/'.$url[0].'/'.$url[1].'/'.$url[2].'/json/declaracoes'; ?>',
                {
                    idmatricula: $(this).val()
                }, function(json){
                    var total = json.length;
                    var options = '<option value=""><?php echo $idioma['selecione_declaracao']; ?></option>';
                    for (var i = 0; i < total; i++) {
                        options += '<option value="' + json[i].iddeclaracao + '" >' + json[i].nome + '</option>';
                    }
                    $('#iddeclaracao').html(options);
                });
        } else {
            $('#iddeclaracao').html('<option value=""><?php echo $idioma['selecione_matricula']; ?></option>');
        }
    });
</script>