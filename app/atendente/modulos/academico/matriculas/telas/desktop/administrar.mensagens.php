<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usu_vendedor); ?>
<link rel="stylesheet" href="/assets/plugins/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
<!--<link rel="stylesheet" href="/assets/plugins/fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css" media="screen" />
<link rel="stylesheet" href="/assets/plugins/fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen" />-->
<script language="javascript">
function novo_arquivo(){
  var IE = document.all?true:false
  var div_arquivos = document.getElementById( "div_arquivos" );		
  if( !IE ){
	var length = div_arquivos.childNodes.length -1;
  }else{
	var length = div_arquivos.childNodes.length +1;			
  }		
  var input = document.createElement( 'INPUT' );
  input.setAttribute( "type" , "file" );
  id = "arquivos[" + length + "]";
  input.setAttribute( "name" , id);	
  input.setAttribute( "id" , id);
  div_arquivos.appendChild( input );
  var br = document.createElement('br');
  div_arquivos.appendChild(br);
  regras.push("formato_arquivo,"+id+",jpg|jpeg|gif|png|bmp|zip|rar|tar|gz|doc|docx|xls|xlsx|ppt|pptx|pps|ppsx|txt|pdf,'',<?php echo $idioma['arquivo_invalido']; ?>");
}
</script>
<style type="text/css">

.tituloEdicao {
  font-size:45px;
}
legend {
  line-height:25px;
  margin-bottom: 5px;
  margin-top: 20px;
}
.botao {
  height:100px;
  margin-top: 15px;
  padding-bottom:0px;
  float:left;
  padding-top:40px;
  height:58px;
  text-transform:uppercase;
}
legend {
  background-color: #F4F4f4;
  -webkit-border-radius: 4px;
  -moz-border-radius: 4px;
  border-radius: 4px;
  padding: 5px 5px 5px 15px;
  width: 98%;
}


legend span {
  font-size: 9px;
  float: right;
  margin-right: 15px;
  color: #999;
}

.ui-accordion .ui-accordion-content{
overflow:visible !important;
}
</style>
</head>
<body>
<?php incluirLib("topo",$config,$usu_vendedor); ?>
<div class="container-fluid">
  <section id="global">
    <div class="page-header">
      <h1><?php echo $idioma["pagina_titulo"]; ?>&nbsp;<small><?php echo $idioma["pagina_subtitulo"]; ?></small></h1>
    </div>
    <ul class="breadcrumb">
      <li><a href="/<?php echo $url[0]; ?>"><?php echo $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
      <li><a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>"><?php echo $idioma["nav_modulo"]; ?></a> <span class="divider">/</span></li>
      <li><a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/matriculas"><?php echo $idioma["pagina_titulo"]; ?></a> <span class="divider">/</span></li>
      <li><?php echo $idioma["nav_matricula"]; ?> #<?php echo $matricula["idmatricula"]; ?> <span class="divider">/</span></li>
      <li class="active"><?php echo $idioma["nav_administrar"]; ?></a></li>
      <span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
    </ul>
  </section>
  <div class="row-fluid">
    <div class="span12">
      <div class="box-conteudo" style="padding:20px">

        <div class=" pull-right">
          <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"> <i class="icon-share-alt"></i> <?= $idioma["btn_sair"]; ?></a>
        </div>

 

<table border="0" cellspacing="0" cellpadding="15">
  <tr>
    <td style="padding:0px;" valign="top"><img src="/api/get/imagens/pessoas_avatar/60/60/<?php echo $matricula["pessoa"]["avatar_servidor"]; ?>" class="img-circle"></td>
    <td style="padding: 0px 0px 0px 8px;" valign="top">        <h2 class="tituloEdicao"><?= $idioma["matricula"]; ?> #<?= $matricula["idmatricula"]; ?>
          <br />
          <small style="text-transform:uppercase;">Aluno: <?= $matricula["pessoa"]["nome"]; ?></small>
        </h2></td>
  </tr>
</table>


<? incluirTela("administrar.menu",$config,$matricula); ?>
        <div class="row-fluid">
          <div class="span12">
            <?php if($mensagem["erro"]) { ?>
              <div class="alert alert-error">
                <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                <?= $idioma[$mensagem["erro"]]; ?>
              </div>
        <script>alert('<?= str_ireplace(array("<br />", "<br/>", "<br>"), "\\n", $idioma[$mensagem["erro"]]); ?>');</script>
            <? } ?>
            <? if($_POST["msg"]) { ?>
              <div class="alert alert-success fade in">
                <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
        <strong><?= $idioma[$_POST["msg"]]; ?></strong>
              </div>
        <script>alert('<?= $idioma[$_POST["msg"]]; ?>');</script>
            <? } ?>
            
            <section id="mensagensmatricula">
            <legend data-abrefecha="mensagens_matricula_ancora_div"><?= $idioma["label_mensagens_matricula"]; ?>
              </legend>
             <form name="form_mensagens" method="post" onsubmit="return validaMsg();" id="form_mensagens" enctype="multipart/form-data">
                <div style="border:#CCC solid 1px;" class="accordion-body collapse in"> 
                  <div style="max-height:800px; padding:5px;">
                    <small><?php echo $idioma['mensagem']; ?></small>
                    <br />
                    <textarea name="mensagem" id="mensagem" rows="5" style="width:99%;"></textarea>      
					<input type="checkbox" id="exibir_diploma" name="exibir_diploma" style="float: left; margin-right: 5px"><label for="exibir_diploma">Exibir no diploma</label>                            
                    <input type="checkbox" id="enviar_email" name="enviar_email" style="float: left; margin-right: 5px"><label for="enviar_email">Enviar por email</label>
                    <input type="hidden" name="acao" value="salvar_mensagem">           
                    
                    <div style="float:left;" id="div_arquivos">
                      <?php echo $idioma['anexar_arquivo']; ?> 
                       <input type="button" class="btn btn-primary btn-mini" onclick="novo_arquivo();" name="enviar" value=" + " />
                       <br/>
                       <input type="file" name="arquivos[1]" id="arquivos[1]" />
                      <br />
                    </div> 
                     <br />
                    <input type="hidden" name="acao" value="salvar_mensagem"> 
                    <div style="float:right;"><input type="submit" class="btn btn-primary" name="enviar" value="<?php echo $idioma["btn_cadastrar"]; ?> " /></div>                
                  </div>          
                </div>           
              </form>
              <div class="accordion">
                <div class="accordion-group">
                  <div class="accordion-heading">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_mensagens" href="#mensagens_cadastradas"><?= $idioma["mensagens_matricula_cadastradas"]; ?></a>
                  </div>
                  <div id="mensagens_cadastradas" class="accordion-body collapse in">
                    <div class="accordion-inner" style="max-height:800px; padding:5px;">
                    <?
                        $totalMensagens = count($matricula["mensagens"]);
                        if ($totalMensagens > 0) {
                        $count = 0;
                        foreach ($matricula["mensagens"] as $mensagem) {
                          $count++;
                    ?>
              <div class="mensagens">
                <div class="mensagens-cabecalho">
                    <img src="/api/get/imagens/<?= $mensagem['pasta']; ?>/40/40/<?= $mensagem['avatar_servidor']; ?>" class="img-circle">
                    <span class="mensagens-id"># <?= $mensagem["idmensagem"] ?></span>
                    <span class="mensagens-data"><?= formataData($mensagem["data_cad"],"br",1); ?></span>
                    <span class="mensagens-data"><?= $mensagem["enviar_email"]; ?></span>
                    <span class="mensagens-usuario"><?php echo $mensagem["usuario"]; ?></span><p />
                </div>
                <div class="mensagens-conteudo">
                	<?php echo nl2br($mensagem["mensagem"]); ?>
                <br />
                <? if ($mensagem["arquivos"]) { ?>
                    <br />
                    <strong><?= $idioma["arquivo"]; ?></strong>
                    <br />
    				<?php foreach ($mensagem["arquivos"] as $arquivos) { ?>
                	<div id="arquivo<?= $arquivos["idarquivo"]; ?>">
                    <span class="icon-file"></span>
                    <a href="<?php echo "/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/download/".$arquivos["idarquivo"]; ?>"><?= $arquivos["arquivo_nome"]; ?> (<?= tamanhoArquivo($arquivos["arquivo_tamanho"]); ?>)</a>
                    <br />
                    </div>
    				<?php } ?>
    			<? } ?>
				<br />
				<?php if ($count == 1 && $matricula["id_ultima_mensagem"] == $mensagem["idmensagem"] && $mensagem["idvendedor"] == $usu_vendedor["idvendedor"]) { ?>
                    <span class="mensagens-remover"><a class="btn btn-mini" href="javascript:void(0);" onclick="removerMensagem(<?= $mensagem["idmensagem"]; ?>);" ><span class="icon-remove"></span> <?php echo $idioma["mensagens_matricula_excluir"]; ?></a></span>
                <?php } ?>
				
                </div>
                <div class="left" style="float: right">
                  <span>Exibir no diploma:</span>
                    <?php

                    $classS = '';
                    $classN = '';

                    ('S' == $mensagem['exibir_diploma']) ? $classS = ' btn-success' : $classN = ' btn-success';

                    $urlLinkToChangeVisibility = Request::url('0-4', '/'). $url[4] . '/' . $url[5] .'/trocar_situacao_da_mensagem/%s';

                     ?>
                     <div class="btn-group">
                        <a href="<?php echo sprintf($urlLinkToChangeVisibility, $mensagem['idmensagem']).'?changeTo=S' ?>"><small><button style="font-size: 9px" class="btn<?php echo $classS; ?>" >Sim</button></small></a>
                        <a href="<?php echo sprintf($urlLinkToChangeVisibility, $mensagem['idmensagem']).'?changeTo=N' ?>"><small><button style="font-size: 9px" class="btn<?php echo $classN; ?>" >Não</button></small></a>
                      </div>
                </div>
                <div style="clear: both; line-height: 0;">&nbsp;</div>
              </div>
            <?php } ?>
                        <script>
              function removerMensagem(id) {
              var msg = "<?=$idioma["mensagens_matricula_confirmar_remover"];?>";
              var confirma = confirm(msg);
              if(confirma){
                document.getElementById('idmensagem').value = id;
                document.getElementById('form_remover_mensagem').submit();
                return true;
              } else {
                return false;
              }
              }
            </script>
            <form method="post" id="form_remover_mensagem" action="" style="padding-top:15px;">
              <input name="acao" type="hidden" value="remover_mensagem" />
              <input name="idmensagem" id="idmensagem" type="hidden" value="" />
            </form>
            <?php } else { ?>
                        <ul class="nav nav-tabs nav-stacked">
                          <li>
                            <span> <?php echo $idioma["mensagens_matricula_sem_mensagem"]; ?> </span>
                          </li>
                        </ul>
            <?php } ?>
                    </div>
                  </div>
                </div>
              </div>
              </div>
            </section>
           </div>
        </div>
        <div class="clearfix"></div>
      </div>
    </div>
  </div>
  <? incluirLib("rodape",$config,$usu_vendedor); ?>
</div>
<script src="/assets/plugins/ckeditor/sample.js"></script>
<script src="/assets/plugins/ckeditor/ckeditor.js"></script>
<script>
function validaMsg() {
 CKEDITOR.instances.mensagem.updateElement();
  if (document.getElementById('mensagem').value == "") {
    alert('<?php echo $idioma["mensagem_vazio"]; ?>');
        return false;
    }
}
var editor = CKEDITOR.replace( 'mensagem', {
/*
* Ensure that htmlwriter plugin, which is required for this sample, is loaded.
*/
extraPlugins: 'htmlwriter',

height: 100,
width: '100%',
toolbar: [
["Bold","Italic","Underline","StrikeThrough","-",
"Outdent","Indent","NumberedList","BulletedList"],
["-","JustifyLeft","JustifyCenter","JustifyRight","JustifyBlock","PasteFromWord"],
["-","TextColor","BGColor","Format","Font","FontSize"],
],
/*
* Style sheet for the contents
*/
contentsCss: 'body {color:#000; background-color#FFF; font-family: Arial; font-size:80%;} p, ol, ul {margin-top: 0px; margin-bottom: 0px;}',

/*
* Quirks doctype
*/
docType: '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">',

/*
* Core styles.
*/
coreStyles_bold: { element: 'b' },
coreStyles_italic: { element: 'i' },
coreStyles_underline: { element: 'u' },

/*
* Font face.
*/

// Define the way font elements will be applied to the document. The "font"
// element will be used.
font_style: {
element: 'font',
attributes: { 'face': '#(family)' }
},

/*
* Font sizes.
*/

// The CSS part of the font sizes isn't used by Flash, it is there to get the
// font rendered correctly in CKEditor.
fontSize_sizes: '8px/8;9px/9;10px/10;11px/11;12px/12;14px/14;16px/16;18px/18;20px/20;22px/22;24px/24;26px/26;28px/28;36px/36;48px/48;72px/72',
fontSize_style: {
element: 'font',
attributes: { 'size': '#(size)' },
styles: { 'font-size': '#(size)px' }
} ,

/*
* Font colors.
*/
/*colorButton_enableMore: true,

colorButton_foreStyle: {
element: 'font',
attributes: { 'color': '#(color)' }
},

colorButton_backStyle: {
element: 'font',
styles: { 'background-color': '#(color)' }
},*/

on: { 'instanceReady': configureFlashOutput }
});

/*
* Adjust the behavior of the dataProcessor to match the
* requirements of Flash
*/
function configureFlashOutput( ev ) {
var editor = ev.editor,
dataProcessor = editor.dataProcessor,
htmlFilter = dataProcessor && dataProcessor.htmlFilter;

// Out self closing tags the HTML4 way, like <br>.
dataProcessor.writer.selfClosingEnd = '>';

// Make output formatting match Flash expectations
var dtd = CKEDITOR.dtd;
for ( var e in CKEDITOR.tools.extend( {}, dtd.$nonBodyContent, dtd.$block, dtd.$listItem, dtd.$tableContent ) ) {
dataProcessor.writer.setRules( e, {
indent: false,
breakBeforeOpen: false,
breakAfterOpen: false,
breakBeforeClose: false,
breakAfterClose: false
});
}
dataProcessor.writer.setRules( 'br', {
indent: false,
breakBeforeOpen: false,
breakAfterOpen: false,
breakBeforeClose: false,
breakAfterClose: false
});

// Output properties as attributes, not styles.
htmlFilter.addRules( {
elements: {
$: function( element ) {
var style, match, width, height, align;

// Output dimensions of images as width and height
if ( element.name == 'img' ) {
style = element.attributes.style;

if ( style ) {
// Get the width from the style.
match = ( /(?:^|\s)width\s*:\s*(\d+)px/i ).exec( style );
width = match && match[1];

// Get the height from the style.
match = ( /(?:^|\s)height\s*:\s*(\d+)px/i ).exec( style );
height = match && match[1];

if ( width ) {
element.attributes.style = element.attributes.style.replace( /(?:^|\s)width\s*:\s*(\d+)px;?/i , '' );
element.attributes.width = width;
}

if ( height ) {
element.attributes.style = element.attributes.style.replace( /(?:^|\s)height\s*:\s*(\d+)px;?/i , '' );
element.attributes.height = height;
}
}
}

// Output alignment of paragraphs using align
if ( element.name == 'p' ) {
style = element.attributes.style;

if ( style ) {
// Get the align from the style.
match = ( /(?:^|\s)text-align\s*:\s*(\w*);?/i ).exec( style );
align = match && match[1];

if ( align ) {
element.attributes.style = element.attributes.style.replace( /(?:^|\s)text-align\s*:\s*(\w*);?/i , '' );
element.attributes.align = align;
}
}
}

if ( element.attributes.style === '' )
delete element.attributes.style;

return element;
}
}
});
}
</script>
<?php incluirTela("cabecalho_info", $config, $matricula); ?>
</body>
</html>