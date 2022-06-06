<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib('head', $config, $usuario); ?>
<link href="/assets/css/menuVertical.css" rel="stylesheet" />
</head>
<body>
<?php incluirLib('topo', $config, $usuario); ?>
<div class="container-fluid">
    <section id="global">
      <div class="page-header"><h1><?= $idioma['pagina_titulo']; ?> &nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1></div>
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
        <div class="box-conteudo box-ava">
          <div class="tabbable tabs-left">
            <?php incluirTela("inc_submenu",$config,$linha); ?>
            <div class="ava-conteudo">
              <div class="pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"><i class="icon-share-alt"></i> <?= $idioma["btn_sair"]; ?></a></div>
              <h2 class="tituloEdicao"><?php echo $idioma["secao"]; ?></h2>
              <div class="tab-pane active" id="tab_editar">
                <? if($_POST["msg"]) { ?>
                  <div class="alert alert-success fade in">
                    <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                    <strong><?= $idioma[$_POST["msg"]]; ?></strong>
                  </div>
                <? } ?>



                  <!-- Modal -->
                  <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                      <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                          <h3 id="myModalLabel">Cadastrar arquivo</h3>
                      </div>
                      <div class="modal-body">
                          <form action="" enctype="multipart/form-data" method="post">
                              <fieldset>
                                  <input type="hidden" name="saveVirtualDisk" value="1" />
                                  <div class="form-content">
                                      <label>Nome do arquivo</label>
                                      <input type="text"  class="span4" name="nome" placeholder="Nome do arquivo..." /><br />
                                      <label>Selecione uma pasta</label>
                                      <select name="pasta" class="span4" id="pasta---">
                                          <?php
                                          foreach ($options as $folder) {
                                              echo '<option value='.$folder['id_pasta'].'>'.$folder['nome'].'</option>';
                                          }
                                          ?>

                                      </select><br />
                                      <label for="arquivo">Selecione um arquivo</label>
                                      <input type="file" name="arquivo" /><br />
                                      <input type="submit" class="btn" value="Cadastrar" />
                                  </div>
                              </fieldset>
                          </form>
                      </div>
                      <div class="modal-footer">
                      </div>
                  </div>


                  <!-- Modal -->
                  <div id="novapasta" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                      <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                          <h3 id="myModalLabel"><?= $idioma['pasta_title']; ?></h3>
                      </div>
                      <div class="modal-body">
                          <form action="" enctype="multipart/form-data" method="post">
                              <fieldset>
                                  <div class="form-content">
                                      <label><?= $idioma['pasta_legend']; ?></label>
                                      <input type="text"  class="span4" name="nome" placeholder="<?= $idioma['pasta_placeholder']; ?>" /><br />
                                      <input type="hidden" name="saveFolder" value="1" /><br />
                                      <input type="submit" class="btn" value="Cadastrar" />
                                  </div>
                              </fieldset>
                          </form>
                      </div>
                      <div class="modal-footer">
                      </div>
                  </div>



                <div id="listagem_informacoes">
                  <? printf($idioma["informacoes"],$linhaObj->Get("total")); ?>
                  <br />
                  <? printf($idioma["paginas"],$linhaObj->Get("pagina"),$linhaObj->Get("paginas")); ?>

                    <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|11", NULL)){ ?>
                    <span class="pull-right" style="padding-top:3px; color:#999">

                    <div class="btn-group">
                        <a  href="#novapasta" class="btn" data-toggle="modal"><?= $idioma['botao_nova_pasta']; ?></a>
                        <a  href="#myModal" class="btn  btn-primary" style="color: #FFF" data-toggle="modal"><i class="icon-plus icon-white"></i> <?= $idioma['botao_cadastrar_arquivo']; ?></a>
                    </div>
                    </span>
                  <? } ?>
                </div>
                <?php
                if (! $virtualDisk->_isInFolder()) {
                  $linhaObj->GerarTabela($dadosArray,$_GET["q"],$idioma,'lista_pastas');
                } else {
                  echo '<h2><a href="/'.$url[0].'/'.$url[1].'/'.$url[2].'/'.$url[3].'/'.$url[4].'">'.$idioma['breadcrumb_pasta'].'</a> / '.DiscoVirtual::getFolderName($url[5]).'</h2>';
                  $linhaObj->GerarTabela($dadosArray,$_GET["q"],$idioma,'form_listaa');
                }

                ?>

                <div id="listagem_form_busca">
                  <div class="input">
                    <div class="inline-inputs"> <?= $idioma["registros"]; ?>
                      <form action="" method="get" id="formQtd">
                        <? if($_GET["buscarpor"] && $_GET["buscarem"]) { ?>
                          <input name="buscarpor" type="hidden" id="buscarporQtd" value="<?= $_GET["buscarpor"]; ?>">
                          <input name="buscarem" type="hidden" id="buscaremQtd" value="<?= $_GET["buscarem"]; ?>">
                        <? } ?>
                        <? if(is_array($_GET["q"])){
                          foreach($_GET["q"] as $ind => $valor){
                          ?>
                            <input id="q[<?=$ind?>]" type="hidden" value="<?=$valor;?>" name="q[<?=$ind?>]" />
                          <? }
                        } ?>
                        <? if($_GET["cmp"]){?>
                          <input id="cmp" type="hidden" value="<?=$_GET["cmp"];?>" name="cmp" />
                        <? } ?>
                        <? if($_GET["ord"]){?>
                          <input id="ord" type="hidden" value="<?=$_GET["ord"];?>" name="ord" />
                        <? } ?>
                        <input name="qtd" type="text" class="span1" id="qtd" maxlength="4" value="<?= $linhaObj->Get("limite"); ?>" />
                        <a href="javascript:document.getElementById('formQtd').submit();" class="btn small"><?= $idioma["exibir"]; ?></a>
                      </form>
                    </div>
                  </div>
                </div>
                <? if($linhaObj->get('paginas') > 1) { ?>
                  <div class="pagination"><ul><?= $linhaObj->GerarPaginacao($idioma); ?></ul></div>
                <? } ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <? incluirLib("rodape",$config,$usuario); ?>
  </div>
  <script language="javascript" type="text/javascript">
    jQuery(document).ready(function($) {
        $('.delete').on('click', function(){
          if (confirm('Tem certeza que deseja deletar esse item?')) {
            $.post('/<?= $url[0];?>/<?= $url[1];?>/<?= $url[2];?>/<?= $url[3];?>/<?= $url[4];?>',
              {
                "delete": 1,
                "tipo": $(this).attr('data-tipo'),
                "id": $(this).attr('data-id')
              },
              function(x) {
                  if ('folder' == $(this).attr('data-tipo')) {
                    window.alert('Pasta deletada com sucesso!');
                  } else {
                    window.alert('Arquivo deletado com sucesso!');
                  }
                  window.location.href = window.location.href;
              });
          }
          return false;
        });

      $("#qtd").keypress(isNumber);
      $("#qtd").blur(isNumberCopy);
    });
  </script>
</body>
</html>