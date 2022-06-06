<? header('Content-Type: text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<?php incluirLib("head", $config, $usuario); ?>
</head>
<body>

<?php incluirLib("topo", $config, $usuario); ?>
<!-- /Topo -->
<!-- Conteudo -->
<div class="content">
    <p class="texto-index"></p>
</div>
<div class="content">
    <div class="box-bg">
        <span class="top-box box-azul">
            <h1><?php echo $idioma['documentos']; ?></h1>
            <i class="icon-folder-open"></i>
        </span>
        <h2 class="ball-icon">&bull;</h2>
        <div class="clear"></div>
        <!-- Atendimentos -->
        <div class="row-fluid">
            <div class="span12 abox box-item extra-align">
                <div class="row-fluid">
                    <div class="span12">
                        <div class="r-align"><a href="<?php echo '/'.$url[0].'/'.$url[1].'/'.$url[2].'/solicitar'; ?>" class="abrirModal"><div class="btn btn-verde"><?php echo $idioma['solicitar']; ?></div></a></div>
                    </div>
                </div>
                <br />
                <?php if($_POST["msg"]) { ?>
                    <div class="alert alert-success fade in">
                        <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                        <strong><?php echo $idioma[$_POST["msg"]]; ?></strong>
                    </div>
                <? } ?>
                <?php foreach($matriculas as $matricula) {
					$declaracoes = $matriculaObj->retornarDeclaracoesMatricula($matricula['idmatricula']);

					?>
                    <div class="row-fluid">
                        <div class="span12 border-box">
                            <div class="row-fluid">
                                <div class="span2">
                                    <div class="imagem-item"><img src="/api/get/imagens/cursos_imagem_exibicao/168/114/<?php echo $matricula["imagem_exibicao_servidor"]; ?>" alt="Curso" /></div>
                                </div>
                                <div class="span10">
                                    <div class="row-fluid show-grid">
                                        <div class="span12 description-item r-margin">
                                            <div class="span8">
                                                <h1><?php echo $matricula['curso']; ?></h1>
                                                <p><?php echo $idioma['carga_horaria']; ?> <strong><?php echo $matricula['carga_horaria_total']; ?></strong></p>
                                                <p><?php echo $idioma['matricula']; ?> <strong><?php echo $matricula['idmatricula']; ?></strong></p>
                                                <p><?php echo $idioma['andamaento_curso']; ?> <strong><?php echo number_format($matricula['porcentagem'],2,',','.'); ?>%</strong></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <table width="100%" border="0" cellspacing="1" cellpadding="5">
                                        <thead class="a-table">
                                            <tr bgcolor="#e6e6e6">
                                                <td align="center"><?php echo $idioma['idsolicitacao']; ?></td>
                                                <td align="center"><?php echo $idioma['declaracao']; ?></td>
                                                <td align="center"><?php echo $idioma['data_solicitacao']; ?></td>
                                                <td align="center"><?php echo $idioma['data_deferido']; ?></td>
                                                <td align="center"><?php echo $idioma['situacao']; ?></td>
                                                <td align="center"><?php echo $idioma['opcoes']; ?></td>
                                            </tr>
                                        </thead>
                                        <?php if(count($declaracoes)) { ?>
                                            <tbody class="a-table">
                                                <?php
												foreach($declaracoes as $declaracao) {
													if($declaracao['situacao'] == 'D') {
														$declaracao['id'] = 'D'.$declaracao['idmatriculadeclaracao'];
														$declaracao['data_solicitacao'] = '--';
														$declaracao['data_deferido'] = formataData($declaracao['data_cad'],'br',1);
														$declaracao['situacao'] = 'D';
														$link = "<a href=\"/{$url[0]}/{$url[1]}/{$url[2]}/declaracao/{$declaracao['idmatriculadeclaracao']}/download\" class=\"btn btn-azul btn-mob\" target=\"_blank\">{$idioma['abrir_declaracao']}</a>";
													} else if($declaracao['situacao'] == 'E') {
                                                        $declaracao['id'] = 'E'.$declaracao['idsolicitacao_declaracao'];
                                                        $declaracao['data_solicitacao'] = formataData($declaracao['data_solicitacao'],'br',1);
                                                        $declaracao['data_deferido'] = '--';
                                                        $declaracao['situacao'] = 'E';
                                                        $link = '--';
                                                    }else if($declaracao['situacao'] === null and
                                                        (int)$declaracao['idmatriculadeclaracao'] > 0 and
                                                        $declaracao['idsolicitacao_declaracao'] === null) {
                                                        $declaracao['id'] = 'D'.$declaracao['idmatriculadeclaracao'];
														$declaracao['data_solicitacao'] = '--';
														$declaracao['data_deferido'] = formataData($declaracao['data_cad'],'br',1);
														$declaracao['situacao'] = 'D';
														$link = "<a href=\"/{$url[0]}/{$url[1]}/{$url[2]}/declaracao/{$declaracao['idmatriculadeclaracao']}/download\" class=\"btn btn-azul btn-mob\" target=\"_blank\">{$idioma['abrir_declaracao']}</a>";
													}else {
														$declaracao['id'] = 'I'.$declaracao['idsolicitacao_declaracao'];
														$declaracao['data_cad'] = $declaracao['data_solicitacao'];
														$declaracao['data_solicitacao'] = formataData($declaracao['data_solicitacao'],'br',1);
														$declaracao['data_deferido'] = '--';
														$link = "<a href=\"/{$url[0]}/{$url[1]}/{$url[2]}/solicitacao/{$declaracao['idsolicitacao_declaracao']}/motivo\" class=\"btn btn-vermelho btn-mob abrirModal\">{$idioma['var_motivo']}</a>";
													}
													?>
                                                    <tr>
                                                        <td>
															<?php
															echo $declaracao['id'];
															$diferenca = dataDiferenca($declaracao["data_cad"], date("Y-m-d H:i:s"), "H");
															if($diferenca <= 24) {
															?>
                                                                <i class="new"></i>
															<?php } ?>
                                                        </td>
                                                        <td><?php echo $declaracao['nome']; ?></td>
                                                        <td align="center"><?php echo $declaracao['data_solicitacao']; ?></td>
                                                        <td align="center"><?php echo $declaracao['data_deferido']; ?></td>
                                                        <td align="center"><div class="btn btn-mob" style="background: <?php echo $cor_status_solicitacao_declaracao[$declaracao['situacao']]; ?>;color:#FFFFFF;cursor:default;"><?php echo $status_solicitacao_declaracao[$config['idioma_padrao']][$declaracao['situacao']]; ?></div></td>
                                                        <td width="160px" align="center"><?php echo $link; ?></td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
										<?php } ?>
                                    </table>
                                    <?php if(count($declaracoes) == 0) { ?>
                                        <table width="100%" border="0" cellspacing="1" cellpadding="5">
                                            <tbody class="b-table">
                                                <tr>
                                                    <td><i><?php echo $idioma['nenhuma_solicitacao']; ?></i></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br />
                <?php } ?>
            </div>
        </div>
        <!-- Atendimentos -->
    </div>
</div>
<!-- /Conteudo -->
<?php incluirLib("rodape", $config, $usuario); ?>
<script src="/assets/js/validation.js"></script>
<script type="text/javascript">
function descerScroll() {
	var objScrDiv = document.getElementById("divScroll");
	objScrDiv.scrollTop = objScrDiv.scrollHeight;
}

$(document).ready(function() {
	// Support for AJAX loaded modal window.
	// Focuses on first input textbox after it loads the window.
	$('.abrirModal').click(function(e) {
		e.preventDefault();
		var url = $(this).attr('href');
		var atendimento = url.split('/')[4];
		if (url.indexOf('#') == 0) {
			$(url).modal('open').on('shown', function () { descerScroll(); }).on("hidden", function () { $(this).remove(); });
		} else {
			$.get(url, function(data) {
				$('<div class="modal hide fade text-side-two extra-align" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">'+data+'</div>').modal().on('shown', function () { descerScroll(); }).on("hidden", function () { $(this).remove(); });
			}).success(function() {
				$('input:text:visible:first').focus();
			});
		}
	});
});
</script>
</body>
</html>
