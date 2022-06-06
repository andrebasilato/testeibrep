<div class="section section-small">
  <div class="section-header">
    <h5><?php echo $idioma['relatorios']; ?></h5>
<?php /*?>    <ul>
      <li><a href="/<?= $url[0]; ?>/relatorios/relatorio/"><?php echo $idioma['ver_todas']; ?></a></li>
      <li>&nbsp;</li>
    </ul><?php */?>
  </div>
  <div class="section-body">
    <div id="blog-posts">
      <?php foreach($relatorioArray as $relatorio) { ?>
      
      
      
      <a class="btn btn-mini pull-right" href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/?remrel=<?php echo $relatorio['idrelatorio']; ?>" onclick="javascript:return confirm('Deseja realmente excluir o relatório?')" ><i class="icon-remove"></i></a>
      <h4 class="post-title"><?php echo $relatorio['nome']; ?> </h4>
      <a class="btn btn-mini" href="<?= $relatorio["uri"]; ?>" target="_blank" style="margin: 5px 0px;"><?php echo $idioma['gerar']; ?></a>
        <?php /*?><span class="muted"><?php echo formataData($relatorio['data_cad'],'pt',1); ?></span> <?php */?>
      <hr style="margin: 8px 0px;"> 
      <?php } ?>           
    </div>
  </div>
</div>


<p style="color:#999">
Módulo: <?= $url[0]; ?>/<?= $url[1]; ?>
<br />
Gerado: <?= date("H:i:s"); ?>
</p>

