<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php incluirLib('head', $config, $usuario); ?>
    <style type="text/css">
        body {
            padding-top: 0px;
        }

        .quebra_pagina {
            page-break-after:always;
        }
        .labelPagina {
            font-size: 10px;
            font-weight: 700;
            line-height: 18px;
        }
    </style>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<script>
    function imprimirContrato() {
        parent['frame_contrato'].focus();
        parent['frame_contrato'].print();
    }
</script>

<body>
    <table width="100%" height="90%" border="0" align="center" cellpadding="2" cellspacing="0">
        <tr>
            <td>
                <table width="99%" border="0" cellspacing="0" cellpadding="5" align="center">
                    <tr>
                        <td><img src="/assets/img/logo_pequena.png" width="135" height="50" /></td>
                        <td align="center">
                            #<?= $contratoPendente['idmatricula']; ?> - <?= $usuario['nome_fantasia']; ?>
                            <br />
                            <strong><?= $contratoPendente['contrato']; ?> (<?= $contratoPendente['tipo']; ?>)</strong>
                        </td>
                        <td align="right">
                            <a class="btn btn-small" onclick="imprimirContrato('frame_contrato', '5%', '5%', '90%', '90%');">
                                <i class="icon-print"></i><?= $idioma['matricula_imprimir']; ?>
                            </a>
                        </td>
                        <td align="right">
                            <a class="btn btn-small" href="/<?= $url[0] . '/secretaria/meuscursos'; ?>">
                                <i class='icon-share-alt'></i>Sair
                            </a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td align="center">
                <?php
                $arquivo = '/storage/matriculas_contratos_pendentes/' . $contratoPendente['arquivo_pasta'] . '/' .
                    $contratoPendente['idmatricula'] . '/' . $contratoPendente['idmatricula_contrato'] . '.html';

                $arquivoServidor = $_SERVER['DOCUMENT_ROOT'].$arquivo;
                if (file_exists($arquivoServidor)) {
                    ?>
                    <iframe name="frame_contrato" id="frame_contrato" src="<?= $arquivo; ?>" width="99%" height="450" frameborder="1" style="background-color:#FFFFFF"></iframe>
                    <?php
                } else {
                    ?>
                    <div class="alert alert-error">
                        <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                        <strong><?= $idioma['matricula_erro_label']; ?></strong>
                        <br />
                        <?= $idioma['matricula_erro_msg']; ?>
                    </div>
                    <?php
                }
                ?>
            </td>
        </tr>
    </table>
    <form id="form_contrato" name="form_contrato" style="width: 90%" method="post" onsubmit="return validateFields(this, regras)">
       <input type="hidden" name="acao" id="acao" value="concordar" />
       <div style="margin-left: 35px;" class="row-fluid">
           <div class="span12 contract payment">
               <h3>
                   <label class="labelPagina">
                       <input type="checkbox" id="contrato" onclick="DesabilitaBotao()" name="contrato" value="<?= $contratoPendente['idmatricula_contrato']; ?>">
                       <?= $idioma['li_concordo']; ?>
                   </label>
               </h3>
           </div>
       </div>
       <div style="margin-left: 35px;" class="row-fluid">
           <div class="span12 contract payment">
               <input type="submit" id="concordo" disabled="disabled" value="Concordo"></input>
           </div>
       </div>
    </form>
    <script src="/assets/min/aplicacao.desktop.min.js"></script>
    <script src="/assets/plugins/jquery-ui/ui/jquery.ui.core.js"></script>
    <script type="text/javascript">
        function DesabilitaBotao(){
            if ($('#contrato').prop('checked')) {
                $('#concordo').removeAttr('disabled');
            } else {
                $('#concordo').attr('disabled','disabled');
            }
        }
    </script>

</body>
</html>
