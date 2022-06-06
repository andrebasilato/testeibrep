<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <?php incluirLib('head', $config, $usuario); ?>
        <link media="screen" href="/assets/plugins/jquery.msg/jquery.msg.css" rel="stylesheet" type="text/css" />
        <style>
            .botao {
                height:80px;
                padding-top: 50px;
                padding-bottom:0px;
                font-size:25px;
            }
        </style>
    </head>
    <body style="padding-top: 2px;" class="box-body-iframe">
        <div class="row-fluid">
            <div class="span12">
                <div class="box-conteudo">
                    <div class="tabbable tabs-left">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_editar">
                                <h2 class="tituloOpcao"><?= $idioma['titulo_opcao']; ?></h2>
                                <?= $idioma['texto_explicativo']; ?>
                                <br />
                                <br />
                                <div class="row-fluid">
                                    <a href="javascript:void(0);" class="span3 botao btn" id="disponibilizarEmp" onclick="disponibilizarEmpreendimento('S');"><?= $idioma['disponivel']; ?></a>
                                    <a href="javascript:void(0);" class="span3 botao btn" id="indisponibilizarEmp" onclick="disponibilizarEmpreendimento('N');"><?= $idioma['indisponivel']; ?></a>
                                </div>
                                <div class="row-fluid" id="historicoDisponibilizacao" style="margin-top:20px; max-height:320px; overflow:auto; width:auto"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php require 'rodape.php'; ?>
        <script type="text/javascript" src="/assets/plugins/jquery.msg/jquery.center.min.js"></script>
        <script type="text/javascript" src="/assets/plugins/jquery.msg/jquery.msg.min.js"></script>
        <script type="text/javascript">
            function disponibilizarEmpreendimento(situacao) {
                $.msg({
                    autoUnblock : false,
                    clickUnblock : false,
                    klass : 'white-on-black',
                    content: 'Processando solicitação.',
                    afterBlock : function() {
                        var self = this;
                        jQuery.ajax({
                            url: "/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/json/disponibilizar",
                            dataType: "json", //Tipo de Retorno
                            type: "POST",
                            data: {disponibilizar: situacao},
                            success: function(json) { //Se ocorrer tudo certo
                                if (json.sucesso) {
                                    altualizaBotoes(json.situacao);
                                    exibeHistorico();
                                    self.unblock();
                                } else {
                                    alert('<?= $idioma['json_erro']; ?>');
                                    self.unblock();
                                }
                            }
                        });
                    }
                });
            }
            function altualizaBotoes(situacao) {
                if (situacao == "S") {
                    $("#indisponibilizarEmp").removeClass("btn-danger");
                    $("#disponibilizarEmp").addClass("btn-success");
                } else if (situacao == "N") {
                    $("#disponibilizarEmp").removeClass("btn-success");
                    $("#indisponibilizarEmp").addClass("btn-danger");
                }
            }
            function exibeHistorico() {

                var htmlHistorico;
                htmlHistorico = "";

                jQuery.ajax({
                    url: "/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/json/disponibilizar_historico",
                    dataType: "json", //Tipo de Retorno
                    type: "POST",
                    data: {},
                    success: function(json) { //Se ocorrer tudo certo
                        htmlHistorico = "";
                        htmlHistorico = "<table border=\"0\" cellspacing=\"0\" cellpadding=\"8\" class=\"table tabelaSemTamanho\">";
                        htmlHistorico += "<thead><tr>";
                        htmlHistorico += "  <th>Usuário</th>";
                        htmlHistorico += "  <th>Quando</th>";
                        htmlHistorico += "  <th>Situação</th>";
                        htmlHistorico += "</tr></thead>";

                        $.each(json, function(i, item) {
                            htmlHistorico += "<tr>";
                            htmlHistorico += "  <td>"+item.nome+"</td>";
                            htmlHistorico += "  <td>"+item.quando+"</td>";
                            htmlHistorico += "  <td>"+item.situacao+"</td>";
                            htmlHistorico += "</tr>";
                        });

                        htmlHistorico += "</table>";

                        $("#historicoDisponibilizacao").html(htmlHistorico);
                    }
                });
            }

            exibeHistorico();

            $(document).ready(function() {
                altualizaBotoes('<?= $linha['disponivel']; ?>');
            });
        </script>
    </body>
</html>