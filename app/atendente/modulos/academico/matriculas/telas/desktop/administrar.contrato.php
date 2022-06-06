<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usu_vendedor); ?>
<style type="text/css">
body {
  padding-top: 0px;
}
.quebra_pagina {
  page-break-after:always;
}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<script>
function imprimirContrato() {	
  parent['frame_contrato'].focus();
  parent['frame_contrato'].print();
}  
</script>

<body>
<table width="100%" height="100%" border="0" align="center" cellpadding="2" cellspacing="0">
  <tr>
    <td>
      <table width="99%" border="0" cellspacing="0" cellpadding="5" align="center">
        <tr>
          <td><img src="/assets/img/logo_pequena.png" width="135" height="50" /></td>
          <td align="center">
			<?=$idioma["matricula_id"];?> <?= $contrato["idmatricula"]; ?>
            <br />
            <strong><?= $contrato["contrato"]; ?> (<?= $contrato["tipo"]; ?>)</strong>
          </td>
          <td align="right"><a class="btn btn-small" onclick="imprimirContrato('frame_contrato', '5%', '5%', '90%', '90%');"><i class="icon-print"></i><?=$idioma["matricula_imprimir"];?></a></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td align="center">
	  <?php 
      //$data_matricula = new DateTime($matricula['data_cad']);
      $arquivo = "/storage/matriculas_contratos/" . $contrato['arquivo_pasta'] . "/" . $contrato["idmatricula"]."/".$contrato["idmatricula_contrato"].".html";
      $arquivoServidor = $_SERVER['DOCUMENT_ROOT'].$arquivo;
      if(file_exists($arquivoServidor)) {
      ?>
        <iframe name="frame_contrato" id="frame_contrato" src="<?= $arquivo; ?>" width="99%" height="500" frameborder="1" style="background-color:#FFFFFF"></iframe>
      <? } else { ?>
        <div class="alert alert-error">
          <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
          <strong><?=$idioma["matricula_erro_label"];?></strong>
          <br />
          <?=$idioma["matricula_erro_msg"];?>
        </div>
      <? } ?>
    </td>
  </tr>
</table>
</body>
</html>