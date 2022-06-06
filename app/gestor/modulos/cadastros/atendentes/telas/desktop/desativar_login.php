<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php incluirLib("head", $config, $usuario); ?>
    <link media="screen" href="/assets/plugins/jquery.msg/jquery.msg.css" rel="stylesheet" type="text/css">
    <style type="text/css">
        .botao {
            height: 80px;
            padding-top: 50px;
            padding-bottom: 0px;
            font-size: 15px;
        }
    </style>
</head>
<body>
<? incluirLib("topo", $config, $usuario); ?>
<div class="container-fluid">
    <section id="global">
        <div class="page-header">
            <h1><?= $idioma["pagina_titulo"]; ?> &nbsp;
                <small><?= $idioma["pagina_subtitulo"]; ?></small>
            </h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="/<?= $url[0]; ?>"><?= $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
            <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>"><?= $idioma["nav_modulo"]; ?></a> <span
                    class="divider">/</span></li>
            <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"><?= $idioma["pagina_titulo"]; ?></a> <span
                    class="divider">/</span></li>
            <li class="active"><?php echo $linha["nome"]; ?></li>
            <span class="pull-right"
                  style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
        </ul>
    </section>
    <div class="row-fluid">
        <div class="span12">
            <div class="box-conteudo">
                <div class="pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"
                                           class="btn btn-small"><i
                            class="icon-share-alt"></i> <?= $idioma["btn_sair"]; ?></a></div>
                <h2 class="tituloEdicao"><?php echo $linha["nome"]; ?></h2>

                <div class="tabbable tabs-left">
                    <?php incluirTela("inc_menu_edicao", $config, $linha); ?>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_editar">
                            <h2 class="tituloOpcao"><?php echo $idioma["titulo_opcao"]; ?></h2>
                            <br/>
                            Informe se o login do usuário está ativo ou não.<br/>
                            Desativando o login, o usuário não conseguirá acessar o sistema.<br/>
                            <span style="color:#999999">Essa funcionalidade não remove a conta do usuário.</span>
                            <br/>
                            <br/>

                            <div class="row-fluid">
                                <a href="javascript:void(0);" class="span3 botao btn" id="ativarLogin"
                                   onclick="ativarLogin('S');">Login ativado</a>
                                <a href="javascript:void(0);" class="span3 botao btn" id="desativarLogin"
                                   onclick="ativarLogin('N');">Login desativado</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <? incluirLib("rodape", $config, $usuario); ?>
    <script type="text/javascript" src="/assets/plugins/jquery.msg/jquery.center.min.js"></script>
    <script type="text/javascript" src="/assets/plugins/jquery.msg/jquery.msg.min.js"></script>
    <script type="text/javascript">
        function ativarLogin(ativo) {
            $.msg({
                autoUnblock: false,
                clickUnblock: false,
                klass: 'white-on-black',
                content: 'Processando solicitação.',
                afterBlock: function () {
                    var self = this;
                    jQuery.ajax({
                        url: "/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/json/desativar_login",
                        dataType: "json", //Tipo de Retorno
                        type: "POST",
                        data: {ativo_login: ativo},
                        success: function (json) { //Se ocorrer tudo certo
                            if (json.sucesso) {
                                altualizaBotoes(json.situacao);
                                self.unblock();
                            } else {
                                alert('<?= $idioma["json_erro"]; ?>');
                                self.unblock();
                            }
                        }
                    });
                }
            });
        }
        function altualizaBotoes(situacao) {
            if (situacao == "S") {
                $("#desativarLogin").removeClass("btn-danger");
                $("#ativarLogin").addClass("btn-success");
            } else if (situacao == "N") {
                $("#ativarLogin").removeClass("btn-success");
                $("#desativarLogin").addClass("btn-danger");
            }
        }
        $(document).ready(function () {
            altualizaBotoes('<?= $linha["ativo_login"]; ?>');
        });
    </script>
</div>
</body>
</html>