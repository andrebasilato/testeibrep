<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php incluirLib("head", $config, $usuario); ?>
    <link rel="stylesheet" href="/assets/plugins/fcbkcomplete/style.css" type="text/css" media="screen" charset="utf-8" />

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
                 <div class="pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"><i class="icon-share-alt"></i> <?= $idioma["btn_sair"]; ?></a></div>
          <?php if($url[3] != "cadastrarsubcategoria") { ?><h2 class="tituloEdicao"><?php echo $linha["nome"]; ?></h2><?php } ?>
          <div class="tabbable tabs-left">
			<?php if($url[3] != "cadastrarsubcategoria") { incluirTela("inc_menu_edicao_sub",$config,$linha); } ?>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_editar">
				<h2 class="tituloOpcao"><?php if($url[3] == "cadastrarsubcategoria") { echo $idioma["titulo_opcao_cadastar"]; } else { echo $idioma["titulo_opcao_editar"]; } ?></h2>
				<h2 class="tituloOpcao"><?= $idioma["titulo_opcao"]; ?></h2>
                <? if (count($remover["erros"]) > 0) { ?>
                    <div class="alert alert-error fade in">
                        <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                        <strong><?= $idioma["form_erros"]; ?></strong>
                        <? foreach ($remover["erros"] as $ind => $val) { ?>
                            <br/>
                            <?php echo $idioma[$val]; ?>
                        <? } ?>
                        </strong>
                    </div>
                <? } ?>
                <form method="post" action="" class="form-horizontal">
                    <input name="acao" type="hidden" value="associar"/>

                    <div class="control-group">
                        <p>
                            <br/>
                            <? printf($idioma["usuario_selecionado"], $linha["nome"]); ?>
                            <br/><br/>
                            <?= $idioma["informacoes"]; ?> <br/>
                        </p>
                        <label class="control-label" for="optionsCheckboxList"><?= $idioma["confirmacao"]; ?></label>

                        <div class="controls">
                            <label class="checkbox">
                                <select class="invisivel hidden" name="ids[]" id="ids" multiple></select>
                            </label>

                            <p class="help-block"><?= $idioma["nota"]; ?></p>
                        </div>
                    </div>
                    <div class="form-actions">
                        <input type="submit" class="btn btn-primary" value="<?= $idioma["remover"]; ?>">&nbsp;
                        <input type="reset" class="btn"
                               onclick="MM_goToURL('parent','/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>');"
                               value="<?= $idioma["cancelar"]; ?>"/>
                    </div>
                </form><br/>

                            <form method="post" id="remover_sindicato" name="remover_sindicato">
                                <input type="hidden" id="acao" name="acao" value="remover_sindicato">
                                <input type="hidden" id="remover" name="remover" value="">
                            </form>
						<table class="table table-striped tabelaSemTamanho">
                                <thead>
                                <tr>
                                    <th width="72"><?= $idioma["listagem_nome"]; ?></th>
                                    <th width="79"><?= $idioma["listagem_opcoes"]; ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if (count($sindicatos) > 0) { ?>
                                    <?php foreach ($sindicatos as $sindicato) { ?>
                                        <tr>
                                            <td><?php echo $sindicato["nome_abreviado"]; ?></td>
                                            <td>
                                                <?php if ($perfil["permissoes"][$url[2] . "|5"]) { ?>
                                                    <a href="javascript:void(0);" class="btn btn-mini"
                                                       data-original-title="<?= $idioma["btn_remover"]; ?>"
                                                       data-placement="right" rel="tooltip"
                                                       onclick="remover(<?php echo $sindicato["idassociacao"]; ?>)"><i
                                                            class="icon-remove"></i></a>
                                                <?php } else { ?>
                                                    <a href="javascript:void(0);" class="btn btn-mini disabled"
                                                       data-original-title="<?= $idioma["btn_remover_permissao_excluir"]; ?>"
                                                       data-placement="right" rel="tooltip"><i
                                                            class="icon-remove"></i></a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                <?php } else { ?>
                                    <tr>
                                        <td colspan="3"><?= $idioma["sem_informacao"]; ?></td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>

            </div>
        </div>
         </div>
          </div>
           </div>
    </div>
    <? incluirLib("rodape", $config, $usuario); ?>


    <script src="/assets/plugins/fcbkcomplete/jquery.fcbkcomplete.js"></script>

    <script>
        $(document).ready(function(){
            $("#ids").fcbkcomplete({
                json_url: "/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/json/associar_sindicatos",
                addontab: true,
                height: 10,
                maxshownitems: 10,
                cache: true,
                maxitems: 20,
                filter_selected: true,
                firstselected: true,
                complete_text: "<?= $idioma["mensagem_select"]; ?>",
                addoncomma: true
            });
        });
		function remover(id) {
            confirma = confirm('<?= $idioma["confirmar_remocao"]; ?>');
            if (confirma) {
                document.getElementById("remover").value = id;
                document.getElementById("remover_sindicato").submit();
            }
        }
    </script>
</div>
</body>
</html>