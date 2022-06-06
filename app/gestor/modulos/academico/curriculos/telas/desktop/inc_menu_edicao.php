<ul class="nav nav-tabs">
  <li <? if($url[4] == "resumo") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/resumo"><?= $idioma["tab_resumo"]; ?></a></li>
  <li <? if($url[4] == "dadosgerais") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/dadosgerais"><?= $idioma["tab_dados_gerais"]; ?></a></li>
  <li <? if($url[4] == "avaliacoes") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/avaliacoes"><?= $idioma["tab_avaliacoes"]; ?></a></li>
  <li <? if($url[4] == "blocos") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/blocos"><?= $idioma["tab_blocos"]; ?></a></li>   	
  <li <? if($url[4] == "disciplinas") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/disciplinas"><?= $idioma["tab_disciplinas"]; ?></a></li>
  <li <? if($url[4] == "arquivos_cursos") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/arquivos_cursos"><?= $idioma["tab_arquivos_cursos"]; ?></a></li>
  <li<? if($url[4] == "tipos_notas") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/tipos_notas"><?= $idioma["tab_tipos_notas"]; ?></a></li> 
  <li <? if($url[4] == "remover") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/remover"><?= $idioma["tab_remover"]; ?></a></li>               
</ul>