<ul class="nav nav-tabs">
  <li <? if($url[4] == "deferirdeclaracao") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/deferirdeclaracao"><?= $idioma["tab_deferir"]; ?></a></li>   	
  <li <? if($url[4] == "indeferirdeclaracao") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/indeferirdeclaracao"><?= $idioma["tab_indeferir"]; ?></a></li>               
</ul>