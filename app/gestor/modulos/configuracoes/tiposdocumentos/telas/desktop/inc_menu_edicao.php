<ul class="nav nav-tabs">
    <li <? if ($url[4] == "editar") { ?> class="active"<? } ?>><a
            href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/editar"><?= $idioma["tab_editar"]; ?></a>
    </li>
    <li <? if ($url[4] == "cursos") { ?> class="active"<? } ?>><a
            href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/cursos"><?= $idioma["tab_cursos"]; ?></a>
    </li>
    <li <? if ($url[4] == "sindicatos") { ?> class="active"<? } ?>><a
            href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/sindicatos"><?= $idioma["tab_sindicatos"]; ?></a>
    </li>
    <li <? if ($url[4] == "sindicatos_agendamento") { ?> class="active"<? } ?>><a
            href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/sindicatos_agendamento"><?= $idioma["tab_sindicatos_agendamento"]; ?></a>
    </li>
    <li <? if ($url[4] == "remover") { ?> class="active"<? } ?>><a
            href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/remover"><?= $idioma["tab_remover"]; ?></a>
    </li>
</ul>