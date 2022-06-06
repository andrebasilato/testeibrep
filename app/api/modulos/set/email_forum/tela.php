<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("head/index.php");; ?>
<script src="<?= $config["urlSistema"]; ?>/assets/js/jquery.1.7.1.min.js"></script>
<script src="<?= $config["urlSistema"]; ?>/assets/bootstrap_v2/js/bootstrap-alert.js"></script>
<link href="/assets/bootstrap_v2/css/bootstrap-responsive.css" rel="stylesheet" />
<link href="/assets/css/oraculo.mobile.css" rel="stylesheet" />
<style type="text/css">
  body {
	border-top: 8px #2980b9 solid;	
  }
  .labelPainel {
	position: absolute;
	top: 16px;
	left: 8px;
	text-transform: uppercase;
	color: #2980b9;
	font-weight:bold;
  }	
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
<div class="labelPainel">Painel da Sindicato de Ensino</div>
<div class='container' id='login'>
  <div id='logo'><a href="<?= $config["websiteEmpresa"]; ?>" title="<?= $config["tituloSistema"]; ?>"><img alt="<?= $config["tituloSistema"]; ?>" src="/especifico/img/logo_empresa.png" /></a></div>
  <div class='row'>
    <div class='span6 offset3'>
      <div class='section section-large' id='login-section'>
        <div class='section-header' large='large'>
          <h3>Atividades no forum.</h3>
          <ul>
            <li><a href="<?= $config["urlSistema"]; ?>" target="_blank"><img src="/assets/img/logo_pequena.png" width="135" height="50" /></a></li>
          </ul>
        </div>
        <div class='section-body'>
		  <? if ($_GET["msg"] == 2) { ?>
            <div class="alert alert-error fade in">
              <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
              <strong>Erro ao enviar solicitação.</strong>
            </div>          
          <? } elseif( $_GET["msg"] == 1) { ?>
            <div class="alert alert-success" style="width:400px;">
              <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
              <strong>Solicitação enviada com sucesso.</strong>
            </div>
          <? } ?>
          <?php if($linha["ativo"] == 'S'){ ?>
          <form method="post" onsubmit="return validaForm();">
          <p>Olá, <strong><?php echo $linha["nome"]; ?></strong> marque a opção abaixo e confirme caso não queira receber informações do tópico: <strong><?php echo $linha["topico"] ?></strong>, forum: <strong><?php echo $linha["forum"]; ?>.</strong></p>
            <label class="checkbox inline">
  				<input type="checkbox" id="receber" name="receber"  value="option1"> Não quero receber mais informaçoes do tópico: <strong><?php echo $linha["topico"]; ?></strong>, forum: <strong><?php echo $linha["forum"]; ?></strong>
			</label>
            <div class='form-actions'>
              <div class='row'>
                <div class='span2'>
                  <input name="acao" type="hidden" id="acao" value="naoreceber" />               
                  <input class="btn btn-primary" name="commit" type="submit" value="Confirmar" />
                </div>
              </div>
            </div>
            <p><a href="/<?=$url[0];?>" style="color:#999999" /><?=$idioma["efetuar_login"];?></a></p>
          </form>
          <?php }elseif(!$_GET["msg"]){ ?>
          <p>Olá, <strong><?php echo $linha["nome"]; ?></strong> solicitação já executada anteriomente.</p>
          <?php }else{ ?>
           <p>Obrigado, <strong><?php echo $linha["nome"]; ?></strong>.</p>
          <?php } ?>
           <script language="javascript" type="text/javascript">
           function validaForm() {		   
			 if (!document.getElementById("receber").checked) {                                    
            	alert('Você precisa marcar a confirmação!');
                document.getElementById("receber").focus();
				return false;                
			 }
		   }
		   </script>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>