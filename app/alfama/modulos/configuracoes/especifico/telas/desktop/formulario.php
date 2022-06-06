<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib('head', $config, $usuario); ?>
<link rel="stylesheet" href="/assets/plugins/codemirror/lib/codemirror.css">
<link rel="stylesheet" href="/assets/plugins/codemirror/addon/lint/lint.css">
<script src="/assets/plugins/codemirror/lib/codemirror.js"></script>
<script src="/assets/plugins/codemirror/addon/lint/lint.js"></script>
<script src="/assets/plugins/codemirror/mode/xml/xml.js"></script>
<script src="/assets/plugins/codemirror/mode/javascript/javascript.js"></script>
<script src="/assets/plugins/codemirror/mode/css/css.js"></script>
<script src="/assets/plugins/codemirror/mode/htmlmixed/htmlmixed.js"></script>
<script src="/assets/plugins/codemirror/mode/clike/clike.js"></script>
<script src="/assets/plugins/codemirror/mode/php/php.js"></script>
<script src="/assets/plugins/codemirror/addon/selection/active-line.js"></script>
<script src="/assets/plugins/codemirror/addon/edit/matchbrackets.js"></script>
<script src="/assets/plugins/codemirror/addon/lint/php-parser.min.js"></script>
<script src="/assets/plugins/codemirror/addon/lint/php-lint.js"></script>
<style>
    .CodeMirror {
        font-size: 14px;
        height: 500px;
    }

    .ajuste-butoes {
        position: relative;
        right: 150px;
    }
</style>
</head>
<body>
    <?php incluirLib('topo', $config, $usuario); ?>
    <div class="container-fluid">
        <section id="global">
            <div class="page-header">
                <h1><?= $idioma['pagina_titulo']; ?> &nbsp;<small class="hidden-phone"><?= $idioma['pagina_subtitulo']; ?></small></h1>
            </div>
            <ul class="breadcrumb">
                <li><a href="/<?= $url[0]; ?>"><?= $idioma['nav_inicio']; ?></a> <span class="divider">/</span></li>
                <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>"><?= $idioma['nav_configuracoes']; ?></a> <span class="divider">/</span></li>
                <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"><?= $idioma['pagina_titulo']; ?></a> <span class="divider">/</span></li>
                <?php if ($url[4] == 'editar') { ?>
                    <li class="active"><?php echo $linha['nome']; ?></li>
                <?php
                } else {
                ?>
                    <li class="active"><?= $idioma['nav_formulario']; ?></li>
                <?php
                } ?>
                <span class="pull-right visible-desktop" style="padding-top:3px; color:#999"><?= $idioma['hora_servidor']; ?>  <?= date("d/m/Y H\hi"); ?></span>
            </ul>
        </section>
        <div class="row-fluid">
            <div class="span12">
                <div class="box-conteudo">
                    <h2 class="tituloEdicao">
                        <?= $config['tituloEmpresa']; ?>
                    </h2>
                    <div class="tabbable tabs-left">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_editar">
                                <h2 class="tituloOpcao"><?= $idioma['titulo_opcao'] ?></h2>
                                <?php
                                if ($escreveu) { ?>
                                    <div class="alert alert-success fade in">
                                        <a href="" class="close" data-dismiss="alert">×</a>
                                        <strong><?= $idioma['modificar_sucesso']; ?></strong>
                                    </div>
                                <?php
                                } ?>
                                <?php
                                if (count($salvar['erros']) > 0) { ?>
                                    <div class="alert alert-error fade in">
                                        <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                                        <strong><?= $idioma['form_erros']; ?></strong>
                                            <?php foreach ($salvar['erros'] as $ind => $val) { ?>
                                                <br />
                                                <?= $idioma[$val]; ?>
                                            <?php
                                            } ?>
                                        </strong>
                                    </div>
                                <?php
                                } ?>
                                <form onsubmit="return validarCodigo();" id="form" method="post" enctype="multipart/form-data" class="form-horizontal">
                                    <input name="acao" type="hidden" value="salvar" />
                                    <?php $acao_url = explode('?', $_SERVER['HTTP_REFERER']); ?>
                                    <input name="acao_url" type="hidden" value="<?=base64_encode($acao_url[1])?>" />
                                    <div id="code"></div>
                                    <textarea style="display:none;" name="dadosEspecifico" id="dadosEspecifico" cols="30" rows="20"><?= file_get_contents(DIR_APP . '/especifico/inc/config.especifico.php') ?></textarea>
                                    <div class="form-actions">
                                        <input type="submit" class="btn btn-primary ajuste-butoes" value="<?= $idioma['btn_salvar']; ?>">&nbsp;
                                        <input type="reset" class="btn ajuste-butoes" onclick="MM_goToURL('parent','/<?= $url[0]; ?>/<?= $url[1]; ?>');" value="<?= $idioma['btn_cancelar']; ?>" />
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php incluirLib('rodape', $config, $usuario); ?>
    <script>
    window.onload = function() {
        editor = CodeMirror(document.getElementById("code"), {
            mode: "application/x-httpd-php-open",
            lineWrapping: true,
            lineNumbers: true,
            lint: {
                disableEval: true,
                disableExit: true,
                disablePHP7: false,
                disabledFunctions: ['proc_open', 'system'],
                deprecatedFunctions: ['']
            },
            gutters: ["CodeMirror-lint-markers"],
            styleActiveLine: true,
            matchBrackets: true,
            value: document.getElementById('dadosEspecifico').value
        });
    };

    function validarCodigo()
    {
        qtdErros = document.getElementsByClassName("CodeMirror-lint-marker-error").length;
        qtdAvisos = document.getElementsByClassName("CodeMirror-lint-marker-warning").length;
        if (qtdErros > 0 || qtdAvisos> 0) {
            alert("O código possui erros, por favor corrija e tente novamente.");
            return false;
        }
        document.getElementById('dadosEspecifico').value = editor.getValue();
        return true;
    }
    </script>
</body>
</html>
