<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <?php incluirLib("head",$config,$usuario); ?>
  <link href="/assets/bootstrap_v2/css/bootstrap-responsive.css" rel="stylesheet" />
  <link href="/assets/css/oraculo.mobile.css" rel="stylesheet" />
  <script src="/assets/js/jquery.1.7.1.min.js"></script>
  <script src="/assets/bootstrap_v2/js/bootstrap-alert.js"></script>
  <style>
    #logo {
      padding: 20px;	
    }
	@media (min-width: 480px) {
	  #logo {
		padding: 50px !important;	
	  }
	}
  </style>  
</head>
<body style="padding-top:10px">
<div class='container' id='login'>
  <div id='logo'> <a href="<?= $config["websiteEmpresa"]; ?>" title="<?= $config["tituloSistema"]; ?>"><img alt="<?= $config["tituloSistema"]; ?>" src="/especifico/img/logo_empresa.png" /> </a></div>
  <div class='row'>
    <div class='span6 offset3'>
      <div class='section section-large' id='login-section'>
        <div class='section-header' large='large'>
          <h3>OPS... Ocorreu uma falha!!!</h3>
          <ul>
            <li><a href="<?= $config["urlSite"]; ?>" target="_blank"><img src="/assets/img/logo_pequena.png" width="135" height="50" /></a></li>
          </ul>
        </div>
        <div class='section-body'>
          <p>
            Ocorreu alguma falha ao tentar processar sua solicitação.
            <br />
            Por favor, tente novamente!
            <br />
            <br />
            Caso a falha continue ocorrendo entre em contato com o administrador do sistema.
            <div style="text-align:center;padding:20px;">         
               <a class="btn btn-primary" href="/<?php echo $url[0] ?>" style="color:#FFF;padding:20px;font-size:18px;"><strong>Ir para a página inicial</strong></a>
            </div>
          </p>
        </div>
        <?php 
		if($_SERVER["DOCUMENT_ROOT"] == "C:/Servidor04/www/oraculo/desenvolvimento") {
		//if($_SERVER["DOCUMENT_ROOT"] == "C:/wamp/www/oraculo_novo/") { 
		?>
          <div class='section-body'>
            <p>
              <table cellpadding="8" cellspacing="1" width="570" bgcolor="#CCCCCC">
                <tr>
                  <td colspan="2" bgcolor="#E4E4E4" align="center">INFOMAÇÕES</td>
                </tr>
                <tr>
                  <td bgcolor="#F4F4F4">VARIÁVEL</td>
                  <td bgcolor="#F4F4F4">VALOR</td>
                </tr>
                <tr>
                  <td bgcolor="#FFFFFF"><strong>DATA</strong></td>
                  <td bgcolor="#FFFFFF"><?php echo date("d/m/Y H:i:s"); ?></td>
                </tr>
                <tr>
                  <td bgcolor="#FFFFFF"><strong>ERRO MYSQL</strong></td>
                  <td bgcolor="#FFFFFF"><?php echo $informacoes["mysql_error"]; ?></td>
                </tr>  
                <tr>
                  <td bgcolor="#FFFFFF"><strong>HTTP_REFERER</strong></td>
                  <td bgcolor="#FFFFFF"><?php echo $_SERVER['HTTP_REFERER']; ?></td>
                </tr>
                <tr>
                  <td bgcolor="#FFFFFF"><strong>SCRIPT_NAME</strong></td>
                  <td bgcolor="#FFFFFF"><?php echo $_SERVER['SCRIPT_NAME']; ?></td>
                </tr>
                <tr>
                  <td bgcolor="#FFFFFF"><strong>REQUEST_URI</strong></td>
                  <td bgcolor="#FFFFFF"><?php echo $_SERVER['REQUEST_URI']; ?></td>
                </tr>
                <tr>
                  <td bgcolor="#FFFFFF"><strong>SQL</strong></td>
                  <td bgcolor="#FFFFFF"><?php echo $informacoes["sql"]; ?></td>
                </tr> 
                <tr>
                  <td bgcolor="#FFFFFF"><strong>SCRIPT_FILENAME</strong></td>
                  <td bgcolor="#FFFFFF"><?php echo $_SERVER['SCRIPT_FILENAME']; ?></td>
                </tr>
                <tr>
                  <td bgcolor="#FFFFFF"><strong>SESSION</strong></td>
                  <td bgcolor="#FFFFFF"><?php echo $sessao; ?></td>
                </tr>  
                <tr>
                  <td bgcolor="#FFFFFF"><strong>POST</strong></td>
                  <td bgcolor="#FFFFFF"><?php echo $post; ?></td>
                </tr> 
                <tr>
                  <td bgcolor="#FFFFFF"><strong>GET</strong></td>
                  <td bgcolor="#FFFFFF"><?php echo $get; ?></td>
                </tr>   
            </table>
            </p>
          </div>
        <?php } ?>
      </div>
    </div>
  </div>
</div>
</body>
</html>