<?php $hora = 1; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?= $config["tituloEmpresa"]; ?> - <?= $config["tituloSistema"]; ?> - <?= $config["tituloPainel"]; ?></title>
<link rel="icon" href="/assets/img/favicon.ico">
<link href="/assets/css/oraculo.css" rel="stylesheet"></head>
<body>
<div class='container' id='login'>
  <div id='logo'><a href="<?= $config["urlSistema"]; ?>" title="<?= $config["tituloSistema"]; ?>"><img alt="Logo do Oráculo" height="75" src="/especifico/img/logo_empresa.png" width="400" /> </a></div>
  <div class='row'>
  	<div class="box-conteudo">
    <table>
        <?php if($info["tipo_documento"] == "declaracao") {?>
      <tr>
          <td style="font-size:18px; padding-right:50px;"><?= $idioma["tipo"]; ?><strong><?= $idioma['tipo_documento_declaracao'];?></strong>.</td>
      </tr>
        <tr><td>&nbsp;</td></tr>
        <tr>
          <td style="font-size:18px; padding-right:50px;"><?= $idioma["listagem_tipo"]; ?><strong><?= $info['tipo'];?></strong>.</td>
      </tr>
        <?php }else if($info["tipo_documento"] == "certificado"){ ?>
      <tr>
          <td style="font-size:18px; padding-right:50px;"><?= $idioma["tipo"]; ?><strong><?= $idioma['tipo_documento_certificado'];?></strong>.</td>
      </tr>
        <?php }else if($info["tipo_documento"] == "historico"){ ?>
        <?php $hora = 0; ?>
      <tr>
          <td style="font-size:18px; padding-right:50px;"><?= $idioma["tipo"]; ?><strong><?= $idioma['tipo_documento_historico'];?></strong>.</td>
      </tr>
        <?php } ?>
      <tr><td>&nbsp;</td></tr>
      <tr>
          <td colspan="2" style="font-size:18px; padding-right:50px;"><?= $idioma["listagem_data_certificado"]; ?> <strong><?= formataData($info['data_conclusao'],'pt', 0);?></strong>.</td>
      </tr>
      <tr><td>&nbsp;</td></tr>
      <tr>
          <td style="padding-right:50px; font-size:18px;"><?= $idioma["listagem_aluno"]; ?><strong><?= $info['aluno'];?></strong>.</td>
      </tr>
      <?php if($info["tipo_documento"] == "certificado"){ ?>
      <tr><td>&nbsp;</td></tr>
        <tr>
            <td style="padding-right:50px; font-size:18px;">CURSO: <strong><?= $info['nomeCurso']; ?></strong></td>
        </tr>
      <?php } ?>
 </table>
 </div>
  </div>
</div>
</body>
</html>
