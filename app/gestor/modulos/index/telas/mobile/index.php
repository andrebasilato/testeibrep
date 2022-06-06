<?

// Correcoes dos graficos para os dez dias.

$totais_reservas = explode(",",$totais_reservas); 
$datas_reservas = explode(",",$datas_reservas); 
$grafico_totais_reservas = explode(",",$grafico_totais_reservas); 
array_splice($totais_reservas,0, 13);
array_splice($datas_reservas,0, 13);
array_splice($grafico_totais_reservas,0, 13);
$totais_reservas = join(",",$totais_reservas); 
$datas_reservas = join(",",$datas_reservas); 
$grafico_totais_reservas = join(",",$grafico_totais_reservas);


$grafico_totais = explode(",",$grafico_totais); 
$datas_vendas = explode(",",$datas_vendas); 
$totais_vendas = explode(",",$totais_vendas); 
array_splice($grafico_totais,0, 13);
array_splice($datas_vendas,0, 13);
array_splice($totais_vendas,0, 13);
$grafico_totais = join(",",$grafico_totais); 
$datas_vendas = join(",",$datas_vendas); 
$totais_vendas = join(",",$totais_vendas);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <?php incluirLib("head",$config,$usuario); ?>
  <script type="text/javascript" src="/assets/plugins/highcharts/js/jquery.min.js"></script>
  <script src="/assets/plugins/highcharts/js/highcharts.js"></script>
</head>
<body>
<?php incluirLib("topo",$config,$usuario); ?>
<div class="container-fluid">
  <section id="global">
    <div class="page-header">
      <h1><?= $idioma["pagina_titulo"]; ?>&nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1>
    </div>
    
  </section>
  <div class="row-fluid">
    <div class="span12">
      <div class="box-conteudo">
        <div class="row-fluid">
          <div class="span12">
          
          
<div class='section section-small'>
            <div class='section-header'>
              <h5> <?php echo $idioma['numeros_oraculo']; ?> </h5>
            </div>
            <div class='section-body'>
              <div class='row-fluid'>
              	<div class='span6 ac'>
                  <div class='data-block'>
                    <h1 class='success'><?php echo $matriculas; ?></h1>
                    <h6 class='data-heading'><?php echo $idioma['matriculas']; ?></h6>
                  </div>
                </div>
                <div class='span6 ac'>
                  <div class='data-block'>
                    <h1 class='success'><?php echo $ofertas; ?></h1>
                    <h6 class='data-heading'><?php echo $idioma['ofertas']; ?></h6>
                  </div>
                </div>
               </div>
               <div class='row-fluid'>                 
                <div class='span6 ac'>
                  <div class='data-block'>
                    <h1 class='success'><?php echo $cursos; ?></h1>
                    <h6 class='data-heading'><?php echo $idioma['cursos']; ?></h6>
                  </div>
                </div>
                <div class='span6 ac'>
                  <div class='data-block'>
                    <h1 class='success'><?php echo $pessoas; ?></h1>
                    <h6 class='data-heading'><?php echo $idioma['pessoas']; ?></h6>
                  </div>
                </div>
               </div>
               <div class='row-fluid'>                 
                 <div class='span6 ac'>
                  <div class='data-block'>
                    <h1 class='success'><?php echo $professores; ?></h1>
                    <h6 class='data-heading'><?php echo $idioma['professores']; ?></h6>
                  </div>
                </div>                                 
                <div class='span6 ac'>
                  <div class='data-block'>
                    <h1 class='success'><?php echo $atendimentos; ?></h1>
                    <h6 class='data-heading'><?php echo $idioma['atendimentos']; ?></h6>
                  </div>
                </div>                                  
              </div>
            </div>
          </div>
          

<?php incluirLib("sidebar_index",$config,$usuario); ?>          
          
          
          
          
            
          </div>
        </div>

	 
		
		<div class="row-fluid">
          <div class="span12">
            <div class='section section-small'>
              <div class='section-header'>
                <h5><?php echo $idioma['matriculas_sete']; ?></h5>
              </div>
              <div class='section-body' style="text-align:center; background-color:#F4F4F4">
				<script type="text/javascript"> 
				  var chart;
					$(document).ready(function() {
						chart = new Highcharts.Chart({
							chart: {
								renderTo: 'matriculas',
							},
							
							title: {
								text: '<?php echo $idioma['matriculas_sete_quantidade']; ?>',
								align: 'right',
								x:-500
							},
							xAxis: [{
								min: 0,
								allowDecimals : false,
								categories: [<?php echo $datas_matriculas; ?>],
								labels: {
								  rotation: -100,
								  align: 'right',
								  style: {
									font: 'normal 9px Verdana, sans-serif'
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
								x: 10,
								verticalAlign: 'top',
								y: 10,
								floating: true,
								backgroundColor: '#E4E4E4'
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
      
    </div>
	
	<? incluirLib("rodape",$config,$usuario); ?>
    
  </div>
</body>
</html>