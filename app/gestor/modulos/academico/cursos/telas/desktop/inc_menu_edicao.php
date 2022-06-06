<ul class="nav nav-tabs">
  <li<? if($url[4] == "editar") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/editar"><?= $idioma["tab_editar"]; ?></a></li>   	
  <li<? if($url[4] == "areas") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/areas"><?= $idioma["tab_curso_areas"]; ?></a></li>	
  <li<? if($url[4] == "sindicatos") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/sindicatos"><?= $idioma["tab_curso_sindicatos"]; ?></a></li>
  <li<? if($url[4] == "emailboasvindas") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/emailboasvindas"><?= $idioma["tab_curso_emailboasvindas"]; ?></a></li>
  <li<? if($url[4] == "ava") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/ava"><?= $idioma["tab_ava"]; ?></a></li>	
  <li<? if($url[4] == "remover") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/remover"><?= $idioma["tab_remover"]; ?></a></li>               
</ul>