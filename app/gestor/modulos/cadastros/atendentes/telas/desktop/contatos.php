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
            <li><?php echo $linha["nome"]; ?> <span class="divider">/</span></li>
            <li class="active"><?php echo $idioma["contatos"]; ?></li>
            <span class="pull-right"
                  style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
        </ul>
    </section>
    <div class="row-fluid">
        <div class="span12">
            <div class="box-conteudo">
                <div class=" pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"
                                            class="btn btn-small"><i
                            class="icon-share-alt"></i> <?= $idioma["btn_sair"]; ?></a></div>
                <h2 class="tituloEdicao"><?= $linha["nome"]; ?></h2>

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


                            <form class="form-inline wellCinza" method="post">
                                <input type="hidden" id="acao" name="acao" value="adicionar_contato">
                                Tipo:
                                <select name="idtipo" id="idtipo" style="width:auto">
                                    <? foreach ($tiposArray as $ind => $val) { ?>
                                        <option value="<?= $val["idtipo"]; ?>"
                                                alt="<?= $val["mascara"] ?>"><?= $val["nome"]; ?></option>
                                    <? } ?>
                                </select>
                                &nbsp;
                                <input type="text" name="valor" id="valor" maxlength="200">
                                <?php /*?><button type="submit" class="btn"><?= $idioma["btn_adicionar"]; ?></button><?php */ ?>
                                <input type="submit" class="btn" value="<?= $idioma["btn_adicionar"]; ?>"/>
                            </form>


                            <form method="post" id="remover_contato" name="remover_contato">
                                <input type="hidden" id="acao" name="acao" value="remover_contato">
                                <input type="hidden" id="remover" name="remover" value="">
                            </form>
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th><?= $idioma["listagem_id"]; ?></th>
                                    <th><?= $idioma["listagem_tipo"]; ?></th>
                                    <th><?= $idioma["listagem_valor"]; ?></th>
                                    <th width="60"><?= $idioma["listagem_opcoes"]; ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if (count($associacoesArray) > 0) { ?>
                                    <?php foreach ($associacoesArray as $ind => $associacao) { ?>
                                        <tr>
                                            <td><?php echo $associacao["idcontato"]; ?></td>
                                            <td><?php echo $associacao["tipo"]; ?></td>
                                            <td>
												<span style="display:block; width:500px; word-wrap:break-word;">
												<?php echo $associacao["valor"]; ?>
												</span>
                                            </td>
                                            <td>
                                                <?php if ($perfil["permissoes"][$url[2] . "|6"]) { ?>
                                                    <a href="javascript:void(0);" class="btn btn-mini"
                                                       data-original-title="<?= $idioma["btn_remover"]; ?>"
                                                       data-placement="left" rel="tooltip"
                                                       onclick="remover(<?php echo $associacao["idcontato"]; ?>)"><i
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
                                        <td colspan="4"><?= $idioma["sem_informacao"]; ?></td>
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

    <script type="text/javascript">
        function remover(id) {
            confirma = confirm('<?= $idioma["confirmar_remocao"]; ?>');
            if (confirma) {
                document.getElementById("remover").value = id;
                document.getElementById("remover_contato").submit();
            }
        }

        $('#idtipo').change(function () {
            var select = document.getElementById('idtipo');
            var mas = select.options[select.selectedIndex].getAttribute('alt');
            if (mas) {
                if (mas == '(99) 9999-9999') {
                    $('#valor').focusout(function () {
                        var phone, element;
                        element = $(this);
                        element.unmask();
                        phone = element.val().replace(/\D/g, '');
                        if (phone.length > 10) {
                            element.mask("(99) 99999-999?9");
                        } else {
                            element.mask("(99) 9999-9999?9");
                        }
                    }).trigger('focusout');
                } else {
                    $("#valor").mask(mas);
                }
            } else
                $("#valor").unmask();
        });
    </script>
</div>
</body>
</html>