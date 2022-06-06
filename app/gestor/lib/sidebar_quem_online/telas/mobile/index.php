<div id="quem_esta_online_gestor" style="display:none;" class="section section-small">
  <h3 class="section-header"><?php echo $idioma['tabela_gestor']; ?></h3>
  <table class="table" cellpadding="2" cellspacing="0" width="100%" >
    <tr>
      <td><strong><?php echo $idioma["tabela_nome"]; ?></strong></td>
      <td><strong><?php echo $idioma["tabela_email"]; ?></strong></td>
    </tr>
	<?php
	if($total_gestores > 0) {
	  while($gestores = mysql_fetch_assoc($query_gestores)) { ?>
        <tr>
          <td><?php echo $gestores["nome"]; ?></td>
          <td><?php echo $gestores["email"]; ?></td>
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
<div id="quem_esta_online_corretor" style="display:none;" class="section section-small">
  <h3 class="section-header"><?php echo $idioma['tabela_corretor']; ?></h3>
  <table class="table" cellpadding="2" cellspacing="0" width="100%" >
    <tr>
      <td><strong><?php echo $idioma["tabela_nome"]; ?></strong></td>
      <td><strong><?php echo $idioma["tabela_email"]; ?></strong></td>
    </tr>
	<?php
	if($total_corretores > 0) {
	  while($corretores = mysql_fetch_assoc($query_corretores)) { ?>
        <tr>
          <td><?php echo $corretores["nome"]; ?></td>
          <td><?php echo $corretores["email"]; ?></td>
        </tr>
	  <?php
	  }
	} else {
	?>
      <tr>
        <td colspan="2"><?php echo $idioma['nenhum_corretor']; ?></td>
      </tr>
    <?php
	}
	?>
  </table>
</div>
<div id="quem_esta_online_usuarios_imobiliarias" style="display:none;" class="section section-small">
  <h3 class="section-header"><?php echo $idioma['tabela_usuarios_imobiliarias']; ?></h3>
  <table class="table" cellpadding="2" cellspacing="0" width="100%" >
    <tr>
      <td><strong><?php echo $idioma["tabela_nome"]; ?></strong></td>
      <td><strong><?php echo $idioma["tabela_email"]; ?></strong></td>
    </tr>
	<?php
	if($total_usuarios_imobiliarias > 0) {
	  while($usuarios_imobiliarias = mysql_fetch_assoc($query_usuarios_imobiliarias)) { ?>
        <tr>
          <td><?php echo $usuarios_imobiliarias["nome"]; ?></td>
          <td><?php echo $usuarios_imobiliarias["email"]; ?></td>
        </tr>
	  <?php
	  }
	} else {
	?>
      <tr>
        <td colspan="2"><?php echo $idioma['nenhum_usuario_imobiliaria']; ?></td>
      </tr>
    <?php
	}
	?>
  </table>
</div>
<div id="quem_esta_online_clientes" style="display:none;" class="section section-small">
  <h3 class="section-header"><?php echo $idioma['tabela_clientes']; ?></h3>
  <table class="table" cellpadding="2" cellspacing="0" width="100%" >
    <tr>
      <td><strong><?php echo $idioma["tabela_nome"]; ?></strong></td>
      <td><strong><?php echo $idioma["tabela_email"]; ?></strong></td>
    </tr>
  </table>
</div>
<div id="quem_esta_online_correspondentes" style="display:none;" class="section section-small">
  <h3 class="section-header"><?php echo $idioma['tabela_correspondentes']; ?></h3>
  <table class="table" cellpadding="2" cellspacing="0" width="100%" >
    <tr>
      <td><strong><?php echo $idioma["tabela_nome"]; ?></strong></td>
      <td><strong><?php echo $idioma["tabela_email"]; ?></strong></td>
    </tr>
	<?php
	if($total_correspondentes > 0) {
	  while($correspondentes = mysql_fetch_assoc($query_correspondentes)) { ?>
        <tr>
          <td><?php echo $correspondentes["nome"]; ?></td>
          <td><?php echo $correspondentes["email"]; ?></td>
        </tr>
      <?php
	  }
	} else {
	?>
      <tr>
        <td colspan="2"><?php echo $idioma['nenhum_correspondente']; ?></td>
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
      <div class='span4 ac'>
        <div class='data-block'>
          <? if($total_gestores > 0) { ?><a href="#quem_esta_online_gestor" rel="facebox"><? } ?>
            <h1><?php echo $total_gestores; ?></h1>
            <h6><?php echo $idioma['quem_online_gestor']; ?></h6>
          <? if($total_gestores > 0) { ?></a><? } ?>
        </div>
      </div>
      <div class='span4 ac'>
        <div class='data-block'>
          <? if($total_usuarios_imobiliarias > 0) { ?><a href="#quem_esta_online_usuarios_imobiliarias" rel="facebox"><? } ?>
            <h1 class='success'><?php echo $total_usuarios_imobiliarias; ?></h1>
            <h6 class='data-heading'><?php echo $idioma['quem_online_imobiliaria']; ?></h6>
          <? if($total_usuarios_imobiliarias > 0) { ?></a><? } ?>
        </div>
      </div>
      <div class='span4 ac'>
        <div class='data-block'>
          <? if($total_corretores > 0) { ?><a href="#quem_esta_online_corretor" rel="facebox"><? } ?>
            <h1 class='success'><?php echo $total_corretores; ?></h1>
            <h6 class='data-heading'><?php echo $idioma['quem_online_corretor']; ?></h6>
          <? if($total_corretores > 0) { ?></a><? } ?>
        </div>
      </div>
    </div>
    <div class='row-fluid'>
      <div class='span4 ac'>
        <div class='data-block'>
          <? if($total_clientes > 0) { ?><a href="#quem_esta_online_clientes" rel="facebox"><? } ?>
            <h1 class='success'><?php echo $total_clientes; ?></h1>
            <h6 class='data-heading'><?php echo $idioma['quem_online_cliente']; ?></h6>
          <? if($total_clientes > 0) { ?></a><? } ?>
        </div>
      </div>
      <div class='span4 ac'>
        <div class='data-block'>
          <? if($total_correspondentes > 0) { ?><a href="#quem_esta_online_correspondentes" rel="facebox"><? } ?>
            <h1 class='success'><?php echo $total_correspondentes; ?></h1>
            <h6 class='data-heading'><?php echo $idioma['quem_online_correspondente']; ?></h6>
          <? if($total_correspondentes > 0) { ?></a><? } ?>
        </div>
      </div>
      <div class='span4 ac'>
        <div class='data-block'> </div>
      </div>
    </div>
  </div>
</div>
