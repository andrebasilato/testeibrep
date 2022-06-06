<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <link rel="stylesheet" href="/assets/bootstrap_v2.3.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/bootstrap_v2.3.2/css/bootstrap-responsive.css">  
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>
    <!-- OPS -->
    <div class="bg-not">
        <div class="center-not">
            <div class="row-fluid not-found">
                <img src="/assets/img/marca-pb.png" alt="Marca">
                <h1><?= $idioma["ops"]; ?></h1>
                <h4><?= $idioma["mensagem"]; ?></h4>
                <div class="span12">
                    <a href="/<?= $url[0]; ?>">
                        <div class="btn btn-verde btn-mob btn-medium"><?= $idioma["btn_voltar"]; ?></div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- /OPS -->
    <?php
    //Caso esteja local ou em homologação exibe os erro do OPS
    if($_SERVER["DOCUMENT_ROOT"] == "C:/Servidor04/www/oraculo/desenvolvimento") {
        ?>
        <div class='section-body'>
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
        </div>
        <?php
    }
    ?>
</body>
</html>