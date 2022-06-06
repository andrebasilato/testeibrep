<ul class="nav nav-tabs">
    <li <? if ($url[4] == "editarcategoria") { ?> class="active"<? } ?>><a
            href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/editarcategoria"><?= $idioma["tab_editar"]; ?></a>
    </li>
    <li <? if ($url[4] == "removercategoria") { ?> class="active"<? } ?>><a
            href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/removercategoria"><?= $idioma["tab_remover"]; ?></a>
    </li>
</ul>