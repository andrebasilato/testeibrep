<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php incluirLib("head", $config, $usuario); ?>
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
    </section>
    <div class="row-fluid">
        <div class="span12">
            <div class="box-conteudo">
                <div id="listagem_informacoes">
                    <?= $idioma["pagina_subtitulo"]; ?>
                </div>
                <?php
                $funcionalidades = array();
                $i = 0;
                $linhaNumero = 0;
                $colunas = 2;
                foreach ($config['workflows'] as $ind => $val) {
                    $i++;
                    $funcionalidades[$linhaNumero][$ind] = $val;
                    if ($i == $colunas) {
                        $i = 0;
                        $linhaNumero++;
                    }
                }

                foreach ($funcionalidades as $linha => $pastas) {
                    ?>
                    <div class="row-fluid">
                        <?
                        foreach ($pastas as $ind => $val) {
                            ?>
                            <div class="span6 blocoFuncionalidade">
                                <a href="/gestor/configuracoes/workflows/<?= $ind; ?>">
                                    <div class="blocoImagem"><img src="/assets/icones/preto/32/workflows_32.png"></div>
                                    <div class="blocoLink"><?= strtoupper($val["titulo"]); ?></div>
                                </a>
                            </div>
                        <? } ?>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
    <?php incluirLib("rodape", $config, $usuario); ?>
</div>
</body>
</html>