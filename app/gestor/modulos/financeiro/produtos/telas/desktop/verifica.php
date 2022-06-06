<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php incluirLib("head", $config, $usuario); ?>
    <link media="screen" href="/assets/plugins/jquery.msg/jquery.msg.css" rel="stylesheet" type="text/css">
</head>
<body>
<? incluirLib("topo", $config, $usuario); ?>
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
            <li class="active"><?= $idioma["nav_formulario"]; ?></li>
            <span class="pull-right"
                  style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
        </ul>
    </section>
    <div class="row-fluid">
        <div class="span12">
            <div class="box-conteudo">

                <form action="" method="get" name="form_novo">
                    <fieldset>
                        <legend><?= $idioma["form_novo"]; ?></legend>
                        <label><?= $idioma["form_pergunta"]; ?></label>
                        <input name="nome" type="text" id="frm_nome" placeholder="<?= $idioma["form_input"]; ?>"
                               value="<?= $_GET["nome"]; ?>"
                               style="font-size: 30px; margin-bottom: 5px; width: 500px; line-height: 32px; height: 35px;">
                        <br/>
                        <button type="submit" class="btn"><?= $idioma["form_botao"]; ?></button>
                    </fieldset>
                </form>
                <script>
                    document.getElementById('frm_nome').focus();
                </script>

                <?
                if ($_GET["nome"]) {
                    ?>

                    <br/>
                    <?= $idioma["explicacao"]; ?>

                    <br/><br/>
                    <table border="0" cellspacing="0" cellpadding="5" class="table">
                        <thead>
                        <tr>
                            <th bgcolor="#F4F4F4"><?= $idioma["relacionado"]; ?></th>
                            <th bgcolor="#F4F4F4">&nbsp;</th>
                        </tr>
                        </thead>
                        <? foreach ($dadosArray as $linha) { ?>
                            <tr>
                                <td><strong>
                                        <?= $linha["nome"]; ?>
                                    </strong></td>
                                <td style="text-align:right" align="right"><a class="btn btn-mini"
                                                                              href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $linha["idproduto"]; ?>/editar"><?= $idioma["editar"]; ?></a>
                                </td>
                            </tr>
                        <? } ?>
                        <tr>
                            <td bgcolor="#FFFFCC"><strong>
                                    <?= $idioma["cadastrar_label"]; ?>
                                </strong></td>
                            <td bgcolor="#FFFFCC" style="text-align:right" align="right"><a
                                    class="btn btn-mini btn-primary" style="color:#FFF"
                                    href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/cadastrar?nome=<?= urlencode($_GET["nome"]); ?>"><?= $idioma["cadastrar"]; ?></a>
                            </td>
                        </tr>
                    </table>
                <? } ?>

            </div>
        </div>
    </div>
    <? incluirLib("rodape", $config, $usuario); ?>

</div>
</body>
</html>