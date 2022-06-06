<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <?php incluirLib("head",$config,$usuario); ?>
    <script type="text/javascript" src="/assets/plugins/highcharts/js/jquery.min.js"></script>
  <script src="/assets/plugins/highcharts/js/highcharts.js"></script>
  <!--<script src="/assets/plugins/highcharts/js/modules/exporting.js"></script>-->
</head>
<body>
  <?php incluirLib("topo",$config,$usuario); ?>
  <div class="container-fluid">
    <section id="global">
      <div class="page-header">
    	<h1><?php echo $idioma["pagina_titulo"]; ?> &nbsp;<small><?php echo $idioma["pagina_subtitulo"]; ?></small></h1>
      </div>
      <ul class="breadcrumb">
    	<li><a href="/<?php echo $url[0]; ?>"><?php echo $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
    	<li><a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>"><?php echo $idioma["nav_modulo"]; ?></a> <span class="divider">/</span></li>
    	<li><a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>"><?php echo $idioma["pagina_titulo"]; ?></a> </li>
		<li class="active"><span class="divider">/</span><?= $idioma["titulo_opcao"]; ?></li>
        <?php if($_GET["q"]) { ?><li><span class="divider">/</span> <a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>"><?php echo $idioma["nav_resetarbusca"]; ?></a></li><?php } ?>
    	<span class="pull-right" style="padding-top:3px; color:#999"><?php echo $idioma["hora_servidor"]; ?> <?php echo date("d/m/Y H\hi"); ?></span>
      </ul>
    </section>
    <div class="row-fluid">
      <div class="span12">
        <div class="box-conteudo">
          <div class="pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"><i class="icon-share-alt"></i> <?= $idioma["sair_fila"]; ?></a></div>
          <h2 class="tituloEdicao"><?= $linha["nome"]; ?> </h2> 
           <div class="tabbable tabs-left">
			<?php incluirTela("inc_menu_edicao",$config,$usuario); ?>
            <div class="tab-content">                    		
              <div class="tab-pane active" id="tab_editar">
                
                <?php if($_POST["msg"]) { ?>
                  <div class="alert alert-success fade in">
                    <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                    <strong><?php echo $idioma[$_POST["msg"]]; ?></strong>
                  </div>
                <?php } ?>
                <?php if(count($salvar["erros"]) > 0){ ?>
                  <div class="alert alert-error fade in">
                    <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                    <strong><?= $idioma["form_erros"]; ?></strong>
                    <?php foreach($salvar["erros"] as $ind => $val) { ?>
                      <br />
                      <?php echo $idioma[$val]; ?>
                    <?php } ?>
                  </div>
                <?php 
				} 				
				?>
				
				<?php $cores = array(1=>'#4572A7',2=>'#89A54E',3=>'#AA4643',4=>'#80699B',5=>'#3D96AE',6=>'#DB843D',7=>'#A47D7C'); ?>
				<?php 
				foreach ($perguntas as $ind => $pergunta) { $cor = $cores;
					if ($pergunta['tipo'] == 'O') {
				?>
					<div>
						<h3><?php echo $pergunta['nome']; ?></h3>
						<script type="text/javascript">
						$(function () {
							var chart;
							$(document).ready(function() {
								chart = new Highcharts.Chart({
									chart: {
										renderTo: 'resultado_<?php echo $pergunta['idpergunta']; ?>',
										type: 'bar'
									},
									title: {
										text: ''
									},
									/*subtitle: {
										text: 'Source: Wikipedia.org'
									},*/
									xAxis: {
										categories: [<?php foreach ($pergunta['respostas'] as $resposta) {  echo "'".$resposta['nome']."',"; } ?>],
										title: {
											text: null
										}
									},
									yAxis: {
										min: 0,
										max: 100,
										allowDecimals:false,										
										title: {
											text: null,
											align: 'high'
										},
										labels: {
											overflow: 'justify',
											formatter: function() {
												return this.value +' %';
											}
										}
									},
									/*tooltip: {
										formatter: function() {
											return ''+
												this.y +' ';
										}
									},*/
									tooltip:false,
									plotOptions: {
										bar: {
											dataLabels: {
												enabled: true,
												formatter: function() {
													return this.y +' %';
												}
											}
										}
									},
									/*legend: {
										layout: 'vertical',
										align: 'right',
										verticalAlign: 'top',
										x: -100,
										y: 100,
										floating: true,
										borderWidth: 1,
										backgroundColor: '#FFFFFF',
										shadow: true
									},*/
									legend:false,
									credits: {
										enabled: false
									},
									series: [
									<?php /*foreach ($pergunta['respostas'] as $resposta) {   ?>
									{
										name: '<?php echo $resposta['porcentagem'].'%'; ?>',
										data: [<?php echo $resposta['valor'].''; ?>]
									},
									<?php }*/ ?>
									{
										name: 'asd',
										data: [<?php foreach ($pergunta['respostas'] as $resposta) { $c++; echo '{y:'.number_format($resposta['porcentagem'], 2, ".", "").',color:"'.$cor[$c].'"}, '; unset($cor[$c]); } $c = 0; ?>]
									},
									]
								});
							});
							
						});
						</script>
						<div id="resultado_<?php echo $pergunta['idpergunta']; ?>" style="width: 100%; height: 250px; margin: 0 auto"></div>
						<?php if ($pergunta['multipla_escolha'] == 'S') { ?>
							<div style="text-align:right;"><?php echo $idioma['mensagem_multipla_escolha']; ?></div>
						<?php } ?>
						<div style=" padding:2px; display:table;">							
							<?php foreach ($pergunta['respostas'] as $resposta) {  ?>
								<span style=" background:<?php echo $cores[++$a]; ?>; ">&nbsp;&nbsp;&nbsp;</span>
								<?php echo $resposta['nome']." - <strong>".round($resposta['valor'], 2)." </strong>&nbsp;"; ?>
							<?php } $a = 0; ?>
						</div>						
						
						<br />
					</div>
				<?php 
					} else { 
					?>
						<div>
							<h3><?php echo $pergunta['nome']; ?></h3><br />							
							<div id="resultado_<?php echo $pergunta['idpergunta']; ?>" style="width: 100%; margin: 0 auto">
								<?php foreach($pergunta['subjetiva'] as $resp) { 
									if (strlen($resp)) {
								?>
									<div style=" padding:7px; background:#C0D9D9;"> <?php echo $resp .'<br />'; ?> </div><br />
								<?php } } ?>
							</div>
						</div>
					<?php 
					}
				} 
				?>
				
              </div>   
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php incluirLib("rodape",$config,$usuario); ?>
  </div>
</body>
</html>