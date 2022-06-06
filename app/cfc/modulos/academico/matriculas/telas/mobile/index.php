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
                <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/novamatricula" class="btn btn-large btn-primary" style="color:#FFF; margin-bottom:10px"><?= $idioma["nav_cadastrar"]; ?></a>
          <div id="listagem_informacoes"> 	         	  
            <? printf($idioma["informacoes"],$matriculaObj->Get("total")); ?>
            <br />
            <? printf($idioma["paginas"],$matriculaObj->Get("pagina"),$matriculaObj->Get("paginas")); ?>
          </div>


<table width="100%" class="table table-striped " id="sortTableExample">
          <thead>
            <tr>
              <th class="header headerSortUp headerSortReloca"  width="60"><a href="?qtd=30&cmp=re.idreserva&ord=asc" title="re.idreserva">
                <div class='headerNew'>Nome do cliente</div>
                </a></th>
            </tr>
          </thead>
          <tbody>
			<form action="" method="get" id="formBusca" class="form-inline">
            <tr>
              <td>
				<table width="100%">
				  <tr>
				    <td>
						<input class="span2" id="q[2|p.nome]" name="q[2|p.nome]" type="text" value="" />
					</td>
					<td>
						<input type="submit" class="btn small" value="Buscar" />
					</td>
				  </tr>
				</table>
			  </td>
            </tr>
			</form>
		<?
			foreach($dadosArray as $linha => $dados){ 
		?>           
            <tr>
              <td colspan="2">
                  <div class="pull-right">
                  <span class="label" style="background:#<?= $dados["situacao_cor_bg"]; ?>; color:#FFF;"><?= $dados["situacao"]; ?></span>
                  <br />
                  <div class="pull-right">
                  	<a class="btn dropdown-toggle btn-mini" style="margin-top:5px;" target="_blank" href="/<?= $url[0]; ?>/academico/matriculas/<?= $dados["idmatricula"]; ?>/dossie">Ficha</a>  
                  </div>
                  </div>
                  <div>
                    <span style="width:150px;"><strong><?= $dados["idmatricula"]; ?></strong></span>
                    
                    <br />
                    <span><strong><?= $dados["aluno"]; ?></strong></span>
                  </div>
                  <div>
                  	<?= $dados["sindicato"]["nome_abreviado"]; ?> &gt; <?= $dados["escola"]["razao_social"]; ?>
                  </div>                  
                  <div>
                  	<?= $dados["oferta"]; ?> &gt; <?= $dados["curso"]["codigo"]; ?>
                  </div> 
              </td>
            </tr>
        <? } ?>

          </tbody>
        </table>
          
         
          <? if($matriculaObj->Get("paginas") > 1) { ?>
            <div class="pagination"><ul><?= $matriculaObj->GerarPaginacao($idioma); ?></ul></div>
          <? } ?>
          <div class="clearfix"></div>                                  
        </div>
      </div>
    </div>
    <? incluirLib("rodape",$config,$usuario); ?>
    <script language="javascript" type="text/javascript">
	  jQuery(document).ready(function($) {
		$("#qtd").keypress(isNumber);
		$("#qtd").blur(isNumberCopy);
	  });
    </script>
  </div>
</body>
</html>