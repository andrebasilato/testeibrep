<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usuario); ?>
<link rel="stylesheet" href="/assets/plugins/fcbkcomplete/style.css" type="text/css" media="screen" charset="utf-8" />
    <style>
        .botao {
            height:80px;
            padding-top: 50px;
            padding-bottom:0px;
            font-size:25px;
        }
    </style>
</head>
<body>
<?php incluirLib("topo",$config,$usuario); ?>
<div class="container-fluid">
	<section id="global">
		<div class="page-header">
    		<h1><?= $idioma["pagina_titulo"]; ?> &nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1>
  		</div>
  		<ul class="breadcrumb">
      		<li><a href="/<?= $url[0]; ?>"><?= $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
            <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>"><?= $idioma["nav_modulo"]; ?></a> <span class="divider">/</span></li>
            <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"><?= $idioma["pagina_titulo"]; ?></a> <span class="divider">/</span></li>
            <li class="active"><?php echo $idioma["titulo_opcao"]; ?></li>
      		<span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
  		</ul>
  	</section>
  	
        <div class="row-fluid">
            <div class="span12">
                <div class="box-conteudo">
                    <div class=" pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"><i class="icon-share-alt"></i> <?= $idioma["sair_edicao"]; ?></a></div>
                    <div class="tabbable tabs-left">
                        <?php if($url[3] != "cadastrar") { incluirTela("inc_menu_edicao",$config,$linha); } ?>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_editar">
                                <h2 class="tituloOpcao"><?= $idioma['titulo_opcao']; ?></h2>
                                <?= $idioma['texto_explicativo']; ?>
                                <br />
                                <br />
                                <div class="row-fluid">
                                    <a href="javascript:void(0);" class="span3 botao btn" id="disponibilizarEmp" onclick="bloquearAcesso('N');"><?= $idioma['disponivel']; ?></a>
                                    <a href="javascript:void(0);" class="span3 botao btn" id="indisponibilizarEmp" onclick="bloquearAcesso('S');"><?= $idioma['indisponivel']; ?></a>
                                </div>
                                <div class="row-fluid" id="historicoDisponibilizacao" style="margin-top:20px; max-height:320px; overflow:auto; width:auto"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
  	<? incluirLib("rodape",$config,$usuario); ?>
    	<script type="text/javascript" src="/assets/plugins/jquery.msg/jquery.center.min.js"></script>
        <script type="text/javascript" src="/assets/plugins/jquery.msg/jquery.msg.min.js"></script>
        <script type="text/javascript">
            function bloquearAcesso(situacao) {
                $.msg({
                    autoUnblock : false,
                    clickUnblock : false,
                    klass : 'white-on-black',
                    content: 'Processando solicitação.',
                    afterBlock : function() {
                        var self = this;
                        jQuery.ajax({
                            url: "/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/json/bloquear",
                            dataType: "json", //Tipo de Retorno
                            type: "POST",
                            data: {bloquear: situacao},
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
                if (situacao == "N") {
                    $("#indisponibilizarEmp").removeClass("btn-danger");
                    $("#disponibilizarEmp").addClass("btn-success");
                } else {
                    $("#disponibilizarEmp").removeClass("btn-success");
                    $("#indisponibilizarEmp").addClass("btn-danger");
                }
            }
            function exibeHistorico() {

                var htmlHistorico;
                htmlHistorico = "";

                jQuery.ajax({
                    url: "/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/json/bloquear_historico",
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
                altualizaBotoes('<?= $linha['acesso_bloqueado']; ?>');
            });
        </script>
    
</div>
</body>
</html>