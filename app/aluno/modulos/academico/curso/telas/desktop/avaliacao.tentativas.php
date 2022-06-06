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
                        <div class="row-fluid m-box">
                            <div class="span3">
                                <div class="imagem-item"><img src="/api/get/imagens/avas_avaliacoes_imagem_exibicao/249/138/<?php echo $avaliacao["imagem_exibicao_servidor"]; ?>" alt="Avaliação" /></div>
                            </div>
                            <div class="span9">
                                <div class="row-fluid show-grid">
                                    <div class="span12 description-item">
                                        <div class="span8">
                                            <h1><?php echo $avaliacao['nome']; ?></h1>
                                            <p><?php echo $idioma['periodo']; ?> <strong><?php echo formataData($avaliacao['periode_de'],'br',0).' - '.formataData($avaliacao['periode_ate'],'br',0); ?></strong></p>
                                            <p><?php echo $idioma['tentativas']; ?> <strong><?php echo count($avaliacoes); ?></strong></p>
                                        </div>                               
                                    </div>
                                </div>
                            </div> 
                        </div>
                        <div class="row-fluid">
                            <div class="span12 center-align">
                                <table width="100%" border="0" cellspacing="1" cellpadding="5">
                                    <tbody class="a-table">
                                        <tr bgcolor=#e6e6e6>
                                            <td><?php echo $idioma['tabela_id']; ?></td>
                                            <td><?php echo $idioma['tabela_inicio']; ?></td>
                                            <td><?php echo $idioma['tabela_fim']; ?></td>
                                            <td><?php echo $idioma['tabela_corrigida']; ?></td>
                                            <td><?php echo $idioma['tabela_respondido']; ?></td>
                                            <td><?php echo $idioma['tabela_nao_respondido']; ?></td>
                                            <td><?php echo $idioma['tabela_nota']; ?></td>
                                            <td>&nbsp;</td>
                                        </tr>
                                    </tbody>
                                    <tbody class="b-table">
                                        <?php foreach($avaliacoes as $avaliacao) { ?>
                                            <tr>
                                                <td><?php echo $avaliacao['idprova']; ?></td>
                                                <td><?php echo formataData($avaliacao['inicio'],'br',1); ?></td>
                                                <td><?php echo formataData($avaliacao['fim'],'br',1); ?></td>
                                                <td><?php echo $idioma[$avaliacao['prova_corrigida']]; ?></td>
                                                <td>
                                                <?php
                                                    echo  $avaliacao['questoes_respondidas'] . '/' . $avaliacao['total_questoes']; 
                                                ?>
                                                </td>
                                                <td>
                                                <?php
                                                    echo  ($avaliacao['total_questoes'] - $avaliacao['questoes_respondidas']) . '/' . $avaliacao['total_questoes']; 
                                                ?>
                                                </td>
                                                <td><?php echo number_format($avaliacao['nota'],1,',','.'); ?></td>
                                                <td><a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $matricula['idmatricula']; ?>/<?php echo $url[4]; ?>/<?php echo $url[5]; ?>/<?php echo $url[6]; ?>/<?php echo $url[7]; ?>/<?php echo $avaliacao['idprova']; ?>/visualizar"><div class="btn btn-azul btn-mob"><?php echo $idioma["visualizar_prova"]; ?></div></a></td>
                                            </tr>
                                        <?php } ?>        
                                    </tbody> 
                                </table>
                                <?php if(count($avaliacoes) <= 0) { ?>
                                    <table width="100%" border="1" bordercolor=#d3d7da cellspacing="1" cellpadding="5">
                                        <tbody class="c-table">
                                            <tr>
                                                <td><i><?php $idioma['nenhuma_avaliacao']; ?></i></td>
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
</html>