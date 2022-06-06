<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usuario); ?>
<link href="/assets/css/menuVertical.css" rel="stylesheet" />
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
        <div class="box-conteudo box-ava">
          <div class="tabbable tabs-left">
            <?php incluirTela("inc_submenu",$config,$linha); ?>
            <div class="ava-conteudo"> 
              <div class="pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"><i class="icon-share-alt"></i> <?= $idioma["btn_sair"]; ?></a></div>
              <h2 class="tituloEdicao"><?php echo $linha["nome"]; ?></h2>
              <div class="tab-pane active" id="tab_editar">
                <? if($_POST["msg"]) { ?>
                  <div class="alert alert-success fade in">
                    <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                    <strong><?= $idioma[$_POST["msg"]]; ?></strong>
                  </div>
                <? } ?>
                <div id="listagem_informacoes"> 		  
                  <? printf($idioma["informacoes"],$linhaObj->Get("total")); ?>
                  <br />
                  <? printf($idioma["paginas"],$linhaObj->Get("pagina"),$linhaObj->Get("paginas")); ?>
                  <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|8", NULL)){ ?>
                    <span class='pull-right' style='padding-top:3px; color:#999'>
                        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/cadastrar" class="btn btn-primary"><i class="icon-plus icon-white"></i> <?= $idioma["nav_cadastrar"]; ?></a>
                    </span>
                  <? } ?>					
                </div>
                <?php $linhaObj->GerarTabela($dadosArray,$_GET["q"],$idioma,"listagem_conteudos"); ?>
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
                <? if($linhaObj->Get("paginas") > 1) { ?>
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
      $("#qtd").keypress(isNumber);
      $("#qtd").blur(isNumberCopy);
    });
  </script>

  <script type="text/javascript">
      function popup(url)
      {
          params  = 'width='+screen.width;
          params += ', height='+0.88*screen.height;
          params += ', top=0, left=0'
          params += ', fullscreen=yes';
          console.log(screen.height);
          newwin = window.open(url,'windowname4', params);
          if (window.focus) {newwin.focus()}
          return false;
      }
  </script>
</body>
</html>