<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <link rel="stylesheet" href="/assets/bootstrap_v2.3.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/bootstrap_v2.3.2/css/bootstrap-responsive.css">  
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>
    <!-- 404 -->
    <div class="bg-not">
        <div class="center-not">
            <div class="row-fluid not-found">
                <img src="/assets/img/marca-pb.png" alt="Marca">
                <h1><?= $idioma["404"]; ?></h1>
                <h2><?= $idioma["nao_existe"]; ?></h2>
                <h3><?= $idioma["txt_voltar"]; ?></h3>
                <div class="span12">
                    <a href="/<?= $url[0]; ?>">
                        <div class="btn btn-verde btn-mob btn-medium"><?= $idioma["btn_voltar"]; ?></div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- /404 -->
</body>
</html>