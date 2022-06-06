<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php incluirLib("head",$config,$usuario); ?>
    <link href="/assets/css/menuVertical.css" rel="stylesheet" />
</head>
<body>
<?php incluirLib("topo",$config,$usuario); ?>
<div class="container-fluid">
    <section id="global">
        <div class="page-header"><h1><?= $idioma["pagina_titulo"]; ?> &nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1></div>
        <ul class="breadcrumb">
            <li><a href="/<?= $url[0]; ?>"><?= $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
            <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>"><?= $idioma["nav_modulo"]; ?></a> <span class="divider">/</span></li>
            <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"><?= $idioma["pagina_titulo"]; ?></a> <span class="divider">/</span></li>
            <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $linha["idava"]; ?>/editar"><?php echo $linha["nome"]; ?></a> <span class="divider">/</span> </li>
            <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $linha["idava"]; ?>/disciplinas"><?= $idioma["pagina_titulo_interno"]; ?></a> <span class="divider">/</span></li>
            <li class="active"><?php echo $idioma["nav_formulario"]; ?></li>
            <span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
        </ul>
    </section>
    <div class="row-fluid">
        <div class="span12">
            <div class="box-conteudo box-ava">
                <div class="tabbable tabs-left">
                    <?php incluirTela("inc_submenu",$config,$linha); ?>
                    <div class="ava-conteudo">
                        <div class="pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"><i class="icon-share-alt"></i> <?= $idioma["btn_sair"]; ?></a></div>
                        <h2 class="tituloEdicao"><?php echo $linha["nome"]; ?></h2>
                        <div class="tab-pane active" id="tab_editar">
                            <h2 class="tituloOpcao"><?php echo $idioma["titulo_opcao_objetos"]; ?></h2>
                            <?php if($_POST["msg"] == 'vazio_select_aula') {?>
                                <div class="alert alert-error fade in">
                                    <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                                    <strong><?= $idioma[$_POST["msg"]]; ?></strong>
                                </div>
                            <?php }
                            if ($_POST["msg"] == 'cadastrar_objeto_sucesso') { ?>
                                <div class="alert alert-success fade in">
                                    <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                                    <strong><?= $idioma[$_POST["msg"]]; ?></strong>
                                </div>
                            <?php } ?>
                            <?php if(count($salvar["erros"]) > 0){ ?>
                                <div class="alert alert-error fade in">
                                    <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                                    <strong><?= $idioma["form_erros"]; ?></strong>
                                    <?php foreach($salvar["erros"] as $ind => $val) { ?>
                                        <br />
                                        <?php echo $idioma[$val]; ?>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                            <form class="well wellDestaque form-inline" method="post">
                                <table>
                                    <tr>
                                        <td><?php echo $idioma["form_aula_online"]; ?></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <select class="span4" name="idaula" id="idaula">
                                                <option value=""></option>
                                                <?php foreach($aulas as $aula) { ?>
                                                    <option value="<?php echo $aula["idaula"]; ?>"><?php echo $aula["nome"]; ?></option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="hidden" id="acao" name="acao" value="cadastrar_aula_online">
                                            <input type="submit" class="btn" value="<?= $idioma["btn_adicionar"]; ?>" />
                                        </td>
                                    </tr>
                                </table>
                            </form>
                            <form method="post" id="remover_aula_online" name="remover_aula_online">
                                <input type="hidden" id="acao" name="acao" value="remover_aula_online">
                                <input type="hidden" id="remover" name="remover" value="">
                            </form>
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th><?= $idioma["tabela_aula_online"]; ?></th>
                                </tr>
                                <tr>
                                    <th>Id</th>
                                    <th>Aula</th>
                                    <th>Disciplina</th>
                                    <th>Data / hora</th>
                                    <th width="60"><?= $idioma["tabela_opcoes"]; ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if(count($aulasAva) > 0) {
                                    foreach($aulasAva as $aulaAva) {?>
                                        <tr>
                                            <td><?php echo $aulaAva['idavas_aulas_online']; ?></td>
                                            <td><?php echo $aulaAva['nome']; ?></td>
                                            <td><?php echo $aulaAva['disciplina']; ?></td>
                                            <td><?php echo formataData($aulaAva['data_aula'], 'br',0) .' das '. mascara($aulaAva['hora_de'], '#####') .' às '. mascara($aulaAva['hora_ate'], '#####'); ?></td>
                                            <td><a href="javascript:void(0);" class="btn btn-mini" data-original-title="<?= $idioma["btn_remover"]; ?>" data-placement="left" rel="tooltip" onclick="remover(<?php echo $aulaAva['idavas_aulas_online']; ?>)"><i class="icon-remove"></i></a></td>
                                        </tr>
                                    <?php } ?>
                                <?php } else { ?>
                                    <tr>
                                        <td colspan="6"><?= $idioma["sem_informacao"]; ?></td>
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
    <?php incluirLib("rodape",$config,$usuario); ?>
    <script type="text/javascript">
        function remover(id) {
            confirma = confirm('<?= $idioma["confirmar_remocao"]; ?>');
            if(confirma) {
                document.getElementById("remover").value = id;
                document.getElementById("remover_aula_online").submit();
            }
        }
    </script>
</div>
</body>
</html>
