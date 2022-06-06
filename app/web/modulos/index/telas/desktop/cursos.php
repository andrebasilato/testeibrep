<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/2000/svg">
<head>
    <?php incluirLib('head', $config, $usuario); ?>
</head>
<body>
    <?php incluirLib('topo', $config, $usuario); ?>

    <div class="container">
        <?php
        if (count($erros) > 0) {
            ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <span><?= $idioma['form_erros']; ?></span>
                <?php
                foreach ($erros as $ind => $val) {
                    echo '<br />' . $idioma[$val];
                }
                ?>
            </div>
            <?php
        }

        if (count($_SESSION['loja_erros']) > 0) {
            ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <span><?= $idioma['form_erros']; ?></span>
                <?php
                foreach ($_SESSION['loja_erros'] as $ind => $val) {
                    echo '<br />' . $idioma[$val];
                }
                ?>
            </div>
            <?php
        }
        unset($_SESSION['loja_erros']);

        if (count($GLOBALS['mensagens']) > 0) {
            ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>
                    <?php
                    foreach ($GLOBALS['mensagens'] as $ind => $val) {
                        echo $idioma[$val] . '<br />';
                    }
                    ?>
                </strong>
            </div>
            <?php
        }
        ?>
        <div class="row">
            <div class="row">
                <?php
                $qnt = 0;
                foreach ($ofertaCursos as $ind => $var) {
                    $qnt++;
                    ?>
                    <div class="col-sm-4 itemPrematricula">
                        <div class="shadow">
                            <div class="bg" style="background: url(/api/get/imagens/cursos_imagem_exibicao/370/150/<?= $var['imagem_exibicao_servidor']; ?>)no-repeat center center"></div>
                            <div class="info">
                                <h3><?= $var['curso']; ?></h3>
                            </div>

                            <?php
                            if ($var['avista'] > 0) {
                                ?>
                                <div class="preco">
                                    <h2>
                                        <?php
                                        if ($var['parcelas'] > 1 && $var['aprazo'] > 0) {
                                            echo sprintf($idioma['em_x_vezes_de'], $var['parcelas']);
                                            echo 'R$ ' . number_format(($var['aprazo'] / $var['parcelas']), 2, ',', '.');
                                        } else {
                                            echo 'R$ ' . number_format($var['avista'], 2, ',', '.');
                                        }
                                        ?>
                                    </h2>
                                    <p>
                                        <?php
                                        if ($var['parcelas'] > 1 && $var['aprazo'] > 0) {
                                            echo sprintf($idioma['valor_a_vista'], number_format(($var['avista']), 2, ',', '.'));
                                        }
                                        ?>
                                    </p>
                                </div>
                                <?php
                            }
                            ?>

                            <div class="botoes">
                                <a
                                class="btBox verde"
                                onclick="matricular(<?= $var['idoferta_curso']; ?>)"
                                style="cursor:pointer;">
                                    <?= $idioma['matricule_se']; ?>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php
                    if ($qnt == 3) {
                        $qnt = 0;
                        echo '</div><div class="row">';
                    }
                }
                ?>
            </div>
        </div>
    </div>
    <form id="form_matricular" method="post">
        <input type="hidden" id="acao" name="acao" value="matricular">
        <input type="hidden" id="idoferta_curso" name="idoferta_curso" value="">
    </form>

    <?php incluirLib('rodape', $config, $usuario); ?>

    <script type="text/javascript">
        function matricular(id) {
            document.getElementById('idoferta_curso').value = id;
            document.getElementById('form_matricular').submit();
        }
    </script>
</body>
</html>