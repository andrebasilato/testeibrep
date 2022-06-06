<ul class="nav nav-tabs">
  <li<? if($url[4] == "editar") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/editar"><?= $idioma["tab_editar"]; ?></a></li>   	
  <li<? if($url[4] == "desativar_login") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/desativar_login"><?= $idioma["tab_desativar_login"]; ?></a></li>
  <li<? if($url[4] == "resetar_senha") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/resetar_senha"><?= $idioma["tab_resetar_senha"]; ?></a></li>
  <li<? if($url[4] == "professor_cursos") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/professor_cursos"><?= $idioma["tab_associar_curso"]; ?></a></li>
  <li<? if($url[4] == "professor_avas") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/professor_avas"><?= $idioma["tab_associar_ava"]; ?></a></li>
  <li<? if($url[4] == "professor_ofertas") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/professor_ofertas"><?= $idioma["tab_associar_oferta"]; ?></a></li>
  <li<? if($url[4] == "professor_disciplinas") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/professor_disciplinas"><?= $idioma["tab_associar_disciplina"]; ?></a></li>
  <li<? if($url[4] == "pastavirtual") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/pastavirtual"><?= $idioma["tab_pastavirtual"]; ?></a></li>
  <li<? if($url[4] == "remover") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/remover"><?= $idioma["tab_remover"]; ?></a></li>               
</ul>