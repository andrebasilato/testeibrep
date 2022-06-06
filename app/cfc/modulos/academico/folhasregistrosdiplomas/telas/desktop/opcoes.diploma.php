<section id="global">
  <div class="page-header">
    <h1><?php echo $idioma["cancelar_"]; ?> &nbsp;<small><?php echo $idioma["opcoes_subtitulo"]; ?></small></h1>
  </div>
  <ul class="breadcrumb">
    <?php echo $idioma['_cancelar_'];?>
  </ul>
  <ul class="nav nav-tabs nav-stacked">
    <li> <a href="" onclick="$.facebox.close(); return false;"><? echo $idioma["cancelar_rejeitar"]; ?></a> </li>
    <li> <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/diplomas/<? echo $diploma["idfolha_matricula"]; ?>/cancelar"> <? echo $idioma["cancelar_confirmar"]; ?></a> </li>
  </ul>
</section>
