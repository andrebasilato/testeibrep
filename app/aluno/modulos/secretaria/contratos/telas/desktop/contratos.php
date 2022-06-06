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

<?php incluirLib("topo", $config, $usuario); ?>
<!-- /Topo -->
<!-- Conteudo -->
<div class="content">
    <p class="texto-index"></p>
</div>
<div class="content">
    <div class="box-bg">
        <span class="top-box box-azul">
            <h1><?= $idioma['titulo']; ?></h1>
            <i class="icon-folder-open"></i>
        </span>
        <h2 class="ball-icon">&bull;</h2>
        <div class="clear"></div>
        <!-- Atendimentos -->
        <div class="row-fluid">
            <div class="span12 abox box-item extra-align">
                <?php
                if($_POST["msg"]) {
                    ?>
                    <div class="alert alert-success fade in">
                        <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                        <strong><?= $idioma[$_POST["msg"]]; ?></strong>
                    </div>
                    <?php
                }
                foreach($matriculas as $matricula) {
                    $matriculaObj->Set('id', $matricula['idmatricula']);
                    $contratos = $matriculaObj->RetornarContratos();
                    $contratosPendentes = $matriculaObj->RetornarContratosPendentes();
                    foreach ($contratosPendentes as $key => $value) {
                        array_push($contratos,$contratosPendentes[$key]);
                    }
                    ?>
                    <div class="row-fluid">
                        <div class="span12 border-box">
                            <div class="row-fluid">
                                <div class="span2">
                                    <div class="imagem-item"><img src="/api/get/imagens/cursos_imagem_exibicao/168/114/<?= $matricula["imagem_exibicao_servidor"]; ?>" alt="Curso" /></div>
                                </div>
                                <div class="span10">
                                    <div class="row-fluid show-grid">
                                        <div class="span12 description-item r-margin">
                                            <div class="span8">
                                                <h1><?= $matricula['curso']; ?></h1>
                                                <p><?= $idioma['carga_horaria']; ?> <strong><?= $matricula['carga_horaria_total']; ?></strong></p>
                                                <p><?= $idioma['matricula']; ?> <strong><?= $matricula['idmatricula']; ?></strong></p>
                                                <p><?= $idioma['andamaento_curso']; ?> <strong><?= number_format($matricula['porcentagem'],2,',','.'); ?>%</strong></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <table width="100%" border="0" cellspacing="1" cellpadding="5">
                                        <thead class="a-table">
                                            <tr bgcolor="#e6e6e6">
                                                <td align="center"><?= $idioma['contrato_numero']; ?></td>
                                                <td align="center"><?= $idioma['contrato_tipo']; ?></td>
                                                <td align="center"><?= $idioma['contrato_nome']; ?></td>
                                                <td align="center"><?= $idioma['contrato_data_geracao']; ?></td>
                                                <td align="center"><?= $idioma['contrato_opcoes']; ?></td>
                                            </tr>
                                        </thead>
                                        <?php
                                        if(count($contratos)) {
                                            ?>
                                            <tbody class="a-table">
                                                <?php
                                                foreach($contratos as $contrato) {
                                                    ?>
                                                    <tr>
                                                        <td><?= $contrato['idmatricula_contrato']; ?></td>
                                                        <td><?= $contrato['tipo']; ?></td>
                                                        <td><?= $contrato['contrato']; ?></td>
                                                        <td><?= formataData($contrato['data_cad'],'br',0); ?></td>
                                                        <td width="21%">
                                                            <?php
                                                            if ($contrato['arquivo_servidor']) {
                                                                ?>
                                                                <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $contrato['idmatricula']; ?>/<?= $contrato['idmatricula_contrato']; ?>/download" target="_blank"><div class="btn btn-azul btn-mob"><?= $idioma["baixar"]; ?></div></a>
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $contrato['idmatricula']; ?>/<?= $contrato['idmatricula_contrato']; ?>/contrato<?= ((!empty($contrato['contrato_pendente'])) ? '/pendentes' : '');?>" target="_blank"><div class="btn btn-azul btn-mob"><?= $idioma["baixar"]; ?></div></a>
                                                                <?php
                                                            }

                                                            //Só pode aceitar contrato se a sindicato da matrícula tiver acesso ao AVA liberado
                                                            if ($matricula['acesso_ava'] == 'S') {
                                                                if ($contrato['aceito_aluno'] == 'N' || $contrato['aceito'] == 'N') {
                                                                    ?>
                                                                    <input type="button" class="btn btn-amarelo btn-mob" value="<?= $idioma['aceitar']; ?>" onclick="aceitarContrato('<?= $contrato['idmatricula']; ?>','<?= $contrato['idmatricula_contrato']; ?>')" />
                                                                    <?php
                                                                } else {
                                                                    ?>
                                                                    <div class="btn btn-verde btn-mob no-click"><?= $idioma["contrato_aceito"]; ?></div>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                            </tbody>
                                          <?php
                                        } else {
                                            ?>
                                            <table width="100%" border="0" cellspacing="1" cellpadding="5">
                                                <tbody class="b-table">
                                                    <tr>
                                                        <td>
                                                            <i><?= $idioma['nenhum_contrato']; ?></i>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <?php
                                        }
                                        ?>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br />
                <?php } ?>
            </div>
        </div>
        <!-- Atendimentos -->
    </div>
</div>
<script type="text/javascript">
    function aceitarContrato(idmatricula, id) {
      var confirma = confirm('Deseja realmente aceitar o contrato?');
      if (confirma) {
          document.getElementById('idmatricula').value = idmatricula;
          document.getElementById('idmatricula_contrato').value = id;
          document.getElementById('formAceitarContrato').submit();
      }
    }
</script>
<form action="" method="post" id="formAceitarContrato" name="formAceitarContrato" enctype="multipart/form-data">
    <input type="hidden" name="acao" value="aceitar_contrato" />
    <input type="hidden" name="idmatricula" id="idmatricula" value="" />
    <input type="hidden" name="idmatricula_contrato" id="idmatricula_contrato" value="" />
</form>
<!-- /Conteudo -->
<?php incluirLib("rodape", $config, $usuario); ?>
</body>
</html>
