<ul class="nav nav-tabs">
<!--  <li < ? if($url[4] == "resumo") { ?> class="active"< ? } ? >><a href="/< ?= $url[0]; ?>/< ?= $url[1]; ?>/< ?= $url[2]; ?>/< ?= $url[3]; ?>/resumo">< ?= $idioma["tab_resumo"]; ?></a></li>-->
  <li <? if($url[4] == "editar") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/editar"><?= $idioma["tab_editar"]; ?></a></li>
  <li <? if($url[4] == "cursos") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/cursos"><?= $idioma["tab_oferta_cursos"]; ?></a></li>
  <li <? if($url[4] == "cfc") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/cfc"><?= $idioma["tab_oferta_escolas"]; ?></a></li>
  <li <? if($url[4] == "turmas") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/turmas"><?= $idioma["tab_oferta_turmas"]; ?></a></li>
  <li <? if($url[4] == "turmas_sindicatos") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/turmas_sindicatos"><?= $idioma["tab_oferta_turmas_sindicatos"]; ?></a></li>
  <li <? if($url[4] == "cursos_cfc") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/cursos_cfc"><?= $idioma["tab_oferta_cursos_escolas"]; ?></a></li>
  <li <? if($url[4] == "cursos_sindicatos") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/cursos_sindicatos"><?= $idioma["tab_oferta_cursos_sindicatos"]; ?></a></li>
  <li <? if($url[4] == "curriculos_avas") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/curriculos_avas"><?= $idioma["tab_oferta_curriculos_avas"]; ?></a></li>
  <li <? if($url[4] == "imprimir") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/impressao" target="_blank"><?= $idioma["tab_gerar_impressao"]; ?></a></li>
  <li <? if($url[4] == "clonar") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/clonar"><?= $idioma["tab_clonar"]; ?></a></li>
  <li <? if($url[4] == "remover") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/remover"><?= $idioma["tab_remover"]; ?></a></li>
</ul>