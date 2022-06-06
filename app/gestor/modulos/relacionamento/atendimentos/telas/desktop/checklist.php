<section id="global">
  <div class="page-header">
      <h1><?php echo $idioma["titulo"]; ?></h1>
  </div>
  
  <form method="post">
  
  	<?php 
	  if ($checklists) {
		foreach ($checklists as $check) { ?>
    	<br /><h5><?php echo $check['nome']; ?></h5><br />
        <?php foreach ($check as $ind => $opcao) { 
			    if ($ind !== 'nome') { ?>
        	<input name="opcao[]" type="checkbox" value="<?php echo $opcao['idchecklist'].'|'.$opcao['idopcao']; ?>" <?php if ($opcao['idmarcada']) echo 'checked="checked"'; ?> /> <?php echo $opcao['nome']; ?><br />
        <?php   }
			  } ?>
    <?php } 
	  } else {
		?> <span>Nenhuma informação encontrada.</span> <?php  
	  }
	?>
      <br />
      <input type="hidden" name="acao" id="acao" value="salvar_checklist" />
      <input class="btn" type="submit" name="salvar" id="salvar" value="<?php echo $idioma['btn_salvar']; ?>" />  
  </form>
    
</section>