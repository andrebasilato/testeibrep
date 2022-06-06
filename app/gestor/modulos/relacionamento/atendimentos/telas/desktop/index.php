<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usuario); ?>
</head>
<body>
<?php incluirLib("topo",$config,$usuario); ?>
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
    	<span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
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
        		<div id="listagem_informacoes"> 		  
		  			<? printf($idioma["informacoes"],$linhaObj->Get("total")); ?>
                    <br />
          			<? printf($idioma["paginas"],$linhaObj->Get("pagina"),$linhaObj->Get("paginas")); ?>
                    <span class="pull-right" style="padding-top:3px; color:#999">
                        <a class="btn btn-primary" href="#o_que_eu_posso_ver" rel="facebox"> <?= $idioma["btn_o_que_eu_vejo"]; ?> </a> 
						<a href="/<?= $url[0]; ?>/relacionamento/atendimentos/cadastrar" style="color:#FFF;" class="btn btn-primary"><i class="icon-plus icon-white"></i> <?= $idioma["btn_novoatendimento"]; ?> </a>
                    </span>
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
                <? if($linhaObj->Get("paginas") > 1) { ?>
                    <div class="pagination">
                        <ul><?= $linhaObj->GerarPaginacao($idioma); ?></ul>
                    </div>
                <? } ?>
                <div class="clearfix"></div>                                  
        </div>
    </div>
    
  </div>
  <? incluirLib("rodape",$config,$usuario); ?>
<script src="/assets/plugins/jquery-ui/ui/jquery.ui.core.js"></script>
<script src="/assets/plugins/jquery-ui/ui/jquery.ui.datepicker.js"></script>
<script src="/assets/plugins/jquery-ui/ui/i18n/jquery.ui.datepicker-pt-BR.js"></script>
<script language="javascript" type="text/javascript">
jQuery(document).ready(function($) {
  $("#qtd").keypress(isNumber);
  $("#qtd").blur(isNumberCopy);
  $("input[name='q[3|ate.proxima_acao]']").datepicker($.datepicker.regional["pt-BR"]);

  var todosOptions = $("select[name='q[1|ate.idsubassunto]']").html();
  
  $("select[name='q[1|ate.idassunto]']").change(function(){
	if($(this).val()){ 
	  $.getJSON('<?php echo "/".$url["0"]."/".$url["1"]."/".$url["2"]."/cadastrar/json/subassunto"; ?>',{idassunto: $(this).val(), ajax: 'true'}, function(json){
		var options = '<option value=""> </option>';
		for (var i = 0; i < json.subassunto.length; i++) {
		  options += '<option value="' + json.subassunto[i].idsubassunto + '" >' + json.assunto + ' - ' + json.subassunto[i].nome + '</option>';
		}	
		$("select[name='q[1|ate.idsubassunto]']").html(options);
	  }); 
	} else {
	  $("select[name='q[1|ate.idsubassunto]']").html(todosOptions);
	}
  });
  <?php if($_GET["q"]["1|ate.idassunto"]) { ?>
	$.getJSON('<?php echo "/".$url["0"]."/".$url["1"]."/".$url["2"]."/cadastrar/json/subassunto"; ?>',{idassunto: <?php echo intval($_GET["q"]["1|ate.idassunto"]); ?>, ajax: 'true'}, function(json){
	  var options = '<option value=""> </option>';
	  for (var i = 0; i < json.subassunto.length; i++) {
		var selected = '';
		if(json.subassunto[i].idsubassunto == <?php echo intval($_GET["q"]["1|ate.idsubassunto"]); ?>)
		  var selected = 'selected';
		options += '<option value="' + json.subassunto[i].idsubassunto + '" '+ selected +'>' + json.assunto + ' - ' + json.subassunto[i].nome + '</option>';
	  }	
	  $("select[name='q[1|ate.idsubassunto]']").html(options);
	});
  <?php } ?>  
});
</script>

</div>

<div id="o_que_eu_posso_ver" style="display:none; width:900px;">
	<h1><?php echo $idioma["btn_o_que_eu_vejo"]; ?></h1><br />	
	
	<table border="0" width="700" class="table table-striped">
		<?php	
		echo '<tr><th colspan="2"><h3>'.$idioma['grupos'].'</h3></th></tr>';
		foreach($retorno_grupos as $grupo) {
			echo '<tr><th colspan="2"><h4>'.$grupo['nome'].' (Grupo)</h4></th></tr>';
			
			echo '<tr><th><h5>'.$idioma['assunto'].' </h5></th>';
			echo '<td>';
			foreach($grupo['assuntos'] as $assunto) {
				echo $assunto['nome'].'<br />';
			}
			echo '</tr>';
			echo '<tr><th><h5>'.$idioma['subassunto'].' </h5></th>';
			echo '<td>';
			foreach($grupo['subassuntos'] as $subassunto) {
				echo $subassunto['nome'].'<br />';
			}
			echo '</td></tr>';
		}
		?>
	</table>
</div>

</body>
</html>