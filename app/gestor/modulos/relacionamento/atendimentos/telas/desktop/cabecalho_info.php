<!-- Cabeçalho -->
<div id="cabecalho_info">
  <div class="row">
    <div class="span4">
      <h3 class="tituloEdicao">
        <?=$idioma["antendimento"];?> # <?=$informacoes['atendimento']["idatendimento"];?>
        <br />
        <small style="text-transform:uppercase;"><?= $idioma["data_abertura"]; ?> <?=formataData($informacoes['atendimento']["data_cad"], "br", 1);?></small>
      </h3>
    </div>
    <div class="span4" style="line-height: 18px;">
      <?=$idioma["dados_matricula_cod_aluno"];?>
      <strong><?= $informacoes["idpessoa"]; ?></strong>
      <br />
      <?=$idioma["dados_matricula_idmatricula"];?>
      <strong><?= $informacoes["idmatricula"] . ' - ' . formataData($informacoes["data_cad"], "br", 1); ?></strong>
    </div>
    <div class="span4" style="line-height: 18px;">
      <?=$idioma["dados_aluno_nome"];?>
      <strong><?= $informacoes["pessoa"]["nome"]; ?></strong>
      <br />
      <?=$idioma["dados_aluno_documento"];?>
      <strong>
    <?php if ($informacoes["pessoa"]["documento_tipo"] == 'cpf') { ?>
      <?php echo str_pad($informacoes["pessoa"]["documento"], 11, "0", STR_PAD_LEFT); ?>
        <?php } else { ?>
      <?php echo str_pad($informacoes["pessoa"]["documento"], 14, "0", STR_PAD_LEFT); ?>
        <?php } ?>
      </strong>
    </div>
    <div class="pull-right" style="padding-right:30px;"><a class="btn btn-mini" href="#topo"><?= $idioma["voltar_ao_topo"]; ?></a></div>
  </div>
</div>

<script>
$(document).ready(function(){
  $(window).scroll(function(){
    if ($(window).scrollTop() > 200) {
      $('#cabecalho_info').fadeIn('slow');
  }else{
      $('#cabecalho_info').fadeOut('slow');
  }
});
});
</script>