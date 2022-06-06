<script type="text/javascript">
  function confirmar(){
	 if(confirm('<?=$idioma["confirma_clonar"];?>')){
	 	return true;
	 }
	 return false;
  }
</script>
<ul class="nav nav-tabs">
  <? if($url[3] != "cadastrar") { ?>
    <li<? if($url[4] == "editar") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/editar"><?= $idioma["tab_editar"]; ?></a></li>
    <li<? if($url[4] == "perguntas") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/perguntas"><?= $idioma["tab_perguntas"]; ?></a></li>
	<li<? if($url[4] == "fila") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/fila"><?= $idioma["tab_filtros"]; ?></a></li>
    <li<? if($url[4] == "corpo_email") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/corpo_email"><?= $idioma["tab_corpo_email"]; ?></a></li>
    <li<? if($url[4] == "imagens") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/imagens"><?= $idioma["imagens"]; ?></a></li>
    <li<? if($url[4] == "layout") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/layout"><?= $idioma["layout"]; ?></a></li>    
    <li<? if($url[4] == "preview") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/preview"><?= $idioma["tab_preview"]; ?></a></li>
	<li<? if($url[4] == "alterar_situacao") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/alterar_situacao"><?= $idioma["tab_alterar_situacao"]; ?></a></li>
    <li<? if($url[4] == "clonar") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/clonar" onclick="return confirmar();"><?= $idioma["tab_clonar"]; ?></a></li>
	<li<? if($url[4] == "resultado") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/resultado"><?= $idioma["tab_resultado"]; ?></a></li>
	<li<? if($url[4] == "reenviar") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/reenviar"><?= $idioma["tab_reenviar"]; ?></a></li>
    <li<? if($url[4] == "remover") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/remover"><?= $idioma["tab_remover"]; ?></a></li>
  <? } ?>
</ul>