<section id="global">
  <div class="page-header">
      <h1><?php echo $idioma["titulo"]; ?></h1>
  </div>
  
  <form method="post"> 
    <br /> 
  	<strong><?php echo $idioma['empreendimento']; ?></strong><br />
  	<?php 
	  if ($empreendimentos) {
		  ?> <select name="idempreendimento"> <?php
		foreach ($empreendimentos as $empreendimento) { ?>
        	<option value="<?php echo $empreendimento['idempreendimento']; ?>" > <?php echo $empreendimento['nome']; ?></option>
    <?php }
		?> </select> <?php 
	  } else {
		?> <span>Nenhuma informação encontrada.</span> <?php  
	  }
	?>
    <br />
    
    <strong><?php echo $idioma['situacao']; ?></strong><br />
    <select name="situacao"> 
	  <?php foreach ($situacao_pessoa[$config['idioma_padrao']] as $ind => $situacao) { ?>
            <option value="<?php echo $ind; ?>" > <?php echo $situacao; ?></option>
      <?php } ?> 
    </select>
    <br /><br />
    
    <input type="hidden" name="acao" id="acao" value="salvar_pessoas_em_bloco" />
    <input type="submit" name="salvar" id="salvar" value="<?php echo $idioma['btn_salvar']; ?>" />  
  </form>
    
</section>