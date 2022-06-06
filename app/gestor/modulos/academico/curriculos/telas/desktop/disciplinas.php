<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php incluirLib("head",$config,$usuario); ?>
</head>
<body>
	<? incluirLib("topo",$config,$usuario); ?>
	<div class="container-fluid">
		<section id="global">
			<div class="page-header">
				<h1><?= $idioma["pagina_titulo"]; ?> &nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1>
			</div>
			<ul class="breadcrumb">
				<li><a href="/<?= $url[0]; ?>"><?= $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
				<li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>"><?= $idioma["nav_modulo"]; ?></a> <span class="divider">/</span></li>
				<li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"><?= $idioma["pagina_titulo"]; ?></a> <span class="divider">/</span></li>
				<li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/dadosgerais"><?= $linha["nome"]; ?></a> <span class="divider">/</span></li>
				<li class="active"><?= $idioma["nav_formulario"]; ?></li>
				<span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
			</ul>
		</section>
		<div class="row-fluid">
			<div class="span12">
				<div class="box-conteudo">
					<div class="pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"><i class="icon-share-alt"></i> <?= $idioma["btn_sair"]; ?></a></div>
					<h2 class="tituloEdicao"><?= $linha["nome"]; ?></h2>
					<div class="tabbable tabs-left">
						<?php incluirTela("inc_menu_edicao",$config,$linha); ?>
						<div class="tab-content">
							<div class="tab-pane active" id="tab_editar">
								<h2 class="tituloOpcao"><?= $idioma["titulo_opcao_editar"]; ?></h2>
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
									<?= $idioma[$val]; ?>
									<? } ?>
								</div>
								<? } ?>
								<form class="well wellDestaque form-inline" method="post" onsubmit="return validateFields(this, regras);">
									<table>
										<tr>
											<td><?= $idioma["form_ordem"]; ?></td>
											<td><?= $idioma["form_iddisciplina"]; ?></td>
											<td><?= $idioma["form_idbloco"]; ?></td>
											<td>&nbsp;</td>
										</tr>
										<tr>
											<td><input type="text" class="span1" name="ordem" id="form_ordem" maxlength="3" /></td>
											<td>
												<select class="span4" name="iddisciplina" id="form_iddisciplina">
													<option value=""></option>
													<?php foreach($disciplinas as $disciplina) { ?>
													<option value="<?= $disciplina["iddisciplina"]; ?>"><?= $disciplina["nome"]; ?></option>
													<?php } ?>
												</select>
											</td>
											<td>
												<select class="span4" name="idbloco" id="form_idbloco">
													<option value=""></option>
													<?php foreach($blocos as $bloco) { ?>
													<option value="<?= $bloco["idbloco"]; ?>"><?= $bloco["nome"]; ?></option>
													<?php } ?>
												</select>
											</td>
											<td>
												<input type="hidden" id="acao" name="acao" value="cadastrar_disciplina">
												<input type="submit" class="btn" value="<?= $idioma["btn_adicionar"]; ?>" />
											</td>
										</tr>
									</table>
								</form>
								<form method="post" id="remover_disciplina" name="remover_disciplina">
									<input type="hidden" id="acao" name="acao" value="remover_disciplina">
									<input type="hidden" id="remover" name="remover" value="">
								</form>
								<form method="post" id="editar_disciplina" name="editar_disciplina" onsubmit="return validateFields(this, regras_editar);">
									<input type="hidden" id="acao" name="acao" value="editar_disciplina">
									<?php
									$validacao = "";
									foreach($blocos as $bloco) { ?>
									<h2 class="tituloOpcao"><?= $bloco["ordem"]; ?> - <?= $bloco["nome"]; ?></h2>
									<table class="table table-striped tabelaSemTamanho">
										<thead>
											<tr>
												<th width="60"><?= $idioma["tabela_ordem"]; ?></th>
												<th><?= $idioma["tabela_disciplina"]; ?></th>
												<th><?= $idioma["tabela_horas"]; ?></th>
												<th><?= $idioma["tabela_ignorar_historico"]; ?></th>
												<th><?= $idioma["tabela_contabilizar_media"]; ?></th>
												<th><?= $idioma["tabela_exibir_aptidao"]; ?></th>
												<th><?= $idioma["tabela_nota_conceito"]; ?></th>
												<?php/*<th><?= $idioma["tabela_ava"]; ?></th>*/?>
												<?php
                          /*if(count($avaliacoes) > 0) {
                            foreach($avaliacoes as $avaliacao) {
                              ?>
                              <th width="100"><?= $GLOBALS["tipo_avaliacao"][$GLOBALS["config"]["idioma_padrao"]][$avaliacao["avaliacao"]]; ?></th>
                            <?php } ?>
                            <?php }*/ ?>
                            <th width="60"><?= $idioma["formula"]; ?></th>
                            <th width="60"><?= $idioma["tabela_opcoes"]; ?></th>
                        </tr>
                    </thead>
                    <tbody>
                    	<?php if(count($bloco["disciplinas"]) > 0) {
                    		foreach($bloco["disciplinas"] as $disciplina) {
                    			$validacao .= '$("#ordem'.$disciplina["idbloco_disciplina"].'").keypress(isNumber); $("#ordem'.$disciplina["idbloco_disciplina"].'").blur(isNumberCopy); ';
                    			$validacao .= 'regras_editar.push("required,ordem'.$disciplina["idbloco_disciplina"].','.$idioma["ordem_vazio"].'"); ';
                    			?>
                    			<tr>
                    				<td><input type="text" maxlength="2" class="span1" name="disciplinas[<?= $disciplina["idbloco_disciplina"]; ?>][ordem]" id="ordem<?= $disciplina["idbloco_disciplina"]; ?>" value="<?= $disciplina["ordem"]; ?>" /></td>
                    				<td><?= $disciplina["disciplina"]; ?></td>
                    				<td><input type="text" maxlength="4" class="span1" name="disciplinas[<?= $disciplina["idbloco_disciplina"]; ?>][horas]" id="horas<?= $disciplina["idbloco_disciplina"]; ?>" value="<?= $disciplina["horas"]; ?>" /></td>                              
                    				<td><input type="checkbox" name="disciplinas[<?= $disciplina["idbloco_disciplina"]; ?>][ignorar_historico]" id="ignorar_historico<?= $disciplina["idbloco_disciplina"]; ?>" <?php if($disciplina['ignorar_historico'] == 'S') { ?> checked="checked" <?php } ?> /></td>
                    				<td><input type="checkbox" name="disciplinas[<?= $disciplina["idbloco_disciplina"]; ?>][contabilizar_media]" id="contabilizar_media<?= $disciplina["idbloco_disciplina"]; ?>" <?php if($disciplina['contabilizar_media'] == 'S') { ?> checked="checked" <?php } ?> /></td>
                    				<td><input type="checkbox" name="disciplinas[<?= $disciplina["idbloco_disciplina"]; ?>][exibir_aptidao]" id="exibir_aptidao<?= $disciplina["exibir_aptidao"]; ?>" <?php if($disciplina['exibir_aptidao'] == 'S') { ?> checked="checked" <?php } ?> /></td>
                    				<td><input type="checkbox" name="disciplinas[<?= $disciplina["idbloco_disciplina"]; ?>][nota_conceito]" id="nota_conceito<?= $disciplina["nota_conceito"]; ?>" <?php if($disciplina['nota_conceito'] == 'S') { ?> checked="checked" <?php } ?> /></td>
                              <?php/*<td>
                                <select class="span4" name="disciplinas[<?= $disciplina["idbloco_disciplina"]; ?>][idava]" id="idava<?= $disciplina["idbloco_disciplina"]; ?>">
                                  <option value=""></option>
                                  <?php
                                  $linhaObjAva->Set("limite","-1");
                                  $linhaObjAva->Set("ordem","asc");
                                  $linhaObjAva->Set("ordem_campo","a.nome");
                                  $linhaObjAva->Set("campos","a.*");
                                  $avas = $linhaObjAva->ListarTodasPorDisciplinas($disciplina["iddisciplina"]);
                                  foreach($avas as $ava) { ?>
                                    <option value="<?= $ava["idava"]; ?>" <?php if($ava["idava"] == $disciplina["idava"]) {?>selected="selected"<?php } ?>><?= $ava["nome"]; ?></option>
                                  <?php } ?>
                                </select>
                            </td>*/?>
                            <?php
                              /*if(count($avaliacoes) > 0) {
                                foreach($avaliacoes as $avaliacao) {
                								  $campo = $GLOBALS["campo_peso_avaliacao"][$avaliacao["avaliacao"]];
                								  $validacao .= '$("#'.$campo.$disciplina["idbloco_disciplina"].'").maskMoney({symbol:"",decimal:",",thousands:".",allowZero:true}); ';
                								  $validacao .= 'regras_editar.push("required,'.$campo.$disciplina["idbloco_disciplina"].','.$idioma[$campo."_vazio"].'"); ';
              								  ?>
                                  <td><input type="text" maxlength="5" class="span1" name="disciplinas[<?= $disciplina["idbloco_disciplina"]; ?>][<?= $campo; ?>]" id="<?= $campo.$disciplina["idbloco_disciplina"]; ?>" value="<?php if($disciplina[$campo] || $disciplina[$campo] === "0") { echo number_format($disciplina[$campo], 2, ",", "."); } ?>" /></th>
                                <?php } ?>
                                <?php }*/ ?>
                                <td>
                                	<?php $query = mysql_query('SELECT * FROM formulas_notas WHERE ativo = "S"'); ?>
                                	<select class="span2" id="idformula" name="disciplinas[<?= $disciplina["idbloco_disciplina"]; ?>][idformula]">
                                		<?php while ($row = mysql_fetch_assoc($query)) :?>
                                		<option value="<?= $row['idformula']; ?>" <?php if($row['idformula'] == $disciplina['idformula']) echo 'selected="selected"'; ?> ><?= $row['nome']; ?></option>
                                	<?php endwhile; ?>
                                </select>
                                <td><a href="javascript:void(0);" class="btn btn-mini" data-original-title="<?= $idioma["btn_remover"]; ?>" data-placement="left" rel="tooltip" onclick="remover(<?= $disciplina["idbloco_disciplina"]; ?>)"><i class="icon-remove"></i></a></td>
                            </tr>
                            <?php } ?>
                            <?php } else { ?>
                            <tr>
                            	<td colspan="8"><?= $idioma["sem_informacao"]; ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <?php } ?>
                    <div class="form-actions">
                    	<input class="btn btn-primary" type="submit" value="Salvar">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
</div>
<? incluirLib("rodape",$config,$usuario); ?>
<script type="text/javascript">
function remover(id) {
	confirma = confirm('<?= $idioma["confirmar_remocao"]; ?>');
	if(confirma) {
		document.getElementById("remover").value = id;
		document.getElementById("remover_disciplina").submit();
	}
}
var regras = new Array();
regras.push("required,form_ordem,<?= $idioma["ordem_vazio"]; ?>");
regras.push("required,form_iddisciplina,<?= $idioma["iddisciplina_vazio"]; ?>");
regras.push("required,form_idbloco,<?= $idioma["idbloco_vazio"]; ?>");

var regras_editar = new Array();
jQuery(document).ready(function($) {
	$("#form_ordem").keypress(isNumber);
	$("#form_ordem").blur(isNumberCopy);
	<?= $validacao; ?>
});
</script>
</div>
</body>
</html>