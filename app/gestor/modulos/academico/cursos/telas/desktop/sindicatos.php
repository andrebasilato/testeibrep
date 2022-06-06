<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <?php incluirLib("head",$config,$usuario); ?>
  <link rel="stylesheet" href="/assets/plugins/fcbkcomplete/style.css" type="text/css" media="screen" charset="utf-8" />
</head>
<body>
  <? incluirLib("topo",$config,$usuario); ?>
  <div class="container-fluid">
    <section id="global">
      <div class="page-header"><h1><?= $idioma["pagina_titulo"]; ?> &nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1></div>
      <ul class="breadcrumb">
        <li><a href="/<?= $url[0]; ?>"><?= $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>"><?= $idioma["nav_modulo"]; ?></a> <span class="divider">/</span></li>
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"><?= $idioma["pagina_titulo"]; ?></a> <span class="divider">/</span></li>
        <li class="active"><?php echo $linha["nome"]; ?></li>
        <span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
      </ul>
    </section>
    <div class="row-fluid">
      <div class="span12">
        <div class="box-conteudo">
          <div class=" pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"><i class="icon-share-alt"></i> <?= $idioma["btn_sair"]; ?></a></div>
          <h2 class="tituloEdicao"><?= $linha["nome"]; ?></h2>
          <div class="tabbable tabs-left">
			<?php incluirTela("inc_menu_edicao",$config,$usuario); ?>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_editar">
                <h2 class="tituloOpcao"><?= $idioma["titulo_opcao"]; ?></h2>	
                <div id="listagem_informacoes"><?= $idioma["texto_explicativo"]; ?></div>
				<? if($_POST["msg"]) { ?>
                  <div class="alert alert-success fade in">
                    <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                    <strong><?= $idioma[$_POST["msg"]]; ?></strong>
                  </div>
                <? } ?>
                <? if(count($salvar["erros"]) > 0){ ?>
                  <div class="alert alert-error fade in">
                    <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                    <strong><?= $idioma["form_erros"]; ?></strong>
                    <? foreach($salvar["erros"] as $ind => $val) { ?>
                      <br />
                      <?php echo $idioma[$val]; ?>
                    <? } ?>
                  </div>
                <? } ?>   
                <form class="well" method="post" id="form">
                  <p><?= $idioma["form_associar"]; ?></p>
                  <?php if($perfil["permissoes"][$url[2]."|5"]) { ?>    
                    <select id="sindicatos" name="sindicatos"></select>
                    <br />
                    <br />
                    <input type="hidden" id="acao" name="acao" value="associar_sindicato">
                    <input type="submit" class="btn" value="<?= $idioma["btn_adicionar"]; ?>" />
				  <?php } else { ?>
                    <select id="sindicatos" name="sindicatos" disabled="disabled"></select>
                    <br />
                    <br />
                    <a href="javascript:void(0);" rel="tooltip" data-original-title="<?= $idioma["btn_permissao_inserir"]; ?>" data-placement="right" class="btn disabled"><?= $idioma["btn_adicionar"]; ?></a>
                  <?php } ?>
                </form>
                <br />
                <form method="post" id="remover_sindicato" name="remover_sindicato">
                  <input type="hidden" id="acao" name="acao" value="remover_sindicato">
                  <input type="hidden" id="remover" name="remover" value="">
                </form>
                <?php /*<form method="post" id="form_salvar_certificado_diploma" name="form_salvar_certificado_diploma">
                    <input type="hidden" id="acao" name="acao" value="salvar_certificado_diploma"> */?>
                    <?php if(count($associacoesArray) > 0) { ?>
                        <table class="table table-striped tabelaSemTamanho">
                        <?php foreach($associacoesArray as $ind => $associacao) { ?>
                            <?php /*<tr>                                                
                              <td>
                                  Certificado:
                                  <br />
                                  <select id="sindicato_curso[<?php echo $associacao["idcurso_sindicato"]; ?>][informacoes][certificado]" name="sindicato_curso[<?php echo $associacao["idcurso_sindicato"]; ?>][informacoes][certificado]">
                                      <option value="">- Selecione -</option>
                                      <?php foreach($certificados as $certificado) { ?>
                                          <option value="<?php echo $certificado["idcertificado"]; ?>" <?php if($certificado['idcertificado'] == $associacao['idcertificado']) { ?>selected="selected"<?php } ?>><?php echo $certificado["nome"]; ?></option>
                                      <?php } ?>
                                  </select>
                              </td>
                              <td>
                                  Fundamentação:
                                  <br />
                                  <textarea id="sindicato_curso[<?php echo $associacao["idcurso_sindicato"]; ?>][informacoes][fundamentacao]" name="sindicato_curso[<?php echo $associacao["idcurso_sindicato"]; ?>][informacoes][fundamentacao]"><?php echo $associacao["fundamentacao"]; ?></textarea>
                              </td>
                              <td>
								  Fundamentação legal:
                                  <br />
                                  <textarea id="sindicato_curso[<?php echo $associacao["idcurso_sindicato"]; ?>][informacoes][fundamentacao_legal]" name="sindicato_curso[<?php echo $associacao["idcurso_sindicato"]; ?>][informacoes][fundamentacao_legal]"><?php echo $associacao["fundamentacao_legal"]; ?></textarea>
                              </td>
                              <td rowspan="3">
                                <?php if($perfil["permissoes"][$url[2]."|6"]) { ?>
                                <a href="javascript:void(0);" class="btn btn-mini" data-original-title="<?= $idioma["btn_remover"]; ?>" data-placement="left" rel="tooltip" onclick="remover(<?php echo $associacao["idcurso_sindicato"]; ?>)"><i class="icon-remove"></i></a>
                                <?php } else { ?>
                                <a href="javascript:void(0);" class="btn btn-mini disabled" data-original-title="<?= $idioma["btn_remover_permissao_excluir"]; ?>" data-placement="left" rel="tooltip"><i class="icon-remove"></i></a>
                                <?php } ?>
                              </td>
                            </tr>
                            <tr>                                                
                              <td>
								  Autorização:
                                  <br />
                                  <textarea id="sindicato_curso[<?php echo $associacao["idcurso_sindicato"]; ?>][informacoes][autorizacao]" name="sindicato_curso[<?php echo $associacao["idcurso_sindicato"]; ?>][informacoes][autorizacao]"><?php echo $associacao["autorizacao"]; ?></textarea>
                              </td>
                              <td>
								  Perfil:
                                  <br />
                                  <textarea id="sindicato_curso[<?php echo $associacao["idcurso_sindicato"]; ?>][informacoes][perfil]" name="sindicato_curso[<?php echo $associacao["idcurso_sindicato"]; ?>][informacoes][perfil]"><?php echo $associacao["perfil"]; ?></textarea>
                              </td>
                              <td>
								  Regulamento:
                                  <br />
                                  <textarea id="sindicato_curso[<?php echo $associacao["idcurso_sindicato"]; ?>][informacoes][regulamento]" name="sindicato_curso[<?php echo $associacao["idcurso_sindicato"]; ?>][informacoes][regulamento]"><?php echo $associacao["regulamento"]; ?></textarea>
                              </td>
                            </tr>*/?>
							<tr>
								<td width="200"><?php echo $associacao["nome"]; ?></td>
								<td>
									<?php if($perfil["permissoes"][$url[2]."|5"]) { ?>
										<?php /*<a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/editarsindicato/<?php echo $associacao["idcurso_sindicato"]; ?>" class="btn btn-mini" data-original-title="<?= $idioma["btn_editar"]; ?>" data-placement="left" rel="facebox">Editar</a> */?>
										
										<a class="btn btn-mini" href="#editarcursosindicato<?php echo $associacao["idcurso_sindicato"]; ?>" rel="facebox" >Editar</a>
                                        <div id="editarcursosindicato<?php echo $associacao["idcurso_sindicato"]; ?>" style="display:none">
                                            <iframe src="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/editarsindicato/<?php echo $associacao["idcurso_sindicato"]; ?>" width="900" height="500" frameborder="0"></iframe>
                                        </div>
										
									<?php } else { ?>
										<a href="javascript:void(0);" class="btn btn-mini disabled" data-original-title="<?= $idioma["btn_editar_permissao_excluir"]; ?>" data-placement="left" >Editar</a>
									<?php } ?>
								</td>
								<td>
									<?php if($perfil["permissoes"][$url[2]."|6"]) { ?>
									<a href="javascript:void(0);" class="btn btn-mini" data-original-title="<?= $idioma["btn_remover"]; ?>" data-placement="left" rel="tooltip" onclick="remover(<?php echo $associacao["idcurso_sindicato"]; ?>)"><i class="icon-remove"></i></a>
									<?php } else { ?>
									<a href="javascript:void(0);" class="btn btn-mini disabled" data-original-title="<?= $idioma["btn_remover_permissao_excluir"]; ?>" data-placement="left" rel="tooltip"><i class="icon-remove"></i></a>
									<?php } ?>
								</td>
							</tr>
					  <?php } ?>
                      </table>
					<?php } else { ?>
                      <tr>
                        <td colspan="5"><?= $idioma["sem_informacao"]; ?></td>
                      </tr>
					<?php } ?>
                    <div class="form-actions">
                        <input type="submit" class="btn btn-primary" value="<?= $idioma["btn_salvar"]; ?>">
                    </div>
                <?php /*</form>*/?>
              </div>
            </div>
          </div>                           
        </div>
      </div>    	
    </div>
	<? incluirLib("rodape",$config,$usuario); ?>
    <script src="/assets/plugins/fcbkcomplete/jquery.fcbkcomplete.min.js"></script>
    <script type="text/javascript">
	  $(document).ready(function(){                
		$("#sindicatos").fcbkcomplete({
		  json_url: "/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/json/associar_sindicato",
		  addontab: true,
		  height: 10,
		  maxshownitems: 10,
		  cache: true,
		  maxitems: 20,
		  filter_selected: true,
		  firstselected: true,
		  complete_text: "<?= $idioma["mensagem_select"]; ?>",
		  addoncomma: true
		});
	  });
		
	  function remover(id) {
		confirma = confirm('<?= $idioma["confirmar_remocao"]; ?>');
		if(confirma) {
		  document.getElementById("remover").value = id;
		  document.getElementById("remover_sindicato").submit();
		} 
	  }
	</script>
  </div>
</body>
</html>