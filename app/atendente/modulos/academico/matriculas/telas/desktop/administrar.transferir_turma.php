<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usu_vendedor); ?>
  <style type="text/css">
    .blocoLink small {
      font-size:9px;
    }
    .blocoLink {
      padding-top: 10px !important;
    }

	body {
	min-width: 800px;
	}
	.container-fluid {
		min-width: 800px;
	}
  </style>
  <script type="text/javascript">
    function mudarUrl(objId,targ,restore){ //v9.0
      var selObj = null;
      with (document) {
        if (getElementById) selObj = getElementById(objId);
        if (selObj) {
            var redireciona = selObj.options[selObj.selectedIndex].value;
            if(objId = 'selecionaCurso') {
                redireciona += '#escolas';
            }
            eval(targ+".location='"+redireciona+"'");
        }
        if (restore) selObj.selectedIndex=0;
      }
    }
</script>
</head>
<body>
<?php #incluirLib("topo",$config,$usu_vendedor); ?>
<div class="container-fluid">

  <div class="row-fluid">
    <div class="span12">
	  <legend><?=$idioma["label_titulo"]; ?></legend>
      <div class="box-conteudo" style="padding:35px;">

        <div class="row-fluid">
          <div class="span12">
            <section id="formulario_cpf">

			  <? if(count($matricula["erros"])) { ?>
                <div class="control-group">
                  <div class="row alert alert-error fade in" style="margin:0px;">
                    <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                    <strong><?= $idioma["form_erros"]; ?></strong>
                    <? foreach($matricula["erros"] as $ind => $val) { ?>
                      <br />
                      <?php echo $idioma[$val]; ?>
                    <? } ?>
                  </div>
                </div>
              <? } ?>

			  <?php
				if($salvar['sucesso']) { ?>
					<div class="alert alert-success fade in">
						<a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
						<strong><?= 'Transferência realizada com sucesso!'; ?></strong>
					</div>
					<br /><br />
					<a class="btn btn-large btn-primary" style="color:#FFFFFF" target="_parent" onclick="javascript:window.opener.location.href='/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>';window.close();" >Clique aqui para voltar à matrícula</a>
					<br />
			  <?php exit; } else if ($salvar['mensagem']) { ?>
				<div class="row alert alert-error fade in" style="margin:0px;">
					<strong><?= $idioma["form_erros"]; ?></strong>
					<br />
					<?php echo $idioma[$salvar['mensagem']]; ?>
				</div>
				<br />
			  <?php } ?>

              <? if(!$url[6]) { ?>

                <form style="border-bottom:1px #F4F4F4 solid; padding:15px;" onsubmit="return false;">
				  <legend><?=$idioma["form_oferta_curso"];?></legend>
                  <h2 style="margin-bottom:15px; font-size:30px; line-height:35px;"><?= $curso["nome"]; ?></h2>
                  <legend><?=$idioma["form_oferta"];?></legend>
                  <select name="selecionaOferta" id="selecionaOferta" class="inputGrande">
                    <option value=""><?= $idioma["selecione_oferta"]; ?></option>
                    <? foreach($ofertas as $oferta) { ?>
                      <option <? if($oferta["idoferta"] == $url[4]) { ?> selected="selected" <? } ?> value="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/<?= $oferta["idoferta"]; ?>"><?= $oferta["nome"]; ?></option>
                    <? } ?>
                  </select>
                  <br />
                  <br />
                  <a type="button" class="btn" onclick="mudarUrl('selecionaOferta','parent',1)"><?= $idioma["ver_escolas"];?></a>
                </form>

              <? } elseif($url[6] && !$url[7]) { ?>

                <legend><?=$idioma["form_oferta_curso"];?></legend>
                <h2 style="margin-bottom:15px; font-size:30px; line-height:35px;"><?= $curso["nome"]; ?></h2>
				<span class="pull-right"><small><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>"><?=$idioma["seleciona_outra_oferta"];?></a></small></span>
				<legend><?=$idioma["form_oferta"];?></legend>
                <h2 style="margin-bottom:15px; font-size:30px; line-height:35px;"><?= $oferta["nome"]; ?></h2>
                <legend><?=$idioma["form_oferta_curso_escola"];?></legend>
                <?php if(count($ofertaCursoEscolas) > 0) { ?>
                  <section class="blocoDisponibilidade" id="escolas">
                    <?
                    $i = 0;
                    foreach($ofertaCursoEscolas as $ofertaCursoEscola) {
                    $i++;
                    ?>
                      <? if($i == 1) { ?>
                        <div class="row-fluid">
                      <? } ?>
                        <div class="span3 unidadeDisponibilidade">
                          <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/<?= $url[6]; ?>/<?= $ofertaCursoEscola["idoferta_curso_escola"]; ?>#turmas" class="btn"  rel="tooltip" title="<?= $idioma["iniciar_matricula"]; ?>"  style="color: #FFFFFF; border-color: #006DCC; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); background: #006DCC;">
                            <div class="blocoLink"><?= $ofertaCursoEscola["escola"]; ?></div>
                          </a>
                        </div>
                      <? if($i == 4) { ?>
                        </div>
                        <br />
                        <br />
                        <?
                        $i = 0;
                      } ?>
                    <? } ?>
                    <? if($i <> 0) { echo "</div>"; } ?>
                  </section>
                <? } else { ?>
                  <h5><?= $idioma['nenhuma_escola_disponivel']; ?></h5>
                <? } ?>

              <? } elseif($url[7] && !$url[8]) { ?>

                <legend><?=$idioma["form_oferta_curso"];?></legend>
                <h2 style="margin-bottom:15px; font-size:30px; line-height:35px;"><?= $curso['nome']; ?></h2>
				<span class="pull-right"><small><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>"><?=$idioma["seleciona_outra_oferta"];?></a></small></span>
				<legend><?=$idioma["form_oferta"];?></legend>
                <h2 style="margin-bottom:15px; font-size:30px; line-height:35px;"><?= $oferta["nome"]; ?></h2>
				<span class="pull-right"><small><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/<?= $url[6]; ?>"><?=$idioma["seleciona_outro_escola"];?></a></small></span>
                <legend><?=$idioma["form_oferta_curso_escola"];?></legend>
                <h2 style="margin-bottom:15px; font-size:30px; line-height:35px;"><?= $escola["nome_fantasia"]; ?></h2>
                <legend><?=$idioma["form_oferta_turma"];?></legend>
                <?php if(count($ofertaTurmas) > 0) { ?>
                  <section class="blocoDisponibilidade" id="turmas">
                    <?
                    $i = 0;
                    foreach($ofertaTurmas as $ofertaTurma) {
                    $i++;
                    ?>
                      <? if($i == 1) { ?>
                        <div class="row-fluid">
                      <? } ?>
                        <div class="span3 unidadeDisponibilidade">
                          <?php if($escola["limite"] === '0' || ($escola["limite"] != "" && $ofertaTurma["total_turma"] >= $escola["limite"])) { ?>
                            <a href="javascript:void();" onclick="javascript:alert('Turma completa.')" class="btn"  rel="tooltip" title="<?= $idioma["iniciar_matricula"]; ?>"  style="color: #FFFFFF; border-color: #696969; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); background: #696969;">
                                <div class="blocoLink"><?= $ofertaTurma["nome"]; ?> (<?= $ofertaTurma["total_turma"]; ?>/<?= $escola["limite"]; ?>)</div>
                              </a>
                          <?php } else { ?>
                            <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/<?= $url[6]; ?>/<?= $url[7]; ?>/<?= $ofertaTurma["idturma"]; ?>" class="btn"  rel="tooltip" title="<?= $idioma["iniciar_matricula"]; ?>"  style="color: #FFFFFF; border-color: #006DCC; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); background: #006DCC;">
                                <?php if (is_null($escola["limite"])) { ?>
                                    <div class="blocoLink"><?= $ofertaTurma["nome"]; ?> <?php if (! is_null($ofertaTurma["total_turma"])) { echo '('.$ofertaTurma["total_turma"].')'; }  ?></div>
                                <?php } else { ?>
                                    <div class="blocoLink"><?= $ofertaTurma["nome"]; ?> (<?php echo (int)$ofertaTurma["total_turma"]; ?>/<?= $escola["limite"]; ?>)</div>
                                <?php } ?>
                              </a>
                          <?php } ?>
                        </div>
                      <? if($i == 4) { ?>
                        </div>
                        <br />
                        <br />
                        <?
                        $i = 0;
                      } ?>
                    <? } ?>
                    <? if($i <> 0) { echo "</div>"; } ?>
                  </section>
                <? } else { ?>
                  <h5><?= $idioma['nenhuma_escola_disponivel']; ?></h5>
                <? } ?>

              <?php } else { ?>

				<legend><?=$idioma["form_oferta_curso"];?></legend>
                <h2 style="margin-bottom:15px; font-size:30px; line-height:35px;"><?= $curso['nome']; ?></h2>
				<span class="pull-right"><small><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>"><?=$idioma["seleciona_outra_oferta"];?></a></small></span>
				<legend><?=$idioma["form_oferta"];?></legend>
                <h2 style="margin-bottom:15px; font-size:30px; line-height:35px;"><?= $oferta["nome"]; ?></h2>
				<span class="pull-right"><small><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/<?= $url[6]; ?>"><?=$idioma["seleciona_outro_escola"];?></a></small></span>
                <legend><?=$idioma["form_oferta_curso_escola"];?></legend>
                <h2 style="margin-bottom:15px; font-size:30px; line-height:35px;"><?= $escola["nome_fantasia"]; ?></h2>
				<span class="pull-right"><small><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/<?= $url[6]; ?>/<?= $url[7]; ?>"><?=$idioma["seleciona_outra_turma"];?></a></small></span>
                <legend><?=$idioma["form_oferta_turma"];?></legend>
				<h2 style="margin-bottom:15px; font-size:30px; line-height:35px;"><?= $turma["nome"]; ?></h2>

				<?php
				if ($config['remover_dados_tabelas_transferencias_alunos']) {
				?>
					<div class="control-group">
					  <div class="row alert alert-error fade in" style="margin:0px;">
						<a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
						<strong><?= $idioma["form_dados_removidos"]; ?></strong>
						<br />
						<? foreach($config['remover_dados_tabelas_transferencias_alunos'] as $ind => $val) { ?>
						  <?php echo $idioma[$val]; ?><br />
						<? } ?>
					  </div>
					</div>
				<?php } ?>

				<form method="post" style="border-bottom:1px #F4F4F4 solid; padding-top:15px;">
					<input name="acao" type="hidden" value="transferir_turma_salvar" />
					<input type="submit" class="btn btn-primary btn-large" value="Transferir turma" />
				</form>

			  <?php } ?>


            </section>
          </div>
        </div>
        <div class="clearfix"></div>
      </div>
    </div>
  </div>
  <? #incluirLib("rodape",$config,$usu_vendedor); ?>
</div>
</body>
</html>
