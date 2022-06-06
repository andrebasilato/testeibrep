<div class="section section-small">
  <div class="section-header">
    <h5><?php echo $idioma['mural_recados']; ?></h5>
    <ul>
      <li><a href="/<?= $url[0]; ?>/comercial/relacionamentocomercial/"><?php echo $idioma['ver_todas']; ?></a></li>
      <li><span style="display: none;" class="loading " id="loading-blog"><img alt="Loading" class="loading-img" src="/assets/ajax-loader-e0ae018fdf0b8e9f091987f22637f5c5.gif"></span></li>
    </ul>
  </div>
  <div class="section-body">
    <div id="blog-posts">
        <?php foreach($proximasAcoesArray as $relacionamento) { ?>
            <h4 class="post-title">
                Próxima ação : <?php echo formataData($relacionamento['proxima_acao'],'pt',0); ?>
                <a href="/<?= $url[0]; ?>/comercial/relacionamentocomercial/<?= $relacionamento["idrelacionamento"]; ?>/administrar">
                     - <?php echo $relacionamento['nome_pessoa']; ?>
                </a>
            </h4>
            <p>
                <?php echo tamanhoTexto(250,$relacionamento['mensagem'],$html = NULL,$link = NULL); ?> <a href="/<?= $url[0]; ?>/comercial/relacionamentocomercial/<?= $relacionamento["idrelacionamento"]; ?>/administrar"><?php echo $idioma['leia_mais']; ?></a>
            </p>
            <hr> 
        <?php } ?>           
    </div>
  </div>
</div>