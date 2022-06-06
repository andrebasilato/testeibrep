<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="icon" href="/assets/img/favicon.ico">
<link href="/assets/css/oraculo.css" rel="stylesheet"></head>
<body>
  <div class='section section-large' id='login-section'>
        <div class='section-header' large='large'>
          <h3><?php echo $linha["nome"]; ?></h3>
        </div>
        <div class='section-body'>
          <? if($linha["corpo_email"]){
           
              echo $linha['layout'];
          }else{
              echo $idioma["vazio_preview"];
          } ?>
        </div>
  </div>
</body>
</html>