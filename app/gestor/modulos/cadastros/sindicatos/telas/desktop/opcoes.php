<section id="global">
    <div class="page-header"><h1><?= $idioma["opcoes"]; ?> &nbsp;<small><?= $idioma["opcoes_subtitulo"]; ?></small></h1></div>
    <ul class="breadcrumb">
        <li><?= $idioma["usuario_selecionado"]; ?></li>
        <li class="active"><strong><?= $linha["nome"]; ?></strong></li>
    </ul>
    <ul class="nav nav-tabs nav-stacked">
        <li>
            <?php
            if ($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2", NULL)) {
                ?>
                <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $linha['idsindicato']; ?>/editar"> <i class="icon-edit"></i> <?= $idioma['editar']; ?></a>
                <?php
            } else {
                ?>
                <a href="javascript:void(0)" data-original-title="<?= $idioma['opcao_permissao'] ?>"  data-placement="left" rel="tooltip" style="color:#999;">
                    <i class="icon-edit icon-white"></i> <?= $idioma['editar']; ?>
                </a>
                <?php
            }
            ?>
        </li>
        <li>
            <?php
            if ($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|4", NULL)) {
                ?>
                <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $linha['idsindicato']; ?>/acesso_ava"> <i class="icon-off"></i> <?= $idioma['acesso_ava']; ?></a>
                <?php
            } else {
                ?>
                <a href="javascript:void(0)" data-original-title="<?= $idioma['opcao_permissao'] ?>"  data-placement="left" rel="tooltip" style="color:#999;">
                    <i class="icon-off icon-white"></i> <?= $idioma['acesso_ava']; ?>
                </a>
                <?php
            }
            ?>
        </li>
        <li>
            <?php
            if ($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|5", NULL)) {
                ?>
                <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $linha['idsindicato']; ?>/pastavirtual"> <i class="icon-share-alt"></i> <?= $idioma['pastavirtual']; ?></a>
                <?php
            } else {
                ?>
                <a href="javascript:void(0)" data-original-title="<?= $idioma['opcao_permissao'] ?>"  data-placement="left" rel="tooltip" style="color:#999;">
                    <i class="icon-off icon-white"></i> <?= $idioma['pastavirtual']; ?>
                </a>
                <?php
            }
            ?>
        </li>
        <li>
            <?php
            if ($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|8", NULL)) {
                ?>
                <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $linha['idsindicato']; ?>/valores_cursos"> <i class="icon-edit"></i> <?= $idioma['valores_cursos']; ?></a>
                <?php
            } else {
                ?>
                <a href="javascript:void(0)" data-original-title="<?= $idioma['opcao_permissao'] ?>"  data-placement="left" rel="tooltip" style="color:#999;">
                    <i class="icon-edit icon-white"></i> <?= $idioma['valores_cursos']; ?>
                </a>
                <?php
            }
            ?>
        </li>
        <li>
            <?php
            if ($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|3", NULL)) {
                ?>
                <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $linha['idsindicato']; ?>/remover"> <i class="icon-remove"></i> <?= $idioma['remover']; ?></a></li>
                <?php
            } else {
                ?>
                <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;">
                    <i class="icon-edit icon-white"></i> <?= $idioma['remover']; ?>
                </a>
                <?php
            }
            ?>
        </li>
    </ul>
</section>