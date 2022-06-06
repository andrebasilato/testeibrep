<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usuario); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style type="text/css">
  body {
	background-color: #FFF !important;
	background-image:none;
	padding-top:0px !important;
  }
  
  body {
	min-width: 300px;
  }
  .container-fluid {
	min-width: 300px;
  }
</style>
</head>
<body>   
<script type="text/javascript">
    function selecionarSituacaoReprovada(div1_d, id_select) {
        document.getElementById(div1_d).style.display = "block";
        document.getElementById(id_select).focus();
    }
  
    function selecionarSituacao(situacao) {
    	if(situacao == "reprovado"){
            var confirma = confirm("<?= $idioma["confirma_desaprovar_documento"]; ?>");
    	} else if(situacao == "aprovado") {
            var confirma = confirm("<?= $idioma["confirma_aprovar_documento"]; ?>");
    	}

    	if(confirma) {
            document.getElementById("situacao").value = situacao;
            document.getElementById("form_aprovar_documento").submit();
    	} else {
            return false;	
    	}
    }
</script>
<div id="documento_validar" > 
  <section id="global">
    <div class="page-header">
      <h1><?= $idioma["valida_documento"]; ?></h1>
    </div>
    <ul class="breadcrumb">
      <li><?= $idioma["label_documento"]; ?></li>
      <li class="active"><strong id="documento_nome"><?= $documento["arquivo_nome"]; ?></strong></li>
    </ul>
        <form action="" method="post" id="form_aprovar_documento" target="_parent">
            <input name="acao" type="hidden" value="aprovar_documento" />
            <input name="iddocumento" id="iddocumento" type="hidden" value="<?= $url["4"]; ?>" />
            <input name="idmatricula" id="idmatricula" type="hidden" value="<?= $url["5"]; ?>" />
            <input name="situacao" id="situacao" type="hidden" value="" />
            <?= $idioma["explicativo_documento"]; ?> 
            <br />
            <div class="row-fluid">
                <div class="span5 botao btn <?php if($documento["situacao"] == "aprovado") { ?>btn-success<?php } ?>" id="documento_aprovar" onclick="selecionarSituacao('aprovado');" style="padding-top:30px;">
                    <?= $idioma["documento_aprovado"]; ?>
                </div>
                <div class="span5 botao btn <?php if($documento["situacao"] == "reprovado") { ?>btn-danger<?php } ?>" id="documento_desaprovar" onclick="selecionarSituacaoReprovada('div_motivo_reprovacao','descricao_motivo_reprovacao');"  style="padding-top:30px;">
                    <?= $idioma["documento_reprovado"]; ?>
                </div>
            </div>
            <div class="row-fluid" id="div_motivo_reprovacao" style="display:none;">
                <br />
                <div class="row-fluid">
                    <div class="span11">
                        <?= $idioma["descricao_motivo"]; ?>
                        <br />
                        <textarea id="descricao_motivo_reprovacao" name="descricao_motivo_reprovacao" rows="4" style="width:100%;"></textarea>
                    </div>
                </div>
                <br />
                <div class="row-fluid">
                    <input class="btn" type="button" value="<?= $idioma["btn_efeturar"]; ?>" onclick="selecionarSituacao('reprovado');">
                </div>
            </div>
        </form>     
    </section>
  </div>   
  <div style="display:none;"><img src="/assets/img/ajax_loader.png" width="64" height="64" /></div>
</body>
</html>