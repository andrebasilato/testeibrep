<?php

?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $config["tituloEmpresa"]; ?> - <?= $config["tituloSistema"]; ?> - <?= $config["tituloPainel"]; ?> </title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <!--<meta name="viewport" content="width=device-width"> Site sem responsive -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- site com responsivo -->

    <!-- Core Meta Data -->
    <meta name="author" content="AlfamaWeb">

    <!-- Humans -->
    <link rel="author" href="humans.txt">

    <!-- Favicon -->
    <link rel="shortcut icon" href="/assets/img/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="/assets/img/favicon.png" type="image/png">

    <!-- Styles -->
    <link href="/assets/min/aplicacao.aluno.min.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" type="text/css" media="screen" />
    <!--[if IE 7]>
      <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome-ie7.min.css">
    <![endif]-->

    <script src="/assets/aluno_novo/js/jquery-1.10.2.min.js"></script>
    <script src="/assets/aluno_novo/js/jquery-1.9.1.min.js"></script>
    <script src="/assets/aluno_novo/js/jquery-migrate-1.2.1.min.js"></script>
    <script src="/assets/aluno_novo/js/jquery.cycle2.min.js"></script>
    <script src="/assets/aluno_novo/js/vendor/modernizr-2.6.2.min.js"></script>
    <link href="/assets/plugins/jquery.msg/jquery.msg.css" type="text/css" media="screen" />

    <style type="text/css">
        .content, .extra-content {
            max-width: 772.8px;
        }
        .container-fixed {
            padding-left: 0px;
        }
        .half-side-box {
            width: 100%;
        }
    </style>
    
</head>

<body>
    <div class="content print" style="position: relative;">
        <div class="row container-fixed print">
            <div class="box-side box-bg half-side-box">
                <div class="top-box box-verde">
                    <h1>Conteúdo</h1>
                    <i id="icone_favorito" class="icon-heart-empty" style="cursor:pointer;"></i>
                    <i id="icone_contabilizado" class="icon-ok-circle"></i>
                </div>
                <i class="set-icon icon-caret-up"></i>
                <div class="clear"></div>
                <div class="row-fluid">
                    <div class="span12">
                        <div class="abox m-box">
                            <div class="nav-breadcrumb">
                                <div class="nav-breadcrumb-item first-nav">
                                    <span>
                                        <hr>
                                        <div></div>
                                    </span>
                                </div>
                                <div class="nav-breadcrumb-item no-mobile">
                                    <span>
                                        <hr>
                                        <div></div>
                                    </span>
                                </div>
                                <div class="nav-breadcrumb-item"  style="background:#F4F4F4;color:#333;border-bottom:1px #666 solid;margin-top: 0px;">
                                    <a href="#">
                                        <hr>
                                        <div><i class="icon-book"></i></div>
                                    </a>
                                </div>
                                <div class="nav-breadcrumb-item no-mobile">
                                    <span>
                                        <hr>
                                        <div></div>
                                    </span>
                                </div>
                                <div class="nav-breadcrumb-item last-nav">
                                    <span>
                                        <hr>
                                        <div></div>
                                    </span>
                                </div>
                            </div>
                            <div class="clear"></div>
                            <!-- Conteúdo -->
                            <div id="conteudo" class="extra-align contents print">
                                <?php
                                echo $conteudo["conteudo"];
                                ?>
                            </div>
                            <div class="clear"></div>
                            <div class="nav-breadcrumb">
                                <div class="nav-breadcrumb-item first-nav">
                                    <span>
                                        <hr>
                                        <div></div>
                                    </span>
                                </div>
                                <div class="nav-breadcrumb-item no-mobile">
                                    <span>
                                        <hr>
                                        <div></div>
                                    </span>
                                </div>
                                <div class="nav-breadcrumb-item"  style="background:#F4F4F4;color:#333;border-bottom:1px #666 solid;margin-top: 0px;">
                                    <a href="#">
                                        <hr>
                                        <div><i class="icon-book"></i></div>
                                    </a>
                                </div>
                                <div class="nav-breadcrumb-item no-mobile">
                                    <span>
                                        <hr>
                                        <div></div>
                                    </span>
                                </div>
                                <div class="nav-breadcrumb-item last-nav">
                                    <span>
                                        <hr>
                                        <div></div>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>