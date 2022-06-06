<ul class="nav nav-tabs">
  <li <? if($url[4] == "editar") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/editar"><?= $idioma["tab_editar"]; ?></a></li> 
  <?php if($informacoes["tipo"] == "O") { ?>
    <li <? if($url[4] == "perguntaopcoes") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/perguntaopcoes"><?= $idioma["tab_opcoes"]; ?></a></li>
  <?php } ?>
  <li <? if($url[4] == "disciplinas") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/disciplinas"><?= $idioma["tab_disciplinas"]; ?></a></li>  	
  <li <? if($url[4] == "remover") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/remover"><?= $idioma["tab_remover"]; ?></a></li>               
</ul>