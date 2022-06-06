<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <?php incluirLib("head",$config,$usuario); ?>
  <link href="/assets/css/menuVertical.css" rel="stylesheet" />
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
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $linha["idoferta"]; ?>/editar"><? echo $linha["oferta"]; ?></a> <span class="divider">/</span> </li>
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $linha["idoferta"]; ?>/cursos"><?= $idioma["pagina_titulo_interno"]; ?></a> <span class="divider">/</span></li>
        <li><?php echo $linha["curso"]; ?> <span class="divider">/</span></li>
        <li class="active"><?php echo $idioma["titulo_opcao_remover"]; ?></li>
        <span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
      </ul>
    </section>
    <div class="row-fluid">
      <div class="span12">
        <div class="box-conteudo box-ava">
          <div class="tabbable tabs-left">

            <? if($_POST["msg"]) { ?>
                <div class="alert alert-success fade in">
                    <a href="javascript:void(0);" 
                    class="close" 
                    data-dismiss="alert">×</a>
                    <strong><?= $idioma[$_POST["msg"]]; ?></strong>
                </div>
            <? } ?>

            <div class="tab-pane active" id="tab_editar">
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
            </div>

            <div class="pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"><i class="icon-share-alt"></i> <?= $idioma["btn_sair"]; ?></a></div>
		    <h2 class="tituloEdicao"><?php echo $linha["oferta"]; ?></h2>
            <?php incluirTela("inc_menu_edicao",$config,$linha); ?>
            
            <div class="tab-content">
			<div class="ava-conteudo"> 
              
			             
                        <div class="cabecalho-subsecao">
                        	<div class=" pull-right">
                                <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>" class="btn btn-mini"> 
                                    <?= $idioma["btn_sair_curso"]; ?> 
                                    <i class="icon-remove"></i>
                                </a>
                            </div>
                      		<small><?= $idioma["voce_esta_no_curso"]; ?></small>	
    						<h4 class="tituloEdicao" style="padding-left:0px;">
                                <?= $linha["curso"]; ?> 
                            </h4>
                            <?php include("inc_submenu_cursos.php"); ?>
                        </div>    
                        <h2 class="tituloOpcao">
                            <?= $idioma["titulo_opcao_remover"]; ?>
                        </h2>
                        </br></br>

                        <section>
                            <legend><?= $idioma['folhasRegistro'] ?></legend>
                            <br>
                            <form name="formFolhasRegistro" method="post" action="" enctype="multipart/form-data" class="form-horizontal">
                                <input name="acao" type="hidden" value="salvarFolhaRegistro" />
                                <table>
                                    <tr>
                                        <td style="padding-right:20px;">
                                            <strong>
                                            <?= $idioma['folhasRegistro']; ?>
                                            </strong>
                                        </td>
                                        <td>
                                            <strong>
                                                <?= $idioma["labelPorcentagemMinina"]; ?>
                                            </strong>
                                        </td>
                                        <td>
                                            &nbsp;
                                        </td>
                                        <td>
                                            <strong>
                                                <?= $idioma["labelQtdDias"]; ?>
                                            </strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding-right:20px;">
                                            <select class="span4 inputGrande" name="idfolha" id="folhaRegistro" style="width: 400px;">
                                                <option value=""></option>
                                                <?php foreach ($folhas as $folha) { ?>
                                                    <option <?php if ($linha["idfolha"] == $folha["idfolha"]) { ?> selected <?php } ?> value="<?= $folha["idfolha"]; ?>"><?= $folha["nome"]; ?></option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                        <td>
                                            <input maxlength="2" id="form_porcentagem_minima_disciplinas" value="<?= $linha["porcentagem_minima_disciplinas"] ?>" class="span1 inputGrande" type="text" name="porcentagem_minima_disciplinas" /> %
                                        </td>
                                        <td>
                                            &nbsp;
                                        </td>
                                        <td>
                                            <input maxlength="2" id="form_gerar_quantidade_dias" value="<?= $linha["gerar_quantidade_dias"] ?>" class="span1 inputGrande" type="text" name="gerar_quantidade_dias" /> dias
                                        </td>
                                    </tr>
                                </table>
                                <br />
                                <input id="btn_submit" class="btn btn-primary" type="submit" value="<?= $idioma["btn_salvar"];?>" />
                                <br />
                                <br />
                            </form>
                        </section>
						
						<section id="dados_curso">
                            <legend><?= $idioma['dados_curso'] ?></legend>
                            <small><?= $idioma['explicativo_dados_curso'] ?></small>
                            <br>
                            <form name="form_dados_curso" method="post" action="" enctype="multipart/form-data" class="form-horizontal">
                                <input name="acao" type="hidden" value="salvar_dados_curso" />
                                <table>
                                    <tr>
                                        <td style="padding-right:20px;">
                                            <strong>
                                            <?= $idioma['data_inicio_aula']; ?>
                                            </strong>
                                        </td>
                                    </tr>
                                    <tr>
                                    <td style="padding-right:20px;">
                                        <input id="form_data_inicio_aula" class="span2 inputGrande" type="text" value="<?php echo formataData($linha["data_inicio_aula"],'pt',0); ?>" name="data_inicio_aula" />
                                    </td>
                                    </tr>
                                </table>
                                </br />
                                <input id="btn_submit" class="btn btn-primary" type="submit" value="<?= $idioma["btn_salvar"];?>" />
                                <br />
                            </form>
                        </section>
						<br /><br />
						
                        <section id="provas_presenciais">

                            <legend><?= $idioma['provas'] ?></legend>
                            <small><?= $idioma['explicativo_campos_presenciais'] ?></small>
                            <br><br>
                            <form name="form_provas_presenciais" method="post" action="" 
                            enctype="multipart/form-data" 
                            class="form-horizontal">

                                <input name="acao" type="hidden" 
                                value="salvar_campos_prova_presencial" />
                                <table>
                                    <tr>
                                        <td style="padding-right:20px;">
                                            <strong>
                                            <?= $idioma['porcentagem_minima']; ?>
                                            </strong>
                                        </td>
                                        <td>
                                            <strong>
                                            <?php echo $idioma['qtde_minima_dias']; ?>
                                            </strong>
                                        </td>
                                    </tr>
                                    <tr>
                                    <td style="padding-right:20px;">

                                        <input id="form_porcentagem_minima" 
                                        class="span2 inputGrande" type="text" 
                                        value="<?php if($linha["porcentagem_minima"]) echo number_format($linha["porcentagem_minima"],2,",","."); ?>" 
                                        name="porcentagem_minima">

                                    </td>
                                    <td style="padding-right:20px;">

                                        <input id="form_qtde_minima_dias" 
                                        class="span2 inputGrande" 
                                        type="text" 
                                        value="<?php 
                                            if ($linha["qtde_minima_dias"]) {
                                                echo $linha["qtde_minima_dias"]; 
                                            }
                                        ?>" name="qtde_minima_dias">

                                    </td>
                                    </tr>
                                </table>

                                </br>

                                <input id="btn_submit" 
                                class="btn btn-primary" 
                                type="submit" 
                                value="<?= $idioma["btn_salvar"];?>">

                                <br />
                            </form>
                        </section>
                        <br /><br /><br />
                        <section id="provas_virtuais">
                            <legend><?= $idioma['provas_virtuais'] ?></legend>
                            <small><?= $idioma['explicativo_campos_virtuais'] ?></small>
                            <br>
                            <form name="form_aval_virtuais" method="post" action="" 
                            enctype="multipart/form-data" 
                            class="form-horizontal">

                                <input name="acao" type="hidden" 
                                value="salvar_campos_aval_virtual" />
                                <table>
                                    <tr>
                                        <td style="padding-right:20px;">
                                            <strong>
                                            <?= $idioma['porcentagem_minima']; ?>
                                            </strong>
                                        </td>
                                    </tr>
                                    <tr>
                                    <td style="padding-right:20px;">
                                        <input id="form_porcentagem_minima_virtual" 
                                        class="span2 inputGrande" type="text" 
                                        value="<?php if($linha["porcentagem_minima_virtual"]) echo number_format($linha["porcentagem_minima_virtual"],2,",","."); ?>" 
                                        name="porcentagem_minima_virtual">
                                    </td>
                                    </tr>
                                </table>

                                </br>

                                <input id="btn_submit" 
                                class="btn btn-primary" 
                                type="submit" 
                                value="<?= $idioma["btn_salvar"];?>">

                                <br />
                            </form>
                        </section>

			  </div>
            </div>
          </div>
        </div>
      </div>
    </div>
	<? incluirLib("rodape",$config,$usuario); ?>
    <script type="text/javascript">
		$("#form_data_inicio_aula").mask("99/99/9999");
        $("#form_qtde_minima_dias").keypress(isNumber);
        $("#form_qtde_minima_dias").blur(isNumberCopy);
        $("#form_porcentagem_minima").maskMoney({decimal:",",thousands:".", precision: 2,allowZero: false});
        $("#form_porcentagem_minima_virtual").maskMoney({decimal:",",thousands:".", precision: 2,allowZero: false});
        $("#form_porcentagem_minima_disciplinas").maskMoney({decimal:",",thousands:".", precision: 0,allowZero: false});
        $("#form_gerar_quantidade_dias").maskMoney({decimal:",",thousands:".", precision: 0,allowZero: false});
    </script>
  </div>
</body>
</html>