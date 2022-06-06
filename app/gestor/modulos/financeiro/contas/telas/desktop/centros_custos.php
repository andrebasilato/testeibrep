<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php incluirLib("head", $config, $usuario); ?>
    <link rel="stylesheet" href="/assets/plugins/fcbkcomplete/style.css" type="text/css" media="screen"
          charset="utf-8"/>
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
        <div class="span9">
            <div class="box-conteudo">
                <div class=" pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"
                                            class="btn btn-small"><i
                            class="icon-share-alt"></i> <?= $idioma["btn_sair"]; ?></a></div>
                <h2 class="tituloEdicao"><?= $linha["nome"]; ?></h2>

                <div class="tabbable tabs-left">
                    <?php incluirTela("inc_menu_edicao", $config, $usuario); ?>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_editar">
                            <h2 class="tituloOpcao"><?= $idioma["titulo_opcao"]; ?></h2>

                            <div id="listagem_informacoes">
                                <?php /*<h4>Sindicato: &nbsp;<small><?= $linha['sindicato'] ?></small> </h4>*/ ?>
                                <?= $idioma["texto_explicativos"]; ?>
                            </div>
                            <? if ($_POST["msg"]) { ?>
                                <div class="alert alert-success fade in">
                                    <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                                    <strong><?= $idioma[$_POST["msg"]]; ?></strong>
                                </div>
                            <? } ?>
                            <? if (count($salvar["erros"]) > 0) { ?>
                                <div class="alert alert-error fade in">
                                    <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                                    <strong><?= $idioma["form_erros"]; ?></strong>
                                    <? foreach ($salvar["erros"] as $ind => $val) { ?>
                                        <br/>
                                        <?php echo $idioma[$val]; ?>
                                    <? } ?>
                                </div>
                            <? } ?>
                            <form class="well" method="post" id="form">
                                <p><?= $idioma["form_associar"]; ?></p>
                                <?php if ($perfil["permissoes"][$url[2] . "|2"]) { ?>
                                    <select id="centros_custos" name="centros_custos"></select>
                                    <br/>
                                    <br/>
                                    <input type="hidden" id="acao" name="acao" value="associar_centros_custos">
                                    <input type="submit" class="btn" value="<?= $idioma["btn_adicionar"]; ?>"/>
                                <?php } else { ?>
                                    <select id="centros_custos" name="centros_custos" disabled="disabled"></select>
                                    <br/>
                                    <br/>
                                    <a href="javascript:void(0);" rel="tooltip"
                                       data-original-title="<?= $idioma["btn_permissao_inserir"]; ?>"
                                       data-placement="right" class="btn disabled"><?= $idioma["btn_adicionar"]; ?></a>
                                <?php } ?>
                            </form>
                            <br/>

                            <form method="post" id="remover_centro_custo" name="remover_centro_custo">
                                <input type="hidden" id="acao" name="acao" value="remover_centro_custo">
                                <input type="hidden" id="remover" name="remover" value="">
                            </form>
							
							<div style="padding-left:10px;padding-bottom:20px;">
								<strong>Valor Líquido:</strong> <?php echo number_format(abs($linha['valor']) + $linha['valor_juros'] + $linha['valor_multa'] + $linha['valor_outro'] - $linha['valor_desconto'], 2, ',' ,'.'); ?>
							</div>							
							
                            <table class="table table-striped tabelaSemTamanho">
                                <thead>
                                <tr>
                                    <th><?= $idioma["listagem_centro_custo"]; ?></th>
									<th><?= $idioma["listagem_valor"]; ?></th>
                                    <th><?= $idioma["listagem_porcentagem"]; ?></th>
                                    <th width="60"><?= $idioma["listagem_opcoes"]; ?></th>
                                </tr>
                                </thead>
                                <form method="post">
                                    <input type="hidden" name="acao" value="salvar_porcentagens_centros_custos"/>
                                    <tbody id="tabela_centros_custos">
                                    <?php if (count($associacoesArray) > 0) { ?>
                                        <?php foreach ($associacoesArray as $ind => $associacao) { ?>
                                            <tr>
                                                <td><?php echo $associacao["nome"]; ?></td>
												<td><input class="span2 valor" type="text"
                                                           id="centros_custos_valor_<?php echo $associacao['idcentro_custo']; ?>"
                                                           name="centros_custos_array[<?= $associacao['idconta_centro_custo']; ?>][valor]"
                                                           value="<?php echo number_format($associacao['valor'], 2, ',', '.'); ?>"
														   onkeyup="alteraPorcentagem()"		/>
                                                </td>
                                                <td><input class="span2 porcentagem" type="text"
                                                           id="centros_custos_porcentagem_<?php echo $associacao['idcentro_custo']; ?>"
                                                           name="centros_custos_array[<?= $associacao['idconta_centro_custo']; ?>][porcentagem]"
                                                           value="<?php echo number_format($associacao['porcentagem'], 2, ',', '.'); ?>"
														   onkeyup="alteraValor()"	/>
                                                </td>
                                                <td>
                                                    <?php if ($perfil["permissoes"][$url[2] . "|2"]) { ?>
                                                        <a href="javascript:void(0);" class="btn btn-mini"
                                                           data-original-title="<?= $idioma["btn_remover"]; ?>"
                                                           data-placement="left" rel="tooltip"
                                                           onclick="remover(<?php echo $associacao["idconta_centro_custo"]; ?>)"><i
                                                                class="icon-remove"></i></a>
                                                    <?php } else { ?>
                                                        <a href="javascript:void(0);" class="btn btn-mini disabled"
                                                           data-original-title="<?= $idioma["btn_remover_permissao_excluir"]; ?>"
                                                           data-placement="left" rel="tooltip"><i
                                                                class="icon-remove"></i></a>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <tr>
                                            <td colspan="5"><?= $idioma["sem_informacao"]; ?></td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <td colspan="5"><input class="btn" type="submit" value="Salvar"/></td>
                                    </tr>
                                    </tbody>
                                </form>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="span3">
            <? if ($linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2", NULL)) { ?>
                <div class="well">
                    <?= $idioma["nav_cadastrar_explica"]; ?>
                    <br/>
                    <br/>
                    <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/cadastrar"
                       class="btn primary"><?= $idioma["nav_cadastrar"]; ?></a>
                </div>
            <? } ?>
            <?php incluirLib("sidebar_" . $url[1], $config); ?>
        </div>
    </div>
    <? incluirLib("rodape", $config, $usuario); ?>
    <script src="/assets/plugins/fcbkcomplete/jquery.fcbkcomplete.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#centros_custos").fcbkcomplete({
                json_url: "/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/json/associar_centros_custos/<?= $linha['idconta']; ?>",
                addontab: true,
                height: 10,
                maxshownitems: 10,
                cache: true,
                maxitems: 20,
                filter_selected: true,
                firstselected: true,
                input_min_size: 1,
                complete_text: "<?= $idioma["mensagem_select"]; ?>",
                addoncomma: true
            });
        });

        <?php if(count($associacoesArray)) {
          foreach($associacoesArray as $mat) { ?>
		    $("#<?= 'centros_custos_valor_'.$mat["idcentro_custo"]; ?>").maskMoney({decimal: ",", thousands: ".", precision: 2, allowZero: true});
			$("#<?= 'centros_custos_porcentagem_'.$mat["idcentro_custo"]; ?>").maskMoney({decimal: ",", thousands: ".", precision: 2, allowZero: true});
        <?php } } ?>

        function remover(id) {
            confirma = confirm('<?= $idioma["confirmar_remocao"]; ?>');
            if (confirma) {
                document.getElementById("remover").value = id;
                document.getElementById("remover_centro_custo").submit();
            }
        }		

		function alteraPorcentagem() {
			array_inputs = new Array();
			var valor_total = 0;
			var total_centros = <?php echo count($associacoesArray); ?>;
			array_inputs = document.getElementById('tabela_centros_custos').getElementsByTagName('input');
			tamanho = array_inputs.length;
			for (i=0; i<tamanho; i++) {
				if (array_inputs[i].type == 'text') {
					if (array_inputs[i].className.indexOf("valor") > 0) {
						var valor = array_inputs[i].value;
						valor = valor.replace(".", "");
						valor = valor.replace(".", "");
						valor = valor.replace(",", ".");
						valor = parseFloat(valor);
						valor_total += valor;
						total_centros++;
					}
				}				
			}

			for (i=0; i<tamanho; i++) {
				if (array_inputs[i].type == 'text') {
					if (array_inputs[i].className.indexOf("valor") > 0) {
						var valor = array_inputs[i].value;
						valor = valor.replace(".", "");
						valor = valor.replace(".", "");
						valor = valor.replace(",", ".");
						valor = parseFloat(valor);
						
						porc_valor = ((valor*100)/valor_total);
						
						id_array = array_inputs[i].id.split("_");
						id_alterar = 'centros_custos_porcentagem_' + id_array[id_array.length-1];						
						
						nova_porcentagem = number_format( porc_valor, 2, ',', '.' );
						document.getElementById(id_alterar).value = nova_porcentagem;
					}
				}				
			}
		}
		
		function alteraValor() {
			array_inputs = new Array();
			var valor_total = <?php echo (float) (abs($linha['valor']) + $linha['valor_juros'] + $linha['valor_multa'] + $linha['valor_outro'] - $linha['valor_desconto']); ?>;
			var total_centros = <?php echo count($associacoesArray); ?>;
			array_inputs = document.getElementById('tabela_centros_custos').getElementsByTagName('input');
			tamanho = array_inputs.length;
			/*for (i=0; i<tamanho; i++) {
				if (array_inputs[i].type == 'text') {
					if (array_inputs[i].className.indexOf("valor") > 0) {
						var valor = array_inputs[i].value;
						valor = valor.replace(".", "");
						valor = valor.replace(".", "");
						valor = valor.replace(",", ".");
						valor = parseFloat(valor);
						valor_total += valor;
						total_centros++;
					}
				}				
			}*/

			for (i=0; i<tamanho; i++) {
				if (array_inputs[i].type == 'text') {
					if (array_inputs[i].className.indexOf("porcentagem") > 0) {
						var porcentagem = array_inputs[i].value;
						porcentagem = porcentagem.replace(".", "");
						porcentagem = porcentagem.replace(".", "");
						porcentagem = porcentagem.replace(",", ".");
						porcentagem = parseFloat(porcentagem);
						
						porc_valor = ((porcentagem*valor_total)/100);
						
						id_array = array_inputs[i].id.split("_");
						id_alterar = 'centros_custos_valor_' + id_array[id_array.length-1];						
						
						novo_valor = number_format( porc_valor, 2, ',', '.' );
						//console.log(valor_total + ' - ' + array_inputs[i].value + ' - ' + novo_valor);
						document.getElementById(id_alterar).value = novo_valor;
					}
				}				
			}
		}

    </script>
</div>
</body>
</html>