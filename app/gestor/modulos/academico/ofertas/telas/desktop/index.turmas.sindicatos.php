<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usuario); ?>
<link rel="stylesheet" href="/assets/plugins/fcbkcomplete/style.css" type="text/css" media="screen" charset="utf-8" />
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
                <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $linha["idava"]; ?>/editar"><?= ($linha["oferta"]) ? $linha["oferta"] : $linha["nome"]; ?></a> <span class="divider">/</span> </li>
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
                                <br />
                                <form method="post">
                                    <input type="hidden" name="acao" value="salvar_turmas_sindicatos" />
                                    <?php 
                                    foreach ($dadosArray as $idsindicato => $sindicato) { ?>
                                        <h4 class="tituloOpcao"><?php echo $sindicato["sindicato"]; ?> </h4>							
                                        <table class="table tabelaSemTamanho">
                                            <tr style="background-color:#f5f5f5;">
                                                <th width="90%">Turma</th>
                                                <?php /*<th width="20%">Institução</th>*/ ?>
                                                <th width="10%">Ignorar</th>
                                            </tr>							
                                            <?php foreach ($sindicato['turmas'] as $idturma => $turma) { ?> 
                                                <tr>
                                                    <td><?php echo $turma['turma_sindicato']['turma']; ?></td>
                                                    <?php /* <td><?php echo $turma['turma_sindicato']['sindicato']; ?></td> */ ?>
                                                    <td><input type="checkbox" name="sindicatos[<?php echo $idsindicato; ?>][turmas][<?php echo $turma['turma_sindicato']['idturma']; ?>][ignorar]" value="S" <?php if($turma['turma_sindicato']['ignorar'] == 'S') { ?> checked="checked" <?php } ?> /></td>
                                                </tr>
                                            <?php } ?>
                                        </table>
                                    <?php } ?>
                                    <input type="submit" class="btn btn-primary" value="<?= $idioma["btn_salvar"]; ?>" />
                                </form>				
                            </div>
                        </div>
                    </div>
                </div>
            </div>  
        </div>
		<? incluirLib("rodape",$config,$usuario); ?>
    </div>
</body>
</html>