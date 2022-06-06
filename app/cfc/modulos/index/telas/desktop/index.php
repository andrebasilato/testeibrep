<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php incluirLib("head",$config,$usuario); ?>
    <script type="text/javascript" src="/assets/plugins/highcharts/js/jquery.min.js"></script>
    <script src="/assets/plugins/highcharts/js/highcharts.js"></script>
    <script src="/assets/plugins/highcharts/js/modules/exporting.js"></script>
</head>
<body >
<?php incluirLib("topo",$config,$usuario); ?>
<div class="container-fluid">
    <section id="global">
        <div class="page-header">
            <h1><?= $idioma["pagina_titulo"]; ?>&nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="/<?= $url[0]; ?>"><?= $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
            <li><?= $idioma["modulo"]; ?></li>
            <span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?><?= date("d/m/Y H\hi"); ?></span>
        </ul>
    </section>
    <div class="row-fluid">
        <div class="span9">
            <div class="box-conteudo">
                <div class="row-fluid">
                    <div class="span12">
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
                        <div>
                            <?php incluirLib("sidebar_mural",$config,$usuario); ?>
                        </div>
                        <div class='section section-small'>
                            <div class='section-header'>
                                <h5><?php echo $idioma['matriculas_vinte']; ?></h5>
                            </div>
                            <div class='section-body' style="text-align:center; background-color:#F4F4F4">
                                <script type="text/javascript">
                                    var chart;
                                    $(document).ready(function() {
                                        chart = new Highcharts.Chart({
                                            chart: {
                                                renderTo: 'matriculas',
                                                zoomType: 'xy',
                                                events: {
                                                    load: function() {
                                                        this.renderer.image('<?= $config["urlSistema"]; ?>/especifico/img/logo_empresa_peq.png', 0, 0, 150, 50).add();
                                                    }
                                                }
                                            },

                                            title: {
                                                text: '<?php echo $idioma['matriculas_vinte_quantidade']; ?>',
                                                align: 'right',
                                                x:-100
                                            },
                                            subtitle: {
                                                text: '<?php echo $idioma['com_total_matriculas']; ?>',
                                                align: 'right',
                                                x:-100
                                            },
                                            xAxis: [{
                                                min: 0,
                                                allowDecimals : false,
                                                categories: [<?php echo $datas_matriculas; ?>],
                                                labels: {
                                                    rotation: -45,
                                                    align: 'right',
                                                    style: {
                                                        font: 'normal 13px Verdana, sans-serif'
                                                    }
                                                }
                                            }],
                                            yAxis: [{ // Primary yAxis
                                                min: 0,
                                                allowDecimals : false,
                                                labels: {
                                                    formatter: function() {
                                                        return this.value +'';
                                                    },
                                                    style: {
                                                        color: '#4572A7'
                                                    }
                                                },
                                                title: {
                                                    text: '<?php echo $idioma['por_dia_matricula']; ?>',
                                                    style: {
                                                        color: '#4572A7'
                                                    }
                                                }
                                            }, { // Secondary yAxis
                                                title: {
                                                    text: '<?php echo $idioma['total_geral_matricula']; ?>',
                                                    style: {
                                                        color: '#89A54E'
                                                    }
                                                },
                                                labels: {
                                                    formatter: function() {
                                                        return this.value +'';
                                                    },
                                                    style: {
                                                        color: '#89A54E'
                                                    }
                                                },
                                                min: 0,
                                                opposite: true,
                                                allowDecimals: false
                                            }],
                                            tooltip: {
                                                formatter: function() {
                                                    return ''+
                                                        this.x +': '+ this.y + (this.series.name == '<?php echo $idioma['por_dia_matricula']; ?>' ? ' <?php echo $idioma['matricula_dia']; ?>' : ' <?php echo $idioma['matricula_total']; ?>');
                                                }
                                            },
                                            legend: {
                                                align: 'left',
                                                x: 170,
                                                verticalAlign: 'top',
                                                y: 0,
                                                floating: true,
                                                backgroundColor: '#FFFFFF'
                                            },
                                            series: [{
                                                name: '<?php echo $idioma['por_dia_matricula']; ?>',
                                                color: '#4572A7',
                                                type: 'column',
                                                yAxis: 0,
                                                data: [<?php echo $totais_matriculas; ?>],
                                                dataLabels: {
                                                    enabled: true,
                                                    rotation: -90,
                                                    color: '#FFFFFF',
                                                    align: 'right',
                                                    x: -3,
                                                    y: 10,
                                                    formatter: function() {
                                                        return this.y;
                                                    },
                                                    style: {
                                                        font: 'normal 13px Verdana, sans-serif'
                                                    }
                                                }
                                            }, {
                                                name: '<?php echo $idioma['total_geral_matricula']; ?>',
                                                color: '#89A54E',
                                                yAxis: 1,
                                                type: 'spline',
                                                data: [<?php echo $grafico_totais_matriculas; ?>]
                                            }]
                                        });
                                    });
                                </script>
                                <div id="matriculas" style="width: 100%; height: 400px; margin: 0 auto"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="span3">
            <?php if ($usuario['criar_matricula'] == 'S') { ?>
                <a class="btn btn-large btn-block btn-primary" href="/cfc/academico/matriculas/novamatricula" style="margin-bottom: 15px; width: 91%">Iniciar nova matrícula</a>
            <?php } ?>
            <?php incluirLib("sidebar_index",$config,$usuario); ?>
            <?php if($_SESSION['classico']) { ?>
                <div class='section section-small'> <a href="?classico=false" style="text-decoration:none;">
                        <div class='section-footer'> <font style="color:#555; text-transform:uppercase;">Acessar vers&atilde;o mobile</font> </div>
                    </a> </div>
            <?php } ?>
        </div>
    </div>
    <?php incluirLib("rodape",$config,$usuario); ?>
    <?php
    if( $_SESSION['cfc_aviso'] == true ){
        $idModal = "cfc-aviso";
        $imgAviso = "<a href=\"/cfc/financeiro/faturas\" ><img src=\"/especifico/img/cfc_aviso.png\" width=\"560\"></a>";
        echo getModalOraculo($idModal,"Aviso",$imgAviso);
    }
    ?>
</div>
<?php
if ($_SESSION['cfc_aviso']){
    ?>
    <script>
        $('#<?php echo $idModal; ?>').modal('show');
    </script>
    <?
}?>
</body>
</html>
