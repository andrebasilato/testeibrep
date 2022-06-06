<ul class="nav nav-tabs">
<?php
$uri = explode('/', $_SERVER['REQUEST_URI']);

if (isset($uri[6])) {
	$url[3] = (int) $uri[6];
}

$perguntaTabAtiva = array(
	'cadastrar', 
	'novapergunta', 
	'editarpergunta',
	'removerpergunta',
	'perguntaopcoes'
);

?>
  <li <? if ($url[4] == 'editar') { ?> class='active'<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/editar"><?= $idioma["tab_editar"]; ?></a></li>   	
  <li <? if ($url[4] == 'cursos') { ?> class='active'<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/cursos"><?= $idioma["tab_oferta_cursos"]; ?></a></li>
  <li <? if (in_array($url[4], $perguntaTabAtiva)) { ?> class='active'<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/cadastrar"><?= $idioma["tab_perguntas"]; ?></a></li>
  <li <? if ($url[4] == 'remover') { ?> class='active'<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/remover"><?= $idioma["tab_remover"]; ?></a></li>
</ul>