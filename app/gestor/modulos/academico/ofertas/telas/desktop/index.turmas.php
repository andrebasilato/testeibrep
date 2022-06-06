<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usuario); ?>
<link media="screen" href="/assets/plugins/jquery.msg/jquery.msg.css" rel="stylesheet" type="text/css">
</head>
<body>
	<? incluirLib("topo",$config,$usuario); ?>
    <div class="container-fluid">
        <section id="global">
            <div class="page-header"><h1><?= $idioma["pagina_titulo"]; ?> &nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1></div>
            <ul class="breadcrumb">
                <li><a href="/<?= $url[0]; ?>"><?= $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
                <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>"><?= $idioma["nav_modulo"]; ?></a> <span class="divider">/</span></li>
                <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"><?= $idioma["pagina_titulo"]; ?></a> <span class="divider">/</span></li>
                <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $linha["idava"]; ?>/editar"><?= $linha["nome"]; ?></a> <span class="divider">/</span> </li>
                <li class="active"><?= $idioma["pagina_titulo_interno"]; ?></li>
                <span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
            </ul>
        </section>
        <div class="row-fluid">
            <div class="span12">
                <div class="box-conteudo">
                    <div class=" pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"><i class="icon-share-alt"></i> <?= $idioma["btn_sair"]; ?></a></div>
                    <h2 class="tituloEdicao"><?= $linha["nome"]; ?> <? /* <small>(<?= $linha["email"]; ?>)</small> */ ?></h2>
                    <div class="tabbable tabs-left">
                        <?php incluirTela("inc_menu_edicao",$config,$linha); ?>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_editar">
                                <h2 class="tituloOpcao"><?= $idioma["titulo_opcao"]; ?></h2>
                                <? if($_POST["msg"]) { ?>
                                    <div class="alert alert-success fade in">
                                        <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                                        <strong><?= $idioma[$_POST["msg"]]; ?></strong>
                                    </div>
                                <? } ?>
                                <? if(count($salvar["erros"]) > 0){ ?>
                                    <div class="alert alert-error fade in">
                                        <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                                        <strong><?= $idioma["form_erros"]; ?></strong>
                                        <? foreach($salvar["erros"] as $ind => $val) { ?>
                                            <br />
                                            <?php echo $idioma[$val]; ?>
                                        <? } ?>
                                    </div>
                                <? } ?>
                                <form class="well" method="post" id="form">
                                    <p><?= $idioma["form_associar"]; ?></p>
                                    <?php if($perfil["permissoes"][$url[2]."|5"]) { ?>    
                                        <input name="turma" type="text" class="span4" />
                                        &nbsp;
                                        <input type="hidden" id="acao" name="acao" value="cadastrar_turma">
                                        <input type="submit" class="btn" value="<?= $idioma["btn_adicionar"]; ?>" />
                                    <?php } else { ?>
                                        <select id="turma" name="turmas" disabled="disabled"></select>
                                        <br />
                                        <a href="javascript:void(0);" rel="tooltip" data-original-title="<?= $idioma["btn_permissao_inserir"]; ?>" data-placement="right" class="btn disabled"><?= $idioma["btn_adicionar"]; ?></a>
                                    <?php } ?>
                                </form>
                                <br />
                                <form method="post" id="remover_turma" name="remover_turma">
                                    <input type="hidden" id="acao" name="acao" value="remover_turma">
                                    <input type="hidden" id="remover" name="remover" value="">
                                </form>
                                <?php $linhaObj->GerarTabela($dadosArray,$_GET["q"],$idioma,"listagem_turmas"); ?>
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
			function ativarInativar(oferta, turma){
				$.msg({ 
					autoUnblock : false,
					clickUnblock : false,
					klass : 'white-on-black',
					content: 'Processando solicitação.',
					afterBlock : function(){
						var self = this;
						jQuery.ajax({
							url: "/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/json/ativardesativar",
							dataType: "json", //Tipo de Retorno
							type: "POST",
							data: {idoferta: oferta, idturma: turma},
							success: function(json){ //Se ocorrer tudo certo
								if(json.sucesso){
									altualizaBotoes(json.ativo, json.turma);
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
    
			function altualizaBotoes(ativo, turma) {
				if(ativo == "S"){
					$("#ativo_painel"+turma).removeClass("label-important");
					$("#ativo_painel"+turma).addClass("label-success");
					$("#ativo_painel"+turma).html("Sim");
				} else if(ativo == "N") {
					$("#ativo_painel"+turma).removeClass("label-success");
					$("#ativo_painel"+turma).addClass("label-important");
					$("#ativo_painel"+turma).html("Não");
				}
			}
				
            function remover(id) {
                confirma = confirm('<?= $idioma["confirmar_remocao"]; ?>');
                if(confirma) {
                    document.getElementById("remover").value = id;
                    document.getElementById("remover_turma").submit();
                } 
            }
        </script>
    </div>
</body>
</html>