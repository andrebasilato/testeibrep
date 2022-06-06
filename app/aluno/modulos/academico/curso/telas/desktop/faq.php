<?php header('Content-Type: text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<html class="no-js">
<head>
	<?php incluirLib("head", $config, $usuario); ?>
</head>
<style>
	.accordion {
	background-color: #eee;
	color: #444;
	cursor: pointer;
	padding: 18px;
	width: 100%;
	border: none;
	text-align: left;
	outline: none;
	font-size: 15px;
	transition: 0.4s;
	}

	.active, .accordion:hover {
	background-color: #ccc; 
	}

	.panel {
	padding: 0 18px;
	display: none;
	background-color: white;
	overflow: hidden;
	}
</style>
<body>

<!-- Topo -->
<?php incluirLib("topo", $config, $usuario); ?>
<!-- /Topo -->
<!-- Topo curso -->
<?php incluirLib("topo_curso", $config, $informacoesTopoCurso); ?>
<!-- /Topo curso -->
<!-- Conteudo -->
<div class="content" style="position: relative;">
    <div class="row container-fixed">
        <!-- Menu Fixo -->
        <?php incluirLib("menu", $config, $usuario); ?>
        <!-- /Menu Fixo -->   
        <!-- Box -->
        <div class="box-side box-bg">
            <div class="top-box box-amarelo">
                <h1><?php echo $idioma['titulo']; ?></h1>
                <i class="icon-question"></i>            
            </div>
            <h2 class="ball-icon">&bull;</h2> 
            <div class="clear"></div>
				<div class="row-fluid box-item">
					<div class="span12">                
						<div class="abox extra-align">
						<?php
						if (count($faqs) > 0) { ?>
							<?php 
							$ordem = 0;
							foreach ($faqs as $faq) {  
								$ordem++;
							?>
							<button class="accordion"><?php echo $ordem . ') ' .$faq['pergunta']; ?></button>
							<div class="panel">
							<p><?php echo $faq['resposta']; ?></p>
							</div>
							<?php } ?>
						<?php } else { ?>
							<p><?php echo $idioma['nenhum_faq']; ?> </p>
						<?php } ?>
						
						</div>            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Box -->
    </div>
</div>
</body>
<script>
var acc = document.getElementsByClassName("accordion");
var i;
for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.display === "block") {
      panel.style.display = "none";
    } else {
      panel.style.display = "block";
    }
  });
}
</script>
</html>