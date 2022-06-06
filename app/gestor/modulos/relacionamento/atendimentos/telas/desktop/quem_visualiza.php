<div style="width:100%; overflow:auto; height:400px; border:#CCC solid 1px;">
  <table cellpadding="4" cellspacing="0" width="100%" >
    <tr class="linha">
      <td class="coluna"><strong><?php echo $idioma['quem_visualiza']; ?></strong></td>
    </tr>
    <?php 
    $tamanho = (count($visualizadores) - 1);
    if ($visualizadores) {
      foreach($visualizadores as $ind => $visualizador) {   
	  ?>
        <tr <?php if ($tamanho != $ind) { ?>class="linha" <?php } ?>>
          <td class="coluna" style="text-transform:uppercase;"><?php echo $visualizador['nome']; ?></td>
        </tr>
	  <?php 
      }
    } 
    ?>       
  </table>
</div>