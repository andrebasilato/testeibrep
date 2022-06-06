<ul class="nav nav-tabs">
  <?php if($url[3] != "cadastrar") { ?>
    <li<?php if($url[4] == "editar") { ?> class="active"<? } ?>>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/editar"><?= $idioma["tab_editar"]; ?></a>
    </li>
    <li<?php if($url[4] == "imagens") { ?> class="active"<? } ?>>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/imagens"><?= $idioma["imagens"]; ?></a>
    </li>
    <li<?php if($url[4] == "arquivos") { ?> class="active"<? } ?>>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/arquivos"><?= $idioma["arquivos"]; ?></a>
    </li>    
    <li<?php if($url[4] == "preview") { ?> class="active"<? } ?>>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/preview"><?= $idioma["tab_preview"]; ?></a>
    </li>
    <li<?php if($url[4] == "fila") { ?> class="active"<? } ?>>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/fila"><?= $idioma["tab_fila"]; ?></a>
    </li>
    <li<?php if($url[4] == "remover") { ?> class="active"<? } ?>>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/remover"><?= $idioma["tab_remover"]; ?></a>
    </li>
  <?php } ?>
</ul>