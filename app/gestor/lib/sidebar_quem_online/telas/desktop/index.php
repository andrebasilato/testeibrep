<div id="quem_esta_online_gestor" style="display:none;" class="section section-small"> 
  <h3 class="section-header"><?php echo $idioma['tabela_gestor']; ?></h3>
  <table class="table" cellpadding="2" cellspacing="0" width="100%" style="text-transform:uppercase;">
    <tr>
      <td>&nbsp;</td>
      <td><strong><?php echo $idioma["tabela_nome"]; ?></strong></td>
      <td><strong><?php echo $idioma["tabela_perfil"]; ?></strong></td>
      <td><strong><?php echo $idioma["tabela_cidade"]; ?></strong></td>
    </tr>
	<?php 
	if($total_gestores > 0) {
	  while($gestores = mysql_fetch_assoc($query_gestores)) { ?>
        <tr>
          <td><img src="/api/get/imagens/usuariosadm_avatar/30/30/<?php echo $gestores["avatar_servidor"]; ?>" class="img-circle"></td>
          <td><?php echo $gestores["nome"]; ?></td>
          <td><?php echo $gestores["perfil"]; ?></td>
          <td><?php echo $gestores["cidade"]; ?></td>
        </tr>
    <?php 
	  } 
	} else {
	?>
      <tr>
        <td colspan="2"><?php echo $idioma['nenhum_gestor']; ?></td>
      </tr>
    <?php
	}
	?>
  </table>
</div>
<div id="quem_esta_online_vendedores" style="display:none;" class="section section-small"> 
  <h3 class="section-header"><?php echo $idioma['tabela_vendedor']; ?></h3>
  <table class="table" cellpadding="2" cellspacing="0" width="100%" style="text-transform:uppercase;">
    <tr>
	  <td>&nbsp;</td>
      <td><strong><?php echo $idioma["tabela_nome"]; ?></strong></td>
      <td><strong><?php echo $idioma["tabela_cidade"]; ?></strong></td>
    </tr>
	<?php 
	if($total_vendedores > 0) {
	  while($vendedores = mysql_fetch_assoc($query_vendedores)) { ?>
        <tr>
		  <td><img src="/api/get/imagens/vendedores_avatar/30/30/<?php echo $vendedores["avatar_servidor"]; ?>" class="img-circle"></td>
          <td><?php echo $vendedores["nome"]; ?></td>
          <td><?php echo $vendedores["cidade"]; ?></td>		  
        </tr>
	  <?php 
	  } 
	} else {
	?>
      <tr>
        <td colspan="2"><?php echo $idioma['nenhum_vendedor']; ?></td>
      </tr>
    <?php
	}
	?>
  </table>
</div>
<div id="quem_esta_online_professores" style="display:none;" class="section section-small"> 
  <h3 class="section-header"><?php echo $idioma['tabela_professor']; ?></h3>
  <table class="table" cellpadding="2" cellspacing="0" width="100%" style="text-transform:uppercase;">
    <tr>
      <td>&nbsp;</td>
      <td><strong><?php echo $idioma["tabela_nome"]; ?></strong></td>
      <td><strong><?php echo $idioma["tabela_cidade"]; ?></strong></td>
    </tr>
	<?php 
	if($total_professores > 0) {
	  while($professores = mysql_fetch_assoc($query_professores)) { ?>
        <tr>
          <td><img src="/api/get/imagens/professores_avatar/30/30/<?php echo $professores["avatar_servidor"]; ?>" class="img-circle"></td>
          <td><?php echo $professores["nome"]; ?></td>
          <td><?php echo $professores["cidade"]; ?></td>
        </tr>
	  <?php 
	  } 
	} else {
	?>
      <tr>
        <td colspan="2"><?php echo $idioma['nenhum_usuario_professor']; ?></td>
      </tr>
    <?php
	}
	?>
  </table>
</div>
<div id="quem_esta_online_alunos" style="display:none;" class="section section-small"> 
  <h3 class="section-header"><?php echo $idioma['tabela_aluno']; ?></h3>
  <table class="table" cellpadding="2" cellspacing="0" width="100%" style="text-transform:uppercase;">
    <tr>
      <td>&nbsp;</td>
      <td><strong><?php echo $idioma["tabela_nome"]; ?></strong></td>
      <td><strong><?php echo $idioma["tabela_cidade"]; ?></strong></td>
    </tr>
	<?php 
	if($total_alunos > 0) {
	  while($alunos = mysql_fetch_assoc($query_alunos)) { ?>
        <tr>
          <td><img src="/api/get/imagens/pessoas_avatar/30/30/<?php echo $alunos["avatar_servidor"]; ?>" class="img-circle"></td>
          <td><?php echo $alunos["nome"]; ?></td>
          <td><?php if($alunos["cidade"]) { echo $alunos["cidade"]; } else { echo "--"; } ?></td>
        </tr>
      <?php 
	  } 
	} else {
	?>
      <tr>
        <td colspan="2"><?php echo $idioma['nenhum_aluno']; ?></td>
      </tr>
    <?php
	}
	?>
  </table>
</div>
<div class='section section-small'>
  <div class='section-header'>
    <h5> <?php echo $idioma['quem_online']; ?> </h5>
  </div>
  <div class='section-body'>
    <div class='row-fluid'>
      <div class='span6 ac'>
        <div class='data-block'>
          <? if($total_gestores > 0) { ?><a href="#quem_esta_online_gestor" rel="facebox" style="text-decoration:none;"><? } ?>
            <h1><?php echo $total_gestores; ?></h1>
            <h6><?php echo $idioma['quem_online_gestor']; ?></h6>
          <? if($total_gestores > 0) { ?></a><? } ?>
        </div>
      </div>
      <div class='span6 ac'>
        <div class='data-block'>
          <? if($total_alunos > 0) { ?><a href="#quem_esta_online_alunos" rel="facebox" style="text-decoration:none;"><? } ?>
            <h1 class='success'><?php echo $total_alunos; ?></h1>
            <h6 class='data-heading'><?php echo $idioma['quem_online_aluno']; ?></h6>
          <? if($total_alunos > 0) { ?></a><? } ?>
        </div>
      </div>
      <div class='span6 ac'>
        <div class='data-block'>
          <? if($total_professores > 0) { ?><a href="#quem_esta_online_professores" rel="facebox" style="text-decoration:none;"><? } ?>
            <h1 class='success'><?php echo $total_professores; ?></h1>
            <h6 class='data-heading'><?php echo $idioma['quem_online_professor']; ?></h6>
          <? if($total_professores > 0) { ?></a><? } ?>
        </div>
      </div>
    <div class='row-fluid'>
      <div class='span6 ac'>
        <div class='data-block'>
          <? if($total_vendedores > 0) { ?><a href="#quem_esta_online_vendedores" rel="facebox" style="text-decoration:none;"><? } ?>
            <h1 class='success'><?php echo $total_vendedores; ?></h1>
            <h6 class='data-heading'><?php echo $idioma['quem_online_vendedor']; ?></h6>
          <? if($total_vendedores > 0) { ?></a><? } ?>
        </div>
      </div>
      <?php /*?><div class='span4 ac'>
        <div class='data-block'></div>
      </div>
      <div class='span4 ac'>
        <div class='data-block'> </div>
      </div><?php */?>
    </div>
  </div>
</div>
</div>
