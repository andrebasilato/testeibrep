<link rel="stylesheet" href="/assets/plugins/fcbkcomplete/style.css" type="text/css" media="screen" charset="utf-8" />
<style>
.divCentralizada_interna {
  position: relative;
  width: 300px;
  height: 50px;
  left: 5%;
  top:50%;
}
</style>

<section id="global">
  <div class="page-header">
      <h1><?php echo $idioma["titulo"]; ?></h1>
  </div>
  <br />
  
  <form method="post" onsubmit="return validateFields(this, regras)">
	  <div style="border:#CCC solid 1px; overflow:visible;">
          <strong style="line-height:10px;">&nbsp; <?php echo $idioma["informa_usuario"]; ?></strong>
          <br />          
          
          <div class="divCentralizada_interna">
            <strong><?php echo $idioma['tipo_selecao']; ?></strong><br />
            <div style="float:left;">
                <select id="usuarios" name="usuarios" style="overflow:auto;"></select>
            </div>
            <div style="float:left; padding:3px; ">
                <input type="submit" name="salvar" id="salvar" value="<?php echo $idioma['btn_adicionar']; ?>" />
            </div>
          </div>
          <div style="width:100%; overflow:auto;"></div>
          <br />
      </div>
      <br />
      
      <div style="width:100%; overflow:auto; height:100px; border:#CCC solid 1px;">
        <table cellpadding="4" cellspacing="0" width="100%" >
          <tr class="linha">
            <td class="coluna"><strong><?php echo $idioma['quem_visualiza']; ?></strong></td>
          </tr>
          <?php 
          $tamanho = (count($visualizadoresDestaque) - 1);
          if ($visualizadoresDestaque) {
            foreach($visualizadoresDestaque as $ind => $visualizador) {   
          ?>
              <tr <?php if ($tamanho != $ind) { ?>class="linha" <?php } ?>>
                <td class="coluna"><?php echo $visualizador['usuario']; ?></td>
              </tr>
          <?php 
            }
          } 
          ?>       
        </table>
      </div>
      <br />      
      <input type="hidden" name="acao" id="acao" value="destacar_usuario" />        
  </form>
    
</section>

<script src="/assets/plugins/fcbkcomplete/jquery.fcbkcomplete.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){                
		$("#usuarios").fcbkcomplete({
			width: 200,
			width_options: 200,
			width_dialog: 200,
			json_url: "/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/json/destacar_usuarios",
			addontab: true,
			height: 10,
			maxshownitems: 10,
			cache: true,
			maxitems: 20,
			filter_selected: true,
			firstselected: true,
			complete_text: "<?= $idioma["mensagem_select"]; ?>",
			addoncomma: true
		});
	});
</script>