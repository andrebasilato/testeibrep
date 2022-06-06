<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php incluirLib("head", $config, $usuario); ?>
    <style>
        .flex {
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
        }

        .flex strong {
            margin-right: 0.5rem;
        }

        .flex strong.strong-right {
            margin-right: 0;
            margin-left: 0.5rem;
        }

        .uneditable-input.span2, input.span2, textarea.span2 {
            width: 83px;
        }

        .check {
            display: flex;
        }

        .check label {
            margin-left: .5rem;
        }

    </style>
</head>
<body>
<?php incluirLib("topo", $config, $usuario); ?>
<div class="container-fluid">
    <section id="global">
        <div class="page-header">
            <h1><?= $idioma["pagina_titulo"]; ?> &nbsp;
                <small><?= $idioma["pagina_subtitulo"]; ?></small>
            </h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="/<?= $url[0]; ?>"><?= $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
            <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>"><?= $idioma["nav_modulo"]; ?></a> <span
                    class="divider">/</span></li>
            <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"><?= $idioma["pagina_titulo"]; ?></a> <span
                    class="divider">/</span></li>
            <li class="active"><?= $linha["titulo_pagina"]; ?></li>
            <span class="pull-right"
                  style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
        </ul>
    </section>
    <div class="row-fluid">
        <div class="span12">
            <div class="box-conteudo">
                <div class=" pull-right">
                <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"><i
                            class="icon-share-alt"></i> <?= $idioma["btn_sair"]; ?></a></div>
                <h2 class="tituloEdicao"><?= $linha["nome"]; ?></h2>

                <div class="tabbable tabs-left">
                    <?php incluirTela("inc_menu_edicao", $config, $usuario); ?>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_editar">
                            <h2 class="tituloOpcao"><?= $linha["titulo_pagina"]; ?></h2>
                            <?php if ($_POST["msg"]) { ?>
                                <div class="alert alert-success fade in">
                                    <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                                    <strong><?= $idioma[$_POST["msg"]]; ?></strong>
                                </div>
                            <?php } ?>
                            <?php if (count($salvar["erros"]) > 0) { ?>
                                <div class="alert alert-error fade in">
                                    <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                                    <strong><?= $idioma["form_erros"]; ?></strong>
                                    <?php foreach ($salvar["erros"] as $ind => $val) { ?>
                                        <br/>
                                        <?php echo $idioma[$val]; ?>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                            <form method="POST" id="form-dados">
                                <input type="hidden" value="salvar_valores_curso" name="acao">
                                <table class="table table-striped tabelaSemTamanho">
                                    <thead>
                                        <tr>
                                            <th><?= $idioma["listagem_nome"]; ?></th>
<!--                                            <th><?/*= $idioma["listagem_avista"]; */?></th>
                                            <th><?/*= $idioma["listagem_aprazo"]; */?></th>-->
                                            <th><?= $idioma["1-quantidade_matriculas"]; ?></th>
                                            <th><?= $idioma["1-valor_por_matricula"]; ?></th>
                                            <th><?= $idioma["2-quantidade_matriculas"]; ?></th>
                                            <th><?= $idioma["2-valor_por_matricula"]; ?></th>
                                            <th><?= $idioma["valor_excedente"]; ?></th>
                                            <th><?= $idioma["listagem_parcelas"]; ?></th>
                                            <th><?= $idioma["quantidades_faturas_ciclo"]; ?></th>
                                            <th><?= $idioma["listagem_max_parcelas"]; ?></th>
                                            <th><?= $idioma["formas_pagamento"]; ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php if (count($cursos) > 0) { ?>
                                        <?php foreach ($cursos as $curso) { ?>
                                            <tr>
                                                <input name="valores[<?= $curso['idcurso'] ?>][idcurso]" value="<?= $curso['idcurso'] ?>" type="hidden">
                                                <input name="valores[<?= $curso['idcurso'] ?>][idvalor_curso]" value="<?= $curso['idvalor_curso'] ?>" type="hidden">
                                                <td>
                                                    <strong><?= $curso["nome"]; ?></strong>
                                                </td>
                                                <!--<td>
                                                    <div class="flex">
                                                        <strong>R$</strong>
                                                        <input
                                                            value="<?/*= (! empty($curso['avista'])) ? number_format($curso['avista'], 2, ',', '.') : '' */?>"
                                                            class="valor_monetario avista span2 valores<?/*= $curso['idcurso'] */?>"
                                                            name="valores[<?/*= $curso['idcurso'] */?>][avista]"
                                                            maxlength="12"
                                                            type="text"
                                                            >
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="flex">
                                                        <strong>R$</strong>
                                                        <input
                                                            value="<?/*= (! empty($curso['aprazo'])) ? number_format($curso['aprazo'], 2, ',', '.') : '' */?>"
                                                            class="valor_monetario aprazo span2 valores<?/*= $curso['idcurso'] */?>"
                                                            name="valores[<?/*= $curso['idcurso'] */?>][aprazo]"
                                                            maxlength="12"
                                                            type="text"
                                                        >
                                                    </div>
                                                </td>-->
                                                <td>
                                                <div class="flex">
                                                    <input
                                                            value="<?=$curso['quantidade_matriculas']?>"
                                                            class="parcela quantidade_matriculas_1 span2 valores<?= $curso['idcurso'] ?>"
                                                            name="valores[<?= $curso['idcurso']?>][quantidade_matriculas]"
                                                            maxlength="8" type="int">
                                                </div>
                                                </td>
                                                <td>
                                                    <div class="flex">
                                                        <strong>R$</strong>
                                                        <input
                                                                value="<?= !empty($curso['valor_por_matricula']) ? number_format($curso['valor_por_matricula'], 2, ',', '.') : '' ?>"
                                                                class="valor_matricula_1 valor_monetario span2 valores<?= $curso['idcurso'] ?>"
                                                                name="valores[<?= $curso['idcurso'] ?>][valor_por_matricula]"
                                                                maxlength="12" type="text">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="flex">
                                                        <input
                                                                value="<?=$curso['quantidade_matriculas_2']?>"
                                                                class="parcela quantidade_matriculas_2 span2 valores<?= $curso['idcurso'] ?>"
                                                                name="valores[<?= $curso['idcurso']?>][quantidade_matriculas_2]"
                                                                maxlength="8" type="int">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="flex">
                                                        <strong>R$</strong>
                                                        <input
                                                                value="<?= !empty($curso['valor_por_matricula_2']) ? number_format($curso['valor_por_matricula_2'], 2, ',', '.') : '' ?>"
                                                                class="valor_matricula_2 valor_monetario span2 valores<?= $curso['idcurso'] ?>"
                                                                name="valores[<?= $curso['idcurso'] ?>][valor_por_matricula_2]"
                                                                maxlength="12" type="text">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="flex">
                                                        <strong>R$</strong>
                                                        <input
                                                                value="<?= !empty($curso['valor_excedente']) ? number_format($curso['valor_excedente'], 2, ',', '.') : '' ?>"
                                                                class="valor_excedente valor_monetario span2 valores<?= $curso['idcurso'] ?>"
                                                                name="valores[<?= $curso['idcurso'] ?>][valor_excedente]"
                                                                maxlength="12" type="text">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="flex">
                                                        <input
                                                            value="<?= $curso['parcelas'] ?>"
                                                            class="parcela parcelas_max10 span1 valores<?= $curso['idcurso'] ?>"
                                                            name="valores[<?= $curso['idcurso'] ?>][parcelas]"
                                                            maxlength="2"
                                                            type="text">
                                                        <strong class="strong-right"> x</strong>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="flex">
                                                        <input
                                                        value="<?= $curso['quantidade_faturas_ciclo'] ?>"
                                                        class="quantidade_faturas_ciclo parcela span1 valores<?= $curso['idcurso'] ?>"
                                                        name="valores[<?= $curso['idcurso'] ?>][quantidade_faturas_ciclo]"
                                                        maxlength="3" type="text"><strong class="strong-right"> x</strong>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="flex">
                                                        <input
                                                            value="<?= $curso['max_parcelas'] ?>"
                                                            class="parcela parcelas_max10 span1 valores<?= $curso['idcurso'] ?>"
                                                            name="valores[<?= $curso['idcurso'] ?>][max_parcelas]"
                                                            maxlength="2"
                                                            type="text"><strong class="strong-right"> x</strong>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?php foreach ($formasPagamento as $indexForma => $forma): ?>
                                                        <div class="check">
                                                            <input
                                                                id="forma_pagamento_<?= $indexForma ?>_<?= $curso['idcurso'] ?>"
                                                                <?= (in_array($indexForma, $formasPagamentoCurso[$curso['idcurso']]) ? 'checked' : '') ?>
                                                                value="<?= $indexForma ?>"
                                                                class="forma_pagamento valores<?= $curso['idcurso'] ?>"
                                                                name="valores[<?= $curso['idcurso'] ?>][forma_pagamento][]"
                                                                type="checkbox"> <label for="forma_pagamento_<?= $indexForma ?>_<?= $curso['idcurso'] ?>"><?= $forma ?></label>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </td>

                                            </tr>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <tr>
                                            <td colspan="3"><?= $idioma["sem_informacao"]; ?></td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                                <div class="form-actions">
                                    <input type="submit" class="btn btn-primary" value="<?= $idioma["btn_salvar"]; ?>">&nbsp;
                                    <input type="reset" class="btn" onclick="MM_goToURL('parent','/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>');" value="<?= $idioma["btn_cancelar"]; ?>" />
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php incluirLib("rodape", $config, $usuario); ?>
    <script type="text/javascript">
        $(".valor_monetario").maskMoney({
            symbol:"R$",
            decimal:",",
            thousands:"."
        });
        $(".parcela").keypress(isNumber);
        $(".parcela").blur(isNumberCopy);

        $('#form-dados').on('submit', function() {
            var retornar = true;

            $('.parcelas_max10').each((i, item) => {
                if ($(item).val() > 10) {
                    alert('A quantidade de parcelas não pode ser maior que 10');
                    retornar = false;
                    setTimeout(() => {
                        $(item).select();
                    }, 250)
                    return false;
                }
            })

            return retornar;
        })
    </script>
</div>
</body>
</html>
