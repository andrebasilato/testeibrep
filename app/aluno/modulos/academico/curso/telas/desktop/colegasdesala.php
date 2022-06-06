<? header('Content-Type: text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <?php incluirLib("head", $config, $usuario); ?>
</head>
<body>

<!-- Topo -->
<?php incluirLib("topo", $config, $usuario); ?>
<!-- /Topo -->
<!-- Topo curso -->
<?php incluirLib("topo_curso", $config, $informacoesTopoCurso); ?>
<!-- /Topo curso -->
<!-- Conteudo -->
<div class="content" style="position: relative;">
    <div class="row container-fixed">
        <!-- Menu Fixo -->
        <?php incluirLib("menu", $config, $usuario); ?>
        <!-- /Menu Fixo -->   
        <div class="box-side box-bg">
            <span class="top-box box-verde">
                <h1><?php echo $idioma['titulo']; ?></h1>
                <i class="icon-user"></i>            
            </span>
            <h2 class="ball-icon">&bull;</h2> 
            <!-- /Rota Topo -->        
            <div class="clear"></div>
            <div class="row-fluid">
                <div class="span12">
                    <div class="row-fluid">
                        <div class="span12 abox rel-box extra-align">
                            <div class="row-fluid">
                                <div class="span5">
                                    <form id="formBusca" name="formBusca" method="get" action="">
                                        <div class="row-fluid search-fellow">
                                            <div class="btn-toolbar"> 
                                                <label><strong><?php echo $idioma['nome']; ?></strong></label>
                                                <input type="text" class="span7" name="b" id="b" value="<?php echo $_GET['b']; ?>">
                                                <input type="submit" value="<?php echo $idioma['busca']; ?>" class="btn btn-azul btn-fellow">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="span7">
                                    <div class="pagination pagination-mini fellow-pagination">
                                        <ul>
                                            <?php
                                            $listaletras = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                                            for($i = 0, $total = strlen($listaletras); $i < $total; $i++) {
                                                $letraExibida = substr($listaletras, $i, 1);
                                                ?>  
                                                <li class="<?php if(strtolower($_GET['l']) == strtolower($letraExibida)) { ?>active<?php } ?>"><a href="?l=<?php echo $letraExibida; ?>"><?php echo $letraExibida; ?></a></li> 
                                            <?php } ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <?php 
                            $contadorColegas = 0;
                            foreach($colegas as $colega) { 
                                $contadorColegas++;
                                if($contadorColegas == 1) { ?>
                                    <!-- Linha -->
                                    <div class="row-fluid">
                                <?php } ?>
                                <div class="span3 window-box-height">
                                    <div class="picture-avatar">
                                        <img src="/api/get/imagens/pessoas_avatar/56/56/<?php echo $colega['avatar_servidor']; ?>" alt="Avatar">
                                    </div>
                                    <h4><?php echo $colega['nome']; ?></h4>
                                    <?php if(verificaPermissaoAcesso(false) && $colega['disponivel_interacao'] == 'S') { ?>
                                        <a href="<?php echo '/'.$url[0].'/'.$url[1].'/'.$url[2].'/'.$matricula['idmatricula'].'/'.$url[4].'/'.$url[5].'/'.$colega['idpessoa'].'/mensagens'; ?>" class="abrirModal"><div class="btn btn-azul"><?php echo $idioma['tira_duvidas']; ?></div></a>
                                    <?php } ?>
                                </div>
                                <?php 
                                if($contadorColegas == 4) { 
                                    $contadorColegas = 0; ?>                                   
                                    </div>
                                    <!-- /Linha -->
                                <?php } 
                            } 
                            if($contadorColegas > 0) { ?>   
                                </div>                                            
                                <!-- /Linha -->    
                            <?php } ?>
                            <br />
                            <div class="pagination pagination-small pagination-b">
                                <ul>
                                    <?php echo $matriculaObj->retornarPaginacaoColegas($idioma); ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Conteudo -->
<?php incluirLib("rodape", $config, $usuario); ?>
<script type="text/javascript">
    $(document).ready(function() {  
        // Support for AJAX loaded modal window.
        // Focuses on first input textbox after it loads the window.
        $('.abrirModal').click(function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            if (url.indexOf('#') == 0) {
                $(url).modal('open').on('shown', function () { }).on("hidden", function () { $(this).remove(); });
            } else {
                $.get(url, function(data) {
                    $('<div class="modal hide fade text-side-two extra-align" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">'+data+'</div>').modal().on('shown', function () { }).on("hidden", function () { $(this).remove(); });
                }).success(function() { 
                    $('input:text:visible:first').focus();
                });
            }
        });
    });
</script>
</body>
</html>