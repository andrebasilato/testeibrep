<div class="section section-small">
  <div class="section-header">
    <h5><?php echo $idioma['mural_recados']; ?></h5>
    <ul>
      <li><a href="/<?= $url[0]; ?>/relacionamento/mural/"><?php echo $idioma['ver_todas']; ?></a></li>
      <li><span style="display: none;" class="loading " id="loading-blog"><img alt="Loading" class="loading-img" src="/assets/ajax-loader-e0ae018fdf0b8e9f091987f22637f5c5.gif"></span></li>
    </ul>
  </div>
  <div class="section-body">
    <div id="blog-posts">
      <?php foreach($muralArray as $mural) { ?>
      <h4 class="post-title"> <? if($mural['data_lido'] == ''){ echo "<i class='icon-star'></i>";}?> 
       <a href="/<?= $url[0]; ?>/relacionamento/mural/<?= $mural["idmural"]; ?>/visualizar" <? if($mural['data_lido'] == ''){ ?>style="color:#F00;" <? } ?>>
	   <?php echo $mural['titulo']; ?></a></h4>
      <p> <?php echo tamanhoTexto(250,$mural['resumo'],$html = NULL,$link = NULL); ?> <a href="/<?= $url[0]; ?>/relacionamento/mural/<?= $mural["idmural"]; ?>/visualizar"><?php echo $idioma['leia_mais']; ?></a> <br>
        <small class="muted"><?php echo formataData($mural['data_cad'],'pt',1); ?></small> </p>
      <hr> 
      <?php } ?>           
    </div>
  </div>
</div>