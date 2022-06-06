<ul class="nav nav-tabs">
    <li <?php if($url[4] == "editar") { ?> class="active"<?php } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/editar"><?= $idioma["tab_editar"]; ?></a></li>
    <li <?php if($url[4] == "remover") { ?> class="active"<?php } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/remover"><?= $idioma["tab_remover"]; ?></a></li>
</ul>
