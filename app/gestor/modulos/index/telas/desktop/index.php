<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php

    incluirLib("head",$config,$usuario); ?>
    <script type="text/javascript" src="/assets/plugins/highcharts/js/jquery.min.js"></script>
    <script src="/assets/plugins/highcharts/js/highcharts.js"></script>
    <script src="/assets/plugins/highcharts/js/modules/exporting.js"></script>
    <script type="text/javascript">
        function filtrar(select) {
            document.getElementById('i').value = document.getElementById('i').options[document.getElementById('i').selectedIndex].value;
            document.getElementById('c').value = document.getElementById('c').options[document.getElementById('c').selectedIndex].value;
            document.getElementById('form_filtro').submit();
        }
    </script>
</head>
<body >
<?php incluirLib("topo",$config,$usuario); ?>
<div class="container-fluid">
    <section id="global">
        <div class="page-header">
            <h1>
                <?= $idioma["pagina_titulo"]; ?>
                &nbsp;<small>
                    <?= $idioma["pagina_subtitulo"]; ?>
                </small></h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="/<?= $url[0]; ?>">
                    <?= $idioma["nav_inicio"]; ?>
                </a> <span class="divider">/</span></li>
            <li>
                <?= $idioma["modulo"]; ?>
            </li>
            <span class="pull-right" style="color:#999">
      <?= $idioma["hora_servidor"]; ?>
      <?= date("d/m/Y H\hi"); ?>
      </span>
        </ul>
    </section>
    <div class="row-fluid">
        <div class="span9">
            <div class="box-conteudo" style="padding-bottom: 0px; margin-bottom: 10px;">

                Opções:
                <ul class="nav nav-pills">
                    <li><a href="/gestor/academico/matriculas">Matrículas</a></li>
                    <li><a href="/gestor/academico/matriculas/novamatricula">+ Nova matrícula</a></li>
                    <li><a href="/gestor/comercial/mapadealcance">Mapa de alcance</a></li>
                    <li><a href="/gestor/comercial/visitas">Visitas</a></li>
                    <li><a href="/gestor/comercial/visitas/cadastrar">+ Nova visita</a></li>
                    <li><a href="/gestor/financeiro/contas">Financeiro</a></li>
                    <li><a href="/gestor/financeiro/contas/cadastrar">+ Nova conta</a></li>
                </ul>

            </div>


            <div class="row-fluid">
                <div class="span12">
                    <?php incluirLib("sidebar_mural",$config,$usuario); ?>
                </div>
            </div>



            <div class="box-conteudo">

                <form action="/<?= $url[0]; ?>" method="get" id="form_filtro">
                    <select id="i" name="i" onchange="filtrar(this)">
                        <option value="">Todos os estados</option>
                        <optgroup label="Sindicatos">
                            <?php
                            $regioes = array();
                            foreach ($sindicatos as $sindicato) {
                                if($sindicato['idregiao']) {
                                    $regioes[$sindicato['idregiao']]['idregiao'] = $sindicato['idregiao'];
                                    $regioes[$sindicato['idregiao']]['nome'] = $sindicato['regiao'];
                                }
                                ?>
                                <option value="i|<?php echo $sindicato['idsindicato']; ?>" <?php if ($_GET['i'] == 'i|'.$sindicato['idsindicato']) echo 'selected="selected"'; ?> ><?php echo $sindicato['nome_abreviado']; ?></option>
                            <?php } ?>
                        </optgroup>
                        <?php if(count($regioes) > 0) { ?>
                            <optgroup label="Regiões">
                                <?php foreach ($regioes as $regiao) { ?>
                                    <option value="r|<?php echo $regiao['idregiao']; ?>" <?php if ($_GET['i'] == 'r|'.$regiao['idregiao']) echo 'selected="selected"'; ?> ><?php echo $regiao['nome']; ?></option>
                                <?php } ?>
                            </optgroup>
                        <?php } ?>
                    </select>
                    <select id="c" name="c" onchange="filtrar(this)">
                        <option value="">Todos os cursos</option>
                        <?php foreach ($cursosUsuario as $curso) { ?>
                            <option value="<?php echo $curso['idcurso']; ?>" <?php if ($_GET['c'] == $curso['idcurso']) echo 'selected="selected"'; ?> ><?php echo $curso['nome']; ?></option>
                        <?php } ?>
                    </select>
                </form>
                <br />
                <div class='section section-small'>
                    <div class='section-header'>
                        <h5> <?php echo $idioma['numeros_oraculo']; ?> </h5>
                    </div>
                    <div class='section-body'>
                        <div class='row-fluid'>
                            <div class='span1 ac numeros-oraculo'>
                                <div class='data-block'>
                                    <h1 class='success'><?php echo $matriculas; ?></h1>
                                    <h6 class='data-heading'><?php echo $idioma['matriculas']; ?></h6>
                                </div>
                            </div>
<!--                            <div class='span1 ac numeros-oraculo'>-->
<!--                                <div class='data-block'>-->
<!--                                    <h1 class='success'>--><?php //echo $ofertas; ?><!--</h1>-->
<!--                                    <h6 class='data-heading'>--><?php //echo $idioma['ofertas']; ?><!--</h6>-->
<!--                                </div>-->
<!--                            </div>-->
                            <!--<div class='span1 ac numeros-oraculo'>
                                <div class='data-block'>
                                    <h1 class='success'><?php /*echo $cursos; */?></h1>
                                    <h6 class='data-heading'><?php /*echo $idioma['cursos']; */?></h6>
                                </div>
                            </div>-->
                            <div class='span1 ac numeros-oraculo'>
                                <div class='data-block'>
                                    <h1 class='success'><?php echo $pessoas; ?></h1>
                                    <h6 class='data-heading'><?php echo $idioma['pessoas']; ?></h6>
                                </div>
                            </div>
                            <!--<div class='span1 ac numeros-oraculo'>
                                <div class='data-block'>
                                    <h1 class='success'><?php /*echo $professores; */?></h1>
                                    <h6 class='data-heading'><?php /*echo $idioma['professores']; */?></h6>
                                </div>
                            </div>
                            <div class='span1 ac numeros-oraculo'>
                                <div class='data-block'>
                                    <h1 class='success'><?php /*echo $atendimentos; */?></h1>
                                    <h6 class='data-heading'><?php /*echo $idioma['atendimentos']; */?></h6>
                                </div>
                            </div>-->
                            <div class='span1 ac numeros-oraculo'>
                                <a href="/gestor/academico/matriculas/detran_certificados">
                                    <div class='data-block'>
                                        <h1 class='success'><?php echo $certificadosNaoEnviados['total']; ?></h1>
                                        <h6 class='data-heading'><?php echo $idioma['matriculas_nao_enviadas']; ?></h6>
                                    </div>
                                </a>
                            </div>
                            <div class='span1 ac numeros-oraculo'>
                                <a href="/gestor/academico/matriculas/falha_biometrica?&q[1|rec.tipo_biometria]=DATAVALID">
                                    <div class='data-block'>
                                        <h1 class='success'><?php echo $matriculas_biometria['DATAVALID']; ?></h1>
                                        <h6 class='data-heading'><?php echo " DATAVALID"; ?></h6>
                                    </div>
                                </a>
                            </div>
                            <div class='span1 ac numeros-oraculo'>
                                <a href="/gestor/academico/matriculas/falha_biometrica?&q[1|rec.tipo_biometria]=AZURE">
                                    <div class='data-block'>
                                        <h1 class='success'><?php echo $matriculas_biometria['AZURE']; ?></h1>
                                        <h6 class='data-heading'><?php echo  " AZURE"; ?></h6>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span12">
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
            <?php incluirLib("sidebar_busca",$config); ?>
            <?php incluirLib("sidebar_quem_online",$config); ?>
            <?php incluirLib("sidebar_index",$config,$usuario); ?>
            <?php if($_SESSION['classico']) { ?>
                <div class='section section-small'> <a href="?classico=false" style="text-decoration:none;">
                        <div class='section-footer'> <font style="color:#555; text-transform:uppercase;">Acessar vers&atilde;o mobile</font> </div>
                    </a> </div>
            <?php } ?>
        </div>
    </div>
    <? incluirLib("rodape",$config,$usuario); ?>
</div>
</body>
</html>
