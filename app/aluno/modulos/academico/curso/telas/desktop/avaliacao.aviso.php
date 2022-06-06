<div class="row-fluid m-box">
    <div class="span12">
        <div class="row-fluid">
            <div class="span12">
                <i class="closed-x" data-dismiss="modal"> <strong><?= $idioma['fechar']; ?></strong></i>
                <h1><?= $idioma['fazer_avaliacao']; ?></h1>
                <div style="font-size: 13px; color: #000;">
                    Olá <?= $_SESSION['cliente_nome']; ?><br>
                    <br>
                    A seguir você efetuará sua Avaliação.
                    <br>
                    <br>
                    Desejamos boa sorte!<br>
                    <br>
                    Se você estiver preparado e deseja realizar a Avaliação agora clique SIM.<br>
                    <br>
                    <br>
                <a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $matricula['idmatricula']; ?>/<?php echo $url[4]; ?>/<?php echo $url[5]; ?>/<?php echo $url[6]; ?>/fazer" style="margin-left: 0;">
                    <div class="btn btn-verde">Sim</div>
                </a>
                <a data-dismiss="modal">
                    <div class="btn btn-vermelho">Não</div>
                </a>
                </div>
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