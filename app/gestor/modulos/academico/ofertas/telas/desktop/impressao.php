<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php incluirLib("head",$config,$usuario); ?>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style type="text/css">
        body, td, th {
            font-family: Verdana, Geneva, sans-serif;
            font-size: 10px;
            color: #000;
        }
        body {
            background-color: #FFF;
            background-image:none;
            padding-top: 0px;
            margin-left: 5px;
            margin-top: 5px;
            margin-right: 5px;
            margin-bottom: 5px;
        }
        a:link {
            color: #000;
            text-decoration: none;
        }
        a:visited {
            text-decoration: none;
            color: #000;
        }
        a:hover {
            text-decoration: underline;
            color: #000;
        }
        a:active {
            text-decoration: none;
            color: #000;
        }
    </style>
    <style type="text/css" media="print">
        body, td, th {
            font-family: Verdana, Geneva, sans-serif;
            font-size: 9px;
            color: #000;
        }
        .impressao {
            display:none;
        }
    </style>
</head>
<body>
<table width="100%" border="0" cellpadding="10" cellspacing="0">
    <tr>
        <td height="80"><table border="0" cellspacing="0" cellpadding="8">
                <tr>
                    <td>
                        <img src="/especifico/img/logo_empresa_peq.png" width="135" height="50">
                    </td>
                </tr>
            </table></td>
        <td align="right" valign="top"><table border="0" cellspacing="0" cellpadding="5" class="impressao">
                <tr>
                    <td><img src="/assets/img/print_24x24.png" width="24" height="24"></td>
                    <td><a href="javascript:window.print();"><?= $idioma["imprimir"]; ?></a></td>
                </tr>
            </table></td>
    </tr>
</table>

<table border="0" cellpadding="10" cellspacing="0">
    <tr>
        <td><table border="1" cellpadding="8" cellspacing="0">
                <tr>
                    <td colspan=9 bgcolor="#F4F4F4"><strong><?= $idioma["dados_oferta"]; ?></strong></td>
                </tr>
                <tr>
                    <td align="left"><?= $idioma["nome"]; ?></td>
                    <td colspan="5"><strong><?= $impressao["oferta"]["nome"]; ?></strong></td>
                    <td align="left"><?= $idioma["modalidade"]; ?></td>
                    <td colspan="2"><strong><?= $impressao["oferta"]["modalidade"]; ?></strong></td>
                </tr>
                <tr>
                    <td align="left"><?= $idioma["data_inicio_matricula"]; ?></td>
                    <td colspan="5"><strong><?= formataData($impressao["oferta"]["data_inicio_matricula"],'pt',0); ?></strong></td>
                    <td align="left"><?= $idioma["data_fim_matricula"]; ?></td>
                    <td colspan="2"><strong><?= formataData($impressao["oferta"]["data_fim_matricula"],'pt',0); ?></strong></td>
                </tr>
                <tr>
                    <td align="left"><?= $idioma["data_inicio_ava"]; ?></td>
                    <td colspan="5"><strong><?= formataData($impressao["oferta"]["data_inicio_acesso_ava"],'pt',0); ?></strong></td>
                    <td align="left"><?= $idioma["data_fim_ava"]; ?></td>
                    <td colspan="2"><strong><?= formataData($impressao["oferta"]["data_fim_acesso_ava"],'pt',0); ?></strong></td>
                </tr>
            </table>
        </td></tr>
</table>

<table border="0" cellpadding="10" cellspacing="0">
    <tr>
        <td><table width="100%" border="1" cellpadding="8" cellspacing="0">
                <tr>
                    <td colspan=9 bgcolor="#F4F4F4"><strong><?= $idioma["dados_cursos"]; ?></strong></td>
                </tr>
                <tr>
                    <th align="left"><?= $idioma["cursos_nome"]; ?></th>
                    <th align="left"><?= $idioma["cursos_matriculas"]; ?></th>
                </tr>
                <?php
                $total_cursos = 0;
                foreach($impressao['cursos'] as $curso) {
                    $total_cursos += $curso["matriculas"];
                    ?>
                    <tr>
                        <td align="left"><?= $curso["nome"]; ?></td>
                        <td align="left"><?= $curso["matriculas"]; ?></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td align="right"><strong><?= $idioma["total"]; ?></strong></td>
                    <td align="left"><strong><?= $total_cursos; ?></strong></td>
                </tr>
            </table>
        </td></tr>
</table>

<table border="0" cellpadding="10" cellspacing="0">
    <tr>
        <td><table width="100%" border="1" cellpadding="8" cellspacing="0">
                <tr>
                    <td colspan=9 bgcolor="#F4F4F4"><strong><?= $idioma["dados_escolas"]; ?></strong></td>
                </tr>
                <tr>
                    <th align="left"><?= $idioma["escolas_nome"]; ?></th>
                    <th align="left"><?= $idioma["escolas_matriculas"]; ?></th>
                </tr>
                <?php
                $total_escolas = 0;
                foreach($impressao['escolas'] as $escola) {
                    $total_escolas += $escola["matriculas"];
                    ?>
                    <tr>
                        <td align="left"><?= $escola["nome"]; ?></td>
                        <td align="left">
                            <a target="_blank" href="/<?= $url["0"]; ?>/relatorios/matriculas_relatorio/html?q[1|ma.idoferta]=<?= $url["3"]; ?>&q[1|ma.idescola]=<?= $escola["idescola"]; ?>&de=<?= formataData($impressao["oferta"]["data_inicio_matricula"],'pt',0); ?>&ate=<?= formataData($impressao["oferta"]["data_fim_matricula"],'pt',0); ?>">
                                <?= $escola["matriculas"]; ?>
                            </a>
                        </td>
                    </tr>
                <?php } ?>
                <tr>
                    <td align="right"><strong><?= $idioma["total"]; ?></strong></td>
                    <td align="left"><strong><?= $total_escolas; ?></strong></td>
                </tr>
            </table>
        </td></tr>
</table>

<table border="0" cellpadding="10" cellspacing="0">
    <tr>
        <td><table width="100%" border="1" cellpadding="8" cellspacing="0">
                <tr>
                    <td colspan=9 bgcolor="#F4F4F4"><strong><?= $idioma["dados_turmas"]; ?></strong></td>
                </tr>
                <tr>
                    <th align="left"><?= $idioma["turmas_nome"]; ?></th>
                    <th align="left"><?= $idioma["turmas_matriculas"]; ?></th>
                </tr>
                <?php
                $total_turmas = 0;
                foreach($impressao['turmas'] as $turma) {
                    $total_turmas += $turma["matriculas"];
                    ?>
                    <tr>
                        <td align="left"><?= $turma["nome"]; ?></td>
                        <td align="left">
                            <a target="_blank" href="/<?= $url["0"]; ?>/relatorios/matriculas_relatorio/html?q[1|ma.idoferta]=<?= $url["3"]; ?>&q[1|ma.idturma]=<?= $turma["idturma"]; ?>&de=<?= formataData($impressao["oferta"]["data_inicio_matricula"],'pt',0); ?>&ate=<?= formataData($impressao["oferta"]["data_fim_matricula"],'pt',0); ?>">
                                <?= $turma["matriculas"]; ?>
                            </a>
                        </td>
                    </tr>
                <?php } ?>
                <tr>
                    <td align="right"><strong><?= $idioma["total"]; ?></strong></td>
                    <td align="left"><strong><?= $total_turmas; ?></strong></td>
                </tr>
            </table>
        </td></tr>
</table>

<table border="0" cellpadding="10" cellspacing="0">
    <tr>
        <td>
            <table width="100%" border="1" cellpadding="8" cellspacing="0">
                <tr>
                    <td colspan=9 bgcolor="#F4F4F4"><strong><?= $idioma["dados_cursos_escolas"]; ?></strong></td>
                </tr>
                <tr>
                    <th align="left"><?= $idioma["cursos_escolas_escola"]; ?></th>
                    <th align="left"><?= $idioma["cursos_escolas_curso"]; ?></th>
                    <th align="left"><?= $idioma["cursos_escolas_curriculo"]; ?></th>
                    <th align="left"><?= $idioma["cursos_escolas_limitador"]; ?></th>
                    <th align="left"><?= $idioma["cursos_escolas_dias"]; ?></th>
                    <th align="left"><?= $idioma["cursos_escolas_data"]; ?></th>
                    <th align="left"><?= $idioma["cursos_escolas_ignorar"]; ?></th>
                    <th align="left"><?= $idioma["cursos_escolas_turmas"]; ?></th>
                    <th align="left"><?= $idioma["cursos_escolas_matriculas"]; ?></th>
                </tr>
                <?php
                $total_geral = 0;
                foreach($impressao['escolas'] as $indescola => $escola) {
                    foreach($impressao['cursos'] as $indcurso => $curso) {
                        foreach($impressao['turmas'] as $indturma => $turma) {
                            $matriculas = $impressao["matriculas_p_c_t"][$escola['idescola']][$curso["idcurso"]][$turma['idturma']];
                            $configuracoes = $impressao["configuracao_p_c"][$escola['idescola']][$curso["idcurso"]];
                            $total_geral += $matriculas;
                            ?>
                            <tr>
                                <td align="left" ><?= $escola['sindicato']; ?> -> <?= $escola['nome']; ?></td>
                                <td align="left"><?= $curso["nome"]; ?></td>
                                <td align="left"><?= $configuracoes["curriculo"]; ?></td>
                                <td align="left"><?= $configuracoes["limite"]; ?></td>
                                <td align="left"><?= $configuracoes["dias_para_ava"]; ?></td>
                                <td align="left"><?= formataData($configuracoes["data_limite_ava"],'pt',0); ?></td>
                                <td align="left"><?= $sim_nao[$config["idioma_padrao"]][$configuracoes["ignorar"]]; ?></td>
                                <td align="left"><?php echo $turma['nome']; ?></td>
                                <td align="left">
                                    <a target="_blank" href="/<?= $url["0"]; ?>/relatorios/matriculas_relatorio/html?q[1|ma.idoferta]=<?= $url["3"]; ?>&q[1|ma.idturma]=<?= $turma["idturma"]; ?>&q[1|ma.idescola]=<?= $escola["idescola"]; ?>&q[1|ma.idcurso]=<?= $curso["idcurso"]; ?>&de=<?= formataData($impressao["oferta"]["data_inicio_matricula"],'pt',0); ?>&ate=<?= formataData($impressao["oferta"]["data_fim_matricula"],'pt',0); ?>">
                                        <?php echo ($matriculas) ? $matriculas : 0; ?>
                                    </a>
                                </td>
                            </tr>
                        <?php } } } ?>
                <tr>
                    <td align="right" colspan="8"><strong><?php echo $idioma['total']; ?></strong></td>
                    <td align="left"><strong><?php echo $total_geral; ?></strong></td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<table width="100%" border="0" align="center" cellpadding="10" cellspacing="0">
    <tr>
        <td valign="top"><span style="color:#999999;">Gerado dia <?= date("d/m/Y "); ?> por <?= $usuario["nome"]; ?> (<?= $usuario["email"]; ?>)<br />
      Alfama Oráculo - Sistema acadêmico. <br>
      www.alfamaoraculo.com.br
    </span></td>
        <td align="right" valign="top"><img src="/assets/img/logo_pequena.png" width="135" height="50"></td>
    </tr>
</table>
</body>
</html>