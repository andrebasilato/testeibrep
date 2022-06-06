<ul class="nav nav-tabs">
    <li <? if ($url[5] == "editar") { ?> class="active"<? } ?>><a
            href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/editar"><?= $idioma["tab_editar"]; ?></a>
    </li>
    <?php /*<li <? if($url[4] == "quitar") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/quitar"><?= $idioma["tab_quitar"]; ?></a></li>*/ ?>
    <li <? if ($url[5] == "centros_custos") { ?> class="active"<? } ?>><a
            href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/centros_custos"><?= $idioma["tab_centros_custos"]; ?></a>
    </li>
    <li <? if ($url[5] == "historico") { ?> class="active"<? } ?>><a
            href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/historico"><?= $idioma["tab_historico"]; ?></a>
    </li>
	<li<? if($url[5] == "pastavirtual") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/pastavirtual"><?= $idioma["tab_pastavirtual"]; ?></a></li>
    <li <? if ($url[5] == "remover") { ?> class="active"<? } ?>><a
            href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/remover"><?= $idioma["tab_remover"]; ?></a>
    </li>
</ul>