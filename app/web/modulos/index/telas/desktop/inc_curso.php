<?php
if ($informacoes['idcurso']) {
    ?>
    <div class="col-sm-4 cardMatricula text-center border">
        <div class="bg" style="background: url(/api/get/imagens/cursos_imagem_exibicao/350/150/<?= $informacoes['imagem_exibicao_servidor']; ?>) no-repeat center center"></div>
        <div class="info">
            <h3><?= $informacoes['nome']; ?></h3>
            <img
            src="/api/get/imagens/escolas_avatar/137/39/<?= $_SESSION['dados_escola']['avatar_servidor']; ?>"
            alt="<?= $_SESSION['dados_escola']['nome']; ?>"
            title="<?= $_SESSION['dados_escola']['nome']; ?>">
            <span>
                <?php ($informacoes['avista'] > 0) ? printf($idioma['total'], number_format($informacoes['avista'], 2, ',' , '.')) : ''; ?>
            </span>
        </div>
    </div>
    <?php
}
?>