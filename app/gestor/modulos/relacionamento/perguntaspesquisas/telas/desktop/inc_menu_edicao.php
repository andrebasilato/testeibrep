
<ul class="nav nav-tabs">
  <? if($url[3] != "cadastrar") { ?>
    <li<? if($url[4] == "editar") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/editar"><?= $idioma["tab_editar"]; ?></a></li>
    
    <?php if ($informacoes["tipo"] == 'O') { ?>
        <li<? if($url[4] == "editar_opcoes") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/editar_opcoes"><?= $idioma["tab_editar_opcoes"]; ?></a></li>
        <li<? if($url[4] == "grafico_respostas") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/grafico_respostas"><?= $idioma["tab_grafico_respostas"]; ?></a></li>
    <?php } ?>
    <li<? if($url[4] == "remover") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/remover"><?= $idioma["tab_remover"]; ?></a></li>                
  <? } ?>
</ul>