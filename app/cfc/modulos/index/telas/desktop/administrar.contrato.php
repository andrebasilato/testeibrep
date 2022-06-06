<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usuario); ?>
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
			#<?= $contrato["idescola"]; ?> - <?= $usuario["nome_fantasia"]; ?>
            <br />
            <strong><?= $contrato["contrato"]; ?> (<?= $contrato["tipo"]; ?>)</strong>
          </td>
          <td align="right">
              <a class="btn btn-small" onclick="imprimirContrato('frame_contrato', '5%', '5%', '90%', '90%');">
                  <i class="icon-print"></i><?=$idioma["escola_imprimir"];?>
              </a>
          </td>
          <td align="right">
            <a class="btn btn-small" href="?opLogin=sair">
                <i class='icon-share-alt'></i>Sair
            </a>
          </td>
        </tr>
      </table>
    </td>
  </tr>
    
  <tr>
    <td align="center">
	  <?php 
       
          if($contrato['arquivo_servidor']){
            $arquivo = "/storage/escolas_contratos/" . $contrato['arquivo_pasta'] . "/" . $contrato["idescola"]."/".$contrato["arquivo_servidor"];
          }else{
            $arquivo = "/storage/escolas_contratos/" . $contrato['arquivo_pasta'] . "/" . $contrato["idescola"]."/".$contrato["idescola_contrato"].".html";
          }
      
          $arquivoServidor = $_SERVER['DOCUMENT_ROOT'].$arquivo;
          if(file_exists($arquivoServidor) && $contrato['arquivo_servidor']) {
          
      ?>
        <h2>
            <a href="?pdf=true" target="_blank"> Clique aqui para abrir o contrato. </a>
        </h2>
        
      <?php } elseif(file_exists($arquivoServidor)) { ?>
         <iframe name="frame_contrato" id="frame_contrato" src="<?= $arquivo; ?>" width="99%" height="500" frameborder="1" style="background-color:#FFFFFF"></iframe>
      <?php } else { ?>
        <div class="alert alert-error">
          <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
          <strong><?=$idioma["escola_erro_label"];?></strong>
          <br />
          <?=$idioma["escola_erro_msg"];?>
        </div>
      <?php } ?>
        
        
    </td>
  </tr>
</table>
 
    <form id="form_contrato" name="form_contrato" method="post" onsubmit="return validateFields(this, regras)">
       <input type="hidden" name="acao" id="acao" value="concordar" />                       
       <div style="margin-left: 35px;" class="row-fluid">
           <div class="span12 contract payment">
               <h3>
                   <input type="checkbox" id="contrato" onclick="DesabilitaBotao()" name="contrato" value="<?= $contrato['idescola_contrato']; ?>">
                   <?= $idioma['li_concordo']; ?>
                   <?= $idioma['termos_contrato']; ?>
                   <input type="submit" id="concordo" disabled="disabled" value="Concordo"></input>
               </h3>
           </div>
       </div>
    </form>
    
    <script src="/assets/min/aplicacao.desktop.min.js"></script>
    <script src="/assets/plugins/jquery-ui/ui/jquery.ui.core.js"></script>
    <script type="text/javascript">
        function DesabilitaBotao(){
            if ($('#contrato').prop('checked')) {
                $('#concordo').removeAttr('disabled');
            }else{
                $('#concordo').attr('disabled','disabled');
            }
        }
    </script>
    
</body>
</html>