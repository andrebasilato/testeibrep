<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usuario); ?>
<link rel="stylesheet" href="/assets/plugins/fcbkcomplete/style.css" type="text/css" media="screen" charset="utf-8" />
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
            <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"><?= $idioma["pagina_titulo"]; ?></a> <span class="divider">/</span></li>
			<li class="active"><?php echo tamanhoTexto(100,$linha["nome"]); ?></li>
      		<span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
  		</ul>
  	</section>
  	<div class="row-fluid" style="border: none; box-shadow: none">
  		<div class="span12">
        	<div class="box-conteudo">
        		<div class=" pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"><i class="icon-share-alt"></i> <?= $idioma["btn_sair"]; ?></a></div>
            	<h2 class="tituloEdicao"><?= tamanhoTexto(100,$linha["nome"]); ?> <? /* <small>(<?= $linha["email"]; ?>)</small> */ ?></h2>
            	<div class="tabbable tabs-left">
			 		<?php incluirTela("inc_menu_edicao",$config,$usuario); ?>
              		<div class="tab-content">
                		<div class="tab-pane active" id="tab_editar">
                      		<h2 class="tituloOpcao"><?= $idioma['lista_de_perguntas']; ?></h2>	
    						<div id="listagem_informacoes"><?= $idioma["texto_explicativo"]; ?></div>
    						<?php if($_POST["msg"]) { ?>
                            	<div class="alert alert-success fade in">
                                    <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                                  	<strong><?php echo $idioma[$_POST["msg"]]; ?></strong>
                              	</div>
                          	<? } ?>
                          	<?php if(count($salvar["erros"]) > 0){ ?>
                              	<div class="alert alert-error fade in">
                                    <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                                    <strong><?= $idioma["form_erros"]; ?></strong>
                                    <? foreach($salvar["erros"] as $ind => $val) { ?>
                                    	<br />
                                        <?php echo $idioma[$val]; ?>
                                    <? } ?>
                                </div>
                          	<? } ?>
       <div class="row-fluid">
      <div class="span12">
        <div class="box-conteudo"  style="border: none; box-shadow: none">
            <form action="" method="post" name="formDisciplinasClonar" id="formDisciplinasClonar" style="display: none; margin:10px 0;overflow:hidden;">
                <input type="hidden" name="acao" id="acao" value="clonar_perguntas" />
                <div id="select_acao" style="float: left;margin-right:10px;">
                    <label for="selDisciplinas"><strong><?= $idioma['form_clonar_perguntas']; ?></strong></label>
                    <select name="selDisciplinas" id="selDisciplinas" style="width:276px;">
                        <option value=""></option>
                        <?php
                        foreach ($disciplinasClonar as $disciplinaClonar) {?>
                            <option value="<?= $disciplinaClonar['iddisciplina']; ?>"><?= $disciplinaClonar['nome']; ?></option>
                            <?php
                        } ?>
                    </select>
                    <input type="button" id='submitDisciplinasClonar' class="btn" value="<?= $idioma['btn_clonar']; ?>" style="margin-top: 7px;" <?= !$linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|11", false) ? 'disabled' : ''; ?> /><br />
                </div>

            </form>
          <div id="listagem_informacoes"> 		  
            <? printf($idioma["informacoes"],$linhaObj->Get("total")); ?>
            <br />
            <? printf($idioma["paginas"],$linhaObj->Get("pagina"),$linhaObj->Get("paginas")); ?>
            <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|10", NULL)){ ?>
              <span class="pull-right" style="padding-top:3px; color:#999">
              <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/novapergunta" class="btn primary"><?= $idioma["nav_cadastrar"]; ?></a>
              </span>
			<? } ?>
          </div>
          <?php $linhaObj->GerarTabela($dadosArray,$_GET["q"],$idioma); ?>
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
        </div>
      </div>
    </div>
    <? incluirLib("rodape",$config,$usuario); ?>
    <script language="javascript" type="text/javascript">
	  jQuery(document).ready(function($) {
		$("#qtd").keypress(isNumber);
		$("#qtd").blur(isNumberCopy);

          (function(){
              var primeiraColunaBusca = $(".header:first");
              primeiraColunaBusca.html('')

              var novoBox = document.createElement('input');
              novoBox.type = "checkbox";
              novoBox.id = "marcarTodos";
              primeiraColunaBusca.append(novoBox);
          })();

          var formDisciplinasClonar = $('#formDisciplinasClonar'),
              inputPerguntas = $('input[name="perguntas[]"]');


          $('[type="checkbox"]').on('change', function(){
              if(this.id == 'marcarTodos') {
                  var checado = $(this).is(':checked');
                  $('[type="checkbox"]').each(function(){
                      $(this).prop('checked', checado);
                  });
              }
              var count = inputPerguntas.filter(':checked').length;
              if (count) {
                  formDisciplinasClonar.slideDown();
                  return;
              }

              formDisciplinasClonar.slideUp();
          });

          $("#submitDisciplinasClonar").click(function () {
              var disciplina = document.getElementById('selDisciplinas').value;
              var perguntaSelecionada = inputPerguntas.filter(':checked').length;
              if (disciplina != '') {
                  if (!perguntaSelecionada) {
                      alert('Selecione ao menos uma pergunta para clonar!');
                      return false;
                  }

                  var resposta = confirm('Deseja clonar a(s) pergunta(s) para a disciplina selecionada?');
                  if(! resposta) {
                      return false;
                  }
                  var form = document.getElementById('formDisciplinasClonar');
                  inputPerguntas.filter(':checked').each(function () {
                      var input = document.createElement("input");
                      input.type = "hidden";
                      input.name = "perguntas[" + $(this).val() + "]";
                      input.value = $(this).val();
                      form.appendChild(input);
                  });
                  form.submit();
              } else {
                  alert('Selecione uma disciplina para qual clonar!');
                  return false;
              }
          });
	  });
    </script>
  </div>
</body>
</html>