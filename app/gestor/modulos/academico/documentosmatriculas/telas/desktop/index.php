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

</div>
</body>
</html>