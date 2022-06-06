<ul class="nav nav-tabs">
    <li <? if ($url[4] == "editar") { ?> class="active"<? } ?>><a
            href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/editar"><?= $idioma["tab_editar"]; ?></a>
    </li>
    <li <? if ($url[4] == "matriculas") { ?> class="active"<? } ?>><a
            href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/matriculas"><?= $idioma["tab_matriculas"]; ?></a>
    </li>
    <?php /*<li <? if($url[4] == "historico") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/historico"><?= $idioma["tab_historico"]; ?></a></li> */ ?>
    <li <? if ($url[4] == "remover") { ?> class="active"<? } ?>><a
            href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/remover"><?= $idioma["tab_remover"]; ?></a>
    </li>
</ul>