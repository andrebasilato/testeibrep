<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usuario); ?>
</head>
<body>
<?php incluirLib("topo",$config,$usuario); ?>
<div class="container-fluid">
  <section id="global">
	<div class="page-header">
    	<h1><?= $idioma["pagina_titulo"]; ?> &nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1>
  	</div>
  	<ul class="breadcrumb">
    	<li><a href="/<?= $url[0]; ?>"><?= $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
    	<li class="active"><?= $idioma["modulo"]; ?></li>   
    	<span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
  	</ul>
  </section>
  <div class="row-fluid">
  	<div class="span12">
        <div class="box-conteudo">
        
        		<div id="listagem_informacoes"> 	
                    <div class="pull-right">
                        <input type="text" id="filtroFuncionalidade" class="span3 search-query" placeholder="Escreva o que procura...">
                    </div>  
		  			<?= $idioma["pagina_subtitulo"]; ?>
                </div>
                
             
                
                
                
                <?php 
				
					$pastas = listarFuncionalidades($url[1]); 
					
					$funcionalidades = array();
					$i = 0;
					$linhaNumero = 0;
					$colunas = 6;
					foreach($pastas as $ind => $val) {
						$i++;
						$funcionalidades[$linhaNumero][$ind] = $val;
						if($i == $colunas){
							$i = 0;
							$linhaNumero++;	
						}
					}					
					
				?>
                
                <?
					foreach($funcionalidades as $linha => $pastas) {
				?>
               	 <div class="row-fluid">
                <?						
						foreach($pastas as $ind => $val) {
				?>
                        <div class="span2 blocoFuncionalidade">
                            <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $val["pasta"]; ?>" class="filtroBloco">
                            <div class="blocoImagem"><img src=<?= $val["imagem"]; ?> /></div> 
                            <div class="blocoLink filtroTexto"><?= $val["nome"]; ?></div> 
                            </a>
                        </div>
                	<? } ?>
               	 </div>
                <? } ?>               
                                               
        </div>
    </div>
  </div>
  <? incluirLib("rodape",$config,$usuario); ?>

<script type="text/javascript" src="/assets/plugins/jcFilter/jcfilter.min.js"></script>
<script type="text/javascript">
jQuery(document).ready(function(){
jQuery("#filtroFuncionalidade").jcOnPageFilter({animateHideNShow: true,
	focusOnLoad:true,
	highlightColor:'yellow',
	textColorForHighlights:'#000000',
	caseSensitive:false,
	hideNegatives:true,
	parentLookupClass:'filtroBloco',
	childBlockClass:'filtroTexto'});
});          
</script>
  
</div>
</body>
</html>