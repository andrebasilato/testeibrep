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
      <div class="page-header">
    	<h1><?= $idioma["pagina_titulo"]; ?> &nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1>
  	</div>
  	<ul class="breadcrumb">
      <li><a href="/<?= $url[0]; ?>"><?= $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>"><?= $idioma["nav_modulo"]; ?></a> <span class="divider">/</span></li>
        <li class="active"><?= $idioma["pagina_titulo"]; ?></a></li>
      <span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
  	</ul>
  </section>
  <div class="row-fluid">
    <div class="span12">
      <div class="box-conteudo">
        <div class="pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"><i class="icon-share-alt"></i> <?= $idioma["btn_sair"]; ?></a></div>
          <h2 class="tituloEdicao"><?php echo $linha["nome"]; ?></h2>
          <div class="tabbable tabs-left">
            <div class="tab-content">
              <div class="tab-pane active" id="tab_editar">
				<h2 class="tituloOpcao"><?php echo $idioma["titulo_opcao_administrar"];?></h2>
				<? if($_POST["msg"]) { ?>
                  <div class="alert alert-success fade in">
                    <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                    <strong><?= $idioma[$_POST["msg"]]; ?></strong>
                  </div>
                <? } ?>
                <? if(count($salvar["erros"]) > 0){ ?>
                  <div class="alert alert-error fade in">
                    <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                    <strong><?= $idioma["form_erros"]; ?></strong>
                    <? foreach($salvar["erros"] as $ind => $val) { ?>
                      <br />
                      <?php echo $idioma[$val]; ?>
                    <? } ?>
                  </div>
                <? } ?>
                <form class="well" action="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/administrar/" method="post" id="form_matricula" name="form_matricula" onsubmit="return Seturl();">
                  <p><?= $idioma["form_abrir"]; ?></p>
                  <?php if($perfil["permissoes"][$url[2]."|2"]) { ?>    
                  <select id="matriculas" name="matriculas"></select>
                  <br />
                  <input type="hidden" id="acao" name="acao" value="abrir_ficha_financeira">
                  <input type="submit" class="btn btn-primary" name="enviar" value="<?php echo $idioma["btn_abrir_ficha"]; ?> " />
                  <? /*<a class="btn btn-mini" href="#ficha_financeira" rel="facebox" onclick="seleciona_matricula()"><?= $idioma["btn_abrir_ficha"]; ?></a> */?>
                  <?php } else { ?>
                  <select id="matriculas" name="matriculas" disabled="disabled"></select>
                  <br />
                  <br />
				  <?php } ?>
                  <br /><br />
                </form>



          <div id="listagem_informacoes">       
            <? printf($idioma["informacoes"],$linhaObj->Get("total")); ?>
            <br />
            <? printf($idioma["paginas"],$linhaObj->Get("pagina"),$linhaObj->Get("paginas")); ?>
            
          </div>
          <?php  $linhaObj->GerarTabela($cobrancasArray,$_GET["q"],$idioma,'listagem_cobrancas','tabelaSemTamanho'); ?>
          <div id="listagem_form_busca">
            <div class="input">
              <div class="inline-inputs"> 
        <?= $idioma["registros"]; ?>
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
          <? if($linhaObj->Get("paginas") > 1) { ?>
            <div class="pagination"><ul><?= $linhaObj->GerarPaginacao($idioma); ?></ul></div>
          <? } ?>
          <div class="clearfix"></div>                                  
      <script>
  				function Seturl() {
            matricula =  document.getElementById('matriculas').selectedIndex;
            if (matricula==-1){
              alert("Selecione a matrícula que deseja abrir a ficha financeira.");
              return false;
            }
  					document.form_matricula.action = "/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/administrar/" + document.getElementById('matriculas').options[document.getElementById('matriculas').selectedIndex].value;
  				  return true;
          }
				</script>
              </div>
            </div>
          </div>
        </div>
      </div>  
    </div>
	<? incluirLib("rodape",$config,$usuario); ?>
    <script src="/assets/plugins/fcbkcomplete/jquery.fcbkcomplete.min.js"></script>
    <script type="text/javascript">
		$(document).ready(function(){                
			$("#matriculas").fcbkcomplete({
				json_url: "/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/json/pesquisar_matriculas",
				addontab: true,
				height: 10,
				maxshownitems: 15,
				cache: true,
				maxitems: 1,
				input_min_size: 1,
				filter_selected: true,
				firstselected: true,
				complete_text: "<?= $idioma["mensagem_select"]; ?>",
				addoncomma: true,
			});
			
			$('.submit-link')
			  .click(function(e){
				  e.preventDefault();
				  $(this).closest('form')
					  .submit();
    		});
		});
		
	</script>
  </div>
</body>
</html>