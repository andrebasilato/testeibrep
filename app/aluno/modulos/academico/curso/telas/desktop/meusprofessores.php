<? header('Content-Type: text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js">
<!--<![endif]-->

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
                <span class="top-box box-azul">
                    <h1><?php echo $idioma['titulo']; ?></h1>
                    <i class="icon-group"></i>
                </span>
                <h2 class="ball-icon">&bull;</h2>
                <div class="clear"></div>
                <div class="row-fluid">
                    <div class="span12">
                        <div class="row-fluid">
                            <div class="span12 abox extra-align">
                                <?php if (!count($professoresCategorias)) {
                                    echo 'Nenhuma categoria encontrada';
                                } ?>
                                <?php foreach ($professoresCategorias as $idcategoria => $categoria) {
                                    if (count($categoria['professores'])) { ?>
                                        <h4><?php echo $categoria['nome']; ?></h4>
                                    <?php } ?>
                                    <?php
                                    $contadorProfessores = array();
                                    foreach ($categoria['professores'] as $professor) {
                                        $professores_listados[$professor['idprofessor']] = $professor['idprofessor'];
                                        $contadorProfessores[$idcategoria]++;
                                        if ($contadorProfessores[$idcategoria] == 1) { ?>
                                            <!-- Linha -->
                                            <div class="row-fluid">
                                            <?php } ?>
                                            <div class="span3 window-box" style="<?php if ($professor['tipo'] == 'P') { ?>border: 1px solid #08c;<?php } else { ?>border: 1px solid #bc4a77;<?php } ?>">
                                                <div class="picture-avatar"><img src="/api/get/imagens/professores_avatar/56/56/<?php echo $professor['avatar_servidor']; ?>" alt="Avatar"></div>
                                                <h4><?php echo $professor['nome']; ?></h4>
                                                <h5 style="<?php if ($professor['tipo'] == 'P') { ?>color: #08c;<?php } ?>"><?php echo $tipo_professor_config[$config['idioma_padrao']][$professor['tipo']]; ?></h5>
                                                <?php if (verificaPermissaoAcesso(false)) { ?>
                                                    <a href="<?php echo '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $matricula['idmatricula'] . '/' . $url[4] . '/' . $url[5] . '/' . $professor['idprofessor'] . '/mensagens'; ?>" class="abrirModal">
                                                        <div class="btn btn-<?php echo $cor_ava_tipo_professor[$professor['tipo']]; ?>"><?php echo $idioma['tira_duvidas']; ?></div>
                                                    </a>
                                                <?php } ?>
                                                <?php //if($professor['tipo'] == 'P') { 
                                                ?>
                                                <a href="<?php echo '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $matricula['idmatricula'] . '/' . $url[4] . '/' . $url[5] . '/' . $professor['idprofessor'] . '/disciplinas'; ?>" class="abrirModal">
                                                    <h6><?php echo $idioma['ver_disciplinas']; ?></h6>
                                                </a>
                                                <?php //} 
                                                ?>
                                            </div>
                                            <?php
                                            if ($contadorProfessores[$idcategoria] == 4) {
                                                $contadorProfessores[$idcategoria] = 0; ?>
                                            </div>
                                            <!-- /Linha -->
                                        <?php }
                                        }
                                        if ($contadorProfessores[$idcategoria] > 0) { ?>
                            </div>
                            <!-- /Linha -->
                    <?php }
                                    } ?>
                    <?php //print_r2($professores);exit(); 
                    ?>
                    <?php //if () {
                    $contadorProfessores = 0;
                    foreach ($professores as $professor) {
                        if (!array_search($professor['idprofessor'], $professores_listados)) {
                            $contadorProfessores++;
                            if ($contadorProfessores == 1) { ?>
                                <h4>Outros Professores</h4>
                                <!-- Linha -->
                                <div class="row-fluid">
                                <?php } ?>
                                <div class="span3 window-box" style="<?php if ($professor['tipo'] == 'P') { ?>border: 1px solid #08c;<?php } else { ?>border: 1px solid #bc4a77;<?php } ?>">
                                    <div class="picture-avatar"><img src="/api/get/imagens/professores_avatar/56/56/<?php echo $professor['avatar_servidor']; ?>" alt="Avatar"></div>
                                    <h4><?php echo $professor['nome']; ?></h4>
                                    <h5 style="<?php if ($professor['tipo'] == 'P') { ?>color: #08c;<?php } ?>"><?php echo $tipo_professor_config[$config['idioma_padrao']][$professor['tipo']]; ?></h5>
                                    <?php if ($professor['tipo'] == 'P') { ?>
                                        <a href="<?php echo '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $matricula['idmatricula'] . '/' . $url[4] . '/' . $url[5] . '/' . $professor['idprofessor'] . '/disciplinas'; ?>" class="abrirModal">
                                            <h6><?php echo $idioma['ver_disciplinas']; ?></h6>
                                        </a>
                                    <?php } ?>
                                </div>
                                <?php
                                if ($contadorProfessores == 4) {
                                    $contadorProfessores = 0; ?>
                                </div>
                                <!-- /Linha -->
                    <?php }
                            }
                        }
                        //} 
                    ?>
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
    <script src="/assets/js/validation.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            // Support for AJAX loaded modal window.
            // Focuses on first input textbox after it loads the window.
            $('.abrirModal').click(function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                if (url.indexOf('#') == 0) {
                    $(url).modal('open').on('shown', function() {}).on("hidden", function() {
                        $(this).remove();
                    });
                } else {
                    $.get(url, function(data) {
                        $('<div class="modal hide fade text-side-two extra-align" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">' + data + '</div>').modal().on('shown', function() {}).on("hidden", function() {
                            $(this).remove();
                        });
                    }).success(function() {
                        $('input:text:visible:first').focus();
                    });
                }
            });
        });
    </script>
</body>

</html>