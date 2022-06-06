<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php incluirLib("head", $config, $usuario); ?>
    <link media="screen" href="/assets/plugins/jquery.msg/jquery.msg.css" rel="stylesheet" type="text/css">
    <style type="text/css">
        .botao {
            height: 20px;
        }
    </style>
</head>
<body>
<?php incluirLib("topo", $config, $usuario); ?>
<div class="container-fluid">
    <section id="global">
        <div class="page-header">
            <h1><?= $idioma["pagina_titulo"]; ?> &nbsp;
                <small><?= $idioma["pagina_subtitulo"]; ?></small>
            </h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="/<?= $url[0]; ?>"><?= $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
            <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>"><?= $idioma["nav_configuracoes"]; ?></a> <span
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
                    <?php incluirTela("inc_menu_edicao", $config, $usuario); ?>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_editar">
                            <h2 class="tituloOpcao"><?= $idioma["titulo_opcao"]; ?></h2>

                            Informe se o usuário receberá ou não os tipos de e-mails.<br/>
                            <span
                                style="color:#999999">Escolha o tipo de e-mail abaixo, e defina se o usuário receberá.</span>
                            <br/>
                            <br/>

                            <table class="table">
                                <tr style="background-color:#f5f5f5;">
                                    <th width="60%"><?=$idioma['email_automaticos'];?></th>
                                    <th width="15%">Opção</th>
                                </tr>
                                <tr>
                                    <td width="70%">Quantidade de dias em que uma matrícula fica em determinada
                                        situação
                                    </td>
                                    <td>
                                        <div class="row-fluid">
                                            <a href="javascript:void(0);" class="span4 botao btn"
                                               id="ativar_qtd_dias_sit"
                                               onclick="ativarEmail('S', 'qtd_dias_sit');">Sim</a>
                                            <a href="javascript:void(0);" class="span4 botao btn"
                                               id="desativar_qtd_dias_sit" onclick="ativarEmail('N', 'qtd_dias_sit');">Não</a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="70%">Matrículas alteradas para Homologar certificado ou Curso concluído
                                    </td>
                                    <td>
                                        <div class="row-fluid">
                                            <a href="javascript:void(0);" class="span4 botao btn"
                                               id="ativar_recebe_email_homologacao"
                                               onclick="ativarEmail('S', 'recebe_email_homologacao');">Sim</a>
                                            <a href="javascript:void(0);" class="span4 botao btn"
                                               id="desativar_recebe_email_homologacao" onclick="ativarEmail('N', 'recebe_email_homologacao');">Não</a>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <table class="table">
                                <tr style="background-color:#f5f5f5;">
                                    <th width="60%"><?=$idioma['relatorios_automaticos'];?></th>
                                    <th width="15%">Opção</th>
                                </tr>
                                <tr>
                                    <td width="70%">Receber gráficos gerenciais na segunda-feira
                                    </td>
                                    <td>
                                        <div class="row-fluid">
                                            <a href="javascript:void(0);" class="span4 botao btn"
                                               id="ativar_relatorio_gerencial_segunda"
                                               onclick="ativarEmail('S', 'relatorio_gerencial_segunda');">Sim</a>
                                            <a href="javascript:void(0);" class="span4 botao btn"
                                               id="desativar_relatorio_gerencial_segunda" onclick="ativarEmail('N', 'relatorio_gerencial_segunda');">Não</a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="70%">Receber gráficos gerenciais na terça-feira
                                    </td>
                                    <td>
                                        <div class="row-fluid">
                                            <a href="javascript:void(0);" class="span4 botao btn"
                                               id="ativar_relatorio_gerencial_terca"
                                               onclick="ativarEmail('S', 'relatorio_gerencial_terca');">Sim</a>
                                            <a href="javascript:void(0);" class="span4 botao btn"
                                               id="desativar_relatorio_gerencial_terca" onclick="ativarEmail('N', 'relatorio_gerencial_terca');">Não</a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="70%">Receber gráficos gerenciais na quarta-feira
                                    </td>
                                    <td>
                                        <div class="row-fluid">
                                            <a href="javascript:void(0);" class="span4 botao btn"
                                               id="ativar_relatorio_gerencial_quarta"
                                               onclick="ativarEmail('S', 'relatorio_gerencial_quarta');">Sim</a>
                                            <a href="javascript:void(0);" class="span4 botao btn"
                                               id="desativar_relatorio_gerencial_quarta" onclick="ativarEmail('N', 'relatorio_gerencial_quarta');">Não</a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="70%">Receber gráficos gerenciais na quinta-feira
                                    </td>
                                    <td>
                                        <div class="row-fluid">
                                            <a href="javascript:void(0);" class="span4 botao btn"
                                               id="ativar_relatorio_gerencial_quinta"
                                               onclick="ativarEmail('S', 'relatorio_gerencial_quinta');">Sim</a>
                                            <a href="javascript:void(0);" class="span4 botao btn"
                                               id="desativar_relatorio_gerencial_quinta" onclick="ativarEmail('N', 'relatorio_gerencial_quinta');">Não</a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="70%">Receber gráficos gerenciais na sexta-feira
                                    </td>
                                    <td>
                                        <div class="row-fluid">
                                            <a href="javascript:void(0);" class="span4 botao btn"
                                               id="ativar_relatorio_gerencial_sexta"
                                               onclick="ativarEmail('S', 'relatorio_gerencial_sexta');">Sim</a>
                                            <a href="javascript:void(0);" class="span4 botao btn"
                                               id="desativar_relatorio_gerencial_sexta" onclick="ativarEmail('N', 'relatorio_gerencial_sexta');">Não</a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="70%">Receber gráficos gerenciais no sábado
                                    </td>
                                    <td>
                                        <div class="row-fluid">
                                            <a href="javascript:void(0);" class="span4 botao btn"
                                               id="ativar_relatorio_gerencial_sabado"
                                               onclick="ativarEmail('S', 'relatorio_gerencial_sabado');">Sim</a>
                                            <a href="javascript:void(0);" class="span4 botao btn"
                                               id="desativar_relatorio_gerencial_sabado" onclick="ativarEmail('N', 'relatorio_gerencial_sabado');">Não</a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="70%">Receber gráficos gerenciais no domingo
                                    </td>
                                    <td>
                                        <div class="row-fluid">
                                            <a href="javascript:void(0);" class="span4 botao btn"
                                               id="ativar_relatorio_gerencial_domingo"
                                               onclick="ativarEmail('S', 'relatorio_gerencial_domingo');">Sim</a>
                                            <a href="javascript:void(0);" class="span4 botao btn"
                                               id="desativar_relatorio_gerencial_domingo" onclick="ativarEmail('N', 'relatorio_gerencial_domingo');">Não</a>
                                        </div>
                                    </td>
                                </tr>
                            </table>
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
        function ativarEmail(ativo, tipo) {
            $.msg({
                autoUnblock: false,
                clickUnblock: false,
                klass: 'white-on-black',
                content: 'Processando solicitação.',
                afterBlock: function () {
                    var self = this;
                    jQuery.ajax({
                        url: "/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/json/alterar_emails",
                        dataType: "json", //Tipo de Retorno
                        type: "POST",
                        data: {ativo_email: ativo, tipo_email: tipo},
                        success: function (json) { //Se ocorrer tudo certo
                            if (json.sucesso) {
                                //alert(json.mensagem);
                                altualizaBotoes(json.situacao, 'ativar_' + tipo, 'desativar_' + tipo);
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
        function altualizaBotoes(situacao, id_ativo, id_inativo) {
            if (situacao == "S") {
                $("#" + id_inativo).removeClass("btn-danger");
                $("#" + id_ativo).addClass("btn-success");
            } else if (situacao == "N") {
                $("#" + id_ativo).removeClass("btn-success");
                $("#" + id_inativo).addClass("btn-danger");
            }
        }
        $(document).ready(function () {
            altualizaBotoes('<?= $linha["receber_email_matricula_situacao"]; ?>', 'ativar_qtd_dias_sit', 'desativar_qtd_dias_sit');
            altualizaBotoes('<?= $linha["recebe_email_homologacao"]; ?>', 'ativar_recebe_email_homologacao', 'desativar_recebe_email_homologacao');

            altualizaBotoes('<?= $linha["receber_email_relatorio_gerencial_segunda"]; ?>', 'ativar_relatorio_gerencial_segunda', 'desativar_relatorio_gerencial_segunda');
            altualizaBotoes('<?= $linha["receber_email_relatorio_gerencial_terca"]; ?>', 'ativar_relatorio_gerencial_terca', 'desativar_relatorio_gerencial_terca');
            altualizaBotoes('<?= $linha["receber_email_relatorio_gerencial_quarta"]; ?>', 'ativar_relatorio_gerencial_quarta', 'desativar_relatorio_gerencial_quarta');
            altualizaBotoes('<?= $linha["receber_email_relatorio_gerencial_quinta"]; ?>', 'ativar_relatorio_gerencial_quinta', 'desativar_relatorio_gerencial_quinta');
            altualizaBotoes('<?= $linha["receber_email_relatorio_gerencial_sexta"]; ?>', 'ativar_relatorio_gerencial_sexta', 'desativar_relatorio_gerencial_sexta');
            altualizaBotoes('<?= $linha["receber_email_relatorio_gerencial_sabado"]; ?>', 'ativar_relatorio_gerencial_sabado', 'desativar_relatorio_gerencial_sabado');
            altualizaBotoes('<?= $linha["receber_email_relatorio_gerencial_domingo"]; ?>', 'ativar_relatorio_gerencial_domingo', 'desativar_relatorio_gerencial_domingo');            

        });
    </script>
</div>
</body>
</html>
