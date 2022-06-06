<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php incluirLib("head", $config, $usuario); ?>
    <link rel="stylesheet" href="/assets/plugins/fcbkcomplete/style.css" type="text/css" media="screen"
          charset="utf-8"/>
</head>
<body>
<?php incluirLib("topo", $config, $usuario); ?>
<div class="container-fluid">
    <section id="global">
        <div class="page-header">
            <h1><?= $idioma["pagina_titulo"]; ?> &nbsp;
                <small><?= $idioma["pagina_subtitulo"]; ?></small>
            </h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="/<?= $url[0]; ?>"><?= $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
            <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>"><?= $idioma["nav_modulo"]; ?></a> <span
                    class="divider">/</span></li>
            <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"><?= $idioma["pagina_titulo"]; ?></a> <span
                    class="divider">/</span></li>
            <li class="active"><?php echo $linha["nome"]; ?></li>
            <span class="pull-right"
                  style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
        </ul>
    </section>
    <div class="row-fluid">
        <div class="span12">
            <div class="box-conteudo">
                <div class="pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"
                                           class="btn btn-small"><i
                            class="icon-share-alt"></i> <?= $idioma["btn_sair"]; ?></a></div>
                <h2 class="tituloEdicao"><?php echo $linha["nome"]; ?></h2>

                <div class="tabbable tabs-left">
                    <?php incluirTela("inc_menu_edicao", $config, $usuario); ?>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_editar">
                            <h2 class="tituloOpcao"><?= $idioma["titulo_opcao"]; ?></h2>

                            <div id="listagem_informacoes"><?= $idioma["texto_explicativo"]; ?></div>
                            <?php if ($_POST["msg"]) { ?>
                                <div class="alert alert-success fade in">
                                    <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                                    <strong><?php echo $idioma[$_POST["msg"]]; ?></strong>
                                </div>
                            <? } ?>
                            <?php if (count($salvar["erros"]) > 0) { ?>
                                <div class="alert alert-error fade in">
                                    <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                                    <strong><?= $idioma["form_erros"]; ?></strong>
                                    <? foreach ($salvar["erros"] as $ind => $val) { ?>
                                        <br/>
                                        <?php echo $idioma[$val]; ?>
                                    <? } ?>
                                </div>
                            <? } ?>
                            <form class="well form-inline" method="post">
                                <?php if ($perfil["permissoes"][$url[2] . "|11"]) { ?>
                                    <label class="checkbox">
                                        <input type="checkbox" value="S" id="gestor_cfc"
                                               name="gestor_cfc"
                                               <?php if ($linha["gestor_cfc"] == "S") { ?>checked="checked"<?php } ?>> <?= $idioma["form_gestor"]; ?>
                                    </label>
                                    <p class="help-block"><?= $idioma["form_gestor_ajuda"]; ?></p>
                                    <br/>
                                    <input type="hidden" id="acao" name="acao" value="gestor_cfc">
                                    <input type="submit" class="btn" value="<?= $idioma["btn_salvar"]; ?>"/>
                                <?php } else { ?>
                                    <label class="checkbox">
                                        <input type="checkbox" value="S" id="gestor_cfc"
                                               name="gestor_cfc"
                                               <?php if ($linha["gestor_cfc"] == "S") { ?>checked="checked"<?php } ?>
                                               disabled="disabled"> <?= $idioma["form_gestor"]; ?>
                                    </label>
                                    <p class="help-block"><?= $idioma["form_gestor_ajuda"]; ?></p>
                                    <br/>
                                    <a href="javascript:void(0);" class="btn disabled"
                                       data-original-title="<?= $idioma["btn_permissao_inserir"]; ?>"
                                       data-placement="right" rel="tooltip"><?php echo $idioma["btn_salvar"]; ?></a>
                                <?php } ?>
                            </form>
                            <form class="well" method="post">
                                <p><?= $idioma["form_sindicato"]; ?></p>
                                <?php if ($perfil["permissoes"][$url[2] . "|11"]) { ?>
                                    <select id="cfcs" name="cfcs"></select>
                                    <br/>
                                    <br/>
                                    <input type="hidden" id="acao" name="acao" value="associar_cfc">
                                    <input type="submit" class="btn" value="<?= $idioma["btn_adicionar"]; ?>"/>
                                <?php } else { ?>
                                    <select id="cfcs" name="cfcs" disabled="disabled"></select>
                                    <br/>
                                    <br/>
                                    <a href="javascript:void(0);" rel="tooltip"
                                       data-original-title="<?= $idioma["btn_permissao_inserir"]; ?>"
                                       data-placement="right" class="btn disabled"><?= $idioma["btn_adicionar"]; ?></a>
                                <?php } ?>
                            </form>
                            <br/>

                            <form method="post" id="remover_cfc" name="remover_cfc">
                                <input type="hidden" id="acao" name="acao" value="remover_cfc">
                                <input type="hidden" id="remover" name="remover" value="">
                            </form>
                            <table class="table table-striped tabelaSemTamanho">
                                <thead>
                                <tr>
                                    <th><?= $idioma["listagem_nome"]; ?></th>
                                    <th width="60"><?= $idioma["listagem_opcoes"]; ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if (count($cfcs) > 0) { ?>
                                    <?php foreach ($cfcs as $ind => $cfc) { ?>
                                        <tr>
                                            <td><?php echo $cfc["idcfc"]; ?>
                                                - <?php echo $cfc["nome_fantasia"]; ?></td>
                                            <td>
                                                <?php if ($perfil["permissoes"][$url[2] . "|12"]) { ?>
                                                    <a href="javascript:void(0);" class="btn btn-mini"
                                                       data-original-title="<?= $idioma["btn_remover"]; ?>"
                                                       data-placement="left" rel="tooltip"
                                                       onclick="remover(<?php echo $cfc["idusuario_cfc"]; ?>)"><i
                                                            class="icon-remove"></i></a>
                                                <?php } else { ?>
                                                    <a href="javascript:void(0);" class="btn btn-mini disabled"
                                                       data-original-title="<?= $idioma["btn_remover_permissao_excluir"]; ?>"
                                                       data-placement="left" rel="tooltip"><i
                                                            class="icon-remove"></i></a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                <?php } else { ?>
                                    <tr>
                                        <td colspan="3"><?= $idioma["sem_informacao"]; ?></td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <? incluirLib("rodape", $config, $usuario); ?>
    <script src="/assets/plugins/fcbkcomplete/jquery.fcbkcomplete.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#cfcs").fcbkcomplete({
                json_url: "/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/json/associar_cfc",
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
        });

        function remover(id) {
            confirma = confirm('<?= $idioma["confirmar_remocao"]; ?>');
            if (confirma) {
                document.getElementById("remover").value = id;
                document.getElementById("remover_cfc").submit();
            }
        }
    </script>
</div>
</body>
</html>