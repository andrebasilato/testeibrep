<ul class="nav nav-tabs">
    <li <?php if($url[4] == "editar") { ?> class="active"<?php } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/editar"><?= $idioma["tab_editar"]; ?></a></li>
    <li <?php if($url[4] == "estados_cidades") { ?> class="active"<?php } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/estados_cidades"><?= $idioma["tab_estados_cidades"]; ?></a></li>
    <li <?php if($url[4] == "mensagens") { ?> class="active"<?php } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/mensagens"><?= $idioma["tab_mensagens"]; ?></a></li>
    <li <?php if($url[4] == "contatos") { ?> class="active"<?php } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/contatos"><?= $idioma["tab_contatos"]; ?></a></li>
    <li <?php if($url[4] == "contratos") { ?> class="active"<?php } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/contratos"><?= $idioma["tab_contratos"]; ?></a></li>
    <li <?php if($url[4] == "acesso_bloqueado") { ?> class="active"<?php } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/acesso_bloqueado"><?= $idioma["tab_acesso_bloqueado"]; ?></a></li>
    <li <?php if($url[4] == "vendas_cfc") { ?> class="active"<?php } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/vendas_cfc"><?= $idioma["tab_vendas_cfc"]; ?></a></li>
    <li <?php if($url[4] == "valores_cursos") { ?> class="active"<?php } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/valores_cursos"><?= $idioma["tab_valores_cursos"]; ?></a></li>
    <li <?php if($url[4] == "pasta_virtual") { ?> class="active"<?php } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/pasta_virtual"><?= $idioma["tab_pasta_virtual"]; ?></a></li>
    <li <?php if($url[4] == "remover") { ?> class="active"<?php } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/remover"><?= $idioma["tab_remover"]; ?></a></li>
</ul>
