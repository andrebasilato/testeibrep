<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usuario); ?>
<link rel="stylesheet" href="/assets/plugins/fcbkcomplete/style.css" type="text/css" media="screen" charset="utf-8" />
</head>
<body>
  <? incluirLib("topo",$config,$usuario); ?>
  <div class="container-fluid">
    <section id="global">
      <div class="page-header"><h1><?= $idioma["pagina_titulo"]; ?> &nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1></div>
      <ul class="breadcrumb">
        <li><a href="/<?= $url[0]; ?>"><?= $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>"><?= $idioma["nav_modulo"]; ?></a> <span class="divider">/</span></li>
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"><?= $idioma["pagina_titulo"]; ?></a> <span class="divider">/</span></li>
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $linha["idoferta"]; ?>/editar"><?= strlen($linha["nome"]) > 30 ? mb_strimwidth($linha["nome"], 0, 50, " ...") : $linha["nome"]; ?></a> <span class="divider">/</span> </li>
        <li class="active"><?= $idioma["pagina_titulo_interno"]; ?></li>
        <?php if ($_GET["q"]) { ?>
          <li><span class="divider">/</span> <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $linha["idoferta"]; ?>/cursos"><?= $idioma["nav_resetarbusca"]; ?></a></li>
        <?php } ?>
        <span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
      </ul>
    </section>
    <div class="row-fluid">
      <div class="span12">
        <div class="box-conteudo">
		  <div class=" pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"><i class="icon-share-alt"></i> <?= $idioma["btn_sair"]; ?></a></div>
            <h2 class="tituloEdicao"><?= !mb_strpos($linha["nome"], ' ') ? wordwrap($linha["nome"], 30, " ", true) : $linha["nome"] ; ?></h2>
          <div class="tabbable tabs-left">
            <?php incluirTela("inc_menu_edicao",$config,$linha); ?>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_editar">
					<h2 class="tituloOpcao"><?= $idioma["titulo_opcao"]; ?></h2>
					<?
					if ($_POST["msg"]) {
						?>
					  	<div class="alert alert-success fade in">
							<a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
							<strong><?= $idioma[$_POST["msg"]]; ?></strong>
					  	</div>
					<?php
					}
					if (count($salvar['erros']) > 0) {
                        ?>
                        <div class="alert alert-error fade in">
                            <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                                <strong><?= $idioma['form_erros']; ?></strong>
                                <?php
                                foreach ($salvar['erros'] as $ind => $val) {
                                    echo '<br />' . $idioma[$val];
                                }
                                ?>
                            </div>
                        <?php
                    }
					?>
                
							<form class="well" method="post" id="form">
    							<p><?= $idioma["form_associar"]; ?></p>
								<?php if($perfil["permissoes"][$url[2]."|5"]) { ?>    
                                <select id="cursos" name="cursos" class="span6">
									<option value=""><?php echo $idioma['selecione_curso']; ?></option>
									<?php foreach ($cursosArray as $curso) { ?>
                                        <option value="<?php echo $curso['idcurso']; ?>">(<?php echo $curso['idcurso']; ?>) <?php echo strlen($curso["nome"]) > 30 ? mb_strimwidth($curso["nome"], 0, 50, "...") : $curso["nome"]; ?></option>
									<?php } ?>
								</select>
								&nbsp;
							  	<select id="possui_financeiro" name="possui_financeiro" class="span2">
                                    <option value=""><?= $idioma['possui_financeiro'] ?></option>
									<option value="S"><?= $idioma['opcao_sim'] ?></option>
									<option value="N"><?= $idioma['opcao_nao'] ?></option>
								</select>
								&nbsp;	
    							<input type="hidden" id="acao" name="acao" value="associar_curso">
                                <input type="submit" class="btn" value="<?= $idioma["btn_adicionar"]; ?>" />
                                <?php } else { ?>
                                <select id="cursos" name="cursos" disabled="disabled"></select>
                                <br />
                                <a href="javascript:void(0);" rel="tooltip" data-original-title="<?= $idioma["btn_permissao_inserir"]; ?>" data-placement="right" class="btn disabled"><?= $idioma["btn_adicionar"]; ?></a>
                                <?php } ?>
    						</form>
                            <br />
     						<form method="post" id="remover_curso" name="remover_curso">
    							<input type="hidden" id="acao" name="acao" value="remover_curso">
                                <input type="hidden" id="remover" name="remover" value="">
    						</form>
							
							<?php $linhaObj->GerarTabela($dadosArray,$_GET["q"],$idioma,"listagem_cursos"); ?>
							
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
									  <? } 
									} ?>
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
                            <?= $idioma['texto_explicativo_porc_pres'] ?><br>
                            <?= $idioma['texto_explicativo_porc_virt'] ?><br>
                            <?= $idioma['texto_explicativo_qtde_pres'] ?><br>
							<? if($linhaObj->Get("paginas") > 1) { ?>
							  <div class="pagination"><ul><?= $linhaObj->GerarPaginacao($idioma); ?></ul></div>
							<? } ?>
				
				
              </div>
            </div>
          </div>
        </div>
      </div>  
    </div>
    <? incluirLib("rodape",$config,$usuario); ?>
	
	<script src="/assets/plugins/fcbkcomplete/jquery.fcbkcomplete.min.js"></script>
    <script type="text/javascript">
		/*$(document).ready(function(){                
			$("#cursos").fcbkcomplete({
				json_url: "/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/json/associar_curso",
				addontab: true,
				height: 10,
				maxshownitems: 10,
				cache: true,
				maxitems: 20,
				filter_selected: true,
				firstselected: true,
				complete_text: "<?= $idioma["mensagem_select"]; ?>",
				addoncomma: true
			});
		});*/
		
		function remover(id) {
			confirma = confirm('<?= $idioma["confirmar_remocao"]; ?>');
			if(confirma) {
				document.getElementById("remover").value = id;
				document.getElementById("remover_curso").submit();
			} 
		}
	</script>
	
  </div>
</body>
</html>