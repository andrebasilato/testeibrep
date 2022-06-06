<? header('Content-Type: text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<?php incluirLib("head", $config, $usuario); ?>
    <style type="text/css">
        .reprovacao {
            margin: 0;
            font-family: Verdana, Verdana, "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-size: 10px;
            line-height: 15px;
            color: #333333;
            background-color: #ffffff;
        }

        .page-header {
            padding-bottom: 5px;
            margin: 18px 0;
            border-bottom: 1px solid #eeeeee;
        }

        .page-header h1 {
            line-height: 1;
            font-size: 30px;
            line-height: 36px;
            margin: 0;
            font-weight: bold;
            color: #333333;
            text-rendering: optimizelegibility;
            font-family: Calibri, Vernada;
        }

        textarea {
            font-family: Verdana, "Helvetica Neue", Helvetica, Arial, sans-serif;
        }

        textarea,
        .uneditable-input {
            display: inline-block;
            width: 210px;
            height: 18px;
            padding: 4px;
            margin-bottom: 0px;
            font-size: 11px; /* Era 13 */
            line-height: 18px;
            color: #555555;
            border: 1px solid #ccc;
        }

        textarea[disabled],
        textarea[readonly] {
            background-color: #f5f5f5;
            border-color: #ddd;
            cursor: not-allowed;
        }

        @font-face {
            font-family: 'Calibri';
            src: url('/assets/fontface/calibri/calibri.eot');
            src: url('/assets/fontface/calibri/calibri.eot?#iefix') format('embedded-opentype'), url('/assets/fontface/calibri/calibri.woff') format('woff'), url('/assets/fontface/calibri/calibri.ttf') format('truetype'), url('/assets/fontface/calibri/calibri.svg#calibri') format('svg');
            font-weight: normal;
            font-style: normal;
        }
        .page-header {
            margin-top: 5px;
            margin-bottom: 2px;
        }

        .page-header h1 {
            font-family: 'Calibri', Calibri, Verdana, Geneva, sans-serif;
            color: #666666; /* #296744 */
            text-transform: uppercase;
        }
    </style>
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
                        <div class="r-align">
                            <a href="<?= '/'.$url[0].'/'.$url[1].'/'.$url[2].'/adicionar'; ?>" class="abrirModalAdicionar">
                                <div class="btn btn-verde"><?= $idioma['adicionar']; ?></div>
                            </a>
                        </div>
                    </div>
                </div>
                <br />
                <?php
                if($_POST["msg"]) {
                    ?>
                    <div class="alert alert-success fade in">
                        <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                        <strong><?= $idioma[$_POST["msg"]]; ?></strong>
                    </div>
                    <?php
                }
                
                if ($mensagem["erro"]) {
                    ?>
                    <div class="alert alert-error">
                        <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                        <?= $idioma[$mensagem["erro"]]; ?>
                    </div>
                    <?php
                }

                if (count($adicionar["erros"]) > 0) {
                    ?>
                    <div class="alert alert-error fade in">
                        <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                        <strong><?= $idioma["form_erros"]; ?></strong>
                        <?php
                        foreach ($adicionar["erros"] as $ind => $val) {
                            ?>
                            <br />
                            <?php 
                            echo $idioma[$val];
                        }
                        ?>
                    </div>
                    <?php
                }

                foreach($matriculas as $matricula) { 
                    $matriculaObj->Set("id", $matricula["idmatricula"]);
                    $documentos = $matriculaObj->RetornarDocumentos();
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
                                                <h1><?= $matricula['curso']; ?></h1>
                                                <p><?= $idioma['carga_horaria']; ?> <strong><?= $matricula['carga_horaria_total']; ?></strong></p>
                                                <p><?= $idioma['matricula']; ?> <strong><?= $matricula['idmatricula']; ?></strong></p>
                                                <p><?= $idioma['andamaento_curso']; ?> <strong><?= number_format($matricula['porcentagem'],2,',','.'); ?>%</strong></p>
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
                                                <td align="center"><?= $idioma['documentos_matricula_tipo']; ?></td>
                                                <td align="center"><?= $idioma['documentos_matricula_pessoa']; ?></td>
                                                <td align="center"><?= $idioma['documentos_matricula_arquivo']; ?></td>
                                                <td align="center"><?= $idioma['documentos_matricula_opcoes']; ?></td>
                                                <td align="center"><?= $idioma['documentos_matricula_situacao']; ?></td>
                                                <td align="center"><?= $idioma['documentos_matricula_motivo']; ?></td>
                                            </tr>
                                        </thead>
                                        <?php
                                        if(count($documentos)) {
                                            ?>
                                            <tbody class="a-table">
                                                <?php 
												foreach($documentos as $ind => $var) { 
													?>
                                                    <tr>
                                                        <td>
															<?= $var['tipo']; ?>
                                                        </td>
                                                        <td>
                                                            <?= ($var["associacao"]) ? $var["associacao"] : $idioma["aluno"]; ?>
                                                        </td>
                                                        <td>
                                                            <span id="mensagem_retorno">
                                                                <?= $var["arquivo_nome"]; ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            if ($var["arquivo_nome"]) {
                                                                ?>
                                                                <?php
                                                                if(strpos($var['arquivo_tipo'],'image') !== false) {
                                                                    ?>
                                                                    <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/visualizardocumento/<?= $var["iddocumento"]; ?>/<?= $matricula["idmatricula"]; ?>" class="fancybox btn btn-mini" rel="gallery" title="<?= $var["tipo"].' ('.$var["arquivo_nome"].')'; ?>">
                                                                        <i class="icon-picture"></i> <?= $idioma["documentos_matricula_visualizar"]; ?>
                                                                    </a>
                                                                    <?php
                                                                }
                                                                ?>
                                                                <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/downloaddocumento/<?= $var["iddocumento"]; ?>/<?= $matricula["idmatricula"]; ?>" class="btn btn-mini" rel="tooltip" data-original-title="<?= $idioma["documentos_matricula_download"]; ?>" data-placement="left">
                                                                    <i class="icon-download-alt"></i>
                                                                </a>
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <a href="javascript:enviarArquivo(<?= $var["iddocumento"]; ?>,<?= $matricula["idmatricula"]; ?>);" class="btn btn-mini" rel="tooltip" data-original-title="<?= $idioma["documentos_matricula_enviar"]; ?>" data-placement="left">
                                                                    <i class="icon-upload"></i> <?= $idioma["documentos_matricula_enviar"]; ?>
                                                                </a>
                                                                <?php
                                                            }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <div class="btn btn-mob" style="background: <?= $situacao_documento_cores[$var["situacao"]]; ?>;color:#FFFFFF;cursor:default;"><?= $situacao_documento[$config["idioma_padrao"]][$var["situacao"]]; ?></div>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            if ($var["situacao"] == 'reprovado') {
                                                                ?>
                                                                <a href="#reprovacao_motivo_<?= $var["iddocumento"]; ?>" class="btn btn-vermelho btn-mob abrirModal">
                                                                    <?= $idioma['documentos_matricula_ver_motivo']; ?>
                                                                </a>
                                                                <div class="modal hide fade text-side-two extra-align" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                                    <div class="row-fluid m-box" id="reprovacao_motivo_<?= $var["iddocumento"]; ?>" style="display:none;">
                                                                       <div class="row-fluid m-box">
                                                                        <div class="span12">
                                                                            <div class="row-fluid">
                                                                                <div class="span12">
                                                                                    <i class="closed-x" data-dismiss="modal"> <strong><?php echo $idioma['fechar']; ?></strong></i>
                                                                                    <h1><?= $idioma["documentos_matricula_motivo_reprovacao"]; ?></h1>
                                                                                    <p><?php echo $var['arquivo_nome'];?> </p>
                                                                                    <div class="message-box">
                                                                                        <div class="span12 box-gray extra-align no-margin">
                                                                                             <?= $var["descricao_motivo_reprovacao"]?>
                                                                                        </div>
                                                                                    </div>
                                                                       		  </div>
                                                                            </div>
                                                                 		  </div>
                                                                       </div>
                                                                      </div>
                                                                </div>
                                                                &nbsp;
                                                                <?php
                                                            } else {
                                                                echo '---';
                                                            }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                            </tbody>
										  <?php
                                        } else {
                                            ?>
                                            <table width="100%" border="0" cellspacing="1" cellpadding="5">
                                                <tbody class="b-table">
                                                    <tr>
                                                        <td>
                                                            <i><?= $idioma['nenhum_documento']; ?></i>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>  
                                            <?php
                                        }
                                        ?>
                                    </table>
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
<form action="" method="post" id="formEnviarArquivo" name="formEnviarArquivo" enctype="multipart/form-data">
    <input type="hidden" name="acao" value="enviar_documento" />
    <input type="hidden" name="matricula" id="matricula" value="" />
    <input type="hidden" name="iddocumento" id="iddocumento_enviar" value="" />
    <input type="file" id="documento_enviar" name="documento" style="display:none;" />
</form>
<script type="text/javascript">
    function enviarArquivo(id,idmatricula) {
        document.getElementById('iddocumento_enviar').value = id;
        document.getElementById('matricula').value = idmatricula;
        document.getElementById('documento_enviar').click();
    }
</script>

<!-- /Conteudo -->
<?php incluirLib("rodape", $config, $usuario); ?>
<script src="/assets/js/jquery.1.7.1.min.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-alert.js"></script>
<script src="/assets/js/validation.js"></script>
<link rel="stylesheet" href="/assets/plugins/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
<script type="text/javascript" src="/assets/plugins/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>

<script type="text/javascript">
function descerScroll() {  
	var objScrDiv = document.getElementById("divScroll");  
	objScrDiv.scrollTop = objScrDiv.scrollHeight;  
} 
jQuery.noConflict();
$(document).ready(function() {	
	// Support for AJAX loaded modal window.
	// Focuses on first input textbox after it loads the window.
	$('.abrirModal').click(function(e) {
		e.preventDefault();
		var url = $(this).attr('href');
		var atendimento = url.split('#');
		if (url.indexOf('#') == 0) { 
			$('<div class="modal hide fade text-side-two extra-align" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">'+$(url).html()+'</div>').modal().on('shown', function () { 
				descerScroll(); 
			}).on("hidden", function () { 
				$(this).remove(); 
			}).success(function() { 
					$('input:text:visible:first').focus();
			});
			//$(url).modal('open').on('shown', function () { descerScroll(); }).on("hidden", function () { $(this).remove(); });
		} 
	});
    $('.abrirModalAdicionar').click(function(e) {
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

     $('#documento_enviar').change(function(){
        $('#mensagem_retorno').html('Enviando...');
        $('#formEnviarArquivo').submit();
    });
});

jQuery(document).ready(function($) {
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

    $("span[rel*=tooltip]").tooltip({
        // live: true
    });
    
    $("a[rel*=tooltip]").tooltip({
        // live: true
    });
    
    $("button[rel*=tooltip]").tooltip({
        // live: true
    });

    $("img[rel*=tooltip]").tooltip({
        // live: true
    }); 

    $('a[rel*=facebox]').facebox();
});


</script>

<link rel="stylesheet" href="/assets/plugins/facebox/src/facebox.css" type="text/css" media="screen" />
<script src="/assets/plugins/facebox/src/facebox.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-tooltip.js"></script>

</body>
</html>