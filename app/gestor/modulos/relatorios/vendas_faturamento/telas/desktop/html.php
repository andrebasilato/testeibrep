<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usuario); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css">
body, td, th {
    font-family: Verdana, Geneva, sans-serif;
    font-size: 10px;
    color: #000;
}
body {
    background-color: #FFF;
    background-image:none;
    padding-top: 0px;
    margin-left: 5px;
    margin-top: 5px;
    margin-right: 5px;
    margin-bottom: 5px;
}
a:link {
    color: #000;
    text-decoration: none;
}
a:visited {
    text-decoration: none;
    color: #000;
}
a:hover {
    text-decoration: underline;
    color: #000;
}
a:active {
    text-decoration: none;
    color: #000;
}
table.zebra-striped {
    padding: 0;
    font-size: 9px;
    border-collapse: collapse;
}
table.zebra-striped th, table td {
    padding: 8px 8px 7px;
    line-height: 16px;
    text-align: left;
}
table.zebra-striped th {
    padding-top: 9px;
    font-weight: bold;
    vertical-align: middle;
}
table.zebra-striped td {
    vertical-align: top;
    border: 1px solid #000;
}
table.zebra-striped tbody th {
    border-top: 1px solid #ddd;
    vertical-align: top;
}
.zebra-striped tbody tr:nth-child(odd) td, .zebra-striped tbody tr:nth-child(odd) th {
    background-color: #F4F4F4;
}
.zebra-striped tbody tr:hover td, .zebra-striped tbody tr:hover th {
    background-color: #E4E4E4;
}
</style>
<style type="text/css" media="print">
body, td, th {
    font-family: Verdana, Geneva, sans-serif;
    font-size: 9px;
    color: #000;
}
.impressao {
    display:none;
}
</style>

<script src="/assets/js/jquery.1.7.1.min.js"></script>
<script src="/assets/plugins/facebox/src/facebox.js"></script>
<script src="/assets/js/validation.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-tooltip.js"></script>
<script>
$(document).ready(function(){
    $('a[rel*=facebox]').facebox();
});
var regras = new Array();
regras.push("required,nome,<?php echo $idioma['nome_obrigatorio']; ?>");
</script>
</head>
<body>
<table width="100%" border="0" cellpadding="10" cellspacing="0">
  <tr>
    <td height="80"><table border="0" cellspacing="0" cellpadding="8">
      <tr>
        <td><a href="/<?= $url[0]; ?>" class="logo"></a><?php/*<img src="<?php echo $config['logo_pequena']; ?>" />*/?></td>
      </tr>
    </table></td>
    <td align="center"><h2><strong><?= $idioma["pagina_titulo"]; ?></strong></h2></td>
    <td  align="right" valign="top"><table border="0" align="right" cellpadding="3" cellspacing="0" class="impressao">
      <tr>
        <td><img src="/assets/img/print_24x24.png" width="24" height="24"></td>
        <td><a href="javascript:window.print();">
          <?= $idioma["imprimir"]; ?>
        </a></td>

        <td>
            <a class="btn" href="#link_salvar" rel="facebox" ><?php echo $idioma['salvar_relatorio'] ?></a>
            <div id="link_salvar" style="display:none;">
                <div style="width:300px;">
                <form method="post" onSubmit="return validateFields(this, regras)">
                    <input type="hidden" name="acao" value="salvar_relatorio" />
                    <label for="nome"><strong><?php echo $idioma['tabela_nome']; ?>:</strong></label>
                    <input type="text" class="input" name="nome" id="nome" style="height:30px;" /><br /><br />
                    <input type="submit" class="btn" value="<?php echo $idioma['salvar_relatorio'] ?>" />
                </form>
                </div>
            </div>
        </td>

      </tr>
    </table></td>
  </tr>
</table>

<? if($mensagem_sucesso) { ?>
    <div class="alert alert-success fade in">
        <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
        <strong><?= $idioma[$mensagem_sucesso]; ?></strong>
    </div>
<? } else if($mensagem_erro) { ?>
    <div class="alert alert-error fade in">
        <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
        <strong><?= $idioma[$mensagem_erro]; ?></strong>
    </div>
<? } ?>

<center><h1><?php echo $dadosArray['mes_nome'] . ' ' . $dadosArray['ano']; ?></h1></center>
<br />

<table border="1">
    <tr>
        <td style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;"><?php echo $dadosArray['mes_nome']; ?></strong></td>
        <td style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;"><?php echo 'Ano ' . $dadosArray['ano_anterior']; ?></strong></td>
        <td style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;"><?php echo 'Ano ' . $dadosArray['ano']; ?></strong></td>
        <td style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;"><?php echo 'Percentual em relação a ' . $dadosArray['ano_anterior']; ?></strong></td>
    </tr>
    <tr>
        <td style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;"><?php echo 'Matrículas'; ?></strong></td>
        <td><?php echo (int) $dadosArray['tabela_1'][$dadosArray['ano_anterior']]['quantidade']; ?></td>
        <td><?php echo (int) $dadosArray['tabela_1'][$dadosArray['ano']]['quantidade']; ?></td>
        <td><?php echo number_format($dadosArray['tabela_1']['porcentagem']['quantidade'], 2) . '%'; ?></td>
    </tr>
    <tr>
        <td style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;"><?php echo 'Faturamento'; ?></strong></td>
        <td>

        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:2px;">
          <tr>
            <td style="color:#CCCCCC; padding:2px;">R$</td>
            <td style="text-align:right; padding:2px;"><?php echo number_format($dadosArray['tabela_1'][$dadosArray['ano_anterior']]['valor'], 2, ',', '.'); ?></td>
          </tr>
        </table>

        </td>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:2px;">
          <tr>
            <td style="color:#CCCCCC; padding:2px;">R$</td>
            <td style="text-align:right; padding:2px;"><?php echo number_format($dadosArray['tabela_1'][$dadosArray['ano']]['valor'], 2, ',', '.'); ?></td>
          </tr>
        </table>          </td>
        <td><?php echo number_format($dadosArray['tabela_1']['porcentagem']['valor'], 2) . '%'; ?></td>
    </tr>
</table>

<br />
<h3><?php echo 'Detalhamento por Estado:'; ?></h3>
<br />

<table border="1">
    <tr>
        <td style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;"><?php echo 'Mês'; ?></strong></td>
        <td style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;"><?php echo ''; ?></strong></td>
        <td style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;"><?php echo 'Dias úteis'; ?></strong></td>
        <td style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;"><?php echo 'Dias trab'; ?></strong></td>
        <td style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;"><?php echo ''; ?></strong></td>


        <td style="background-color:#F0F0F0;" colspan="2"><strong style="text-transform:uppercase;"><?php echo 'Matrículas do dia'; ?></strong></td>

    </tr>
    <tr>
        <td><?php echo $dadosArray['mes_nome']; ?></td>
        <td><?php echo ''; ?></td>
        <td><?php echo $dadosArray['uteis']; ?></td>
        <td><?php echo $dadosArray['uteis_trabalhados']; ?></td>
        <td><?php echo '%'; ?></td>
        <td><?php echo 'Mat'; ?></td>
        <td><?php echo 'Valor'; ?></td>
    </tr>
    <tr>
        <td style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;"><?php echo 'Estado'; ?></strong></td>
        <td style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;"><?php echo 'Meta'; ?></strong></td>
        <td style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;"><?php echo 'Matrículas'; ?></strong></td>
        <td style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;"><?php echo 'R$'; ?></strong></td>
        <td style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;"><?php echo ''; ?></strong></td>
        <td style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;"><?php echo ''; ?></strong></td>
        <td style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;"><?php echo 'R$'; ?></strong></td>
    </tr>
    <?php foreach ($dadosArray['tabela_2']['estados'] as $estado) { ?>
        <tr>
            <td><?php echo $estado['estado']; ?></td>
            <td><?php echo (int) $estado['meta']; ?></td>
            <td><?php echo $estado['quantidade']; ?></td>
            <td><table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:2px;">
              <tr>
                <td style="color:#CCCCCC; padding:2px;">R$</td>
                <td style="text-align:right; padding:2px;"><?php echo number_format($estado['valor'], 2, ',', '.'); ?></td>
              </tr>
            </table>              </td>
            <td style="background-color:<? if($estado['porcentagem'] >= 100) { echo "#77DD77"; } else { echo "#FF6961"; } ?>"><?php echo number_format($estado['porcentagem'], 2); ?>%</td>
            <td><?php echo $estado['dia_atual']['quantidade']; ?></td>
            <td><?php echo number_format($estado['dia_atual']['valor'], 2, ',', '.'); ?></td>
        </tr>
    <?php } ?>
    <tr>
        <td style="background-color:#FFFFCC;"><?php echo 'TOTAL'; ?></td>
        <td style="background-color:#FFFFCC;"><?php echo (int) $dadosArray['tabela_2']['totais']['meta']; ?></td>
        <td style="background-color:#FFFFCC;"><?php echo $dadosArray['tabela_2']['totais']['quantidade']; ?></td>
        <td style="background-color:#FFFFCC;"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:2px;">
          <tr>
            <td style="color:#CCCCCC; padding:2px;">R$</td>
            <td style="text-align:right; padding:2px;"><?php echo number_format($dadosArray['tabela_2']['totais']['valor'], 2, ',', '.'); ?></td>
          </tr>
        </table>          </td>
        <?
            $porcentagem_total = ($dadosArray['tabela_2']['totais']['quantidade'] / $dadosArray['tabela_2']['totais']['meta']) * 100;
        ?>
        <td style="background-color:<? if($porcentagem_total >= 100) { echo "#77DD77"; } else { echo "#FF6961"; } ?>"><?php echo number_format($porcentagem_total, 2, ',', '.'); ?>%</td>
        <td><?php echo $dadosArray['tabela_2']['totais']['dia_atual']['quantidade']; ?></td>
        <td><?php echo number_format($dadosArray['tabela_2']['totais']['dia_atual']['valor'], 2, ',', '.'); ?></td>
    </tr>
    <tr>
        <td style="background-color:#FFFFCC;"><?php echo 'PROJETADO'; ?></td>
        <td style="background-color:#FFFFCC;"><?php echo ''; ?></td>
        <td style="background-color:#FFFFCC;"><?php echo $dadosArray['tabela_2']['projetado']['quantidade']; ?></td>
        <td style="background-color:#FFFFCC;"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:2px;">
          <tr>
            <td style="color:#CCCCCC; padding:2px;">R$</td>
            <td style="text-align:right; padding:2px;"><?php echo number_format($dadosArray['tabela_2']['projetado']['valor'], 2, ',', '.'); ?></td>
          </tr>
        </table>          </td>
        <td><?php echo ''; ?></td>
        <td><?php echo ''; ?></td>
        <td><?php echo ''; ?></td>
    </tr>
    <tr>
        <td><?php echo 'MÉDIA'; ?></td>
        <td><?php echo ''; ?></td>
        <td><?php echo number_format($dadosArray['tabela_2']['media']['quantidade'], 2, ',', '.'); ?></td>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:2px;">
          <tr>
            <td style="color:#CCCCCC; padding:2px;">R$</td>
            <td style="text-align:right; padding:2px;"><?php echo number_format($dadosArray['tabela_2']['media']['valor'], 2, ',', '.'); ?></td>
          </tr>
        </table>          </td>
        <td><?php echo ''; ?></td>
        <td><?php echo ''; ?></td>
        <td><?php echo ''; ?></td>
    </tr>
</table>

<br />

<table border="1">
    <tr>
        <td style="background-color:#F0F0F0; text-align:center;" colspan="4">
            <strong style="text-transform:uppercase;"><?php echo 'MÉDIA DIÁRIA POR ESTADOS'; ?></strong>
        </td>
    </tr>
    <tr>
        <td style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;"><?php echo ''; ?></strong></td>
        <td style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;"><?php echo 'Matrículas'; ?></strong></td>
        <td style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;"><?php echo 'Faturamento'; ?></strong></td>
        <td style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;"><?php echo 'Média'; ?></strong></td>
    </tr>
    <?php foreach ($dadosArray['tabela_3']['totais']['estados'] as $estado) { ?>
        <tr>
            <td style="background-color:#F0F0F0; font-weight:bold;"><?php echo $estado['estado']; ?></td>
            <td style="<?php if ($dadosArray['tabela_3']['totais']['quantidade_media'] > $estado['quantidade']) { ?> color:red; font-weight:bold; <?php } ?>" >
                <?php echo $estado['quantidade']; ?>
            </td>
            <td style="<?php if ($dadosArray['tabela_3']['totais']['valor_media'] > $estado['valor']) { ?> color:red; font-weight:bold; <?php } ?>" >
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:2px;">
                  <tr>
                    <td style="color:#CCCCCC; padding:2px;">R$</td>
                    <td style="text-align:right; padding:2px;"><?php echo number_format($estado['valor'], 2, ',', '.'); ?></td>
                  </tr>
              </table>

            </td>
            <td style="<?php if ($dadosArray['tabela_3']['totais']['unitario_media'] > $estado['valor_unitario']) { ?> color:red; font-weight:bold; <?php } ?>" >
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:2px;">
                  <tr>
                    <td style="color:#CCCCCC; padding:2px;">R$</td>
                    <td style="text-align:right; padding:2px;"><?php echo number_format($estado['valor_unitario'], 2, ',', '.'); ?></td>
                  </tr>
              </table>

            </td>
        </tr>
    <?php } ?>
    <tr>
        <td style="background-color:#F0F0F0; font-weight:bold;"><?php echo 'GERAL'; ?></td>
        <td><?php echo $dadosArray['tabela_3']['totais']['quantidade_media']; ?></td>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:2px;">
          <tr>
            <td style="color:#CCCCCC; padding:2px;">R$</td>
            <td style="text-align:right; padding:2px;"><?php echo number_format($dadosArray['tabela_3']['totais']['valor_media'], 2, ',', '.'); ?></td>
          </tr>
        </table>          </td>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:2px;">
          <tr>
            <td style="color:#CCCCCC; padding:2px;">R$</td>
            <td style="text-align:right; padding:2px;"><?php echo number_format($dadosArray['tabela_3']['totais']['unitario_media'], 2, ',', '.'); ?></td>
          </tr>
        </table>          </td>
    </tr>
</table>
<font><?php echo '* Grifo vermelhos nos valores abaixo da média geral.'; ?></font>
<br /><br />

<?php foreach ($dadosArray['tabela_3']['estados'] as $estado) { ?>
<table border="1">
    <tr>
        <td style="background-color:#F0F0F0; text-align:center;" colspan="5">
            <strong style="text-transform:uppercase;"><?php echo 'VENDAS DO DIA - ' . $estado['estado']; ?></strong>
        </td>
    </tr>
    <tr>
        <td style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;"><?php echo 'ATENDENTE'; ?></strong></td>
        <td style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;"><?php echo 'REGIÃO'; ?></strong></td>
        <td style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;"><?php echo 'MATRÍCULAS'; ?></strong></td>
        <td style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;"><?php echo 'FATURAMENTO'; ?></strong></td>
        <td style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;"><?php echo 'MÉDIA'; ?></strong></td>
    </tr>
    <?php foreach ($estado['vendedores'] as $vendedor) { ?>
        <tr>
            <td style="background-color:#F0F0F0; font-weight:bold;"><?php echo $vendedor['vendedor']; ?></td>
            <td style="background-color:#F0F0F0; font-weight:bold;"><?php echo $vendedor['regiao']; ?></td>
            <td><?php echo $vendedor['quantidade']; ?></td>
            <td><table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:2px;">
              <tr>
                <td style="color:#CCCCCC; padding:2px;">R$</td>
                <td style="text-align:right; padding:2px;"><?php echo number_format($vendedor['valor'], 2, ',', '.'); ?></td>
              </tr>
            </table>              </td>
            <td><table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:2px;">
              <tr>
                <td style="color:#CCCCCC; padding:2px;">R$</td>
                <td style="text-align:right; padding:2px;"><?php echo number_format($vendedor['diario_media'], 2, ',', '.'); ?></td>
              </tr>
            </table>              </td>
        </tr>
    <?php } ?>
</table>
<br />
<?php } ?>

<script type="text/javascript">
$(function () {
    var chart;
    $(document).ready(function() {
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'grafico_1',
                zoomType: 'xy',
                type: 'column'
            },
            title: {
                text:""
            },
            subtitle: {
                text: '% Matrículas em relação a META <?php echo $dadosArray['mes_nome'] . ' ' . $dadosArray['ano']; ?>',
                style: {
                    color: '#000000',
                    fontWeight: 'bold',
                    fontSize: 20
                }
            },
            xAxis: [{
                labels: {
                  //rotation: -45,
                  align: 'right',
                  style: {
                    font: 'normal 13px Verdana, sans-serif'
                  }
                },
                categories: [
                    <?php
                    foreach ($dadosArray['grafico_1']['estados'] as $estado) {
                        if(!$estado['estado'])
                            $estado['estado'] = " ";
                        echo '"' . $estado['estado'] . '", ';
                    }
                    ?>
                ]
            }],
            yAxis: [{
                labels: {
                    formatter: function() {
                        return this.value;// +'°C'
                    },
                    style: {
                        color: '#89A54E'
                    }
                },
                title: {
                    text: 'Total',
                    style: {
                        color: '#89A54E'
                    }
                },
                min: 0,
                allowDecimals : true,
            }],
            tooltip: {
                formatter: function() {
                    return ''+
                        this.x +': '+ this.y ;//+ (this.series.name == 'Matrículas' ? ' mm' : '°C')
                }
            },
            legend: {
                layout: 'vertical',
                align: 'left',
                x: 50,
                verticalAlign: 'top',
                y: 50,
                floating: true,
                backgroundColor: '#FFFFFF',
            },
            series: [{
                dataLabels: {
                    enabled: true,
                    //rotation: -90,
                    color: '#000000',
                    align: 'right',
                    x: -2,
                    y: -10,
                    allowDecimals : true,
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif',
                        textShadow: '0 0 0px black'
                    },
                    formatter: function() {
                        return Highcharts.numberFormat(this.y, 2, ',', '.') + '%';
                    },
                },
                name: 'Matrículas',
                data: [
                    <?php foreach ($dadosArray['grafico_1']['estados'] as $estado) {
                        if(!$estado['porcentagem'])
                            $estado['porcentagem'] = 0.0;
                        if($estado['estado'] == 'Ideal') {
                            echo '{y: ' . $estado['porcentagem'] . ', color:"#990033"}, ';
                        } else {
                            echo $estado['porcentagem'] . ', ';
                        }
                    } ?>
                ]

            }/*, {
                dataLabels: {
                    enabled: true,
                    rotation: -90,
                    color: '#FFFFFF',
                    align: 'right',
                    x: -2,
                    y: 2,
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif',
                        textShadow: '0 0 0px black'
                    }
                },
                name: 'Vendas',
                data: [1,2,3,4,5,6,7]

            }*/]
        });
    });

});
</script>
<div id="grafico_1" style="width: 75%; height: 400px; margin: 0 auto;"></div>

<script type="text/javascript">
$(function () {
    var chart;
    $(document).ready(function() {
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'grafico_2',
                zoomType: 'xy',
                type: 'column'
            },
            title: {
                text:""
            },
            subtitle: {
                text: '% Faturamento em relação a  <?php echo $dadosArray['ano_anterior'] . ' ' . $dadosArray['mes_nome'] . ' ' . $dadosArray['ano']; ?>',
                style: {
                    color: '#000000',
                    fontWeight: 'bold',
                    fontSize: 20
                }
            },
            xAxis: [{
                labels: {
                  //rotation: -45,
                  align: 'right',
                  style: {
                    font: 'normal 13px Verdana, sans-serif'
                  }
                },
                categories: [
                    <?php
                    foreach ($dadosArray['grafico_2']['estados'] as $estado) {
                        if(!$estado['estado'])
                            $estado['estado'] = " ";
                        echo '"' . $estado['estado'] . '", ';
                    }
                    ?>
                ]
            }],
            yAxis: [{
                labels: {
                    formatter: function() {
                        return this.value;// +'°C'
                    },
                    style: {
                        color: '#89A54E'
                    }
                },
                title: {
                    text: 'Total',
                    style: {
                        color: '#89A54E'
                    }
                },
                min: 0,
                allowDecimals : true,
            }],
            tooltip: {
                formatter: function() {
                    return ''+
                        this.x +': '+ this.y ;//+ (this.series.name == 'Matrículas' ? ' mm' : '°C')
                }
            },
            legend: {
                layout: 'vertical',
                align: 'left',
                x: 50,
                verticalAlign: 'top',
                y: 50,
                floating: true,
                backgroundColor: '#FFFFFF',
            },
            series: [{
                dataLabels: {
                    enabled: true,
                    //rotation: -90,
                    color: '#000000',
                    align: 'right',
                    x: -2,
                    y: -10,
                    allowDecimals : true,
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif',
                        textShadow: '0 0 0px black'
                    },
                    formatter: function() {
                        return Highcharts.numberFormat(this.y, 2, ',', '.') + '%';
                    },
                },
                name: 'Matrículas',
                data: [
                    <?php foreach ($dadosArray['grafico_2']['estados'] as $estado) {
                        if(!$estado['porcentagem'])
                            $estado['porcentagem'] = 0.0;
                        if($estado['estado'] == 'Ideal') {
                            echo '{y: ' . $estado['porcentagem'] . ', color:"#990033"}, ';
                        } else {
                            echo $estado['porcentagem'] . ', ';
                        }
                    } ?>
                ]

            }/*, {
                dataLabels: {
                    enabled: true,
                    rotation: -90,
                    color: '#FFFFFF',
                    align: 'right',
                    x: -2,
                    y: 2,
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif',
                        textShadow: '0 0 0px black'
                    }
                },
                name: 'Vendas',
                data: [1,2,3,4,5,6,7]

            }*/]
        });
    });

});
</script>
<div id="grafico_2" style="width: 75%; height: 400px; margin: 0 auto;"></div>

<script type="text/javascript">
$(function () {
    var chart;
    $(document).ready(function() {
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'grafico_3',
                zoomType: 'xy',
                type: 'column'
            },
            title: {
                text:""
            },
            subtitle: {
                text: '% Comparativo Matrículas <?php echo $dadosArray['mes_nome'] . ' ' . $dadosArray['ano'] . ' / META'; ?>',
                style: {
                    color: '#000000',
                    fontWeight: 'bold',
                    fontSize: 20
                }
            },
            xAxis: [{
                labels: {
                  //rotation: -45,
                  align: 'right',
                  style: {
                    font: 'normal 13px Verdana, sans-serif'
                  }
                },
                categories: [
                    <?php
                    foreach ($dadosArray['grafico_3']['estados'] as $estado) {
                        if(!$estado['estado'])
                            $estado['estado'] = " ";
                        echo '"' . $estado['estado'] . '", ';
                    }
                    ?>
                ]
            }],
            yAxis: [{
                labels: {
                    formatter: function() {
                        return this.value;// +'°C'
                    },
                    style: {
                        color: '#89A54E'
                    }
                },
                title: {
                    text: 'Total',
                    style: {
                        color: '#89A54E'
                    }
                },
                min: 0,
                allowDecimals : true,
            }],
            tooltip: {
                formatter: function() {
                    return ''+
                        this.x +': '+ this.y ;//+ (this.series.name == 'Matrículas' ? ' mm' : '°C')
                }
            },
            legend: {
                layout: 'vertical',
                align: 'left',
                x: 50,
                verticalAlign: 'top',
                y: 50,
                floating: true,
                backgroundColor: '#FFFFFF',
            },
            series: [{
                dataLabels: {
                    enabled: true,
                    //rotation: -90,
                    color: '#000000',
                    align: 'right',
                    x: -2,
                    y: -10,
                    allowDecimals : true,
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif',
                        textShadow: '0 0 0px black'
                    },
                    formatter: function() {
                        return this.y;
                    },
                },
                name: 'Meta',
                data: [
                    <?php
                    foreach ($dadosArray['grafico_3']['estados'] as $estado) {
                        if(!$estado['meta'])
                            $estado['meta'] = 0;
                        echo $estado['meta'] . ', ';
                    }
                    ?>
                ]

            }, {
                dataLabels: {
                    enabled: true,
                    //rotation: -90,
                    color: '#000000',
                    align: 'right',
                    x: -2,
                    y: -10,
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif',
                        textShadow: '0 0 0px black'
                    }
                },
                name: '<?php echo $dadosArray['ano']; ?>',
                data: [
                    <?php
                    foreach ($dadosArray['grafico_3']['estados'] as $estado) {
                        if(!$estado['quantidade'])
                            $estado['quantidade'] = 0;
                        echo $estado['quantidade'] . ', ';
                    }
                    ?>
                ]

            }]
        });
    });

});
</script>
<div id="grafico_3" style="width: 75%; height: 400px; margin: 0 auto;"></div>

<script type="text/javascript">
$(function () {
    var chart;
    $(document).ready(function() {
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'grafico_4',
                zoomType: 'xy',
                type: 'column'
            },
            title: {
                text:""
            },
            subtitle: {
                text: '% Comparativo Faturamento <?php echo $dadosArray['mes_nome'] . ' ' . $dadosArray['ano'] . ' / ' . $dadosArray['ano_anterior']; ?>',
                style: {
                    color: '#000000',
                    fontWeight: 'bold',
                    fontSize: 20
                }
            },
            xAxis: [{
                labels: {
                  //rotation: -45,
                  align: 'right',
                  style: {
                    font: 'normal 13px Verdana, sans-serif'
                  }
                },
                categories: [
                    <?php
                    foreach ($dadosArray['grafico_4']['estados'] as $estado) {
                        if(!$estado['estado'])
                            $estado['estado'] = " ";
                        echo '"' . $estado['estado'] . '", ';
                    }
                    ?>
                ]
            }],
            yAxis: [{
                labels: {
                    formatter: function() {
                        return Highcharts.numberFormat(this.value, 2, ',', '.');
                    },
                    style: {
                        color: '#89A54E'
                    }
                },
                title: {
                    text: 'Total',
                    style: {
                        color: '#89A54E'
                    }
                },
                min: 0,
                allowDecimals : true,
            }],
            tooltip: {
                formatter: function() {
                    return ''+
                        this.x +': '+ this.y ;//+ (this.series.name == 'Matrículas' ? ' mm' : '°C')
                }
            },
            legend: {
                layout: 'vertical',
                align: 'left',
                x: 50,
                verticalAlign: 'top',
                y: 50,
                floating: true,
                backgroundColor: '#FFFFFF',
            },
            series: [{
                dataLabels: {
                    enabled: true,
                    //rotation: -90,
                    color: '#000000',
                    align: 'right',
                    x: -2,
                    y: -10,
                    allowDecimals : true,
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif',
                        textShadow: '0 0 0px black'
                    },
                    formatter: function() {
                        return Highcharts.numberFormat(this.y, 2, ',', '.');
                    },
                },
                name: '<?php echo $dadosArray['ano_anterior']; ?>',
                data: [
                    <?php
                    foreach ($dadosArray['grafico_4']['estados'] as $estado) {
                        if(!$estado['valor_ano_anterior'])
                            $estado['valor_ano_anterior'] = 0.0;
                        echo $estado['valor_ano_anterior'] . ', ';
                    }
                    ?>
                ]

            }, {
                dataLabels: {
                    enabled: true,
                    //rotation: -90,
                    color: '#000000',
                    align: 'right',
                    x: -2,
                    y: -10,
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif',
                        textShadow: '0 0 0px black'
                    },
                    formatter: function() {
                        return Highcharts.numberFormat(this.y, 2, ',', '.');
                    },
                },
                name: '<?php echo $dadosArray['ano']; ?>',
                data: [
                    <?php
                    foreach ($dadosArray['grafico_4']['estados'] as $estado) {
                        if(!$estado['valor'])
                            $estado['valor'] = 0.0;
                        echo $estado['valor'] . ', ';
                    }
                    ?>
                ]

            }]
        });
    });

});
</script>
<div id="grafico_4" style="width: 75%; height: 400px; margin: 0 auto;"></div>

<script type="text/javascript">
$(function () {
    var chart;
    $(document).ready(function() {
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'grafico_5',
                zoomType: 'xy',
                type: 'column'
            },
            title: {
                text:""
            },
            subtitle: {
                text: '% Comparativo acumulado de Matrículas - dias trabalhados no ano',
                style: {
                    color: '#000000',
                    fontWeight: 'bold',
                    fontSize: 20
                }
            },
            xAxis: [{
                labels: {
                  //rotation: -45,
                  align: 'right',
                  style: {
                    font: 'normal 13px Verdana, sans-serif'
                  }
                },
                categories: [
                    <?php
                    foreach ($dadosArray['grafico_5_6']['estados'] as $estado) {
                        if(!$estado['estado'])
                            $estado['estado'] = " ";
                        echo '"' . $estado['estado'] . '", ';
                    }
                    ?>
                ]
            }],
            yAxis: [{
                labels: {
                    formatter: function() {
                        return this.value;// +'°C'
                    },
                    style: {
                        color: '#89A54E'
                    }
                },
                title: {
                    text: 'Total',
                    style: {
                        color: '#89A54E'
                    }
                },
                min: 0,
                allowDecimals : true,
            }],
            tooltip: {
                formatter: function() {
                    return ''+
                        this.x +': '+ this.y ;//+ (this.series.name == 'Matrículas' ? ' mm' : '°C')
                }
            },
            legend: {
                layout: 'vertical',
                align: 'left',
                x: 50,
                verticalAlign: 'top',
                y: 50,
                floating: true,
                backgroundColor: '#FFFFFF',
            },
            series: [{
                dataLabels: {
                    enabled: true,
                    //rotation: -90,
                    color: '#000000',
                    align: 'right',
                    x: -2,
                    y: -10,
                    allowDecimals : true,
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif',
                        textShadow: '0 0 0px black'
                    },
                    /*formatter: function() {
                        return Highcharts.numberFormat(this.y, 2, ',', '.');
                    },*/
                },
                name: '<?php echo $dadosArray['ano_anterior']; ?>',
                data: [
                    <?php
                    foreach ($dadosArray['grafico_5_6']['estados'] as $estado) {
                        if(!$estado['quantidade_ano_anterior'])
                            $estado['quantidade_ano_anterior'] = 0;
                        echo $estado['quantidade_ano_anterior'] . ', ';
                    }
                    ?>
                ]

            }, {
                dataLabels: {
                    enabled: true,
                    //rotation: -90,
                    color: '#000000',
                    align: 'right',
                    x: -2,
                    y: -10,
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif',
                        textShadow: '0 0 0px black'
                    },
                    /*formatter: function() {
                        return Highcharts.numberFormat(this.y, 2, ',', '.');
                    },*/
                },
                name: '<?php echo $dadosArray['ano']; ?>',
                data: [
                    <?php
                    foreach ($dadosArray['grafico_5_6']['estados'] as $estado) {
                        if(!$estado['quantidade'])
                            $estado['quantidade'] = 0;
                        echo $estado['quantidade'] . ', ';
                    }
                    ?>
                ]

            }]
        });
    });

});
</script>
<div id="grafico_5" style="width: 75%; height: 400px; margin: 0 auto;"></div>

<script type="text/javascript">
$(function () {
    var chart;
    $(document).ready(function() {
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'grafico_6',
                zoomType: 'xy',
                type: 'column'
            },
            title: {
                text:""
            },
            subtitle: {
                text: '% Comparativo acumulado Faturamento - dias trabalhados no ano',
                style: {
                    color: '#000000',
                    fontWeight: 'bold',
                    fontSize: 20
                }
            },
            xAxis: [{
                labels: {
                  //rotation: -45,
                  align: 'right',
                  style: {
                    font: 'normal 13px Verdana, sans-serif'
                  }
                },
                categories: [
                    <?php
                    foreach ($dadosArray['grafico_5_6']['estados'] as $estado) {
                        if(!$estado['estado'])
                            $estado['estado'] = " ";
                        echo '"' . $estado['estado'] . '", ';
                    }
                    ?>
                ]
            }],
            yAxis: [{
                labels: {
                    formatter: function() {
                        return Highcharts.numberFormat(this.value, 2, ',', '.');
                    },
                    style: {
                        color: '#89A54E'
                    }
                },
                title: {
                    text: 'Total',
                    style: {
                        color: '#89A54E'
                    }
                },
                min: 0,
                allowDecimals : true,
            }],
            tooltip: {
                formatter: function() {
                    return ''+
                        this.x +': '+ this.y ;//+ (this.series.name == 'Matrículas' ? ' mm' : '°C')
                }
            },
            legend: {
                layout: 'vertical',
                align: 'left',
                x: 50,
                verticalAlign: 'top',
                y: 50,
                floating: true,
                backgroundColor: '#FFFFFF',
            },
            series: [{
                dataLabels: {
                    enabled: true,
                    //rotation: -90,
                    color: '#000000',
                    align: 'right',
                    x: -2,
                    y: -10,
                    allowDecimals : true,
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif',
                        textShadow: '0 0 0px black'
                    },
                    formatter: function() {
                        return Highcharts.numberFormat(this.y, 2, ',', '.');
                    },
                },
                name: '<?php echo $dadosArray['ano_anterior']; ?>',
                data: [
                    <?php
                    foreach ($dadosArray['grafico_5_6']['estados'] as $estado) {
                        if(!$estado['valor_ano_anterior'])
                            $estado['valor_ano_anterior'] = 0;
                        echo $estado['valor_ano_anterior'] . ', ';
                    }
                    ?>
                ]

            }, {
                dataLabels: {
                    enabled: true,
                    //rotation: -90,
                    color: '#000000',
                    align: 'right',
                    x: -2,
                    y: -10,
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif',
                        textShadow: '0 0 0px black'
                    },
                    formatter: function() {
                        return Highcharts.numberFormat(this.y, 2, ',', '.');
                    },
                },
                name: '<?php echo $dadosArray['ano']; ?>',
                data: [
                    <?php
                    foreach ($dadosArray['grafico_5_6']['estados'] as $estado) {
                        if(!$estado['valor'])
                            $estado['valor'] = 0;
                        echo $estado['valor'] . ', ';
                    }
                    ?>
                ]

            }]
        });
    });

});
</script>
<div id="grafico_6" style="width: 75%; height: 400px; margin: 0 auto;"></div>

<script src="/assets/plugins/highcharts/js/highcharts.js"></script>

<table width="100%" border="0" align="center" cellpadding="10" cellspacing="0">
  <tr>
    <td valign="top"><span style="color:#999999;"><?= $idioma["rodape"]; ?></span></td>
    <td align="right" valign="top"><div align="right"><a href="/<?= $url[0]; ?>" class="logo"></a></div><?php/*<img src="/assets/img/logo_pequena.png" width="135" height="50" align="right">*/?></td>
  </tr>
</table>
</body>
</html>