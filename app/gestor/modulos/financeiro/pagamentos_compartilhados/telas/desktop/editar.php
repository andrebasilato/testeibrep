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
        <div class="span12">
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
                            
							<?php if($mensagem["erro"]) { ?>
							  <div class="alert alert-error">
								<a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
								<?= $idioma[$mensagem["erro"]]; ?>
							  </div>						
							<? } ?>
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

                            <form method="post">
                                <input name="acao" type="hidden" value="editar_contas"/>

                                <section id="salvar_contas">
                                    <legend>Contas</legend>
                                    <br/>
                                    <table border="0" width="100%"
                                           class="table table-bordered table-condensed tabelaSemTamanho">
                                        <tr>
                                            <th bgcolor="#F4F4F4">Id.</th>
                                            <th bgcolor="#F4F4F4">Nome</th>
                                            <th bgcolor="#F4F4F4">Vencimento</th>
                                            <th bgcolor="#F4F4F4">Valor</th>
                                            <th bgcolor="#F4F4F4">Situação</th>
											<th bgcolor="#F4F4F4">Opções</th>
                                            <?php /*<th bgcolor="#F4F4F4">Pagamento</th>
								<th bgcolor="#F4F4F4">Valor pago</th>*/
                                            ?>
                                        </tr>

                                        <?php foreach ($contas as $conta) { ?>
                                            <tr>
                                                <td style="padding:10px;">
                                                    <?= $conta['idconta']; ?>
                                                    <input type="hidden"
                                                           name="contas_array[<?= $conta['idconta']; ?>][numero]"
                                                           value="<?= $conta['idconta']; ?>"/>
                                                </td>
                                                <td>
                                                    <input class="span3" type="text"
                                                           name="contas_array[<?= $conta['idconta']; ?>][nome]"
                                                           value="<?php echo $conta['nome']; ?>"/>
                                                </td>
                                                <td>
                                                    <input class="span2" type="text"
                                                           id="parcelas_vencimento_<?= $conta['idconta']; ?>"
                                                           name="contas_array[<?= $conta['idconta']; ?>][vencimento]"
                                                           value="<?= formataData($conta['data_vencimento'], 'pt', 0); ?>"/>
                                                </td>
                                                <td>
                                                    <span
                                                        style="color:#999">R$</span> <?php echo number_format($conta['valor'], 2, ',', '.'); ?>
                                                </td>
                                                <td>
                                                    <span class="label"
                                                          style="background:#<?= $conta["situacao_cor_bg"] ?>; color:#<?= $conta["situacao_cor_nome"] ?>"><?= $conta["situacao"] ?></span>
                                                </td>
                                                <?php /*<td>
									<input class="span2" type="text" id="parcelas_pagamento_<?php echo $i; ?>" name="contas_array[<?= $i; ?>][data_pagamento]" value="<?php echo $_POST['data_pagamento']; ?>" />
								  </td>
								  <td>
									<input class="span2" type="text" id="parcelas_pago_<?php echo $i; ?>" name="contas_array[<?= $i; ?>][valor_pago]" value="<?php echo number_format($valor_pago_parcela,2,',','.'); ?>" />
								  </td>*/
                                                ?>
												
												<td>
												<a class="btn btn-mini" href="#editarpagamento<?php echo $conta["idconta"]; ?>" rel="facebox" ><?= $idioma["financeiro_editar"]; ?></a>
												<div id="editarpagamento<?php echo $conta["idconta"]; ?>" style="display:none">
													<iframe src="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/editar/<?php echo $conta["idconta"]; ?>/editar_pagamento" width="500" height="150" frameborder="0"></iframe>
												</div>
												</td>
												
                                            </tr>
                                        <?php } ?>
                                    </table>
                                    <input class="btn" type="submit" value="Salvar"/>
                                    <br/>
                                </section>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <? incluirLib("rodape", $config, $usuario); ?>
    <script src="/assets/plugins/jquery-ui/ui/jquery.ui.core.js"></script>
    <script src="/assets/plugins/jquery-ui/ui/jquery.ui.widget.js"></script>
    <script src="/assets/plugins/jquery-ui/ui/jquery.ui.datepicker.js"></script>
    <script src="/assets/plugins/jquery-ui/ui/i18n/jquery.ui.datepicker-pt-BR.js"></script>
    <script>
        <?php foreach($contas as $conta) { ?>
        $("#<?= 'parcelas_vencimento_'.$conta['idconta']; ?>").datepicker($.datepicker.regional["pt-BR"]);
        $("#<?= 'parcelas_vencimento_'.$conta['idconta']; ?>").mask("99/99/9999");
        <?php } ?>
    </script>
</div>
</body>
</html>