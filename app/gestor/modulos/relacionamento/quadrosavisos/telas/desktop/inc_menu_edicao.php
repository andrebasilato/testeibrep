<ul class="nav nav-tabs">
  <?php if($url[3] != "cadastrar") { ?>
    <li<?php if($url[4] == "editar") { ?> class="active"<? } ?>>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/editar"><?= $idioma["tab_editar"]; ?></a>
    </li>
    <li<?php if($url[4] == "imagens") { ?> class="active"<? } ?>>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/imagens"><?= $idioma["imagens"]; ?></a>
    </li>
   <?php /* <li<?php if($url[4] == "arquivos") { ?> class="active"<? } ?>>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/arquivos"><?= $idioma["arquivos"]; ?></a>
    </li>   
    <li<?php if($url[4] == "preview") { ?> class="active"<? } ?>>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/preview"><?= $idioma["tab_preview"]; ?></a>
    </li> */ ?> 
	<li<? if($url[4] == "quadro_escolas") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/quadro_escolas"><?= $idioma["tab_associar_escola"]; ?></a></li>
	<li<? if($url[4] == "quadro_ofertas") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/quadro_ofertas"><?= $idioma["tab_associar_oferta"]; ?></a></li>
    <li<? if($url[4] == "quadro_cursos") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/quadro_cursos"><?= $idioma["tab_associar_curso"]; ?></a></li>
    <li<?php if($url[4] == "remover") { ?> class="active"<? } ?>>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/remover"><?= $idioma["tab_remover"]; ?></a>
    </li>
  <?php } ?>
</ul>