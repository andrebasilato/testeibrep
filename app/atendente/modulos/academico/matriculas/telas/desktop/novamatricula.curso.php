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
<?php incluirLib("topo",$config,$usu_vendedor); ?>
<div class="container-fluid">
  <section id="global">
    <div class="page-header"><h1><?= $idioma["pagina_titulo"]; ?> &nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1></div>
    <ul class="breadcrumb">
      <li><a href="/<?= $url[0]; ?>"><?= $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
      <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>"><?= $idioma["nav_modulo"]; ?></a> <span class="divider">/</span></li>
      <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"><?= $idioma["pagina_titulo"]; ?></a> <span class="divider">/</span></li>
      <li class="active"><?= $idioma["nav_novamatricula"]; ?></li>
      <span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
    </ul>
  </section>
  <div class="row-fluid">
    <div class="span12">
      <div class="box-conteudo" style="padding:35px;">

          <?php //INTERRUPÇÃO #174911 - ITEM (Informações Gerais) NO MENU INDICADOR DE NAVEGAÇÃO ?>
          <ul id="navegacao_passos" style="margin-bottom:20px;">
              <li class="frist inprogress"><?= $idioma["nav_oferta_curso_escola"]; ?></li>
              <li><?= $idioma["nav_aluno"]; ?><span></span></li>
              <li><?= $idioma["nav_informacoes"]; ?><span></span></li>
              <li class="last"><?= $idioma["nav_concluida"]; ?><span></span></li>
          </ul>

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
              <? if(!$url[4]) { ?>
                <form style="border-bottom:1px #F4F4F4 solid; padding:15px;" onsubmit="return false;">
                  <label for="selecionaOferta"><?= $idioma["form_oferta"];?></label>
                  <select name="selecionaOferta" id="selecionaOferta" class="inputGrande">
                    <option value=""><?= $idioma["selecione_oferta"]; ?></option>
                    <? foreach($ofertas as $oferta) { ?>
                      <option <? if($oferta["idoferta"] == $url[4]) { ?> selected="selected" <? } ?> value="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $oferta["idoferta"]; ?>"><?= $oferta["nome"]; ?></option>
                    <? } ?>
                  </select>
                  <br />
                  <br />
                  <a type="button" class="btn" onclick="mudarUrl('selecionaOferta','parent',1)"><?= $idioma["ver_cursos"];?></a>
                </form>
              <? } elseif($url[4] && !$url[5]) { ?>
                <span class="pull-right"><small><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>"><?=$idioma["seleciona_outra_oferta"];?></a></small></span>
                <legend><?=$idioma["form_oferta"];?></legend>
                <h2 style="margin-bottom:15px; font-size:30px; line-height:35px;"><?= $oferta["nome"]; ?></h2>
                <form style="border-bottom:1px #F4F4F4 solid; padding:15px;" onsubmit="return false;">
                  <label for="selecionaCurso"><?= $idioma["form_oferta_curso"];?></label>
                  <select name="selecionaCurso" id="selecionaCurso" class="inputGrande">
                    <option value=""><?= $idioma["selecione_oferta_curso"]; ?></option>
                    <? foreach($ofertaCursos as $ofertaCurso) { ?>
                      <option <? if($ofertaCurso["idoferta_curso"] == $url[5]) { ?> selected="selected" <? } ?> value="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $ofertaCurso["idoferta_curso"]; ?>"><?= $ofertaCurso["curso"]; ?></option>
                    <? } ?>
                  </select>
                  <br />
                  <br />
                  <a type="button" class="btn" onclick="mudarUrl('selecionaCurso','parent',1)"><?= $idioma["ver_escolas"];?></a>
                </form>
              <? } elseif($url[4] && $url[5] && !$url[6]) { ?>
                <span class="pull-right"><small><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>"><?=$idioma["seleciona_outra_oferta"];?></a></small></span>
                <legend><?=$idioma["form_oferta"];?></legend>
                <h2 style="margin-bottom:15px; font-size:30px; line-height:35px;"><?= $oferta["nome"]; ?></h2>
                <span class="pull-right"><small><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>"><?=$idioma["seleciona_outro_curso"];?></a></small></span>
                <legend><?=$idioma["form_oferta_curso"];?></legend>
                <h2 style="margin-bottom:15px; font-size:30px; line-height:35px;"><?= $ofertaCurso["curso"]; ?></h2>
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
                          <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/<?= $ofertaCursoEscola["idoferta_curso_escola"]; ?>#turmas" class="btn"  rel="tooltip" title="<?= $idioma["iniciar_matricula"]; ?>"  style="color: #FFFFFF; border-color: #006DCC; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); background: #006DCC;">
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
              <? } else { ?>
                <span class="pull-right"><small><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>"><?=$idioma["seleciona_outra_oferta"];?></a></small></span>
                <legend><?=$idioma["form_oferta"];?></legend>
                <h2 style="margin-bottom:15px; font-size:30px; line-height:35px;"><?= $oferta["nome"]; ?></h2>
                <span class="pull-right"><small><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>"><?=$idioma["seleciona_outro_curso"];?></a></small></span>
                <legend><?=$idioma["form_oferta_curso"];?></legend>
                <h2 style="margin-bottom:15px; font-size:30px; line-height:35px;"><?= $ofertaCurso["curso"]; ?></h2>
                <legend><?=$idioma["form_oferta_curso_escola"];?></legend>
                <h2 style="margin-bottom:15px; font-size:30px; line-height:35px;"><?= $escola["nome_fantasia"]; ?></h2>
                <legend><?=$idioma["form_oferta_turma"];?></legend>
                <?php if(count($ofertaCursoEscolas) > 0) { ?>
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
                            <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/<?= $url[6]; ?>/<?= $ofertaTurma["idturma"]; ?>/aluno" class="btn"  rel="tooltip" title="<?= $idioma["iniciar_matricula"]; ?>"  style="color: #FFFFFF; border-color: #006DCC; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); background: #006DCC;">
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
              <?php } ?>
              <?php /*?><form method="post" style="border-bottom:1px #F4F4F4 solid; padding:15px;" action="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/aluno" onsubmit="return validateFields(this, regras)">
                <div class="control-group">
                  <label class="control-label" for="idoferta"><strong><?=$idioma["form_oferta"];?></strong></label>
                  <div class="controls">
                    <select name="idoferta" id="idoferta" class="inputGrande">
                      <option value=""><?= $idioma["selecione_oferta"]; ?></option>
                      <? foreach($ofertas as $oferta) { ?>
                        <option value="<?php echo $oferta["idoferta"]; ?>"><?php echo $oferta["nome"]; ?></option>
                      <? } ?>
                    </select>
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label" for="idoferta_curso"><strong><?= $idioma["form_oferta_curso"]; ?></strong></label>
                  <div class="controls">
                    <select name="idoferta_curso" id="idoferta_curso" class="inputGrande" disabled="disabled">
                      <option value=""><?= $idioma["selecione_oferta"]; ?></option>
                    </select>
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label" for="idoferta_curso_escola"><strong><?= $idioma["form_oferta_curso_escola"]; ?></strong></label>
                  <div class="controls">
                    <select name="idoferta_curso_escola" id="idoferta_curso_escola" class="inputGrande" disabled="disabled">
                      <option value=""><?= $idioma["selecione_oferta"]; ?></option>
                    </select>
                  </div>
                </div>
                <? if(!$_GET["cpf"]) { ?>
                  <div class="control-group">
                    <div class="controls">
                      <input type="submit" class="btn" value="<?=$idioma["btn_buscar"];?>" />
                    </div>
                  </div>
                <? } ?>
              </form><?php */?>
            </section>
          </div>
        </div>
        <div class="clearfix"></div>
      </div>
    </div>
  </div>
  <? incluirLib("rodape",$config,$usu_vendedor); ?>
</div>
</body>
</html>
