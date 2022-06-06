<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usuario); ?>
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
      
    <div class="box-conteudo">  

        <br />
        <div class='section section-small'>
          <div class='section-header'>
            <h5> <?php echo $idioma['numeros_orio']; ?> </h5>
          </div>
          <div class='section-body'>
            <div class='row-fluid'>
              <div class='span2 ac'>
                <div class='data-block'>
                  <h1 class='success'><?php echo $totalTransacoes; ?></h1>
                  <h6 class='data-heading'><?php echo $idioma['numero_transacoes_total']; ?></h6>
                </div>
              </div>
              <div class='span2 ac'>
                <div class='data-block'>
                  <h1 class='success'><?php echo $totalTransacoes_pendentes; ?></h1>
                  <h6 class='data-heading'><?php echo $idioma['numero_transacoes_pendentes']; ?></h6>
                </div>
              </div>
              <div class='span2 ac'>
                <div class='data-block'>
                  <h1 class='success'><?php echo $totalTransacoes_concluidas; ?></h1>
                  <h6 class='data-heading'><?php echo $idioma['numero_transacoes_concluidas']; ?></h6>
                </div>
              </div>
              <div class='span2 ac'>
                <div class='data-block'>
                  <h1 class='success'><?php echo $totalTransacoes_erros; ?></h1>
                  <h6 class='data-heading'><?php echo $idioma['numero_transacoes_erros']; ?></h6>
                </div>
              </div>
              <div class='span2 ac'>
                <div class='data-block'>
                  <h1 class='success'><?php echo $totalTransacoes_entrada; ?></h1>
                  <h6 class='data-heading'><?php echo $idioma['numero_transacoes_entrada']; ?></h6>
                </div>
              </div>
              <div class='span2 ac'>
                <div class='data-block'>
                  <h1 class='success'><?php echo $totalTransacoes_saida; ?></h1>
                  <h6 class='data-heading'><?php echo $idioma['numero_transacoes_saida']; ?></h6>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row-fluid">
          <div class="span12">
            <div class='section section-small'>
              <div class='section-header'>
                <h5><?php echo $idioma['transacoes_ultimas']; ?></h5>
              </div>
              <div class='section-body' style="text-align:center; background-color:#F4F4F4"> 
                <script type="text/javascript"> 
				  var chart;
					$(document).ready(function() {
						chart = new Highcharts.Chart({
							chart: {
								renderTo: 'transacoes',
								zoomType: 'xy',
								events: {
									load: function() {

									}
								}
							},
							
							title: {
								text: '<?php echo $idioma['transacoes_quantidade']; ?>',
								align: 'right',
								x:-100
							},
							subtitle: {
								text: '<?php echo $idioma['com_total_transacoes']; ?>',
								align: 'right',
								x:-100
							},
							xAxis: [{
								min: 0,
								allowDecimals : false,
								categories: [<?php echo $datas_transacoes; ?>],
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
									text: '<?php echo $idioma['por_dia_transacoes']; ?>',
									style: {
										color: '#4572A7'
									}
								}
							}, { // Secondary yAxis
								title: {
									text: '<?php echo $idioma['com_total_transacoes']; ?>',
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
										this.x +': '+ this.y + (this.series.name == '<?php echo $idioma['por_dia_transacoes']; ?>' ? ' <?php echo $idioma['transacoes_dia']; ?>' : ' <?php echo $idioma['transacoes_total']; ?>');
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
								name: '<?php echo $idioma['por_dia_transacoes']; ?>',								
								color: '#4572A7',
								type: 'column',
								yAxis: 0,
								data: [<?php echo $totais_transacoes; ?>],
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
								name: '<?php echo $idioma['total_geral_transacoes']; ?>',
								color: '#89A54E',
								yAxis: 1,
								type: 'spline',
								data: [<?php echo $grafico_totais_transacoes; ?>]
							}]
						});
					});		
                </script>
                <div id="transacoes" style="width: 100%; height: 400px; margin: 0 auto"></div>
              </div>
            </div>
          </div>
        </div>
        
      </div>
    </div>
    <div class="span3">
        <?php incluirLib("sidebar_index", $config,$usuario); ?>
    </div>
  </div>
  <? incluirLib("rodape",$config,$usuario); ?>
</div>
</body>
</html>