<ul class="nav nav-tabs">
  <?php if($url[3] == "cadastrar") { ?>
    <!-- <li class="active"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/cadastrar"><?= $idioma["tab_cadastrar"]; ?></a></li> -->
  <?php } else { ?>    
    <li<?php if($url[4] == "editar") { ?> class="active"<?php } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/editar"><?= $idioma["tab_editar"]; ?></a></li>    
    <li<?php if($url[4] == "remover") { ?> class="active"<?php } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/remover"><?= $idioma["tab_remover"]; ?></a></li>               
  <?php } ?>
</ul>