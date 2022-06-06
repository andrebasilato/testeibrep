<!DOCTYPE html>
<html class="no-js">
<head>
	<?php incluirLib("head",$config,$usu_professor); ?>


	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<link rel="stylesheet" href="<?= $config["urlSistema"]; ?>/assets/aluno/css/normalize.css">
	<link rel="stylesheet" href="<?= $config["urlSistema"]; ?>/assets/aluno/css/main.css">
	<link rel="stylesheet" href="<?= $config["urlSistema"]; ?>/assets/aluno/css/base.css">
	<link rel="stylesheet" href="<?= $config["urlSistema"]; ?>/assets/aluno/css/interna.css">
	<script src="<?= $config["urlSistema"]; ?>/assets/aluno/js/vendor/jquery-1.9.1.min.js"></script>
	<script src="<?= $config["urlSistema"]; ?>/assets/aluno/js/main.js"></script>
	<link rel="stylesheet" href="<?= $config["urlSistema"]; ?>/assets/aluno/css/forum.css">
	<link rel="stylesheet" href="/assets/plugins/fcbkcomplete/style.css" type="text/css" media="screen" charset="utf-8" />
	<style type="text/css">
		.lista-chat {
			background-color: #f2f2f2;
			height: 472px;
			overflow-y: scroll;
		}
		.chat-listagem {
			background-color: #f2f2f2;
			height: 300px;
			overflow-y: scroll;
		}
		.chat-participante {
			background-color: #f2f2f2;
			height: 310px;
			overflow-y: scroll;
		}
		.chat-titulo {
			margin-top: -44px;
			padding-left: 59px;
			top: 0px;
			width: 100% !important;
		}
		.chat-tx-apoio {
			font-size: 10px !important;
			margin-top: -7px;
			text-transform: uppercase;
		}
		.chat-tx-vermelho {
			color: #F30;
		}
		.chat-tx-verde {
			color: #093;
		}
		.chat-avatar-inverso {
			float: right;
		}
		.chat-listagem-inverso {
			padding-left: 0px;
			padding-right: 59px;
		}
		.chat-input {
			appearance: textfield;
			border: none !important;
			color: #FFF !important;
			float: left !important;
			height: 50px !important;
			max-width: none !important;
			padding-left: 10px !important;
			width: 80% !important;
		}
		.chat-bt {
			float: right;
			height: 50px !important;
		}
		.marge20-up {
		margin-top: 20px;
		}
		.chat-input::-webkit-input-placeholder {
			color: #FFF;
		}
		.chat-input::-moz-placeholder {
			color: #FFF;
		}
		.input-verde {
			border: medium none;
			color: #FFFFFF;
			height: 40px;
			text-align: center;
			font-size: 14px;
		}
	</style>
	<script type="text/javascript">
		<?php if ($_POST["msg"]) { ?>
			alert("<?php echo $idioma[$_POST["msg"]]; ?>");
		<?php } ?>
	</script>
</head>
<body>
	<div class="content" style="min-width: 600px !important;">
		<div class="conteudo" style="width: 100%; margin: 0 auto;">
			<div class="coluna-dados" id="coluna-conteudo">
				<div class="area area-conteudo">
					<div class="principal-area">
						<div class="principal-titulo">
							<h2><?= $idioma["titulo"]; ?></h2>
							<span style="font-weight: 100;background: #CF1826; color: #fff; padding: 3px 14px; top: 15px; border-radius: 10px 0; font-size: 16px; position: absolute; right: 22px;">
								<?= $idioma["ambiente"]; ?>
							</span>
						</div> <!-- principal - titulo  -->
						<div style="clear: both"></div>
						<?php if ($_POST["msg"]) { ?>
							<div class="alert alert-success fade in">
								<a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
								<strong><?= $idioma[$_POST["msg"]]; ?></strong>
							</div>
						<?php } ?>
						<?php if (count($salvar["erros"]) > 0) { ?>
							<div class="alert alert-error fade in">
								<a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
								<strong><?= $idioma["form_erros"]; ?></strong>
								<?php foreach ($salvar["erros"] as $ind => $val) { ?>
									<br />
									<?= $idioma[$val]; ?>
								<?php } ?>
								</strong>
							</div>
						<?php } ?>
						<div class="principal-texto" style="overflow-y:hidden;">
							<div class="row-fluid"> 
								<div class="span12"> <!-- coluna 1 -->
									<div>
										<?php /*<div class="forum-base-titulo corbgVerde-claro">
											<?= $idioma["conversas"]; ?>
										</div>*/?>
										<div class="forum-base-titulo corbgVerde-claro">
											<?= $idioma["adicionar_usuarios"]; ?>
										</div>
										<form method="post" enctype="multipart/form-data" onsubmit="return validateFields(this, regras);">
											<ul style="overflow: auto; width: 100%;" class="forum-listagem chat-participante">
												<li>
													<input name="acao" type="hidden" value="adicionar_usuario" />
													<select id="participantes" name="participantes"></select>
													<button type="submit" class="cursos-box-bt cursos-box-btfix corbgverde btfade" style="border: 0px none;"><?= $idioma["btn_salvar"]; ?></button>
												</li>
											</ul>
										</form>

									</div>
								</div>
							</div>
						</div> <!-- principal area -->
					</div>
				</div> <!-- area-conteudo -->   
			</div><!-- coluna dados -->
		</div>
	</div>
	<script src="/assets/js/validation.js"></script>
	<script type="text/javascript">
		var regras = new Array();
		regras.push("required,participantes,<?php echo $idioma["participantes_vazio"]; ?>");
	</script>
	<script src="/assets/js/jquery.1.7.1.min.js"></script>
	<script src="/assets/plugins/fcbkcomplete/jquery.fcbkcomplete.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$('#coluna-conteudo').css('left', 0);

			$("#participantes").fcbkcomplete({
				json_url: "/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/json/participantes_mensagem_instantanea/<?= intval($url[3]); ?>/<?= $usu_professor["idprofessor"]; ?>/",
				addontab: true,
				height: 10,
				width: "100%",
				maxshownitems: 10,
				cache: true,
				maxitems: 20,
				filter_selected: true,
				firstselected: true,
				complete_text: "<?= $idioma["participante_select"]; ?>",
				addoncomma: true
			});
		});
	</script>
	<script src="/assets/bootstrap_v2/js/bootstrap-alert.js"></script>
</body>
</html>