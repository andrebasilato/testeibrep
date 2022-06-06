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
      <div class="span9">
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
                  <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|17", NULL)){ ?>
                    <span class="pull-right" style="padding-top:3px; color:#999">
                      <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/cadastrar" class="btn btn-primary"><i class="icon-plus icon-white"></i> <?= $idioma["nav_cadastrar"]; ?></a>
                    </span>
                  <? } ?>				
                </div>
                <?php $linhaObj->GerarTabela($dadosArray,$_GET["q"],$idioma,"listagem_downloads"); ?>
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
      <div class="span3">
        <div class="box-conteudo folder-list">
          <h2>Pastas</h2>
          <input type="text" class="span2" id="pasta-nome" placeholder="Nome da pasta">
          <button type="submit" class="btn">Criar</button>
          <table class="table tree-folder-list" style="margin-top:20px">
			<?php
			//$link = parse_url($_SERVER['REQUEST_URI']);
			  foreach ($listaDePastas as $value) {
			  $class = '';
			  if ($url[3] == $value['idpasta']) {
				$class = ' current';
			  }
			  echo '<tr class="'.$class.'">
				<td class="name-of-folder" data-id="'.$value['idpasta'].'" style="width: 100%;">
				  <a href="/'.join('/', array($url[0], $url[1], $url[2], $url[3], $url[4])).'?q[1|d.idpasta]='.$value['idpasta'].'" data-id="'.$value['idpasta'].'">'.$value['nome'].'</a>
				</td>
				<td>
				  <a href="#" class="edit" rel="tooltip" data-original-title="Editar"  data-id="'.$value['idpasta'].'">
					<i class="icon-edit"> </i>
				  </a>
				</td>
				<td>
				  <a href="#" data-id="'.$value['idpasta'].'"  rel="tooltip" data-original-title="Remover">
                    <i class="icon-trash"> </i>
				  </a>
				</td>
			  </tr>';
			}
			?>
          </table>
          <!-- </form> -->
          <div class="clearfix"></div>
        </div>
      </div>
    </div>
    <? incluirLib("rodape",$config,$usuario); ?>
  </div>
  <script language="javascript" type="text/javascript">
    jQuery(document).ready(function($) {
      $("#qtd").keypress(isNumber);
      $("#qtd").blur(isNumberCopy);
	  
	  // Desabilita click ao editar
	  $('.name-of-folder a').click(function(){
		  if (! $(this).has('input').length) {
			  return true;
		  }
		  return false;
	  });

	  // Edit folder
	  var saveOnNextClick = false;
	  var element = 'null';
	  var prevValue = null;
	  function ableEdit()
	  {
		  $('.edit').click(function(){
  
			  if (! saveOnNextClick)
			  {
				  console.log('Editar: save-> ' + saveOnNextClick + ', element-> '+ element);
  
				  if (element != 'null' && element !== $(this).attr('data-id')) {
					  window.alert('Já existe um campo sendo editado.');
					  return false;
				  }
  
				  element = $(this).attr('data-id');
				  // prevValue = $(this).parent('td').parent('tr').children('.name-of-folder').html();
				  $(this).children('i').removeClass('icon-edit')
				  .addClass('icon-ok');
  
				  var folderTd = $(this).parent('td').parent('tr').children('.name-of-folder').children('a');
  
				  var valueFolderTd = folderTd.html();
				  folderTd.html('<input type="text" id="save-this-value" style="background: transparent;border: medium none; border-bottom: 1px dotted rgb(11, 102, 186);width: 100% !important;" value="'+ valueFolderTd + '" />');
				  document.getElementById('save-this-value').focus();
			  } else {
  
				  console.log('Salvar: save-> ' + saveOnNextClick + ', element-> '+ element);
  
				  if (element != 'null' && element !== $(this).attr('data-id')) {
					  window.alert('Já existe um campo sendo editado.');
					  return false;
				  }
  
				  $(this).children('i').removeClass('icon-ok')
				  .addClass('icon-edit');
  
  
				  var folderName = document.getElementById('save-this-value').value;
				  var folderTd = $(this).parent('td').parent('tr').children('.name-of-folder').children('a');
  
				  folderTd.html(folderName);
  
				  console.log('Id da pasta -> ' + folderTd.attr('data-id'));
				  jQuery.post('/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/renomearpasta', {
					  nome: folderName,
					  id: folderTd.attr('data-id')
				  }, function (x){
					  alert(x);
				  })
  
				  element = 'null';
			  }
  
  
			  saveOnNextClick = !saveOnNextClick;
			  return false;
		  });
  }

    // Delete folder
    condition = false;
    function ableDelete()
    {
        $('.icon-trash').click(function()
        {
            if (window.confirm('Deseja mesmo deletar a pasta?')) {
                var dataId = $(this).parent('a').attr('data-id');

                jQuery.post('/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/removerpasta', {
                    id: dataId
                }, function(x) {
                    console.log(x);
                    x = JSON.parse(x);
                    // alert(x.alert);
                    alert(x.alert);

                    if (! x.error) {
                        document.location.reload();
                    }
                });
            }

            return false;
        })
    }


    ableEdit();
    ableDelete();


    // Criar pasta
    $('.folder-list button').click(function(){

        var valor = document.getElementById('pasta-nome').value;

        jQuery.post('/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/criarpasta', {
            nome: valor
        }, function(x){
            x = JSON.parse(x);
            var result = '<tr>';
            result = result + '<td class="name-of-folder" data-id="' + x.id + '">'+ x.name + '</td> <td><a href="#" class="edit" data-id="' + x.id + '"><i class="icon-edit"> </i></a></td> <td><a href="#" data-id="' + x.id + '"><i class="icon-trash"> </i></a></td> </tr>';
            $('.tree-folder-list').append(result);
            document.getElementById('pasta-nome').value = '';
            document.getElementById('pasta-nome').focus();
            $( "body" ).off( "click", ".edit");
            $( "body" ).off( "click", ".icon-trash");
            document.location.reload();
            ableEdit();
            ableDelete();
        });
    });
    });
  </script>
</body>
</html>