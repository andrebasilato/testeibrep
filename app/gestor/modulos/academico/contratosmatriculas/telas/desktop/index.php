<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usuario); ?>
<link rel="stylesheet" href="/assets/plugins/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
<style type="text/css">
    .botao {
        height:100px;
        margin-top: 15px;
        padding-bottom:0px;
        float:left;
        padding-top:40px;
        height:58px;
        text-transform:uppercase;
    }
</style>
</head>
<body>
<?php incluirLib("topo",$config,$usuario); ?>
<div id="validarcontrato" style="display:none;">
  <section id="global">
    <div class="page-header">
      <h1>Validar contrato</h1>
    </div>
    <ul class="breadcrumb">
      <li>Contrato:</li>
      <li class="active"><strong id="contrato_validar_nome"></strong></li>
    </ul>
    <form action="" method="post" id="form_contrato_validar">
      <input name="acao" type="hidden" value="validar_contrato" />
      <input name="idmatricula" id="idmatricula_validar" type="hidden" value="" />
      <input name="idmatricula_contrato" id="idmatricula_contrato_validar" type="hidden" value="" />
      <input name="situacao" id="situacao_validar" type="hidden" value="" />
      <? /* =$idioma['explicativo_contrato']; */?>
      <br />
      <div class="row-fluid">
        <div class="span5 botao btn" id="contrato_validar_aprovar" onclick="contrato_validar_selecionarSituacao(2);">Validar</div>
        <div class="span5 botao btn" id="contrato_validar_desaprovar" onclick="contrato_validar_selecionarSituacao(1);">Não validar</div>
      </div>
      <br />
    </form>
  </section>
  <script type="text/javascript">
    function contrato_validar_selecionarSituacao(situacao) {
    if(situacao == 1){
      var confirma = confirm('Deseja realmente não validar o contrato?');
    } else if(situacao == 2) {
      var confirma = confirm('Deseja realmente validar o contrato?');
    }
    if(confirma) {
      document.getElementById('situacao_validar').value = situacao;
      document.getElementById('form_contrato_validar').submit();
    } else {
      return false;
    }
    }

    function validarContrato(idmatricula,id,nome,situacaoatual) {
      document.getElementById('idmatricula_validar').value = idmatricula;
    document.getElementById('idmatricula_contrato_validar').value = id;
    document.getElementById('contrato_validar_nome').innerHTML = nome;
    if(situacaoatual == 1){
      // Nao aprovado
      $("#contrato_validar_aprovar").removeClass("btn-success");
      $("#contrato_validar_desaprovar").addClass("btn-danger");
    } else if(situacaoatual == 2) {
      // Aprovado
      $("#contrato_validar_desaprovar").removeClass("btn-danger");
      $("#contrato_validar_aprovar").addClass("btn-success");
    } else {
      $("#contrato_validar_aprovar").removeClass("btn-success");
      $("#contrato_validar_desaprovar").removeClass("btn-danger");
    }
    return true;
    }
  </script>
</div>
<div id="cancelarcontrato" style="display:none;">
  <iframe id="iframe_cancelarcontrato" src="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/cancelarcontrato" width="400" height="290" frameborder="0"></iframe>
</div>
<div class="container-fluid">
  <section id="global">
	<div class="page-header">
    	<h1><?= $idioma["pagina_titulo"]; ?> &nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1>
  	</div>
  	<ul class="breadcrumb">
    	<li><a href="/<?= $url[0]; ?>"><?= $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
    	<li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>"><?= $idioma["nav_modulo"]; ?></a> <span class="divider">/</span></li>
    	<li class="active"><?= $idioma["pagina_titulo"]; ?></li>
        <? if($_GET["q"]) { ?><li><span class="divider">/</span> <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"><?= $idioma["nav_resetarbusca"]; ?></a></li><? } ?>
    	<span class="pull-right" style="padding-top:3px; color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
  	</ul>
  </section>
  <div class="row-fluid">
  	<div class="span12">
        <div class="box-conteudo">
       			<? if($_POST["msg"]) { ?>
      				<div class="alert alert-success fade in"> 
                        <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                        <strong><?= $idioma[$_POST["msg"]]; ?></strong>
      				</div>
      			<? } ?>
                <? if($mensagem["erro"]) { ?>
                  <div class="alert alert-error">
                    <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                    <?= $idioma[$mensagem["erro"]]; ?>
                  </div> 
                  <script>alert('<?= $idioma[$mensagem["erro"]]; ?>');</script>
				<? } ?>
        		<div id="listagem_informacoes"> 		  
		  			<? printf($idioma["informacoes"],$linhaObj->Get("total")); ?>
                    <br />
          			<? printf($idioma["paginas"],$linhaObj->Get("pagina"),$linhaObj->Get("paginas")); ?>
                    <br />
                    <?php printf($idioma["situacoes_nao_exibidas"],$idSituacaoCancelada["nome"],$idSituacaoFim["nome"],$idSituacaoInativa["nome"]); ?>
                </div>
                <?php $linhaObj->GerarTabela($dadosArray,$_GET["q"],$idioma); ?>
                <div id="listagem_form_busca">
                    <div class="input">
                        <div class="inline-inputs"> <?= $idioma["registros"]; ?>
                            <form action="" method="get" id="formQtd">
                                <? if($_GET["buscarpor"] && $_GET["buscarem"]) { ?>
                                    <input name="buscarpor" type="hidden" id="buscarporQtd" value="<?= $_GET["buscarpor"]; ?>">
                                    <input name="buscarem" type="hidden" id="buscaremQtd" value="<?= $_GET["buscarem"]; ?>">
                                <? } ?>
                                <? if(is_array($_GET["q"])){
                                    foreach($_GET["q"] as $ind => $valor){
                                ?>
                                    <input id="q[<?=$ind?>]" type="hidden" value="<?=$valor;?>" name="q[<?=$ind?>]" />
                                <? } } ?>
                                <? if($_GET["cmp"]){?>
                                    <input id="cmp" type="hidden" value="<?=$_GET["cmp"];?>" name="cmp" />
                                <? } ?>
                                <? if($_GET["ord"]){?>
                                    <input id="ord" type="hidden" value="<?=$_GET["ord"];?>" name="ord" />
                                <? } ?>
                                <input name="qtd" type="text" class="span1" id="qtd" maxlength="4" value="<?= $linhaObj->Get("limite"); ?>" />
                                <a href="javascript:document.getElementById('formQtd').submit();" class="btn small"><?= $idioma["exibir"]; ?></a> 
                            </form>
                        </div>
                    </div>
                </div>
                <? 
				if($linhaObj->Get("paginas") > 1) { 
					$linhaObj->Set("ordem","desc");
					$linhaObj->Set("ordem_campo","ma.idmatricula");
				?>
                    <div class="pagination">
                        <ul><?= $linhaObj->GerarPaginacao($idioma); ?></ul>
                    </div>
                <? } ?>
                <div class="clearfix"></div>                                  
        </div>
    </div>
  </div>
  <? incluirLib("rodape",$config,$usuario); ?>
<script type="text/javascript" src="/assets/plugins/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>  
<script>
   $(document).ready(function(){
		$('.fancybox').fancybox({
			type       : 'image',
			//prevEffect : 'none',
			//nextEffect : 'none',
			//closeBtn   : false,
			//helpers : {
			//  title : { type : 'inside' },
			//  buttons : {}
			//}
		});
    });
</script> 
<script>
function attContrato(idmatricula, id) {
  document.getElementById('iframe_cancelarcontrato').src = '/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/cancelarcontrato?m=' + idmatricula + '&' + 'c=' + id;
}
</script>

</div>
</body>
</html>