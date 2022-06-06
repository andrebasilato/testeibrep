<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php incluirLib('head', $config, $usuario); ?>
<style type="text/css">
h3.tituloVideo{
    line-height: 20px;
    font-size: 14px;
}
</style>
<script type="text/javascript">
// Control the key press and click with ctrl button
// Select itens and send it to be exclude :3
function isKeyPressed(event)
{
    $('.thumbnail').click(function() {

        if (1 == event.ctrlKey)
        {
            //remove or add class 'selected'
            if ($(this).hasClass('selected')) {
                $(this).removeClass('selected');
            } else {
                $(this).addClass('selected');
            }

            return true;
        }
    });
}

var ctrlKey = false;

document.onkeyup = function(e) {

    if (17 == e.which)
        ctrlKey = false;

    if(46 == e.which){
        var length = $('.thumbnail.selected').length;

        if (0 == length) {
            return false;
        }

        var listOfVideosToRemove = new Array();
        if (window.confirm('Deseja deletar ' + parseInt(length) + ' items?')) {
            $('.thumbnail.selected').each(function(){

                listOfVideosToRemove.push(
                    $(this).children('.caption').attr('data-id')
                    );

            });

            jQuery.post('/<?= $url[0]; ?>/<?= $url[1]; ?>/videoteca/removermultiplos', {
                idvideos: listOfVideosToRemove
            }, function (x){
               $('.thumbnail.selected').each(function(){
                $(this).parent('li').hide('slow');
                $(this).parent('li').remove();
            });
               alert(x);
           })
        }
    }
};

document.onkeydown = function(e) {
    var ctrlKey = 17, vKey = 65, cKey = 97;

    if (e.which == ctrlKey) {
        ctrlDown = true;
    }

    if (e.keyCode == vKey) {
        if (ctrlDown && (e.which == vKey || e.which == cKey)) {
            var quantity = $('.thumbnail').length;
            var selected = $('.thumbnail.selected').length;

            if (quantity == selected) {
                $('.thumbnail').removeClass('selected');
                ctrlDown = false;
                return false;
            }

            if (quantity > selected) {
                $('.thumbnail').addClass('selected');
                ctrlDown = false;
                return false;
            }
        }
    }

};

</script>
</head>
<body onmousedown="isKeyPressed(event)">
  <?php incluirLib("topo",$config,$usuario); ?>
  <div class="container-fluid">
    <section id="global">
      <div class="page-header">
        <h1><?= $idioma["pagina_titulo"]; ?> &nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1>
    </div>
    <ul class="breadcrumb">
        <li><a href="/<?= $url[0]; ?>"><?= $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>"><?= $idioma["nav_modulo"]; ?></a> <span class="divider">/</span></li>
        <li class="active"><?= $idioma["pagina_titulo"]; ?></li>
        <? if($_GET["q"] || $_GET['termo'] || $_GET['tag']) { ?><li><span class="divider">/</span> <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"><?= $idioma["nav_resetarbusca"]; ?></a></li><? } ?>
        <span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
    </ul>
</section>
<div class="row-fluid">
  <div class="span9">
    <div class="box-conteudo">
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

    </div>

        <form action="" method="" id="searchForm">
        <div class="busca">
            <?php if ($_GET['tag']) : ?>
                <input type="hidden" name="tag" id="tag" value="<?php echo $_GET['tag']; ?>" />
            <?php endif; ?>

            <?php if ($_GET['qtd']) : ?>
                <input type="hidden" name="qtd" id="qtd" value="<?php echo $_GET['qtd']; ?>" />
            <?php endif; ?>

            <?php
                $buscaValor = '';
                if ($_GET['termo']) {
                    $buscaValor = strip_tags($_GET['termo']);
                }
            ?>

            <input type="text" name="termo" id="termo" value="<?php echo $buscaValor; ?>" />
            <input type="submit" class="btn" value="Buscar" />
        </div>
    </form>

    <div class="clear"></div>

    <style type="text/css">
    #listagem_informacoes{
        width: 28%;
        float: left;
    }
    #searchForm{
        width: 68%;
        float: right;
    }
    .busca{
        background: #0B66BA;
        padding: 10px;
        width: 96.5%;
        margin-bottom: 30px;
        border-radius: 5px 5px;
    }
    .busca input[type="text"]
    {
        width: 86%;
    }
    .tag{
        margin-left: 5px;
        margin-bottom: 5px;
        display: block;
        float: left;
    }
    .selected{
        box-shadow: 0px 1px 1px green;
        border: 1px solid green;
    }
    .current-tag,
    .label:hover{
        background-color: rgb(11, 102, 186) !important;
    }
    .current,
    .current:hover{
        background: rgb(227, 255, 255) !important;
    }

    .search-params {
      list-style: none;
      margin: 0;
      overflow: hidden;
      padding: 0;
    }

    .search-params li {
      float: left;
    }

    .search-param {
      background: #eee;
      border-radius: 3px 0 0 3px;
      color: #999;
      display: inline-block;
      height: 26px;
      line-height: 26px;
      padding: 0 20px 0 23px;
      position: relative;
      margin: 0 10px 10px 0;
      text-decoration: none;
      -webkit-transition: color 0.2s;
    }

    .search-param::before {
      background: #fff;
      border-radius: 10px;
      box-shadow: inset 0 1px rgba(0, 0, 0, 0.25);
      content: '';
      height: 6px;
      left: 10px;
      position: absolute;
      width: 6px;
      top: 10px;
    }

    .search-param::after {
      background: #fff;
      border-bottom: 13px solid transparent;
      border-left: 10px solid #eee;
      border-top: 13px solid transparent;
      content: '';
      position: absolute;
      right: 0;
      top: 0;
    }

    .search-param:hover {
      background-color: crimson;
      text-decoration: none;
      color: white;
    }

    .search-param:hover::after {
       border-left-color: crimson;
    }
    .clear{
        clear: both;
    }
    </style>



<?php if(
        (isset($_GET['tag']) and trim($_GET['tag']))
        || (isset($_GET['termo']) and trim($_GET['termo']))
        || 30 != $_GET['qtd']
        || ('filtro' == $url[4])
    ) { ?>
<div>
    <p><strong>Busca usando</strong> <i>(Clique em um filtro para removê-lo)</i>:</p>
    <ul class="search-params">

<?php

$urlQuery = parse_url($_SERVER['REQUEST_URI']);
parse_str($urlQuery['query'], $queryOptions);

if ('filtro' == $url[4]) {
    unset($tmpCopy['pag']);
    $removeFromUrl = '/'.$url[3] .'/'. $url[4];
    $lessFilter = str_replace($removeFromUrl, '', $_SERVER['REQUEST_URI']);
    echo '<li><a href="'.$lessFilter.'" class="search-param"><strong>Pasta:</strong> '.videotecaPastas::getName((int) $url[3]).'</a></li>';
}

if (isset($_GET['termo']) and trim($_GET['termo'])) {
    $tmpCopy = $queryOptions;
    unset($tmpCopy['termo']);
    unset($tmpCopy['pag']);
    $lessFilter = http_build_query($tmpCopy);
    echo '<li><a href="?'.$lessFilter.'" class="search-param"><strong>Termo: </strong>'.strip_tags($_GET['termo']).'</a></li>';
}


if (isset($_GET['tag']) and trim($_GET['tag'])) {
    $tmpCopy = $queryOptions;
    unset($tmpCopy['pag']);
    unset($tmpCopy['tag']);
    $lessFilter = http_build_query($tmpCopy);
    echo '<li><a href="?'.$lessFilter.'" class="search-param"><strong>Tag: </strong>'.rawurldecode(videotecaTags::getName($_GET['tag'])).'</a></li>';
}

if (isset($_GET['qtd']) and 30 != $_GET['qtd'] and trim($_GET['qtd'])) {
    $removeFromUrl = 'qtd='.$_GET['qtd'];
    $lessFilter = str_replace(array($removeFromUrl, '&cmp=idvideo','pag='), array('', '', ''), $_SERVER['REQUEST_URI']);
    echo '<li><a href="'.$lessFilter.'" class="search-param"><strong>Quantidade: </strong>'.(int) $_GET['qtd'].'</a></li>';
}

?>

</ul>
    <div class="clear"></div>
</div>
<?php } ?>

<?php
    echo '<ul class="thumbnails">';
    $html = '';
    foreach ($dadosArray as $dados) {

        $caminho = $videotecaPastas->getPathNameById($dados['idpasta']);

        $tagCollection = $videotecaTags->listTagsForVideoId($dados['idvideo']);
        $tags = '';
        foreach ($tagCollection as $tag) {
            $tags .=  rawurldecode($tag->title). ', ';
        }

        $html .= '<li class="span3 video-list-item">';
        $html .= '<div class="thumbnail" style="height: 250px">';
        $html .= '<a href="#'.$dados['idvideo'].'" class="primary" rel="facebox">';

        if ('youtube' == $dados['variavel'] || 'vimeo' == $dados['variavel']) {
            $html .= '<img style="width: 100%" data-src="holder.js/160x120" alt="'.$dados['titulo'].'" src="'.$dados['imagem'].'">';
        } elseif($dados['imagem'] && ('html5' == $dados['variavel'] || 'interno' == $dados['variavel'])) {
            if($config['videoteca_local']){
                $html .= '<img style="width: 100%" data-src="holder.js/160x120" alt="'.$dados['titulo'].'" src="/storage/videoteca/'. $caminho->caminho.'/'.$dados['idvideo'] .'/'.$dados['imagem'].'.jpg">';
            } else {
                $html .= '<img style="width: 100%" data-src="holder.js/160x120" alt="'.$dados['titulo'].'" src="' . $config['videoteca_endereco'][rand(0, (count($config['videoteca_endereco']) - 1))] . '/' . $caminho->caminho . '/' . $dados['video_imagem'] . '">';
            }
        } else {
            $html .= '<img style="width: 100%" data-src="holder.js/160x120" alt="'.$dados['titulo'].'" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASwAAADICAYAAABS39xVAAAEg0lEQVR4nO3bMW7jOABA0bn/UdK5cunWB/A5dIWdyguFS8oBkkzma1/xGoa2ZAT6oGT617Zt/wAU/PrpEwD4KMECMgQLyBAsIEOwgAzBAjIEC8gQLCBDsIAMwQIyBAvIECwgQ7CADMECMgQLyBAsIEOwgAzBAjIEC8gQLCBDsIAMwQIyBAvIECwgQ7CADMECMgQLyBAsIEOwgAzBAjIEC8gQLCBDsIAMwQIyBAvIECwgQ7CADMECMgQLyBAsIEOwgAzBAjIEC8gQLCBDsIAMwQIyBAvIECwgQ7CADMECMgQLyBAsIEOwgAzBAjIEC8gQLCBDsIAMwQIyBAvIECwgQ7CADMECMgQLyBAsIEOwgAzBAjIEC8gQLCBDsIAMwQIyBAvIECwgQ7CADMECMgQLyBAsIEOwgAzBAjIEC8gQLCBDsIAMwQIyBAvIECwgQ7CADMECMgQLyBAsIEOwgAzBAjIE62Te3t7eud/v03mPx+M/c1fveb1e38273W6fPs/L5fLh44/zHo/HdN79fn8373K5/Pj/g68lWCcxC9AqMOOFfRSNWVg+G63VsccYHX2mMVq32206T7TORbBOYr8Kel7M+9js5z7H9xfzc971ev13bB+250ptH4bVSufI/vX71d/s+PvPdHTus9fvY/cVK0L+DoJ1ErMVxT4Oz7H9hbwPxmzuMxirODxDsH/tPg6zOK2CM4vT7D33EX0GczZ2dP50CdaJzSK2urhn48+47Fc9q/FxNbcP40eCMQZrFdbZ+Cy2R+N0CdYJ7S/UMRari3h2+7e6pZqtkvavv16v7wL26tZxFrdVWGe3equV1GdvX/n7CNYJjd/qvbr127bPB2t23I8+P5rNFyxmBOvEZhfsdwZr/FbvI7eCq/mCxYxgndz47dl3Bmvb3q+yxmdfq3N7dT6CxZNgndxRsF49dF+FZ/Uwfra/axWKcX/X+PdZQLdt/tB99g3j+Fl/+v/A1xCsEzjaczSOf8e2hnH81W3h+Kxr9blmx7Gt4f9NsE5iFoDZZtJt+56No+Oeq9XerHEVdnSrZuMoI8E6iaOf23z3T3P2cdgHb/a+q/ebzfXTHEaCdTLjBfsnfvy82nM17s06CtDRebyK1ex4YnVOggVkCBaQIVhAhmABGYIFZAgWkCFYQIZgARmCBWQIFpAhWECGYAEZggVkCBaQIVhAhmABGYIFZAgWkCFYQIZgARmCBWQIFpAhWECGYAEZggVkCBaQIVhAhmABGYIFZAgWkCFYQIZgARmCBWQIFpAhWECGYAEZggVkCBaQIVhAhmABGYIFZAgWkCFYQIZgARmCBWQIFpAhWECGYAEZggVkCBaQIVhAhmABGYIFZAgWkCFYQIZgARmCBWQIFpAhWECGYAEZggVkCBaQIVhAhmABGYIFZAgWkCFYQIZgARmCBWQIFpAhWECGYAEZggVkCBaQIVhAhmABGYIFZAgWkCFYQIZgARmCBWQIFpAhWECGYAEZggVkCBaQIVhAhmABGYIFZAgWkCFYQIZgARmCBWQIFpAhWEDGb6hpu7RLtCtOAAAAAElFTkSuQmCC">';
        }

        $html .= '</a>';
        $html .= '<div class="caption" data-id="'.$dados['idvideo'].'">';
        $html .= '<h3 class="tituloVideo">'.substr($dados['titulo'], 0, 60).'</h3>';
        $html .= '<div class="btn-group" style="float: right">';
        $html .= '<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">';
        $html .= '<i class="icon-wrench">&nbsp;</i>';
        $html .= '<span class="caret"></span>';
        $html .= '</a>';
        $html .= '<ul class="dropdown-menu">';
        $html .= '<li>';
        $html .= '<a href="/gestor/academico/videoteca/'.$dados['idvideo'].'/editar"> ';
        $html .= '<i class="icon-edit"> </i>';
        $html .= '<span> Editar</span>';
        $html .= '</a>';
        $html .= '</li>';
        $html .= '<li>';
        $html .= '<a href="videoteca/'.$dados['idvideo'].'/remover">';
        $html .= '<i class="icon-trash"> </i>';
        $html .= '<span> Remover</span>';
        $html .= '</a>';
        $html .= '</li>';
        $html .= '</ul>';
        $html .= '</div>';
        // $html .= '<p><strong>Tags: </strong><span>'.trim($tags, ', ').'</span></p>';


        $html .= '<div id="'.$dados['idvideo'].'" style="display: none; width: 400px !important"><div>';

        if ('html5' == $dados['variavel'] || 'interno' == $dados['variavel']) {
            if($config['videoteca_local']){
                $html .= '<video id="video-'.$dados['idvideo'] .'"
                        controls="controls" preload="none" height="345" width="420"
                        poster="/storage/videoteca/'. $caminho->caminho.'/'.$dados['idvideo'] .'/'.$dados['imagem'].'.jpg">';
                $html .= '<source src="/storage/videoteca/'. $caminho->caminho.'/'.$dados['idvideo'] .'/'.$dados['arquivo'].'_hd.mp4" type="video/mp4" ></source>';
            } else {
                 $html .= '<video id="video-'.$dados['idvideo'] .'"
                        controls="controls" preload="none" height="345" width="420"
                        poster="' . $config['videoteca_endereco'][rand(0, (count($config['videoteca_endereco']) - 1))] . '/' . $caminho->caminho . '/' . $dados['video_imagem'] . '">';
                $html .= '<source src="' . $config['videoteca_endereco'][rand(0, (count($config['videoteca_endereco']) - 1))] . '/' . $caminho->caminho . '/' . $dados['video_nome'] . '" type="video/mp4">';
            }
            $html .= '</video>';
        }

        if ('youtube' == $dados['variavel'] || 'vimeo' == $dados['variavel']) {
            $html .= '<iframe src="'.$dados['arquivo'].'?quality=540p" border="0" width="420" height="345" style="border: medium none"></iframe>';
        }

        $html .= '
            <legend>
            <h2 style="text-align: center;">INFORMAÇÕES DO VÍDEO</h2>
        </legend>';
        
        if('vimeo' == $dados['variavel']){
            $html .= '<a onclick="AtualizaInformacoes'.$dados['idvideo'].'();" class="btn-mini btn"> Atualizar Informações </a>'; 
        }
        $html .= '<table class="table table-bordered table-condensed" border="0" cellpadding="5" cellspacing="0" style="width: 420px !important">
                    <tbody>
                    <tr>
                        <td><strong>Duraçao: </strong></td>
                        <td>'.$dados['duracao'].'</td>
                    </tr>
                    <tr>
                        <td><strong>Referência: </strong></td>
                        <td>'.$dados['variavel'].'</td>
                    </tr>
                    <tr>
                        <td><strong>Pasta: </strong></td>
                        <td>'.videoteca::getFolder($dados['idvideo']).'</td>
                    </tr>
                    <tr>
                        <td><strong>Descrição:</strong></td>
                        <td id="descricao'.$dados['idvideo'].'">'.nl2br(strip_tags($dados['descricao'])).'</td>
                    </tr>';


                    if ('html5' == $dados['variavel']
                        || 'interno' == $dados['variavel']
                    ) {
                        $html .=  '<tr>
                            <td colspan="3">
                                <center>
                                    <strong>
                                        <a href="/gestor/academico/videoteca/'.$dados['idvideo'].'/download">Download do vídeo</a>
                                    </strong>
                                </center>
                            </td>
                        </tr>';
                    }

                $html .= '</tbody>
            </table>';

        $html .= '</div></div>';

        $html .= '<p><strong>Duração: </strong><span id="duracao'.$dados['idvideo'].'">'.$dados['duracao'].'</span></p>';
        $html .= '<p><strong>Referência: </strong><span> Externa </span></p>';
        // $html .= '<p><strong>Pasta: </strong><span>'.videoteca::getFolder($dados['idvideo']).'</span></p>';
        $html .= '</div></div>';
        $html .= '</li>';
        $html .= '<script>
                    function AtualizaInformacoes'.$dados['idvideo'].'(idvideo){
                        var idvideo = '.$dados['idvideo'].';

                        $.post("/'.$url[0].'/'.$url[1].'/'.$url[2].'", {
                            url: idvideo,
                            acao: "atualizaInformacoes"
                        }, function (result){
                            window.alert(result);
                            document.location.href = "/'.$url[0].'/'.$url[1].'/'.$url[2].'";
                            return true;
                        });
                    }
                  </script>';

    }

    echo $html;
    echo '</ul>';

?>


    <div id="listagem_form_busca">
        <div class="input">
          <div class="inline-inputs">
            <?= $idioma["registros"]; ?>
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

                <?php
                $link = parse_url($_SERVER['REQUEST_URI']);

                $params = explode('&', $link['query']);

                foreach ($params as $value) {
                    $hashtable = explode('=', $value);
                    if ('pag' == $hashtable[0])
                        continue;
                    ?>

                    <input id="<?php echo $hashtable[0]; ?>" type="hidden" value="<?=$hashtable[1];?>" name="<?php echo $hashtable[0]; ?>" />
                <?php
                }

                 if($_GET["cmp"]){?>
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
<?php  if ($linhaObj->get('paginas')) : ?>
<div class="pagination"><ul><?php echo $linhaObj->GerarPaginacao($idioma); ?></ul></div>
<?php  endif; ?>
<div class="clearfix"></div>
</div>
</div>

<video style="display: none" id="other">

</video>
<div class="span3">
    <?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2", NULL)){ ?>
    <div class="box-conteudo">
        <span class="pull-right" style="width: 100%; padding-top:3px; color:#999">
            <a data-original-title="Clique para ver as opções de cadastro para vídeos" href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/opcoes" class="btn btn-primary" data-placement="left" rel="tooltip facebox">
                <i class="icon-plus icon-white"></i> <?= $idioma["nav_cadastrar"]; ?>
            </a>
        </span>

       <div class="clearfix"></div>
   </div>
   <?php } ?>

   <div class="box-conteudo folder-list" style="margin-top: 10px">

    <h2>Pastas</h2>
    <input type="text" class="span3" id="pasta-nome" placeholder="Nome da pasta">
    <button type="submit" class="btn">Criar</button>


    <table class="table tree-folder-list" style="margin-top:20px">
        <?php
        $link = parse_url($_SERVER['REQUEST_URI']);

        foreach ($listaDePastas as $value) {

            $class = '';
            if ($url[3] == $value['idpasta']) {
                $class = ' current';
            }
            echo '<tr class="'.$class.'">

            <td class="name-of-folder" data-id="'.$value['idpasta'].'" style="width: 100%;">
                <a href="/'.join('/', array(
                    $url[0], $url[1], $url[2]
                    )
                ).'/'.$value['idpasta'].'/filtro?'.$link['query'].'" data-id="'.$value['idpasta'].'" rel="tooltip" data-original-title="'.$value['caminho'].'" >'.$value['nome'].'</a>
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
<div class="box-conteudo" style="margin-top: 10px">

    <h2>Nuvem de tags</h2>

    <table class="table" style="margin-top:20px">
    <?php
    $link = parse_url($_SERVER['REQUEST_URI']);
    parse_str($link['query'], $optionsQuery);

    if (isset($optionsQuery['tag']))
        unset($optionsQuery['tag']);

    foreach ($videotecaTags->ListarTodas() as $value) {

        $cssClass = ($value['idtag'] == $_GET['tag'])
                    ? 'current-tag'
                    : '';

        $optionsQuery['tag'] = $value['idtag'];

        echo '<a href="?'.http_build_query($optionsQuery).'">
                <span class="tag label '.$cssClass.'">'.
                    rawurldecode($value['nome']).'
                </span>
            </a> ';
    }
    ?>
    </table>

    <div class="clearfix"></div>
</div>
</div>

</div>
<? incluirLib("rodape",$config,$usuario); ?>
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
                jQuery.post('/<?= $url[0]; ?>/<?= $url[1]; ?>/videoteca/renomearpasta', {
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

                jQuery.post('/<?= $url[0]; ?>/<?= $url[1]; ?>/videoteca/removerpasta', {
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

        jQuery.post('/<?= $url[0]; ?>/<?= $url[1]; ?>/videoteca/criarpasta', {
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

    $(".close, #facebox, body").click(function(){
        var videos = document.getElementsByTagName('video')
        var tamanho = videos.length;

        for(i = 0; i < tamanho; i++){
            videos[i].pause();
        }
    });




})(jQuery);
</script>
</div>
</body>
</html>