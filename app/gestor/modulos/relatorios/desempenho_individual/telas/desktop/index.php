<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php incluirLib("head", $config, $usuario); ?>
	<link rel="icon" href="/assets/img/favicon.ico">
	<link rel="stylesheet" href="/assets/plugins/fcbkcomplete/style.css" type="text/css" media="screen" charset="utf-8" />
	<style type="text/css">
		body {
			padding-top: 40px;
			background-image:none;
		}
		h2 {
			font-size:30px;	
			text-transform:uppercase;
			line-height:110%;
			margin:25px;
			color:#666;
		}

		body,td,th {
			font-size: 12px;
			color: #666666;
		}
		a:link {
			color: #000000;
		}
		a:visited {
			color: #000000;
		}
		a:active {
			color: #000;
		}
		p {
			margin-left:25px;	
		}
		.breadcrumb {
			font-size:10px;	
		}
		a:hover {
			color: #000000;
		}
	</style>
</head>
<body>
<div class="container"> 
	<div style="margin-bottom:25px">
    	<a href="/<?= $url[0]; ?>" class="logo"></a>
    </div>
    <div class="row">
		<ul class="breadcrumb">
			<li><?= $idioma["nav_inicio"]; ?><span class="divider">/</span></li>
			<li><?= $idioma["nav_relatorios"]; ?> <span class="divider">/</span></li>
			<li class="active"><?= $idioma["pagina_titulo"]; ?></li>
			<? if($_GET["q"]) { ?><li><span class="divider">/</span> <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"><?= $idioma["nav_resetarbusca"]; ?></a></li><? } ?>
			<span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
		</ul> 

		<h2><?= $idioma["pagina_titulo"]; ?></h2>  
		<p>Selecione as opções abaixo.</p>
		
		<p>
			<form method="get" action="/<?= rtrim(implode('/', $url), '/') ?>/html" id="formRelatorio" class="form-horizontal" target="_blank">    
				<?php $relatorioDesempenhoObj->gerarFormulario("formulario", $linha, $idioma); ?>
					<div class="form-actions">
						<input type="hidden" name="matricula_aluno" id="matricula_aluno" value="">
						<input type="hidden" name="matricula_aluno_cpf" id="matricula_aluno_cpf" value="">
						<input type="submit" class="btn btn-primary" value="<?= $idioma["btn_gerar_html"]; ?>" onclick="document.getElementById('formRelatorio').action = '/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/html'">
						&nbsp;
						<a href="/gestor/relatorios" class="btn dropdown-toggle"> <?= $idioma["btn_cancelar"]; ?> </a>
					</div>
				</fieldset>
			</form>
		</p>
  	</div>
  </div>
</div>
<?php incluirLib("rodape", $config, $usuario); ?>
	<script src="/assets/plugins/fcbkcomplete/jquery.fcbkcomplete.js"></script>
	<?php incluirLib("validacao_forms", $config, $usuario); ?>
	<script type="text/javascript">
		$(document).ready(function() {
			$('#formRelatorio').on('submit', function() {
				if (
					$('#form_idmatricula').val().length == 0
					&& $('#matricula_aluno').val().length == 0
					&& $('#matricula_aluno_cpf').val().length == 0
				) {
					alert('É necessário preencher um dos campos');
					return false;
				}
			})

			$("#nome_aluno").fcbkcomplete({
				width: 300,
				width_options: 300,
				width_dialog: 300,
				json_url: "/<?= rtrim(implode('/', $url), '/') ?>/ajax_alunos" + $("#nome_aluno").val(),
				addontab: true,
				height: 10,
				maxshownitems: 1000,
				cache: true,
				maxitems: 1,
				input_min_size: 1,
				filter_selected: true,
				firstselected: true,
				complete_text: "Digite o nome do aluno, selecione e associe...",
				addoncomma: true,
				onselect: function(value) {
					$('#matricula_aluno').val(value._value);
				},
				onremove: function() {
					$('#matricula_aluno').val("");
				}
			});

			$("#cpf_aluno").fcbkcomplete({
				width: 300,
				width_options: 300,
				width_dialog: 300,
				json_url: "/<?= rtrim(implode('/', $url), '/') ?>/ajax_alunos_cpf" + $("#cpf_aluno").val(),
				addontab: true,
				height: 10,
				maxshownitems: 1000,
				cache: true,
				maxitems: 1,
				input_min_size: 1,
				filter_selected: true,
				firstselected: true,
				complete_text: "Digite o cpf do aluno, selecione e associe...",
				addoncomma: true,
				onselect: function(value) {
					$('#matricula_aluno_cpf').val(value._value);
				},
				onremove: function() {
					$('#matricula_aluno_cpf').val("");
				}
			});

			$("#nome_aluno, #cpf_aluno").hide();

			
		});
	</script>
</body>
</html>