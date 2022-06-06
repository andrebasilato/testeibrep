<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usuario); ?>

<?php if ($dados_grafico) { 
		 $t = count($dados_grafico); 
?>					
<script type="text/javascript" src="/assets/plugins/highcharts/js/jquery.min.js"></script>
<script type="text/javascript">
  $(function () {
	  var chart;
	  $(document).ready(function() {
		  chart = new Highcharts.Chart({
			  chart: {
				  renderTo: 'container',
				  plotBackgroundColor: null,
				  plotBorderWidth: null,
				  plotShadow: false
			  },
			  title: {
				  text: '<?php echo $idioma["titulo_grafico"]; ?>'
			  },
			  tooltip: {
				  formatter: function() {
					  return '<b>'+ this.point.name +'</b>: '+ Highcharts.numberFormat(this.percentage,2) +' %';
				  }
			  },
			  plotOptions: {
				  pie: {
					  allowPointSelect: true,
					  cursor: 'pointer',
					  dataLabels: {
						  enabled: true,
						  color: '#000000',
						  connectorColor: '#000000',
						  formatter: function() {
							  return '<b>'+ this.point.name +'</b>: '+ Highcharts.numberFormat(this.percentage,2) +' %';
						  }
					  }
				  }
			  },
			  series: [{
				  type: 'pie',
				  name: 'Browser share',
				  data: [
				  
				  	<?php 
					foreach($dados_grafico as $ind => $val) {
						echo '[\''.$val['opcao'].'\','.$val['porcentagem'].']';  
						if (($ind+1) != $t) echo ","; 
					} 
					?>				  
					  
				  ]
			  }]
		  });
	  });
	  
  });
</script>

<?php } ?>

</head>
<?php if($_POST['idpesquisa']){ ?>
<body onload="loadAjax(<?= $_POST['idpesquisa']; ?>);">
<?php } else { ?>
<body>
<?php } ?>
<?php incluirLib("topo",$config,$usuario); ?>
<div class="container-fluid">
	<section id="global">
		<div class="page-header">
    		<h1><?= $idioma["pagina_titulo"]; ?> &nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1>
  		</div>
  		<ul class="breadcrumb">
      		<li><a href="/<?= $url[0]; ?>"><?= $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
      		<li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>"><?= $idioma["nav_configuracoes"]; ?></a> <span class="divider">/</span></li>
      		<li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"><?= $idioma["pagina_titulo"]; ?></a> <span class="divider">/</span></li>
      		<? if($url[4] == "editar") { ?>
      			<li class="active"><?php echo $linha["nome"]; ?></li>
      		<? } else { ?>
      			<li class="active"><?= $idioma["nav_formulario"]; ?></li>
      		<? } ?>
      		<span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
  		</ul>
	</section>
  	<div class="row-fluid">
  		<div class="span12">
        	<div class="box-conteudo">
        		<div class=" pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"><i class="icon-share-alt"></i> <?= $idioma["sair_edicao"]; ?></a></div>
            	<h2 class="tituloEdicao"><?= $linha["nome"]; ?></h2> 
            	<div class="tabbable tabs-left">
					<?php incluirTela("inc_menu_edicao",$config,$linha); ?>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_editar">
                            <h2 class="tituloOpcao"><?= $idioma["titulo_opcao"]; ?></h2>
                            <div id="listagem_informacoes"><?= $idioma["texto_explicativo"]; ?></div>
                            
				
                            <form class="well wellDestaque form-inline" method="post">
                                <table>
                                  <tr>
									<td><?php echo $idioma["legenda_pesquisa"]; ?></td>
									<td>
                                      <select class="span3" name="idpesquisa" onchange="loadAjax(this.value);">
                                        <option value=""><?php echo $idioma['pesquisa_todos']; ?></option>
										<?php 
                                        if ($pesquisasArrayAss) {
                                            foreach($pesquisasArrayAss as $ind => $pesquisa) {
                                            ?>
                                            <option value="<?php echo $pesquisa['idpesquisa']; ?>"  <?php if ($_POST['idpesquisa'] == $pesquisa['idpesquisa']) echo "selected='selected'" ?> ><?php echo $pesquisa['pesquisa']; ?></option>
                                            <?php } 
                                        } ?>
                                      </select>	
									</td>  
								  </tr>
								  <tr>
									<td><?php echo $idioma["legenda_empreendimento"]; ?></td>
                                    <td>
                                      <select class="span3" name="idempreendimento" id="empreendimento">								  
                                      </select>
                                    </td>									
								  </tr>
                                  <tr>
									<td><?php echo $idioma["legenda_de"]; ?></td>
									<td><input type="text" class="span2" name="de" id="de" value="<?php if ($_POST['de']) echo formataData($_POST['de'], 'pt', 0); ?>"/></td>
								  </tr>
                                  <tr>
									 <td><?php echo $idioma["legenda_ate"]; ?></td>
									 <td><input type="text" class="span2" name="ate" id="ate" value="<?php if ($_POST['ate']) echo formataData($_POST['ate'], 'pt', 0); ?>" /></td>
								  </tr>
								  <tr>
                                    <td>
                                      <input type="hidden" id="acao" name="acao" value="filtrar_grafico" />
                                	  <input type="submit" class="btn" value="<?= $idioma["btn_filtrar"]; ?>" />
                                    </td>
								  </tr>

                                </table>
                                
                            </form> 
                            
                            <?php
                            if (!$dados_grafico) 
								echo $idioma['sem_informacao']; 
							?>                          
                            <div id="container" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
                                     
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<? incluirLib("rodape",$config,$usuario); ?>
    
	<script src="/assets/plugins/jquery-ui/ui/jquery.ui.core.js"></script>
    <script src="/assets/plugins/jquery-ui/ui/jquery.ui.datepicker.js"></script>
    <script src="/assets/plugins/jquery-ui/ui/i18n/jquery.ui.datepicker-pt-BR.js"></script>
    
    
    <script src="/assets/plugins/highcharts/js/highcharts.js"></script>
	<script src="/assets/plugins/highcharts/js/modules/exporting.js"></script>    
  
    
    <script type="text/javascript">
		function loadAjax(idpesquisa) {
			if (window.XMLHttpRequest)
			  xmlhttp=new XMLHttpRequest();
			else
			  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");

			xmlhttp.onreadystatechange=function(){
			  if (xmlhttp.readyState==4 && xmlhttp.status==200) {
				document.getElementById("empreendimento").innerHTML = xmlhttp.responseText;
			  }
			}
			xmlhttp.open("POST","/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/ajax_empreendimentos",true);
			xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			xmlhttp.send("idpesquisa="+idpesquisa);
		}			
			
		
        var regras = new Array();
    
        jQuery(document).ready(function($) {
            $("#de").datepicker($.datepicker.regional["pt-BR"]);
			$("#ate").datepicker($.datepicker.regional["pt-BR"]);
        });
    </script>
      
</div>
</body>
</html>