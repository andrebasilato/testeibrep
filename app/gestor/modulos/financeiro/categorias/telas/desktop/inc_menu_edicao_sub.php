<ul class="nav nav-tabs">
    <li <? if ($url[4] == "editarsubcategoria") { ?> class="active"<? } ?>><a
            href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/editarsubcategoria"><?= $idioma["tab_editar"]; ?></a>
    </li>
    <li <? if ($url[4] == "associar_subcategoria") { ?> class="active"<? } ?>><a
            href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/associar_subcategoria"><?= $idioma["associar"]; ?></a>
    </li>
      <li <? if ($url[4] == "removersubcategoria") { ?> class="active"<? } ?>><a
            href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/removersubcategoria"><?= $idioma["tab_remover"]; ?></a>
    </li>
</ul>