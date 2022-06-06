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
        <!-- Box -->
        <div class="box-side box-bg">
            <span class="top-box box-azul">
                <h1><?php echo $idioma['titulo']; ?></h1>
                <i class="icon-file-text"></i>            
            </span>
            <h2 class="ball-icon">&bull;</h2> 
            <div class="clear"></div>
            <div class="row-fluid box-item">
                <div class="span12">                
                    <div class="abox extra-align">
                        <?php if($_POST["msg"]) { ?>
                            <div class="alert alert-success fade in">
                                <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                                <strong><?php echo $idioma[$_POST["msg"]]; ?></strong>
                            </div>
						<?php } ?>
                        <div class="row-fluid m-box">
                            <div class="span3">
                                <div class="imagem-item"><img src="/api/get/imagens/cursos_imagem_exibicao/249/138/<?php echo $curso["imagem_exibicao_servidor"]; ?>" alt="Curso" /></div>
                            </div>
                            <div class="span9">
                                <div class="row-fluid show-grid">
                                    <div class="span12 description-item">
                                        <div class="span8">
                                            <h1><?php echo $curso['nome']; ?></h1>
                                            <p><?php echo $curso['aluno']; ?> <strong><?php echo $usuario['nome']; ?></strong></p>
                                        </div>                               
                                    </div>
                                </div>
                            </div> 
                        </div>
                        <div class="row-fluid">
                            <div class="span12 center-align">
                                <table width="100%" border="0" cellspacing="1" cellpadding="5">
                                    <tbody class="a-table">
                                        <tr bgcolor="#e6e6e6">
                                            <td><?php echo $idioma['tabela_nome']; ?></td>
                                            <td><?php echo $idioma['tabela_de']; ?></td>
                                            <td><?php echo $idioma['tabela_ate']; ?></td>
                                            <td><?php echo $idioma['tabela_opcoes']; ?></td>
                                        </tr>
                                    </tbody>
                                    <tbody class="b-table">
                                        <?php foreach($simulados as $simulado) { ?>
                                            <tr>
                                                <td><?php echo $simulado['nome']; ?></td>
                                                <td><?php echo formataData($simulado['periode_de'],'br',0); ?></td>
                                                <td><?php echo formataData($simulado['periode_ate'],'br',0); ?></td>
                                                <td>
                                                    <?php 
                                                    $dataHoje = strtotime(date("Y-m-d"));
                                                    $de = strtotime($simulado["periode_de"]);
                                                    $ate = strtotime($simulado["periode_ate"]);
													
													if($de <= $dataHoje && $ate >= $dataHoje) { ?>
                                                        <?php if(verificaPermissaoAcesso(false)) { ?>
                                                            <div class="btn btn-azul btn-mob desabilitar-fazer-prova" data-idsimulado="<?php echo $simulado['idsimulado']; ?>"><?php echo $idioma["fazer_prova"]; ?></div>
                                                            <?php if ($simulado["tentativas"] > 0) { ?>
                                                                <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $matricula['idmatricula']; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/<?= $simulado['idsimulado']; ?>/tentativas"><div class="btn btn-verde btn-mob">(<?= $simulado["tentativas"] ?>) <?= $idioma["ver_tentativas"]; ?></div></a>
                                                            <?php } ?>
                                                        <?php } else { ?>
                                                            <div class="btn btn-azul btn-mob no-click"><?php echo $idioma["fazer_prova"]; ?></div> 
                                                            <?php if ($simulado["tentativas"] > 0) { ?>
                                                                <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $matricula['idmatricula']; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/<?= $simulado['idsimulado']; ?>/tentativas"><div class="btn btn-verde btn-mob">(<?= $simulado["tentativas"] ?>) <?= $idioma["ver_tentativas"]; ?></div></a>
                                                            <?php } ?>                                        
                                                        <?php } ?>
                                                    <?php } else { ?>
														<div class="btn btn-vermelho btn-mob no-click"><?php echo $idioma["fora_periodo"]; ?></div>
                                                        <?php if ($simulado["tentativas"] > 0) { ?>
                                                                <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $matricula['idmatricula']; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/<?= $simulado['idsimulado']; ?>/tentativas"><div class="btn btn-verde btn-mob">(<?= $simulado["tentativas"] ?>) <?= $idioma["ver_tentativas"]; ?></div></a>
                                                            <?php } ?>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php } ?>        
                                    </tbody> 
                                </table>
                                <?php if(count($simulados) <= 0) { ?>
                                    <table width="100%" border="1" bordercolor=#d3d7da cellspacing="1" cellpadding="5">
                                        <tbody class="c-table">
                                            <tr>
                                                <td><i><?php $idioma['nenhuma_simulado']; ?></i></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Box -->
    </div>
</div>
<!-- /Conteudo -->
<?php incluirLib("rodape", $config, $usuario); ?>
</body>
<script src="/assets/js/jquery.1.7.1.min.js"></script>
<script type="text/javascript">
    
    $(".desabilitar-fazer-prova").click(function(){
        var idsimulado = $(this).attr("data-idsimulado");
        var url = '/'+"<?php echo $url[0]; ?>"+'/'+"<?php echo $url[1]; ?>"+'/'+"<?php echo $url[2]; ?>"+'/'+"<?php echo $matricula['idmatricula']; ?>"+'/'+"<?php echo $url[4]; ?>"+'/'+"<?php echo $url[5]; ?>"+'/'+idsimulado+'/fazer';

        if ($(this).hasClass("btn-azul")){
            $(this).removeClass("btn-azul").addClass("btn-cinza");
            window.location.href = url; 
        }
    });
    <?php if($_GET['alert'] == 'verificarSimulados'){
        echo "alert('{$idioma['simulado_obrigatorio']}');";
        echo "window.location.href = '/{$url[0]}/{$url[1]}/{$url[2]}/{$url[3]}/{$url[4]}/simulado/';";
    }?>
</script>
</html>