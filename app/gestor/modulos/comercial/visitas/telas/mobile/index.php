<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usuario); ?>
<script language="javascript" type="text/javascript">
  jQuery(function($){
  	$("#qtd").keypress(isNumber);
  	$("#qtd").blur(isNumberCopy);
  });
</script>
</head>
<body>
<?php incluirLib("topo",$config,$usuario); ?>
<div class="container-fluid">
  <section id="global">
	<div class="page-header">
    	<h1><?= $idioma["pagina_titulo"]; ?> &nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1>
  	</div>
  </section>
  <div class="row-fluid">
  	<div class="span12">
        <div class="box-conteudo">
       			<? if($_POST["msg"]) { ?>
      				<div class="alert alert-success fade in"> 
                    	<a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
        				<p><?= $idioma[$_POST["msg"]]; ?></p>
      				</div>
      			<? } ?> 
                
    
 				<? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2", NULL)){ ?>
                    <span style="padding-bottom:10px; color:#999">
						<a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/cadastrar" style="color:#FFF;" class="btn btn-primary btn-large"> <?= $idioma["nav_novousuario"]; ?> </a>
                    </span> 
					<? } ?>    
                
                
        		<div id="listagem_informacoes"> 
					
	 
                                    		  
		  			<? printf($idioma["informacoes"],$linhaObj->Get("total")); ?>
                    <br />
          			<? printf($idioma["paginas"],$linhaObj->Get("pagina"),$linhaObj->Get("paginas")); ?>                    
                </div>


<table width="100%" class="table table-striped " id="sortTableExample">
          <thead>
            <tr>
              <th class="header headerSortUp headerSortReloca"  width="60"><a href="?qtd=30&cmp=idvisita&ord=asc" title="idvisita">
                <div class='headerNew'>Número</div>
                </a></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <form action="" method="get" id="formBusca">
                <td><input class="inputPreenchimentoCompleto" id="q[1|idvisita]" name="q[1|idvisita]" type="text" value="" />
                <input type="submit" class="btn small" value="Buscar" /></td>
              </form>
            </tr>
<?
	foreach($dadosArray as $linha => $dados){ 
?>           
            <tr>
              <td>
                  <div>
                    <?
					  $diferenca = dataDiferenca($dados["data_cad"], date("Y-m-d"), "H");
					  if($diferenca > 24) {
						  echo "<span title=\"$diferenca\" style=\"width:150px;\"><strong>".$dados["idvisita"]."</strong></span>";
					  } else {
						  echo "<span title=\"$diferenca\" style=\"width:150px;\"><strong>".$dados["idvisita"]."</strong></span> <i class=\"novo\"></i>";
					  }					
					?>
                    
                    <?
					  if($dados["situacao"] == "MAT") {
						echo "<span class=\"label label-success pull-right\" style=\"color:#FFF\">Matriculado</span>";
					  } else if($dados["situacao"] == "EMV") {
						echo "<span class=\"label label-warning pull-right\" style=\"color:#FFF\">Em visita</span>";
					  } else {
						echo "<span class=\"label label-important pull-right\" style=\"color:#FFF\">Sem interesse</span>";
					  }
					?>
                    
                    <br />
                    <span><strong><?= $dados["nome"]; ?></strong> <br /><?= $dados["email_pessoa"]; ?></span>
                  </div>
                  <div>
                  	<?= $dados["vendedor"]; ?>
                  </div>
              </td>
            </tr>
<? } ?>

          </tbody>
        </table>


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
</div>
</body>
</html>