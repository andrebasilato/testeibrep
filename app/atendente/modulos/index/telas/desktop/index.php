<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usu_vendedor); ?>
<style>
.blocoCinza {
    background-color: #F4F4F4;
    padding: 10px;
    -webkit-border-radius: 8px;
    -moz-border-radius: 8px;
    border-radius: 8px;
    border-bottom:1px #E4E4e4 solid;
    border-left:1px #E4E4e4 solid;
    margin-bottom:15px;
}
.blocoCinza a {
    height: 80px;
}
.blocoCinza:hover {
    background-color: #E4E4e4;
    border-bottom:1px #CCCCCC solid;
    border-left:1px #CCCCCC solid;
}
.blocoCinzaDestaque {
    background-color: #E4E4e4;
    padding: 10px;
    -webkit-border-radius: 8px;
    -moz-border-radius: 8px;
    border-radius: 8px;
    border-bottom:1px #CCCCCC solid;
    border-left:1px #CCCCCC solid;
    margin-bottom:15px;
}
.blocoCinzaDestaque a {
    height: 80px;
}
.blocoCinzaDestaque:hover {
    background-color: #CCCCCC;
    border-bottom:1px #999999 solid;
    border-left:1px #999999 solid;
}
</style>
<script type="text/javascript" src="/assets/plugins/highcharts/js/jquery.min.js"></script>
<script src="/assets/plugins/highcharts/js/highcharts.js"></script>
<script src="/assets/plugins/highcharts/js/modules/exporting.js"></script>
</head>
<body>
<?php incluirLib("topo",$config,$usu_vendedor); ?>
<div class="container-fluid">
    <section id="global">
        <div class="page-header">
            <h1>
                <?= $idioma["pagina_titulo"]; ?>
                &nbsp;<small>
                <?= $idioma["pagina_subtitulo"]; ?>
                </small>
            </h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="/<?= $url[0]; ?>">
            <?= $idioma["nav_inicio"]; ?>
            </a> </li>
            <span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?>
            <?= date("d/m/Y H\hi"); ?>
            </span>
        </ul>
    </section>
    <div class="row-fluid">
        <div class="span12">
            <div class="box-conteudo">
                <div class="row-fluid">
                    <div class="span8">
                        <?php
                        if (count($banners) > 0) {
                            foreach ($banners as $banner) { ?>
                                <div style="text-align:center; background-color:<?= $banner['cor_background']; ?>">
                                    <?php if ($banner['link']){ ?><a href="<?= $banner['link'] ?>" target="_blank"><?php } ?>
                                        <img src="/api/get/imagens/bannersavaaluno_imagem/x/x/<?= $banner["imagem_servidor"]; ?>" />
                                        <?php if ($banner['link']) { ?></a><?php } ?>
                                </div><br>
                            <?php } ?>
                        <?php } ?>
                        <div class="section-small">
                            <?php incluirLib("sidebar_mural",$config,$usu_vendedor); ?>
                        </div>
                        <div class='section section-small'>
                            <div class='section-header'>
                                <h5><?php echo $idioma['cursos']; ?></h5>
                            </div>
                            <div class='section-body'>
                                <div class='row-fluid'>
                                    <table class="table table-striped ">
                                        <thead>
                                            <tr>
                                                <th><?= $idioma["listagem_sindicato"]; ?></th>
                                                <th><?= $idioma["listagem_curso"]; ?></th>
                                                <th><?= $idioma["listagem_codigo"]; ?></th>
                                                <th><?= $idioma["listagem_tipo"]; ?></th>
                                                <th><?= $idioma["listagem_carga_horaria_total"]; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if(count($cursos) > 0) {
                                                foreach($cursos as $ind => $curso) {
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $curso["nome_abreviado"]; ?></td>
                                                        <td><?php echo $curso["nome"]; ?></td>
                                                        <td><?php echo $curso["codigo"]; ?></td>
                                                        <td><?php echo $curso["tipo"]; ?></td>
                                                        <td><?php echo $curso["carga_horaria_total"]; ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <tr>
                                                    <td colspan="7"><?= $idioma["sem_informacao"]; ?></td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="span4">
                        <?php
                        if($usu_vendedor['venda_bloqueada'] == 'N') {
                            ?>
                            <a class="btn btn-large btn-block btn-primary" href="/atendente/academico/matriculas/novamatricula" style="margin-bottom: 15px; width: 91%">Iniciar nova matrícula</a>
                            <?php
                        }
                        ?>
                        <?php incluirLib("sidebar_index",$config,$usu_vendedor); ?>
                        <?php //incluirLib("sidebar_relacionamento_proximas_acoes",$config,$usu_vendedor); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php incluirLib("rodape",$config,$usu_vendedor); ?>
</div>
</body>
</html>
