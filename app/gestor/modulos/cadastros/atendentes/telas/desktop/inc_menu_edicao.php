<ul class="nav nav-tabs">
    <li <? if ($url[4] == "editar") { ?> class="active"<? } ?>><a
            href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/editar"><?= $idioma["tab_editar"]; ?></a>
    </li>
    <li<? if ($url[4] == "sindicatos") { ?> class="active"<? } ?>><a
            href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/sindicatos"><?= $idioma["tab_sindicatos"]; ?></a>
    </li>
    <li<? if ($url[4] == "cfc") { ?> class="active"<? } ?>><a
            href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/cfc"><?= $idioma["tab_cfc"]; ?></a>
    </li>
    <li<? if ($url[4] == "contatos") { ?> class="active"<? } ?>><a
            href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/contatos"><?= $idioma["tab_contatos"]; ?></a>
    </li>
    <li<? if ($url[4] == "desativar_login") { ?> class="active"<? } ?>><a
            href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/desativar_login"><?= $idioma["tab_desativar_login"]; ?></a>
    </li>
    <li<? if ($url[4] == "resetar_senha") { ?> class="active"<? } ?>><a
            href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/resetar_senha"><?= $idioma["tab_resetar_senha"]; ?></a>
    </li>
    <li<? if ($url[4] == "bloquear_vendas") { ?> class="active"<? } ?>><a
            href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/bloquear_vendas"><?= $idioma["tab_bloquear_vendas"]; ?></a>
    </li>
    <li <? if ($url[4] == "remover") { ?> class="active"<? } ?>><a
            href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/remover"><?= $idioma["tab_remover"]; ?></a>
    </li>
</ul>