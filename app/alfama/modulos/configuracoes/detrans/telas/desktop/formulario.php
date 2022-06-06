<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php incluirLib('head', $config, $usuario); ?>
    <link media="screen" href="/assets/plugins/jquery.msg/jquery.msg.css" rel="stylesheet" type="text/css">
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.0/css/bootstrap-toggle.min.css" rel="stylesheet">
    <style type="text/css">
        .botao {
            height: 20px;
        }

        .toggle.btn {
            min-width: 59px !important;
            max-height: 25px !important;
            min-height: 25px !important;
            border: 1px solid #C5C5C5;
            box-sizing: border-box;
        }

        .toggle label.btn {
            padding-top: 4px;
        }
    </style>
<body>
<?php incluirLib('topo', $config, $usuario); ?>
<div class="container-fluid">
    <section id="global">
        <div class="page-header">
            <h1><?= $idioma['pagina_titulo']; ?> &nbsp;
                <small class="hidden-phone"><?= $idioma['pagina_subtitulo']; ?></small>
            </h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="/<?= $url[0]; ?>"><?= $idioma['nav_inicio']; ?></a> <span class="divider">/</span></li>
            <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>"><?= $idioma['nav_configuracoes']; ?></a>
                <span class="divider">/</span>
            </li>
            <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"><?= $idioma['pagina_titulo']; ?></a>
                <span class="divider">/</span>
            </li>
            <?php if ($url[4] == 'editar') { ?>
                <li class="active"><?php echo $linha['nome']; ?></li>
            <?php } else { ?>
                <li class="active"><?= $idioma['nav_formulario']; ?></li>
            <?php } ?>
            <span class="pull-right visible-desktop"
                  style="padding-top:3px; color:#999"><?php echo $idioma['hora_servidor']; ?><?php echo date("d/m/Y H\hi"); ?></span>
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
                            <?php if ($escreveu) { ?>
                                <div class="alert alert-success fade in">
                                    <a href="" class="close" data-dismiss="alert">×</a>
                                    <strong><?= $idioma['modificar_sucesso']; ?></strong>
                                </div>
                            <?php } ?>
                            <?php if (count($salvar['erros']) > 0) { ?>
                                <div class="alert alert-error fade in">
                                    <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                                    <strong><?php echo $idioma['form_erros']; ?></strong>
                                    <?php foreach ($salvar['erros'] as $ind => $val) {
                                        echo '<br/>' . $idioma[$val];
                                    } ?>
                                </div>
                            <?php } ?>
                            <form id="form" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <?php foreach ($estados as $estado) {

                                    if (method_exists($detranObj, "Certificado{$estado['sigla']}")) {
                                        $checked = $detranObj->obterSituacaoIntegracao((int)$estado['idestado']);?>
                                        <div class="row-fluid">
                                            <label for="mudarSituacaoEstado_<?php echo $estado['idestado'] ?>">
                                                <input class="btn btn-success <?php echo $checked ? 'toggle-on' : 'toggle-off'; ?>"
                                                       id="mudarSituacaoEstado_<?php echo $estado['idestado'] ?>"
                                                       <?php echo $checked ? 'checked="checked"' :''; ?>
                                                       type="checkbox" data-on="Sim" data-off="Não" data-size="normal"
                                                       data-onstyle="success" data-offstyle="danger"
                                                       data-toggle="toggle"
                                                       onchange="ativarEstado(<?php echo $estado['idestado']; ?>, this);"/>
                                                <?php echo $estado['nome'] ?>
                                            </label>
                                        </div>
                                    <?php }
                                } ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" charset="utf-8">
    function ativarEstado(id, campo) {
        var situacao_estado = 'N';
        if (campo.checked) {
            situacao_estado = 'S'
        }
        jQuery.ajax({
            url: "/<?php echo "{$url[0]}/{$url[1]}/{$url[2]}/json/alterar_estados"; ?>",
            dataType: "json",
            type: "POST",
            data: {idestado: id, situacao: situacao_estado},
            success: function (json) {
                if (json.sucesso) {
                    self.unblock();
                } else {
                    alert('<?php echo $idioma["json_erro"]; ?>');
                }
            }
        });
    }
</script>
<script type="text/javascript" src="/assets/js/jquery-1.7.1.min.js"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.0/js/bootstrap-toggle.min.js"></script>
<?php incluirLib('rodape', $config, $usuario); ?>
</body>
</html>
