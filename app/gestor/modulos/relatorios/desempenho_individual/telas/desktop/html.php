<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <?php incluirLib("head", $config, $usuario); ?>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style type="text/css">
        body,
        td,
        th {
            font-family: Verdana, Geneva, sans-serif;
            font-size: 10px;
            color: #000;
        }

        body {
            background-color: #FFF;
            background-image: none;
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

        table.zebra-striped {
            padding: 0;
            font-size: 9px;
            border-collapse: collapse;
        }

        table.zebra-striped th,
        table td {
            padding: 8px 8px 7px;
            line-height: 16px;
            text-align: left;
        }

        table.zebra-striped th {
            padding-top: 9px;
            font-weight: bold;
            vertical-align: middle;
        }

        table.zebra-striped td {
            vertical-align: top;
            border: 1px solid #000;
        }

        table.zebra-striped tbody th {
            border-top: 1px solid #ddd;
            vertical-align: top;
        }

        .zebra-striped tbody tr:hover td,
        .zebra-striped tbody tr:hover th {
            background-color: #E4E4E4;
        }

        table.tabela-com-bordas {
            width: 100%;
            border: 1px solid #333;
            margin-bottom: 1rem;
        }

        .tabela-com-bordas tbody {
            width: 100%; float: left
        }
    </style>
    <style type="text/css" media="print">
        @page {
            margin: 0.5cm;
        }
        body,
        td,
        th {
            font-family: Verdana, Geneva, sans-serif;
            font-size: 13px;
            color: #000;
        }
        .font-impressao {
            font-size: 15px !important;
        }
        .impressao {
            display: none;
        }

    </style>
    <script src="/assets/js/jquery.1.7.1.min.js"></script>
    <script src="/assets/plugins/facebox/src/facebox.js"></script>
    <script src="/assets/js/validation.js"></script>
    <script src="/assets/bootstrap_v2/js/bootstrap-tooltip.js"></script>
    <script>
        $(document).ready(function() {
            $('a[rel*=facebox]').facebox();
        });
        var regras = new Array();
        regras.push("required,nome,<?php echo $idioma['nome_obrigatorio']; ?>");
    </script>
</head>

<body>
    <table width="100%" border="0" cellpadding="10" cellspacing="0">
        <tr>
            <td height="80">
                <table border="0" cellspacing="0" cellpadding="8">
                    <tr>
                        <td><a href="/<?= $url[0]; ?>" class="logo"></a><?php/*<img src="<?php echo $config['logo_pequena']; ?>" />*/?></td>
                    </tr>
                </table>
            </td>
            <td align="center">
                <h2><strong><?= $idioma["pagina_titulo"]; ?></strong></h2>
            </td>
            <td align="right" valign="top">
                <table border="0" align="right" cellpadding="3" cellspacing="0" class="impressao">
                    <tr>
                        <td><img src="/assets/img/print_24x24.png" width="24" height="24"></td>
                        <td><a href="javascript:window.print();">
                                <?= $idioma["imprimir"]; ?>
                            </a></td>

                        <td>
                            <a class="btn" href="#link_salvar" rel="facebox"><?php echo $idioma['salvar_relatorio'] ?></a>
                            <div id="link_salvar" style="display:none;">
                                <div style="width:300px;">
                                    <form method="post" onsubmit="return validateFields(this, regras)">
                                        <input type="hidden" name="acao" value="salvar_relatorio" />
                                        <label for="nome"><strong><?php echo $idioma['tabela_nome']; ?>:</strong></label>
                                        <input type="text" class="input" name="nome" id="nome" style="height:30px;" /><br /><br />
                                        <input type="submit" class="btn" value="<?php echo $idioma['salvar_relatorio'] ?>" />
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <?php if ($mensagem_sucesso) { ?>
        <div class="alert alert-success fade in">
            <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
            <strong><?= $idioma[$mensagem_sucesso]; ?></strong>
        </div>
    <?php } else if ($mensagem_erro) { ?>
        <div class="alert alert-error fade in">
            <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
            <strong><?= $idioma[$mensagem_erro]; ?></strong>
        </div>
    <?php } ?>

    <table class="tabela-com-bordas">
        <tr>
            <td> <strong>Nome: </strong> </td>
            <td> <?= $dadosArray['aluno'] ?> </td>
        </tr>
        <tr>
            <td> <strong>CPF: </strong> </td>
            <td> <?= $dadosArray['documento'] ?> </td>
        </tr>
        <tr>
            <td> <strong>RG/Órgão: </strong> </td>
            <td> <?= $dadosArray['rg'] ?> / <?= $dadosArray['rg_orgao_emissor'] ?> </td>
        </tr>
        <tr>
            <td> <strong>Sexo: </strong> </td>
            <td> <?= $sexo[$config["idioma_padrao"]][$dadosArray['sexo']] ?> </td>
        </tr>
        <tr>
            <td> <strong>CNH: </strong> </td>
            <td> <?= $dadosArray['cnh'] ?> </td>
        </tr>
        <tr>
            <td> <strong>Categoria: </strong> </td>
            <td> <?= $dadosArray['categoria'] ?> </td>
        </tr>
        <tr>
            <td> <strong>Nome da mãe: </strong> </td>
            <td> <?= $dadosArray['filiacao_mae'] ?> </td>
        </tr>
        <tr>
            <td> <strong>Data de nascimento: <strong> </td>
            <td> <?= formataData($dadosArray['data_nasc']) ?> </td>
        </tr>
        <tr>
            <td> <strong>Telefone: </strong> </td>
            <td> <?= $dadosArray['telefone'] ?> </td>
        </tr>
        <tr>
            <td> <strong>Celular: </strong> </td>
            <td> <?= $dadosArray['celular'] ?> </td>
        </tr>
        <tr>
            <td> <strong>Email: </strong> </td>
            <td> <?= $dadosArray['email'] ?> </td>
        </tr>

    </table>

    <table class="tabela-com-bordas">
        <legend>Inscrição</legend>
        <tbody>
            <tr>
                <td class="font-impressao"> <strong>Data: </strong> <?= formataData($dadosArray['data_matricula']) ?> </td>
                <td class="font-impressao"> <strong>Hora: </strong> <?= $dadosArray['hora_matricula'] ?> </td>
            </tr>
            <tr>
                <td class="font-impressao"><strong>Curso: </strong> <?= $dadosArray['curso'] ?></td>
            </tr>
        </tbody>
    </table>

    <?php foreach ($dadosArray['avas'] as $ava): ?>
        <?php
        $tempoOffline = NULL;
        $tempoOffline = $matriculasObj->somaHorasOfflinesDisciplinas($ava['idava'],$ava['iddisciplina']);
        $matAcessosAva = getAcessos($acessos,$ava['idava']);
        $totalAcessosAva = getTotalAcesso($acessoTotal,$ava['idava']);
        $provaAva = getProvaFinal($avaliacoes,$ava['idava']);
        $tempoTotal = somaDuasHoras($tempoOffline['tempo_total_offline'],$totalAcessosAva['duracao']);
        ?>
        <!--<?php print_r($tempoOffline); ?> -->
        <!--<?php print_r($matAcessosAva); ?> -->
        <!--<?php print_r($totalAcessosAva); ?> -->
        <table class="tabela-com-bordas">
            <legend>AVA <?= $ava['idava'] ?> / <?= $ava['nome_disciplina'] ?> - (<?= $ava['carga_min'] ?> h/a)</legend>
            <tbody>
                <tr>
                    <td class="font-impressao"> <strong>Data Início: </strong> <?= $ava['data_ini'] ? formataData($ava['data_ini']) : '' ?> </td>
					<td class="font-impressao"> <strong>Hora Início: </strong> <?= $ava['data_ini'] ? date('H:i', strtotime($ava['data_ini'])) : '' ?></td>
					<td class="font-impressao"> <strong>Data Final: </strong> <?= ($ava['data_fim']) ? formataData($ava['data_fim']) : '' ?> </td>
                    <td class="font-impressao"> <strong>Hora Final: </strong> <?= ($ava['data_fim']) ? date('H:i', strtotime($ava['data_fim'])) : '' ?></td>
                    <td class="font-impressao"> <strong>Tempo Total de Conexão: </strong> <?= ($tempoTotal) ? $tempoTotal : '' ?></td>
                </tr>
                <tr>
                    <td colspan="5" style="text-alig:left;"><strong><?php echo $idioma['log_acessos']; ?></strong></td>
                </tr>
                <?php if ( is_array($matAcessosAva) ) { ?>
                <?php foreach ($matAcessosAva as $acessosAva): ?>
                <tr>
                    <td> <strong>Data Início: </strong> <?= $acessosAva['inicio_data'] ? formataData($acessosAva['inicio_data']) : '' ?> </td>
					<td> <strong>Hora Início: </strong> <?= $acessosAva['inicio_hora'] ? $acessosAva['inicio_hora'] : '' ?></td>
					<td> <strong>Data Final: </strong> <?= $acessosAva['fim_data'] ? formataData($acessosAva['fim_data']) : '' ?> </td>
                    <td> <strong>Hora Final: </strong> <?= $acessosAva['fim_hora'] ? $acessosAva['fim_hora'] : '' ?> <?= ($acessosAva['inatividade'] == 'S') ? '(Inatividade)' : ''?></td>
                    <td> <strong>Duração: </strong> <?= $acessosAva['duracao'] ? $acessosAva['duracao'] : '' ?></td>
                </tr>
                <?php endforeach; ?>
                <?php } ?>

                <tr>
                    <td class="font-impressao"> <strong>Data Avaliação: </strong> <?= $ava['dados_nota']['data_avaliacao'] ? formataData($ava['dados_nota']['data_avaliacao']) : '' ?> </td>
                    <td class="font-impressao"> <strong>Hora Avaliação: </strong> <?= $ava['dados_nota']['data_avaliacao'] ? date('H:i', strtotime($ava['dados_nota']['data_avaliacao'])) : '' ?> </td>
                    <td class="font-impressao"> <strong>Nota Avaliação: </strong> <?= $ava['dados_nota']['nota'] ?> </td>
                </tr>
            </tbody>
        </table>
    <?php endforeach; ?>

    <table width="100%" border="0" align="center" cellpadding="10" cellspacing="0">
        <tr>
            <td valign="top"><span style="color:#999999;"><?= $idioma["rodape"]; ?></span></td>
            <td align="right" valign="top">
                <div align="right"><a href="/<?= $url[0]; ?>" class="logo"></a></div>
            </td>
        </tr>
    </table>
</body>

</html>
